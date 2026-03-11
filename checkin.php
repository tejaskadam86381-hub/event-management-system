<?php
include 'config.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

$user_id  = $_SESSION['user']['id'];
$event_id = intval($_GET['event_id'] ?? 0);

// check registration
$reg = mysqli_query($conn,"
    SELECT 1 FROM registrations
    WHERE event_id=$event_id AND user_id=$user_id
");

if(mysqli_num_rows($reg) == 0){
    die("Not registered for this event");
}

// prevent duplicate
$chk = mysqli_query($conn,"
    SELECT 1 FROM attendance
    WHERE event_id=$event_id AND user_id=$user_id
");

if(mysqli_num_rows($chk) > 0){
    die("Attendance already marked");
}

// mark attendance
mysqli_query($conn,"
    INSERT INTO attendance(event_id, user_id)
    VALUES($event_id, $user_id)
");

header("Location: dashboard.php");
exit;
