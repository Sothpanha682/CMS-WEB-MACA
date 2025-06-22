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
    $_SESSION['message'] = "No announcement specified.";
    $_SESSION['message_type'] = "danger";
    header('Location: ../index.php?page=dashboard');
    exit;
}

$announcement_id = $_GET['id'];

try {
    // Get the announcement to check if it has an image
    $stmt = $pdo->prepare("SELECT image_path FROM announcements WHERE id = :id");
    $stmt->bindParam(':id', $announcement_id);
    $stmt->execute();
    $announcement = $stmt->fetch();
    
    // Delete the image file if it exists
    if ($announcement && $announcement['image_path'] && file_exists('../' . $announcement['image_path'])) {
        unlink('../' . $announcement['image_path']);
    }
    
    // Delete the announcement from the database
    $stmt = $pdo->prepare("DELETE FROM announcements WHERE id = :id");
    $stmt->bindParam(':id', $announcement_id);
    $stmt->execute();
    
    $_SESSION['message'] = "Announcement deleted successfully!";
    $_SESSION['message_type'] = "success";
} catch(PDOException $e) {
    $_SESSION['message'] = "Error deleting announcement: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}

header('Location: ../index.php?page=dashboard');
exit;
?>
