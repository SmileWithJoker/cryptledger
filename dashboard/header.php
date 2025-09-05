<?php
require "../includes/header_title.php";
include_once "dashboardquery.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo getPageTitle(); ?> | World Liberty Financial</title>

    <!-- Favicon -->
    <link rel="icon" href="assets/image/favicon/favicon.png" type="image/png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">

    <!-- Imported Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /*opening of css code for this project */

        /* Sidebar Styling */
        .sidebar {
            width: 250px;
            background-color: #1a1e21;
            padding: 2rem 1rem;
            height: 100vh;
            overflow-y: auto;
            border-right: 1px solid #343a40;
            transition: transform 0.3s ease-in-out;
            z-index: 1050;
            position: fixed;
            top: 0;
            left: 0;
        }

        .sidebar-header {
            margin-bottom: 30px;
        }

        .sidebar-logo {
            width: 60px;
            margin-bottom: 5px;
        }

        #sidebar-menu {
            list-style: none;
            padding: 0;
        }

        #sidebar-menu li {
            margin: 10px 0;
        }

        .sidebar-closed {
            transform: translateX(-100%);
        }

        .sidebar-toggle-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 2100;
            background-color: #1C1917;
            color: #FAFAF9;
            border: none;
            font-size: 2em;
            border-radius: 6px;
            padding: 6px 12px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .nav-link.sidebar-link {
            color: #f8f9fa;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            transition: background-color 0.2s ease;
        }

        .nav-link.sidebar-link:hover,
        .nav-link.sidebar-link.active {
            background-color: #343a40;
        }

        /* Content area */
        .content {
            margin-left: 0;
            transition: margin-left 0.3s ease-in-out;
            padding: 2rem;
        }

        section.container {
            padding: 16px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            color: #FAFAF9;
        }

        .max-w-md {
            width: 100%;
        }

        /* Transaction listing */
        /* Title */
        h1 {
            font-size: 2.25rem;
            font-weight: bold;
            margin-bottom: 8px;
            color: #FAFAF9;
        }

        p {
            color: #FAFAF9;
            font-size: 14px;
        }

        /* Card container */
        .bg-gray-900 {
            background-color: #1C1917;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.4);
            border: 1px solid #1f2937;
        }

        /* Header row */
        .bg-gray-900>.flex {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 16px;
            border-bottom: 1px solid #2e2d33;
            padding-bottom: 8px;
        }

        .bg-gray-900 span {
            font-weight: 500;
        }

        .bg-gray-900 span.w-1/2 {
            width: 50%;
        }

        .bg-gray-900 span.text-right {
            text-align: right;
        }

        /* Transactions container */
        #transactions-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE 10+ */
        }

        #transactions-container::-webkit-scrollbar {
            display: none;
            /* Chrome, Safari */
        }


        /* Each transaction row */
        #transactions-container .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #2e2d33;
            padding: 12px;
            font-size: 14px;
        }

        #transactions-container .row span:first-child {
            color: #FAFAF9;
        }

        #transactions-container .row span:last-child {
            color: #FAFAF9;
            font-family: monospace;
        }

        /* CSS for card-custom */
        .card-custom {
            background-color: #2e2d33;
            border-radius: 1rem;
            padding: 2rem;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-custom h3 {
            font-weight: 600;
            color: #FAFAF9;
            margin-bottom: 0.5rem;
        }

        .card-custom p {
            color: #FAFAF9;
            font-weight: 400;
        }

        .icon-container {
            position: relative;
            width: 8rem;
            height: 8rem;
            margin: 0 auto 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .icon-circle {
            width: 100%;
            height: 100%;
            background-color: #FEED8B;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .icon-circle i {
            color: #2c2c2c;
            font-size: 2.5rem;
        }

        /* The animated rings */
        .ring {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 8rem;
            height: 8rem;
            border-radius: 50%;
            border: 2px solid #FEED8B;
            transform: translate(-50%, -50%);
            z-index: 1;
        }

        .ring.ring1 {
            animation: pulse 2s infinite;
        }

        .ring.ring2 {
            animation: pulse 2s infinite 0.5s;
            /* Delay the second animation */
        }

        .ring.ring3 {
            animation: pulse 2s infinite 1s;
            /* Delay the third animation */
        }

        @keyframes pulse {
            0% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }

            100% {
                transform: translate(-50%, -50%) scale(1.5);
                opacity: 0;
            }
        }

        /* CSS for card sponsor */
        .card-custom-sponsor {
            background-color: #2e2d33;
            border-radius: 1rem;
            padding: 2rem;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }

        .logo-container .logo {
            height: 2.5rem;
            width: auto;
        }

        .logo-container .logo.peckshield {
            height: 3rem;
            /* Adjust height for this specific logo */
        }

        .logo-container .logo.blocksec {
            height: 2.5rem;
        }

        .logo-container .logo.zokyo {
            height: 2.5rem;
        }

        .logo-container .logo-name {
            font-size: 1.5rem;
            font-weight: 600;
        }

        /* Using a filter to make logos white */
        .peckshield-logo {
            filter: brightness(0) invert(1);
        }

        /* CSS for card custom crypto */
        .card-custom-crypto {
            background-color: #2e2d33;
            border-radius: 0.5rem;
            padding: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
            /* Makes the entire anchor tag a block element */
        }

        .card-custom-crypto:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
        }

        .crypto-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .crypto-header img {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
        }

        .price-info {
            text-align: right;
        }

        .current-price {
            font-size: 0.875rem;
        }

        .change-percentage {
            font-size: 0.75rem;
            display: block;
        }

        .positive {
            color: #27c870;
        }

        .negative {
            color: #e44f50;
        }

        .crypto-name {
            font-size: 0.875rem;
            font-weight: 500;
        }

        .other-info {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            color: #a0a0a0;
        }

        /* Media qeries */
        /* On larger screens, sidebar always visible */
        @media (min-width: 992px) {
            .sidebar {
                transform: translateX(0) !important;
                position: fixed;
            }
        }

        @media (max-width: 767px) {
            .sidebar-header {
                margin-top: 55px;
                margin-bottom: 5px;
            }
        }
        
        /* Content area */
        @media (min-width: 992px) {
            .content {
                margin-left: 250px;
            }
        }
    </style>
</head>
