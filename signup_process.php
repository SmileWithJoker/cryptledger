<?php
// Set the content type to application/json so the client-side JavaScript knows to parse it as JSON.
header('Content-Type: application/json');

// Include the multi-method database connection file.
require_once 'config/config.php';

// Initialize the response array.
$response = ['success' => false, 'message' => 'An unknown error occurred.'];

// Check if the request method is POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate the input data.
    $fullName = htmlspecialchars(trim($_POST['fullName']));
    $username = htmlspecialchars(trim($_POST['username']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phoneNumber = htmlspecialchars(trim($_POST['phoneNumber']));
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Basic server-side validation.
    if (empty($fullName) || empty($username) || empty($email) || empty($phoneNumber) || empty($password) || empty($confirmPassword)) {
        $response['message'] = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Invalid email address.';
    } elseif ($password !== $confirmPassword) {
        $response['message'] = 'Passwords do not match.';
    } else {
        try {
            // Establish a database connection using PDO.
            $conn = connectWithPDO($config);

            // Hash the password for secure storage.
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Check if the username or email already exists to prevent duplicates.
            $checkStmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
            $checkStmt->execute([$username, $email]);
            $count = $checkStmt->fetchColumn();

            if ($count > 0) {
                $response['message'] = 'Username or email already exists.';
            } else {
                // Prepare and execute the SQL INSERT statement.
                // Using a prepared statement is crucial for security.
                $stmt = $conn->prepare("INSERT INTO users (full_name, username, email, phone_number, password_hash) VALUES (?, ?, ?, ?, ?)");
                
                if ($stmt->execute([$fullName, $username, $email, $phoneNumber, $passwordHash])) {
                    $response['success'] = true;
                    $response['message'] = 'Your account has been created successfully!';
                } else {
                    $response['message'] = 'Account creation failed. Please try again.';
                }
            }

            // Close the database connection.
            $conn = null;

        } catch (PDOException $e) {
            // Log the error for debugging. For security, don't show the detailed error to the user.
            error_log("Signup Error: " . $e->getMessage());
            $response['message'] = 'A server error occurred. Please try again later.';
        }
    }
} else {
    $response['message'] = 'Invalid request method.';
}

// Echo the final JSON response.
echo json_encode($response);
?>
