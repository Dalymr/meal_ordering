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
    <img src="/meal_ordering/uploads/<?= $meal['image'] ?>" alt="">
    <h3><?= $meal['name'] ?></h3>
    <p><?= $meal['description'] ?></p>
    <p>$<?= $meal['price'] ?></p>
    <form method="POST" action="cart.php">
      <input type="hidden" name="meal_id" value="<?= $meal['id'] ?>">
      <button type="submit">Add to Cart</button>
    </form>
  </div>
<?php endforeach; ?>
</div>
<?php include '../includes/footer.php'; ?>
