<?php
// Start output buffering at the very beginning of the main file
ob_start();

// Start session
session_start();

// Define a constant to prevent direct access to included files
if (!defined('INCLUDED')) {
    define('INCLUDED', true);
}

// Include database connection
require_once 'config/database.php';

// Include functions
require_once 'includes/functions.php';

// Set default language if not set
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en';
}

// Handle language change
if (isset($_GET['lang']) && ($_GET['lang'] == 'en' || $_GET['lang'] == 'kh')) {
    $_SESSION['lang'] = $_GET['lang'];
    
    // Redirect back to the same page without the lang parameter
    $redirect = strtok($_SERVER['REQUEST_URI'], '?');
    if (isset($_GET['page'])) {
        $redirect .= '?page=' . $_GET['page'];
        
        // Preserve search parameter if it exists
        if (isset($_GET['search'])) {
            $redirect .= '&search=' . urlencode($_GET['search']);
        }
    }
    header('Location: ' . $redirect);
    exit;
}

// Handle direct actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    // Handle message actions
    if ($action == 'mark-message-read' && isset($_GET['id'])) {
        require_once 'actions/mark-message-read.php';
        exit;
    } elseif ($action == 'delete-message' && isset($_GET['id'])) {
        require_once 'actions/delete-message.php';
        exit;
    }
    
    // Handle user actions
    elseif ($action == 'delete-user' && isset($_GET['id'])) {
        require_once 'actions/delete-user.php';
        exit;
    } elseif ($action == 'toggle-admin-status' && isset($_GET['id'])) {
        require_once 'actions/toggle-admin-status.php';
        exit;
    }
    
    // Add other actions as needed
}

// Get the requested page
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Sanitize the page name
$page = preg_replace('/[^a-zA-Z0-9_\/\-]/', '', $page); // Allow slashes in page names

// Define allowed pages
$allowed_pages = [
    'home', 'about', 'contact', 'login', 'dashboard', 
    'announcements', 'news', 'news-detail', 'add-announcement', 
    'edit-announcement', 'add-news', 'edit-news', 'manage-media',
    'manage-team-members', 'add-team-member', 'edit-team-member',
    'manage-site-settings', 'manage-users', 'add-user', 'edit-user',
    'manage-popular-jobs', 'add-popular-job', 'edit-popular-job',
    'manage-popular-majors', 'add-popular-major', 'edit-popular-major',
    'manage-career-paths', 'add-career-path', 'edit-career-path',
    'explore/popular-jobs', 'explore/popular-majors', 'explore/career-paths',
    'program/online-learning', 'program/career-counselling', 'program/internship',
    'manage-talkshow', 'add-talkshow', 'edit-talkshow',
    'manage-roadshow', 'add-roadshow', 'edit-roadshow',
    'program/talkshow', 'program/roadshow',
    'talkshow-detail', 'roadshow-detail',
    'popular-majors', 'popular-jobs',
    'manage-messages','news.php',
    '404','edit-internship', 'talkshow', 'roadshow', 'career-paths', 'popular-majors', 'popular-jobs'
    ,'add-internship', 'edit-internship', 'delete-roadshow', 'delete-talkshow', 'delete-internship', 'manage-internships','program/online-recruitment'
];

// Check if the page exists and is allowed
if (!in_array($page, $allowed_pages) || !file_exists("pages/{$page}.php")) {
    // Debug information
    error_log("404 Error - Page not found: " . $page);
    error_log("Requested URL: " . $_SERVER['REQUEST_URI']);
    error_log("GET parameters: " . print_r($_GET, true));
    
    $page = '404';
}

// Check if user is logged in for admin pages
$admin_pages = ['dashboard', 'add-announcement', 'edit-announcement', 'add-news', 'edit-news', 'manage-media', 'manage-site-settings', 'manage-users', 'manage-team-members', 'add-team-member', 'edit-team-member'];

if (in_array($page, $admin_pages) && !isLoggedIn()) {
  $_SESSION['message'] = "You must be logged in to access this page.";
  $_SESSION['message_type'] = "danger";
  header('Location: index.php?page=login');
  exit;
}

// Include header
include 'includes/header.php';

// Include the requested page
include "pages/{$page}.php";

// Include footer
include 'includes/footer.php';

// End output buffering and send all output
ob_end_flush();
?>
