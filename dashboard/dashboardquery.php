<?php

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session to access user data
session_start();

// Load the environment variables from the .env file

function loadEnv($path)
{
    if (!file_exists($path)) {
        return;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value, " \t\n\r\0\x0B\"'");
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}

loadEnv('./.env');

// --- Self-Contained Database Connection ---
function pdo_connect_mysql()
{
    // Retrieve credentials securely from environment variables
    $db_host = $_ENV['DB_HOST'] ?? 'localhost';
    $db_name = $_ENV['DB_NAME'] ?? '';
    $db_user = $_ENV['DB_USER'] ?? '';
    $db_pass = $_ENV['DB_PASS'] ?? '';

    try {
        $conn = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        error_log("Database connection error: " . $e->getMessage());
        // For security, do not display the detailed error to the user in a live environment
        die("<h1>Database connection failed.</h1>");
    }
}

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

try {
    $pdo = pdo_connect_mysql();
    $stmt = $pdo->query('SELECT name, symbol, coingecko_id FROM cryptocurrencies LIMIT 20');
    $db_cryptos = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

    $stmt_user = $pdo->prepare("SELECT `username`, `email` FROM `users` WHERE id = ?");
    $stmt_user->execute([$user_id]);
    $user_data = $stmt_user->fetch(PDO::FETCH_ASSOC);
    if ($user_data) {
        $user_display_name = htmlspecialchars($user_data['username']);
        $user_email = htmlspecialchars($user_data['email']);
    } else {
        $user_display_name = 'Guest';
        $user_email = 'Not Logged In';
    }
} catch (Exception $e) {
        error_log("Failed to load dashboard data for user $user_id: " . $e->getMessage());
        $user_display_name = 'Guest';
        $user_email = 'Not Logged In';
    }
} else {
    $user_display_name = 'Guest';
    $user_email = 'Not Logged In';
}

// Pagination logic
$coins_per_page = 10;
$total_coins = count($cryptocurrencies_data);
$total_pages = ceil($total_coins / $coins_per_page);
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max(1, min($current_page, $total_pages)); // Clamp page number
$start_index = ($current_page - 1) * $coins_per_page;
$paginated_cryptos = array_slice($cryptocurrencies_data, $start_index, $coins_per_page);

?>