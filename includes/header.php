<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
if (!isset($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="<?= isset($_SESSION['lang']) ? htmlspecialchars($_SESSION['lang']) : 'fr' ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FoodFrenzy - Commandez vos plats préférés</title>
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
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="/public/index.php">
      <i class="fas fa-utensils me-2"></i>FoodFrenzy
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="/public/index.php">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/public/meals.php">Menu</a>
        </li>
        <?php if (isset($_SESSION['user'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="/public/cart.php">
              <i class="fas fa-shopping-cart"></i> Panier
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/public/order_history.php">Commandes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/public/profile.php">Profil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/public/logout.php">Déconnexion</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="/public/login.php">Connexion</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/public/register.php">Inscription</a>
          </li>
        <?php endif; ?>
        <li class="nav-item">
          <a class="nav-link" href="/public/contact.php">Contact</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/public/about.php">À propos</a>
        </li>
      </ul>
      <div class="d-flex align-items-center">
        <img src="/img/palestine-flag.png" alt="Palestine Flag" class="palestine-flag" 
             onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/0/00/Flag_of_Palestine.svg'; this.onerror=null;">
        <span class="viva-palestine">Viva Palestina</span>
      </div>
    </div>
  </div>
</nav>
<div class="content-wrapper">
  <div class="container py-4">
<main>
<!-- Add JS include before closing body tag -->
<script src="/js/scripts.js"></script>
</body>
</html>
