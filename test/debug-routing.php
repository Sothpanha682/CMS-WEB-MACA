<?php
// Debug the routing system to see what's happening
echo "<h1>Routing Debug</h1>";

echo "<h2>Current Request Info</h2>";
echo "<p><strong>REQUEST_URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p><strong>GET Parameters:</strong></p>";
echo "<pre>" . print_r($_GET, true) . "</pre>";

// Test the routing logic
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
echo "<p><strong>Page parameter:</strong> " . htmlspecialchars($page) . "</p>";

// Sanitize the page name (same as index.php)
$original_page = $page;
$page = preg_replace('/[^a-zA-Z0-9_\/\-]/', '', $page);
echo "<p><strong>Sanitized page:</strong> " . htmlspecialchars($page) . "</p>";

if ($original_page !== $page) {
    echo "<p><strong>⚠ Page was modified during sanitization!</strong></p>";
}

// Check allowed pages
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
    'program/online-learning', 'program/career-counselling',
    'manage-talkshow', 'add-talkshow', 'edit-talkshow',
    'manage-roadshow', 'add-roadshow', 'edit-roadshow',
    'program/talkshow', 'program/roadshow',
    'talkshow-detail', 'roadshow-detail',
    'popular-majors', 'popular-jobs',
    '404'
];

echo "<p><strong>Is page in allowed list:</strong> " . (in_array($page, $allowed_pages) ? 'YES' : 'NO') . "</p>";

// Check if file exists
$file_path = "pages/{$page}.php";
echo "<p><strong>File path:</strong> " . $file_path . "</p>";
echo "<p><strong>File exists:</strong> " . (file_exists($file_path) ? 'YES' : 'NO') . "</p>";

// Final routing decision
if (!in_array($page, $allowed_pages) || !file_exists($file_path)) {
    echo "<p><strong>🚨 ROUTING RESULT: 404 ERROR</strong></p>";
    echo "<p>This explains why you're getting a 404 error!</p>";
} else {
    echo "<p><strong>✅ ROUTING RESULT: Page should load normally</strong></p>";
}

echo "<h2>Test URLs</h2>";
echo "<ul>";
echo "<li><a href='index.php?page=program/talkshow'>Normal talkshow page</a></li>";
echo "<li><a href='index.php?page=program/talkshow&search=test'>Talkshow page with search</a></li>";
echo "<li><a href='direct-talkshow-search.php'>Direct search page (bypass routing)</a></li>";
echo "</ul>";
?>
