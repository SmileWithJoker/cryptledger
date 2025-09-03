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

    $total_change_display = '+0.00%';

    $asset_allocation_labels = [];

    $asset_allocation_data = [];



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

    <title>Crypto Dashboard</title>

    <!-- Use Bootstrap CSS CDN -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://use.hugeicons.com/font/icons.css">

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

    <img src="https://placehold.co/40x40/FF9900/ffffff?text=W" alt="Wallet" class="rounded-circle me-2">

    Wallet

    </h2>

    </div>



    <!-- Main Navigation Links -->

    <ul class="nav flex-column mb-4">

    <li class="nav-item">

    <a class="nav-link sidebar-link active" href="#">

    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" color="#ffffff" fill="212429">

    <path d="M13.6903 19.4567C13.5 18.9973 13.5 18.4149 13.5 17.25C13.5 16.0851 13.5 15.5027 13.6903 15.0433C13.944 14.4307 14.4307 13.944 15.0433 13.6903C15.5027 13.5 16.0851 13.5 17.25 13.5C18.4149 13.5 18.9973 13.5 19.4567 13.6903C20.0693 13.944 20.556 14.4307 20.8097 15.0433C21 15.5027 21 16.0851 21 17.25C21 18.4149 21 18.9973 20.8097 19.4567C20.556 20.0693 20.0693 20.556 19.4567 20.8097C18.9973 21 18.4149 21 17.25 21C16.0851 21 15.5027 21 15.0433 20.8097C14.4307 20.556 13.944 20.0693 13.6903 19.4567Z" stroke="#141B34" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="round" />

    <path d="M13.6903 8.95671C13.5 8.49728 13.5 7.91485 13.5 6.75C13.5 5.58515 13.5 5.00272 13.6903 4.54329C13.944 3.93072 14.4307 3.44404 15.0433 3.1903C15.5027 3 16.0851 3 17.25 3C18.4149 3 18.9973 3 19.4567 3.1903C20.0693 3.44404 20.556 3.93072 20.8097 4.54329C21 5.00272 21 5.58515 21 6.75C21 7.91485 21 8.49728 20.8097 8.95671C20.556 9.56928 20.0693 10.056 19.4567 10.3097C18.9973 10.5 18.4149 10.5 17.25 10.5C16.0851 10.5 15.5027 10.5 15.0433 10.3097C14.4307 10.056 13.944 9.56928 13.6903 8.95671Z" stroke="#141B34" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="round" />

    <path d="M3.1903 19.4567C3 18.9973 3 18.4149 3 17.25C3 16.0851 3 15.5027 3.1903 15.0433C3.44404 14.4307 3.93072 13.944 4.54329 13.6903C5.00272 13.5 5.58515 13.5 6.75 13.5C7.91485 13.5 8.49728 13.5 8.95671 13.6903C9.56928 13.944 10.056 14.4307 10.3097 15.0433C10.5 15.5027 10.5 16.0851 10.5 17.25C10.5 18.4149 10.5 18.9973 10.3097 19.4567C10.056 20.0693 9.56928 20.556 8.95671 20.8097C8.49728 21 7.91485 21 6.75 21C5.58515 21 5.00272 21 4.54329 20.8097C3.93072 20.556 3.44404 20.0693 3.1903 19.4567Z" stroke="#141B34" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="round" />

    <path d="M3.1903 8.95671C3 8.49728 3 7.91485 3 6.75C3 5.58515 3 5.00272 3.1903 4.54329C3.44404 3.93072 3.93072 3.44404 4.54329 3.1903C5.00272 3 5.58515 3 6.75 3C7.91485 3 8.49728 3 8.95671 3.1903C9.56928 3.44404 10.056 3.93072 10.3097 4.54329C10.5 5.00272 10.5 5.58515 10.5 6.75C10.5 7.91485 10.5 8.49728 10.3097 8.95671C10.056 9.56928 9.56928 10.056 8.95671 10.3097C8.49728 10.5 7.91485 10.5 6.75 10.5C5.58515 10.5 5.00272 10.5 4.54329 10.3097C3.93072 10.056 3.44404 9.56928 3.1903 8.95671Z" stroke="#141B34" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="round" />

    </svg>

    Dashboard

    </a>

    </li>

    <li class="nav-item">

    <a class="nav-link sidebar-link" href="#">

    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" color="#000000" fill="none">

    <path d="M2.5 12C2.5 7.52166 2.5 5.28249 3.89124 3.89124C5.28249 2.5 7.52166 2.5 12 2.5C16.4783 2.5 18.7175 2.5 20.1088 3.89124C21.5 5.28249 21.5 7.52166 21.5 12C21.5 16.4783 21.5 18.7175 20.1088 20.1088C18.7175 21.5 16.4783 21.5 12 21.5C7.52166 21.5 5.28249 21.5 3.89124 20.1088C2.5 18.7175 2.5 16.4783 2.5 12Z" stroke="currentColor" stroke-width="1.5"></path>

    <path d="M2.5 12H21.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>

    <path d="M13 7L17 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>

    <circle cx="8.25" cy="7" r="1.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></circle>

    <circle cx="8.25" cy="17" r="1.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></circle>

    <path d="M13 17L17 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>

    </svg>

    Connect Wallet

    </a>

    </li>

    <li class="nav-item">

    <a class="nav-link sidebar-link" href="#">

    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" color="#000000" fill="none">

    <path d="M3.3457 16.1976L16.1747 3.36866M18.6316 11.0556L16.4321 13.2551M14.5549 15.1099L13.5762 16.0886" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>

    <path d="M3.17467 16.1411C1.60844 14.5749 1.60844 12.0355 3.17467 10.4693L10.4693 3.17467C12.0355 1.60844 14.5749 1.60844 16.1411 3.17467L20.8253 7.85891C22.3916 9.42514 22.3916 11.9645 20.8253 13.5307L13.5307 20.8253C11.9645 22.3916 9.42514 22.3916 7.85891 20.8253L3.17467 16.1411Z" stroke="currentColor" stroke-width="1.5"></path>

    <path d="M4 22H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>

    </svg>

    Buy

    </a>

    </li>

    <li class="nav-item">

    <a class="nav-link sidebar-link" href="#">

    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" color="#000000" fill="none">

    <path d="M18.9349 13.9453L18.2646 10.2968C17.9751 8.72096 17.8303 7.93303 17.257 7.46651C16.6837 7 15.8602 7 14.2132 7H9.78685C8.1398 7 7.31628 7 6.74298 7.46651C6.16968 7.93303 6.02492 8.72096 5.73538 10.2968L5.06506 13.9453C4.46408 17.2162 4.16359 18.8517 5.08889 19.9259C6.01419 21 7.72355 21 11.1423 21H12.8577C16.2765 21 17.9858 21 18.9111 19.9259C19.8364 18.8517 19.5359 17.2162 18.9349 13.9453Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>

    <path d="M12 10.5V17M9.5 15L12 17.5L14.5 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>

    <path d="M21 11C21.1568 10.9209 21.2931 10.8212 21.4142 10.6955C22 10.0875 22 9.10893 22 7.15176C22 5.1946 22 4.21602 21.4142 3.60801C20.8284 3 19.8856 3 18 3L6 3C4.11438 3 3.17157 3 2.58579 3.60801C2 4.21602 2 5.1946 2 7.15176C2 9.10893 2 10.0875 2.58579 10.6955C2.70688 10.8212 2.84322 10.9209 3 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>

    </svg>

    Withdraw

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

    <span class="me-3 d-none d-md-inline">Welcome, <span class="fw-bold"><?php echo $user_display_name; ?></span></span>

    <div class="dropdown">

    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">

    Profile

    </button>

    <ul class="dropdown-menu dropdown-menu-dark">

    <li><a class="dropdown-item" href="#">Account Settings</a></li>

    <li><a class="dropdown-item disabled text-muted-custom" href="#"><?php echo $user_email; ?></a></li>

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

    });

    }





    // Function to render holdings

    function renderHoldings() {

    const list = document.getElementById('holdingsList');

    list.innerHTML = '';

    holdings.forEach(item => {

    const isPositive = item.change_24h >= 0;

    const changeClass = isPositive ? 'text-success' : 'text-danger';

    const arrowSvg = isPositive ? `

    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-caret-up-fill me-1" viewBox="0 0 16 16">

      <path d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592c.859 0 1.319-1.012.753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z"/>

    </svg>` : `

    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-caret-down-fill me-1" viewBox="0 0 16 16">

      <path d="M7.247 11.14 2.451 5.66c-.566-.647-.106-1.659.753-1.659h9.592c.859 0 1.319 1.012.753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>

    </svg>`;

        

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