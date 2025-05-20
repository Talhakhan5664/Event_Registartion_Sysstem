<?php

include 'db.php';

session_start();
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    } else {
        header('location:login.php');
        exit;
    }
session_unset();    
session_destroy();
header('location:login.php');


?>