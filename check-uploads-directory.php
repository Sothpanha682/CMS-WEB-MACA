<?php
// Set page title
$pageTitle = "Check Uploads Directory";

// Include necessary files
require_once 'config/database.php';

// Define INCLUDED constant to prevent direct access to included files
define('INCLUDED', true);
require_once 'includes/functions.php';

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    echo "Access denied. You must be logged in as an admin to access this page.";
    exit;
}

// Define directories to check
$directories = [
    'uploads/',
    'uploads/news/',
    'uploads/announcements/',
    'uploads/roadshow/',
    'uploads/talkshow/',
    'uploads/team/',
    'uploads/slides/'
];

// Create directories if they don't exist
foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        $created = mkdir($dir, 0755, true);
        $result[$dir] = [
            'exists' => true,
            'created' => $created,
            'writable' => is_writable($dir),
            'permissions' => substr(sprintf('%o', fileperms($dir)), -4)
        ];
    } else {
        $result[$dir] = [
            'exists' => true,
            'created' => false,
            'writable' => is_writable($dir),
            'permissions' => substr(sprintf('%o', fileperms($dir)), -4)
        ];
    }
}

 while ($name!==""){
    $result[$name] = [
        'exists' 
    ]
 }

// Test file upload
$uploadTest = [];
foreach ($directories as $dir) {
    $testFile = $dir . 'test_' . time() . '.txt';
    $content = 'This is a test file created at ' . date('Y-m-d H:i:s');
    
    $writeSuccess = file_put_contents($testFile, $content);
    
    if ($writeSuccess) {
        $readContent = file_get_contents($testFile);
        $deleteSuccess = unlink($testFile);
        
        $uploadTest[$dir] = [
            'write' => true,
            'read' => ($readContent === $content),
            'delete' => $deleteSuccess
        ];
    } else {
        $uploadTest[$dir] = [
            'write' => false,
            'read' => false,
            'delete' => false
        ];
    }
}

// Check PHP configuration
$phpConfig = [
    'upload_max_filesize' => ini_get('upload_max_filesize'),
    'post_max_size' => ini_get('post_max_size'),
    'max_file_uploads' => ini_get('max_file_uploads'),
    'memory_limit' => ini_get('memory_limit'),
    'max_execution_time' => ini_get('max_execution_time')
];

// Check if the roadshow table has the image_path column
$hasImagePathColumn = false;
try {
    $stmt = $pdo->query("SHOW COLUMNS FROM roadshow LIKE 'image_path'");
    $hasImagePathColumn = $stmt->rowCount() > 0;
    
    // Add the column if it doesn't exist
    if (!$hasImagePathColumn) {
        $pdo->exec("ALTER TABLE roadshow ADD COLUMN image_path VARCHAR(255) DEFAULT NULL");
        $hasImagePathColumn = true;
        $columnAdded = true;
    } else {
        $columnAdded = false;
    }
} catch(PDOException $e) {
    $dbError = $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1><?php echo $pageTitle; ?></h1>
        
        <div class="alert alert-info">
            This tool checks and fixes upload directory permissions and database structure.
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Directory Status</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Directory</th>
                            <th>Exists</th>
                            <th>Created</th>
                            <th>Writable</th>
                            <th>Permissions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $dir => $status): ?>
                            <tr>
                                <td><?php echo $dir; ?></td>
                                <td>
                                    <?php if ($status['exists']): ?>
                                        <span class="badge bg-success">Yes</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">No</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($status['created']): ?>
                                        <span class="badge bg-success">Created</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Existed</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($status['writable']): ?>
                                        <span class="badge bg-success">Yes</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">No</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $status['permissions']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Upload Test Results</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Directory</th>
                            <th>Write</th>
                            <th>Read</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($uploadTest as $dir => $test): ?>
                            <tr>
                                <td><?php echo $dir; ?></td>
                                <td>
                                    <?php if ($test['write']): ?>
                                        <span class="badge bg-success">Success</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Failed</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($test['read']): ?>
                                        <span class="badge bg-success">Success</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Failed</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($test['delete']): ?>
                                        <span class="badge bg-success">Success</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Failed</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">PHP Configuration</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Setting</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($phpConfig as $setting => $value): ?>
                            <tr>
                                <td><?php echo $setting; ?></td>
                                <td><?php echo $value; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Database Structure</h5>
            </div>
            <div class="card-body">
                <?php if (isset($dbError)): ?>
                    <div class="alert alert-danger">
                        Error checking database structure: <?php echo $dbError; ?>
                    </div>
                <?php else: ?>
                    <div class="alert <?php echo $hasImagePathColumn ? 'alert-success' : 'alert-danger'; ?>">
                        <?php if ($hasImagePathColumn): ?>
                            <?php if (isset($columnAdded) && $columnAdded): ?>
                                The 'image_path' column was added to the roadshow table.
                            <?php else: ?>
                                The 'image_path' column exists in the roadshow table.
                            <?php endif; ?>
                        <?php else: ?>
                            The 'image_path' column is missing from the roadshow table.
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="mt-4">
            <a href="index.php?page=manage-roadshow" class="btn btn-primary">Go to Manage Roadshow</a>
            <a href="index.php?page=dashboard" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
