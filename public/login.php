<?php
// public/login.php
session_start();
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        header('Location: index.php');
        exit;
    } else {
        $error = "Invalid login credentials";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>
<h2>Login</h2>
<?php if (isset($error)): ?>
    <p style="color:red;"><?= $error ?></p>
<?php endif; ?>
<form method="POST">
    Email:    <input name="email" type="email" required><br>
    Password: <input name="password" type="password" required><br>
    <button type="submit">Login</button>
</form>
</body>
</html>
