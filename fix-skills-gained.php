<?php
// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    $_SESSION['message'] = "You must be logged in as an administrator to access this page.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=login');
    exit;
}

// Initialize variables
$success = false;
$error = '';
$log = [];

try {
    // Check if skills_gained column exists
    $column_exists = false;
    $stmt = $pdo->query("DESCRIBE popular_majors");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row['Field'] == 'skills_gained') {
            $column_exists = true;
            $log[] = "Skills Gained column already exists in the database.";
            break;
        }
    }
    
    // If column doesn't exist, add it
    if (!$column_exists) {
        $sql = "ALTER TABLE popular_majors ADD COLUMN skills_gained TEXT AFTER institutions";
        $pdo->exec($sql);
        $log[] = "Added Skills Gained column to the database.";
        
        // Add sample data to the new column
        $sql = "UPDATE popular_majors 
                SET skills_gained = CONCAT('Students who major in ', title, ' will develop the following skills:\n
- Critical thinking and problem-solving\n
- Research and analytical skills\n
- Communication and presentation skills\n
- Teamwork and collaboration\n
- Technical skills specific to the field')
                WHERE skills_gained IS NULL OR skills_gained = ''";
        $pdo->exec($sql);
        $log[] = "Added sample data to the Skills Gained column for existing records.";
    }
    
    // Check if any records have NULL values for skills_gained
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM popular_majors WHERE skills_gained IS NULL");
    $result = $stmt->fetch();
    if ($result['count'] > 0) {
        $sql = "UPDATE popular_majors SET skills_gained = '' WHERE skills_gained IS NULL";
        $pdo->exec($sql);
        $log[] = "Fixed NULL values in Skills Gained column for {$result['count']} records.";
    }
    
    $success = true;
    $_SESSION['message'] = $column_exists 
        ? "Skills Gained column is already set up correctly." 
        : "Skills Gained column has been added and configured successfully.";
    $_SESSION['message_type'] = "success";
    
} catch(PDOException $e) {
    $error = $e->getMessage();
    $_SESSION['message'] = "Database error: " . $error;
    $_SESSION['message_type'] = "danger";
}
?>

<div class="container-fluid py-4">
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Fix Skills Gained Field</h1>
        <a href="index.php?page=manage-popular-majors" class="btn btn-secondary">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Majors
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
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">Database Update Results</h6>
        </div>
        <div class="card-body">
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <h5><i class="fas fa-check-circle"></i> Update Completed</h5>
                    <p>The Skills Gained field has been set up correctly. You can now use this field in the Edit Popular Major form.</p>
                </div>
                
                <h6 class="font-weight-bold">Update Log:</h6>
                <ul class="list-group mb-4">
                    <?php foreach ($log as $entry): ?>
                        <li class="list-group-item"><?php echo $entry; ?></li>
                    <?php endforeach; ?>
                </ul>
                
                <div class="mt-4">
                    <a href="index.php?page=manage-popular-majors" class="btn btn-primary">
                        <i class="fas fa-list fa-sm text-white-50"></i> Go to Manage Majors
                    </a>
                    <a href="index.php?page=add-popular-major" class="btn btn-success">
                        <i class="fas fa-plus fa-sm text-white-50"></i> Add New Major
                    </a>
                </div>
            <?php else: ?>
                <div class="alert alert-danger">
                    <h5><i class="fas fa-exclamation-triangle"></i> Update Failed</h5>
                    <p>There was an error updating the database structure:</p>
                    <pre class="bg-light p-3"><?php echo $error; ?></pre>
                </div>
                
                <div class="mt-4">
                    <a href="index.php?page=fix-skills-gained" class="btn btn-primary">
                        <i class="fas fa-sync fa-sm text-white-50"></i> Try Again
                    </a>
                    <a href="index.php?page=manage-popular-majors" class="btn btn-secondary">
                        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Majors
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
