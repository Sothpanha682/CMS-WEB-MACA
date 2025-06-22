<?php
session_start();
define('INCLUDED', true);

// Include database connection
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "You must be logged in to perform this action.";
    $_SESSION['message_type'] = "danger";
    header('Location: ../index.php?page=login');
    exit;
}

// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = "Invalid slider ID.";
    $_SESSION['message_type'] = "danger";
    header('Location: ../index.php?page=manage-slider');
    exit;
}

$id = $_GET['id'];

try {
    // Get current status
    $stmt = $pdo->prepare("SELECT is_active FROM slider_images WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $slider = $stmt->fetch();
    
    if ($slider) {
        // Toggle status
        $new_status = $slider['is_active'] ? 0 : 1;
        
        $stmt = $pdo->prepare("UPDATE slider_images SET is_active = :status WHERE id = :id");
        $stmt->bindParam(':status', $new_status, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $_SESSION['message'] = "Slider status updated successfully.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Slider not found.";
        $_SESSION['message_type'] = "danger";
    }
} catch(PDOException $e) {
    $_SESSION['message'] = "Error: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}

header('Location: ../index.php?page=manage-slider');
exit;
?>
