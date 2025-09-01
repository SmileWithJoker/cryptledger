<?php

// Define database connection parameters.
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cryptledger";

try {
    // Create a new PDO instance to connect to the database.
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Successfully connected to the database.<br><br>";

    // Define the website content as an associative array.
    // This allows you to easily manage and update the data before insertion.
    $websiteContent = [
        ['content_key' => 'site_name', 'content_value' => 'World Liberty Financial', 'section' => 'header'],
        ['content_key' => 'hero_button', 'content_value' => 'Inspired by Donald J. Trump', 'section' => 'hero'],
        ['content_key' => 'hero_title', 'content_value' => 'Shape a New Era of Finance', 'section' => 'hero'],
        ['content_key' => 'hero_subtitle', 'content_value' => 'Be DeFiant', 'section' => 'hero'],
        ['content_key' => 'hero_text', 'content_value' => 'We\'re leading a financial revolution by dismantling the stranglehold of traditional financial institutions and putting the power back where it belongs: in your hands.', 'section' => 'hero'],
        ['content_key' => 'trump_disclaimer_1', 'content_value' => 'None of Donald J. Trump, any of his family members or any director, officer or employee of the Trump Organization, DT Marks DEFI LLC or any of their respective affiliates is an officer, director, founder, or employee of World Liberty Financial or its affiliates. None of World Liberty Financial, Inc., its affiliates or the World Liberty Financial platform is owned, managed, or operated, by Donald J. Trump, any of his family members, the Trump Organization, DT Marks DEFI LLC or any of their respective directors, officers, employees, affiliates, or principals. $WLFI tokens and use of the World Liberty Financial platform are offered and sold solely by World Liberty Financial or its affiliates. DT Marks DeFi, LLC and its affiliates, including Donald J. Trump has or may receive approximately 22.5 billion tokens from World Liberty Financial, and will be entitled to receive significant fees for services provided to World Liberty Financial, which amount cannot yet be determined. World Liberty Financial and $WLFI are not political and not part of any political campaign.', 'section' => 'body'],
        ['content_key' => 'copyright_text', 'content_value' => '© 2024 WorldLiberty Financial, Inc. All Rights Reserved.', 'section' => 'footer'],
        ['content_key' => 'privacy_policy_link', 'content_value' => 'Privacy Policy', 'section' => 'footer'],
        ['content_key' => 'uk_residency_disclaimer', 'content_value' => 'If you are resident in the UK, you acknowledge that this information is only intended to be available to persons who meet the requirements of qualified investors (i) who have professional experience in matters relating to investments and who fall within the definition of “investment professional” in Article 19(5) of the Financial Services and Markets Act 2000 (Financial Promotion) Order 2005, as amended (the “Order”); or (ii) who are high net worth entities, unincorporated associations or partnerships falling within Article 49(2) of the Order; or (iii) any other persons to whom this information may lawfully be communicated under the Order. Persons who do not fall within these categories should not act or rely on the information contained herein.', 'section' => 'footer']
    ];

    // Prepare the SQL statement for inserting the data.
    // Using a prepared statement with placeholders (?) is a crucial security measure.
    // The REPLACE INTO syntax will either insert a new row or update an existing one
    // if the 'content_key' already exists.
    $stmt = $conn->prepare("REPLACE INTO website_content (content_key, content_value, section) VALUES (?, ?, ?)");

    // Loop through the data array and insert each item into the database.
    foreach ($websiteContent as $item) {
        $stmt->execute([$item['content_key'], $item['content_value'], $item['section']]);
        echo "Inserted/Updated content with key: '{$item['content_key']}'<br>";
    }

    echo "<br>Database population complete.";

} catch(PDOException $e) {
    die("Database operation failed: " . $e->getMessage());
}

?>
https://www.siteprice.org/SellWebsites.aspx