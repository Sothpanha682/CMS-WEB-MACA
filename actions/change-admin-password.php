<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    $_SESSION['message'] = "You don't have permission to perform this action.";
    $_SESSION['message_type'] = "danger";
    header('Location: ../index.php?page=login');
    exit;
}

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['message'] = "No roadshow specified.";
    $_SESSION['message_type'] = "danger";
    header('Location: ../index.php?page=manage-roadshow');
    exit;
}

$id = $_GET['id'];

try {
    // First, get the roadshow to check if there's an image to delete
    $stmt = $pdo->prepare("SELECT image_path FROM roadshow WHERE id = ?");
    $stmt->execute([$id]);
    $roadshow = $stmt->fetch();
    
    if ($roadshow) {
        // Delete the image file if it exists
        if (!empty($roadshow['image_path']) && file_exists('../' . $roadshow['image_path'])) {
            if (!unlink('../' . $roadshow['image_path'])) {
                // Log error but continue with database deletion
                error_log("Failed to delete image file: " . $roadshow['image_path']);
            }
        }
        
        // Delete the roadshow from the database
        $stmt = $pdo->prepare("DELETE FROM roadshow WHERE id = ?");
        $stmt->execute([$id]);
        
        $_SESSION['message'] = "Roadshow deleted successfully.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Roadshow not found.";
        $_SESSION['message_type'] = "danger";
    }
} catch (PDOException $e) {
    $_SESSION['message'] = "Error: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
    error_log("Database error in delete-roadshow.php: " . $e->getMessage());
}

// Redirect back to manage roadshow page
header('Location: ../index.php?page=manage-roadshow');
exit;
