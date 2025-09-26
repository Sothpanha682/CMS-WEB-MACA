<?php
session_start(); // Start the session to access user login status

// Prevent direct access
if (!defined('INCLUDED')) {
    define('INCLUDED', true);
}

require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../index.php?page=login');
    exit;
}

// Set headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="job_applications_' . date('Y-m-d_H-i-s') . '.csv"');

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Output the column headings
fputcsv($output, [
    'Application ID', 'Full Name', 'Email', 'Phone', 'Telegram', 'Job Title', 'Company', 'Location',
    'Application Date', 'Status', 'Notes', 'Resume Path', 'Cover Letter Path', 'Portfolio URL', 'Created At', 'Updated At'
]);

// Build query for fetching applications based on filters
$where_conditions = [];
$params = [];

// Get filter parameters from GET request
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? trim($_GET['status']) : '';
$job_filter = isset($_GET['job_id']) ? (int)$_GET['job_id'] : 0;
$date_from = isset($_GET['date_from']) ? trim($_GET['date_from']) : '';
$date_to = isset($_GET['date_to']) ? trim($_GET['date_to']) : '';

if (!empty($search)) {
    $where_conditions[] = "(full_name LIKE :search OR email LIKE :search OR phone LIKE :search OR telegram LIKE :search)";
    $params[':search'] = "%$search%";
}

if (!empty($status_filter)) {
    $where_conditions[] = "status = :status";
    $params[':status'] = $status_filter;
}

if ($job_filter > 0) {
    $where_conditions[] = "job_id = :job_id";
    $params[':job_id'] = $job_filter;
}

if (!empty($date_from)) {
    $where_conditions[] = "DATE(application_date) >= :date_from";
    $params[':date_from'] = $date_from;
}

if (!empty($date_to)) {
    $where_conditions[] = "DATE(application_date) <= :date_to";
    $params[':date_to'] = $date_to;
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

try {
    $sql = "SELECT ja.*, pj.title as job_title, pj.company, pj.location 
            FROM job_applications ja 
            LEFT JOIN popular_jobs pj ON ja.job_id = pj.id 
            $where_clause 
            ORDER BY ja.application_date DESC";
    
    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();

    // Fetch all applications and output them
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, [
            $row['id'],
            $row['full_name'],
            $row['email'],
            $row['phone'],
            $row['telegram'],
            $row['job_title'],
            $row['company'],
            $row['location'],
            $row['application_date'],
            $row['status'],
            $row['notes'],
            $row['resume_path'],
            $row['cover_letter_path'],
            $row['portfolio_url'],
            $row['created_at'],
            $row['updated_at']
        ]);
    }
} catch (PDOException $e) {
    // Log error and output a message to the CSV
    error_log("Error exporting applications: " . $e->getMessage());
    fputcsv($output, ['Error', 'Failed to export data: ' . $e->getMessage()]);
}

fclose($output);
exit;
?>
