<?php
// Check if user is logged in (removed the admin check)
if (!isLoggedIn()) {
    $_SESSION['message'] = "You must be logged in to access this page.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=login');
    exit;
}

// Get all roadshows
$roadshows = [];
try {
    $stmt = $pdo->query("SELECT * FROM roadshow ORDER BY created_at DESC");
    $roadshows = $stmt->fetchAll();
} catch(PDOException $e) {
    $_SESSION['message'] = "Error: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}
?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Manage Roadshows</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="index.php?page=add-roadshow" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add New Roadshow
            </a>
        </div>
    </div>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <?php if (count($roadshows) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Location</th>
                                <th>Event Date</th>
                                <th>Video URL</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($roadshows as $roadshow): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($roadshow['title']); ?></td>
                                    <td><?php echo htmlspecialchars($roadshow['location'] ?? ''); ?></td>
                                    <td><?php echo isset($roadshow['event_date']) ? formatDate($roadshow['event_date']) : 'N/A'; ?></td>
                                    <td>
                                        <?php if (!empty($roadshow['video_url'])): ?>
                                            <a href="<?php echo htmlspecialchars($roadshow['video_url']); ?>" target="_blank" class="btn btn-sm btn-info" title="View Video">
                                                <i class="fas fa-video"></i> View
                                            </a>
                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (isset($roadshow['is_active']) && $roadshow['is_active']): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo formatDate($roadshow['created_at']); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="index.php?page=edit-roadshow&id=<?php echo $roadshow['id']; ?>" class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="index.php?page=delete-roadshow&id=<?php echo $roadshow['id']; ?>" class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $roadshow['id']; ?>" title="Delete" onclick="return confirm('Are you sure you want to delete this roadshow?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <p>No roadshows found. <a href="index.php?page=add-roadshow">Add your first roadshow</a>.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
