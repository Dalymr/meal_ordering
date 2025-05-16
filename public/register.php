<?php
// public/register.php
require '../config/db.php';
session_start();

// Redirect if already logged in
if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['is_admin']) {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: index.php');
    }
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize user input
    $username = trim(filter_var($_POST['username'], FILTER_SANITIZE_STRING));
    $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'] ?? '';
    
    // Validate username
    if (empty($username)) {
        $errors['username'] = "Le nom d'utilisateur est requis";
    } elseif (strlen($username) < 3) {
        $errors['username'] = "Le nom d'utilisateur doit avoir au moins 3 caractères";
    }
    
    // Validate email
    if (empty($email)) {
        $errors['email'] = "L'email est requis";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Format d'email invalide";
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $errors['email'] = "Cet email est déjà utilisé";
        }
    }
    
    // Validate password
    if (empty($password)) {
        $errors['password'] = "Le mot de passe est requis";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "Le mot de passe doit avoir au moins 6 caractères";
    } elseif ($password !== $password_confirm) {
        $errors['password_confirm'] = "Les mots de passe ne correspondent pas";
    }
    
    // If no errors, create user
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO users (username, email, password) VALUES (?, ?, ?)"
            );
            $stmt->execute([$username, $email, $hashed_password]);
            
            // Set success message and redirect
            $_SESSION['success_message'] = "Compte créé avec succès! Vous pouvez maintenant vous connecter.";
            header('Location: login.php');
            exit;
        } catch (PDOException $e) {
            $errors['general'] = "Une erreur est survenue lors de la création du compte.";
            error_log("Registration error: " . $e->getMessage());
        }
    }
}

include '../includes/header.php';
?>

<div class="auth-form-container">
    <div class="card auth-form">
        <div class="card-header">
            <h2>Inscription</h2>
        </div>
        
        <div class="card-body">
            <?php if (!empty($errors['general'])): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($errors['general']) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="auth-form" novalidate>
                <div class="input-group">
                    <input type="text" class="form-control <?= !empty($errors['username']) ? 'is-invalid' : '' ?>" 
                           id="username" name="username" placeholder="Nom d'utilisateur" 
                           value="<?= htmlspecialchars($username ?? '') ?>" required>
                    <?php if (!empty($errors['username'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['username']) ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="input-group">
                    <input type="email" class="form-control <?= !empty($errors['email']) ? 'is-invalid' : '' ?>" 
                           id="email" name="email" placeholder="Email" 
                           value="<?= htmlspecialchars($email ?? '') ?>" required>
                    <?php if (!empty($errors['email'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="input-group">
                    <input type="password" class="form-control <?= !empty($errors['password']) ? 'is-invalid' : '' ?>" 
                           id="password" name="password" placeholder="Mot de passe" required>
                    <?php if (!empty($errors['password'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['password']) ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="input-group">
                    <input type="password" class="form-control <?= !empty($errors['password_confirm']) ? 'is-invalid' : '' ?>" 
                           id="password_confirm" name="password_confirm" placeholder="Confirmer le mot de passe" required>
                    <?php if (!empty($errors['password_confirm'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['password_confirm']) ?></div>
                    <?php endif; ?>
                </div>
                
                <button type="submit" class="btn auth-submit-btn w-100">
                    Inscription
                </button>
                
                <div class="auth-links text-center mt-3">
                    <p>Déjà inscrit ? <a href="login.php">Se connecter</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</html>
