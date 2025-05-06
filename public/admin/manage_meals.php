<?php
// public/admin/manage_meals.php
require '../../includes/auth_check.php';
require '../../config/db.php';

// Handle add/edit/delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Upload image if provided
    if (!empty($_FILES['image']['name'])) {
        $target = '../../uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $image = $_FILES['image']['name'];
    } else {
        $image = $_POST['existing_image'] ?? '';
    }

    if (isset($_POST['add'])) {
        $stmt = $pdo->prepare("INSERT INTO meals (name, description, price, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_POST['name'], $_POST['description'], $_POST['price'], $image]);
    }
    if (isset($_POST['delete'])) {
        $stmt = $pdo->prepare("DELETE FROM meals WHERE id = ?");
        $stmt->execute([$_POST['id']]);
    }
    header('Location: manage_meals.php');
    exit;
}

// Fetch meals
$stmt = $pdo->query("SELECT * FROM meals");
$meals = $stmt->fetchAll();
include '../../includes/header.php';
?>
<h2>Manage Meals</h2>
<table>
    <tr><th>ID</th><th>Name</th><th>Price</th><th>Image</th><th>Actions</th></tr>
    <?php foreach($meals as $m): ?>
    <tr>
        <td><?= $m['id'] ?></td>
        <td><?= $m['name'] ?></td>
        <td>$<?= $m['price'] ?></td>
        <td><img src="/meal_ordering/uploads/<?= $m['image'] ?>" width="50"></td>
        <td>
            <form style="display:inline" method="POST">
                <input type="hidden" name="id" value="<?= $m['id'] ?>">
                <button name="delete">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<h3>Add New Meal</h3>
<form method="POST" enctype="multipart/form-data">
    Name:        <input name="name" required><br>
    Description: <textarea name="description"></textarea><br>
    Price:       <input name="price" type="number" step="0.01" required><br>
    Image:       <input name="image" type="file"><br>
    <button name="add">Add Meal</button>
</form>
<?php include '../../includes/footer.php'; ?>
