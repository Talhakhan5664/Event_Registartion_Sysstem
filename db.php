<?php 
$host="127.0.0.1";
$username="root";
$password="";
$db="event_registration_system";

$conn =  mysqli_connect ($host, $username, $password, $db);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }
    //echo "Connected successfully";
?>
