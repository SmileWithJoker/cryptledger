<?php
require "includes/header.php";
?>

<body>
    <div class="d-flex align-items-center justify-content-center min-vh-100 bg-gray-100">
        <div class="text-center p-4">
            <div class="mb-4">
                <!-- Custom SVG Illustration for a 500 error -->
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-48 w-48 text-primary" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2z" />
                    <path d="M12 6a2 2 0 1 0 0 4a2 2 0 0 0 0-4z" />
                    <path d="M12 14v4" />
                </svg>
            </div>
            <h1 class="display-3 fw-bold text-dark">500</h1>
            <p class="h5 fw-normal text-muted mb-4">Internal Server Error</p>
            <p class="text-muted mb-5">
                Something went wrong on our end. We are working to fix it.
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
