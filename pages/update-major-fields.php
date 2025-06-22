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
    // Check which columns exist in the table
    $existing_columns = [];
    $stmt = $pdo->query("DESCRIBE popular_majors");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $existing_columns[] = $row['Field'];
    }
    
    // Define the new columns we want to add
    $new_columns = [
        'avg_salary' => "ADD COLUMN avg_salary VARCHAR(100) AFTER description_kh",
        'duration' => "ADD COLUMN duration VARCHAR(100) AFTER avg_salary",
        'about_major' => "ADD COLUMN about_major TEXT AFTER duration",
        'career_opportunities' => "ADD COLUMN career_opportunities TEXT AFTER skills_gained"
    ];
    
    // Add missing columns
    $columns_added = 0;
    foreach ($new_columns as $column => $sql_fragment) {
        if (!in_array($column, $existing_columns)) {
            $sql = "ALTER TABLE popular_majors $sql_fragment";
            $pdo->exec($sql);
            $log[] = "Added column '$column' to popular_majors table.";
            $columns_added++;
        } else {
            $log[] = "Column '$column' already exists in popular_majors table.";
        }
    }
    
    // If columns were added, add sample data
    if ($columns_added > 0) {
        $sql = "UPDATE popular_majors 
                SET 
                    avg_salary = CONCAT('$', FLOOR(RAND() * (80000 - 40000) + 40000), ' - $', FLOOR(RAND() * (120000 - 80000) + 80000), ' per year'),
                    duration = CASE FLOOR(RAND() * 3)
                        WHEN 0 THEN '4 years (Bachelor\'s Degree)'
                        WHEN 1 THEN '2 years (Associate\'s Degree)'
                        WHEN 2 THEN '5-6 years (Bachelor\'s + Master\'s)'
                    END,
                    about_major = CONCAT('This major provides students with a comprehensive education in ', title, '. Students will develop critical thinking, problem-solving, and communication skills while gaining specialized knowledge in their field of study.'),
                    career_opportunities = 'Graduates can pursue careers in various sectors including:\n- Education and Research\n- Industry and Private Sector\n- Government and Public Service\n- Non-profit Organizations\n- Entrepreneurship and Innovation'
                WHERE 
                    (avg_salary IS NULL OR avg_salary = '') AND
                    (duration IS NULL OR duration = '') AND
                    (about_major IS NULL OR about_major = '') AND
                    (career_opportunities IS NULL OR career_opportunities = '')";
        $pdo->exec($sql);
        $log[] = "Added sample data to new columns for existing records.";
    }
    
    $success = true;
    $_SESSION['message'] = $columns_added > 0 
        ? "Database updated successfully. Added $columns_added new column(s)." 
        : "Database is already up to date. No changes were made.";
    $_SESSION['message_type'] = "success";
    
} catch(PDOException $e) {
    $error = $e->getMessage();
    $_SESSION['message'] = "Database error: " . $error;
    $_SESSION['message_type'] = "danger";
}
?>

<div class="container-fluid py-4">
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Update Major Fields</h1>
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
                    <p>The database structure has been updated successfully. You can now use all the new fields in the Edit Popular Major form.</p>
                </div>
                
                <h6 class="font-weight-bold">Update Log:</h6>
                <ul class="list-group mb-4">
                    <?php foreach ($log as $entry): ?>
                        <li class="list-group-item"><?php echo $entry; ?></li>
                    <?php endforeach; ?>
                </ul>
                
                <h6 class="font-weight-bold">New Fields Added:</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Field Name</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Average Salary</td>
                                <td>The typical salary range for graduates with this major</td>
                            </tr>
                            <tr>
                                <td>Duration</td>
                                <td>How long it typically takes to complete this major</td>
                            </tr>
                            <tr>
                                <td>About This Major</td>
                                <td>Detailed information about what students will learn</td>
                            </tr>
                            <tr>
                                <td>Career Opportunities</td>
                                <td>Potential career paths and job opportunities for graduates</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
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
                    <a href="index.php?page=update-major-fields" class="btn btn-primary">
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
