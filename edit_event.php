<?php
include 'config.php';
if(!isset($_SESSION['user'])) header("Location: login.php");

$event_id = intval($_GET['id']);
$user = $_SESSION['user'];

$event = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM events WHERE id=$event_id"));
if(!$event){ echo "Event not found"; exit; }

// permission check
if($user['role']=='organizer' && $event['organizer_id'] != $user['id']){
    echo "Unauthorized"; exit;
}

if($_SERVER['REQUEST_METHOD']=='POST'){
    $title = mysqli_real_escape_string($conn,$_POST['title']);
    $desc  = mysqli_real_escape_string($conn,$_POST['description']);
    $venue = mysqli_real_escape_string($conn,$_POST['venue']);
    $start = $_POST['start_time'];
    $end   = $_POST['end_time'];
    $cap   = intval($_POST['capacity']);

    mysqli_query($conn,"
        UPDATE events SET
        title='$title',
        description='$desc',
        venue='$venue',
        start_time='$start',
        end_time='$end',
        capacity=$cap
        WHERE id=$event_id
    ");
    header("Location: dashboard.php");
    exit;
}

include 'header.php';
?>

<h3>Edit Event</h3>
<form method="post">
  <input class="form-control mb-2" name="title" value="<?= $event['title'] ?>" required>
  <textarea class="form-control mb-2" name="description"><?= $event['description'] ?></textarea>
  <input class="form-control mb-2" name="venue" value="<?= $event['venue'] ?>" required>
  <input class="form-control mb-2" type="datetime-local" name="start_time" value="<?= date('Y-m-d\TH:i',strtotime($event['start_time'])) ?>">
  <input class="form-control mb-2" type="datetime-local" name="end_time" value="<?= date('Y-m-d\TH:i',strtotime($event['end_time'])) ?>">
  <input class="form-control mb-2" type="number" name="capacity" value="<?= $event['capacity'] ?>">
  <button class="btn btn-success">Update</button>
</form>

<?php include 'footer.php'; ?>
