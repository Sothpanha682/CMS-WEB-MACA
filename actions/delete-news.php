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
    $_SESSION['message'] = "No news article specified.";
    $_SESSION['message_type'] = "danger";
    header('Location: ../index.php?page=dashboard');
    exit;
}

$news_id = $_GET['id'];

try {
    // Get the news to check if it has an image
    $stmt = $pdo->prepare("SELECT image_path FROM news WHERE id = :id");
    $stmt->bindParam(':id', $news_id);
    $stmt->execute();
    $news = $stmt->fetch();
    
    // Delete the image file if it exists
    if ($news && $news['image_path'] && file_exists('../' . $news['image_path'])) {
        unlink('../' . $news['image_path']);
    }
    
    // Delete the news from the database
    $stmt = $pdo->prepare("DELETE FROM news WHERE id = :id");
    $stmt->bindParam(':id', $news_id);
    $stmt->execute();
    
    $_SESSION['message'] = "News article deleted successfully!";
    $_SESSION['message_type'] = "success";
} catch(PDOException $e) {
    $_SESSION['message'] = "Error deleting news article: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}

header('Location: ../index.php?page=dashboard');
exit;
?>
