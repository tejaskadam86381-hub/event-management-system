<?php
include 'config.php';
if(!isset($_SESSION['user'])) header("Location: login.php");

$user = $_SESSION['user'];
if($user['role'] == 'student'){
    die("Access denied");
}

$event_id = intval($_GET['event_id'] ?? 0);

// fetch event
$event = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT title FROM events WHERE id=$event_id
"));

// total registered
$total_reg = mysqli_fetch_row(mysqli_query($conn,"
    SELECT COUNT(*) FROM registrations WHERE event_id=$event_id
"))[0];

// total present
$total_present = mysqli_fetch_row(mysqli_query($conn,"
    SELECT COUNT(*) FROM attendance 
    WHERE event_id=$event_id AND status='present'
"))[0];

include 'header.php';
?>

<h3>Attendance Summary</h3>

<div class="card p-3 mt-3">
  <h5><?= htmlspecialchars($event['title']) ?></h5>

  <p><b>Total Registered:</b> <?= $total_reg ?></p>
  <p><b>Total Present:</b> <?= $total_present ?></p>
</div>

<a href="dashboard.php" class="btn btn-secondary mt-3">Back</a>

<?php include 'footer.php'; ?>
