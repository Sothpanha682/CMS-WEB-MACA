<?php
// Start session
session_start();

// Define MACA_CMS constant to prevent direct access error
define('MACA_CMS', true);

// Include database connection
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "You must be logged in to perform this action.";
    $_SESSION['message_type'] = "danger";
    header('Location: ../index.php?page=login');
    exit;
}

// Get slide ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    try {
        // Get slide image path
        $stmt = $pdo->prepare("SELECT image_path FROM slides WHERE id = ?");
        $stmt->execute([$id]);
        $slide = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Delete slide
        $stmt = $pdo->prepare("DELETE FROM slides WHERE id = ?");
        $stmt->execute([$id]);
        
        // Delete image file if it exists
        if ($slide && !empty($slide['image_path']) && file_exists('../' . $slide['image_path'])) {
            unlink('../' . $slide['image_path']);
        }
        
        $_SESSION['message'] = "Slide deleted successfully.";
        $_SESSION['message_type'] = "success";
    } catch(PDOException $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }
} else {
    $_SESSION['message'] = "Invalid slide ID.";
    $_SESSION['message_type'] = "danger";
}

// Redirect back to manage slides page
header('Location: ../index.php?page=manage-slides');
exit;
