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
                                        <span class="gradient-text" style="font-size: 17px;">World Liberty
                                            Financial</span>
                                    </div>

                                    <h2 class="fw-bold">Create your account</h2>

                                    <p class="text">
                                        Already have an account?
                                        <a href="login" class="text-primary text-decoration-none">Sign in here</a>
                                    </p>
                                </div>

                                <form id="signupForm" method="POST">
                                    <div class="mb-3">
                                        <input type="text" class="form-control" name="fullName" id="InputFullName"
                                            placeholder="Fullname" required>
                                    </div>

                                    <div class="mb-3">
                                        <text-area type="text" class="form-control" name="username" id="InputUserName"
                                            placeholder="Username" required></text-area>
                                    </div>

                                    <div class="mb-3">
                                        <input type="email" class="form-control" name="email" id="InputEmail1"
                                            placeholder="Email address" required>
                                    </div>

                                    <div class="mb-3">
                                        <input type="text" class="form-control" name="phoneNumber" id="InputPhoneNumber"
                                            placeholder="Phone number" required>
                                    </div>

                                    <div class="mb-3">
                                        <input type="password" class="form-control" name="password" id="InputPassword1"
                                            placeholder="Password" required>
                                    </div>

                                    <div class="mb-3">
                                        <input type="password" class="form-control" name="confirmPassword" id="InputConfirmPassword"
                                            placeholder="Confirm Password" required>
                                    </div>

                                    <div class="form-check mb-4">
                                        <input class="form-check-input" type="checkbox" id="terms" required>
                                        <label class="text form-check-label small" for="terms">
                                            I agree to the
                                            <a href="terms-of-serives" class="text-primary text-decoration-none">Terms of Service</a>
                                            and
                                            <a href="privacy-policy" class="text-primary text-decoration-none">Privacy Policy</a>
                                        </label>
                                    </div>

                                    <!-- Changed from <a> to <button> for form submission -->
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
    <!-- Your custom JavaScript for handling the form -->
    <script src="ajax.js"></script>
</body>

</html>
