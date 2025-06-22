<?php
// Check if user is logged in (removed the admin check)
if (!isLoggedIn()) {
    $_SESSION['message'] = "You must be logged in to access this page.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=login');
    exit;
}

// Get roadshow ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    $_SESSION['message'] = "Invalid roadshow ID.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=manage-roadshow');
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // First, get the roadshow to check if there's an image to delete
    $stmt = $pdo->prepare("SELECT image_path FROM roadshow WHERE id = ?");
    $stmt->execute([$id]);
    $roadshow = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($roadshow) {
        // Delete the image file if it exists
        if (!empty($roadshow['image_path']) && file_exists($roadshow['image_path'])) {
            unlink($roadshow['image_path']);
            error_log("Deleted roadshow image: " . $roadshow['image_path']);
        }
        
        // Delete the roadshow from the database
        $stmt = $pdo->prepare("DELETE FROM roadshow WHERE id = ?");
        $stmt->execute([$id]);
        
        $_SESSION['message'] = "Roadshow deleted successfully.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Roadshow not found.";
        $_SESSION['message_type'] = "warning";
    }
} catch(PDOException $e) {
    $_SESSION['message'] = "Error deleting roadshow: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
    error_log("Error deleting roadshow: " . $e->getMessage());
}

// Redirect back to the manage roadshow page
header('Location: index.php?page=manage-roadshow');
exit;
?>
