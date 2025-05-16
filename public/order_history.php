<?php
session_start();
include '../includes/auth_check.php';
include '../config/db.php';

function getStatusBadgeClass($status) {
    switch ($status) {
        case 'confirmed': return 'success';
        case 'delivered': return 'info';
        case 'cancelled': return 'danger';
        case 'pending':
        default: return 'warning';
    }
}

function getStatusLabel($status) {
    switch ($status) {
        case 'confirmed': return 'Confirmée';
        case 'delivered': return 'Livrée';
        case 'cancelled': return 'Annulée';
        case 'pending':
        default: return 'En attente';
    }
}

// Place order if cart is not empty
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['cart']) && isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    try {
        $total = 0;
        foreach ($_SESSION['cart'] as $id => $qty) {
            $stmt = $pdo->prepare("SELECT price FROM meals WHERE id = ?");
            $stmt->execute([$id]);
            $price = $stmt->fetchColumn();
            if ($price === false) {
                throw new Exception("Meal ID $id not found.");
            }
            $total += $price * $qty;
        }

        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price, order_date) VALUES (?, ?, NOW())");
        $stmt->execute([$_SESSION['user']['id'], $total]);
        $order_id = $pdo->lastInsertId();

        foreach ($_SESSION['cart'] as $id => $qty) {
            $stmt = $pdo->prepare("SELECT price FROM meals WHERE id = ?");
            $stmt->execute([$id]);
            $price = $stmt->fetchColumn();
            
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, meal_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$order_id, $id, $qty, $price]);
        }

        unset($_SESSION['cart']);
        $_SESSION['order_success'] = true;
        
        header('Location: order_history.php');
        exit;
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Fetch order history
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$stmt->execute([$_SESSION['user']['id']]);
$orders = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="container py-4">
  <h1 class="mb-4">Historique des commandes</h1>
  
  <?php if (isset($_SESSION['order_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle me-2"></i>
      Votre commande a été passée avec succès !
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['order_success']); ?>
  <?php endif; ?>
  
  <?php if (isset($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="fas fa-exclamation-circle me-2"></i>
      <?= $error ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>
  
  <?php if ($orders): ?>
    <div class="row">
      <?php foreach ($orders as $order): ?>
        <div class="col-md-6 mb-4">
          <div class="card shadow-sm h-100">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
              <h5 class="mb-0">Commande #<?= $order['id'] ?></h5>
              <span class="badge bg-<?= getStatusBadgeClass($order['status']) ?>">
                <?= getStatusLabel($order['status']) ?>
              </span>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <p class="card-text">
                  <strong><i class="fas fa-calendar me-2"></i>Date:</strong> 
                  <?= date('d/m/Y H:i', strtotime($order['order_date'])) ?>
                </p>
                <p class="card-text">
                  <strong><i class="fas fa-money-bill-wave me-2"></i>Total:</strong> 
                  <span class="text-primary fw-bold">€<?= number_format($order['total_price'], 2) ?></span>
                </p>
              </div>
              
              <div class="d-grid">
                <a href="order_detail.php?id=<?= $order['id'] ?>" class="btn btn-outline-primary">
                  <i class="fas fa-eye me-2"></i>Voir les détails
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-info">
      <i class="fas fa-info-circle me-2"></i>
      Vous n'avez pas encore passé de commande.
    </div>
    <a href="meals.php" class="btn btn-primary">
      <i class="fas fa-utensils me-2"></i>Parcourir le menu
    </a>
  <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
