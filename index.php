<?php
include 'config.php'; 
include 'header.php';


date_default_timezone_set('Asia/Kolkata');

/* =========================
   🔔 EVENT REMINDER
========================= */
if (isset($_SESSION['user'])) {

$user_id = $_SESSION['user']['id'];

$reminder = mysqli_query($conn,"
SELECT e.title,e.start_time
FROM events e
JOIN registrations r ON r.event_id=e.id
WHERE r.user_id=$user_id
AND e.start_time BETWEEN NOW() AND DATE_ADD(NOW(),INTERVAL 1 DAY)
ORDER BY e.start_time ASC
LIMIT 1
");

if($reminder && mysqli_num_rows($reminder)>0){

$re = mysqli_fetch_assoc($reminder);
?>

<div class="alert alert-warning shadow-sm">
⏰ <b>Reminder:</b>
<?= htmlspecialchars($re['title']) ?>
on <?= date('d M Y, h:i A',strtotime($re['start_time'])) ?>
</div>

<?php
}
}

/* =========================
🔥 TRENDING EVENTS
========================= */

$trending = mysqli_query($conn,"
SELECT e.*,
(SELECT COUNT(*) FROM registrations r WHERE r.event_id=e.id) AS total
FROM events e
ORDER BY total DESC
LIMIT 3
");

/* =========================
🔎 SEARCH + FILTER
========================= */

$search = mysqli_real_escape_string($conn,$_GET['search'] ?? '');
$category = mysqli_real_escape_string($conn,$_GET['category'] ?? '');

$where="WHERE 1";

if($search)
$where .= " AND e.title LIKE '%$search%'";

if($category)
$where .= " AND e.category='$category'";

/* =========================
📅 EVENTS LIST
========================= */

$res = mysqli_query($conn,"
SELECT e.*,
(SELECT COUNT(*) FROM registrations r WHERE r.event_id=e.id) AS total
FROM events e
$where
ORDER BY e.start_time ASC
");

?>

<!-- =========================
 HERO SECTION
========================= -->

<div class="hero-section mb-5">

<div class="hero-content text-center text-white">

<span class="badge bg-light text-dark px-4 py-2 shadow-sm mb-3 d-inline-block">
🎓 Mulund College of Commerce (MCC)
</span>

<h1 class="fw-bold mt-3">
College Event Management System
</h1>

<p class="lead">
Discover • Register • Experience amazing college events
</p>

<a href="#events" class="btn btn-light btn-lg mt-3">
Explore Events
</a>

</div>

</div>

<!-- =========================
🔥 TRENDING EVENTS
========================= -->

<h3 class="mb-4 text-danger">
🔥 Trending Events
</h3>

<div class="row mb-5">

<?php while($t=mysqli_fetch_assoc($trending)):

$img='assets/events/default.jpg';

if($t['category']=='Technical') $img='assets/events/tech.jpg';
if($t['category']=='Sports') $img='assets/events/sports.jpg';
if($t['category']=='Cultural') $img='assets/events/cultural.jpg';
if($t['category']=='Workshop') $img='assets/events/workshop.jpg';

?>

<div class="col-md-4 mb-4">

<div class="card event-card shadow border-danger h-100">

<div style="position:relative">

<img src="<?= $img ?>" class="event-img">

<span class="badge bg-danger"
style="position:absolute;top:10px;left:10px">

🔥 Trending

</span>

</div>

<div class="card-body">

<h5><?= htmlspecialchars($t['title']) ?></h5>

<p class="text-muted small">

👥 <?= $t['total'] ?> Registrations

</p>

<a href="event.php?id=<?= $t['id'] ?>"
class="btn btn-outline-danger btn-sm">

View Event

</a>

</div>

</div>

</div>

<?php endwhile; ?>

</div>

<!-- =========================
 UPCOMING EVENTS
========================= -->

<h3 style="color: cyan;" class="dashboard-title mb-4" id="events">
Upcoming Events

<!-- =========================
 SEARCH
========================= -->

<form method="get" class="row mb-4">

<div class="col-md-5 mb-2">

<input type="text"
name="search"
class="form-control"
placeholder="🔍 Search event"
value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">

</div>

<div class="col-md-4 mb-2">

<select name="category" class="form-control">

<option value="">All Categories</option>

<?php
foreach(['Technical','Cultural','Sports','Workshop'] as $c):
?>

<option value="<?= $c ?>"
<?= ($category==$c ? 'selected':'') ?>>

<?= $c ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-3 mb-2">

<button class="btn btn-primary w-100">

<i class="bi bi-search"></i>
Search

</button>

</div>

</form>

<!-- =========================
 EVENTS CARDS
========================= -->

<div class="row">

<?php if(mysqli_num_rows($res)==0): ?>

<div class="text-center text-muted mt-5">

<i class="bi bi-calendar-x"
style="font-size:48px"></i>

<p class="mt-3">
No events found
</p>

</div>

<?php else: ?>

<?php while($e=mysqli_fetch_assoc($res)):

$left = $e['capacity'] - $e['total'];
$now = time();
$end = strtotime($e['end_time']);

$img='assets/events/default.jpg';

if($e['category']=='Technical') $img='assets/events/tech.jpg';
if($e['category']=='Sports') $img='assets/events/sports.jpg';
if($e['category']=='Cultural') $img='assets/events/cultural.jpg';
if($e['category']=='Workshop') $img='assets/events/workshop.jpg';

?>

<div class="col-md-4 mb-4">

<div class="card event-card h-100">

<div class="event-image">

<img src="<?= $img ?>" loading="lazy">

</div>

<div class="card-body d-flex flex-column">

<h5><?= htmlspecialchars($e['title']) ?></h5>

<span class="floating-badge">
<?= htmlspecialchars($e['category']) ?>
</span>

<p class="text-muted small">

<?= substr(strip_tags($e['description']),0,90) ?>...

</p>

<!-- STATUS -->

<div class="mt-auto">

<?php if($now>$end): ?>

<span class="badge bg-secondary">
Completed
</span>

<?php elseif($left>0): ?>

<span class="badge bg-success">
<?= $left ?> seats left
</span>

<?php else: ?>

<span class="badge bg-danger">
Full
</span>

<?php endif; ?>

<!-- BUTTON -->

<div class="mt-3">

<a href="event.php?id=<?= $e['id'] ?>"
class="btn btn-primary btn-sm">

<i class="bi bi-eye"></i>
View

</a>

</div>

</div>

</div>

</div>

</div>

<?php endwhile; ?>

<?php endif; ?>

</div>

<?php include 'footer.php'; ?>