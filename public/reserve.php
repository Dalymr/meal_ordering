<?php
session_start();
<<<<<<< HEAD
require '../config/db.php';

=======
>>>>>>> main
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
<<<<<<< HEAD

// Fetch available meals
$stmt = $pdo->query("SELECT id, name FROM meals");
$meals = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
          <h3 class="card-title mb-0">Réserver un repas</h3>
        </div>
        <div class="card-body">
          <form action="process_reservation.php" method="POST" class="needs-validation" novalidate>
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            
            <div class="mb-3">
              <label for="meal_id" class="form-label">Choisir un repas</label>
              <select class="form-select" id="meal_id" name="meal_id" required>
                <option value="" selected disabled>Sélectionnez un repas</option>
                <?php foreach ($meals as $meal): ?>
                  <option value="<?= $meal['id'] ?>"><?= htmlspecialchars($meal['name']) ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback">
                Veuillez sélectionner un repas.
              </div>
            </div>
            
            <div class="mb-3">
              <label for="reservation_date" class="form-label">Date</label>
              <input type="date" class="form-control" id="reservation_date" name="reservation_date" 
                     min="<?= date('Y-m-d') ?>" required>
              <div class="invalid-feedback">
                Veuillez sélectionner une date.
              </div>
            </div>
            
            <div class="mb-3">
              <label for="reservation_time" class="form-label">Heure</label>
              <input type="time" class="form-control" id="reservation_time" name="reservation_time" required>
              <div class="invalid-feedback">
                Veuillez sélectionner une heure.
              </div>
            </div>
            
            <div class="mb-3">
              <label for="quantity" class="form-label">Nombre de personnes</label>
              <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="1" required>
              <div class="invalid-feedback">
                Veuillez indiquer le nombre de personnes.
              </div>
            </div>
            
            <div class="mb-3">
              <label for="notes" class="form-label">Notes spéciales (facultatif)</label>
              <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
            </div>
            
            <div class="d-grid">
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-calendar-check me-2"></i>Confirmer la réservation
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
=======
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
>>>>>>> main
