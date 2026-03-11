<?php
include 'config.php';
if(!isset($_SESSION['user'])) exit;

$event_id = intval($_GET['id']);
$user = $_SESSION['user'];

$event = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM events WHERE id=$event_id"));
if(!$event) exit;

// permission
if($user['role']=='organizer' && $event['organizer_id'] != $user['id']){
    exit;
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="attendees_event_'.$event_id.'.csv"');

$output = fopen("php://output", "w");
fputcsv($output, ['Name','Email','Registered At']);

$res = mysqli_query($conn,"
SELECT u.name,u.email,r.registered_at
FROM registrations r
JOIN users u ON r.user_id=u.id
WHERE r.event_id=$event_id
");

while($row = mysqli_fetch_assoc($res)){
    fputcsv($output, $row);
}
fclose($output);
exit;
