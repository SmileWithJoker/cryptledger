<?php
require "includes/header.php";
?>

<body>
    <div class="d-flex align-items-center justify-content-center min-vh-100 bg-gray-100">
        <div class="text-center p-4">
            <div class="mb-4">
                <!-- Custom SVG Illustration for a 403 error -->
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-48 w-48 text-primary" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2z" />
                    <path d="M15 9h-6a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-6a1 1 0 0 0-1-1z" />
                    <path d="M9 10V7a3 3 0 0 1 6 0v3" />
                </svg>
            </div>
            <h1 class="display-3 fw-bold text-dark">403</h1>
            <p class="h5 fw-normal text-muted mb-4">Forbidden</p>
            <p class="text-muted mb-5">
                You do not have permission to access the requested resource.
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
