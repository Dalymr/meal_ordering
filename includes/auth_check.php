<?php
// includes/auth_check.php
if (session_status() === PHP_SESSION_NONE) session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: ../public/login.php');
    exit;
}

// If this is a client page and user is admin, redirect to admin dashboard
$currentScript = $_SERVER['SCRIPT_NAME'];
if (strpos($currentScript, '/public/admin/') === false && $_SESSION['user']['is_admin']) {
    // Not in admin directory but user is admin - redirect to dashboard
    header('Location: ../public/admin/dashboard.php');
    exit;
}
?>
