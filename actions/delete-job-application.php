<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    $_SESSION['message'] = "Access denied. Admin privileges required.";
    $_SESSION['message_type'] = "danger";
    header('Location: ../index.php?page=login');
    exit;
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    try {
        // Get application details including resume path
        $stmt = $pdo->prepare("SELECT resume_path FROM job_applications WHERE id = ?");
        $stmt->execute([$id]);
        $application = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($application) {
            // Delete the application from database
            $stmt = $pdo->prepare("DELETE FROM job_applications WHERE id = ?");
            if ($stmt->execute([$id])) {
                // Delete resume file if exists
                if ($application['resume_path'] && file_exists('../' . $application['resume_path'])) {
                    unlink('../' . $application['resume_path']);
                }
                
                $_SESSION['message'] = "Job application deleted successfully.";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Error deleting job application.";
                $_SESSION['message_type'] = "danger";
            }
        } else {
            $_SESSION['message'] = "Job application not found.";
            $_SESSION['message_type'] = "warning";
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Database error: " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }
} else {
    $_SESSION['message'] = "Invalid request.";
    $_SESSION['message_type'] = "danger";
}

// Redirect back to applications management
header('Location: ../index.php?page=manage-recruitment-applications');
exit;
?>
