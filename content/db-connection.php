<?php
$servername = "database"; // Change this if your database server is hosted elsewhere
$username = "admin";
$password = "zlagoda_secret";
$dbname = "zlagoda";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully"; // Don't need to echo here
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

