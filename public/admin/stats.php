<?php
// public/admin/stats.php
require '../../includes/auth_check.php';
require '../../config/db.php';

// Check that user is admin
if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
    header('Location: ../index.php');
    exit;
}

// Fetch stats
$userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$mealCount = $pdo->query("SELECT COUNT(*) FROM meals")->fetchColumn();
$orderCount = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalSales = $pdo->query("SELECT SUM(total_price) FROM orders")->fetchColumn() ?? 0;

// Monthly sales data for chart
$stmt = $pdo->query("
    SELECT 
        DATE_FORMAT(order_date, '%Y-%m') as month,
        SUM(total_price) as total,
        COUNT(*) as order_count
    FROM orders
    GROUP BY DATE_FORMAT(order_date, '%Y-%m')
    ORDER BY month ASC
    LIMIT 12
");
$monthlySales = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Top selling meals
$stmt = $pdo->query("
    SELECT m.id, m.name, m.image, SUM(oi.quantity) as total_quantity, SUM(oi.quantity * oi.price) as total_revenue
    FROM order_items oi
    JOIN meals m ON oi.meal_id = m.id
    GROUP BY oi.meal_id
    ORDER BY total_quantity DESC
    LIMIT 5
");
$topMeals = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Recent users
$stmt = $pdo->query("
    SELECT id, username, email, DATE_FORMAT(id, '%Y-%m-%d') as join_date 
    FROM users 
    ORDER BY id DESC LIMIT 5
");
$recentUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Status distribution
$stmt = $pdo->query("
    SELECT status, COUNT(*) as count 
    FROM orders 
    GROUP BY status
");
$statusDistribution = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../../includes/admin_header.php';
?>

<h1 class="mb-4">Statistiques</h1>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Utilisateurs</div>
                        <div class="h5 mb-0 font-weight-bold"><?= $userCount ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Repas</div>
                        <div class="h5 mb-0 font-weight-bold"><?= $mealCount ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-utensils fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Commandes</div>
                        <div class="h5 mb-0 font-weight-bold"><?= $orderCount ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Ventes Totales</div>
                        <div class="h5 mb-0 font-weight-bold">€<?= number_format($totalSales, 2) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Ventes Mensuelles</h6>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="chartOptions" data-bs-toggle="dropdown" aria-expanded="false">
                        Options
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="chartOptions">
                        <li><a class="dropdown-item" href="#" onclick="updateChartView('revenue')">Revenus</a></li>
                        <li><a class="dropdown-item" href="#" onclick="updateChartView('orders')">Nombre de commandes</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="monthlySalesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Statut des Commandes</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie mb-4">
                    <canvas id="orderStatusChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <?php foreach($statusDistribution as $status): ?>
                        <span class="mr-2">
                            <i class="fas fa-circle" style="color: 
                                <?= $status['status'] == 'confirmed' ? '#1cc88a' : 
                                   ($status['status'] == 'pending' ? '#f6c23e' : 
                                   ($status['status'] == 'cancelled' ? '#e74a3b' : '#36b9cc')) ?>"></i>
                            <?= ucfirst($status['status']) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Repas les plus vendus</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Repas</th>
                                <th>Quantité</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($topMeals as $meal): ?>
                            <tr>
                                <td class="d-flex align-items-center">
                                    <?php if(!empty($meal['image'])): ?>
                                        <img src="/uploads/<?= htmlspecialchars($meal['image']) ?>" 
                                             class="img-thumbnail me-2" style="width: 40px; height: 40px; object-fit: cover;" 
                                             alt="<?= htmlspecialchars($meal['name']) ?>">
                                    <?php else: ?>
                                        <div class="bg-light me-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
                                            <i class="fas fa-utensils text-secondary"></i>
                                        </div>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($meal['name']) ?>
                                </td>
                                <td><?= $meal['total_quantity'] ?></td>
                                <td>€<?= number_format($meal['total_revenue'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Utilisateurs récents</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th>Email</th>
                                <th>Date d'inscription</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recentUsers as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= $user['join_date'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Data from PHP
    const monthlySalesData = {
      labels: [<?php echo implode(', ', array_map(function($item) { 
          return '"' . date('M Y', strtotime($item['month'] . '-01')) . '"';
      }, $monthlySales)); ?>],
      revenues: [<?php echo implode(', ', array_map(function($item) { 
          return round($item['total'], 2);
      }, $monthlySales)); ?>],
      orders: [<?php echo implode(', ', array_map(function($item) { 
          return $item['order_count'];
      }, $monthlySales)); ?>]
    };
    
    // Status chart data
    const statusData = {
      labels: [<?php echo implode(', ', array_map(function($item) { 
          return '"' . ucfirst($item['status']) . '"';
      }, $statusDistribution)); ?>],
      data: [<?php echo implode(', ', array_map(function($item) { 
          return $item['count'];
      }, $statusDistribution)); ?>],
      backgroundColor: [
        '#1cc88a',  // confirmed - green
        '#f6c23e',  // pending - yellow
        '#e74a3b',  // cancelled - red
        '#36b9cc'   // other - blue
      ]
    };
    
    // Monthly sales chart
    let salesChartType = 'revenue'; // Default to revenue
    const monthlySalesCtx = document.getElementById('monthlySalesChart').getContext('2d');
    const monthlySalesChart = new Chart(monthlySalesCtx, {
      type: 'line',
      data: {
        labels: monthlySalesData.labels,
        datasets: [{
          label: 'Ventes mensuelles (€)',
          data: monthlySalesData.revenues,
          backgroundColor: 'rgba(78, 115, 223, 0.05)',
          borderColor: 'rgba(78, 115, 223, 1)',
          pointBackgroundColor: 'rgba(78, 115, 223, 1)',
          pointBorderColor: '#fff',
          pointHoverBackgroundColor: '#fff',