<?php
include '../config/database.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "You must be logged in to perform this action.";
    $_SESSION['message_type'] = "danger";
    header('Location: ../index.php?page=login');
    exit;
}

// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = "Invalid major ID.";
    $_SESSION['message_type'] = "danger";
    header('Location: ../index.php?page=manage-popular-majors');
    exit;
}

$major_id = (int)$_GET['id'];

try {
    // First, get the image path to delete the file
    $stmt = $pdo->prepare("SELECT image_path FROM popular_majors WHERE id = :id");
    $stmt->bindParam(':id', $major_id);
    $stmt->execute();
    $major = $stmt->fetch();
    
    // Delete the major from database
    $stmt = $pdo->prepare("DELETE FROM popular_majors WHERE id = :id");
    $stmt->bindParam(':id', $major_id);
    $stmt->execute();
    
    // Delete the image file if it exists
    if ($major && !empty($major['image_path']) && file_exists('../' . $major['image_path'])) {
        unlink('../' . $major['image_path']);
    }
    
    $_SESSION['message'] = "Major deleted successfully.";
    $_SESSION['message_type'] = "success";
} catch(PDOException $e) {
    $_SESSION['message'] = "Error deleting major: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}

header('Location: ../index.php?page=manage-popular-majors');
exit;
?>
