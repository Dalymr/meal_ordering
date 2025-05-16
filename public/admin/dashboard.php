<?php
require '../../includes/auth_check.php';
require '../../config/db.php';

if (!$_SESSION['user']['is_admin']) {
    header('Location: ../index.php');
    exit;
}

// Fetch quick statistics
$userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$mealCount = $pdo->query("SELECT COUNT(*) FROM meals")->fetchColumn();
$orderCount = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalSales = $pdo->query("SELECT SUM(total_price) FROM orders")->fetchColumn() ?? 0;

// Recent orders
$recentOrders = $pdo->query("
    SELECT o.id, u.username, o.total_price, o.order_date, o.status 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    ORDER BY o.order_date DESC LIMIT 5
")->fetchAll();

include '../../includes/admin_header.php';
?>

<h1 class="mb-4">Tableau de bord</h1>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Utilisateurs</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $userCount ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="manage_users.php" class="btn btn-sm btn-primary">Gérer</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Repas</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $mealCount ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-utensils fa-2x text-gray-300"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="manage_meals.php" class="btn btn-sm btn-success">Gérer</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Commandes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $orderCount ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="manage_orders.php" class="btn btn-sm btn-info">Gérer</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Ventes Totales</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">€<?= number_format($totalSales, 2) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="stats.php" class="btn btn-sm btn-warning text-dark">Statistiques</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Commandes Récentes</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Montant</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                    <tr>
                        <td><?= $order['id'] ?></td>
                        <td><?= htmlspecialchars($order['username']) ?></td>
                        <td>€<?= number_format($order['total_price'], 2) ?></td>
                        <td><?= $order['order_date'] ?></td>
                        <td>
                            <span class="badge bg-<?= $order['status'] === 'confirmed' ? 'success' : 'warning' ?>">
                                <?= htmlspecialchars($order['status']) ?>
                            </span>
                        </td>
                        <td>
                            <a href="manage_orders.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Quick Actions Section (modified to remove meal images update) -->
<div class="card shadow mb-4">
    <div class="card-header py-3 bg-dark text-white">
        <h6 class="m-0 font-weight-bold">Actions Rapides</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-utensils fa-3x text-primary mb-3"></i>
                        <h5>Ajouter un Repas</h5>
                        <p class="card-text">Créez et ajoutez un nouveau plat à votre menu.</p>
                        <a href="manage_meals.php" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Ajouter
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-clipboard-list fa-3x text-success mb-3"></i>
                        <h5>Gérer les Commandes</h5>
                        <p class="card-text">Consultez et mettez à jour les commandes récentes.</p>
                        <a href="manage_orders.php" class="btn btn-success">
                            <i class="fas fa-tasks me-2"></i>Gérer
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-bar fa-3x text-info mb-3"></i>
                        <h5>Statistiques</h5>
                        <p class="card-text">Consultez les rapports et analyses de performance.</p>
                        <a href="stats.php" class="btn btn-info">
                            <i class="fas fa-chart-line me-2"></i>Analyser
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
