<?php
// public/update_profile.php
require '../includes/auth_check.php';
require '../config/db.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid request');
    }
    
    $userId = $_SESSION['user']['id'];    $username = trim(filter_var($_POST['username'], FILTER_SANITIZE_STRING));
    $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Validate username
    if (empty($username)) {
        $errors['username'] = "Username is required";
    } elseif (strlen($username) < 3) {
        $errors['username'] = "Username must be at least 3 characters";
    }
    
    // Validate email
    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    } else {
        // Check if email already exists for another user
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $userId]);
        if ($stmt->fetchColumn() > 0) {
            $errors['email'] = "Email already in use by another account";
        }
    }
    
    // If password change is requested
    $passwordChanged = false;
    if (!empty($currentPassword) || !empty($newPassword) || !empty($confirmPassword)) {
        // Verify current password
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $currentHashedPassword = $stmt->fetchColumn();
        
        if (!password_verify($currentPassword, $currentHashedPassword)) {
            $errors['current_password'] = "Current password is incorrect";
        } elseif (empty($newPassword)) {
            $errors['new_password'] = "New password is required";
        } elseif (strlen($newPassword) < 6) {
            $errors['new_password'] = "New password must be at least 6 characters";
        } elseif ($newPassword !== $confirmPassword) {
            $errors['confirm_password'] = "Passwords do not match";
        } else {
            $passwordChanged = true;
        }
    }
    
    // Update user information if no errors
    if (empty($errors)) {
        try {
            $pdo->beginTransaction();
            
            // Update username and email
            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
            $stmt->execute([$username, $email, $userId]);
            
            // Update password if changed
            if ($passwordChanged) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hashedPassword, $userId]);
            }
            
            $pdo->commit();
            
            // Update session data
            $_SESSION['user']['username'] = $username;
            $_SESSION['user']['email'] = $email;
            
            // Set success flag
            $_SESSION['profile_updated'] = true;
            
            // Redirect back to profile
            header('Location: profile.php');
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            $errors['general'] = "Error updating profile: " . $e->getMessage();
        }
    }
}

// If we get here, there were errors
$_SESSION['profile_errors'] = $errors;
header('Location: profile.php');
exit;
