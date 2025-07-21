<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>
<body>
<h1>Welcome to Project 2 (SQLite)</h1>
<?php
if (isset($_SESSION['username'])) {
    echo "<p>Hello, " . htmlspecialchars($_SESSION['username']) . "!</p>";
    echo "<p><a href='logout.php'>Logout</a></p>";
} else {
    echo "<p><a href='login-form-bcrypt.php'>Login with Bcrypt</a></p>";
    echo "<p><a href='login-form-salt.php'>Login with SHA256</a></p>";
}
?>
</body>
</html>