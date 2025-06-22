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
    // Delete the message
    $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = :id");
    $stmt->bindParam(':id', $message_id);
    $stmt->execute();
    
    $_SESSION['message'] = "Message deleted successfully.";
    $_SESSION['message_type'] = "success";
} catch(PDOException $e) {
    $_SESSION['message'] = "Error deleting message: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}

header('Location: index.php?page=dashboard#messages-section');
exit;
?>
