<?php
// public/admin/dashboard.php
require '../../includes/auth_check.php';
if (!$_SESSION['user']['is_admin']) {
    header('Location: ../index.php');
    exit;
}
include '../../includes/header.php';
?>
<h2>Admin Dashboard</h2>
<ul>
  <li><a href="manage_users.php">Gérer les utilisateurs</a></li>
  <li><a href="manage_meals.php">Gérer les repas</a></li>
  <li><a href="manage_orders.php">Gérer les commandes</a></li>
  <li><a href="stats.php">Statistiques</a></li>
</ul>
<?php include '../../includes/footer.php'; ?>