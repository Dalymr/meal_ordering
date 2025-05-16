<?php
// public/meals.php
require '../config/db.php';
session_start();

<<<<<<< HEAD
// Redirect admin users to admin dashboard
if (isset($_SESSION['user']) && $_SESSION['user']['is_admin']) {
    header('Location: admin/dashboard.php');
    exit;
}

// Fetch all meals
$stmt = $pdo->query("SELECT * FROM meals");
$meals = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="container py-4">
  <h1 class="mb-4">Notre Menu</h1>
  
  <div class="row">
    <?php foreach ($meals as $m): ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
          <?php if(!empty($m['image'])): ?>
              <img src="/uploads/<?= htmlspecialchars($m['image']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;" 
                   alt="<?= htmlspecialchars($m['name']) ?>" onerror="this.src='https://via.placeholder.com/300x200?text=<?= htmlspecialchars($m['name']) ?>'">
          <?php else: ?>
              <img src="https://via.placeholder.com/300x200?text=<?= htmlspecialchars($m['name']) ?>" class="card-img-top" 
                   style="height: 200px; object-fit: cover;" alt="<?= htmlspecialchars($m['name']) ?>">
          <?php endif; ?>
          
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?= htmlspecialchars($m['name']) ?></h5>
            <p class="card-text"><?= htmlspecialchars($m['description']) ?></p>
            <p class="card-text fw-bold text-primary fs-5 mt-auto">€<?= number_format($m['price'], 2) ?></p>
            
            <form action="cart.php" method="POST" class="mt-3">
              <input type="hidden" name="meal_id" value="<?= $m['id'] ?>">
              <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
              <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-cart-plus me-2"></i>Ajouter au panier
              </button>
            </form>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
=======
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
>>>>>>> main
