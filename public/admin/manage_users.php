<?php
// public/admin/manage_users.php
require '../../includes/auth_check.php';
require '../../config/db.php';

if (!$_SESSION['user']['is_admin']) {
    header('Location: ../index.php');
    exit;
}

<<<<<<< HEAD
// Generate CSRF token if not already set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle delete user
$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid CSRF token.";
    } else {
        try {
            // Don't allow deletion of the current admin
            if ($_POST['id'] == $_SESSION['user']['id']) {
                $error = "Vous ne pouvez pas supprimer votre propre compte.";
            } else {
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$_POST['id']]);
                $success = "Utilisateur supprimé avec succès.";
            }
        } catch (PDOException $e) {
            $error = "Erreur lors de la suppression: " . $e->getMessage();
        }
    }
}

// Fetch users
$search = $_GET['search'] ?? '';
if ($search) {
    $stmt = $pdo->prepare("SELECT id, username, email, is_admin FROM users WHERE username LIKE ? OR email LIKE ? ORDER BY id");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT id, username, email, is_admin FROM users ORDER BY id");
}
$users = $stmt->fetchAll();

include '../../includes/admin_header.php';
?>

<h1 class="mb-4">Gestion des Utilisateurs</h1>

<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?= $success ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i><?= $error ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Liste des Utilisateurs</h6>
        
        <form class="d-flex" method="GET">
            <input class="form-control me-2" type="search" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-outline-primary" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom d'utilisateur</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['username']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td>
                            <span class="badge bg-<?= $u['is_admin'] ? 'danger' : 'primary' ?>">
                                <?= $u['is_admin'] ? 'Admin' : 'Utilisateur' ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" style="display:inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <button type="submit" name="delete" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

=======
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
>>>>>>> main
<?php include '../../includes/footer.php'; ?>
