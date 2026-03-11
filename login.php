<?php
include 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$email = mysqli_real_escape_string($conn,$_POST['email']);
$pass  = $_POST['password'];

$res = mysqli_query($conn,"
SELECT * FROM users WHERE email='$email'
");

if(mysqli_num_rows($res)==1){

$user = mysqli_fetch_assoc($res);

if(password_verify($pass,$user['password'])){

$_SESSION['user']=$user;
header("Location: dashboard.php");
exit;

}else{

$error="Invalid password";

}

}else{

$error="User not found";

}

}
?>

<!doctype html>
<html lang="en">
<head>

<meta charset="utf-8">
<title>MCC Event System | Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">

</head>

<body class="login-ultra-bg">

<div class="login-container">

<div class="login-card">

<div class="login-logo">

<i class="bi bi-mortarboard-fill"></i>

</div>

<h3 class="text-center text-white mb-1">
MCC Event System
</h3>

<p class="text-center text-light small mb-4">
Student Login
</p>

<?php if($error): ?>

<div class="alert alert-danger text-center small">
<?= $error ?>
</div>

<?php endif; ?>

<form method="post">

<div class="input-group mb-3">

<span class="input-group-text">
<i class="bi bi-envelope"></i>
</span>

<input type="email"
name="email"
class="form-control"
placeholder="Email Address"
required>

</div>

<div class="input-group mb-3">

<span class="input-group-text">
<i class="bi bi-key"></i>
</span>

<input type="password"
id="password"
name="password"
class="form-control"
placeholder="Password"
required>

<span class="input-group-text cursor"
onclick="togglePass()">
<i class="bi bi-eye"></i>
</span>

</div>

<button class="btn btn-primary w-100 ultra-btn">

<i class="bi bi-box-arrow-in-right"></i>
Login

</button>

<div class="text-center mt-3 small text-light">

New user?

<a href="signup.php" class="text-info">
Create account
</a>

</div>

</form>

</div>

</div>

<script>

function togglePass(){

let pass=document.getElementById("password");

pass.type = pass.type === "password" ? "text" : "password";

}

</script>

</body>
</html>