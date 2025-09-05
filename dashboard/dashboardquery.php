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

// --- New: Logic to determine if the popup should be shown ---
$show_popup = false;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $cookie_name = "popup_dismissed_" . $user_id;

    if (isset($_COOKIE[$cookie_name])) {
        $dismissal_timestamp = (int) $_COOKIE[$cookie_name];
        // 14 days in seconds
        $fourteen_days = 14 * 24 * 60 * 60;
        if ((time() - $dismissal_timestamp) > $fourteen_days) {
            // The cookie is expired, so we can show the popup again
            $show_popup = true;
        }
    } else {
        // The cookie does not exist, so we show the popup
        $show_popup = true;
    }
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