<?php
// public/admin/stats.php
require '../../includes/auth_check.php';
require '../../config/db.php';
if (!$_SESSION['user']['is_admin']) {
    header('Location: ../index.php');
    exit;
}

// Fetch stats
$userCount  = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$mealCount  = $pdo->query("SELECT COUNT(*) FROM meals")->fetchColumn();
$orderCount = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();

include '../../includes/header.php';
?>
<h2>Statistiques</h2>
<ul>
  <li>Utilisateurs: <?= $userCount ?></li>
  <li>Repas:       <?= $mealCount ?></li>
  <li>Commandes:   <?= $orderCount ?></li>
</ul>
<?php include '../../includes/footer.php'; ?>
