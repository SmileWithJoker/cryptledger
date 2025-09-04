<?php

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session to access user data
session_start();

// --- Self-Contained Database Connection ---
function pdo_connect_mysql()
{
    $db_host = 'localhost';
    $db_name = 'jotahcom_test'; // REPLACE WITH YOUR DATABASE NAME
    $db_user = 'jotahcom_test'; // REPLACE WITH YOUR DATABASE USERNAME
    $db_pass = 'Ikeotuonye@00'; // REPLACE WITH YOUR DATABASE PASSWORD
    try {
        $conn = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        error_log("Database connection error: " . $e->getMessage());
        throw $e;
    }
}

// Initialize variables with default values to prevent errors
$holdings_data = [];
$transactions_data = [];
$total_value_display = '$0.00';
$total_holdings_count = 0;
$total_change_display = '+0.00%';
$asset_allocation_labels = [];
$asset_allocation_data = [];
$cryptocurrencies_data = [];

// --- Logic to determine if the popup should be shown ---
$show_popup = false;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $shouldShowPopup = true;
    if (isset($_COOKIE['popup_dismissed_' . $user_id])) {
        // Check if the cookie is still valid (less than 14 days old)
        if (time() - $_COOKIE['popup_dismissed_' . $user_id] < 14 * 24 * 60 * 60) {
            $shouldShowPopup = false;
        }
    }
    if ($shouldShowPopup) {
        $show_popup = true;
    }
}
$js_show_popup = json_encode($show_popup);

// --- UPDATED: Fetch live cryptocurrency data from an API and database ---
try {
    $pdo = pdo_connect_mysql();
    // Assuming the 'cryptocurrencies' table now has a 'coingecko_id' column
    $stmt = $pdo->query('SELECT name, symbol, coingecko_id FROM cryptocurrencies LIMIT 20');
    $db_cryptos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get the CoinGecko IDs directly from the database results
    $coingecko_ids = array_column($db_cryptos, 'coingecko_id');
    $ids_string = implode(',', $coingecko_ids);

    $api_url = "https://api.coingecko.com/api/v3/simple/price?ids={$ids_string}&vs_currencies=usd&include_24hr_change=true";
    $response = @file_get_contents($api_url);

    if ($response === false) {
        throw new Exception("Failed to fetch data from CoinGecko API.");
    }

    $live_prices = json_decode($response, true);
    
    if (json_last_error() !== JSON_ERROR_NONE || empty($live_prices)) {
        throw new Exception("Invalid or empty response from CoinGecko API.");
    }

    // Merge database data with live API prices
    $cryptocurrencies_data = [];
    foreach ($db_cryptos as $coin_from_db) {
        $id = $coin_from_db['coingecko_id'] ?? null;
        if ($id && isset($live_prices[$id])) {
            $live_data = $live_prices[$id];
            $cryptocurrencies_data[] = [
                'name' => $coin_from_db['name'],
                'symbol' => $coin_from_db['symbol'],
                'current_price' => $live_data['usd'],
                'change_24h' => $live_data['usd_24h_change'] ?? 0,
            ];
        }
    }
    
} catch (Exception $e) {
    error_log("Error fetching live cryptocurrency data: " . $e->getMessage());
    $cryptocurrencies_data = [];
    echo "<h1>Error fetching live cryptocurrency data. Please check your internet connection and API status.</h1>";
}

// Check if a user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    try {
        $pdo = pdo_connect_mysql();

        // Fetch user's username and email from the database
        $stmt_user = $pdo->prepare("SELECT `username`, `email` FROM `users` WHERE id = ?");
        $stmt_user->execute([$user_id]);
        $user_data = $stmt_user->fetch(PDO::FETCH_ASSOC);
        if ($user_data) {
            $user_display_name = htmlspecialchars($user_data['username']);
            $user_email = htmlspecialchars($user_data['email']);
        }

        // Fetch user's crypto holdings from the database
        // Assuming a 'balance' and 'symbol' column in user_assets table
        $stmt_holdings = $pdo->prepare("SELECT * FROM user_assets WHERE user_id = ?");
        $stmt_holdings->execute([$user_id]);
        $holdings_data = $stmt_holdings->fetchAll(PDO::FETCH_ASSOC);

        // Fetch recent transactions (assuming a 'user_transactions' table exists)
        $stmt_transactions = $pdo->prepare("SELECT * FROM user_transactions WHERE user_id = ? ORDER BY transaction_date DESC LIMIT 10");
        $stmt_transactions->execute([$user_id]);
        $transactions_data = $stmt_transactions->fetchAll(PDO::FETCH_ASSOC);

        // --- Fetch Live Crypto Prices from an API ---
        $coin_symbols = array_column($holdings_data, 'symbol');
        // Map symbols to CoinGecko IDs (this is crucial for a real application)
        $coin_map = [
            'BTC' => 'bitcoin',
            'ETH' => 'ethereum',
            'ADA' => 'cardano',
            'XRP' => 'ripple',
            // Add other coins as needed
        ];

        $coingecko_ids = [];
        foreach ($coin_symbols as $symbol) {
            if (isset($coin_map[$symbol])) {
                $coingecko_ids[] = $coin_map[$symbol];
            }
        }
        $ids_string = implode(',', $coingecko_ids);

        $live_prices = [];
        if (!empty($ids_string)) {
            $api_url = "https://api.coingecko.com/api/v3/simple/price?ids={$ids_string}&vs_currencies=usd&include_24hr_change=true";
            $response = @file_get_contents($api_url);
            if ($response !== false) {
                $live_prices = json_decode($response, true);
            }
        }

        // --- Calculate and Update Portfolio Data ---
        $total_value = 0;
        $total_portfolio_change = 0;
        $temp_holdings_data = []; // Temporary array to build the new data structure

        foreach ($holdings_data as $asset) {
            $coingecko_id = $coin_map[$asset['symbol']] ?? null;
            $live_data = $live_prices[$coingecko_id] ?? null;

            if ($live_data) {
                $live_price = $live_data['usd'];
                $change_24h = $live_data['usd_24h_change'];

                // Calculate current value and total change
                $current_value = $asset['balance'] * $live_price;
                $total_value += $current_value;
                $total_portfolio_change += ($current_value * ($change_24h / 100));

                // Add the new data to the asset array
                $asset['live_price'] = $live_price;
                $asset['current_value'] = $current_value;
                $asset['change_24h'] = $change_24h;
                $temp_holdings_data[] = $asset;

                // Populate data for the asset allocation chart
                $asset_allocation_labels[] = $asset['symbol'];
                $asset_allocation_data[] = $current_value;
            } else {
                // If live data is not available, use placeholders
                $asset['live_price'] = 'N/A';
                $asset['current_value'] = 0;
                $asset['change_24h'] = 0;
                $temp_holdings_data[] = $asset;
            }
        }
        $holdings_data = $temp_holdings_data;

        // Finalize portfolio summary values
        $total_value_display = '$' . number_format($total_value, 2);
        $total_holdings_count = count($holdings_data);
        $total_change_display = number_format($total_portfolio_change, 2) . '%';
        if ($total_portfolio_change >= 0) {
            $total_change_display = '+' . $total_change_display;
        }
    } catch (Exception $e) {
        // Fallback to empty data and log the error for debugging
        error_log("Failed to load dashboard data for user $user_id: " . $e->getMessage());
    }
} else {
    // Fallback for an unauthenticated user
    $user_display_name = 'Guest';
    $user_email = 'Not Logged In';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border: none;
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e0e0e0;
            border-radius: 15px 15px 0 0;
            padding: 20px;
        }
        .card-body {
            padding: 20px;
        }
        .dashboard-header {
            margin-bottom: 30px;
        }
        .stat-card {
            border-radius: 15px;
            padding: 20px;
            text-align: center;
        }
        .stat-card h5 {
            font-size: 1rem;
            color: #888;
        }
        .stat-card h3 {
            font-size: 2rem;
            font-weight: bold;
        }
        .crypto-card {
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .crypto-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        .crypto-card img {
            max-width: 50px;
            height: auto;
        }
        .crypto-price {
            font-weight: bold;
            font-size: 1.2rem;
            color: #121212;
        }
        .crypto-symbol {
            color: #888;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="row dashboard-header align-items-center">
            <div class="col-md-6">
                <h1 class="h2">Dashboard</h1>
            </div>
        </div>

        <!-- Crypto Listings Section -->
        <div class="row">
            <div class="col-12 mb-4">
                <h2 class="h4">Live Cryptocurrency Prices</h2>
            </div>
            <?php
            $count = 0;
            foreach ($cryptocurrencies_data as $coin) {
                // Determine color for daily change
                $change_color = ($coin['change_24h'] >= 0) ? 'text-success' : 'text-danger';
                $change_prefix = ($coin['change_24h'] >= 0) ? '+' : '';

                // Start a new row every 6 cards
                if ($count % 6 == 0) {
                    if ($count > 0) {
                        echo '</div>'; // close previous row
                    }
                    echo '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-6 g-3 mb-4">'; // start new row
                }
                ?>
                <div class="col">
                    <div class="card h-100 crypto-card">
                        <div class="card-body text-center">
                            <!-- Using a generic image for now -->
                            <img src="https://placehold.co/50x50/f0f2f5/a8b9c6?text=<?php echo urlencode($coin['symbol']); ?>" alt="<?php echo htmlspecialchars($coin['name']); ?> logo" class="mb-2 rounded-circle">
                            <h5 class="card-title mb-0"><?php echo htmlspecialchars($coin['name']); ?></h5>
                            <p class="crypto-symbol mb-1"><?php echo htmlspecialchars($coin['symbol']); ?></p>
                            <p class="crypto-price mb-0">
                                $<?php echo number_format($coin['current_price'], 2); ?>
                                <span class="<?php echo $change_color; ?> small">
                                    <?php echo $change_prefix; ?><?php echo number_format($coin['change_24h'], 2); ?>%
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <?php
                $count++;
            }
            // Close the last row if needed
            if ($count > 0 && $count % 6 != 0) {
                echo '</div>';
            }
            ?>
        </div>

        <!-- Remaining sections from original file -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card stat-card bg-primary text-white">
                    <h5>Total Portfolio Value</h5>
                    <h3><?php echo htmlspecialchars($total_value_display); ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card bg-success text-white">
                    <h5>Total Holdings</h5>
                    <h3><?php echo htmlspecialchars($total_holdings_count); ?> Assets</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card bg-info text-white">
                    <h5>Daily Change</h5>
                    <h3><?php echo htmlspecialchars($total_change_display); ?></h3>
                </div>
            </div>
        </div>

        <!-- UPDATED: Asset Allocation now shows holdings as cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Your Asset Holdings</h4>
                    </div>
                    <div class="card-body">
                        <?php if (empty($holdings_data)): ?>
                            <p class="text-center text-muted">You have no assets in your portfolio. Start trading to see your holdings here!</p>
                        <?php else: ?>
                            <div class="row row-cols-1 row-cols-md-2 g-3">
                                <?php foreach ($holdings_data as $asset): ?>
                                    <?php
                                    // Determine color and prefix for daily change
                                    $change_color = ($asset['change_24h'] >= 0) ? 'text-success' : 'text-danger';
                                    $change_prefix = ($asset['change_24h'] >= 0) ? '+' : '';
                                    ?>
                                    <div class="col">
                                        <div class="card h-100 p-3">
                                            <div class="d-flex align-items-center">
                                                <!-- Using a generic image for now -->
                                                <img src="https://placehold.co/50x50/f0f2f5/a8b9c6?text=<?php echo urlencode($asset['symbol']); ?>" alt="<?php echo htmlspecialchars($asset['symbol']); ?> logo" class="rounded-circle me-3">
                                                <div>
                                                    <h5 class="card-title mb-0"><?php echo htmlspecialchars($asset['symbol']); ?></h5>
                                                    <p class="text-muted small mb-1"><?php echo htmlspecialchars($asset['balance']); ?></p>
                                                    <p class="card-text mb-0 fs-5 fw-bold">
                                                        $<?php echo number_format($asset['current_value'], 2); ?>
                                                        <span class="<?php echo $change_color; ?> small ms-2">
                                                            <?php echo $change_prefix; ?><?php echo number_format($asset['change_24h'], 2); ?>%
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Recent Transactions</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Asset</th>
                                        <th>Amount</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($transactions_data)): ?>
                                        <tr><td colspan="5" class="text-center text-muted">No transactions found.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($transactions_data as $transaction): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($transaction['date']); ?></td>
                                                <td><?php echo htmlspecialchars($transaction['type']); ?></td>
                                                <td><?php echo htmlspecialchars($transaction['asset']); ?></td>
                                                <td><?php echo htmlspecialchars($transaction['amount']); ?></td>
                                                <td>$<?php echo htmlspecialchars($transaction['value']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- The popup modal -->
    <div class="modal fade" id="dontShowAgainModal" tabindex="-1" aria-labelledby="dontShowAgainModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="dontShowAgainModalLabel">Welcome!</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            This is a new feature. You can dismiss this message for 14 days.
          </div>
          <div class="modal-footer">
            <div class="form-check me-auto">
              <input class="form-check-input" type="checkbox" value="" id="dontShowAgainCheckbox">
              <label class="form-check-label" for="dontShowAgainCheckbox">
                Don't show this again for 14 days
              </label>
            </div>
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="closeModalButton">OK</button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Pass PHP data to JavaScript
        const shouldShowPopup = <?php echo $js_show_popup; ?>;
        const currentUserId = '<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>';

        // New: Function to handle the popup
        function handlePopup() {
            if (shouldShowPopup && currentUserId) {
                const popupModal = new bootstrap.Modal(document.getElementById('dontShowAgainModal'), {
                    backdrop: 'static', // Prevents modal from closing on outside click
                    keyboard: false // Prevents modal from closing with the escape key
                });
                popupModal.show();

                const closeModalBtn = document.getElementById('closeModalButton');
                const dontShowCheckbox = document.getElementById('dontShowAgainCheckbox');

                closeModalBtn.addEventListener('click', () => {
                    if (dontShowCheckbox.checked) {
                        const date = new Date();
                        // Set cookie to expire in 14 days
                        date.setTime(date.getTime() + (14 * 24 * 60 * 60 * 1000));
                        const expires = "expires=" + date.toUTCString();
                        document.cookie = `popup_dismissed_${currentUserId}=${Date.now()}; ${expires}; path=/; Secure; SameSite=Lax`;
                    }
                });
            }
        }

        // Initialize all components on window load
        window.onload = function () {
            handlePopup();
        };
    </script>
</body>

</html>
