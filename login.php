<?php
/**
 * Single-file login page with a self-submitting form.
 *
 * This file combines the HTML frontend and PHP backend to create a complete,
 * portable login solution. It processes form submissions and displays the HTML form.
 *
 * To use this file, you must have a web server with PHP and a database (e.g., MySQL)
 * with a 'users' table that includes 'id', 'username', 'email', and 'password_hash' columns.
 * You will need to replace the placeholder database connection details below with your own.
 */

// Start a new session or resume the existing one.
session_start();

// --- Backend Logic (PHP) ---
// This part of the script is executed only when the form is submitted via POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set the content type to application/json for the AJAX response.
    header('Content-Type: application/json');

    // Initialize the response array.
    $response = ['success' => false, 'message' => 'An unknown error occurred.'];

    // --- IMPORTANT: Database Connection ---
    // You must replace the following placeholder values with your actual database credentials.
    // This is a simplified, insecure connection for demonstration. In a production environment,
    // you should use a separate configuration file and a more secure method.
    $db_host = 'localhost';
    $db_name = 'jotahcom_test';
    $db_user = 'jotahcom_test';
    $db_pass = 'Ikeotuonye@00';

    try {
        // Establish a database connection using PDO.
        $conn = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Sanitize and validate the input data.
        $usernameOrEmail = htmlspecialchars(trim($_POST['usernameOrEmail']));
        $password = $_POST['password'];

        // Basic server-side validation.
        if (empty($usernameOrEmail) || empty($password)) {
            $response['message'] = 'Both username/email and password are required.';
        } else {
            // Prepare a SQL statement to fetch the user by username or email.
            $stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
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
        }

    } catch (PDOException $e) {
        error_log("Login Error: " . $e->getMessage());
        $response['message'] = 'A server error occurred. Please try again later.';
    } finally {
        // Close the database connection if it was established.
        $conn = null;
    }

    // Echo the final JSON response and exit.
    echo json_encode($response);
    exit(); // Stop script execution after sending the JSON response.
}
?>

<!-- --- Frontend UI (HTML, CSS, JS) --- -->
<!-- This part of the script is executed when the page is first loaded (GET request). -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>World Liberty Financial Login</title>

 <!-- Favicon -->
    <link rel="icon" href="assets/image/favicon/favicon.png" type="image/png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">

    <!-- Imported Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">

    <!-- Custom CSS (inlined for portability) -->
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
                                        <img src="assets/image/png/logo.png" style="width: 75px;" alt="Logo">
                                        <span class="gradient-text" style="font-size: 17px;">World Liberty
                                            Financial</span>
                                    </div>

                                    <h2 class="fw-bold">Login to your account</h2>

                                    <p class="text">
                                        Don't have an account?
                                        <a href="signup" class="text-primary text-decoration-none">Sign up here</a>
                                    </p>
                                </div>

                                <form id="loginForm" method="POST">
                                    <div class="mb-3">
                                        <input type="text" class="form-control" name="usernameOrEmail" id="InputUsernameOrEmail"
                                            placeholder="Username or Email" required>
                                    </div>

                                    <div class="mb-3">
                                        <input type="password" class="form-control" name="password" id="InputPassword"
                                            placeholder="Password" required>
                                    </div>

                                    <div class="form-check mb-4">
                                        <a href="forget-password" class="text-primary text-decoration-none small">Forgot Password?</a>
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
                    <h5 class="modal-title" id="statusModalLabel">Status</h5>
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
    
    <!-- Your custom JavaScript for handling the form submission -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const loginForm = document.getElementById('loginForm');
            const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
            const modalMessage = document.getElementById('modalMessage');

            loginForm.addEventListener('submit', async (event) => {
                event.preventDefault();

                const formData = new FormData(loginForm);
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
                        // Redirect or perform other actions on successful login
                        setTimeout(() => {
                            window.location.href = './dashboard'; // Example: Redirect to the home page
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
