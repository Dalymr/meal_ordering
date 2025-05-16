<?php
require '../../includes/auth_check.php';
require '../../config/db.php';

if (!$_SESSION['user']['is_admin']) {
    header('Location: ../index.php');
    exit;
}

$error = '';
$success = '';

// Handle add/edit/delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF validation
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid request";
    } else {
        // Upload image if provided
        if (!empty($_FILES['image']['name'])) {
            $targetDir = '../../uploads/';
            
            // Create directory if it doesn't exist
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $targetFile = $targetDir . basename($_FILES['image']['name']);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Validate file type
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($imageFileType, $allowedTypes)) {
                $error = "Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
            } elseif (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $error = "Erreur lors du téléchargement de l'image.";
            } else {
                $image = basename($_FILES['image']['name']);
            }
        } else {
            $image = $_POST['existing_image'] ?? '';
        }

        // Process form if no errors
        if (empty($error)) {
            if (isset($_POST['add'])) {
                $stmt = $pdo->prepare("INSERT INTO meals (name, description, price, image) VALUES (?, ?, ?, ?)");
                $stmt->execute([$_POST['name'], $_POST['description'], $_POST['price'], $image]);
                $success = "Repas ajouté avec succès!";
            }
            
            if (isset($_POST['delete']) && isset($_POST['id'])) {
                $stmt = $pdo->prepare("DELETE FROM meals WHERE id = ?");
                $stmt->execute([$_POST['id']]);
                $success = "Repas supprimé avec succès!";
            }
        }
    }
}

// Fetch meals
$search = $_GET['q'] ?? '';
if ($search) {
    $stmt = $pdo->prepare("SELECT * FROM meals WHERE name LIKE ?");
    $stmt->execute(["%{$search}%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM meals");
}
$meals = $stmt->fetchAll();

include '../../includes/admin_header.php';
?>

<div class="container-fluid py-4">
    <h1 class="mb-4">
        <i class="fas fa-utensils me-2 text-primary"></i>Gestion des Repas
    </h1>

    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <!-- Meals Management Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Liste des Repas
                    </h5>
                    <form class="d-flex" method="GET" action="manage_meals.php">
                        <div class="input-group">
                            <input type="text" class="form-control" name="q" placeholder="Rechercher..." 
                                   value="<?= htmlspecialchars($search) ?>" aria-label="Search">
                            <button class="btn btn-light" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <?php if ($meals): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Image</th>
                                        <th scope="col">Nom</th>
                                        <th scope="col">Prix</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($meals as $m): ?>
                                        <tr>
                                            <td>
                                                <?php if (!empty($m['image'])): ?>
                                                    <img src="../../uploads/<?= htmlspecialchars($m['image']) ?>" 
                                                         alt="<?= htmlspecialchars($m['name']) ?>" 
                                                         class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                         style="width: 60px; height: 60px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <h6 class="mb-0"><?= htmlspecialchars($m['name']) ?></h6>
                                                <small class="text-muted"><?= mb_strimwidth(htmlspecialchars($m['description']), 0, 50, "...") ?></small>
                                            </td>
                                            <td>
                                                <span class="badge bg-success fs-6">€<?= number_format($m['price'], 2) ?></span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="edit_meal.php?id=<?= $m['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i> Modifier
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            data-bs-toggle="modal" data-bs-target="#deleteModal<?= $m['id'] ?>">
                                                        <i class="fas fa-trash"></i> Supprimer
                                                    </button>
                                                </div>
                                                
                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteModal<?= $m['id'] ?>" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-danger text-white">
                                                                <h5 class="modal-title">Confirmer la suppression</h5>
                                                                <button type="button" class="btn-close btn-close-white" 
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Êtes-vous sûr de vouloir supprimer <strong><?= htmlspecialchars($m['name']) ?></strong> ?</p>
                                                                <p class="text-danger"><small>Cette action est irréversible.</small></p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                <form method="POST">
                                                                    <input type="hidden" name="id" value="<?= $m['id'] ?>">
                                                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                                    <button type="submit" name="delete" class="btn btn-danger">Confirmer</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <p class="lead">Aucun repas trouvé.</p>
                            <?php if ($search): ?>
                                <a href="manage_meals.php" class="btn btn-outline-primary">Voir tous les repas</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Add New Meal Section -->
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Ajouter un Nouveau Repas
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom du repas</label>
                            <input id="name" name="name" type="text" class="form-control" required>
                            <div class="invalid-feedback">Veuillez entrer un nom pour le repas.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="price" class="form-label">Prix (€)</label>
                            <div class="input-group">
                                <span class="input-group-text">€</span>
                                <input id="price" name="price" type="number" step="0.01" min="0" class="form-control" required>
                                <div class="invalid-feedback">Veuillez entrer un prix valide.</div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="image" class="form-label">Image</label>
                            <input id="image" name="image" type="file" class="form-control" accept="image/*" data-preview="image-preview">
                            <div class="form-text">Format recommandé: JPG, PNG ou GIF. Max 2MB.</div>
                            <div class="mt-2 text-center d-none" id="image-preview-container">
                                <img id="image-preview" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button name="add" type="submit" class="btn btn-success">
                                <i class="fas fa-plus-circle me-2"></i>Ajouter le Repas
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
    // Image preview script
    const imageInput = document.getElementById('image');
    const previewContainer = document.getElementById('image-preview-container');
    const preview = document.getElementById('image-preview');
    
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('d-none');
                }
                
                reader.readAsDataURL(this.files[0]);
            } else {
                previewContainer.classList.add('d-none');
            }
        });
    }
});
</script>

<?php include '../../includes/footer.php'; ?>
