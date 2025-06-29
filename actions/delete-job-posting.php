<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/functions.php';
require_once 'config/database.php';

// Prevent direct access to this file
if (!defined('INCLUDED')) {
    define('INCLUDED', true);
}

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    $_SESSION['message'] = "Access denied. Admin privileges required.";
    $_SESSION['message_type'] = "danger";
    header('Location: ../index.php?page=login');
    exit;
}

// Get job ID from POST request
$job_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if (!$job_id) {
    $_SESSION['message'] = "Invalid job ID.";
    $_SESSION['message_type'] = "danger";
    header('Location: ../index.php?page=manage-recruitment-applications&view=postings');
    exit;
}

try {
    // Start transaction
    $pdo->beginTransaction();
    
    // Get all applications for this job to delete resume files
    $stmt = $pdo->prepare("SELECT resume_path, cover_letter_path FROM job_applications WHERE job_id = ?");
    $stmt->execute([$job_id]);
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Delete resume and cover letter files
    foreach ($applications as $app) {
        if (!empty($app['resume_path']) && file_exists($app['resume_path'])) {
            unlink($app['resume_path']);
        }
        if (!empty($app['cover_letter_path']) && file_exists($app['cover_letter_path'])) {
            unlink($app['cover_letter_path']);
        }
    }
    
    // Delete all applications for this job
    $stmt = $pdo->prepare("DELETE FROM job_applications WHERE job_id = ?");
    $stmt->execute([$job_id]);
    
    // Delete the job posting
    $stmt = $pdo->prepare("DELETE FROM job_postings WHERE id = ?");
    $stmt->execute([$job_id]);
    
    // Commit transaction
    $pdo->commit();
    
    $_SESSION['message'] = "Job posting and all related applications have been deleted successfully.";
    $_SESSION['message_type'] = "success";
    
} catch (PDOException $e) {
    // Rollback transaction
    $pdo->rollback();
    $_SESSION['message'] = "Error deleting job posting: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}

header('Location: ../index.php?page=manage-recruitment-applications&view=postings');
exit;
?>
