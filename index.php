<?php
// Include the database connection script.
// Make sure this file is in the same directory or adjust the path.
require_once 'config/config.php';

$content = []; // Initialize an array to hold all the website content.

try {
    // Establish a database connection using the PDO method from the included file.
    $conn = connectWithPDO($config);

    // Prepare and execute a SQL query to fetch all content from the 'website_content' table.
    $stmt = $conn->prepare("SELECT content_key, content_value FROM website_content");
    $stmt->execute();

    // Fetch all the results and store them in an associative array,
    // where the key is 'content_key' and the value is 'content_value'.
    $dbContent = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    
    // Assign the fetched content to the main content array.
    $content = $dbContent;

    // We no longer need the database connection, so we can close it.
    $conn = null;
    
} catch(PDOException $e) {
    // If the database connection or query fails, we can set a fallback
    // message and log the error for debugging purposes.
    error_log("Database Error: " . $e->getMessage());
    $content['hero_title'] = "Error loading content.";
    $content['hero_text'] = "Please check your database connection.";
    // For a production site, you'd hide this message and show a user-friendly one.
}

// Fallback values in case the database connection fails.
$content_keys = ['site_name', 'hero_button', 'hero_title', 'hero_subtitle', 'hero_text', 'trump_disclaimer_1', 'copyright_text', 'privacy_policy_link', 'uk_residency_disclaimer'];
foreach ($content_keys as $key) {
    if (!isset($content[$key])) {
        // Set an empty string as a default fallback to avoid PHP notices.
        $content[$key] = '';
    }
}

// Now we'll use the $content array to populate the HTML below.
?>

<body>
    <main class="page_wrapper">
        <header>
            <nav id="header-nav" class="navbar fixed-top">
                <div class="container">
                    <div class="logo-navbar">
                        <a class="navbar-brand" href="#">
                            <img src="assets/image/png/logo.png" style="width: 75px;" alt="Logo">
                            <!-- Dynamically set site name from the database -->
                            <span class="gradient-text" style="font-size: 17px;"><?php echo htmlspecialchars($content['site_name']); ?></span>
                        </a>
                    </div>

                    <div class="nav-button d-none d-md-flex">
                        <a class="btn login-btn" href="login" role="button">Log in</a>
                        <a class="btn custom-btn-2" href="signup" role="button">Secure</a>
                    </div>
                </div>
            </nav>
        </header>

        <section class="hero-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <!-- Dynamically set hero button text from the database -->
                        <a class="btn custom-btn" href="#" style="width: 245px;" role="button"><?php echo htmlspecialchars($content['hero_button']); ?></a>
                        <!-- Dynamically set hero title from the database -->
                        <h1 class="mt-3"><?php echo htmlspecialchars($content['hero_title']); ?></h1>
                        <!-- Dynamically set hero subtitle from the database -->
                        <h1 class="gradient-text"><?php echo htmlspecialchars($content['hero_subtitle']); ?></h1>

                        <!-- Dynamically set hero paragraph text from the database -->
                        <p class="text">
                            <?php echo nl2br(htmlspecialchars($content['hero_text'])); ?>
                        </p>

                        <a class="btn custom-btn-2" href="signup.html" style="width: 150px; margin-top: 10px;"
                            role="button">Secure</a>
                    </div>

                    <div class="col-lg-6">
                        <div class="trump-img-wrapper">
                            <img src="assets/image/png/trump.png" class="trump-img-bg img-fluid" alt="Donald Trump">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section style="margin-top: 60px;">
            <div class="container">
                <div class="row align-items-center">
                    <div>
                        <!-- Dynamically set disclaimer from the database -->
                        <p class="text">
                            <?php echo nl2br(htmlspecialchars($content['trump_disclaimer_1'])); ?>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <footer class="text-light py-4 mt-5">
            <div class="container">
                <div class="row align-items-center justify-content-between">
                    <hr>
                    <div class="col-6 d-flex align-items-center gap-3">
                        <a href="#" class="circle-icon" target="_blank">
                            <img src="assets/image/png/circle.png" alt="Circle">
                            <i class="bi bi-twitter-x"></i>
                        </a>
                    </div>

                    <div class="col-6 text-end">
                        <!-- Dynamically set privacy policy link text from the database -->
                        <p class="mb-0" style="color: #FEED8B; font-size: 15px;"><?php echo htmlspecialchars($content['privacy_policy_link']); ?></p>
                    </div>

                    <div class="mt-4">
                        <div class="col">
                            <p class="text">
                                <!-- Dynamically set copyright and UK disclaimer text from the database -->
                                <?php echo htmlspecialchars($content['copyright_text']); ?>
                                <br>
                                <?php echo nl2br(htmlspecialchars($content['uk_residency_disclaimer'])); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </main>

    <?php
    require "includes/footer.php";
    ?>
</body>
