<?php
session_start();
require '../config/db.php';

// Redirect admin users to admin dashboard
if (isset($_SESSION['user']) && $_SESSION['user']['is_admin']) {
    header('Location: admin/dashboard.php');
    exit;
}

// CSRF protection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid request");
    }
    
    if (isset($_POST['remove']) && isset($_POST['meal_id'])) {
        // Remove item from cart
        $meal_id = (int)$_POST['meal_id'];
        if (isset($_SESSION['cart'][$meal_id])) {
            unset($_SESSION['cart'][$meal_id]);
        }
    } elseif (isset($_POST['meal_id'])) {
        // Add item to cart
        $meal_id = (int)$_POST['meal_id'];
        if ($meal_id > 0) {
            $_SESSION['cart'][$meal_id] = ($_SESSION['cart'][$meal_id] ?? 0) + 1;
        }
    }
    
    // Redirect to avoid form resubmission
    header('Location: cart.php');
    exit;
}

// Calculate total
$total = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => $qty) {
        $stmt = $pdo->prepare("SELECT price FROM meals WHERE id = ?");
        $stmt->execute([$id]);
        $price = $stmt->fetchColumn();
        if ($price) {
            $total += $price * $qty;
        }
    }
}

include '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Votre Panier</h1>
    <a href="meals.php" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left me-2"></i>Continuer les achats
    </a>
</div>

<?php if (!empty($_SESSION['cart'])): ?>
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Vos articles</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($_SESSION['cart'] as $id => $qty):
                        $stmt = $pdo->prepare("SELECT * FROM meals WHERE id = ?");
                        $stmt->execute([$id]);
                        $meal = $stmt->fetch();
                        if ($meal): ?>
                            <div class="card mb-3">
                                <div class="row g-0">
                                    <div class="col-md-2">
                                        <?php if(!empty($meal['image'])): ?>
                                            <img src="/uploads/<?= htmlspecialchars($meal['image']) ?>" class="img-fluid rounded-start h-100" 
                                                 alt="<?= htmlspecialchars($meal['name']) ?>" style="object-fit: cover;" 
                                                 onerror="this.src='https://via.placeholder.com/100?text=Repas'">
                                        <?php else: ?>
                                            <img src="https://via.placeholder.com/100?text=Repas" class="img-fluid rounded-start h-100" 
                                                 alt="<?= htmlspecialchars($meal['name']) ?>">
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="card-body d-flex justify-content-between">
                                            <div>
                                                <h5 class="card-title"><?= htmlspecialchars($meal['name']) ?></h5>
                                                <p class="card-text">Quantité: <?= $qty ?></p>
                                                <p class="card-text">Prix unitaire: €<?= number_format($meal['price'], 2) ?></p>
                                                <p class="card-text"><strong>Total: €<?= number_format($meal['price'] * $qty, 2) ?></strong></p>
                                            </div>
                                            <div>
                                                <form method="POST">
                                                    <input type="hidden" name="meal_id" value="<?= $id ?>">
                                                    <input type="hidden" name="remove" value="1">
                                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Résumé de la commande</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Total:</span>
                        <span class="fw-bold">€<?= number_format($total, 2) ?></span>
                    </div>
                    <hr>
                    <form method="POST" action="order_history.php">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check me-2"></i>Passer la commande
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="text-center p-5 mt-4">
        <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
        <h3>Votre panier est vide</h3>
        <p class="text-muted mb-4">Parcourez notre menu et ajoutez des articles à votre panier</p>
        <a href="meals.php" class="btn btn-primary">Découvrir le menu</a>
    </div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
