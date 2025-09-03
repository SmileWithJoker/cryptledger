<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session to access user data
session_start();

// --- Self-Contained Database Connection ---
function pdo_connect_mysql() {
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
        // For a public-facing page, don't show the detailed error.
        // throw new Exception("Could not connect to the database."); 
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
$user_display_names = '?';
$user_emails = '?';

// Check if a user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    try {
        $pdo = pdo_connect_mysql();

        // Check if PDO connection was successful
        if ($pdo) {
            // Fetch user's username and email from the database
            $stmt_user = $pdo->prepare("SELECT `username`, `email` FROM `users` WHERE id = ?");
            $stmt_user->execute([$user_id]);
            $user_data = $stmt_user->fetch(PDO::FETCH_ASSOC);
            if ($user_data) {
                $user_display_name = htmlspecialchars($user_data['username']);
                $user_email = htmlspecialchars($user_data['email']);
            }

            // Fetch user's crypto holdings from the database
            $stmt_holdings = $pdo->prepare("SELECT * FROM user_assets WHERE user_id = ?");
            $stmt_holdings->execute([$user_id]);
            $holdings_data = $stmt_holdings->fetchAll(PDO::FETCH_ASSOC);

            // Fetch recent transactions
            $stmt_transactions = $pdo->prepare("SELECT * FROM user_transactions WHERE user_id = ? ORDER BY transaction_date DESC LIMIT 10");
            $stmt_transactions->execute([$user_id]);
            $transactions_data = $stmt_transactions->fetchAll(PDO::FETCH_ASSOC);

            // --- Fetch Live Crypto Prices from an API ---
            $coin_symbols = array_column($holdings_data, 'symbol');
            $coin_map = [
                'BTC' => 'bitcoin',
                'ETH' => 'ethereum',
                'ADA' => 'cardano',
                'XRP' => 'ripple',
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
            $temp_holdings_data = [];

            foreach ($holdings_data as $asset) {
                $coingecko_id = $coin_map[$asset['symbol']] ?? null;
                $live_data = $live_prices[$coingecko_id] ?? null;

                if ($live_data) {
                    $live_price = $live_data['usd'];
                    $change_24h = $live_data['usd_24h_change'];
                    $current_value = $asset['balance'] * $live_price;
                    $total_value += $current_value;
                    $total_portfolio_change += ($current_value * ($change_24h / 100));
                    $asset['live_price'] = $live_price;
                    $asset['current_value'] = $current_value;
                    $asset['change_24h'] = $change_24h;
                } else {
                    $asset['live_price'] = 'N/A';
                    $asset['current_value'] = 0;
                    $asset['change_24h'] = 0;
                }
                $temp_holdings_data[] = $asset;
                $asset_allocation_labels[] = $asset['symbol'];
                $asset_allocation_data[] = $asset['current_value'];
            }
            $holdings_data = $temp_holdings_data;
            
            // Finalize portfolio summary values
            $total_value_display = '$' . number_format($total_value, 2);
            $total_holdings_count = count($holdings_data);
            $total_change_display = number_format($total_portfolio_change, 2) . '%';
            if ($total_portfolio_change >= 0) {
                $total_change_display = '+' . $total_change_display;
            }

        } else {
            error_log("Failed to create PDO connection.");
        }

    } catch (Exception $e) {
        error_log("Failed to load dashboard data for user $user_id: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Use Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.hugeicons.com/font/icons.css">
    <!-- Custom dark theme CSS to override Bootstrap defaults for a dark look -->
    <style>
        body { background-color: #212529; color: #f8f9fa; }
        .bg-dark-card { background-color: #343a40; }
        .text-muted-custom { color: #adb5bd; }
        .list-unstyled li:not(:last-child) { border-bottom: 1px solid #495057 !important; }
        .sidebar { width: 250px; background-color: #1a1e21; padding: 2rem 1rem; position: fixed; height: 100vh; overflow-y: auto; border-right: 1px solid #343a40; }
        .main-content { margin-left: 250px; padding: 2rem; }
        .top-nav { background-color: #212529; padding: 1rem; border-bottom: 1px solid #343a40; }
        .nav-link.sidebar-link { color: #f8f9fa; border-radius: 0.5rem; padding: 0.75rem 1rem; transition: background-color 0.2s ease; }
        .nav-link.sidebar-link:hover, .nav-link.sidebar-link.active { background-color: #343a40; }
        @media (max-width: 992px) {
            .sidebar { position: relative; width: 100%; height: auto; border-right: none; border-bottom: 1px solid #343a40; }
            .main-content { margin-left: 0; }
        }
        .dropdown-menu-custom {
            background-color: #343a40 !important;
            border-color: #495057 !important;
        }
        .dropdown-menu-custom .dropdown-item {
            color: #f8f9fa !important;
        }
        .dropdown-menu-custom .dropdown-item:hover {
            background-color: #495057 !important;
        }
    </style>
    <!-- Chart.js for all graphs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-dark text-white">

<!-- Sidebar -->
<aside class="sidebar d-flex flex-column">
    <!-- Logo and App Name -->
    <div class="d-flex align-items-center mb-5">
        <h2 class="h4 fw-bold mb-0">
            <img src="https://placehold.co/40x40/FF9900/ffffff?text=W" alt="Wallet" class="rounded-circle me-2">
            Wallet
        </h2>
    </div>

    <!-- Main Navigation Links -->
    <ul class="nav flex-column mb-4">
        <li class="nav-item"><a class="nav-link sidebar-link active" href="#">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link sidebar-link" href="#">Connect Wallet</a></li>
        <li class="nav-item"><a class="nav-link sidebar-link" href="#">Buy</a></li>
        <li class="nav-item"><a class="nav-link sidebar-link" href="#">Withdraw</a></li>
    </ul>

    <!-- Connected Wallets Section -->
    <div class="mt-auto pt-4 border-top border-secondary">
        <p class="text-sm fw-semibold text-muted-custom mb-2">Wallets Connected</p>
        <ul class="nav flex-column">
            <li class="nav-item d-flex align-items-center mb-2">
                <div class="rounded-circle bg-success p-1 me-2" style="width: 10px; height: 10px;"></div>
                <span class="text-sm">MetaMask</span>
            </li>
            <li class="nav-item d-flex align-items-center">
                <div class="rounded-circle bg-success p-1 me-2" style="width: 10px; height: 10px;"></div>
                <span class="text-sm">Trust Wallet</span>
            </li>
        </ul>
    </div>
</aside>

<!-- Main Content Wrapper -->
<div class="main-content">
    <!-- Top Navigation Bar -->
    <nav class="top-nav d-flex justify-content-between align-items-center mb-4 rounded-3 shadow-lg">
        <span class="me-3 d-none d-md-inline">Welcome, <span id="welcome-username" class="fw-bold"><?php echo $user_display_name; ?></span></span>
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Profile
            </button>
            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-custom">
                <li><a class="dropdown-item" href="#" id="update-profile-btn">Update Profile</a></li>
                <li><a class="dropdown-item disabled text-muted-custom" href="#" id="profile-email"><?php echo $user_email; ?></a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Log Out</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Dashboard Content -->
    <div class="bg-dark-card rounded-4 shadow-lg p-4 p-md-5 mb-5">
        <h1 class="display-5 fw-bold mb-2">My Crypto Portfolio</h1>
        <p class="text-muted-custom mb-4">A sleek and modern overview of your digital assets.</p>

        <!-- Portfolio Summary Section -->
        <div class="row g-4 mb-4">
            <!-- Total Value Card -->
            <div class="col-12 col-md-4">
                <div class="bg-dark p-4 rounded-3 shadow-sm">
                    <p class="text-sm fw-semibold text-muted-custom mb-1">Total Portfolio Value</p>
                    <div class="d-flex align-items-center">
                        <span class="fs-2 fw-bold text-success"><?php echo htmlspecialchars($total_value_display); ?></span>
                        <span class="ms-3 text-success fw-semibold d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-caret-up-fill me-1" viewBox="0 0 16 16"><path d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592c.859 0 1.319-1.012.753-1-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z"/></svg>
                            <?php echo htmlspecialchars($total_change_display); ?>
                        </span>
                    </div>
                </div>
            </div>
            <!-- Holdings and Performance Cards -->
            <div class="col-12 col-md-4">
                <div class="bg-dark p-4 rounded-3 shadow-sm">
                    <p class="text-sm fw-semibold text-muted-custom mb-1">Total Holdings</p>
                    <p class="fs-2 fw-bold"><?php echo htmlspecialchars($total_holdings_count); ?></p>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="bg-dark p-4 rounded-3 shadow-sm">
                    <p class="text-sm fw-semibold text-muted-custom mb-1">24h Change</p>
                    <p class="fs-2 fw-bold text-success"><?php echo htmlspecialchars($total_change_display); ?></p>
                </div>
            </div>
        </div>

        <!-- Multiple Charts Section -->
        <div class="row g-4 mb-5">
            <!-- Asset Allocation Chart -->
            <div class="col-12 col-lg-3 col-md-6">
                <div class="bg-dark p-3 rounded-3 shadow-sm">
                    <h2 class="h6 fw-semibold mb-2 text-center">Asset Allocation</h2>
                    <canvas id="assetAllocationChart" style="max-height: 200px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Holdings and Transactions Section -->
        <div class="row g-4">
            <!-- Top Holdings List -->
            <div class="col-12 col-lg-6">
                <div class="bg-dark-card rounded-4 p-4 shadow-lg">
                    <h2 class="h4 fw-bold mb-4">Your Holdings</h2>
                    <ul id="holdingsList" class="list-unstyled"></ul>
                </div>
            </div>

            <!-- Recent Transactions List -->
            <div class="col-12 col-lg-6">
                <div class="bg-dark-card rounded-4 p-4 shadow-lg">
                    <h2 class="h4 fw-bold mb-4">Recent Transactions</h2>
                    <ul id="transactionsList" class="list-unstyled"></ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and Popper -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script>
    // PHP-injected data
    const assetAllocationLabels = <?php echo json_encode($asset_allocation_labels); ?>;
    const assetAllocationData = <?php echo json_encode($asset_allocation_data); ?>;
    const holdings = <?php echo json_encode($holdings_data); ?>;
    const transactions = <?php echo json_encode($transactions_data); ?>;

    function renderAssetAllocationChart() {
        const ctx = document.getElementById('assetAllocationChart').getContext('2d');
        const dataColors = ['#FFC107', '#0dcaf0', '#0d6efd', '#212529'];
        const colors = assetAllocationData.map((_, index) => dataColors[index % dataColors.length]);
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: assetAllocationLabels,
                datasets: [{
                    data: assetAllocationData,
                    backgroundColor: colors,
                    borderColor: '#343a40'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, position: 'bottom', labels: { color: '#adb5bd' } },
                    tooltip: { 
                        backgroundColor: '#343a40', 
                        titleColor: '#f8f9fa', 
                        bodyColor: '#adb5bd',
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                if (label) {
                                    let sum = 0;
                                    let dataArr = context.chart.data.datasets[0].data;
                                    sum = dataArr.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.raw / sum) * 100).toFixed(2) + '%';
                                    return `${label}: $${context.raw.toFixed(2)} (${percentage})`;
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

    function renderHoldings() {
        const list = document.getElementById('holdingsList');
        list.innerHTML = '';
        holdings.forEach(item => {
            const isPositive = item.change_24h >= 0;
            const changeClass = isPositive ? 'text-success' : 'text-danger';
            const arrowSvg = isPositive ? `
                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-caret-up-fill me-1" viewBox="0 0 16 16"><path d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592c.859 0 1.319-1.012.753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z"/></svg>` : `
                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-caret-down-fill me-1" viewBox="0 0 16 16"><path d="M7.247 11.14 2.451 5.66c-.566-.647-.106-1.659.753-1.659h9.592c.859 0 1.319 1.012.753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/></svg>`;
            
            const listItem = document.createElement('li');
            listItem.className = 'd-flex align-items-center justify-content-between py-3';
            listItem.innerHTML = `
                <div class="d-flex align-items-center">
                    <img src="https://placehold.co/40x40/FF9900/ffffff?text=${item.symbol || '?'}" alt="${item.name || 'Unknown'} icon" class="rounded-circle me-3" style="width: 40px; height: 40px;">
                    <div>
                        <p class="fw-medium mb-0">${item.name || 'N/A'}</p>
                        <p class="text-sm text-muted-custom mb-0">${item.symbol || 'N/A'}</p>
                    </div>
                </div>
                <div class="text-end">
                    <p class="fw-medium mb-0">$${item.current_value ? item.current_value.toFixed(2) : 'N/A'}</p>
                    <p class="text-sm ${changeClass} mb-0">${arrowSvg} ${item.change_24h ? item.change_24h.toFixed(2) + '%' : 'N/A'}</p>
                </div>`;
            list.appendChild(listItem);
        });
    }

    function renderTransactions() {
        const list = document.getElementById('transactionsList');
        list.innerHTML = '';
        transactions.forEach(item => {
            const listItem = document.createElement('li');
            listItem.className = 'd-flex align-items-center justify-content-between py-3';
            listItem.innerHTML = `
                <div class="d-flex align-items-center">
                    <span class="text-sm fw-semibold p-2 rounded-circle me-3 ${item.type === 'Buy' ? 'bg-success-subtle text-success' : item.type === 'Sell' ? 'bg-danger-subtle text-danger' : 'bg-info-subtle text-info'}" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">${(item.type || '?').charAt(0)}</span>
                    <div>
                        <p class="fw-medium mb-0">${item.type || 'N/A'} ${item.amount || ''}</p>
                        <p class="text-sm text-muted-custom mb-0">${item.date || 'N/A'}</p>
                    </div>
                </div>
                <div class="text-end">
                    <p class="fw-medium text-white mb-0">${item.value || 'N/A'}</p>
                    <p class="text-sm text-success mb-0">${item.status || 'N/A'}</p>
                </div>`;
            list.appendChild(listItem);
        });
    }
    
    // Function to refresh user data from the server
    async function refreshUserData() {
        try {
            const response = await fetch(window.location.href + '?ajax=1');
            if (!response.ok) throw new Error('Network response was not ok');
            const data = await response.json();
            document.getElementById('welcome-username').textContent = data.user_display_name;
            document.getElementById('profile-email').textContent = data.user_email;
            console.log("User data refreshed successfully.");
        } catch (error) {
            console.error('Failed to refresh user data:', error);
        }
    }

    // Initialize all components on window load
    window.onload = function() {
        renderAssetAllocationChart();
        renderHoldings();
        renderTransactions();

        // Add event listener for the update button in the dropdown
        const updateProfileBtn = document.getElementById('update-profile-btn');
        if (updateProfileBtn) {
            updateProfileBtn.addEventListener('click', (event) => {
                event.preventDefault();
                // This is where you would typically show a modal for profile updates
                // For this example, we'll just simulate a data refresh
                alert("Simulating profile update. Refreshing data...");
                refreshUserData();
            });
        }
    };
</script>
</body>
</html>
