<?php
include 'config.php';
date_default_timezone_set('Asia/Kolkata');
include 'header.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    echo "<div class='alert alert-danger text-center'>Invalid Event</div>";
    include 'footer.php';
    exit;
}

/* ======================
   EVENT DATA
====================== */
$ev = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT e.*,
    (SELECT COUNT(*) FROM registrations r WHERE r.event_id=e.id) AS total
    FROM events e WHERE e.id=$id
"));

if (!$ev) {
    echo "<div class='alert alert-danger text-center'>Event not found</div>";
    include 'footer.php';
    exit;
}

$left   = $ev['capacity'] - $ev['total'];
$now    = time();
$start  = strtotime($ev['start_time']);
$end    = strtotime($ev['end_time']);

/* ======================
   CHECK REGISTRATION
====================== */
$isRegistered = false;
$uid = 0;

if (isset($_SESSION['user'])) {
    $uid = $_SESSION['user']['id'];

    $chk = mysqli_query($conn,"
        SELECT id FROM registrations
        WHERE user_id=$uid AND event_id=$id
    ");

    if(mysqli_num_rows($chk)>0){
        $isRegistered = true;
    }
}

/* ======================
   AVERAGE RATING
====================== */
$avgRating = mysqli_fetch_row(mysqli_query($conn,"
    SELECT AVG(rating) FROM ratings WHERE event_id=$id
"))[0];

$avgRating = $avgRating ? round($avgRating,1) : 0;

/* ======================
   CHECK USER RATED
====================== */
$userRated = false;
if ($isRegistered) {
    $rchk = mysqli_query($conn,"
        SELECT id FROM ratings
        WHERE user_id=$uid AND event_id=$id
    ");
    $userRated = mysqli_num_rows($rchk) > 0;
}

/* ======================
   CATEGORY IMAGE
====================== */
$img='assets/events/default.jpg';
if ($ev['category']=='Technical') $img='assets/events/tech.jpg';
if ($ev['category']=='Sports')    $img='assets/events/sports.jpg';
if ($ev['category']=='Cultural')  $img='assets/events/cultural.jpg';
if ($ev['category']=='Workshop')  $img='assets/events/workshop.jpg';
?>

<div class="container my-5">

  <!-- EVENT IMAGE -->
  <div class="mb-4 text-center">
    <img src="<?= $img ?>" class="img-fluid rounded shadow"
         style="max-height:320px;object-fit:cover;width:100%;">
  </div>

  <div class="card shadow-lg p-4">

    <span class="badge bg-primary mb-2">
      <?= htmlspecialchars($ev['category']) ?>
    </span>

    <h2><?= htmlspecialchars($ev['title']) ?></h2>

    <p class="text-muted">
      📅 <?= date('d M Y',$start) ?> |
      ⏰ <?= date('h:i A',$start) ?> – <?= date('h:i A',$end) ?>
    </p>

    <p class="text-muted">
      📍 <?= htmlspecialchars($ev['venue']) ?>
    </p>

    <hr>

    <p><?= nl2br(htmlspecialchars($ev['description'])) ?></p>

    <!-- SEAT PROGRESS -->
    <?php $percent = ($ev['total']/$ev['capacity'])*100; ?>
    <div class="mt-4">
      <small><?= $ev['total'] ?> / <?= $ev['capacity'] ?> seats filled</small>
      <div class="progress" style="height:10px;">
        <div class="progress-bar bg-success"
             style="width:<?= $percent ?>%"></div>
      </div>
    </div>

    <!-- STATUS -->
    <div class="mt-3">
      <?php if($now > $end): ?>
        <span class="badge bg-secondary">Completed</span>
      <?php elseif($left>0): ?>
        <span class="badge bg-success"><?= $left ?> Seats Left</span>
      <?php else: ?>
        <span class="badge bg-danger">Event Full</span>
      <?php endif; ?>
    </div>

    <!-- ACTION BUTTON -->
    <div class="mt-4">

    <?php if(isset($_SESSION['user']) && $_SESSION['user']['role']=='student'): ?>

        <?php if($now < $start && $left>0 && !$isRegistered): ?>
            <form method="post" action="register.php">
                <input type="hidden" name="event_id" value="<?= $ev['id'] ?>">
                <button class="btn btn-primary btn-lg">
                    🎫 Register Now
                </button>
            </form>

        <?php elseif($isRegistered && $now > $end): ?>
            <a href="certificate.php?event_id=<?= $ev['id'] ?>"
               class="btn btn-success btn-lg">
               🎓 Download Certificate
            </a>

        <?php elseif($isRegistered): ?>
            <span class="badge bg-info fs-6">You are Registered</span>

        <?php else: ?>
            <button class="btn btn-secondary" disabled>
              Registration Closed
            </button>
        <?php endif; ?>

    <?php else: ?>
        <a href="login.php" class="btn btn-outline-primary">
            Login to Register
        </a>
    <?php endif; ?>

    </div>

    <!-- ⭐ RATING SECTION -->
    <hr class="mt-5">

    <h4>⭐ Event Rating</h4>

    <p class="mb-2">
      Average Rating:
      <strong><?= $avgRating ?></strong> / 5
    </p>

    <!-- Stars -->
    <div style="font-size:24px;">
    <?php
    for($i=1;$i<=5;$i++){
        echo ($i <= $avgRating)
            ? "<span style='color:#ffc107;'>★</span>"
            : "<span style='color:#ddd;'>★</span>";
    }
    ?>
    </div>

    <?php if(isset($_SESSION['user']) 
            && $_SESSION['user']['role']=='student'
            && $isRegistered
            && $now > $end): ?>

        <?php if(!$userRated): ?>

        <form method="post" action="rate.php" class="mt-3">
            <input type="hidden" name="event_id" value="<?= $id ?>">

            <select name="rating" class="form-control mb-2" required>
                <option value="">Give Rating</option>
                <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                <option value="4">⭐⭐⭐⭐ Good</option>
                <option value="3">⭐⭐⭐ Average</option>
                <option value="2">⭐⭐ Poor</option>
                <option value="1">⭐ Very Bad</option>
            </select>

            <button class="btn btn-warning">
                Submit Rating
            </button>
        </form>

        <?php else: ?>
            <div class="alert alert-info mt-3">
                You already rated this event 👍
            </div>
        <?php endif; ?>

    <?php elseif($now < $end): ?>
        <small class="text-muted">
          Rating available after event completion.
        </small>
    <?php endif; ?>

  </div>
</div>

<?php include 'footer.php'; ?>
