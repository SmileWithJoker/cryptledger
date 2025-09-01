-- This SQL file creates a table to store the text content of your website.

-- Drop the table if it already exists to ensure a clean start.
DROP TABLE IF EXISTS `website_content`;

-- Create the `website_content` table.
-- 'content_key' is a unique identifier for each piece of text (e.g., 'hero_title').
-- 'content_value' stores the actual text content.
-- 'section' helps categorize the content (e.g., 'header', 'hero', 'footer').
CREATE TABLE `website_content` (
    `content_key` VARCHAR(255) NOT NULL PRIMARY KEY,
    `content_value` TEXT NOT NULL,
    `section` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Now, we'll insert the data directly from your HTML code into the table.
-- Using REPLACE INTO ensures that if you run this script again,
-- it will update the content instead of causing a duplicate key error.

REPLACE INTO `website_content` (`content_key`, `content_value`, `section`) VALUES
('site_name', 'World Liberty Financial', 'header'),
('hero_button', 'Inspired by Donald J. Trump', 'hero'),
('hero_title', 'Shape a New Era of Finance', 'hero'),
('hero_subtitle', 'Be DeFiant', 'hero'),
('hero_text', 'We''re leading a financial revolution by dismantling the stranglehold of traditional financial institutions and putting the power back where it belongs: in your hands.', 'hero'),
('trump_disclaimer_1', 'None of Donald J. Trump, any of his family members or any director, officer or employee of the Trump Organization, DT Marks DEFI LLC or any of their respective affiliates is an officer, director, founder, or employee of World Liberty Financial or its affiliates. None of World Liberty Financial, Inc., its affiliates or the World Liberty Financial platform is owned, managed, or operated, by Donald J. Trump, any of his family members, the Trump Organization, DT Marks DEFI LLC or any of their respective directors, officers, employees, affiliates, or principals. $WLFI tokens and use of the World Liberty Financial platform are offered and sold solely by World Liberty Financial or its affiliates. DT Marks DeFi, LLC and its affiliates, including Donald J. Trump has or may receive approximately 22.5 billion tokens from World Liberty Financial, and will be entitled to receive significant fees for services provided to World Liberty Financial, which amount cannot yet be determined. World Liberty Financial and $WLFI are not political and not part of any political campaign.', 'body'),
('copyright_text', '© 2024 WorldLiberty Financial, Inc. All Rights Reserved.', 'footer'),
('privacy_policy_link', 'Privacy Policy', 'footer'),
('uk_residency_disclaimer', 'If you are resident in the UK, you acknowledge that this information is only intended to be available to persons who meet the requirements of qualified investors (i) who have professional experience in matters relating to investments and who fall within the definition of “investment professional” in Article 19(5) of the Financial Services and Markets Act 2000 (Financial Promotion) Order 2005, as amended (the “Order”); or (ii) who are high net worth entities, unincorporated associations or partnerships falling within Article 49(2) of the Order; or (iii) any other persons to whom this information may lawfully be communicated under the Order. Persons who do not fall within these categories should not act or rely on the information contained herein.', 'footer');
