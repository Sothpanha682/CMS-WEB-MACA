<?php
// Include database configuration
include 'config/database.php';

// Define the columns to add
$columns = [
    'company' => 'VARCHAR(255) NULL',
    'location' => 'VARCHAR(255) NULL',
    'job_type' => 'VARCHAR(50) NULL',
    'benefits' => 'TEXT NULL'
];

// Check if columns exist and add them if they don't
$added_columns = [];
$errors = [];

try {
    // Get existing columns
    $stmt = $pdo->query("DESCRIBE popular_jobs");
    $existing_columns = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $existing_columns[] = $row['Field'];
    }
    
    // Add missing columns
    foreach ($columns as $column => $definition) {
        if (!in_array($column, $existing_columns)) {
            try {
                $pdo->exec("ALTER TABLE popular_jobs ADD COLUMN $column $definition");
                $added_columns[] = $column;
            } catch (PDOException $e) {
                $errors[] = "Error adding column $column: " . $e->getMessage();
            }
        }
    }
    
    // Display results
    if (!empty($added_columns)) {
        echo "<div class='alert alert-success'>Added the following columns to popular_jobs table: " . implode(', ', $added_columns) . "</div>";
    } else if (empty($errors)) {
        echo "<div class='alert alert-info'>All required columns already exist in the popular_jobs table.</div>";
    }
    
    if (!empty($errors)) {
        echo "<div class='alert alert-danger'><ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul></div>";
    }
    
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Database error: " . $e->getMessage() . "</div>";
}

// Redirect back to the dashboard after 5 seconds
echo "<script>setTimeout(function() { window.location.href = 'index.php?page=dashboard'; }, 5000);</script>";
echo "<p>Redirecting to dashboard in 5 seconds... <a href='index.php?page=dashboard'>Click here</a> if you are not redirected automatically.</p>";
?>
