<?php
// Prevent direct access
define('INCLUDED', true);
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is logged in
session_start();
if (!isLoggedIn()) {
    $_SESSION['message'] = "You must be logged in to perform this action.";
    $_SESSION['message_type'] = "danger";
    header('Location: ../index.php?page=login');
    exit;
}

// Check if ID is provided
if (!isset($_GET['id'])) {
    $_SESSION['message'] = "No news article specified.";
    $_SESSION['message_type'] = "danger";
    header('Location: ../index.php?page=scheduled-news');
    exit;
}

$news_id = $_GET['id'];

try {
    // Set publish_at to NULL to publish immediately
    $stmt = $pdo->prepare("UPDATE news SET publish_at = NULL WHERE id = :id");
    $stmt->bindParam(':id', $news_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $_SESSION['message'] = "News article published successfully!";
    $_SESSION['message_type'] = "success";
} catch(PDOException $e) {
    $_SESSION['message'] = "Error publishing news article: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
    error_log("Error publishing news: " . $e->getMessage());
}

header('Location: ../index.php?page=scheduled-news');
exit;
