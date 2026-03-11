<?php
// config.php - database connection + safe session start
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start session only if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$db   = 'event_system';
$user = 'root';
$pass = ''; // change if your MySQL root has password

$conn = mysqli_connect($host, $user, $pass, $db);
if(!$conn){
    die("Database connection failed: " . mysqli_connect_error());
}
?>
