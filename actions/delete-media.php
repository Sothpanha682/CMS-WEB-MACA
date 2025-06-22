<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    $_SESSION['message'] = "You must be logged in to perform this action.";
    $_SESSION['message_type'] = "danger";
    header('Location: ../index.php?page=login');
    exit;
}

if (!isset($_GET['id'])) {
    $_SESSION['message'] = "No media specified.";
    $_SESSION['message_type'] = "danger";
    header('Location: ../index.php?page=manage-media');
    exit;
}

$media_id = $_GET['id'];

try {
    // Get the media to check if it has a file
    $stmt = $pdo->prepare("SELECT file_path FROM media WHERE id = :id");
    $stmt->bindParam(':id', $media_id);
    $stmt->execute();
    $media = $stmt->fetch();
    
    // Delete the file if it exists
    if ($media && $media['file_path'] && file_exists('../' . $media['file_path'])) {
        unlink('../' . $media['file_path']);
    }
    
    // Delete the media from the database
    $stmt = $pdo->prepare("DELETE FROM media WHERE id = :id");
    $stmt->bindParam(':id', $media_id);
    $stmt->execute();
    
    $_SESSION['message'] = "Media deleted successfully!";
    $_SESSION['message_type'] = "success";
} catch(PDOException $e) {
    $_SESSION['message'] = "Error deleting media: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}

header('Location: ../index.php?page=manage-media');
exit;
?>
