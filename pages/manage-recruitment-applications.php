
<?php
// Prevent direct access
if (!defined('INCLUDED')) {
    define('INCLUDED', true);
}

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    header('Location: index.php?page=login');
    exit;
}

// Initialize variables for applications
$applications = [];
$total_applications = 0;
$page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? trim($_GET['status']) : '';
$job_filter = isset($_GET['job_id']) ? (int)$_GET['job_id'] : 0;
$date_from = isset($_GET['date_from']) ? trim($_GET['date_from']) : '';
$date_to = isset($_GET['date_to']) ? trim($_GET['date_to']) : '';

// Determine current view: 'applications' or 'postings'
$current_view = isset($_GET['view']) && $_GET['view'] === 'postings' ? 'postings' : 'applications';

// Handle status update for applications
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($current_view === 'applications') {
        $application_id = (int)($_POST['application_id'] ?? 0);
        $action = $_POST['action'];
        
        if ($application_id > 0) {
            try {
                if ($action === 'update_status') {
                    $new_status = $_POST['status'] ?? '';
                    $notes = trim($_POST['notes'] ?? '');
                    
                    $sql = "UPDATE job_applications SET status = :status, notes = :notes, updated_at = NOW() WHERE id = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':status' => $new_status,
                        ':notes' => $notes,
                        ':id' => $application_id
                    ]);
                    
                    $success_message = "Application status updated successfully!";
                } elseif ($action === 'delete_application') { // Changed action name to avoid conflict
                    // Get file paths before deletion
                    $stmt = $pdo->prepare("SELECT resume_path, cover_letter_path FROM job_applications WHERE id = :id");
                    $stmt->execute([':id' => $application_id]);
                    $app_files = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    // Delete from database
                    $stmt = $pdo->prepare("DELETE FROM job_applications WHERE id = :id");
                    $stmt->execute([':id' => $application_id]);
                    
                    // Delete files
                    if ($app_files) {
                        if (!empty($app_files['resume_path']) && file_exists($app_files['resume_path'])) {
                            unlink($app_files['resume_path']);
                        }
                        if (!empty($app_files['cover_letter_path']) && file_exists($app_files['cover_letter_path'])) {
                            unlink($app_files['cover_letter_path']);
                        }
                    }
                    
                    $success_message = "Application deleted successfully!";
                }
            } catch (PDOException $e) {
                $error_message = "Error: " . $e->getMessage();
            }
        }
    } elseif ($current_view === 'postings') {
        $job_posting_id = (int)($_POST['job_posting_id'] ?? 0);
        $action = $_POST['action'];

        if ($job_posting_id > 0) {
            try {
                if ($action === 'delete_job_posting') {
                    // The form now directly posts to actions/delete-job-posting.php,
                    // so no need to include it here. The action script handles redirection.
                    // The success/error message will be set in the session by the action script.
                }
            } catch (Exception $e) {
                $error_message = "Error: " . $e->getMessage();
            }
        }
    }
}

// Build query for fetching applications
if ($current_view === 'applications') {
    $where_conditions = [];
    $params = [];

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

    // Get total count for applications
    try {
        $count_sql = "SELECT COUNT(*) FROM job_applications ja 
                      LEFT JOIN popular_jobs pj ON ja.job_id = pj.id 
                      $where_clause";
        $count_stmt = $pdo->prepare($count_sql);
        $count_stmt->execute($params);
        $total_applications = $count_stmt->fetchColumn();
    } catch (PDOException $e) {
        $error_message = "Error fetching application count: " . $e->getMessage();
    }

    // Get applications
    try {
        $sql = "SELECT ja.*, pj.title as job_title, pj.company, pj.location 
                FROM job_applications ja 
                LEFT JOIN popular_jobs pj ON ja.job_id = pj.id 
                $where_clause 
                ORDER BY ja.application_date DESC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error_message = "Error fetching applications: " . $e->getMessage();
    }
}

// Get available jobs for filter (for both applications and postings)
$jobs = [];
try {
    $jobs_stmt = $pdo->query("SELECT id, title, company FROM popular_jobs WHERE is_active = 1 ORDER BY title");
    $jobs = $jobs_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle error silently
}

// Initialize variables for job postings
$job_postings = [];
$total_job_postings = 0;
$job_posting_page = isset($_GET['p_postings']) ? max(1, (int)$_GET['p_postings']) : 1;
$job_posting_limit = 10;
$job_posting_offset = ($job_posting_page - 1) * $job_posting_limit;
$job_posting_search = isset($_GET['search_postings']) ? trim($_GET['search_postings']) : '';
$job_posting_type_filter = isset($_GET['type_postings']) ? trim($_GET['type_postings']) : '';
$job_posting_location_filter = isset($_GET['location_postings']) ? trim($_GET['location_postings']) : '';

// Build query for fetching job postings
if ($current_view === 'postings') {
    $posting_where_conditions = [];
    $posting_params = [];

    if (!empty($job_posting_search)) {
        $posting_where_conditions[] = "(title LIKE :search_postings OR description LIKE :search_postings OR company_name LIKE :search_postings)";
        $posting_params[':search_postings'] = "%$job_posting_search%";
    }

    if (!empty($job_posting_type_filter)) {
        $posting_where_conditions[] = "job_type = :type_postings";
        $posting_params[':type_postings'] = $job_posting_type_filter;
    }

    if (!empty($job_posting_location_filter)) {
        $posting_where_conditions[] = "location LIKE :location_postings";
        $posting_params[':location_postings'] = "%$job_posting_location_filter%";
    }

    $posting_where_clause = !empty($posting_where_conditions) ? "WHERE " . implode(" AND ", $posting_where_conditions) : "";

    // Get total count for job postings
    try {
        $count_sql_postings = "SELECT COUNT(*) FROM job_postings $posting_where_clause";
        $count_stmt_postings = $pdo->prepare($count_sql_postings);
        $count_stmt_postings->execute($posting_params);
        $total_job_postings = $count_stmt_postings->fetchColumn();
    } catch (PDOException $e) {
        $error_message = "Error fetching job posting count: " . $e->getMessage();
    }

    // Get job postings
    try {
        $sql_postings = "SELECT * FROM job_postings $posting_where_clause ORDER BY created_at DESC LIMIT :limit_postings OFFSET :offset_postings";
        
        $stmt_postings = $pdo->prepare($sql_postings);
        foreach ($posting_params as $key => $value) {
            $stmt_postings->bindValue($key, $value);
        }
        $stmt_postings->bindValue(':limit_postings', $job_posting_limit, PDO::PARAM_INT);
        $stmt_postings->bindValue(':offset_postings', $job_posting_offset, PDO::PARAM_INT);
        $stmt_postings->execute();
        $job_postings = $stmt_postings->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error_message = "Error fetching job postings: " . $e->getMessage();
    }
}

// Calculate pagination for current view
$total_pages = ceil(($current_view === 'applications' ? $total_applications : $total_job_postings) / ($current_view === 'applications' ? $limit : $job_posting_limit));
$current_page = ($current_view === 'applications' ? $page : $job_posting_page);
$current_limit = ($current_view === 'applications' ? $limit : $job_posting_limit);
$current_offset = ($current_view === 'applications' ? $offset : $job_posting_offset);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Recruitment - MACA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="management-wrapper">
    <!-- Header Section -->
    <div class="management-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="header-content">
                        <h1 class="management-title">
                            <i class="fas fa-users me-3"></i>
                            Recruitment Management
                        </h1>
                        <p class="header-subtitle">Manage job applications and postings efficiently</p>
                        <div class="stats-overview">
                            <div class="stat-item">
                                <span class="stat-number"><?php echo $total_applications; ?></span>
                                <span class="stat-label">Total Applications</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">
                                    <?php 
                                    $pending_count = 0;
                                    foreach ($applications as $app) {
                                        if ($app['status'] === 'pending') $pending_count++;
                                    }
                                    echo $pending_count;
                                    ?>
                                </span>
                                <span class="stat-label">Pending Review</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number"><?php echo $total_job_postings; ?></span>
                                <span class="stat-label">Active Job Postings</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-end">
                    <div class="header-actions">
                        <a href="index.php?page=dashboard" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back to Dashboard
                        </a>
                        <a href="#" class="btn btn-light btn-lg ms-2" onclick="exportApplications(); return false;">
                            <i class="fas fa-download me-2"></i>
                            Export Applications
                        </a>
                       
                        <a href="index.php?page=add-job-opportunity" class="btn btn-success btn-lg ms-2">
                            <i class="fas fa-plus-circle me-2"></i>
                            Add Job 
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-5">
        <!-- Success/Error Messages -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo htmlspecialchars($success_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo htmlspecialchars($error_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link text-black <?php echo $current_view === 'applications' ? 'active' : ''; ?>" 
                   href="index.php?page=manage-recruitment-applications&view=applications">
                    <i class="fas fa-file-alt me-2"></i>Job Applications
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-black <?php echo $current_view === 'postings' ? 'active' : ''; ?>" 
                   href="index.php?page=manage-recruitment-applications&view=postings">
                    <i class="fas fa-briefcase me-2"></i>Job Postings
                </a>
            </li>
        </ul>

        <?php if ($current_view === 'applications'): ?>
            <!-- Filters Section for Applications -->
            <div class="filters-container">
                <div class="filters-header">
                    <h3><i class="fas fa-filter me-2"></i>Filter Applications</h3>
                    <button class="btn btn-outline-secondary" onclick="clearFilters('applications')">
                        <i class="fas fa-times me-2"></i>Clear Filters
                    </button>
                </div>
                
                <form method="GET" class="filters-form">
                    <input type="hidden" name="page" value="manage-recruitment-applications">
                    <input type="hidden" name="view" value="applications">
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="search" class="form-label">
                                    <i class="fas fa-search me-2"></i>Search
                                </label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="<?php echo htmlspecialchars($search); ?>" 
                                       placeholder="Name, email, phone...">
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="status" class="form-label">
                                    <i class="fas fa-flag me-2"></i>Status
                                </label>
                                <select class="form-control" id="status" name="status">
                                    <option value="">All Status</option>
                                    <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="reviewed" <?php echo $status_filter === 'reviewed' ? 'selected' : ''; ?>>Reviewed</option>
                                    <option value="shortlisted" <?php echo $status_filter === 'shortlisted' ? 'selected' : ''; ?>>Shortlisted</option>
                                    <option value="rejected" <?php echo $status_filter === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                    <option value="hired" <?php echo $status_filter === 'hired' ? 'selected' : ''; ?>>Hired</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="job_id" class="form-label">
                                    <i class="fas fa-briefcase me-2"></i>Job Position
                                </label>
                                <select class="form-control" id="job_id" name="job_id">
                                    <option value="">All Positions</option>
                                    <?php foreach ($jobs as $job): ?>
                                        <option value="<?php echo $job['id']; ?>" <?php echo $job_filter == $job['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($job['title']); ?>
                                            <?php if (!empty($job['company'])): ?>
                                                - <?php echo htmlspecialchars($job['company']); ?>
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="date_from" class="form-label">
                                    <i class="fas fa-calendar me-2"></i>From Date
                                </label>
                                <input type="date" class="form-control" id="date_from" name="date_from" 
                                       value="<?php echo htmlspecialchars($date_from); ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="date_to" class="form-label">To Date</label>
                                <input type="date" class="form-control" id="date_to" name="date_to" 
                                       value="<?php echo htmlspecialchars($date_to); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="filters-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <!-- Applications List -->
            <div class="applications-container">
                <?php if (empty($applications)): ?>
                    <div class="no-applications">
                        <div class="no-applications-icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <h3>No Applications Found</h3>
                        <p>No job applications match your current filters. Try adjusting your search criteria.</p>
                    </div>
                <?php else: ?>
                    <div class="applications-header">
                        <h3>
                            <i class="fas fa-list me-2"></i>
                            Applications (<?php echo $total_applications; ?> total)
                        </h3>
                        <div class="view-options">
                            <button class="btn btn-outline-secondary active" onclick="toggleView('card')">
                                <i class="fas fa-th-large"></i>
                            </button>
                            <button class="btn btn-outline-secondary" onclick="toggleView('table')">
                                <i class="fas fa-table"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Card View -->
                    <div class="applications-grid" id="cardView">
                        <?php foreach ($applications as $application): ?>
                            <div class="application-card">
                                <div class="card-header">
                                    <div class="applicant-info">
                                        <div class="applicant-avatar">
                                            <?php echo strtoupper(substr($application['full_name'], 0, 2)); ?>
                                        </div>
                                        <div class="applicant-details">
                                            <h4><?php echo htmlspecialchars($application['full_name']); ?></h4>
                                            <p class="applicant-email"><?php echo htmlspecialchars($application['email']); ?></p>
                                        </div>
                                    </div>
                                    <div class="application-status">
                                        <span class="status-badge status-<?php echo $application['status']; ?>">
                                            <?php echo ucfirst($application['status']); ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <div class="application-meta">
                                        <?php if (!empty($application['job_title'])): ?>
                                            <div class="meta-item">
                                                <i class="fas fa-briefcase"></i>
                                                <span><?php echo htmlspecialchars($application['job_title']); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="meta-item">
                                            <i class="fas fa-phone"></i>
                                            <span><?php echo htmlspecialchars($application['phone']); ?></span>
                                        </div>
                                        
                                        <div class="meta-item">
                                            <i class="fab fa-telegram"></i>
                                            <span><?php echo htmlspecialchars($application['telegram']); ?></span>
                                        </div>
                                        
                                        <div class="meta-item">
                                            <i class="fas fa-calendar"></i>
                                            <span><?php echo date('M d, Y', strtotime($application['application_date'])); ?></span>
                                        </div>
                                    </div>
                                    
                                    <?php if (!empty($application['portfolio_url'])): ?>
                                        <div class="portfolio-link">
                                            <a href="<?php echo htmlspecialchars($application['portfolio_url']); ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-globe me-2"></i>View Portfolio
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="card-footer">
                                    <div class="document-links">
                                        <?php if (!empty($application['resume_path'])): ?>
                                            <a href="<?php echo htmlspecialchars($application['resume_path']); ?>" target="_blank" class="doc-link">
                                                <i class="fas fa-file-pdf"></i>Resume
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($application['cover_letter_path'])): ?>
                                            <a href="<?php echo htmlspecialchars($application['cover_letter_path']); ?>" target="_blank" class="doc-link">
                                                <i class="fas fa-file-text"></i>Cover Letter
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="card-actions">
                                        <button class="btn btn-primary btn-sm" onclick="openStatusModal(<?php echo $application['id']; ?>, '<?php echo $application['status']; ?>', '<?php echo htmlspecialchars($application['notes'] ?? '', ENT_QUOTES); ?>')">
                                            <i class="fas fa-edit"></i>Update
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" onclick="deleteApplication(<?php echo $application['id']; ?>)">
                                            <i class="fas fa-trash"></i>Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Table View -->
                    <div class="applications-table" id="tableView" style="display: none;">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Applicant</th>
                                        <th>Position</th>
                                        <th>Contact</th>
                                        <th>Applied Date</th>
                                        <th>Status</th>
                                        <th>Documents</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($applications as $application): ?>
                                        <tr>
                                            <td>
                                                <div class="applicant-cell">
                                                    <div class="applicant-avatar-sm">
                                                        <?php echo strtoupper(substr($application['full_name'], 0, 2)); ?>
                                                    </div>
                                                    <div>
                                                        <strong><?php echo htmlspecialchars($application['full_name']); ?></strong>
                                                        <br><small class="text-muted"><?php echo htmlspecialchars($application['email']); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if (!empty($application['job_title'])): ?>
                                                    <strong><?php echo htmlspecialchars($application['job_title']); ?></strong>
                                                    <?php if (!empty($application['company'])): ?>
                                                        <br><small class="text-muted"><?php echo htmlspecialchars($application['company']); ?></small>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="contact-info">
                                                    <div><i class="fas fa-phone me-1"></i><?php echo htmlspecialchars($application['phone']); ?></div>
                                                    <div><i class="fab fa-telegram me-1"></i><?php echo htmlspecialchars($application['telegram']); ?></div>
                                                </div>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($application['application_date'])); ?></td>
                                            <td>
                                                <span class="status-badge status-<?php echo $application['status']; ?>">
                                                    <?php echo ucfirst($application['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="document-links-table">
                                                    <?php if (!empty($application['resume_path'])): ?>
                                                        <a href="<?php echo htmlspecialchars($application['resume_path']); ?>" target="_blank" class="doc-link-sm">
                                                            <i class="fas fa-file-pdf"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!empty($application['cover_letter_path'])): ?>
                                                        <a href="<?php echo htmlspecialchars($application['cover_letter_path']); ?>" target="_blank" class="doc-link-sm">
                                                            <i class="fas fa-file-text"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!empty($application['portfolio_url'])): ?>
                                                        <a href="<?php echo htmlspecialchars($application['portfolio_url']); ?>" target="_blank" class="doc-link-sm">
                                                            <i class="fas fa-globe"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="table-actions">
                                                    <button class="btn btn-primary btn-sm" onclick="openStatusModal(<?php echo $application['id']; ?>, '<?php echo $application['status']; ?>', '<?php echo htmlspecialchars($application['notes'] ?? '', ENT_QUOTES); ?>')">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-outline-danger btn-sm" onclick="deleteApplication(<?php echo $application['id']; ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination for Applications -->
                    <?php if ($total_pages > 1): ?>
                        <div class="pagination-container">
                            <nav aria-label="Applications pagination">
                                <ul class="pagination justify-content-center">
                                    <?php if ($current_page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=manage-recruitment-applications&view=applications&p=<?php echo $current_page - 1; ?>&<?php echo http_build_query(array_filter(['search' => $search, 'status' => $status_filter, 'job_id' => $job_filter, 'date_from' => $date_from, 'date_to' => $date_to])); ?>">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++): ?>
                                        <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                                            <a class="page-link" href="?page=manage-recruitment-applications&view=applications&p=<?php echo $i; ?>&<?php echo http_build_query(array_filter(['search' => $search, 'status' => $status_filter, 'job_id' => $job_filter, 'date_from' => $date_from, 'date_to' => $date_to])); ?>">
                                                <?php echo $i; ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if ($current_page < $total_pages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=manage-recruitment-applications&view=applications&p=<?php echo $current_page + 1; ?>&<?php echo http_build_query(array_filter(['search' => $search, 'status' => $status_filter, 'job_id' => $job_filter, 'date_from' => $date_from, 'date_to' => $date_to])); ?>">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                            
                            <div class="pagination-info">
                                Showing <?php echo $current_offset + 1; ?> to <?php echo min($current_offset + $current_limit, $total_applications); ?> of <?php echo $total_applications; ?> applications
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php elseif ($current_view === 'postings'): ?>
            <!-- Filters Section for Job Postings -->
            <div class="filters-container">
                <div class="filters-header">
                    <h3><i class="fas fa-filter me-2"></i>Filter Job Postings</h3>
                    <button class="btn btn-outline-secondary" onclick="clearFilters('postings')">
                        <i class="fas fa-times me-2"></i>Clear Filters
                    </button>
                </div>
                
                <form method="GET" class="filters-form">
                    <input type="hidden" name="page" value="manage-recruitment-applications">
                    <input type="hidden" name="view" value="postings">
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="search_postings" class="form-label">
                                    <i class="fas fa-search me-2"></i>Search
                                </label>
                                <input type="text" class="form-control" id="search_postings" name="search_postings" 
                                       value="<?php echo htmlspecialchars($job_posting_search); ?>" 
                                       placeholder="Job title, company, description...">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="type_postings" class="form-label">
                                    <i class="fas fa-clock me-2"></i>Job Type
                                </label>
                                <select class="form-control" id="type_postings" name="type_postings">
                                    <option value="">All Types</option>
                                    <option value="full-time" <?php echo $job_posting_type_filter === 'full-time' ? 'selected' : ''; ?>>Full-time</option>
                                    <option value="part-time" <?php echo $job_posting_type_filter === 'part-time' ? 'selected' : ''; ?>>Part-time</option>
                                    <option value="contract" <?php echo $job_posting_type_filter === 'contract' ? 'selected' : ''; ?>>Contract</option>
                                    <option value="internship" <?php echo $job_posting_type_filter === 'internship' ? 'selected' : ''; ?>>Internship</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="location_postings" class="form-label">
                                    <i class="fas fa-map-marker-alt me-2"></i>Location
                                </label>
                                <input type="text" class="form-control" id="location_postings" name="location_postings" 
                                       value="<?php echo htmlspecialchars($job_posting_location_filter); ?>" 
                                       placeholder="City, Country...">
                            </div>
                        </div>
                    </div>
                    
                    <div class="filters-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <!-- Job Postings List -->
            <div class="applications-container">
                <?php if (empty($job_postings)): ?>
                    <div class="no-applications">
                        <div class="no-applications-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <h3>No Job Postings Found</h3>
                        <p>No job postings match your current filters. Try adjusting your search criteria or add a new job posting.</p>
                        <a href="index.php?page=add-job-opportunity" class="btn btn-success mt-3">
                            <i class="fas fa-plus-circle me-2"></i>Add New Job Posting
                        </a>
                    </div>
                <?php else: ?>
                    <div class="applications-header">
                        <h3>
                            <i class="fas fa-list me-2"></i>
                            Job Postings (<?php echo $total_job_postings; ?> total)
                        </h3>
                        <div class="view-options">
                            <!-- No card/table view toggle for postings yet, can add later if needed -->
                        </div>
                    </div>

                    <div class="applications-table">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Company</th>
                                        <th>Location</th>
                                        <th>Job Type</th>
                                        <th>Posted Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($job_postings as $posting): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($posting['title']); ?></strong>
                                                <?php if ($posting['is_featured']): ?>
                                                    <span class="badge bg-warning text-dark ms-2">Featured</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($posting['company_name']); ?></td>
                                            <td><?php echo htmlspecialchars($posting['location']); ?></td>
                                            <td>
                                                <span class="status-badge job-type-<?php echo $posting['job_type']; ?>">
                                                    <?php echo ucfirst(str_replace('-', ' ', $posting['job_type'])); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($posting['created_at'])); ?></td>
                                            <td>
                                                <div class="table-actions">
                                                    <a href="index.php?page=edit-job-posting&id=<?php echo $posting['id']; ?>" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <button class="btn btn-outline-danger btn-sm" onclick="deleteJobPosting(<?php echo $posting['id']; ?>)">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination for Job Postings -->
                    <?php if ($total_pages > 1): ?>
                        <div class="pagination-container">
                            <nav aria-label="Job Postings pagination">
                                <ul class="pagination justify-content-center">
                                    <?php if ($current_page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=manage-recruitment-applications&view=postings&p_postings=<?php echo $current_page - 1; ?>&<?php echo http_build_query(array_filter(['search_postings' => $job_posting_search, 'type_postings' => $job_posting_type_filter, 'location_postings' => $job_posting_location_filter])); ?>">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++): ?>
                                        <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                                            <a class="page-link" href="?page=manage-recruitment-applications&view=postings&p_postings=<?php echo $i; ?>&<?php echo http_build_query(array_filter(['search_postings' => $job_posting_search, 'type_postings' => $job_posting_type_filter, 'location_postings' => $job_posting_location_filter])); ?>">
                                                <?php echo $i; ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if ($current_page < $total_pages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=manage-recruitment-applications&view=postings&p_postings=<?php echo $current_page + 1; ?>&<?php echo http_build_query(array_filter(['search_postings' => $job_posting_search, 'type_postings' => $job_posting_type_filter, 'location_postings' => $job_posting_location_filter])); ?>">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                            
                            <div class="pagination-info">
                                Showing <?php echo $current_offset + 1; ?> to <?php echo min($current_offset + $current_limit, $total_job_postings); ?> of <?php echo $total_job_postings; ?> job postings
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Status Update Modal (for applications) -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">
                    <i class="fas fa-edit me-2"></i>Update Application Status
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="statusForm">
                <div class="modal-body">
                    <input type="hidden" name="action" value="update_status">
                    <input type="hidden" name="application_id" id="modalApplicationId">
                    <input type="hidden" name="view" value="applications">
                    
                    <div class="form-group mb-3">
                        <label for="modalStatus" class="form-label">Status</label>
                        <select class="form-control" id="modalStatus" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="reviewed">Reviewed</option>
                            <option value="shortlisted">Shortlisted</option>
                            <option value="rejected">Rejected</option>
                            <option value="hired">Hired</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="modalNotes" class="form-label">Notes</label>
                        <textarea class="form-control" id="modalNotes" name="notes" rows="4" 
                                  placeholder="Add any notes about this application..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal (for applications) -->
<div class="modal fade" id="deleteApplicationModal" tabindex="-1" aria-labelledby="deleteApplicationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteApplicationModalLabel">
                    <i class="fas fa-trash me-2"></i>Delete Application
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this application? This action cannot be undone.</p>
                <p class="text-danger"><strong>Warning:</strong> This will also delete all associated files.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="delete_application">
                    <input type="hidden" name="application_id" id="deleteApplicationId">
                    <input type="hidden" name="view" value="applications">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Delete Application
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Job Posting Confirmation Modal -->
<div class="modal fade" id="deleteJobPostingModal" tabindex="-1" aria-labelledby="deleteJobPostingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteJobPostingModalLabel">
                    <i class="fas fa-trash me-2"></i>Delete Job Posting
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this job posting? This action cannot be undone.</p>
                <p class="text-danger"><strong>Warning:</strong> This will permanently remove the job from the online recruitment page.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" style="display: inline;" action="index.php?action=delete-job-posting">
                    <input type="hidden" name="id" id="deleteJobPostingId">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Delete Job Posting
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
* {
    font-family: 'Inter', sans-serif;
    box-sizing: border-box;
}

body {
    margin: 0;
    padding: 0;
    background: #f8fafc;
}

.management-wrapper {
    min-height: 100vh;
}

/* Header Styles */
.management-header {
    border-radius: 18px;

    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    padding: 3rem 3rem 2rem;  
    position: relative;
    overflow: hidden;
    z-index: 100; /* Even higher z-index for the entire header */
}

.management-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.header-content {
    position: relative;
    z-index: 2;
}

.management-title {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
}

.header-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 2rem;
}

.stats-overview {
    display: flex;
    gap: 2rem;
}

.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    padding: 1rem 1.5rem;
    border-radius: 15px;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.8;
}

.header-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
    position: relative; /* Ensure z-index takes effect */
    z-index: 10; /* Bring buttons to the front */
}

/* Filters Section */
.filters-container {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}

.filters-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.filters-header h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}

.filters-form .form-group {
    margin-bottom: 1rem;
}

.form-label {
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    font-size: 0.9rem;
}

.form-control {
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background-color: #f9fafb;
}

.form-control:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
    background-color: white;
    outline: none;
}

.filters-actions {
    margin-top: 1.5rem;
    text-align: center;
}

/* Applications Container */
.applications-container {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}

.applications-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.applications-header h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}

.view-options {
    display: flex;
    gap: 0.5rem;
}

.view-options .btn {
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
}

.view-options .btn.active {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

/* Card View */
.applications-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 1.5rem;
}

.application-card {
    border: 2px solid #f1f5f9;
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
    background: white;
}

.application-card:hover {
    border-color: #dc3545;
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(220, 53, 69, 0.1);
}

.card-header {
    padding: 1.5rem;
    background: #f8fafc;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.applicant-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.applicant-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
}

.applicant-details h4 {
    font-size: 1.2rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.applicant-email {
    color: #64748b;
    margin: 0;
    font-size: 0.9rem;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-reviewed {
    background: #dbeafe;
    color: #1e40af;
}

.status-shortlisted {
    background: #d1fae5;
    color: #065f46;
}

.status-rejected {
    background: #fee2e2;
    color: #dc2626;
}

.status-hired {
    background: #dcfce7;
    color: #166534;
}

.card-body {
    padding: 1.5rem;
}

.application-meta {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: #64748b;
}

.meta-item i {
    color: #dc3545;
    width: 16px;
}

.portfolio-link {
    margin-top: 1rem;
}

.card-footer {
    padding: 1rem 1.5rem;
    background: #f8fafc;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.document-links {
    display: flex;
    gap: 0.75rem;
}

.doc-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    background: #e5e7eb;
    color: #374151;
    text-decoration: none;
    border-radius: 8px;
    font-size: 0.85rem;
    transition: all 0.3s ease;
}

.doc-link:hover {
    background: #dc3545;
    color: white;
}

.card-actions {
    display: flex;
    gap: 0.5rem;
}

/* Table View */
.applications-table {
    overflow-x: auto;
}

.table {
    margin-bottom: 0;
}

.table th {
    background: #f8fafc;
    border-bottom: 2px solid #e5e7eb;
    font-weight: 600;
    color: #374151;
    padding: 1rem 0.75rem;
}

.table td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
}

.applicant-cell {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.applicant-avatar-sm {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.contact-info div {
    font-size: 0.85rem;
    color: #64748b;
    margin-bottom: 0.25rem;
}

.document-links-table {
    display: flex;
    gap: 0.5rem;
}

.doc-link-sm {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: #e5e7eb;
    color: #374151;
    text-decoration: none;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.doc-link-sm:hover {
    background: #dc3545;
    color: white;
}

.table-actions {
    display: flex;
    gap: 0.5rem;
}

/* No Applications */
.no-applications {
    text-align: center;
    padding: 4rem 2rem;
}

.no-applications-icon {
    font-size: 4rem;
    color: #cbd5e1;
    margin-bottom: 1.5rem;
}

.no-applications h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 0.75rem;
}

.no-applications p {
    color: #94a3b8;
    max-width: 400px;
    margin: 0 auto;
}

/* Pagination */
.pagination-container {
    margin-top: 2rem;
    text-align: center;
}

.pagination .page-link {
    color: #dc3545;
    border-color: #e5e7eb;
    padding: 0.75rem 1rem;
}

.pagination .page-item.active .page-link {
    background-color: #dc3545;
    border-color: #dc3545;
}

.pagination .page-link:hover {
    color: #fff;
    background-color: #dc3545;
    border-color: #dc3545;
}

.pagination-info {
    margin-top: 1rem;
    color: #64748b;
    font-size: 0.9rem;
}

/* Buttons */
.btn-primary {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border: none;
    border-radius: 10px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
}

.btn-outline-secondary {
    border: 2px solid #e5e7eb;
    color: #6b7280;
    border-radius: 10px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
}

.btn-outline-secondary:hover {
    background: #f9fafb;
    border-color: #d1d5db;
    color: #374151;
}

.btn-outline-danger {
    border: 2px solid #fecaca;
    color: #dc2626;
    border-radius: 8px;
}

.btn-outline-danger:hover {
    background: #dc2626;
    border-color: #dc2626;
    color: white;
}

/* Modal Styles */
.modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.modal-header {
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
    border-radius: 15px 15px 0 0;
    padding: 1.5rem;
}

.modal-title {
    font-weight: 600;
    color: #1e293b;
}

.modal-body {
    padding: 2rem;
}

.modal-footer {
    background: #f8fafc;
    border-top: 1px solid #e5e7eb;
    border-radius: 0 0 15px 15px;
    padding: 1.5rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .management-title {
        font-size: 2rem;
    }
    
    .stats-overview {
        flex-direction: column;
        gap: 1rem;
    }
    
    .header-actions {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .filters-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .applications-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .applications-grid {
        grid-template-columns: 1fr;
    }
    
    .application-meta {
        grid-template-columns: 1fr;
    }
    
    .card-footer {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
}

@media (max-width: 480px) {
    .management-header {
        padding: 2rem 0;
    }
    
    .container {
        padding: 0 1rem;
    }
    
    .filters-container,
    .applications-container {
        padding: 1.5rem;
    }
}

/* Job Type Badges for Postings */
.status-badge.job-type-full-time { background: #e3f2fd; color: #1976d2; }
.status-badge.job-type-part-time { background: #f3e5f5; color: #7b1fa2; }
.status-badge.job-type-contract { background: #fff3e0; color: #f57c00; }
.status-badge.job-type-internship { background: #e8f5e8; color: #388e3c; }

</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Toggle between card and table view (for applications)
function toggleView(viewType) {
    const cardView = document.getElementById('cardView');
    const tableView = document.getElementById('tableView');
    const buttons = document.querySelectorAll('.view-options .btn');
    
    buttons.forEach(btn => btn.classList.remove('active'));
    
    if (viewType === 'card') {
        cardView.style.display = 'grid';
        tableView.style.display = 'none';
        buttons[0].classList.add('active');
    } else {
        cardView.style.display = 'none';
        tableView.style.display = 'block';
        buttons[1].classList.add('active');
    }
}

// Open status update modal (for applications)
function openStatusModal(applicationId, currentStatus, currentNotes) {
    document.getElementById('modalApplicationId').value = applicationId;
    document.getElementById('modalStatus').value = currentStatus;
    document.getElementById('modalNotes').value = currentNotes;
    
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
}

// Delete application
function deleteApplication(applicationId) {
    document.getElementById('deleteApplicationId').value = applicationId;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteApplicationModal'));
    modal.show();
}

// Delete job posting
function deleteJobPosting(jobPostingId) {
    document.getElementById('deleteJobPostingId').value = jobPostingId;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteJobPostingModal'));
    modal.show();
}

// Clear all filters
function clearFilters(view) {
    if (view === 'applications') {
        window.location.href = 'index.php?page=manage-recruitment-applications&view=applications';
    } else if (view === 'postings') {
        window.location.href = 'index.php?page=manage-recruitment-applications&view=postings';
    }
}

// Export applications (placeholder function)
function exportApplications() {
    // This would typically generate a CSV or Excel file
    alert('Export functionality would be implemented here. This would generate a CSV/Excel file with all application data.');
}

// Auto-submit form on filter change
document.addEventListener('DOMContentLoaded', function() {
    const filterForms = document.querySelectorAll('.filters-form');
    
    filterForms.forEach(form => {
        const filterInputs = form.querySelectorAll('select, input[type="date"]');
        
        filterInputs.forEach(input => {
            input.addEventListener('change', function() {
                // Auto-submit form when filters change (except search input)
                if (this.type !== 'text') {
                    this.form.submit();
                }
            });
        });
        
        // Submit search on Enter key
        const searchInput = form.querySelector('input[type="text"]');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    this.form.submit();
                }
            });
        }
    });
});
</script>

</body>
</html>
