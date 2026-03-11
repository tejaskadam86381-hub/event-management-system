<?php
include 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$name  = mysqli_real_escape_string($conn,$_POST['name']);
$email = mysqli_real_escape_string($conn,$_POST['email']);
$role  = mysqli_real_escape_string($conn,$_POST['role']);
$pass  = $_POST['password'];

/* CHECK EMAIL */

$check = mysqli_query($conn,"
SELECT id FROM users WHERE email='$email'
");

if(mysqli_num_rows($check)>0){

$error="Email already registered";

}else{

$hashed=password_hash($pass,PASSWORD_DEFAULT);

mysqli_query($conn,"
INSERT INTO users(name,email,password,role)
VALUES('$name','$email','$hashed','$role')
");

header("Location: login.php");
exit;

}

}
?>

<!doctype html>
<html lang="en">

<head>

<meta charset="utf-8">

<title>MCC Event System | Sign Up</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">

</head>

<body class="login-ultra-bg">

<div class="login-container">

<div class="login-card">

<div class="login-logo">

<i class="bi bi-person-plus"></i>

</div>

<h3 class="text-center text-white mb-1">
Create Account
</h3>

<p class="text-center text-light small mb-4">
Join MCC Event System
</p>

<?php if($error): ?>

<div class="alert alert-danger text-center small">
<?= $error ?>
</div>

<?php endif; ?>

<form method="post">

<!-- NAME -->

<div class="input-group mb-3">

<span class="input-group-text">
<i class="bi bi-person"></i>
</span>

<input type="text"
name="name"
class="form-control"
placeholder="Full Name"
required>

</div>

<!-- EMAIL -->

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

<!-- ROLE -->

<div class="input-group mb-3">

<span class="input-group-text">
<i class="bi bi-people"></i>
</span>

<select name="role" class="form-control" required>

<option value="">Register As</option>
<option value="student">Student</option>
<option value="organizer">Organizer</option>

</select>

</div>

<!-- PASSWORD -->

<div class="input-group mb-3">

<span class="input-group-text">
<i class="bi bi-key"></i>
</span>

<input type="password"
id="password"
name="password"
class="form-control"
placeholder="Create Password"
required>

<span class="input-group-text cursor"
onclick="togglePass()">

<i class="bi bi-eye"></i>

</span>

</div>

<button class="btn btn-primary w-100 ultra-btn">

<i class="bi bi-person-check"></i>
Create Account

</button>

<div class="text-center mt-3 small text-light">

Already registered?

<a href="login.php" class="text-info">

Login here

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