<?php
include 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];

/* =========================
   GET EVENT ID
   ========================= */
$event_id = intval($_POST['event_id'] ?? 0);

if ($event_id <= 0) {
    $_SESSION['error'] = "Invalid event.";
    header("Location: index.php");
    exit;
}

/* =========================
   CHECK EVENT EXISTS
   ========================= */
$eventRes = mysqli_query($conn, "SELECT * FROM events WHERE id = $event_id");
$event = mysqli_fetch_assoc($eventRes);

if (!$event) {
    $_SESSION['error'] = "Event not found.";
    header("Location: index.php");
    exit;
}

/* =========================
   CHECK EVENT TIME (CLOSED)
   ========================= */
if (strtotime($event['start_time']) <= time()) {
    $_SESSION['error'] = "Registration closed. Event already started.";
    header("Location: event.php?id=$event_id");
    exit;
}

/* =========================
   CHECK ALREADY REGISTERED
   ========================= */
$chk = mysqli_query($conn, "
    SELECT id FROM registrations
    WHERE user_id = $user_id AND event_id = $event_id
");

if (mysqli_num_rows($chk) > 0) {
    $_SESSION['error'] = "You are already registered for this event.";
    header("Location: event.php?id=$event_id");
    exit;
}

/* =========================
   CHECK SEATS
   ========================= */
$total = mysqli_fetch_row(
    mysqli_query($conn, "
        SELECT COUNT(*) FROM registrations
        WHERE event_id = $event_id
    ")
)[0];

if ($total >= $event['capacity']) {
    $_SESSION['error'] = "Sorry, this event is full.";
    header("Location: event.php?id=$event_id");
    exit;
}

/* =========================
   REGISTER USER
   ========================= */
$insert = mysqli_query($conn, "
    INSERT INTO registrations (user_id, event_id)
    VALUES ($user_id, $event_id)
");

if ($insert) {
    $_SESSION['success'] = "🎉 Registration successful! See you at the event.";
} else {
    $_SESSION['error'] = "Something went wrong. Please try again.";
}

header("Location: event.php?id=$event_id");
exit;
