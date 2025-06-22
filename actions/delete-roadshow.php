<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
session_start();

// Check if user is logged in
if (!isLoggedIn()) {
    $_SESSION['message'] = "You must be logged in to perform this action.";
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
    // Get roadshow info to delete image if exists
    $stmt = $pdo->prepare("SELECT image_path FROM roadshows WHERE id = ?");
    $stmt->execute([$id]);
    $roadshow = $stmt->fetch();
    
    if ($roadshow && !empty($roadshow['image_path']) && file_exists('../' . $roadshow['image_path'])) {
        unlink('../' . $roadshow['image_path']);
    }
    
    // Delete the roadshow
    $stmt = $pdo->prepare("DELETE FROM roadshows WHERE id = ?");
    $stmt->execute([$id]);
    
    $_SESSION['message'] = "Roadshow deleted successfully.";
    $_SESSION['message_type'] = "success";
} catch(PDOException $e) {
    $_SESSION['message'] = "Error: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}

header('Location: ../index.php?page=manage-roadshow');
exit;
