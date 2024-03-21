<?php
include_once 'functions.php';
include_once 'db-connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $key_name = $_POST['key'];
    $table_name = $_POST['table'];
    if ($table_name == 'Product' || $table_name == 'Category') {
        $id = intval($id);
    }
    
    $stmt = $conn->prepare("DELETE FROM $table_name WHERE $key_name = :id");
    $stmt->bindParam(':id', $id);
    

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error: '.$stmt->execute();
    }
}