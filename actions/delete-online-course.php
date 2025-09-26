<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Function to log messages to a file
function log_message($message) {
    file_put_contents('debug.log', date('[Y-m-d H:i:s]') . ' ' . $message . PHP_EOL, FILE_APPEND);
}

log_message("delete-online-course.php accessed.");

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    $_SESSION['message'] = "Access denied. Admin privileges required.";
    $_SESSION['message_type'] = "danger";
    log_message("Access denied. User not logged in or not admin. Redirecting to login.");
    header('Location: ../index.php?page=login');
    exit;
}
log_message("User is logged in and is admin.");

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    log_message("Course ID received: " . $id);
    
    try {
        // Get course info first to delete associated files
        $stmt = $pdo->prepare("SELECT course_image, instructor_image FROM online_courses WHERE id = ?");
        $stmt->execute([$id]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($course) {
            log_message("Course found: " . json_encode($course));
            // Delete course
            $delete_stmt = $pdo->prepare("DELETE FROM online_courses WHERE id = ?");
            $delete_stmt->execute([$id]);
            log_message("Course with ID " . $id . " deleted from database.");
            
            // Delete associated files
            if ($course['course_image'] && file_exists('../uploads/' . $course['course_image'])) {
                unlink('../uploads/' . $course['course_image']);
                log_message("Course image deleted: " . $course['course_image']);
            } else {
                log_message("Course image not found or path incorrect: " . $course['course_image']);
            }
            if ($course['instructor_image'] && file_exists('../uploads/' . $course['instructor_image'])) {
                unlink('../uploads/' . $course['instructor_image']);
                log_message("Instructor image deleted: " . $course['instructor_image']);
            } else {
                log_message("Instructor image not found or path incorrect: " . $course['instructor_image']);
            }
            
            $_SESSION['message'] = "Course deleted successfully.";
            $_SESSION['message_type'] = "success";
            log_message("Session message set: Course deleted successfully.");
        } else {
            $_SESSION['message'] = "Course not found.";
            $_SESSION['message_type'] = "danger";
            log_message("Session message set: Course not found for ID " . $id);
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error deleting course: " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
        log_message("PDOException: " . $e->getMessage());
    }
} else {
    $_SESSION['message'] = "Invalid course ID.";
    $_SESSION['message_type'] = "danger";
    log_message("Invalid course ID. GET['id'] not set.");
}

log_message("Redirecting to manage-online-courses. Session message: " . ($_SESSION['message'] ?? 'N/A'));
header('Location: ../index.php?page=manage-online-courses');
exit;
?>
