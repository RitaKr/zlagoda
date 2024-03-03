<?php
include 'db-connection.php';
include 'functions.php';

define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Handle form submission

    // Retrieve form data
    $un = $_GET["username"];
    $pass = $_GET["password"];

    authenticate($un, $pass);


    exit; // Ensure that no further code is executed after the redirect
}