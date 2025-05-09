<?php
// public/order_detail.php
require '../includes/auth_check.php';
require '../config/db.php';

$orderId = $_GET['id'] ?? null;
if (!$orderId) {
    header('Location: order_history.php');
    exit;
}

// Fetch order
$stmt = $pdo->prepare(
  "SELECT o.id, o.order_date, o.total_price, u.username
   FROM orders o
   JOIN users u ON o.user_id=u.id
   WHERE o.id=?"
);
$stmt->execute([$orderId]);
$order = $stmt->fetch();

// Fetch items
$stmt = $pdo->prepare(
  "SELECT m.name, oi.quantity, m.price
   FROM order_items oi
   JOIN meals m ON oi.meal_id=m.id
   WHERE oi.order_id=?"
);
$stmt->execute([$orderId]);
$items = $stmt->fetchAll();

include '../includes/header.php';
?>
<h2>Détails Commande #<?= $order['id'] ?></h2>
<p>Date: <?= $order['order_date'] ?></p>
<p>Client: <?= htmlspecialchars($order['username']) ?></p>
<ul>
<?php foreach($items as $it): ?>
  <li><?= htmlspecialchars($it['name']) ?> × <?= $it['quantity'] ?>
      — $<?= number_format($it['price'] * $it['quantity'],2) ?></li>
<?php endforeach; ?>
</ul>
<p><strong>Total: $<?= number_format($order['total_price'],2) ?></strong></p>
<?php include '../includes/footer.php'; ?>
