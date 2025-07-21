<?php
session_start();
require_once 'config.inc.php';

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $salted_input = hash('sha256', $user['salt'] . $password);
        if ($salted_input === $user['password_sha256']) {
            $_SESSION['username'] = $user['firstname'];
            header("Location: index.php");
            exit();
        }
    }
    $error = "Invalid email or password.";
}
?>
<!DOCTYPE html>
<html>
<head><title>Login (SHA256 + Salt)</title></head>
<body>
<h2>Login (SHA256 + Salt)</h2>
<form method="post">
    <label>Your email</label><br>
    <input type="email" name="email" required><br><br>

    <label>Your password</label><br>
    <input type="password" name="password" required><br><br>

    <?php if ($error): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <input type="submit" value="Login">
</form>
<p><a href="registration-form.php">You don't have an account? Register here.</a></p>
<p><a href="index.php">Don't want to login? Return to home.</a></p>
</body>
</html>
