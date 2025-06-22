<?php
// Start session
session_start();

// Set language
if (isset($_GET['lang']) && ($_GET['lang'] == 'en' || $_GET['lang'] == 'kh')) {
    $_SESSION['lang'] = $_GET['lang'];
}

// Redirect back to the previous page
if (isset($_GET['redirect'])) {
    $redirect = $_GET['redirect'];
    
    // Make sure the redirect URL is safe
    if (filter_var($redirect, FILTER_VALIDATE_URL)) {
        // For external URLs, only allow redirects to the same host
        $host = parse_url($redirect, PHP_URL_HOST);
        $current_host = $_SERVER['HTTP_HOST'];
        
        if ($host !== $current_host) {
            // If hosts don't match, redirect to home
            header("Location: ../index.php");
            exit;
        }
    }
    
    header("Location: $redirect");
} else {
    // Determine the correct path to redirect to
    $script_path = $_SERVER['SCRIPT_NAME'];
    
    if (strpos($script_path, '/actions/') !== false) {
        // We're in the actions directory, go up one level
        header("Location: ../index.php");
    } else {
        // Default redirect to home page
        header("Location: index.php");
    }
}
exit;
?>
