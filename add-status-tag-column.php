<?php
require_once 'config/database.php';

echo "<h2>Adding Status Tag Column to Popular Jobs Table</h2>";

try {
    // Read and execute the SQL file
    $sql = file_get_contents('sql/add_status_tag_column.sql');
    
    if ($sql === false) {
        throw new Exception("Could not read SQL file");
    }
    
    // Split SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            echo "<p>Executing: " . htmlspecialchars(substr($statement, 0, 100)) . "...</p>";
            $pdo->exec($statement);
            echo "<p style='color: green;'>✓ Success</p>";
        }
    }
    
    echo "<h3 style='color: green;'>Status tag column added successfully!</h3>";
    echo "<p><a href='index.php?page=edit-popular-career&id=1'>Test Edit Career Page</a></p>";
    echo "<p><a href='index.php?page=dashboard'>Back to Dashboard</a></p>";
    
} catch (Exception $e) {
    echo "<h3 style='color: red;'>Error: " . $e->getMessage() . "</h3>";
    echo "<p>Please check your database connection and try again.</p>";
}
?>
