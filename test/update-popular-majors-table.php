<?php
// Define that this file is included
define('INCLUDED', true);

// Include database connection
require_once 'config/database.php';

// Check if user is logged in and is admin
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php?page=login');
    exit;
}

// Function to execute SQL file
function executeSQLFile($pdo, $file) {
    try {
        // Read the SQL file
        $sql = file_get_contents($file);
        
        // Execute the SQL
        $pdo->exec($sql);
        
        return true;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

// Execute the SQL file
$result = executeSQLFile($pdo, 'sql/add_job_fields_to_majors.sql');

// Check the result
if ($result === true) {
    $_SESSION['message'] = "Popular Majors table updated successfully with job-related fields.";
    $_SESSION['message_type'] = "success";
} else {
    $_SESSION['message'] = "Error updating Popular Majors table: " . $result;
    $_SESSION['message_type'] = "danger";
}

// Redirect back to the dashboard
header('Location: index.php?page=dashboard');
exit;
?>
