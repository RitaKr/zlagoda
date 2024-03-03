<?php
// Check if the requested file exists
if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'])) {
    // Redirect to the 404.php page
    header("Location: /404.php");
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
        Here will be menu
        <!-- Your navigation menu, logo, etc. -->
    </header>