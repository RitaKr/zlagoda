<?php
include 'db-connection.php';
include 'functions.php';

define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission

    // Retrieve form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $empl_id = $_POST["id"];

    register($username, $empl_id, $password);

    exit; // Ensure that no further code is executed after the redirect
}