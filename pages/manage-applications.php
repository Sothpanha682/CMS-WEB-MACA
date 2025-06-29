<?php
require_once '../includes/functions.php';
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
        case 'update-status':
            if (isset($_GET['id']) && isset($_GET['status'])) {
                $id = (int)$_GET['id'];
                $status = sanitize($_GET['status']);
                $allowed_statuses = ['pending', 'reviewed', 'shortlisted', 'interviewed', 'hired', 'rejected'];
                
                if (in_array($status, $allowed_statuses)) {
                    try {
                        $stmt = $pdo->prepare("UPDATE job_applications SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                        if ($stmt->execute([$status, $id])) {
                            $_SESSION['message'] = "Application status updated successfully.";
                            $_SESSION['message_type'] = "success";
                        }
                    } catch (PDOException $e) {
                        $_SESSION['message'] = "Database error: " . $e->getMessage();
                        $_SESSION['message_type'] = "danger";
                    }
                }
            }
            header('Location: index.php?page=manage-applications');
            exit;
            break;
            
        case 'delete':
            if (isset($_GET['id'])) {
                $id = (int)$_GET['id'];
                try {
                    // Get resume path before deleting
                    $stmt = $pdo->prepare("SELECT resume_path FROM job_applications WHERE id = ?");
                    $stmt->execute([$id]);
                    $application = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    // Delete the application
                    $stmt = $pdo->prepare("DELETE FROM job_applications WHERE id = ?");
                    if ($stmt->execute([$id])) {
                        // Delete resume file if exists
                        if ($application && $application['resume_path'] && file_exists($application['resume_path'])) {
                            unlink($application['resume_path']);
                        }
                        $_SESSION['message'] = "Application deleted successfully.";
                        $_SESSION['message_type'] = "success";
                    }
                } catch (PDOException $e) {
                    $_SESSION['message'] = "Database error: " . $e->getMessage();
                    $_SESSION['message_type'] = "danger";
                }
            }
            header('Location: index.php?page=manage-applications');
            exit;
            break;
    }
}

// Get filter parameters
$job_filter = isset($_GET['job_id']) ? (int)$_GET['job_id'] : 0;
$status_filter = isset($_GET['status']) ? sanitize($_GET['status']) : '';

// Build query
$where_conditions = [];
$params = [];

if ($job_filter > 0) {
    $where_conditions[] = "ja.job_id = ?";
    $params[] = $job_filter;
}

if (!empty($status_filter)) {
    $where_conditions[] = "ja.status = ?";
    $params[] = $status_filter;
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

// Get applications with job details
try {
    $query = "
        SELECT ja.*, jp.title as job_title, jp.company_name 
        FROM job_applications ja 
        JOIN job_postings jp ON ja.job_id = jp.id 
        $where_clause 
        ORDER BY ja.created_at DESC
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $applications = [];
    $error_message = "Error fetching applications: " . $e->getMessage();
}

// Get all jobs for filter dropdown
try {
    $stmt = $pdo->query("SELECT id, title FROM job_postings ORDER BY title");
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $jobs = [];
}

// Get application statistics
try {
    $stats = [];
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM job_applications");
    $stats['total'] = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) as pending FROM job_applications WHERE status = 'pending'");
    $stats['pending'] = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) as reviewed FROM job_applications WHERE status = 'reviewed'");
    $stats['reviewed'] = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) as hired FROM job_applications WHERE status = 'hired'");
    $stats['hired'] = $stmt->fetchColumn();
} catch (PDOException $e) {
    $stats = ['total' => 0, 'pending' => 0, 'reviewed' => 0, 'hired' => 0];
}
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 text-danger">Job Applications</h1>
                <a href="index.php?page=manage-recruitment" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Recruitment
                </a>
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
                                    <h6 class="mb-0">Total Applications</h6>
                                    <h2 class="mb-0"><?php echo $stats['total']; ?></h2>
                                </div>
                                <div class="bg-white rounded-circle p-3 text-primary">
                                    <i class="fas fa-file-alt fa-2x"></i>
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
                                    <h6 class="mb-0">Pending Review</h6>
                                    <h2 class="mb-0"><?php echo $stats['pending']; ?></h2>
                                </div>
                                <div class="bg-white rounded-circle p-3 text-warning">
                                    <i class="fas fa-clock fa-2x"></i>
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
                                    <h6 class="mb-0">Under Review</h6>
                                    <h2 class="mb-0"><?php echo $stats['reviewed']; ?></h2>
                                </div>
                                <div class="bg-white rounded-circle p-3 text-info">
                                    <i class="fas fa-eye fa-2x"></i>
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
                                    <h6 class="mb-0">Hired</h6>
                                    <h2 class="mb-0"><?php echo $stats['hired']; ?></h2>
                                </div>
                                <div class="bg-white rounded-circle p-3 text-success">
                                    <i class="fas fa-user-check fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="" class="row g-3">
                        <input type="hidden" name="page" value="manage-applications">
                        <div class="col-md-4">
                            <label for="job_id" class="form-label">Filter by Job</label>
                            <select class="form-select" id="job_id" name="job_id">
                                <option value="">All Jobs</option>
                                <?php foreach ($jobs as $job): ?>
                                    <option value="<?php echo $job['id']; ?>" <?php echo ($job_filter == $job['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($job['title']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label">Filter by Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Statuses</option>
                                <option value="pending" <?php echo ($status_filter == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="reviewed" <?php echo ($status_filter == 'reviewed') ? 'selected' : ''; ?>>Reviewed</option>
                                <option value="shortlisted" <?php echo ($status_filter == 'shortlisted') ? 'selected' : ''; ?>>Shortlisted</option>
                                <option value="interviewed" <?php echo ($status_filter == 'interviewed') ? 'selected' : ''; ?>>Interviewed</option>
                                <option value="hired" <?php echo ($status_filter == 'hired') ? 'selected' : ''; ?>>Hired</option>
                                <option value="rejected" <?php echo ($status_filter == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-danger me-2">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="index.php?page=manage-applications" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Applications Table -->
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Job Applications</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php elseif (empty($applications)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No applications found with the current filters.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Applicant</th>
                                        <th>Job Position</th>
                                        <th>Experience</th>
                                        <th>Expected Salary</th>
                                        <th>Status</th>
                                        <th>Applied Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($applications as $app): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($app['applicant_name']); ?></strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-envelope me-1"></i><?php echo htmlspecialchars($app['applicant_email']); ?>
                                                    <?php if ($app['applicant_phone']): ?>
                                                        <br><i class="fas fa-phone me-1"></i><?php echo htmlspecialchars($app['applicant_phone']); ?>
                                                    <?php endif; ?>
                                                </small>
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($app['job_title']); ?></strong>
                                                <br>
                                                <small class="text-muted"><?php echo htmlspecialchars($app['company_name']); ?></small>
                                            </td>
                                            <td>
                                                <?php if ($app['experience_years']): ?>
                                                    <?php echo $app['experience_years']; ?> years
                                                <?php else: ?>
                                                    <span class="text-muted">Not specified</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($app['expected_salary']): ?>
                                                    $<?php echo number_format($app['expected_salary']); ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Not specified</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                $status_classes = [
                                                    'pending' => 'bg-warning text-dark',
                                                    'reviewed' => 'bg-info',
                                                    'shortlisted' => 'bg-primary',
                                                    'interviewed' => 'bg-secondary',
                                                    'hired' => 'bg-success',
                                                    'rejected' => 'bg-danger'
                                                ];
                                                $class = $status_classes[$app['status']] ?? 'bg-secondary';
                                                ?>
                                                <span class="badge <?php echo $class; ?>">
                                                    <?php echo ucfirst($app['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo formatDate($app['created_at']); ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                                            data-bs-toggle="modal" data-bs-target="#applicationModal<?php echo $app['id']; ?>">
                                                        <i class="fas fa-eye"></i> View
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle dropdown-toggle-split" 
                                                            data-bs-toggle="dropdown">
                                                        <span class="visually-hidden">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><h6 class="dropdown-header">Update Status</h6></li>
                                                        <li><a class="dropdown-item" href="?page=manage-applications&action=update-status&id=<?php echo $app['id']; ?>&status=pending">Pending</a></li>
                                                        <li><a class="dropdown-item" href="?page=manage-applications&action=update-status&id=<?php echo $app['id']; ?>&status=reviewed">Reviewed</a></li>
                                                        <li><a class="dropdown-item" href="?page=manage-applications&action=update-status&id=<?php echo $app['id']; ?>&status=shortlisted">Shortlisted</a></li>
                                                        <li><a class="dropdown-item" href="?page=manage-applications&action=update-status&id=<?php echo $app['id']; ?>&status=interviewed">Interviewed</a></li>
                                                        <li><a class="dropdown-item" href="?page=manage-applications&action=update-status&id=<?php echo $app['id']; ?>&status=hired">Hired</a></li>
                                                        <li><a class="dropdown-item" href="?page=manage-applications&action=update-status&id=<?php echo $app['id']; ?>&status=rejected">Rejected</a></li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li><a class="dropdown-item text-danger" href="?page=manage-applications&action=delete&id=<?php echo $app['id']; ?>" 
                                                               onclick="return confirm('Are you sure you want to delete this application?')">Delete</a></li>
                                                    </ul>
                                                </div>

                                                <!-- Application Detail Modal -->
                                                <div class="modal fade" id="applicationModal<?php echo $app['id']; ?>" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Application Details</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <h6>Applicant Information</h6>
                                                                        <p><strong>Name:</strong> <?php echo htmlspecialchars($app['applicant_name']); ?></p>
                                                                        <p><strong>Email:</strong> <?php echo htmlspecialchars($app['applicant_email']); ?></p>
                                                                        <?php if ($app['applicant_phone']): ?>
                                                                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($app['applicant_phone']); ?></p>
                                                                        <?php endif; ?>
                                                                        <?php if ($app['experience_years']): ?>
                                                                            <p><strong>Experience:</strong> <?php echo $app['experience_years']; ?> years</p>
                                                                        <?php endif; ?>
                                                                        <?php if ($app['current_salary']): ?>
                                                                            <p><strong>Current Salary:</strong> $<?php echo number_format($app['current_salary']); ?></p>
                                                                        <?php endif; ?>
                                                                        <?php if ($app['expected_salary']): ?>
                                                                            <p><strong>Expected Salary:</strong> $<?php echo number_format($app['expected_salary']); ?></p>
                                                                        <?php endif; ?>
                                                                        <?php if ($app['availability_date']): ?>
                                                                            <p><strong>Available From:</strong> <?php echo formatDate($app['availability_date']); ?></p>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <h6>Job Information</h6>
                                                                        <p><strong>Position:</strong> <?php echo htmlspecialchars($app['job_title']); ?></p>
                                                                        <p><strong>Company:</strong> <?php echo htmlspecialchars($app['company_name']); ?></p>
                                                                        <p><strong>Status:</strong> 
                                                                            <span class="badge <?php echo $status_classes[$app['status']] ?? 'bg-secondary'; ?>">
                                                                                <?php echo ucfirst($app['status']); ?>
                                                                            </span>
                                                                        </p>
                                                                        <p><strong>Applied:</strong> <?php echo formatDate($app['created_at'], 'M d, Y h:i A'); ?></p>
                                                                        <?php if ($app['portfolio_url']): ?>
                                                                            <p><strong>Portfolio:</strong> <a href="<?php echo htmlspecialchars($app['portfolio_url']); ?>" target="_blank">View Portfolio</a></p>
                                                                        <?php endif; ?>
                                                                        <?php if ($app['resume_path']): ?>
                                                                            <p><strong>Resume:</strong> <a href="<?php echo htmlspecialchars($app['resume_path']); ?>" target="_blank">Download Resume</a></p>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                                <?php if ($app['cover_letter']): ?>
                                                                    <hr>
                                                                    <h6>Cover Letter</h6>
                                                                    <p><?php echo nl2br(htmlspecialchars($app['cover_letter'])); ?></p>
                                                                <?php endif; ?>
                                                                <?php if ($app['notes']): ?>
                                                                    <hr>
                                                                    <h6>Internal Notes</h6>
                                                                    <p><?php echo nl2br(htmlspecialchars($app['notes'])); ?></p>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
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
    min-width: 150px;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.modal-body h6 {
    color: #dc3545;
    margin-bottom: 1rem;
}

.modal-body p {
    margin-bottom: 0.5rem;
}
</style>
