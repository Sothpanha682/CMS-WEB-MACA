<?php
// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    $_SESSION['message'] = "Access denied. Admin privileges required.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=login');
    exit;
}

// Get job ID
$job_id = isset($_GET['job_id']) ? (int)$_GET['job_id'] : 0;
if (!$job_id) {
    $_SESSION['message'] = "Invalid job ID.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=manage-recruitment');
    exit;
}

// Get job details
try {
    $stmt = $pdo->prepare("SELECT * FROM job_postings WHERE id = ?");
    $stmt->execute([$job_id]);
    $job = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$job) {
        $_SESSION['message'] = "Job not found.";
        $_SESSION['message_type'] = "danger";
        header('Location: index.php?page=manage-recruitment');
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['message'] = "Error loading job details.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=manage-recruitment');
    exit;
}

// Handle actions
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'update-status':
            if (isset($_GET['app_id']) && isset($_GET['status'])) {
                $app_id = (int)$_GET['app_id'];
                $status = sanitize($_GET['status']);
                $allowed_statuses = ['pending', 'reviewed', 'shortlisted', 'interviewed', 'hired', 'rejected'];
                
                if (in_array($status, $allowed_statuses)) {
                    try {
                        $stmt = $pdo->prepare("UPDATE job_applications SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                        if ($stmt->execute([$status, $app_id])) {
                            $_SESSION['message'] = "Application status updated successfully.";
                            $_SESSION['message_type'] = "success";
                        }
                    } catch (PDOException $e) {
                        $_SESSION['message'] = "Database error: " . $e->getMessage();
                        $_SESSION['message_type'] = "danger";
                    }
                }
            }
            header('Location: index.php?page=view-job-applications&job_id=' . $job_id);
            exit;
            break;
            
        case 'delete-application':
            if (isset($_GET['app_id'])) {
                $app_id = (int)$_GET['app_id'];
                try {
                    // Get resume path before deleting
                    $stmt = $pdo->prepare("SELECT resume_path FROM job_applications WHERE id = ?");
                    $stmt->execute([$app_id]);
                    $application = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    // Delete the application
                    $stmt = $pdo->prepare("DELETE FROM job_applications WHERE id = ?");
                    if ($stmt->execute([$app_id])) {
                        // Delete resume file if exists
                        if ($application && $application['resume_path'] && file_exists($application['resume_path'])) {
                            unlink($application['resume_path']);
                        }
                        
                        // Update application count
                        $stmt = $pdo->prepare("UPDATE job_postings SET applications_count = applications_count - 1 WHERE id = ?");
                        $stmt->execute([$job_id]);
                        
                        $_SESSION['message'] = "Application deleted successfully.";
                        $_SESSION['message_type'] = "success";
                    }
                } catch (PDOException $e) {
                    $_SESSION['message'] = "Database error: " . $e->getMessage();
                    $_SESSION['message_type'] = "danger";
                }
            }
            header('Location: index.php?page=view-job-applications&job_id=' . $job_id);
            exit;
            break;
    }
}

// Get applications for this job
try {
    $stmt = $pdo->prepare("SELECT * FROM job_applications WHERE job_id = ? ORDER BY created_at DESC");
    $stmt->execute([$job_id]);
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $applications = [];
    $error_message = "Error fetching applications: " . $e->getMessage();
}

// Get application statistics for this job
try {
    $stats = [];
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM job_applications WHERE job_id = ?");
    $stmt->execute([$job_id]);
    $stats['total'] = $stmt->fetchColumn();
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as pending FROM job_applications WHERE job_id = ? AND status = 'pending'");
    $stmt->execute([$job_id]);
    $stats['pending'] = $stmt->fetchColumn();
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as reviewed FROM job_applications WHERE job_id = ? AND status IN ('reviewed', 'shortlisted', 'interviewed')");
    $stmt->execute([$job_id]);
    $stats['reviewed'] = $stmt->fetchColumn();
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as hired FROM job_applications WHERE job_id = ? AND status = 'hired'");
    $stmt->execute([$job_id]);
    $stats['hired'] = $stmt->fetchColumn();
} catch (PDOException $e) {
    $stats = ['total' => 0, 'pending' => 0, 'reviewed' => 0, 'hired' => 0];
}
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 text-danger">Applications for: <?php echo htmlspecialchars($job['title']); ?></h1>
                    <p class="text-muted mb-0"><?php echo htmlspecialchars($job['company_name']); ?> • <?php echo htmlspecialchars($job['location']); ?></p>
                </div>
                <div>
                    <a href="index.php?page=edit-job-posting&id=<?php echo $job['id']; ?>" class="btn btn-outline-primary me-2">
                        <i class="fas fa-edit me-1"></i> Edit Job
                    </a>
                    <a href="index.php?page=manage-recruitment" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Jobs
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

            <!-- Job Summary Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="card-title"><?php echo htmlspecialchars($job['title']); ?></h5>
                            <p class="card-text text-muted"><?php echo truncateText(strip_tags($job['description']), 200); ?></p>
                            <div class="d-flex gap-3 flex-wrap">
                                <span class="badge bg-primary"><?php echo ucfirst(str_replace('-', ' ', $job['job_type'])); ?></span>
                                <?php if ($job['salary_min'] && $job['salary_max']): ?>
                                <span class="badge bg-success">$<?php echo number_format($job['salary_min']); ?> - $<?php echo number_format($job['salary_max']); ?></span>
                                <?php endif; ?>
                                <span class="badge bg-info"><?php echo $job['views_count']; ?> views</span>
                                <span class="badge bg-secondary">Posted <?php echo formatDate($job['created_at'], 'M d, Y'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="d-flex flex-column gap-2">
                                <a href="index.php?page=job-detail&id=<?php echo $job['id']; ?>" class="btn btn-outline-primary btn-sm" target="_blank">
                                    <i class="fas fa-external-link-alt me-1"></i> View Public Page
                                </a>
                                <button class="btn btn-outline-danger btn-sm" onclick="confirmDelete(<?php echo $job['id']; ?>)">
                                    <i class="fas fa-trash me-1"></i> Delete Job
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                                    <h6 class="mb-0">In Process</h6>
                                    <h2 class="mb-0"><?php echo $stats['reviewed']; ?></h2>
                                </div>
                                <div class="bg-white rounded-circle p-3 text-info">
                                    <i class="fas fa-cogs fa-2x"></i>
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

            <!-- Applications Table -->
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Job Applications (<?php echo count($applications); ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php elseif (empty($applications)): ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                            <h5>No Applications Yet</h5>
                            <p class="mb-0">This job posting hasn't received any applications yet. Share the job posting to attract more candidates.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Applicant</th>
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
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-3" 
                                                         style="width: 40px; height: 40px; font-weight: bold;">
                                                        <?php echo strtoupper(substr($app['applicant_name'], 0, 2)); ?>
                                                    </div>
                                                    <div>
                                                        <strong><?php echo htmlspecialchars($app['applicant_name']); ?></strong>
                                                        <br>
                                                        <small class="text-muted">
                                                            <i class="fas fa-envelope me-1"></i><?php echo htmlspecialchars($app['applicant_email']); ?>
                                                        </small>
                                                        <?php if ($app['applicant_phone']): ?>
                                                            <br>
                                                            <small class="text-muted">
                                                                <i class="fas fa-phone me-1"></i><?php echo htmlspecialchars($app['applicant_phone']); ?>
                                                            </small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if ($app['experience_years'] !== null): ?>
                                                    <span class="badge bg-secondary"><?php echo $app['experience_years']; ?> years</span>
                                                <?php else: ?>
                                                    <span class="text-muted">Not specified</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($app['expected_salary']): ?>
                                                    <span class="text-success fw-bold">$<?php echo number_format($app['expected_salary']); ?></span>
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
                                            <td>
                                                <?php echo formatDate($app['created_at'], 'M d, Y'); ?>
                                                <br>
                                                <small class="text-muted"><?php echo formatDate($app['created_at'], 'h:i A'); ?></small>
                                            </td>
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
                                                        <li><a class="dropdown-item" href="?page=view-job-applications&job_id=<?php echo $job_id; ?>&action=update-status&app_id=<?php echo $app['id']; ?>&status=pending">
                                                            <i class="fas fa-clock me-1 text-warning"></i> Pending
                                                        </a></li>
                                                        <li><a class="dropdown-item" href="?page=view-job-applications&job_id=<?php echo $job_id; ?>&action=update-status&app_id=<?php echo $app['id']; ?>&status=reviewed">
                                                            <i class="fas fa-eye me-1 text-info"></i> Reviewed
                                                        </a></li>
                                                        <li><a class="dropdown-item" href="?page=view-job-applications&job_id=<?php echo $job_id; ?>&action=update-status&app_id=<?php echo $app['id']; ?>&status=shortlisted">
                                                            <i class="fas fa-star me-1 text-primary"></i> Shortlisted
                                                        </a></li>
                                                        <li><a class="dropdown-item" href="?page=view-job-applications&job_id=<?php echo $job_id; ?>&action=update-status&app_id=<?php echo $app['id']; ?>&status=interviewed">
                                                            <i class="fas fa-comments me-1 text-secondary"></i> Interviewed
                                                        </a></li>
                                                        <li><a class="dropdown-item" href="?page=view-job-applications&job_id=<?php echo $job_id; ?>&action=update-status&app_id=<?php echo $app['id']; ?>&status=hired">
                                                            <i class="fas fa-user-check me-1 text-success"></i> Hired
                                                        </a></li>
                                                        <li><a class="dropdown-item" href="?page=view-job-applications&job_id=<?php echo $job_id; ?>&action=update-status&app_id=<?php echo $app['id']; ?>&status=rejected">
                                                            <i class="fas fa-times me-1 text-danger"></i> Rejected
                                                        </a></li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li><a class="dropdown-item text-danger" href="?page=view-job-applications&job_id=<?php echo $job_id; ?>&action=delete-application&app_id=<?php echo $app['id']; ?>" 
                                                               onclick="return confirm('Are you sure you want to delete this application? This action cannot be undone.')">
                                                            <i class="fas fa-trash me-1"></i> Delete Application
                                                        </a></li>
                                                    </ul>
                                                </div>

                                                <!-- Application Detail Modal -->
                                                <div class="modal fade" id="applicationModal<?php echo $app['id']; ?>" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">
                                                                    <i class="fas fa-user me-2"></i>
                                                                    Application Details - <?php echo htmlspecialchars($app['applicant_name']); ?>
                                                                </h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <!-- Personal Information -->
                                                                    <div class="col-md-6">
                                                                        <div class="card h-100">
                                                                            <div class="card-header bg-primary text-white">
                                                                                <h6 class="mb-0"><i class="fas fa-user me-2"></i>Personal Information</h6>
                                                                            </div>
                                                                            <div class="card-body">
                                                                                <div class="mb-3">
                                                                                    <strong>Full Name:</strong><br>
                                                                                    <?php echo htmlspecialchars($app['applicant_name']); ?>
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <strong>Email:</strong><br>
                                                                                    <a href="mailto:<?php echo htmlspecialchars($app['applicant_email']); ?>">
                                                                                        <?php echo htmlspecialchars($app['applicant_email']); ?>
                                                                                    </a>
                                                                                </div>
                                                                                <?php if ($app['applicant_phone']): ?>
                                                                                <div class="mb-3">
                                                                                    <strong>Phone:</strong><br>
                                                                                    <a href="tel:<?php echo htmlspecialchars($app['applicant_phone']); ?>">
                                                                                        <?php echo htmlspecialchars($app['applicant_phone']); ?>
                                                                                    </a>
                                                                                </div>
                                                                                <?php endif; ?>
                                                                                <?php if ($app['portfolio_url']): ?>
                                                                                <div class="mb-3">
                                                                                    <strong>Portfolio:</strong><br>
                                                                                    <a href="<?php echo htmlspecialchars($app['portfolio_url']); ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                                                                        <i class="fas fa-external-link-alt me-1"></i>View Portfolio
                                                                                    </a>
                                                                                </div>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Professional Information -->
                                                                    <div class="col-md-6">
                                                                        <div class="card h-100">
                                                                            <div class="card-header bg-success text-white">
                                                                                <h6 class="mb-0"><i class="fas fa-briefcase me-2"></i>Professional Information</h6>
                                                                            </div>
                                                                            <div class="card-body">
                                                                                <?php if ($app['experience_years'] !== null): ?>
                                                                                <div class="mb-3">
                                                                                    <strong>Experience:</strong><br>
                                                                                    <span class="badge bg-secondary"><?php echo $app['experience_years']; ?> years</span>
                                                                                </div>
                                                                                <?php endif; ?>
                                                                                <?php if ($app['current_salary']): ?>
                                                                                <div class="mb-3">
                                                                                    <strong>Current Salary:</strong><br>
                                                                                    $<?php echo number_format($app['current_salary']); ?> USD
                                                                                </div>
                                                                                <?php endif; ?>
                                                                                <?php if ($app['expected_salary']): ?>
                                                                                <div class="mb-3">
                                                                                    <strong>Expected Salary:</strong><br>
                                                                                    <span class="text-success fw-bold">$<?php echo number_format($app['expected_salary']); ?> USD</span>
                                                                                </div>
                                                                                <?php endif; ?>
                                                                                <?php if ($app['availability_date']): ?>
                                                                                <div class="mb-3">
                                                                                    <strong>Available From:</strong><br>
                                                                                    <?php echo formatDate($app['availability_date'], 'M d, Y'); ?>
                                                                                </div>
                                                                                <?php endif; ?>
                                                                                <?php if ($app['resume_path']): ?>
                                                                                <div class="mb-3">
                                                                                    <strong>Resume:</strong><br>
                                                                                    <a href="<?php echo htmlspecialchars($app['resume_path']); ?>" target="_blank" class="btn btn-outline-danger btn-sm">
                                                                                        <i class="fas fa-file-pdf me-1"></i>Download Resume
                                                                                    </a>
                                                                                </div>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Application Status -->
                                                                <div class="row mt-3">
                                                                    <div class="col-12">
                                                                        <div class="card">
                                                                            <div class="card-header bg-info text-white">
                                                                                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Application Status</h6>
                                                                            </div>
                                                                            <div class="card-body">
                                                                                <div class="row">
                                                                                    <div class="col-md-4">
                                                                                        <strong>Current Status:</strong><br>
                                                                                        <span class="badge <?php echo $status_classes[$app['status']] ?? 'bg-secondary'; ?> fs-6">
                                                                                            <?php echo ucfirst($app['status']); ?>
                                                                                        </span>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <strong>Applied Date:</strong><br>
                                                                                        <?php echo formatDate($app['created_at'], 'M d, Y h:i A'); ?>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <strong>Last Updated:</strong><br>
                                                                                        <?php echo formatDate($app['updated_at'], 'M d, Y h:i A'); ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Cover Letter -->
                                                                <?php if ($app['cover_letter']): ?>
                                                                <div class="row mt-3">
                                                                    <div class="col-12">
                                                                        <div class="card">
                                                                            <div class="card-header bg-warning text-dark">
                                                                                <h6 class="mb-0"><i class="fas fa-edit me-2"></i>Cover Letter</h6>
                                                                            </div>
                                                                            <div class="card-body">
                                                                                <div class="bg-light p-3 rounded">
                                                                                    <?php echo nl2br(htmlspecialchars($app['cover_letter'])); ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php endif; ?>

                                                                <!-- Internal Notes -->
                                                                <?php if ($app['notes']): ?>
                                                                <div class="row mt-3">
                                                                    <div class="col-12">
                                                                        <div class="card">
                                                                            <div class="card-header bg-secondary text-white">
                                                                                <h6 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Internal Notes</h6>
                                                                            </div>
                                                                            <div class="card-body">
                                                                                <?php echo nl2br(htmlspecialchars($app['notes'])); ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <div class="btn-group me-auto">
                                                                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                                                                        <i class="fas fa-edit me-1"></i>Update Status
                                                                    </button>
                                                                    <ul class="dropdown-menu">
                                                                        <li><a class="dropdown-item" href="?page=view-job-applications&job_id=<?php echo $job_id; ?>&action=update-status&app_id=<?php echo $app['id']; ?>&status=pending">Pending</a></li>
                                                                        <li><a class="dropdown-item" href="?page=view-job-applications&job_id=<?php echo $job_id; ?>&action=update-status&app_id=<?php echo $app['id']; ?>&status=reviewed">Reviewed</a></li>
                                                                        <li><a class="dropdown-item" href="?page=view-job-applications&job_id=<?php echo $job_id; ?>&action=update-status&app_id=<?php echo $app['id']; ?>&status=shortlisted">Shortlisted</a></li>
                                                                        <li><a class="dropdown-item" href="?page=view-job-applications&job_id=<?php echo $job_id; ?>&action=update-status&app_id=<?php echo $app['id']; ?>&status=interviewed">Interviewed</a></li>
                                                                        <li><a class="dropdown-item" href="?page=view-job-applications&job_id=<?php echo $job_id; ?>&action=update-status&app_id=<?php echo $app['id']; ?>&status=hired">Hired</a></li>
                                                                        <li><a class="dropdown-item" href="?page=view-job-applications&job_id=<?php echo $job_id; ?>&action=update-status&app_id=<?php echo $app['id']; ?>&status=rejected">Rejected</a></li>
                                                                    </ul>
                                                                </div>
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

<script>
function confirmDelete(jobId) {
    if (confirm('Are you sure you want to delete this job posting? This will also delete all applications and cannot be undone.')) {
        window.location.href = 'actions/delete-job-posting.php?id=' + jobId;
    }
}
</script>

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

.modal-body .card {
    margin-bottom: 0;
}

.modal-body .card-header {
    font-weight: 600;
}

.modal-xl {
    max-width: 1200px;
}

.bg-light {
    background-color: #f8f9fa !important;
}
</style>
