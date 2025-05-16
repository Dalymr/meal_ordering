<?php
require '../../includes/auth_check.php';
require '../../config/db.php';

if (!$_SESSION['user']['is_admin']) {
    header('Location: ../index.php');
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: manage_meals.php');
    exit;
}

// Fetch existing meal
$stmt = $pdo->prepare("SELECT * FROM meals WHERE id = ?");
$stmt->execute([$id]);
$meal = $stmt->fetch();

if (!$meal) {
    header('Location: manage_meals.php');
    exit;
}

// Handle update
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF validation
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid request";
    } else {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $price = floatval($_POST['price']);
        $image = $meal['image'];

        // Validate inputs
        if (!$name || $price <= 0) {
            $error = 'Le nom et le prix sont requis, et le prix doit être supérieur à 0.';
        } else {
            // Handle image upload
            if (!empty($_FILES['image']['name'])) {
                $targetDir = '../../uploads/';
                
                // Create directory if it doesn't exist
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                
                $targetFile = $targetDir . basename($_FILES['image']['name']);
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

                if (!in_array($imageFileType, $allowedTypes)) {
                    $error = 'Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.';
                } elseif (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $error = "Erreur lors du téléchargement de l'image.";
                } else {
                    $image = basename($_FILES['image']['name']);
                }
            }

            // Update meal if no errors
            if (!$error) {
                $stmt = $pdo->prepare(
                    "UPDATE meals SET name = ?, description = ?, price = ?, image = ? WHERE id = ?"
                );
                $stmt->execute([$name, $description, $price, $image, $id]);
                $success = "Repas mis à jour avec succès!";
                
                // Refresh meal data
                $stmt = $pdo->prepare("SELECT * FROM meals WHERE id = ?");
                $stmt->execute([$id]);
                $meal = $stmt->fetch();
            }
        }
    }
}

include '../../includes/admin_header.php';
?>

<h2>Modifier le Repas</h2>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="form-container">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    
    <label for="name">Nom:</label>
    <input id="name" name="name" value="<?= htmlspecialchars($meal['name']) ?>" required>

    <label for="description">Description:</label>
    <textarea id="description" name="description" rows="4"><?= htmlspecialchars($meal['description']) ?></textarea>

    <label for="price">Prix (€):</label>
    <input id="price" name="price" type="number" step="0.01" value="<?= $meal['price'] ?>" required>

    <?php if (!empty($meal['image'])): ?>
        <div style="margin: 20px 0;">
            <label>Image actuelle:</label><br>
            <img src="../../uploads/<?= htmlspecialchars($meal['image']) ?>" alt="<?= htmlspecialchars($meal['name']) ?>" style="max-width: 200px; max-height: 200px; margin-top: 10px;">
        </div>
    <?php endif; ?>

    <label for="image">Nouvelle image (optionnel):</label>
    <input id="image" name="image" type="file" accept="image/*">

    <div style="margin-top: 20px; display: flex; justify-content: space-between;">
        <a href="manage_meals.php" class="button" style="background-color: #6c757d;">Annuler</a>
        <button type="submit" class="add-button">Sauvegarder</button>
    </div>
</form>

<?php include '../../includes/footer.php'; ?>
