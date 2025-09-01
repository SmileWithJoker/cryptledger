<?php
require "includes/header.php";
?>

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
                                    <a href="login.php" class="text text-decoration-none small" style="color: #FAFAF9;">
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
    <script src="ajax.js"></script>
</body>
</html>
