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
        throw $e;
    }
}

// Initialize variables with default values to prevent errors
$holdings_data = [];
$transactions_data = [];
$total_value_display = '$0.00';
$total_holdings_count = 0;
$total_change_display = '+0.00%'; // Placeholder for now

$asset_allocation_labels = ['BTC', 'ETH', 'ADA', 'XRP'];
$asset_allocation_data = [50, 30, 15, 5]; // Percentages


// Check if a user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    try {
        $pdo = pdo_connect_mysql();

        // Fetch user's crypto holdings from the database
        $stmt_holdings = $pdo->prepare("SELECT * FROM user_assets WHERE user_id = ?");
        $stmt_holdings->execute([$user_id]);
        $holdings_data = $stmt_holdings->fetchAll(PDO::FETCH_ASSOC);

        // Fetch recent transactions (assuming a 'user_transactions' table exists)
        $stmt_transactions = $pdo->prepare("SELECT * FROM user_transactions WHERE user_id = ? ORDER BY transaction_date DESC LIMIT 10");
        $stmt_transactions->execute([$user_id]);
        $transactions_data = $stmt_transactions->fetchAll(PDO::FETCH_ASSOC);

        // Calculate portfolio summary values
        $total_value = 0;
        foreach ($holdings_data as $asset) {
            // Assuming the 'value' column stores the USD value of the holding
            $total_value += (float)$asset['value'];
        }
        $total_value_display = '$' . number_format($total_value, 2);
        $total_holdings_count = count($holdings_data);

        // Note: The 24h change requires more complex logic and historical data, so it remains a placeholder
        // until you provide more details on how to calculate it.

    } catch (Exception $e) {
        // Fallback to empty data and log the error for debugging
        error_log("Failed to load dashboard data for user $user_id: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crypto Dashboard</title>
    <!-- Use Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom dark theme CSS to override Bootstrap defaults for a dark look -->
    <style>
        body {
            background-color: #212529;
            color: #f8f9fa;
        }
        .bg-dark-card {
            background-color: #343a40;
        }
        .text-muted-custom {
            color: #adb5bd;
        }
        /* Custom styles to fix the list item border issue */
        .list-unstyled li:not(:last-child) {
            border-bottom: 1px solid #495057 !important;
        }
        .sidebar {
            width: 250px;
            background-color: #1a1e21;
            padding: 2rem 1rem;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            border-right: 1px solid #343a40;
        }
        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }
        .top-nav {
            background-color: #212529;
            padding: 1rem;
            border-bottom: 1px solid #343a40;
        }
        .nav-link.sidebar-link {
            color: #f8f9fa;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            transition: background-color 0.2s ease;
        }
        .nav-link.sidebar-link:hover,
        .nav-link.sidebar-link.active {
            background-color: #343a40;
        }

        @media (max-width: 992px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                border-right: none;
                border-bottom: 1px solid #343a40;
            }
            .main-content {
                margin-left: 0;
            }
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
                <img src="https://placehold.co/40x40/FF9900/ffffff?text=C" alt="Crypto App Logo" class="rounded-circle me-2">
                Wallet
            </h2>
        </div>

        <!-- Main Navigation Links -->
        <ul class="nav flex-column mb-4">
            <li class="nav-item">
                <a class="nav-link sidebar-link active" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-graph-up me-2" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M0 0h1v15h15v1H0z"/>
                        <path d="M7.74 3.754a.5.5 0 1 0-.66-.993l-4 2.666-.33.22c-.114.076-.114.246 0 .322l.33.22 4 2.666a.5.5 0 1 0 .66-.993L4.253 6.99zM.5 14a.5.5 0 0 0 .5.5h14a.5.5 0 0 0 0-1H1a.5.5 0 0 0-.5.5z"/>
                        <path d="M11 6a.5.5 0 1 0 .66-.993l-4-2.667-.33-.22c-.114-.076-.114-.246 0-.322l.33-.22 4-2.667a.5.5 0 1 0-.66-.993L7.753 1.99zM7.25 10a.5.5 0 1 0-.66-.993l-4 2.666-.33.22c-.114.076-.114.246 0 .322l.33.22 4 2.666a.5.5 0 1 0 .66-.993L4.253 12.99zM.5 14a.5.5 0 0 0 .5.5h14a.5.5 0 0 0 0-1H1a.5.5 0 0 0-.5.5z"/>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link sidebar-link" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-wallet2 me-2" viewBox="0 0 16 16">
                        <path d="M12.136.326A1.5 1.5 0 0 0 10.518 0H1.5A1.5 1.5 0 0 0 0 1.5v12A1.5 1.5 0 0 0 1.5 15h12a1.5 1.5 0 0 0 1.5-1.5V6.764a1.5 1.5 0 0 0-.326-1.554zM7 13.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                        <path d="M12.136.326A1.5 1.5 0 0 0 10.518 0H1.5A1.5 1.5 0 0 0 0 1.5v12A1.5 1.5 0 0 0 1.5 15h12a1.5 1.5 0 0 0 1.5-1.5V6.764a1.5 1.5 0 0 0-.326-1.554zM10.518 1H1.5A.5.5 0 0 1 1 1.5v12a.5.5 0 0 1 .5.5h12a.5.5 0 0 1 .5-.5V6.764a.5.5 0 0 0-.326-.474L11.5 6.764V1.5a.5.5 0 0 1 .5.5v4.5a.5.5 0 0 0 1 0V2a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 1 0V1.5a.5.5 0 0 1 .5-.5z"/>
                    </svg>
                    Connect Wallet
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link sidebar-link" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-wallet2 me-2" viewBox="0 0 16 16">
                        <path d="M12.136.326A1.5 1.5 0 0 0 10.518 0H1.5A1.5 1.5 0 0 0 0 1.5v12A1.5 1.5 0 0 0 1.5 15h12a1.5 1.5 0 0 0 1.5-1.5V6.764a1.5 1.5 0 0 0-.326-1.554zM7 13.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                        <path d="M12.136.326A1.5 1.5 0 0 0 10.518 0H1.5A1.5 1.5 0 0 0 0 1.5v12A1.5 1.5 0 0 0 1.5 15h12a1.5 1.5 0 0 0 1.5-1.5V6.764a1.5 1.5 0 0 0-.326-1.554zM10.518 1H1.5A.5.5 0 0 1 1 1.5v12a.5.5 0 0 1 .5.5h12a.5.5 0 0 1 .5-.5V6.764a.5.5 0 0 0-.326-.474L11.5 6.764V1.5a.5.5 0 0 1 .5.5v4.5a.5.5 0 0 0 1 0V2a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 1 0V1.5a.5.5 0 0 1 .5-.5z"/>
                    </svg>
                    Connect Wallet
                </a>
            </li>
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
        <nav class="top-nav d-flex justify-content-end align-items-center mb-4 rounded-3 shadow-lg">
            <span class="me-3 d-none d-md-inline">Welcome, <span class="fw-bold"><?php echo  $_SESSION['usernamee']; ?></span></span>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Profile
                </button>
                <ul class="dropdown-menu dropdown-menu-dark">
                    <li><a class="dropdown-item" href="#">Account Settings</a></li>
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-caret-up-fill me-1" viewBox="0 0 16 16">
                                    <path d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592c.859 0 1.319-1.012.753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z"/>
                                </svg>
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
                        <ul id="holdingsList" class="list-unstyled">
                            <!-- Holding items will be populated by JavaScript -->
                        </ul>
                    </div>
                </div>

                <!-- Recent Transactions List -->
                <div class="col-12 col-lg-6">
                    <div class="bg-dark-card rounded-4 p-4 shadow-lg">
                        <h2 class="h4 fw-bold mb-4">Recent Transactions</h2>
                        <ul id="transactionsList" class="list-unstyled">
                            <!-- Transaction items will be populated by JavaScript -->
                        </ul>
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


        // Function to render the Asset Allocation Chart (Pie Chart)
        function renderAssetAllocationChart() {
            const ctx = document.getElementById('assetAllocationChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: assetAllocationLabels,
                    datasets: [{
                        data: assetAllocationData,
                        backgroundColor: ['#FFC107', '#0dcaf0', '#0d6efd', '#212529'], // Bootstrap colors
                        borderColor: '#343a40'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true, position: 'bottom', labels: { color: '#adb5bd' } },
                        tooltip: { backgroundColor: '#343a40', titleColor: '#f8f9fa', bodyColor: '#adb5bd' }
                    }
                }
            });
        }


        // Function to render holdings
        function renderHoldings() {
            const list = document.getElementById('holdingsList');
            list.innerHTML = '';
            holdings.forEach(item => {
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
                        <p class="fw-medium mb-0">$${item.value || '0.00'}</p>
                        <p class="text-sm ${item.change && item.change.startsWith('+') ? 'text-success' : 'text-danger'} mb-0">${item.change || 'N/A'}</p>
                    </div>
                `;
                list.appendChild(listItem);
            });
        }

        // Function to render transactions
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
                    </div>
                `;
                list.appendChild(listItem);
            });
        }

        // Initialize all components on window load
        window.onload = function() {
            renderAssetAllocationChart();
            renderHoldings();
            renderTransactions();
        };
    </script>
</body>
</html>
