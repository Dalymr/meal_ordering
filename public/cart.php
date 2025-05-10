<?php
// public/cart.php
session_start();
require '../config/db.php';

// Add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $meal_id = (int)$_POST['meal_id'];
    $_SESSION['cart'][$meal_id] = ($_SESSION['cart'][$meal_id] ?? 0) + 1;
    header('Location: cart.php');
    exit;
}

include '../includes/header.php';
?>
<h2>Your Cart</h2>
<?php if (!empty($_SESSION['cart'])): ?>
    <ul>
    <?php foreach($_SESSION['cart'] as $id => $qty):
        $stmt = $pdo->prepare("SELECT * FROM meals WHERE id = ?");
        $stmt->execute([$id]);
        $meal = $stmt->fetch();
    ?>
        <li>
            <?= $meal['name'] ?> × <?= $qty ?> — $<?= number_format($meal['price'] * $qty, 2) ?>
        </li>
    <?php endforeach; ?>
    </ul>
    <form method="POST" action="order_history.php">
        <button type="submit">Place Order</button>
    </form>
<?php else: ?>
    <p>Your cart is empty.</p>
<?php endif; ?>
<?php include '../includes/footer.php'; ?>
