<?php
/**
 * Single-file password reset page with a self-submitting form.
 *
 * This file combines the HTML frontend and PHP backend to create a complete,
 * portable solution for password recovery. It handles OTP requests and password resets
 * via AJAX to provide a smooth user experience.
 *
 * To use this file, your database must have a 'users' table with columns:
 * 'id', 'email', 'password_hash', 'otp_code', and 'otp_expires_at'.
 * You must also replace the placeholder database connection details and configure
 * your server for email sending.
 */

// Check if the request method is POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set the content type to application/json for the AJAX response.
    header('Content-Type: application/json');

    // Initialize the response array.
    $response = ['success' => false, 'message' => 'An unknown error occurred.'];

    // --- IMPORTANT: Database Connection ---
    // You must replace the following placeholder values with your actual database credentials.
    $db_host = 'localhost';
    $db_name = 'jotahcom_test';
    $db_user = 'jotahcom_test';
    $db_pass = 'Ikeotuonye@00';
    $conn = null;

    try {
        // Establish a database connection using PDO.
        $conn = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Check if an action is specified.
        if (!isset($_POST['action'])) {
            $response['message'] = 'Action not specified.';
            echo json_encode($response);
            exit();
        }

        $action = $_POST['action'];
        
        if ($action === 'request_otp') {
            // --- Step 1: Handle OTP Request ---
            if (!isset($_POST['email']) || empty($_POST['email'])) {
                $response['message'] = 'Email is required.';
                echo json_encode($response);
                exit();
            }

            $email = $_POST['email'];

            // Check if the email exists in the database.
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if (!$user) {
                $response['message'] = 'No account found with that email address.';
                echo json_encode($response);
                exit();
            }

            // Generate a 6-digit OTP and set an expiration time (15 minutes).
            $otp = random_int(100000, 999999);
            $otp_expires_at = date('Y-m-d H:i:s', strtotime('+15 minutes'));

            // Store the OTP in the database.
            $stmt = $conn->prepare("UPDATE users SET otp_code = ?, otp_expires_at = ? WHERE email = ?");
            $stmt->execute([$otp, $otp_expires_at, $email]);

            // --- Email Sending ---
            // NOTE: For this to work, your PHP server must be configured with mail() settings.
            // For production, it's highly recommended to use a dedicated email library like PHPMailer or a service like SendGrid.
            $subject = 'Your Password Reset Code';
            $message = "Your password reset code is: {$otp}. It will expire in 15 minutes. If you did not request this, please ignore this email.";
            $headers = 'From: noreply@worldlibertyfinancial.com';

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

            // Verify the OTP and check if it's expired.
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND otp_code = ? AND otp_expires_at > ?");
            $stmt->execute([$email, $otp, $now]);
            $user = $stmt->fetch();

            if (!$user) {
                $response['message'] = 'Invalid or expired OTP. Please try requesting a new one.';
                echo json_encode($response);
                exit();
            }

            // Hash the new password and update the user's record.
            // The password column must be 'password_hash' to be consistent with the other pages.
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password_hash = ?, otp_code = NULL, otp_expires_at = NULL WHERE id = ?");
            $stmt->execute([$hashed_password, $user['id']]);

            $response['success'] = true;
            $response['message'] = 'Your password has been successfully changed.';
        }

    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        $response['message'] = 'A server error occurred. Please try again later.';
    } finally {
        // Close the database connection.
        $conn = null;
    }

    echo json_encode($response);
    exit(); // Stop script execution after sending the JSON response.
}
?>

<!-- --- Frontend UI (HTML, CSS, JS) --- -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>World Liberty Financial Forgot Password</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #0d1117;
            color: #c9d1d9;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        }
        .card {
            background-color: #161b22;
            border-color: #30363d;
        }
        .form-control {
            background-color: #0d1117;
            border-color: #30363d;
            color: #c9d1d9;
        }
        .form-control:focus {
            background-color: #0d1117;
            border-color: #58a6ff;
            box-shadow: 0 0 0 0.2rem rgba(88, 166, 255, 0.25);
            color: #c9d1d9;
        }
        .text-primary {
            color: #58a6ff !important;
        }
        .custom-btn-2 {
            background: linear-gradient(to right, #6a11cb 0%, #2575fc 100%);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }
        .custom-btn-2:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            color: white;
        }
        .gradient-text {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .logo-placeholder {
            width: 75px;
            height: 75px;
            display: inline-block;
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            border-radius: 50%;
            text-align: center;
            line-height: 75px;
            font-size: 2rem;
            color: white;
        }
    </style>
</head>

<body>
    <main>
        <div class="min-vh-100 d-flex align-items-center justify-content-center">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-5">
                        <div class="card border-0 shadow-lg">
                            <div class="card-body p-5">
                                <div class="text-center mb-4">
                                    <div class="d-inline-block mb-3">
                                        <!-- Replaced image with a placeholder since external images are not available. -->
                                        <div class="logo-placeholder" role="img" aria-label="Logo">WLF</div>
                                        <span class="gradient-text" style="font-size: 17px;">World Liberty Financial</span>
                                    </div>
                                    <h2 class="fw-bold">Forgot your password?</h2>
                                    <p class="text">
                                        Enter your account email to receive a password reset code.
                                    </p>
                                </div>

                                <!-- Step 1: Request OTP Form -->
                                <form id="requestOtpForm" novalidate>
                                    <input type="hidden" name="action" value="request_otp">
                                    <div class="mb-3">
                                        <input type="email" class="form-control" id="InputEmail" name="email"
                                            placeholder="Email address" required>
                                        <div class="invalid-feedback">
                                            Please enter a valid email address.
                                        </div>
                                    </div>
                                    <button type="submit" class="btn custom-btn-2 w-100 py-2">Send Reset Code</button>
                                </form>

                                <!-- Step 2: Reset Password Form (initially hidden) -->
                                <form id="resetPasswordForm" novalidate style="display:none;">
                                    <input type="hidden" name="action" value="reset_password">
                                    <input type="hidden" name="email" id="emailHiddenInput">
                                    <div class="mb-3">
                                        <input type="text" class="form-control" id="InputOtp" name="otp"
                                            placeholder="Enter OTP" required>
                                        <div class="invalid-feedback">
                                            Please enter the code sent to your email.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <input type="password" class="form-control" id="InputPassword" name="password"
                                            placeholder="New Password" required>
                                        <div class="invalid-feedback">
                                            Please enter a new password.
                                        </div>
                                    </div>
                                    <button type="submit" class="btn custom-btn-2 w-100 py-2">Change Password</button>
                                </form>

                                <div class="text-center mt-4">
                                    <a href="login_complete.php" class="text text-decoration-none small" style="color: #FAFAF9;">
                                        ← Back to login
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal for Status Messages -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="modalMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const requestOtpForm = document.getElementById('requestOtpForm');
            const resetPasswordForm = document.getElementById('resetPasswordForm');
            const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
            const modalMessage = document.getElementById('modalMessage');
            const emailHiddenInput = document.getElementById('emailHiddenInput');
            let userEmail = '';

            // Handle the OTP request form submission.
            requestOtpForm.addEventListener('submit', async (event) => {
                event.preventDefault();

                const formData = new FormData(requestOtpForm);
                const data = new URLSearchParams(formData);

                try {
                    const response = await fetch('<?php echo basename(__FILE__); ?>', {
                        method: 'POST',
                        body: data,
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                    });
                    
                    const result = await response.json();
                    modalMessage.textContent = result.message;
                    statusModal.show();
                    
                    if (result.success) {
                        userEmail = formData.get('email');
                        emailHiddenInput.value = userEmail;
                        requestOtpForm.style.display = 'none';
                        resetPasswordForm.style.display = 'block';
                    }

                } catch (error) {
                    console.error('Error:', error);
                    modalMessage.textContent = 'An error occurred. Please try again.';
                    statusModal.show();
                }
            });

            // Handle the password reset form submission.
            resetPasswordForm.addEventListener('submit', async (event) => {
                event.preventDefault();
                
                const formData = new FormData(resetPasswordForm);
                const data = new URLSearchParams(formData);

                try {
                    const response = await fetch('<?php echo basename(__FILE__); ?>', {
                        method: 'POST',
                        body: data,
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                    });

                    const result = await response.json();
                    modalMessage.textContent = result.message;
                    statusModal.show();
                    
                    if (result.success) {
                        // Redirect to the login page on success.
                        setTimeout(() => {
                            window.location.href = 'login.php';
                        }, 2000);
                    }

                } catch (error) {
                    console.error('Error:', error);
                    modalMessage.textContent = 'An error occurred. Please try again.';
                    statusModal.show();
                }
            });
        });
    </script>
</body>
</html>
