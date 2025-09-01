<?php
// Start a new session or resume the existing one.
session_start();

// Set the content type to application/json.
header('Content-Type: application/json');

// Include the multi-method database connection file.
require_once 'multi_db_connect.php';

// Initialize the response array.
$response = ['success' => false, 'message' => 'An unknown error occurred.'];

// Check if the request method is POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate the input data.
    $usernameOrEmail = htmlspecialchars(trim($_POST['usernameOrEmail']));
    $password = $_POST['password'];

    // Basic server-side validation.
    if (empty($usernameOrEmail) || empty($password)) {
        $response['message'] = 'Both username/email and password are required.';
    } else {
        try {
            // Establish a database connection using PDO.
            $conn = connectWithPDO($config);

            // Prepare a SQL statement to fetch the user by username or email.
            $stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$usernameOrEmail, $usernameOrEmail]);

            // Fetch the user data.
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if a user was found and if the password is correct.
            if ($user && password_verify($password, $user['password_hash'])) {
                // Password is correct, so start a session.
                $_SESSION['user_id'] = $user['id'];
                $response['success'] = true;
                $response['message'] = 'Login successful!';
            } else {
                $response['message'] = 'Invalid username/email or password.';
            }

            // Close the database connection.
            $conn = null;

        } catch (PDOException $e) {
            // Log the error for debugging.
            error_log("Login Error: " . $e->getMessage());
            $response['message'] = 'A server error occurred. Please try again later.';
        }
    }
} else {
    $response['message'] = 'Invalid request method.';
}

// Echo the final JSON response.
echo json_encode($response);
