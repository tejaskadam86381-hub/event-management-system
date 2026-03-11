<?php
include 'config.php';
include 'header.php';

$cert = $_GET['cert'] ?? '';

if(!$cert){
    echo "<div class='alert alert-danger text-center'>Invalid Certificate</div>";
    include 'footer.php';
    exit;
}
?>

<div class="container mt-5">
  <div class="card shadow p-4 text-center">
    <h3 class="text-success">Certificate Verified ✅</h3>
    <p class="mt-3">
      This certificate <b><?= htmlspecialchars($cert) ?></b>
      is issued by
    </p>
    <h4 class="mt-2">Mulund College of Commerce (MCC)</h4>
  </div>
</div>

<?php include 'footer.php'; ?>
