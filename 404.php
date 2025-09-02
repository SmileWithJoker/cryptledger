<?php
require "includes/header.php";
?>

<body>
    <div class="d-flex align-items-center justify-content-center min-vh-100 bg-gray-100">
        <div class="text-center p-4">
            <div class="mb-4">
                <!-- Custom SVG Illustration for a 404 error -->
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-48 w-48 text-primary" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6L6 18" />
                    <path d="M6 6l12 12" />
                    <circle cx="12" cy="12" r="10" />
                    <path d="M9 15L15 9" />
                </svg>
            </div>
            <h1 class="display-3 fw-bold text-dark">404</h1>
            <p class="h5 fw-normal text-muted mb-4">Page Not Found</p>
            <p class="text-muted mb-5">
                The page you're looking for doesn't exist or has been moved.
            </p>
            <a class="btn custom-btn-2" href="index.php" role="button">
                Go to Homepage
            </a>
        </div>
    </div>
</body>

<?php
require "includes/footer.php";
?>