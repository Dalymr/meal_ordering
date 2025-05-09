<?php
// public/admin/edit_meal.php
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

// Fetch existing
$stmt = $pdo->prepare("SELECT * FROM meals WHERE id = ?");
$stmt->execute([$id]);
$meal = $stmt->fetch();

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $image = $meal['image'];
    if (!empty($_FILES['image']['name'])) {
        $target = '../../uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $image = $_FILES['image']['name'];
    }
    $stmt = $pdo->prepare(
      "UPDATE meals SET name=?, description=?, price=?, image=? WHERE id=?"
    );
    $stmt->execute([
      $_POST['name'], $_POST['description'], $_POST['price'], $image, $id
    ]);
    header('Location: manage_meals.php');
    exit;
}

include '../../includes/header.php';
?>
<h2>Modifier Repas</h2>
<form method="POST" enctype="multipart/form-data">
    Nom:        <input name="name" value="<?= htmlspecialchars($meal['name']) ?>" required><br>
    Description:<textarea name="description"><?= htmlspecialchars($meal['description']) ?></textarea><br>
    Prix:       <input name="price" type="number" step="0.01" value="<?= $meal['price'] ?>" required><br>
    Image:      <input name="image" type="file"><br>
    <button type="submit">Enregistrer</button>
</form>
<?php include '../../includes/footer.php'; ?>
