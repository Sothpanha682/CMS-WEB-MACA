<?php
// Include database configuration
require_once '../config/database.php';

// Check if user is logged in as admin
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    $_SESSION['message'] = "You must be logged in as an admin to perform this action.";
    $_SESSION['message_type'] = "danger";
    header('Location: ../index.php?page=login');
    exit;
}

// Process the form submission
$id = $_POST['id'] ?? '';
$is_update = !empty($id);

// Handle image upload
$image_url = '';
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $upload_dir = '../uploads/slides/';
    
    // Create directory if it doesn't exist
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file_name = time() . '_' . basename($_FILES['image']['name']);
    $target_file = $upload_dir . $file_name;
    
    // Move uploaded file
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $image_url = 'uploads/slides/' . $file_name;
    } else {
        $_SESSION['message'] = "Sorry, there was an error uploading your file.";
        $_SESSION['message_type'] = "danger";
        header('Location: ../index.php?page=manage-slides');
        exit;
    }
} elseif ($is_update) {
    // If updating and no new image uploaded, keep the existing image
    $stmt = $pdo->prepare("SELECT image_url FROM slides WHERE id = ?");
    $stmt->execute([$id]);
    $current_slide = $stmt->fetch();
    if ($current_slide) {
        $image_url = $current_slide['image_url'];
    }
}

try {
    // Update or insert slide
    if ($is_update) {
        $stmt = $pdo->prepare("UPDATE slides SET image_url = ? WHERE id = ?");
        $stmt->execute([$image_url, $id]);
        $message = "Slide updated successfully!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO slides (image_url) VALUES (?)");
        $stmt->execute([$image_url]);
        $message = "Slide added successfully!";
    }
    
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = "success";
} catch (PDOException $e) {
    $_SESSION['message'] = "Error: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}

header('Location: ../index.php?page=manage-slides');
exit;
?>
