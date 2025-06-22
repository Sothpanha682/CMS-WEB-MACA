<?php
// Check if user is logged in
if (!isLoggedIn()) {
    $_SESSION['message'] = "You must be logged in to access this page.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=login');
    exit;
}

// Create talkshows table if it doesn't exist
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'talkshows'");
    if ($stmt->rowCount() == 0) {
        // Table doesn't exist, create it
        $pdo->exec("CREATE TABLE IF NOT EXISTS `talkshows` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `title` varchar(255) NOT NULL,
            `title_km` varchar(255) NOT NULL,
            `summary` text NOT NULL,
            `summary_km` text NOT NULL,
            `content` text NOT NULL,
            `content_km` text NOT NULL,
            `location` varchar(255) NOT NULL,
            `location_km` varchar(255) NOT NULL,
            `event_date` date NOT NULL,
            `video_url` varchar(255) NOT NULL,
            `image_path` varchar(255) DEFAULT NULL,
            `is_active` tinyint(1) NOT NULL DEFAULT 1,
            `created_at` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }
} catch(PDOException $e) {
    echo '<div class="alert alert-danger">Error creating table: ' . $e->getMessage() . '</div>';
}

// Get all talkshow entries
$talkshows = [];
try {
    // Updated to use talkshows table (plural)
    $stmt = $pdo->query("SELECT * FROM talkshows ORDER BY created_at DESC");
    $talkshows = $stmt->fetchAll();
} catch(PDOException $e) {
    $_SESSION['message'] = "Error: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Manage Talkshow</h1>
        <a href="index.php?page=add-talkshow" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add New Talkshow
        </a>
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
            <?php if (count($talkshows) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Location</th>
                                <th>Event Date</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($talkshows as $talkshow): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($talkshow['title']); ?></td>
                                    <td><?php echo htmlspecialchars($talkshow['location']); ?></td>
                                    <td><?php echo formatDate($talkshow['event_date']); ?></td>
                                    <td>
                                        <?php if ($talkshow['is_active']): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo formatDate($talkshow['created_at']); ?></td>
                                    <td>
                                        <a href="index.php?page=edit-talkshow&id=<?php echo $talkshow['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <a href="actions/delete-talkshow.php?id=<?php echo $talkshow['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this talkshow?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    No talkshow entries found. Click "Add New Talkshow" to create your first entry.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
