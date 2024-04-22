<?php
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
include_once ROOT_PATH . '/functions.php';
include_once ROOT_PATH . '/db-connection.php';
create_all_tables();
// Check if the requested file exists
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $path)) {
    // Redirect to the 404.php page
    header("Location: /404.php");
    exit;
}
if ($_SERVER['PHP_SELF'] != '/signin.php' && $_SERVER['PHP_SELF'] != '/signup.php' && !is_logged_in()) {
    header("Location: /signin.php");
    exit;
}
if (has_role('cashier') && $_SERVER['PHP_SELF'] == '/employees.php') {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
$currentPage = basename($_SERVER['SCRIPT_NAME']);
$filters = isset ($_SESSION['filtersData'][$currentPage]) ? $_SESSION['filtersData'][$currentPage] : array();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zlagoda AIS</title>
    <link rel="shortcut icon" href="assets/img/favicon.png" type="image/png">
    <link rel="stylesheet" href="build/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Didact+Gothic&family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Quicksand:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- Your CSS files, meta tags, etc. -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
<div class="logo-container">
            <img src="<? ROOT_PATH ?>/assets/img/logo.svg" alt="Zlagoda AIS logo">
            <h2>ZLAGODA</h2>
</div>
<div class="signin-switch">
    <a href="<? ROOT_PATH ?>/signup.php" target="" <?php echo ($_SERVER['PHP_SELF'] == '/signup.php') ? 'class="active"' : ''; ?>>Sign up</a>
    <a href="<? ROOT_PATH ?>/signin.php" target="" <?php echo ($_SERVER['PHP_SELF'] == '/signin.php') ? 'class="active"' : ''; ?>>Login</a>
</div>
 