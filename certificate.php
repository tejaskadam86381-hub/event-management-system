<?php
include 'config.php';

if(!isset($_SESSION['user'])){
  die("Unauthorized");
}

$user = $_SESSION['user'];
$event_id = intval($_GET['event_id'] ?? 0);
$cert_no = "MCC-" . date('Y') . "-" . str_pad($_SESSION['user']['id'], 4, "0", STR_PAD_LEFT);


$event = mysqli_fetch_assoc(
  mysqli_query($conn,"SELECT * FROM events WHERE id=$event_id")
);

if(!$event){
  die("Event not found");
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Certificate</title>
  <style>
    body {
      font-family: 'Georgia', serif;
      background: #f4f6f9;
      padding: 40px;
    }
    .cert {
      background: white;
      padding: 50px;
      border: 10px solid #1cc88a;
      text-align: center;
    }
    h1 {
      color: #1cc88a;
      font-size: 40px;
    }
    .name {
      font-size: 28px;
      font-weight: bold;
      margin: 20px 0;
    }
    .event {
      font-size: 20px;
    }
    .footer {
      margin-top: 40px;
      font-size: 14px;
    }

    .btn-success:hover {
  transform: scale(1.05);
  box-shadow: 0 0 15px rgba(25,135,84,0.6);
}

  </style>
</head>
<body>

<div class="cert">
  <h1>Certificate of Participation</h1>

 

  <p>This is to certify that</p>

  <div class="name"><?= htmlspecialchars($user['name'] ?? 'Student') ?></div>

  <p>has successfully participated in</p>

  <div class="event">
    <?= htmlspecialchars($event['title']) ?>
  </div>

  <p>organized by</p>

  <b>Mulund College of Commerce (MCC)</b>

  <div class="footer">
    Date: <?= date('d M Y', strtotime($event['end_time'])) ?><br>
    Department of Commerce
  </div>

    <p style="margin-top:20px;font-size:14px;">
Certificate ID: <b><?= $cert_no ?></b>
</p>

<a href="verify.php?cert=<?= $cert_no ?>"
   class="btn btn-outline-primary mt-3">
   Verify Certificate
</a>

 

</div>

<script>
window.print();
</script>

</body>
</html>
