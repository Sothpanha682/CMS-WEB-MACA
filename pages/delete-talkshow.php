<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "You must be logged in to perform this action.";
    $_SESSION['message_type'] = "danger";
    header('Location: ../index.php?page=login');
    exit;
}

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['message'] = "No talkshow specified.";
    $_SESSION['message_type'] = "danger";
    header('Location: ../index.php?page=manage-talkshow');
    exit;
}

$id = $_GET['id'];

try {
    // Delete the talkshow
    $stmt = $pdo->prepare("DELETE FROM talkshows WHERE id = ?");
    $stmt->execute([$id]);
    
    $_SESSION['message'] = "Talkshow deleted successfully.";
    $_SESSION['message_type'] = "success";
} catch(PDOException $e) {
    $_SESSION['message'] = "Error: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}

header('Location: ../index.php?page=manage-talkshow');
exit;
