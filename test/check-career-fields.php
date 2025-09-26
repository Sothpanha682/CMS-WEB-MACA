<?php
require_once 'config/database.php';

echo "<h2>Popular Jobs Table Structure</h2>";

try {
    // Check current table structure
    $stmt = $pdo->query("DESCRIBE popular_jobs");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Current Columns:</h3>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    $existing_columns = [];
    foreach ($columns as $column) {
        $existing_columns[] = $column['Field'];
        echo "<tr>";
        echo "<td>" . htmlspecialchars($column['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Default'] ?? 'NULL') . "</td>";
        echo "<td>" . htmlspecialchars($column['Extra']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Check for missing fields
    $required_fields = ['tags', 'openings', 'is_trending', 'is_new', 'is_hot'];
    $missing_fields = [];
    
    foreach ($required_fields as $field) {
        if (!in_array($field, $existing_columns)) {
            $missing_fields[] = $field;
        }
    }
    
    if (!empty($missing_fields)) {
        echo "<h3 style='color: red;'>Missing Fields:</h3>";
        echo "<ul>";
        foreach ($missing_fields as $field) {
            echo "<li style='color: red;'>" . htmlspecialchars($field) . "</li>";
        }
        echo "</ul>";
        
        echo "<p><strong>You need to run the database update script to add these fields.</strong></p>";
        echo "<a href='add-career-tags-fields.php' style='background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Add Missing Fields</a>";
    } else {
        echo "<h3 style='color: green;'>All required fields are present!</h3>";
    }
    
    // Show sample data
    echo "<h3>Sample Data (first 3 records):</h3>";
    $stmt = $pdo->query("SELECT * FROM popular_jobs LIMIT 3");
    $sample_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($sample_data) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%; font-size: 12px;'>";
        echo "<tr>";
        foreach (array_keys($sample_data[0]) as $header) {
            echo "<th>" . htmlspecialchars($header) . "</th>";
        }
        echo "</tr>";
        
        foreach ($sample_data as $row) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
