<?php
require_once 'config/database.php';

echo "<h2>Adding Career Tags and Openings Fields</h2>";

try {
    // Read and execute the SQL file
    $sql = file_get_contents('sql/add_career_tags_fields.sql');
    
    // Split by semicolon and execute each statement
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (!empty($statement) && !preg_match('/^\s*--/', $statement)) {
            echo "<p>Executing: " . htmlspecialchars(substr($statement, 0, 100)) . "...</p>";
            $pdo->exec($statement);
        }
    }
    
    echo "<div style='background: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3>✅ Success!</h3>";
    echo "<p>Career tags and openings fields have been added successfully.</p>";
    echo "<p>You can now:</p>";
    echo "<ul>";
    echo "<li>Edit careers to add tags (trending, new, hot)</li>";
    echo "<li>Set the number of job openings</li>";
    echo "<li>Use individual tag checkboxes for better control</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<a href='check-career-fields.php' style='background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>Check Fields Again</a>";
    echo "<a href='index.php?page=manage-popular-career' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Manage Careers</a>";
    
} catch (PDOException $e) {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3>❌ Error!</h3>";
    echo "<p>Error adding fields: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}
?>
