<?php
include 'config.php';

if(!isset($_SESSION['user'])){
  die("Login required");
}

$user_id = $_SESSION['user']['id'];
$event_id = intval($_GET['event_id']);

/* check registered */
$check = mysqli_query($conn,"
SELECT id FROM registrations
WHERE user_id=$user_id AND event_id=$event_id
");

if(mysqli_num_rows($check)==0){
  die("You are not registered.");
}

/* already marked? */
$exist = mysqli_query($conn,"
SELECT id FROM attendance
WHERE user_id=$user_id AND event_id=$event_id
");

if(mysqli_num_rows($exist)>0){
  die("Attendance already marked");
}

/* mark attendance */
mysqli_query($conn,"
INSERT INTO attendance(user_id,event_id,status)
VALUES($user_id,$event_id,'present')
");

echo "<h2>✅ Attendance Marked Successfully</h2>";
?>
