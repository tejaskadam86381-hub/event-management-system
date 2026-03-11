<?php
include 'config.php';
if(!isset($_SESSION['user'])) header("Location: login.php");

$event_id = intval($_GET['id']);
$user = $_SESSION['user'];

$event = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM events WHERE id=$event_id"));
if(!$event){ exit; }

if($user['role']=='organizer' && $event['organizer_id'] != $user['id']){
    echo "Unauthorized"; exit;
}

mysqli_query($conn,"DELETE FROM events WHERE id=$event_id");
header("Location: dashboard.php");
