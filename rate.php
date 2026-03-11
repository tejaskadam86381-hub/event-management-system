<?php
include 'config.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];
$event_id = intval($_POST['event_id'] ?? 0);
$rating = intval($_POST['rating'] ?? 0);

if($event_id > 0 && $rating >=1 && $rating <=5){

    mysqli_query($conn,"
        INSERT INTO ratings (user_id,event_id,rating)
        VALUES ($user_id,$event_id,$rating)
    ");

}

header("Location: event.php?id=$event_id");
exit;
