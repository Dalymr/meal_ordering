<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réserver un repas</title>
</head>
<body>
    <h1>Réserver un repas</h1>
    <form action="process_reservation.php" method="POST">
        <label for="meal_id">Choisir un repas:</label>
        <select name="meal_id" id="meal_id" required>
            <!-- Options will be populated dynamically later -->
            <option value="1">Spaghetti</option>
            <option value="2">Couscous</option>
            <option value="3">Pizza</option>
        </select><br><br>

        <label for="reservation_date">Date:</label>
        <input type="date" name="reservation_date" required><br><br>

        <label for="reservation_time">Heure:</label>
        <input type="time" name="reservation_time" required><br><br>

        <button type="submit">Réserver</button>
    </form>
</body>
</html>
