<?php
// Include the database connection file
require_once 'config/config.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'An error occurred.'];

// Check if an action is specified
if (!isset($_POST['action'])) {
    echo json_encode(['success' => false, 'message' => 'Action not specified.']);
    exit();
}

$action = $_POST['action'];

try {
    // Get the PDO database connection
    $conn = pdo_connect_mysql();

    if ($action === 'request_otp') {
        // --- Step 1: Handle OTP Request ---
        if (!isset($_POST['email']) || empty($_POST['email'])) {
            $response['message'] = 'Email is required.';
            echo json_encode($response);
            exit();
        }

        $email = $_POST['email'];

        // Check if the email exists in the database
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            $response['message'] = 'No account found with that email address.';
            echo json_encode($response);
            exit();
        }

        // Generate a 6-digit OTP
        $otp = random_int(100000, 999999);
        $otp_expires_at = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        // Store the OTP in the database
        $stmt = $conn->prepare("UPDATE users SET otp_code = ?, otp_expires_at = ? WHERE email = ?");
        $stmt->execute([$otp, $otp_expires_at, $email]);

        // Send the OTP via email
        $subject = 'Your Password Reset Code';
        $message = "Your password reset code is: {$otp}. It will expire in 15 minutes. If you did not request this, please ignore this email.";
        $headers = 'From: noreply@worldlibertyfinancial.com';

        // NOTE: For this to work, your PHP server must be configured with mail() settings in php.ini.
        // It's recommended to use a dedicated email library like PHPMailer for production.
        if (mail($email, $subject, $message, $headers)) {
            $response['success'] = true;
            $response['message'] = 'A password reset code has been sent to your email.';
        } else {
            $response['message'] = 'Failed to send the email. Please try again later.';
        }

    } elseif ($action === 'reset_password') {
        // --- Step 2: Handle Password Reset ---
        if (!isset($_POST['email'], $_POST['otp'], $_POST['password']) ||
            empty($_POST['email']) || empty($_POST['otp']) || empty($_POST['password'])) {
            $response['message'] = 'All fields are required.';
            echo json_encode($response);
            exit();
        }

        $email = $_POST['email'];
        $otp = $_POST['otp'];
        $password = $_POST['password'];
        $now = date('Y-m-d H:i:s');

        // Verify the OTP and check if it's expired
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND otp_code = ? AND otp_expires_at > ?");
        $stmt->execute([$email, $otp, $now]);
        $user = $stmt->fetch();

        if (!$user) {
            $response['message'] = 'Invalid or expired OTP. Please try requesting a new one.';
            echo json_encode($response);
            exit();
        }

        // Hash the new password and update the user's record
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ?, otp_code = NULL, otp_expires_at = NULL WHERE id = ?");
        $stmt->execute([$hashed_password, $user['id']]);

        $response['success'] = true;
        $response['message'] = 'Your password has been successfully changed.';
    }

} catch (PDOException $e) {
    // In a production environment, log the error instead of displaying it.
    $response['message'] = 'Database error: ' . $e->getMessage();
}

echo json_encode($response);
