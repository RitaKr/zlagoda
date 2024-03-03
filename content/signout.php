<?php
include "functions.php";

if (isset($_POST['signout'])) {
     
    signout();
    header("Location: /index.php");
    exit;
}