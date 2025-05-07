<?php
// public/admin/manage_users.php
require '../../includes/auth_check.php';
require '../../config/db.php';
if (!$_SESSION['user']['is_admin']) {
    header('Location: ../index.php');
    exit;
}
$stmt = $pdo->query("SELECT id, username, email FROM users");
$users = $stmt->fetchAll();
include '../../includes/header.php';
?>
<h2>Manage Users</h2>
<table>
  <tr><th>ID</th><th>Username</th><th>Email</th><th>Action</th></tr>
  <?php foreach($users as $u): ?>
  <tr>
    <td><?= $u['id'] ?></td>
    <td><?= $u['username'] ?></td>
    <td><?= $u['email'] ?></td>
    <td>
      <form method="POST" action="manage_users.php">
        <input type="hidden" name="id" value="<?= $u['id'] ?>">
        <button name="delete">Delete</button>
      </form>
    </td>
  </tr>
  <?php endforeach; ?>
</table>
<?php include '../../includes/footer.php'; ?>
