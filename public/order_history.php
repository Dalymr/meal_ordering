<?php
// public/order_history.php
require '../includes/auth_check.php';
session_start();
require '../config/db.php';

// Place order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['cart'])) {
    $total = 0;
    foreach ($_SESSION['cart'] as $id => $qty) {
        $stmt = $pdo->prepare("SELECT price FROM meals WHERE id = ?");
        $stmt->execute([$id]);
        $total += $stmt->fetchColumn() * $qty;
    }
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user']['id'], $total]);
    $order_id = $pdo->lastInsertId();
    foreach ($_SESSION['cart'] as $id => $qty) {
        $stmt = $pdo->prepare(
            "INSERT INTO order_items (order_id, meal_id, quantity) VALUES (?, ?, ?)"
        );
        $stmt->execute([$order_id, $id, $qty]);
    }
    unset($_SESSION['cart']);
    header('Location: order_history.php');
    exit;
}

// Display history
$stmt  = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$stmt->execute([$_SESSION['user']['id']]);
$orders = $stmt->fetchAll();

include '../includes/header.php';
?>
<h2>Order History</h2>
<?php foreach ($orders as $order): ?>
    <div>
        <strong>Order #<?= $order['id'] ?></strong>
        <em><?= $order['order_date'] ?></em>
        — $<?= $order['total_price'] ?>
    </div>
<?php endforeach; ?>
<?php include '../includes/footer.php'; ?>
