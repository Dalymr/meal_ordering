<?php
require '../includes/auth_check.php';
require '../config/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid request');
    }
    
    $userId = $_SESSION['user']['id'];
    
    // Check if file was uploaded without errors
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_image']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        // Validate file extension
        if (!in_array(strtolower($filetype), $allowed)) {
            $errors['profile_image'] = "Seuls les fichiers JPG, JPEG, PNG et GIF sont acceptés.";
        }
        
        // Validate file size (max 5MB)
        if ($_FILES['profile_image']['size'] > 5 * 1024 * 1024) {
            $errors['profile_image'] = "La taille du fichier ne doit pas dépasser 5MB.";
        }
        
        if (empty($errors)) {
            // Create upload directory if it doesn't exist
            $uploadDir = '../uploads/profiles/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // Generate unique filename
            $newFilename = $userId . '_' . time() . '.' . $filetype;
            $uploadPath = $uploadDir . $newFilename;
            
            // Move uploaded file
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadPath)) {
                try {
                    // Update user profile image in database
                    $stmt = $pdo->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
                    if ($stmt->execute([$newFilename, $userId])) {
                        // Update session data
                        $_SESSION['user']['profile_image'] = $newFilename;
                        $_SESSION['profile_updated'] = true;
                    } else {
                        $errors['profile_image'] = "Erreur de mise à jour de la base de données.";
                    }
                } catch (PDOException $e) {
                    $errors['profile_image'] = "Erreur: " . $e->getMessage();
                }
            } else {
                $errors['profile_image'] = "Erreur lors du téléchargement de l'image.";
            }
        }
    } else {
        $errors['profile_image'] = "Veuillez sélectionner une image.";
    }
}

// Save errors to session and redirect back to profile
if (!empty($errors)) {
    $_SESSION['profile_errors'] = $errors;
}

header('Location: profile.php');
exit;
