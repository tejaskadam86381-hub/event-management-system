<?php
include 'config.php';
if(!isset($_SESSION['user'])) header("Location: login.php");

$event_id = intval($_POST['event_id']);
$present  = $_POST['present'] ?? [];

// delete old attendance (safe re-submit)
mysqli_query($conn,"
    DELETE FROM attendance WHERE event_id=$event_id
");

foreach($present as $uid){
    $uid = intval($uid);
    mysqli_query($conn,"
        INSERT INTO attendance (event_id, user_id, status)
        VALUES ($event_id, $uid, 'present')
    ");
}

header("Location: dashboard.php");
exit;
