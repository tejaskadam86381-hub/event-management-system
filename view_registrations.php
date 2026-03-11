<?php
include 'config.php';

if(!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['organizer','admin'])){
    exit('Unauthorized');
}

$event_id = intval($_GET['event_id'] ?? 0);

$res = mysqli_query($conn,"
    SELECT u.name, u.email
    FROM registrations r
    JOIN users u ON r.user_id = u.id
    WHERE r.event_id = $event_id
");

if(mysqli_num_rows($res) == 0){
    echo "<div class='alert alert-warning'>No registrations yet.</div>";
    exit;
}

echo "<div class='card mt-3'>
        <div class='card-header bg-dark text-white'>
            Registered Students
        </div>
        <ul class='list-group list-group-flush'>";

while($row = mysqli_fetch_assoc($res)){
    echo "<li class='list-group-item'>
            <b>{$row['name']}</b><br>
            <small>{$row['email']}</small>
          </li>";
}

echo "</ul></div>";
