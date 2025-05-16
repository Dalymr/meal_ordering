<?php
session_start();
require '../config/db.php';

// Redirect if already logged in
if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['is_admin']) {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: index.php');
    }
    exit;
}

// Initialize variables
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize user input
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Tous les champs sont obligatoires";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Regenerate session ID to prevent session fixation attacks
            session_regenerate_id(true);
            $_SESSION['user'] = $user;
            
            // Redirect based on user role
            if ($user['is_admin']) {
                header('Location: admin/dashboard.php');
            } else {
                header('Location: index.php');
            }
            exit;
        } else {
            $error = "Identifiants invalides";
        }
    }
}

include '../includes/header.php';
?>

<div class="auth-form-container">
    <div class="card auth-form">
        <div class="card-header">
            <h2>Connexion</h2>
        </div>
        
        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form method="POST" class="auth-form">
                <div class="input-group">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                </div>

                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe" required>
                </div>

                <button type="submit" class="btn auth-submit-btn w-100">
                    Connexion
                </button>

                <div class="auth-links text-center mt-3">
                    <p>Pas encore inscrit ? <a href="register.php">Créer un compte</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
