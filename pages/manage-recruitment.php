<?php
// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    $_SESSION['message'] = "Access denied. Admin privileges required.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=login');
    exit;
}

// Handle actions
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'delete':
            if (isset($_GET['id'])) {
                $id = (int)$_GET['id'];
                try {
                    $stmt = $pdo->prepare("DELETE FROM job_postings WHERE id = ?");
                    if ($stmt->execute([$id])) {
                        $_SESSION['message'] = "Job posting deleted successfully.";
                        $_SESSION['message_type'] = "success";
                    } else {
                        $_SESSION['message'] = "Error deleting job posting.";
                        $_SESSION['message_type'] = "danger";
                    }
                } catch (PDOException $e) {
                    $_SESSION['message'] = "Database error: " . $e->getMessage();
                    $_SESSION['message_type'] = "danger";
                }
            }
            header('Location: index.php?page=manage-recruitment');
            exit;
            break;
            
        case 'toggle-featured':
            if (isset($_GET['id'])) {
                $id = (int)$_GET['id'];
                try {
                    $stmt = $pdo->prepare("UPDATE job_postings SET is_featured = NOT is_featured WHERE id = ?");
                    if ($stmt->execute([$id])) {
                        $_SESSION['message'] = "Job posting updated successfully.";
                        $_SESSION['message_type'] = "success";
                    }
                } catch (PDOException $e) {
                    $_SESSION['message'] = "Database error: " . $e->getMessage();
                    $_SESSION['message_type'] = "danger";
                }
            }
            header('Location: index.php?page=manage-recruitment');
            exit;
            break;
            
        case 'toggle-status':
            if (isset($_GET['id'])) {
                $id = (int)$_GET['id'];
                try {
                    $stmt = $pdo->prepare("UPDATE job_postings SET is_active = NOT is_active WHERE id = ?");
                    if ($stmt->execute([$id])) {
                        $_SESSION['message'] = "Job status updated successfully.";
                        $_SESSION['message_type'] = "success";
                    }
                } catch (PDOException $e) {
                    $_SESSION['message'] = "Database error: " . $e->getMessage();
                    $_SESSION['message_type'] = "danger";
                }
            }
            header('Location: index.php?page=manage-recruitment');
            exit;
            break;
    }
}

// Get all job postings
try {
    $stmt = $pdo->query("SELECT * FROM job_postings ORDER BY created_at DESC");
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $jobs = [];
    $error_message = "Error fetching job postings: " . $e->getMessage();
}

// Get recruitment statistics
try {
    $stats = [];
    $stmt = $pdo->query("SELECT COUNT(*) as total_jobs FROM job_postings");
    $stats['total_jobs'] = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) as active_jobs FROM job_postings WHERE is_active = 1");
    $stats['active_jobs'] = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) as featured_jobs FROM job_postings WHERE is_featured = 1");
    $stats['featured_jobs'] = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) as total_applications FROM job_applications");
    $stats['total_applications'] = $stmt->fetchColumn();
} catch (PDOException $e) {
    $stats = ['total_jobs' => 0, 'active_jobs' => 0, 'featured_jobs' => 0, 'total_applications' => 0];
}
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 text-danger">Recruitment Management</h1>
                <div>
                    <a href="index.php?page=add-job-posting" class="btn btn-danger me-2">
                        <i class="fas fa-plus me-1"></i> Add New Job
                    </a>
                    <a href="index.php?page=manage-applications" class="btn btn-outline-danger me-2">
                        <i class="fas fa-file-alt me-1"></i> View Applications
                    </a>
                    <a href="index.php?page=recruitment-settings" class="btn btn-outline-secondary">
                        <i class="fas fa-cog me-1"></i> Settings
                    </a>
                </div>
            </div>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Total Jobs</h6>
                                    <h2 class="mb-0"><?php echo $stats['total_jobs']; ?></h2>
                                </div>
                                <div class="bg-white rounded-circle p-3 text-primary">
                                    <i class="fas fa-briefcase fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Active Jobs</h6>
                                    <h2 class="mb-0"><?php echo $stats['active_jobs']; ?></h2>
                                </div>
                                <div class="bg-white rounded-circle p-3 text-success">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Featured Jobs</h6>
                                    <h2 class="mb-0"><?php echo $stats['featured_jobs']; ?></h2>
                                </div>
                                <div class="bg-white rounded-circle p-3 text-warning">
                                    <i class="fas fa-star fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Applications</h6>
                                    <h2 class="mb-0"><?php echo $stats['total_applications']; ?></h2>
                                </div>
                                <div class="bg-white rounded-circle p-3 text-info">
                                    <i class="fas fa-file-alt fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job Postings Table -->
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Job Postings</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php elseif (empty($jobs)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No job postings found. <a href="index.php?page=add-job-posting">Create your first job posting</a>.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Location</th>
                                        <th>Type</th>
                                        <th>Salary Range</th>
                                        <th>Status</th>
                                        <th>Featured</th>
                                        <th>Applications</th>
                                        <th>Posted</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($jobs as $job): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($job['title']); ?></strong>
                                                <br>
                                                <small class="text-muted"><?php echo htmlspecialchars($job['company_name']); ?></small>
                                            </td>
                                            <td><?php echo htmlspecialchars($job['location']); ?></td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    <?php echo ucfirst(str_replace('-', ' ', $job['job_type'])); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($job['salary_min'] && $job['salary_max']): ?>
                                                    $<?php echo number_format($job['salary_min']); ?> - $<?php echo number_format($job['salary_max']); ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Not specified</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($job['is_active']): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($job['is_featured']): ?>
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-star"></i> Featured
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">No</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-info"><?php echo $job['applications_count']; ?></span>
                                            </td>
                                            <td><?php echo formatDate($job['created_at']); ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="index.php?page=edit-job-posting&id=<?php echo $job['id']; ?>">
                                                                <i class="fas fa-edit me-1"></i> Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="index.php?page=view-job-applications&job_id=<?php echo $job['id']; ?>">
                                                                <i class="fas fa-file-alt me-1"></i> View Applications (<?php echo $job['applications_count']; ?>)
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a class="dropdown-item" href="?page=manage-recruitment&action=toggle-status&id=<?php echo $job['id']; ?>">
                                                                <i class="fas fa-toggle-<?php echo $job['is_active'] ? 'off' : 'on'; ?> me-1"></i>
                                                                <?php echo $job['is_active'] ? 'Deactivate' : 'Activate'; ?>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="?page=manage-recruitment&action=toggle-featured&id=<?php echo $job['id']; ?>">
                                                                <i class="fas fa-star me-1"></i>
                                                                <?php echo $job['is_featured'] ? 'Remove Featured' : 'Make Featured'; ?>
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="?page=manage-recruitment&action=delete&id=<?php echo $job['id']; ?>" 
                                                               onclick="return confirm('Are you sure you want to delete this job posting? This action cannot be undone.')">
                                                                <i class="fas fa-trash me-1"></i> Delete
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.badge {
    font-size: 0.75em;
}

.btn-group .dropdown-menu {
    min-width: 200px;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}
</style>
