<?php
require "includes/header.php";
?>

<body>
    <div class="d-flex align-items-center justify-content-center min-vh-100 bg-gray-100">
        <div class="text-center p-4">
            <div class="mb-4">
                <!-- Custom SVG Illustration for a 400 error -->
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-48 w-48 text-primary" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.5 10c-.8 0-1.6.4-2 1-1.3-2-5.1-4.7-6.5-2-1.3 2.7-2 6.5-2 8.5V18" />
                    <path d="M5 18c0 1.2.8 2 2 2h3.5a1.5 1.5 0 001.5-1.5V18" />
                    <path d="M21.7 17.5L18 11l-4.5 6.5" />
                    <path d="M19 14h3" />
                    <circle cx="12" cy="12" r="10" />
                </svg>
            </div>
            <h1 class="display-3 fw-bold text-dark">400</h1>
            <p class="h5 fw-normal text-muted mb-4">Bad Request</p>
            <p class="text-muted mb-5">
                The server could not understand the request due to malformed syntax.
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
