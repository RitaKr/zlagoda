<?php
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
include ROOT_PATH . '/functions.php';
include ROOT_PATH . '/db-connection.php';

// Check if the requested file exists
if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'])) {
    // Redirect to the 404.php page
    header("Location: /404.php");
    exit;
}

if ($_SERVER['PHP_SELF'] != '/signin.php' && $_SERVER['PHP_SELF'] != '/signup.php' && !is_logged_in()) {
    header("Location: /signin.php");
    exit;
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zlagoda AIS</title>
    <link rel="shortcut icon" href="assets/img/favicon.png" type="image/png">
    <link rel="stylesheet" href="build/css/style.css">
    <!-- Your CSS files, meta tags, etc. -->
</head>

<body>
    <header>
        <!-- Your navigation menu, logo, etc. -->
        <nav class="mainmenu">
            <ul>
                <li>
                    <a href="<? ROOT_PATH ?>/categories.php" target="">Product categories</a>
                </li>

                <li>
                    <a href="<? ROOT_PATH ?>/products.php" target="">Products</a>
                </li>

                <li>
                    <a href="<? ROOT_PATH ?>/store-products.php" target="">Products in store</a>
                </li>

                <a href="<? ROOT_PATH ?>/employees.php" target="">Employees</a>
                </li>
                <li>
                    <a href="<? ROOT_PATH ?>/clients.php" target="">Clients</a>
                </li>
                <li>
                    <a href="<? ROOT_PATH ?>/bills.php" target="">Bills</a>
                </li>
            </ul>
        </nav>
        <div>
            <?php if (!is_logged_in()): ?>
                <a href="<? ROOT_PATH ?>/signin.php" target="">Sign in</a>
            <?php else: ?>
                <a href="<? ROOT_PATH ?>/account.php" target="">My account</a>
            <?php endif; ?>

        </div>
    </header>