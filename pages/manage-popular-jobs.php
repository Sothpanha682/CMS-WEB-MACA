<?php
// Check if user is logged in
if (!isLoggedIn()) {
    $_SESSION['message'] = "You must be logged in to access this page.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=login');
    exit;
}
?>

<div class="container-fluid py-4">
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manage Popular Jobs</h1>
        <a href="index.php?page=add-popular-job" class="btn btn-danger">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Job
        </a>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-danger">Popular Jobs</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="15%">Image</th>
                            <th width="15%">Title</th>
                            <th width="30%">Description</th>
                            <th width="15%">Salary Range</th>
                            <th width="10%">Status</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $stmt = $pdo->query("SELECT * FROM popular_jobs ORDER BY display_order ASC");
                            $jobs = $stmt->fetchAll();
                            
                            if (count($jobs) > 0):
                                foreach ($jobs as $index => $job):
                        ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td>
                                <?php if (!empty($job['image_path'])): ?>
                                    <img src="<?php echo $job['image_path']; ?>" alt="<?php echo $job['title']; ?>" class="img-thumbnail" style="max-height: 80px;">
                                <?php else: ?>
                                    <span class="text-muted">No image</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $job['title']; ?></td>
                            <td><?php echo mb_substr(strip_tags($job['description'] ?? ''), 0, 100) . '...'; ?></td>
                            <td><?php echo $job['salary_range'] ?? 'N/A'; ?></td>
                            <td>
                                <?php if ($job['is_active']): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="index.php?page=edit-popular-job&id=<?php echo $job['id']; ?>" class="btn btn-sm btn-primary mb-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="actions/delete-popular-job.php?id=<?php echo $job['id']; ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Are you sure you want to delete this job?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php 
                                endforeach;
                            else:
                        ?>
                        <tr>
                            <td colspan="7" class="text-center">No popular jobs found. Click "Add New Job" to create your first job listing.</td>
                        </tr>
                        <?php 
                            endif;
                        } catch(PDOException $e) {
                            echo '<tr><td colspan="7" class="text-center text-danger">Error: ' . $e->getMessage() . '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
