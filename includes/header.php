<?php
// includes/header.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>FoodFrenzy</title>
  <link rel="stylesheet" href="/meal_ordering/css/style.css">
</head>
<body>
<nav>
  <a href="/meal_ordering/public/index.php">Home</a>
  <?php if(isset($_SESSION['user'])): ?>
    <a href="/meal_ordering/public/cart.php">Cart</a>
    <a href="/meal_ordering/public/order_history.php">Orders</a>
    <a href="/meal_ordering/public/logout.php">Logout</a>
  <?php else: ?>
    <a href="/meal_ordering/public/login.php">Login</a>
    <a href="/meal_ordering/public/register.php">Register</a>
  <?php endif; ?>
  <?php if(isset($_SESSION['user']) && $_SESSION['user']['is_admin']): ?>
    <a href="/meal_ordering/public/admin/dashboard.php">Admin</a>
  <?php endif; ?>
</nav>
<main>
