<?php
// public/profile.php
require '../includes/auth_check.php';
require '../config/db.php';

// Additional security check - redundant but added for extra protection
if (isset($_SESSION['user']) && $_SESSION['user']['is_admin']) {
    header('Location: admin/dashboard.php');
    exit;
}

// Initialize variables
$success = false;
$errors = [];

// Check for success message
if (isset($_SESSION['profile_updated'])) {
    $success = $_SESSION['profile_updated'];
    unset($_SESSION['profile_updated']);
}

// Check for errors
if (isset($_SESSION['profile_errors'])) {
    $errors = $_SESSION['profile_errors'];
    unset($_SESSION['profile_errors']);
}

// Get user's current profile image with fallback to default
$profileImage = !empty($_SESSION['user']['profile_image']) ? 
    htmlspecialchars($_SESSION['user']['profile_image']) : 'default.jpg';

// Fetch latest user data from database to ensure it's up to date
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user']['id']]);
$userData = $stmt->fetch();

// Update session with latest data if found
if ($userData) {
    $_SESSION['user'] = $userData;
}

include '../includes/header.php';
?>

<div class="container py-4">
  <div class="row">
    <div class="col-lg-4">
      <div class="card shadow-sm mb-4">
        <div class="card-body text-center">
          <form id="profile-image-form" action="update_profile_image.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="profile-img-container mb-3">
              <img src="/uploads/profiles/<?= htmlspecialchars($profileImage) ?>" 
                   class="profile-img" alt="<?= htmlspecialchars($_SESSION['user']['username']) ?>"
                   onerror="this.src='https://via.placeholder.com/150?text=<?= substr(htmlspecialchars($_SESSION['user']['username']), 0, 1) ?>'">
              <label for="profile_image" class="profile-img-edit">
                <i class="fas fa-camera"></i>
                <input type="file" id="profile_image" name="profile_image" class="d-none" accept="image/*">
              </label>
            </div>
          </form>
          
          <h4><?= htmlspecialchars($_SESSION['user']['username']) ?></h4>
          <p class="text-muted"><?= htmlspecialchars($_SESSION['user']['email']) ?></p>
          <div class="d-grid gap-2">
            <a href="order_history.php" class="btn btn-outline-primary">
              <i class="fas fa-clock me-2"></i>Historique des commandes
            </a>
            <a href="logout.php" class="btn btn-outline-danger">
              <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
            </a>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-lg-8">
      <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="fas fa-check-circle me-2"></i>
          Vos informations ont été mises à jour avec succès.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
      
      <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="fas fa-exclamation-circle me-2"></i>
          <?= htmlspecialchars($errors['general']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
      
      <?php if (!empty($errors['profile_image'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="fas fa-exclamation-circle me-2"></i>
          <?= htmlspecialchars($errors['profile_image']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
      
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
          <h5 class="card-title mb-0">Informations personnelles</h5>
        </div>
        <div class="card-body">
          <form method="POST" action="update_profile.php" class="needs-validation" novalidate>
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            
            <div class="mb-3">
              <label for="username" class="form-label">Nom d'utilisateur</label>
              <input type="text" class="form-control <?= !empty($errors['username']) ? 'is-invalid' : '' ?>" 
                     id="username" name="username" value="<?= htmlspecialchars($_SESSION['user']['username']) ?>" required>
              <?php if (!empty($errors['username'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['username']) ?></div>
              <?php else: ?>
                <div class="invalid-feedback">Veuillez entrer un nom d'utilisateur.</div>
              <?php endif; ?>
            </div>
            
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control <?= !empty($errors['email']) ? 'is-invalid' : '' ?>" 
                     id="email" name="email" value="<?= htmlspecialchars($_SESSION['user']['email']) ?>" required>
              <?php if (!empty($errors['email'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></div>
              <?php else: ?>
                <div class="invalid-feedback">Veuillez entrer une adresse email valide.</div>
              <?php endif; ?>
            </div>
            
            <h5 class="mt-4 mb-3">Changer de mot de passe</h5>
            <div class="mb-3">
              <label for="current_password" class="form-label">Mot de passe actuel</label>
              <input type="password" class="form-control <?= !empty($errors['current_password']) ? 'is-invalid' : '' ?>" 
                     id="current_password" name="current_password">
              <?php if (!empty($errors['current_password'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['current_password']) ?></div>
              <?php endif; ?>
            </div>
            
            <div class="mb-3">
              <label for="new_password" class="form-label">Nouveau mot de passe</label>
              <input type="password" class="form-control <?= !empty($errors['new_password']) ? 'is-invalid' : '' ?>" 
                     id="new_password" name="new_password" data-password-strength="password-strength-meter">
              <?php if (!empty($errors['new_password'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['new_password']) ?></div>
              <?php endif; ?>
              <div class="progress mt-2" style="height: 5px;">
                <div id="password-strength-meter" class="progress-bar" role="progressbar" style="width: 0%"></div>
              </div>
            </div>
            
            <div class="mb-3">
              <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe</label>
              <input type="password" class="form-control <?= !empty($errors['confirm_password']) ? 'is-invalid' : '' ?>" 
                     id="confirm_password" name="confirm_password">
              <?php if (!empty($errors['confirm_password'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['confirm_password']) ?></div>
              <?php endif; ?>
            </div>
            
            <div class="text-end">
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Enregistrer les modifications
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Auto-submit profile image form when file is selected
  document.getElementById('profile_image').addEventListener('change', function() {
    document.getElementById('profile-image-form').submit();
  });
});
</script>

<?php include '../includes/footer.php'; ?>
