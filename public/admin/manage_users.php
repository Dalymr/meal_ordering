<?php
// public/admin/manage_users.php
require '../../includes/auth_check.php';
require '../../config/db.php';
if (!$_SESSION['user']['is_admin']) {
    header('Location: ../index.php');
    exit;
}

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    header('Location: manage_users.php');
    exit;
}

$users = $pdo->query("SELECT id, username, email FROM users")->fetchAll();
include '../../includes/header.php';
?>
<h2>Gérer les utilisateurs</h2>
<table>
  <tr><th>ID</th><th>Utilisateur</th><th>Email</th><th>Action</th></tr>
  <?php foreach ($users as $u): ?>
  <tr>
    <td><?= $u['id'] ?></td>
    <td><?= htmlspecialchars($u['username']) ?></td>
    <td><?= htmlspecialchars($u['email']) ?></td>
    <td>
      <form method="POST">
        <input type="hidden" name="id" value="<?= $u['id'] ?>">
        <button name="delete">Supprimer</button>
      </form>
    </td>
  </tr>
  <?php endforeach; ?>
</table>
<?php include '../../includes/footer.php'; ?>
