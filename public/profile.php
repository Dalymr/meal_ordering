<?php
// public/profile.php
require '../includes/auth_check.php';
include '../includes/header.php';
?>
<h2>Mon Profil</h2>
<p>Username: <?= $_SESSION['user']['username'] ?></p>
<p>Email:    <?= $_SESSION['user']['email'] ?></p>
<?php include '../includes/footer.php'; ?>
