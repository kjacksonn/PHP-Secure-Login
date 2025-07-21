<?php
session_start();
require_once 'config.inc.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $password = $_POST['password'];

    // Check if user already exists
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        $error = "Email already registered.";
    } else {
        // Generate bcrypt password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Generate salt and SHA256 hash
        $salt = bin2hex(random_bytes(16));
        $sha256Password = hash('sha256', $salt . $password);

        // Insert into users table
        $stmt = $db->prepare("INSERT INTO users (email, firstname, lastname, city, country, password, salt, password_sha256) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$email, $firstname, $lastname, $city, $country, $hashedPassword, $salt, $sha256Password]);

        $success = "Registration successful! You can now login.";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Register</title></head>
<body>
<h2>Register</h2>
<form method="post">
    <label>Email</label><br>
    <input type="email" name="email" required><br><br>

    <label>First Name</label><br>
    <input type="text" name="firstname" required><br><br>

    <label>Last Name</label><br>
    <input type="text" name="lastname" required><br><br>

    <label>City</label><br>
    <input type="text" name="city"><br><br>

    <label>Country</label><br>
    <input type="text" name="country"><br><br>

    <label>Password</label><br>
    <input type="password" name="password" required><br><br>

    <?php if ($error): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php elseif ($success): ?>
        <p style="color:green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <input type="submit" value="Register">
</form>
<p><a href="login-form-bcrypt.php">Already have an account? Login (bcrypt)</a></p>
<p><a href="index.php">Back to Home</a></p>
</body>
</html>
