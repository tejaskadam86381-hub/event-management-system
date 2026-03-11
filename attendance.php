<?php
include 'config.php';
if(!isset($_SESSION['user'])) header("Location: login.php");

$user = $_SESSION['user'];
$event_id = intval($_GET['event_id'] ?? 0);

// only organizer/admin allowed
if($user['role'] == 'student'){
    die("Access denied");
}

// fetch event
$event = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT * FROM events WHERE id=$event_id
"));

if(!$event){
    die("Event not found");
}

// fetch registered students
$res = mysqli_query($conn,"
    SELECT u.id, u.name, u.email
    FROM registrations r
    JOIN users u ON r.user_id = u.id
    WHERE r.event_id = $event_id
");

include 'header.php';
?>

<h3>Attendance – <?= htmlspecialchars($event['title']) ?></h3>

<form method="post" action="save_attendance.php">
<input type="hidden" name="event_id" value="<?= $event_id ?>">

<table class="table table-bordered mt-3">
  <tr>
    <th>#</th>
    <th>Name</th>
    <th>Email</th>
    <th>Present</th>
  </tr>

<?php $i=1; while($row = mysqli_fetch_assoc($res)): ?>
<tr>
  <td><?= $i++ ?></td>
  <td><?= htmlspecialchars($row['name']) ?></td>
  <td><?= htmlspecialchars($row['email']) ?></td>
  <td>
    <input type="checkbox" name="present[]" value="<?= $row['id'] ?>" checked>
  </td>
</tr>
<?php endwhile; ?>
</table>

<button class="btn btn-success">Save Attendance</button>
<a href="dashboard.php" class="btn btn-secondary">Back</a>

</form>

<?php include 'footer.php'; ?>
