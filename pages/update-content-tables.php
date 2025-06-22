<?php
// Include database configuration
require_once 'config/database.php';

// Check if user is logged in as admin
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    echo "Access denied. You must be an admin to run this script.";
    exit;
}

try {
    // Read the SQL file
    $sql = file_get_contents('update-multilingual-content-tables.sql');
    
    // Execute the SQL commands
    $pdo->exec($sql);
    
    echo "Database tables updated successfully to support multilingual content!";
} catch (PDOException $e) {
    echo "Error updating database tables: " . $e->getMessage();
}
?>
