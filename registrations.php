<?php
include 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$event_id = intval($_GET['event_id'] ?? 0);

if ($event_id <= 0) {
    die("Invalid event");
}

/* -------------------------
   Fetch event (permission)
--------------------------*/
if ($user['role'] == 'admin') {
    $event_q = mysqli_query($conn, "
        SELECT * FROM events WHERE id = $event_id
    ");
} else {
    $event_q = mysqli_query($conn, "
        SELECT * FROM events 
        WHERE id = $event_id 
        AND organizer_id = {$user['id']}
    ");
}

$event = mysqli_fetch_assoc($event_q);

if (!$event) {
    die("Event not found");
}

/* -------------------------
   Fetch registrations
--------------------------*/
$regs = mysqli_query($conn, "
    SELECT u.name, u.email
    FROM registrations r
    JOIN users u ON r.user_id = u.id
    WHERE r.event_id = $event_id
    ORDER BY r.id DESC
");

$total = mysqli_num_rows($regs);

include 'header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">
        👥 Registrations
    </h3>

    <span class="badge bg-primary fs-6">
        <?= $total ?> Students
    </span>
</div>

<h5 class="text-muted mb-4">
    <?= htmlspecialchars($event['title']) ?>
</h5>

<?php if ($total == 0): ?>

    <div class="alert alert-warning text-center shadow-sm">
        No registrations yet.
    </div>

<?php else: ?>

<!-- 🔍 SEARCH -->
<input type="text" id="searchInput"
       class="form-control mb-3"
       placeholder="🔍 Search student name or email">

<div class="table-responsive">

<table class="table table-bordered table-hover shadow-sm">
    <thead class="table-dark">
        <tr>
            <th style="width:70px;">#</th>
            <th>Name</th>
            <th>Email</th>
        </tr>
    </thead>

    <tbody id="regTable">
        <?php $i = 1; mysqli_data_seek($regs, 0); while ($r = mysqli_fetch_assoc($regs)): ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($r['name']) ?></td>
            <td><?= htmlspecialchars($r['email']) ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</div>

<?php endif; ?>

<a href="dashboard.php" class="btn btn-secondary mt-3">
    ← Back to Dashboard
</a>

<!-- 🔍 SEARCH SCRIPT -->
<script>
document.getElementById("searchInput")?.addEventListener("keyup", function() {
    let value = this.value.toLowerCase();
    document.querySelectorAll("#regTable tr").forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
    });
});
</script>

<?php include 'footer.php'; ?>
