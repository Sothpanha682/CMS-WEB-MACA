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
        <h1 class="h3 mb-0 text-gray-800">Manage Popular Majors</h1>
        <a href="index.php?page=add-popular-major" class="btn btn-danger">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Major
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
            <h6 class="m-0 font-weight-bold text-danger">Popular Majors</h6>
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
                            <th width="15%">Institutions</th>
                            <th width="10%">Status</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $stmt = $pdo->query("SELECT * FROM popular_majors ORDER BY display_order ASC");
                            $majors = $stmt->fetchAll();
                            
                            if (count($majors) > 0):
                                foreach ($majors as $index => $major):
                        ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td>
                                <?php if (!empty($major['image_path'])): ?>
                                    <img src="<?php echo $major['image_path']; ?>" alt="<?php echo $major['title']; ?>" class="img-thumbnail" style="max-height: 80px;">
                                <?php else: ?>
                                    <span class="text-muted">No image</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $major['title']; ?></td>
                            <td><?php echo mb_substr(strip_tags($major['description'] ?? ''), 0, 100) . '...'; ?></td>
                            <td><?php echo mb_substr($major['institutions'] ?? '', 0, 50) . (strlen($major['institutions'] ?? '') > 50 ? '...' : ''); ?></td>
                            <td>
                                <?php if ($major['is_active']): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="index.php?page=edit-popular-major&id=<?php echo $major['id']; ?>" class="btn btn-sm btn-primary mb-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="actions/delete-popular-major.php?id=<?php echo $major['id']; ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Are you sure you want to delete this major?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php 
                                endforeach;
                            else:
                        ?>
                        <tr>
                            <td colspan="7" class="text-center">No popular majors found. Click "Add New Major" to create your first major listing.</td>
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
