<?php
// Check if this file is being included from index.php
if (!defined('INCLUDED')) {
    // If accessed directly, redirect to the homepage
    header('Location: ../index.php');
    exit;
}

// Get user ID
$user_id = $_GET['id'];

// Get current language
$currentLang = getCurrentLanguage();

// Check if user exists and get current role
try {
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    if (!$user) {
        $_SESSION['message'] = $currentLang == 'en' ? "User not found." : "រកមិនឃើញអ្នកប្រើប្រាស់។";
        $_SESSION['message_type'] = "danger";
        header('Location: index.php?page=manage-users');
        exit;
    }
    
    // Toggle role
    $new_role = ($user['role'] == 'admin') ? 'editor' : 'admin';
    
    // Update user role
    $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->execute([$new_role, $user_id]);
    
    $_SESSION['message'] = $currentLang == 'en' ? "User role updated successfully." : "តួនាទីអ្នកប្រើប្រាស់ត្រូវបានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ។";
    $_SESSION['message_type'] = "success";
} catch(PDOException $e) {
    $_SESSION['message'] = $currentLang == 'en' ? "Error updating user role: " . $e->getMessage() : "កំហុសក្នុងការធ្វើបច្ចុប្បន្នភាពតួនាទីអ្នកប្រើប្រាស់៖ " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}

// Redirect back to manage users page
header('Location: index.php?page=manage-users');
exit;
?>
