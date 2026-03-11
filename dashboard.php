<?php
include 'config.php';
if(!isset($_SESSION['user'])) header("Location: login.php");

$user = $_SESSION['user'];
include 'header.php';

/* GLOBAL STATS */
$total_events = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM events"))[0];
$total_regs   = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM registrations"))[0];
$total_users  = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM users"))[0];
?>

<!-- ===== WELCOME BAR ===== -->
<div class="card shadow-sm p-3 mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap">
    <h5 class="mb-0">
      👋 Welcome, <strong><?= htmlspecialchars($user['name']) ?></strong>
    </h5>
    <div id="liveClock" class="text-muted fw-bold"></div>
  </div>
</div>

<!-- ===== TOP STATS ===== -->
<div class="row mb-4 g-3">

  <div class="col-md-4">
    <div class="stat-card stat-blue text-center shadow-sm">
      <h4><?= $total_events ?></h4>
      <p>Total Events</p>
    </div>
  </div>

  <div class="col-md-4">
    <div class="stat-card stat-green text-center shadow-sm">
      <h4><?= $total_regs ?></h4>
      <p>Total Registrations</p>
    </div>
  </div>

  <div class="col-md-4">
    <div class="stat-card stat-orange text-center shadow-sm">
      <h4><?= $total_users ?></h4>
      <p>Total Users</p>
    </div>
  </div>

</div>

<h3 style=" color : cyan" class="dashboard-title mb-4">
  Dashboard (<?= ucfirst($user['role']) ?>)
</h3>

<?php if($user['role'] === 'student'): ?>

<?php
/* PARTICIPATION LEVEL */
$cnt = mysqli_fetch_row(mysqli_query($conn,"
SELECT COUNT(*) FROM registrations
WHERE user_id = {$user['id']}
"))[0];

$badge="Beginner";
$color="secondary";

if($cnt >= 5){ $badge="Event Champion"; $color="warning"; }
elseif($cnt >= 3){ $badge="Active Participant"; $color="primary"; }
?>

<div class="alert alert-light border shadow-sm">
  🎖 Participation Level:
  <span class="badge bg-<?= $color ?>"><?= $badge ?></span>
</div>

<h5 style="color: orange;" class="mb-3">My Events & Attendance</h5>

<?php
$res = mysqli_query($conn,"
SELECT 
  e.title,
  e.start_time,
  a.status AS att_status
FROM registrations r
JOIN events e ON r.event_id = e.id
LEFT JOIN attendance a 
  ON a.event_id = e.id AND a.user_id = r.user_id
WHERE r.user_id = {$user['id']}
ORDER BY e.start_time DESC
");

if(mysqli_num_rows($res) == 0):
?>
<div class="alert alert-warning">
  You have not registered for any events yet.
</div>
<?php endif; ?>

<?php while($row = mysqli_fetch_assoc($res)): ?>
<div class="card shadow-sm mb-3 p-3">

  <b><?= htmlspecialchars($row['title']) ?></b>

  <small class="text-muted">
    <?= date('d M Y, h:i A', strtotime($row['start_time'])) ?>
  </small>

  <div class="mt-2">
    Attendance:
    <?php if($row['att_status']): ?>
      <span class="badge bg-success"><?= ucfirst($row['att_status']) ?></span>
    <?php else: ?>
      <span class="badge bg-secondary">Not marked yet</span>
    <?php endif; ?>
  </div>

</div>
<?php endwhile; ?>

<?php else: ?>

<?php
$org_id = $user['id'];

/* Organizer stats */
$total_my_events = mysqli_fetch_row(mysqli_query($conn,
"SELECT COUNT(*) FROM events WHERE organizer_id=$org_id"))[0];

$total_my_regs = mysqli_fetch_row(mysqli_query($conn,"
SELECT COUNT(*) FROM registrations r
JOIN events e ON r.event_id = e.id
WHERE e.organizer_id=$org_id"))[0];

$popular = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT e.id, e.title, COUNT(r.id) as total
FROM events e
LEFT JOIN registrations r ON r.event_id = e.id
WHERE e.organizer_id=$org_id
GROUP BY e.id
ORDER BY total DESC
LIMIT 1
"));
?>

<!-- ORGANIZER ANALYTICS -->
<div class="row mb-4 g-3">

  <div class="col-md-4">
    <div class="card shadow text-center p-3">
      <h6>🎉 My Events</h6>
      <h3 class="text-primary"><?= $total_my_events ?></h3>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card shadow text-center p-3">
      <h6>👥 Total Registrations</h6>
      <h3 class="text-success"><?= $total_my_regs ?></h3>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card shadow text-center p-3">
      <h6>🏆 Most Popular Event</h6>
      <?php if($popular && $popular['title']): ?>
        <b><?= htmlspecialchars($popular['title']) ?></b>
        <small class="d-block"><?= $popular['total'] ?> registrations</small>
      <?php else: ?>
        <small>No data yet</small>
      <?php endif; ?>
    </div>
  </div>

</div>

<a class="btn btn-primary mb-3" href="add_event.php">
  + Create Event
</a>

<h5 class="mb-3">My Events</h5>

<?php
$q = ($user['role']==='admin')
? "SELECT e.*, (SELECT COUNT(*) FROM registrations r WHERE r.event_id=e.id) reg_count FROM events e ORDER BY e.start_time DESC"
: "SELECT e.*, (SELECT COUNT(*) FROM registrations r WHERE r.event_id=e.id) reg_count FROM events e WHERE organizer_id={$user['id']} ORDER BY e.start_time DESC";

$res = mysqli_query($conn,$q);

if(mysqli_num_rows($res)==0):
?>
<div class="alert alert-warning">No events created yet.</div>
<?php endif; ?>

<?php while($ev=mysqli_fetch_assoc($res)): ?>
<div class="card event-card mb-3 p-3 shadow-sm">

  <h5><?= htmlspecialchars($ev['title']) ?></h5>

  <span class="badge bg-light border text-dark mb-2">
    👥 <?= $ev['reg_count'] ?> Registrations
  </span>

  <div class="d-flex flex-wrap gap-2">

    <a href="edit_event.php?id=<?= $ev['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
    <a href="delete_event.php?id=<?= $ev['id'] ?>" class="btn btn-sm btn-danger">Delete</a>
    <a href="registrations.php?event_id=<?= $ev['id'] ?>" class="btn btn-sm btn-success">Registrations</a>
    <a href="attendance.php?event_id=<?= $ev['id'] ?>" class="btn btn-sm btn-secondary">Attendance</a>
    <a href="attendance_summary.php?event_id=<?= $ev['id'] ?>" class="btn btn-sm btn-dark">Summary</a>

   

  </div>

</div>
<?php endwhile; ?>

<?php endif; ?>

<?php include 'footer.php'; ?>

<!-- LIVE CLOCK SCRIPT -->
<script>
function updateClock(){
  const now = new Date();
  document.getElementById('liveClock').innerHTML =
    now.toLocaleDateString() + " | " +
    now.toLocaleTimeString();
}
setInterval(updateClock,1000);
updateClock();
</script>
