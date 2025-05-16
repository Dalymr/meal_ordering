<?php
require '../../includes/auth_check.php';
require '../../config/db.php';

if (!$_SESSION['user']['is_admin']) {
    header('Location: ../index.php');
    exit;
}

// Handle order cancellation or status update
$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid CSRF token.";
    } else {
        $orderId = (int)$_POST['id'];
        
        if (isset($_POST['delete']) && $orderId > 0) {
            try {
                $pdo->beginTransaction();
                $pdo->prepare("DELETE FROM order_items WHERE order_id = ?")->execute([$orderId]);
                $pdo->prepare("DELETE FROM orders WHERE id = ?")->execute([$orderId]);
                $pdo->commit();
                $success = "Commande annulée avec succès.";
            } catch (PDOException $e) {
                $pdo->rollBack();
                $error = "Erreur lors de l'annulation: " . $e->getMessage();
            }
        } elseif (isset($_POST['status']) && $orderId > 0) {
            try {
                $status = $_POST['status'];
                $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
                $stmt->execute([$status, $orderId]);
                $success = "Statut de la commande #$orderId mis à jour avec succès en \"$status\".";
            } catch (PDOException $e) {
                $error = "Erreur lors de la mise à jour: " . $e->getMessage();
            }
        }
    }
}

// Fetch single order if ID is provided
$orderDetails = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $stmt = $pdo->prepare(
        "SELECT o.*, u.username 
         FROM orders o 
         JOIN users u ON o.user_id = u.id 
         WHERE o.id = ?"
    );
    $stmt->execute([$_GET['id']]);
    $orderDetails = $stmt->fetch();
    
    if ($orderDetails) {
        $stmt = $pdo->prepare(
            "SELECT oi.*, m.name, m.image 
             FROM order_items oi 
             JOIN meals m ON oi.meal_id = m.id 
             WHERE oi.order_id = ?"
        );
        $stmt->execute([$_GET['id']]);
        $orderItems = $stmt->fetchAll();
    }
}

// Fetch all orders for list view
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';

$query = "SELECT o.id, u.username, o.total_price, o.order_date, o.status 
          FROM orders o 
          JOIN users u ON o.user_id = u.id";
$params = [];

if ($search || $status) {
    $query .= " WHERE";
    if ($search) {
        $query .= " u.username LIKE ?";
        $params[] = "%$search%";
        if ($status) {
            $query .= " AND";
        }
    }
    if ($status) {
        $query .= " o.status = ?";
        $params[] = $status;
    }
}

$query .= " ORDER BY o.order_date DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$orders = $stmt->fetchAll();

include '../../includes/admin_header.php';
?>

<h1 class="mb-4">Gestion des Commandes</h1>

<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?= $success ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i><?= $error ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($orderDetails): ?>
    <!-- Order Details View -->
    <div class="mb-4">
        <a href="manage_orders.php" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Détails de la Commande #<?= $orderDetails['id'] ?></h6>
            
            <div class="d-flex">
                <form method="POST" class="me-2">
                    <input type="hidden" name="id" value="<?= $orderDetails['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="status">Statut</label>
                        <select name="status" id="status" class="form-select">
                            <option value="pending" <?= $orderDetails['status'] == 'pending' ? 'selected' : '' ?>>En attente</option>
                            <option value="confirmed" <?= $orderDetails['status'] == 'confirmed' ? 'selected' : '' ?>>Confirmée</option>
                            <option value="delivered" <?= $orderDetails['status'] == 'delivered' ? 'selected' : '' ?>>Livrée</option>
                            <option value="cancelled" <?= $orderDetails['status'] == 'cancelled' ? 'selected' : '' ?>>Annulée</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </form>
                
                <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?');">
                    <input type="hidden" name="id" value="<?= $orderDetails['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <button type="submit" name="delete" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Annuler la commande
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Client:</strong> <?= htmlspecialchars($orderDetails['username']) ?></p>
                    <p><strong>Date:</strong> <?= date('d/m/Y H:i', strtotime($orderDetails['order_date'])) ?></p>
                </div>
                <div class="col-md-6">
                    <p>
                        <strong>Statut:</strong> 
                        <span class="badge bg-<?= 
                            $orderDetails['status'] == 'confirmed' ? 'success' : 
                            ($orderDetails['status'] == 'delivered' ? 'info' : 
                            ($orderDetails['status'] == 'cancelled' ? 'danger' : 'warning')) 
                        ?>">
                            <?= ucfirst(htmlspecialchars($orderDetails['status'])) ?>
                        </span>
                    </p>
                    <p><strong>Total:</strong> <span class="text-primary fw-bold">€<?= number_format($orderDetails['total_price'], 2) ?></span></p>
                </div>
            </div>
            
            <h5 class="mb-3">Articles commandés</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
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
                        <?php foreach($orderItems as $item): ?>
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
                            <td>€<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Total:</td>
                            <td class="fw-bold">€<?= number_format($orderDetails['total_price'], 2) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
<?php else: ?>
    <!-- Orders List View -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Commandes</h6>
            
            <div class="d-flex">
                <form class="d-flex" method="GET">
                    <select name="status" class="form-select me-2">
                        <option value="">Tous les statuts</option>
                        <option value="pending" <?= $status == 'pending' ? 'selected' : '' ?>>En attente</option>
                        <option value="confirmed" <?= $status == 'confirmed' ? 'selected' : '' ?>>Confirmée</option>
                        <option value="delivered" <?= $status == 'delivered' ? 'selected' : '' ?>>Livrée</option>
                        <option value="cancelled" <?= $status == 'cancelled' ? 'selected' : '' ?>>Annulée</option>
                    </select>
                    <input class="form-control me-2" type="search" name="search" placeholder="Rechercher client..." value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <?php if ($orders): ?>
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= $order['id'] ?></td>
                            <td><?= htmlspecialchars($order['username']) ?></td>
                            <td>€<?= number_format($order['total_price'], 2) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($order['order_date'])) ?></td>
                            <td>
                                <span class="badge bg-<?= 
                                    $order['status'] == 'confirmed' ? 'success' : 
                                    ($order['status'] == 'delivered' ? 'info' : 
                                    ($order['status'] == 'cancelled' ? 'danger' : 'warning')) 
                                ?>">
                                    <?= ucfirst(htmlspecialchars($order['status'])) ?>
                                </span>
                            </td>
                            <td>
                                <a href="?id=<?= $order['id'] ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Détails
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Aucune commande trouvée.
            </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php include '../../includes/footer.php'; ?>
