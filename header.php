<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>

<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <title>MCC Event System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">


  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

  <!-- Bootstrap -->

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->

  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Google Font -->

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

  <!-- Custom CSS -->

  <link rel="stylesheet" href="styles.css">
</head>
<body>

<!-- NAVBAR -->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
  <div class="container">

```
<!-- BRAND -->
<a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
  <i class="bi bi-mortarboard-fill fs-4 text-warning"></i>
  <div class="lh-sm">
    <div class="fw-bold">MCC Event System</div>
    <small class="text-muted" style="font-size:11px;">
      Mulund College of Commerce
    </small>
  </div>
</a>

<!-- MOBILE TOGGLE -->
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
  <span class="navbar-toggler-icon"></span>
</button>

<!-- NAV LINKS -->
<div class="collapse navbar-collapse" id="navMenu">
  <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">

    <li class="nav-item">
      <a class="nav-link nav-hover" href="index.php">
        <i class="bi bi-house-door"></i> Home
      </a>
    </li>

    <?php if(isset($_SESSION['user'])): ?>

      <li class="nav-item">
        <a class="nav-link nav-hover" href="dashboard.php">
          <i class="bi bi-speedometer2"></i> Dashboard
        </a>
      </li>

      <!-- DARK MODE -->
      <li class="nav-item">
        <button id="darkToggle" class="btn btn-sm btn-outline-light ms-lg-2">
          🌙
        </button>
      </li>

      <li class="nav-item">
        <a class="nav-link nav-hover text-danger" href="logout.php">
          <i class="bi bi-box-arrow-right"></i> Logout
        </a>
      </li>

    <?php else: ?>

      <li class="nav-item">
        <a class="nav-link nav-hover" href="login.php">
          <i class="bi bi-box-arrow-in-right"></i> Login
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link nav-hover" href="signup.php">
          <i class="bi bi-person-plus"></i> Sign Up
        </a>
      </li>

    <?php endif; ?>

  </ul>
</div>
```

  </div>
</nav>

<div class="container mt-4">
