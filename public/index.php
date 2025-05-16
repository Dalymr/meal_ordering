<?php
// public/index.php
require '../config/db.php';
session_start();

// Redirect admin users to admin dashboard
if (isset($_SESSION['user']) && $_SESSION['user']['is_admin']) {
    header('Location: admin/dashboard.php');
    exit;
}

// Fetch featured meals
$stmt = $pdo->query("SELECT * FROM meals LIMIT 6");
$meals = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="jumbotron bg-light p-5 rounded-3 mb-4">
    <div class="container">
        <h1 class="display-4">Bienvenue sur FoodFrenzy</h1>
        <p class="lead">Découvrez notre sélection de délicieux repas, préparés avec des ingrédients frais et livrés directement chez vous.</p>
        <hr class="my-4">
        <p>Prêt à déguster ? Parcourez notre menu et commandez en quelques clics.</p>
        <a class="btn btn-primary btn-lg" href="meals.php" role="button">Voir le menu</a>
    </div>
</div>

<h2 class="mb-4">Nos Plats Populaires</h2>

<div class="row">
    <?php foreach($meals as $meal): ?>
    <div class="col-md-4 mb-4">
        <div class="card meal-card h-100">
            <?php if(!empty($meal['image'])): ?>
                <img src="/uploads/<?= htmlspecialchars($meal['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($meal['name']) ?>" onerror="this.src='https://via.placeholder.com/300x200?text=Repas'">
            <?php else: ?>
                <img src="https://via.placeholder.com/300x200?text=<?= htmlspecialchars($meal['name']) ?>" class="card-img-top" alt="<?= htmlspecialchars($meal['name']) ?>">
            <?php endif; ?>
            
            <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?= htmlspecialchars($meal['name']) ?></h5>
                <p class="card-text"><?= htmlspecialchars($meal['description']) ?></p>
                <p class="price-tag mb-3">€<?= number_format($meal['price'], 2) ?></p>
                
                <form method="POST" action="cart.php" class="mt-auto">
                    <input type="hidden" name="meal_id" value="<?= $meal['id'] ?>">
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

<div class="text-center mt-4 mb-5">
    <a href="meals.php" class="btn btn-outline-primary btn-lg">Voir tout le menu</a>
</div>

<?php include '../includes/footer.php'; ?>
