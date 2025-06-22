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
    $_SESSION['message'] = "Invalid career ID.";
    $_SESSION['message_type'] = "danger";
    header('Location: ../index.php?page=manage-popular-career');
    exit;
}

$job_id = (int)$_GET['id'];

try {
    // First, get the image path to delete the file
    $stmt = $pdo->prepare("SELECT image_path FROM popular_jobs WHERE id = :id");
    $stmt->bindParam(':id', $job_id);
    $stmt->execute();
    $job = $stmt->fetch();
    
    // Delete the job from database
    $stmt = $pdo->prepare("DELETE FROM popular_jobs WHERE id = :id");
    $stmt->bindParam(':id', $job_id);
    $stmt->execute();
    
    // Delete the image file if it exists
    if ($job && !empty($job['image_path']) && file_exists('../' . $job['image_path'])) {
        unlink('../' . $job['image_path']);
    }
    
    $_SESSION['message'] = "Career deleted successfully.";
    $_SESSION['message_type'] = "success";
} catch(PDOException $e) {
    $_SESSION['message'] = "Error deleting career: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}

header('Location: ../index.php?page=manage-popular-career');
exit;
?>
