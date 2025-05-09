<?php
// public/index.php
require '../config/db.php';
session_start();
include '../includes/header.php';

$stmt  = $pdo->query("SELECT * FROM meals");
$meals = $stmt->fetchAll();
?>
<h1>Menu</h1>
<div class="meals">
<?php foreach($meals as $meal): ?>
  <div class="meal">
    <img src="/meal_ordering/uploads/<?= $meal['image'] ?>" alt="" style="max-width:100px;"><br>
    <strong><?= htmlspecialchars($meal['name']) ?></strong><br>
    <?= htmlspecialchars($meal['description']) ?><br>
    Price: $<?= number_format($meal['price'],2) ?><br>
    <form method="POST" action="cart.php">
      <input type="hidden" name="meal_id" value="<?= $meal['id'] ?>">
      Qty: <input type="number" name="quantity" value="1" min="1" style="width:50px;"><br>
      <button type="submit">Add to Cart</button>
    </form>
  </div>
<?php endforeach; ?>
</div>
<?php include '../includes/footer.php'; ?>
