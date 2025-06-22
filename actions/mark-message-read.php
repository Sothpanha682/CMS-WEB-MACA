<?php
// Don't start session here as it's already started in index.php
// session_start();

// Use direct paths since we're including from the root
require_once 'config/database.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    $_SESSION['message'] = "You must be logged in to perform this action.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=login');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = "Invalid message ID.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=dashboard#messages-section');
    exit;
}

$message_id = (int)$_GET['id'];

try {
    // Mark the message as read
    $stmt = $pdo->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = :id");
    $stmt->bindParam(':id', $message_id);
    $stmt->execute();
    
    $_SESSION['message'] = "Message marked as read.";
    $_SESSION['message_type'] = "success";
} catch(PDOException $e) {
    $_SESSION['message'] = "Error updating message: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}

header('Location: index.php?page=dashboard#messages-section');
exit;
?>
