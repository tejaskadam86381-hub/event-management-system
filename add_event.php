<?php
include 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (!in_array($_SESSION['user']['role'], ['organizer', 'admin'])) {
    echo "Unauthorized Access";
    exit;
}

$error = "";

/* ======================
   FORM SUBMIT
   ====================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title        = trim($_POST['title'] ?? '');
    $description  = trim($_POST['description'] ?? '');
    $venue        = trim($_POST['venue'] ?? '');
    $start_time   = $_POST['start_time'] ?? '';
    $end_time     = $_POST['end_time'] ?? '';
    $capacity     = intval($_POST['capacity'] ?? 0);
    $category     = $_POST['category'] ?? '';
    $organizer_id = $_SESSION['user']['id'];

    /* ===== VALIDATION ===== */
    if ($title === '') {
        $error = "Event title is required";
    } elseif ($description === '') {
        $error = "Description is required";
    } elseif ($venue === '') {
        $error = "Venue is required";
    } elseif ($start_time === '' || $end_time === '') {
        $error = "Start & End time required";
    } elseif ($category === '') {
        $error = "Category is required";
    } elseif ($capacity <= 0) {
        $error = "Capacity must be greater than 0";
    }

    /* ===== INSERT EVENT (NO IMAGE COLUMN) ===== */
    if ($error === '') {

        $title       = mysqli_real_escape_string($conn, $title);
        $description = mysqli_real_escape_string($conn, $description);
        $venue       = mysqli_real_escape_string($conn, $venue);
        $category    = mysqli_real_escape_string($conn, $category);

        $sql = "
            INSERT INTO events
            (title, description, venue, start_time, end_time, capacity, category, organizer_id)
            VALUES
            ('$title', '$description', '$venue', '$start_time', '$end_time',
             $capacity, '$category', $organizer_id)
        ";

        if (mysqli_query($conn, $sql)) {
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Database error. Event not created.";
        }
    }
}

include 'header.php';
?>

<!-- ======================
     CREATE EVENT FORM
     ====================== -->
<div class="container my-5">
  <div class="card mx-auto shadow-lg" style="max-width: 750px;">
    <div class="card-body p-4">

      <h3 class="mb-4 text-center fw-bold">🎉 Create New Event</h3>

      <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>

      <form method="post">

        <!-- TITLE -->
        <div class="mb-3">
          <label class="form-label">Event Title</label>
          <input type="text" name="title" class="form-control"
                 placeholder="Eg. Web Development Bootcamp" required>
        </div>

        <!-- DESCRIPTION -->
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control"
                    rows="4"
                    placeholder="Write event details here..." required></textarea>
        </div>

        <!-- CATEGORY -->
        <div class="mb-3">
          <label class="form-label">Category</label>
          <select name="category" class="form-control" required>
            <option value="">Select Category</option>
            <option value="Technical">Technical</option>
            <option value="Cultural">Cultural</option>
            <option value="Sports">Sports</option>
            <option value="Workshop">Workshop</option>
          </select>
        </div>

        <!-- VENUE -->
        <div class="mb-3">
          <label class="form-label">Venue</label>
          <input type="text" name="venue" class="form-control"
                 placeholder="Eg. Seminar Hall A" required>
        </div>

        <!-- START TIME -->
        <div class="mb-3">
          <label class="form-label">Start Time</label>
          <input type="datetime-local" name="start_time"
                 class="form-control" required>
        </div>

        <!-- END TIME -->
        <div class="mb-3">
          <label class="form-label">End Time</label>
          <input type="datetime-local" name="end_time"
                 class="form-control" required>
        </div>

        <!-- CAPACITY -->
        <div class="mb-4">
          <label class="form-label">Capacity</label>
          <input type="number" name="capacity" class="form-control"
                 placeholder="Eg. 100" min="1" required>
        </div>

        <!-- SUBMIT -->
        <button class="btn btn-success w-100 py-2">
          <i class="bi bi-plus-circle"></i> Create Event
        </button>

      </form>

    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
