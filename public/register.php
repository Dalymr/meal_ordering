<?php
// public/register.php
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare(
        "INSERT INTO users (username, email, password) VALUES (?, ?, ?)"
    );
    $stmt->execute([$username, $email, $password]);
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Register</title></head>
<body>
<h2>Register</h2>
<form method="POST">
    Username: <input name="username" required><br>
    Email:    <input name="email" type="email" required><br>
    Password: <input name="password" type="password" required><br>
    <button type="submit">Register</button>
</form>
</body>
</html>
