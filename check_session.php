<?php
include 'config.php';

echo "<h2>Session check</h2>";
echo "PHPSESSID cookie: ";
if(isset($_COOKIE[session_name()])){
    echo htmlspecialchars($_COOKIE[session_name()]);
} else {
    echo "No session cookie found";
}

echo "<pre> \n\$_SESSION:\n";
print_r($_SESSION);
echo "</pre>";
?>
