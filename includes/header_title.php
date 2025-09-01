<?php

/**
 * Gets a human-readable title for the current page based on the filename.
 *
 * @param string|null $filename The filename to process. If null, uses the current script name.
 * @return string The formatted page title.
 */
function getPageTitle(?string $filename = null): string
{
    // Get the current script's base name if no filename is provided.
    if ($filename === null) {
        $filename = basename($_SERVER['PHP_SELF']);
    }

    // Remove the file extension.
    $title = pathinfo($filename, PATHINFO_FILENAME);

    // Handle specific page slugs and filenames.
    switch ($title) {
        case 'index':
            $title = 'Home';
            break;
        case '400':
            $title = 'Bad Request';
            break;
        case '403':
            $title = 'Forbidden';
            break;
        case '404':
            $title = 'Page Not Found';
            break;
        case '419':
            $title = 'Page Expired';
            break;
        case '500':
            $title = 'Internal Server Error';
            break;
        case 'privacy-policy':
            $title = 'Privacy Policy';
            break;
        case 'terms-condition':
            $title = 'Terms & Conditions';
            break;
        case 'forget-password':
            $title = 'Forget Password';
            break;
        default:
            // For all other pages, replace hyphens and capitalize the first letter of each word.
            $title = str_replace('-', ' ', $title);
            $title = ucwords($title);
            break;
    }

    return $title;
}

?>