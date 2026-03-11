<?php
include 'config.php';

$event_id = intval($_GET['event_id']);

$link = "http://localhost/event/mark_attendance.php?event_id=".$event_id;
?>

<h3>Scan QR for Attendance</h3>

<img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=<?= urlencode($link) ?>">

<p>Students scan to mark attendance</p>
