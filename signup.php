<?php
/**
 * Single-file signup page with a self-submitting form.
 *
 * This file combines the HTML frontend and PHP backend for user registration.
 * It requires a 'users' table with at least: id, full_name, username, email, phone_number, and password_hash.
 */

// Start a new session or resume the existing one.
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

// --- Backend Logic (PHP) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set the content type to application/json for the AJAX response.
    header('Content-Type: application/json');

    // Initialize the response array.
    $response = ['success' => false, 'message' => 'An unknown error occurred.'];

    try {
        // Establish a database connection using the new, reusable function.
        $conn = pdo_connect_mysql();

        // Sanitize and retrieve input data.
        $fullname = htmlspecialchars(trim($_POST['fullname']));
        $username = htmlspecialchars(trim($_POST['username']));
        $email = htmlspecialchars(trim($_POST['email']));
        $phone = htmlspecialchars(trim($_POST['phone']));
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $terms = isset($_POST['terms']);

        // --- Server-Side Validation ---
        if (empty($fullname) || empty($username) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
            $response['message'] = 'All fields are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = 'Please enter a valid email address.';
        } elseif (strlen($password) < 8) {
            $response['message'] = 'Password must be at least 8 characters long.';
        } elseif ($password !== $confirm_password) {
            $response['message'] = 'Passwords do not match.';
        } elseif (!$terms) {
            $response['message'] = 'You must agree to the Terms of Service and Privacy Policy.';
        } else {
            // Check if username or email already exists.
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            if ($stmt->fetch()) {
                $response['message'] = 'A user with that username or email already exists.';
            } else {
                // Hash the password for secure storage.
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                // Prepare SQL statement to insert the new user.
                $sql = "INSERT INTO `users`(`full_name`, `username`, `email`, `phone_number`, `password_hash`, `created_at`, `otp_code`, `otp_expires_at`) VALUES (?, ?, ?, ?, ?, NOW(), NULL, NULL)";
                $stmt = $conn->prepare($sql);

                // Execute the statement and check for success.
                if ($stmt->execute([$fullname, $username, $email, $phone, $password_hash])) {
                    $response['success'] = true;
                    $response['message'] = 'Registration successful! You will be redirected to the login page.';
                } else {
                    $response['message'] = 'An error occurred during registration. Please try again.';
                }
            }
        }
    } catch (PDOException $e) {
        error_log("Signup Error: " . $e->getMessage());
        $response['message'] = 'A server error occurred. Please try again later.';
    } finally {
        $conn = null;
    }

    // Echo the final JSON response and exit.
    echo json_encode($response);
    exit();
}
?>

<!-- --- Frontend UI (HTML, CSS, JS) --- -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>World Liberty Financial - Sign Up</title>

    <!-- Favicon -->
    <link rel="icon" href="assets/image/favicon/favicon.png" type="image/png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Imported Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">

    <!-- Custom CSS (inlined for portability) -->
    <style>
        body {
            background-color: #0d1117;
            color: #c9d1d9;
            font-family: 'Sora', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
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
                                        <!-- Note: Using an actual img tag requires the asset path to be correct. -->
                                        <img src="assets/image/png/logo.png" style="width: 75px;" alt="Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                                        <span class="gradient-text" style="font-size: 17px; display: none;">World Liberty Financial</span>
                                    </div>
                                    <h2 class="fw-bold">Create your account</h2>
                                    <p class="text">
                                        Already have an account?
                                        <a href="login.php" class="text-primary text-decoration-none">Sign in here</a>
                                    </p>
                                </div>

                                <form id="signupForm" method="POST">
                                    <div class="mb-3">
                                        <input type="text" class="form-control" name="fullname" placeholder="Full Name" required>
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" class="form-control" name="username" placeholder="Username" required>
                                    </div>
                                    <div class="mb-3">
                                        <input type="email" class="form-control" name="email" placeholder="Email Address" required>
                                    </div>
                                    <div class="mb-3">
                                        <input type="tel" class="form-control" name="phone" placeholder="Phone Number" required>
                                    </div>
                                    <div class="mb-3">
                                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                                    </div>
                                    <div class="mb-3">
                                        <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
                                    </div>
                                    <div class="form-check mb-4">
                                        <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                        <label class="text form-check-label small" for="terms">
                                            I agree to the
                                            <a href="#" class="text-primary text-decoration-none">Terms of Service</a>
                                            and
                                            <a href="#" class="text-primary text-decoration-none">Privacy Policy</a>
                                        </label>
                                    </div>
                                    <button type="submit" class="btn custom-btn-2 w-100 py-2">Secure</button>
                                </form>

                                <div class="text-center mt-4">
                                    <a href="./" class="text text-decoration-none small" style="color: #FAFAF9;">
                                        ← Back to home
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal for success/failure messages -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white border-0">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" id="statusModalLabel">Registration Status</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p id="modalMessage"></p>
                </div>
                <div class="modal-footer border-top-0 d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- Custom JavaScript for AJAX form submission -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const signupForm = document.getElementById('signupForm');
            const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
            const modalMessage = document.getElementById('modalMessage');

            signupForm.addEventListener('submit', async (event) => {
                event.preventDefault();

                const formData = new FormData(signupForm);
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
                        // Redirect to login page on successful registration
                        setTimeout(() => {
                            window.location.href = 'login.php';
                        }, 3000);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    modalMessage.textContent = 'A client-side error occurred. Please try again.';
                    statusModal.show();
                }
            });
        });
    </script>
</body>

</html>