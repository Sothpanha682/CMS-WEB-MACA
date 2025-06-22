<?php
// Start session
session_start();

// Define MACA_CMS constant to prevent direct access error
define('MACA_CMS', true);

// Include database connection
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to perform this action.']);
    exit;
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

// Get slide order data
$slides = isset($_POST['slides']) ? $_POST['slides'] : [];

if (empty($slides)) {
    echo json_encode(['status' => 'error', 'message' => 'No slide order data provided.']);
    exit;
}

try {
    // Begin transaction
    $pdo->beginTransaction();
    
    // Update each slide's display order
    $stmt = $pdo->prepare("UPDATE slides SET display_order = ? WHERE id = ?");
    
    foreach ($slides as $slide) {
        $stmt->execute([$slide['order'], $slide['id']]);
    }
    
    // Commit transaction
    $pdo->commit();
    
    echo json_encode(['status' => 'success', 'message' => 'Slide order updated successfully.']);
} catch(PDOException $e) {
    // Rollback transaction on error
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
