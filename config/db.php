<?php
// Database configuration
$host = 'localhost';
$dbname = 'meal_ordering';
$username = 'root';
$password = 'root'; // Default password is empty for WAMP

try {
    // Create a PDO instance with optimized settings
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    // Log error instead of exposing details in production
    error_log("Database connection failed: " . $e->getMessage());
    // Show generic error message
    die("Une erreur est survenue. Veuillez réessayer plus tard.");
}
?>
