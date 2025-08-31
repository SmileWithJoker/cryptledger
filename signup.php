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
                                        <a href="#" class="text-primary text-decoration-none">Sign in here</a>
                                    </p>
                                </div>

                                <form>
                                    <div class="mb-3">
                                        <input type="text" class="form-control" id="InputFullName"
                                            placeholder="Fullname">
                                    </div>

                                    <div class="mb-3">
                                        <input type="text" class="form-control" id="InputUserName"
                                            placeholder="Username">
                                    </div>

                                    <div class="mb-3">
                                        <input type="email" class="form-control" id="InputEmail1"
                                            placeholder="Email address">
                                    </div>

                                    <div class="mb-3">
                                        <input type="text" class="form-control" id="InputPhoneNumber"
                                            placeholder="Phone number">
                                    </div>

                                    <div class="mb-3">
                                        <input type="password" class="form-control" id="exampleInputPassword1"
                                            placeholder="Password">
                                    </div>

                                    <div class="mb-3">
                                        <input type="password" class="form-control" id="exampleInputConfirmPassword"
                                            placeholder="Confirm Password">
                                    </div>

                                    <div class="form-check mb-4">
                                        <input class="form-check-input" type="checkbox" id="terms" required>
                                        <label class="text form-check-label small" for="terms">
                                            I agree to the
                                            <a href="#" class="text-primary text-decoration-none">Terms of Service</a>
                                            and
                                            <a href="#" class="text-primary text-decoration-none">Privacy Policy</a>
                                        </label>
                                    </div>

                                    <a class="btn custom-btn-2 w-100 py-2" href="#" role="button">Secure</a>
                                </form>

                                <div class="text-center mt-4">
                                    <a href="index.html" class="text text-decoration-none small" style="color: #FAFAF9;">
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

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>
