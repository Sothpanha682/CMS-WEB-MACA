<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    $_SESSION['message'] = "You must be logged in as an administrator to perform this action.";
    $_SESSION['message_type'] = "danger";
    header('Location: ../index.php?page=login');
    exit;
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    try {
        // Get form details before deletion
        $stmt = $pdo->prepare("SELECT * FROM career_counselling_forms WHERE id = ?");
        $stmt->execute([$id]);
        $form = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($form) {
            // Delete the file from server
            if (file_exists($form['file_path'])) {
                unlink($form['file_path']);
            }
            
            // Delete from database
            $stmt = $pdo->prepare("DELETE FROM career_counselling_forms WHERE id = ?");
            $stmt->execute([$id]);
            
            $_SESSION['message'] = "Form deleted successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Form not found.";
            $_SESSION['message_type'] = "danger";
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error deleting form: " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }
} else {
    $_SESSION['message'] = "Invalid form ID.";
    $_SESSION['message_type'] = "danger";
}

header('Location: ../index.php?page=manage-career-counselling-forms');
exit;
?>
