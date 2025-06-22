<?php
session_start();
define('INCLUDED', true);

// Include database connection
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "You must be logged in to perform this action.";
    $_SESSION['message_type'] = "danger";
    header('Location: ../index.php?page=login');
    exit;
}

// Check if data is provided
if (!isset($_POST['order']) || !is_array($_POST['order'])) {
    $_SESSION['message'] = "Invalid data.";
    $_SESSION['message_type'] = "danger";
    header('Location: ../index.php?page=manage-slider');
    exit;
}

try {
    // Begin transaction
    $pdo->beginTransaction();
    
    foreach ($_POST['order'] as $position => $id) {
        $stmt = $pdo->prepare("UPDATE slider_images SET display_order = :position WHERE id = :id");
        $stmt->bindParam(':position', $position, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    // Commit transaction
    $pdo->commit();
    
    $_SESSION['message'] = "Slider order updated successfully.";
    $_SESSION['message_type'] = "success";
} catch(PDOException $e) {
    // Rollback transaction
    $pdo->rollBack();
    
    $_SESSION['message'] = "Error: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}

header('Location: ../index.php?page=manage-slider');
exit;
?>
