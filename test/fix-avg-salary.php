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
    // Check if avg_salary column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM popular_majors LIKE 'avg_salary'");
    $column_exists = $stmt->rowCount() > 0;
    
    if ($column_exists) {
        // Get current column type
        $column_info = $stmt->fetch(PDO::FETCH_ASSOC);
        $current_type = $column_info['Type'];
        $log[] = "Current avg_salary column type: " . $current_type;
        
        // Modify column to ensure it's VARCHAR(100)
        $pdo->exec("ALTER TABLE popular_majors MODIFY COLUMN avg_salary VARCHAR(100)");
        $log[] = "Modified avg_salary column to VARCHAR(100)";
        
        // Update any NULL values to empty string
        $stmt = $pdo->prepare("UPDATE popular_majors SET avg_salary = '' WHERE avg_salary IS NULL");
        $stmt->execute();
        $rows_updated = $stmt->rowCount();
        $log[] = "Updated $rows_updated rows with NULL avg_salary to empty string";
        
        $success = true;
        $_SESSION['message'] = "The avg_salary column has been fixed. You can now update salary information correctly.";
        $_SESSION['message_type'] = "success";
    } else {
        // Add the column if it doesn't exist
        $pdo->exec("ALTER TABLE popular_majors ADD COLUMN avg_salary VARCHAR(100) AFTER description_kh");
        $log[] = "Added avg_salary column as VARCHAR(100)";
        
        $success = true;
        $_SESSION['message'] = "The avg_salary column has been added to the database. You can now add salary information.";
        $_SESSION['message_type'] = "success";
    }
    
} catch(PDOException $e) {
    $error = $e->getMessage();
    $_SESSION['message'] = "Database error: " . $error;
    $_SESSION['message_type'] = "danger";
}
?>

<div class="container-fluid py-4">
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Fix Average Salary Field</h1>
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
                    <h5><i class="fas fa-check-circle"></i> Fix Completed</h5>
                    <p>The Average Salary field has been fixed and should now work correctly when updating major information.</p>
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
                    <a href="index.php?page=debug-major-update&id=1" class="btn btn-info">
                        <i class="fas fa-bug fa-sm text-white-50"></i> Debug Major Update
                    </a>
                </div>
            <?php else: ?>
                <div class="alert alert-danger">
                    <h5><i class="fas fa-exclamation-triangle"></i> Fix Failed</h5>
                    <p>There was an error fixing the Average Salary field:</p>
                    <pre class="bg-light p-3"><?php echo $error; ?></pre>
                </div>
                
                <div class="mt-4">
                    <a href="index.php?page=fix-avg-salary" class="btn btn-primary">
                        <i class="fas fa-sync fa-sm text-white-50"></i> Try Again
                    </a>
                    <a href="index.php?page=manage-popular-majors" class="btn btn-secondary">
                        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Majors
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">Troubleshooting Tips</h6>
        </div>
        <div class="card-body">
            <p>If you continue to experience issues with the Average Salary field, try these steps:</p>
            
            <ol>
                <li>Make sure you're using a compatible format for salary information (e.g., "$50,000 - $80,000 per year")</li>
                <li>Clear your browser cache and cookies</li>
                <li>Try using a different browser</li>
                <li>Check for any JavaScript errors in your browser console</li>
                <li>Ensure your database user has ALTER and UPDATE privileges</li>
            </ol>
            
            <p>For manual database fixes, you can run these SQL commands:</p>
            <pre class="bg-light p-3">
-- Fix the avg_salary column in the popular_majors table
ALTER TABLE popular_majors MODIFY COLUMN avg_salary VARCHAR(100);

-- Update any NULL values to empty string
UPDATE popular_majors SET avg_salary = '' WHERE avg_salary IS NULL;
            </pre>
        </div>
    </div>
</div>
