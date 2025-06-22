<?php
// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    $_SESSION['message'] = "You must be logged in as an administrator to access this page.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=login');
    exit;
}

// Initialize variables
$major_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($major_id <= 0) {
    echo "<div class='alert alert-danger'>Invalid major ID</div>";
    exit;
}

// Fetch current major data
try {
    $stmt = $pdo->prepare("SELECT * FROM popular_majors WHERE id = :id");
    $stmt->bindParam(':id', $major_id);
    $stmt->execute();
    $major = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$major) {
        echo "<div class='alert alert-danger'>Major not found</div>";
        exit;
    }
} catch(PDOException $e) {
    echo "<div class='alert alert-danger'>Database error: " . $e->getMessage() . "</div>";
    exit;
}

// Check table structure
try {
    $stmt = $pdo->query("DESCRIBE popular_majors");
    $columns = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $columns[$row['Field']] = $row;
    }
} catch(PDOException $e) {
    echo "<div class='alert alert-danger'>Error checking table structure: " . $e->getMessage() . "</div>";
    exit;
}

// Process test update if form submitted
$update_result = null;
$debug_info = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $test_value = trim($_POST['test_value'] ?? '');
    
    try {
        // Prepare debug info
        $debug_info['original_value'] = $major['avg_salary'] ?? 'NULL';
        $debug_info['new_value'] = $test_value;
        $debug_info['column_type'] = $columns['avg_salary']['Type'] ?? 'Unknown';
        
        // Update the avg_salary field
        $stmt = $pdo->prepare("UPDATE popular_majors SET avg_salary = :avg_salary WHERE id = :id");
        $stmt->bindParam(':avg_salary', $test_value);
        $stmt->bindParam(':id', $major_id);
        $result = $stmt->execute();
        
        // Check if update was successful
        if ($result) {
            // Fetch the updated value
            $stmt = $pdo->prepare("SELECT avg_salary FROM popular_majors WHERE id = :id");
            $stmt->bindParam(':id', $major_id);
            $stmt->execute();
            $updated = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $debug_info['updated_value'] = $updated['avg_salary'] ?? 'NULL';
            $debug_info['success'] = true;
            $update_result = "Update successful";
        } else {
            $debug_info['success'] = false;
            $debug_info['error'] = "Update failed";
            $update_result = "Update failed";
        }
    } catch(PDOException $e) {
        $debug_info['success'] = false;
        $debug_info['error'] = $e->getMessage();
        $update_result = "Error: " . $e->getMessage();
    }
}

// Fix the column if needed
$fix_result = null;
if (isset($_POST['fix_column'])) {
    try {
        // Modify the column to ensure it's VARCHAR(100)
        $stmt = $pdo->prepare("ALTER TABLE popular_majors MODIFY COLUMN avg_salary VARCHAR(100)");
        $result = $stmt->execute();
        
        if ($result) {
            $fix_result = "Column fixed successfully";
        } else {
            $fix_result = "Failed to fix column";
        }
    } catch(PDOException $e) {
        $fix_result = "Error: " . $e->getMessage();
    }
}
?>

<div class="container-fluid py-4">
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Debug Major Update</h1>
        <a href="index.php?page=edit-popular-major&id=<?php echo $major_id; ?>" class="btn btn-primary">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Edit Major
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">Major Information</h6>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <h5>Current Major Data</h5>
                <p><strong>ID:</strong> <?php echo $major['id']; ?></p>
                <p><strong>Title:</strong> <?php echo htmlspecialchars($major['title']); ?></p>
                <p><strong>Avg. Salary:</strong> <?php echo htmlspecialchars($major['avg_salary'] ?? 'Not set'); ?></p>
            </div>
            
            <div class="alert alert-warning">
                <h5>Column Information</h5>
                <?php if (isset($columns['avg_salary'])): ?>
                    <p><strong>Column Name:</strong> avg_salary</p>
                    <p><strong>Type:</strong> <?php echo $columns['avg_salary']['Type']; ?></p>
                    <p><strong>Null:</strong> <?php echo $columns['avg_salary']['Null']; ?></p>
                    <p><strong>Default:</strong> <?php echo $columns['avg_salary']['Default'] ?? 'NULL'; ?></p>
                <?php else: ?>
                    <p>The avg_salary column does not exist in the table.</p>
                <?php endif; ?>
            </div>
            
            <?php if ($update_result): ?>
            <div class="alert alert-<?php echo $debug_info['success'] ? 'success' : 'danger'; ?>">
                <h5>Update Result</h5>
                <p><?php echo $update_result; ?></p>
                
                <h6>Debug Information:</h6>
                <ul>
                    <li>Original Value: <?php echo htmlspecialchars($debug_info['original_value']); ?></li>
                    <li>New Value: <?php echo htmlspecialchars($debug_info['new_value']); ?></li>
                    <li>Updated Value: <?php echo htmlspecialchars($debug_info['updated_value'] ?? 'Unknown'); ?></li>
                    <li>Column Type: <?php echo htmlspecialchars($debug_info['column_type']); ?></li>
                    <?php if (isset($debug_info['error'])): ?>
                    <li>Error: <?php echo htmlspecialchars($debug_info['error']); ?></li>
                    <?php endif; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <?php if ($fix_result): ?>
            <div class="alert alert-info">
                <h5>Fix Result</h5>
                <p><?php echo $fix_result; ?></p>
            </div>
            <?php endif; ?>
            
            <form action="index.php?page=debug-major-update&id=<?php echo $major_id; ?>" method="POST" class="mb-4">
                <div class="mb-3">
                    <label for="test_value" class="form-label">Test Value for Avg. Salary</label>
                    <input type="text" class="form-control" id="test_value" name="test_value" value="$50,000 - $80,000 per year">
                </div>
                
                <button type="submit" class="btn btn-primary">Test Update</button>
            </form>
            
            <form action="index.php?page=debug-major-update&id=<?php echo $major_id; ?>" method="POST">
                <input type="hidden" name="fix_column" value="1">
                <button type="submit" class="btn btn-warning">Fix Column Type</button>
            </form>
        </div>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">Fix Instructions</h6>
        </div>
        <div class="card-body">
            <ol>
                <li>Click "Fix Column Type" to ensure the avg_salary column is VARCHAR(100)</li>
                <li>Enter a test value in the form above and click "Test Update" to verify it works</li>
                <li>If the test is successful, return to the Edit Major page and try updating again</li>
                <li>If issues persist, check for any form validation or JavaScript that might be interfering</li>
            </ol>
            
            <p>If you need to manually fix the database, run this SQL query:</p>
            <pre class="bg-light p-3">ALTER TABLE popular_majors MODIFY COLUMN avg_salary VARCHAR(100);</pre>
        </div>
    </div>
</div>
