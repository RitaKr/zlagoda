<?php
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
include_once ROOT_PATH . '/functions.php';
include_once ROOT_PATH . '/db-connection.php';
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
    <header class="mainheader">
        <!-- Your navigation menu, logo, etc. -->

        <a href="<? ROOT_PATH ?>/index.php" target="" class="logo">
            <img src="<? ROOT_PATH ?>/assets/img/logo.svg" alt="Zlagoda AIS logo">
            <h2>ZLAGODA</h2>
        </a>

        <nav class="mainmenu">
            <ul>
                <?php if (has_role('manager')): ?>
                    <li class="<?= $_SERVER['PHP_SELF'] == '/employees.php' ? 'active' : '' ?>">
                        <a href="<? ROOT_PATH ?>/employees.php" target="">Employees</a>
                    </li>
                <?php endif; ?>

                <li class="<?= $_SERVER['PHP_SELF'] == '/bills.php' ? 'active' : '' ?>">
                    <a href="<? ROOT_PATH ?>/bills.php" target="">Bills</a>
                </li>
                <li class="<?= $_SERVER['PHP_SELF'] == '/clients.php' ? 'active' : '' ?>">
                    <a href="<? ROOT_PATH ?>/clients.php" target="">Clients</a>
                </li>
                <li class="<?= $_SERVER['PHP_SELF'] == '/products.php' || $_SERVER['PHP_SELF'] == '/products-all.php' || $_SERVER['PHP_SELF'] == '/products-store.php' ? 'active' : '' ?>">
                    <a href="<? ROOT_PATH ?>/products.php" target="">Products</a>
                    <ul class="submenu">
                        <li class="<?=$_SERVER['PHP_SELF'] == '/products-all.php' ? 'active' : '' ?>"><a href="<? ROOT_PATH ?>/products-all.php" target="">All</a></li>
                        <li class="<?= $_SERVER['PHP_SELF'] == '/products-store.php' ? 'active' : '' ?>"><a href="<? ROOT_PATH ?>/products-store.php" target="">In Store</a></li>
                    </ul>
                </li>
                <li class="<?= $_SERVER['PHP_SELF'] == '/categories.php' ? 'active' : '' ?>">
                    <a href="<? ROOT_PATH ?>/categories.php" target="">Categories</a>
                </li>
            </ul>
        </nav>
        <div class="menu-right">
            <?php if (!is_logged_in()): ?>
                <div class="signin-switch">
                    <a href="<? ROOT_PATH ?>/signup.php" target="" <?php echo ($_SERVER['PHP_SELF'] == '/signup.php') ? 'class="active"' : ''; ?>>Sign up</a>
                    <a href="<? ROOT_PATH ?>/signin.php" target="" <?php echo ($_SERVER['PHP_SELF'] == '/signin.php') ? 'class="active"' : ''; ?>>Login</a>
                </div>

            <?php else: ?>
                <a href="<? ROOT_PATH ?>/account.php" alt="My account" title="My account" target="" class="my-account <?= $_SERVER['PHP_SELF'] == '/account.php' ? 'active' : '' ?>">
                    <div>
                        <p class="login"><?= $_SESSION["user_login"]?></p>
                        <p class="role"><?= $_SESSION["role"]?></p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-person-circle"
                        viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                        <path fill-rule="evenodd"
                            d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                    </svg>
                </a>
            <?php endif; ?>

        </div>
    </header>
    <script>
    var currentPage = "<?php echo $currentPage;?>";
    var scrollPosition = <?php echo $_SESSION['scrollPosition'][$currentPage]? $_SESSION['scrollPosition'][$currentPage] : 0; ?>;
</script>

    <!-- Your main content -->