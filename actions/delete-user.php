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

// Prevent deleting your own account
if ($_SESSION['user_id'] == $user_id) {
    $_SESSION['message'] = $currentLang == 'en' ? "You cannot delete your own account." : "អ្នកមិនអាចលុបគណនីផ្ទាល់ខ្លួនរបស់អ្នកបានទេ។";
    $_SESSION['message_type'] = "danger";
} else {
    // Delete user
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        
        $_SESSION['message'] = $currentLang == 'en' ? "User deleted successfully." : "អ្នកប្រើប្រាស់ត្រូវបានលុបដោយជោគជ័យ។";
        $_SESSION['message_type'] = "success";
    } catch(PDOException $e) {
        $_SESSION['message'] = $currentLang == 'en' ? "Error deleting user: " . $e->getMessage() : "កំហុសក្នុងការលុបអ្នកប្រើប្រាស់៖ " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }
}

// Use JavaScript to redirect
echo "<script>window.location.href = 'index.php?page=manage-users';</script>";
exit;
?>
