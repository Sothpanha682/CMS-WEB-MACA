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
        <h1 class="h3 mb-0 text-gray-800">Manage Popular Career</h1>
        <a href="index.php?page=add-popular-career" class="btn btn-danger">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Career
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
            <h6 class="m-0 font-weight-bold text-danger">Popular Career</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="10%">Image</th>
                            <th width="15%">Title</th>
                            <th width="15%">Company</th>
                            <th width="10%">Location</th>
                            <th width="10%">Job Type</th>
                            <th width="10%">Salary Range</th>
                            <th width="10%">Status</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $stmt = $pdo->query("SELECT * FROM popular_jobs ORDER BY display_order ASC");
                            $jobs = $stmt->fetchAll();
                            
                            if (count($jobs) > 0):
                                foreach ($jobs as $index => $job):
                                    // Check for both possible image column names
                                    $image_path = '';
                                    if (!empty($job['image_path'])) {
                                        $image_path = $job['image_path'];
                                    } elseif (!empty($job['image'])) {
                                        $image_path = $job['image'];
                                    }
                        ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td>
                                <?php if (!empty($image_path) && file_exists($image_path)): ?>
                                    <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($job['title']); ?>" class="img-thumbnail" style="max-height: 80px;">
                                <?php elseif (!empty($image_path)): ?>
                                    <span class="text-warning" title="Image file not found: <?php echo htmlspecialchars($image_path); ?>">
                                        <i class="fas fa-exclamation-triangle"></i> Missing file
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">No image</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($job['title']); ?></td>
                            <td><?php echo htmlspecialchars($job['company'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($job['location'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($job['job_type'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($job['salary_range'] ?? 'N/A'); ?></td>
                            <td>
                                <?php if ($job['is_active'] ?? 1): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="index.php?page=edit-popular-career&id=<?php echo $job['id']; ?>" class="btn btn-sm btn-primary mb-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="actions/delete-popular-career.php?id=<?php echo $job['id']; ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Are you sure you want to delete this career?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php 
                                endforeach;
                            else:
                        ?>
                        <tr>
                            <td colspan="9" class="text-center">No popular careers found. Click "Add New Career" to create your first career listing.</td>
                        </tr>
                        <?php 
                            endif;
                        } catch(PDOException $e) {
                            echo '<tr><td colspan="9" class="text-center text-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Debug Information (remove in production) -->
<?php if (isAdmin()): ?>
<div class="card mt-4">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-info">Debug Information (Admin Only)</h6>
    </div>
    <div class="card-body">
        <?php
        try {
            $stmt = $pdo->query("DESCRIBE popular_jobs");
            $columns = $stmt->fetchAll();
            echo "<p><strong>Database Columns:</strong></p>";
            echo "<ul>";
            foreach ($columns as $column) {
                echo "<li>" . htmlspecialchars($column['Field']) . " (" . htmlspecialchars($column['Type']) . ")</li>";
            }
            echo "</ul>";
            
            // Show sample data
            $stmt = $pdo->query("SELECT id, title, image, image_path FROM popular_jobs LIMIT 3");
            $sample_data = $stmt->fetchAll();
            if ($sample_data) {
                echo "<p><strong>Sample Data:</strong></p>";
                echo "<pre>";
                foreach ($sample_data as $row) {
                    echo "ID: " . $row['id'] . "\n";
                    echo "Title: " . htmlspecialchars($row['title']) . "\n";
                    echo "Image: " . htmlspecialchars($row['image'] ?? 'NULL') . "\n";
                    echo "Image Path: " . htmlspecialchars($row['image_path'] ?? 'NULL') . "\n";
                    echo "---\n";
                }
                echo "</pre>";
            }
        } catch (PDOException $e) {
            echo "<p class='text-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
        ?>
    </div>
</div>
<?php endif; ?>
