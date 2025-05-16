<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is admin - redirect to login if not
if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
    header('Location: /public/login.php');
    exit;
}

// Generate CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - FoodFrenzy</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/public/admin/dashboard.php">
            <i class="fas fa-lock me-2"></i>Administration
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarAdmin">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/public/admin/dashboard.php">Tableau de bord</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/public/admin/manage_users.php">Utilisateurs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/public/admin/manage_meals.php">Repas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/public/admin/manage_orders.php">Commandes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/public/admin/stats.php">Statistiques</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/public/admin/about.php">À propos</a>
                </li>
            </ul>
            <div class="d-flex align-items-center">
                <img src="/img/palestine-flag.png" alt="Palestine Flag" class="palestine-flag"
                     onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/0/00/Flag_of_Palestine.svg'; this.onerror=null;">
                <span class="viva-palestine">Viva Palestina</span>
                <a class="btn btn-outline-danger ms-3" href="/public/logout.php">
                    <i class="fas fa-sign-out-alt me-1"></i>Déconnexion
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="content-wrapper">
  <div class="container py-4">
<!-- Add JS include before closing body tag -->
<script src="/js/scripts.js"></script>
</body>
</html>
