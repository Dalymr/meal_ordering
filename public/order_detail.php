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
  "SELECT o.id, o.order_date, o.total_price, o.status, u.username
   FROM orders o
   JOIN users u ON o.user_id=u.id
   WHERE o.id=? AND o.user_id=?"
);
$stmt->execute([$orderId, $_SESSION['user']['id']]);
$order = $stmt->fetch();

if (!$order) {
    include '../includes/header.php';
    echo '<div class="container py-4"><div class="alert alert-danger">Commande non trouvée.</div></div>';
    include '../includes/footer.php';
    exit;
}

// Fetch items
$stmt = $pdo->prepare(
  "SELECT m.name, m.image, oi.quantity, oi.price
   FROM order_items oi
   JOIN meals m ON oi.meal_id=m.id
   WHERE oi.order_id=?"
);
$stmt->execute([$orderId]);
$items = $stmt->fetchAll();

include '../includes/header.php';

// Add the helper functions if not already included
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
?>

<div class="container py-4">
  <div class="mb-4">
    <a href="order_history.php" class="btn btn-outline-primary">
      <i class="fas fa-arrow-left me-2"></i>Retour aux commandes
    </a>
  </div>
  
  <div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h3 class="mb-0">Détails de la Commande #<?= $order['id'] ?></h3>
      <span class="badge bg-<?= getStatusBadgeClass($order['status']) ?> fs-6">
        <?= getStatusLabel($order['status']) ?>
      </span>
    </div>
    <div class="card-body">
      <!-- Add a status timeline to make it more visual -->
      <div class="mb-4">
        <h5>Statut de la commande</h5>
        <div class="d-flex justify-content-between position-relative mt-4">
          <div class="d-flex flex-column align-items-center">
            <div class="rounded-circle bg-<?= in_array($order['status'], ['pending', 'confirmed', 'delivered']) ? 'success' : 'secondary' ?> text-white d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
              <i class="fas fa-check"></i>
            </div>
            <span class="mt-2">Reçue</span>
          </div>
          <div class="d-flex flex-column align-items-center">
            <div class="rounded-circle bg-<?= in_array($order['status'], ['confirmed', 'delivered']) ? 'success' : 'secondary' ?> text-white d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
              <i class="fas fa-thumbs-up"></i>
            </div>
            <span class="mt-2">Confirmée</span>
          </div>
          <div class="d-flex flex-column align-items-center">
            <div class="rounded-circle bg-<?= $order['status'] == 'delivered' ? 'success' : 'secondary' ?> text-white d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
              <i class="fas fa-truck"></i>
            </div>
            <span class="mt-2">Livrée</span>
          </div>
          <!-- Progress line connecting circles -->
          <div class="position-absolute top-50 start-0 end-0 border-top border-2" style="z-index: -1; margin-top: -10px;"></div>
        </div>
      </div>
      
      <div class="row mb-4">
        <div class="col-md-6">
          <p><strong>Client:</strong> <?= htmlspecialchars($order['username']) ?></p>
          <p><strong>Date:</strong> <?= date('d/m/Y H:i', strtotime($order['order_date'])) ?></p>
        </div>
        <div class="col-md-6">
          <p>
            <strong>Statut:</strong> 
            <span class="badge bg-<?= $order['status'] === 'confirmed' ? 'success' : 'warning' ?>">
              <?= ucfirst(htmlspecialchars($order['status'])) ?>
            </span>
          </p>
          <p><strong>Total:</strong> <span class="text-primary fw-bold">€<?= number_format($order['total_price'], 2) ?></span></p>
        </div>
      </div>
      
      <h4 class="mb-3">Articles commandés</h4>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Image</th>
              <th>Article</th>
              <th>Prix unitaire</th>
              <th>Quantité</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($items as $item): ?>
              <tr>
                <td>
                  <?php if(!empty($item['image'])): ?>
                    <img src="/uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" 
                         class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                  <?php else: ?>
                    <img src="https://via.placeholder.com/50?text=Food" alt="<?= htmlspecialchars($item['name']) ?>" 
                         class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td>€<?= number_format($item['price'], 2) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td class="fw-bold">€<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="4" class="text-end fw-bold">Total:</td>
              <td class="fw-bold">€<?= number_format($order['total_price'], 2) ?></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
