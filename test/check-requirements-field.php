<?php
// Include database configuration
include 'config/database.php';

// Check if the requirements column exists in the popular_jobs table
try {
    // Get existing columns
    $stmt = $pdo->query("DESCRIBE popular_jobs");
    $existing_columns = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $existing_columns[] = $row['Field'];
    }
    
    // Check if requirements column exists
    if (!in_array('requirements', $existing_columns)) {
        // Add the requirements column
        $pdo->exec("ALTER TABLE popular_jobs ADD COLUMN requirements TEXT NULL");
        echo "<div class='alert alert-success'>Added 'requirements' column to popular_jobs table.</div>";
    } else {
        echo "<div class='alert alert-info'>The 'requirements' column already exists in the popular_jobs table.</div>";
    }
    
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Database error: " . $e->getMessage() . "</div>";
}

// Redirect back to the edit page
echo "<script>setTimeout(function() { window.location.href = 'index.php?page=edit-popular-career&id=" . ($_GET['id'] ?? '1') . "'; }, 3000);</script>";
echo "<p>Redirecting back to edit page in 3 seconds... <a href='index.php?page=edit-popular-career&id=" . ($_GET['id'] ?? '1') . "'>Click here</a> if you are not redirected automatically.</p>";
?>

