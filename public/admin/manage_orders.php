<?php
// public/admin/manage_orders.php
require '../../includes/auth_check.php';
require '../../config/db.php';
if (!$_SESSION['user']['is_admin']) {
    header('Location: ../index.php');
    exit;

    // Insert this right after session/auth check:
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM order_items WHERE order_id = ?");
    $stmt->execute([$_POST['id']]);
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    header('Location: manage_orders.php');
    exit;
}

}
$stmt = $pdo->query(
    "SELECT o.id, u.username, o.total_price, o.order_date
     FROM orders o
     JOIN users u ON o.user_id = u.id
     ORDER BY o.order_date DESC"
);
$orders = $stmt->fetchAll();
include '../../includes/header.php';
?>
<h2>Manage Orders</h2>
<table>
  <tr><th>Order#</th><th>User</th><th>Total</th><th>Date</th></tr>
  <?php foreach($orders as $o): ?>
  <tr>
    <td><?= $o['id'] ?></td>
    <td><?= $o['username'] ?></td>
    <td>$<?= $o['total_price'] ?></td>
    // Locate the table row in manage_orders.php, then replace <td><?= $o['order_date'] ?></td> with:
<td><?= $o['order_date'] ?></td>
<td>
  <form method="POST" style="display:inline">
    <input type="hidden" name="id" value="<?= $o['id'] ?>">
    <button name="delete">Cancel</button>
  </form>
</td>

  </tr>
  <?php endforeach; ?>
</table>
<?php include '../../includes/footer.php'; ?>
