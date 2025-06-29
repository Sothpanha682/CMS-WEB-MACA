<?php
session_start();
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
        // Get course info first to delete associated files
        $stmt = $pdo->prepare("SELECT course_image, instructor_image FROM online_courses WHERE id = ?");
        $stmt->execute([$id]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($course) {
            // Delete course
            $delete_stmt = $pdo->prepare("DELETE FROM online_courses WHERE id = ?");
            $delete_stmt->execute([$id]);
            
            // Delete associated files
            if ($course['course_image'] && file_exists('../uploads/' . $course['course_image'])) {
                unlink('../uploads/' . $course['course_image']);
            }
            if ($course['instructor_image'] && file_exists('../uploads/' . $course['instructor_image'])) {
                unlink('../uploads/' . $course['instructor_image']);
            }
            
            $_SESSION['message'] = "Course deleted successfully.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Course not found.";
            $_SESSION['message_type'] = "danger";
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error deleting course: " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }
} else {
    $_SESSION['message'] = "Invalid course ID.";
    $_SESSION['message_type'] = "danger";
}

header('Location: ../index.php?page=manage-online-courses');
exit;
?>
