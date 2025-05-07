<?php
// public/meals.php
require '../config/db.php';
session_start();

// Fetch all meals
$stmt  = $pdo->query("SELECT * FROM meals");
$meals = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Liste des repas</title>
</head>
<body>
  <h2>Nos Repas</h2>
  <ul>
    <?php foreach ($meals as $m): ?>
      <li>
        <?= htmlspecialchars($m['name']) ?> — <?= number_format($m['price'], 2) ?> €<br>
        <?= htmlspecialchars($m['description']) ?>
      </li>
    <?php endforeach; ?>
  </ul>
</body>
</html>
