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
    <li><a href="manage_users.php">Users</a></li>
    <li><a href="manage_meals.php">Meals</a></li>
    <li><a href="manage_orders.php">Orders</a></li>
</ul>
<?php include '../../includes/footer.php'; ?>
