<?php
// Include database connection
require_once 'config/database.php';

// Function to check if a column exists in a table
function columnExists($pdo, $table, $column) {
    $stmt = $pdo->prepare("SHOW COLUMNS FROM `$table` LIKE :column");
    $stmt->execute(['column' => $column]);
    return $stmt->rowCount() > 0;
}

// Function to add a column if it doesn't exist
function addColumnIfNotExists($pdo, $table, $column, $definition) {
    if (!columnExists($pdo, $table, $column)) {
        $sql = "ALTER TABLE `$table` ADD COLUMN `$column` $definition";
        $pdo->exec($sql);
        return true;
    }
    return false;
}

// Check if announcements table exists
$tableExists = $pdo->query("SHOW TABLES LIKE 'announcements'")->rowCount() > 0;

if ($tableExists) {
    echo "<h3>Updating announcements table structure...</h3>";
    
    // Add multilingual columns if they don't exist
    $columnsAdded = 0;
    
    if (addColumnIfNotExists($pdo, 'announcements', 'title_en', 'VARCHAR(255) NOT NULL DEFAULT ""')) {
        echo "Added title_en column<br>";
        $columnsAdded++;
    }
    
    if (addColumnIfNotExists($pdo, 'announcements', 'title_kh', 'VARCHAR(255) NOT NULL DEFAULT ""')) {
        echo "Added title_kh column<br>";
        $columnsAdded++;
    }
    
    if (addColumnIfNotExists($pdo, 'announcements', 'content_en', 'TEXT')) {
        echo "Added content_en column<br>";
        $columnsAdded++;
    }
    
    if (addColumnIfNotExists($pdo, 'announcements', 'content_kh', 'TEXT')) {
        echo "Added content_kh column<br>";
        $columnsAdded++;
    }
    
    // Update existing records to copy title to title_en and title_kh if they're empty
    if ($columnsAdded > 0) {
        $pdo->exec("UPDATE announcements 
                    SET title_en = title, title_kh = title 
                    WHERE (title_en = '' OR title_en IS NULL) AND title IS NOT NULL");
        
        $pdo->exec("UPDATE announcements 
                    SET content_en = content, content_kh = content 
                    WHERE (content_en = '' OR content_en IS NULL) AND content IS NOT NULL");
        
        echo "<p>Updated existing records with multilingual content.</p>";
    }
    
    if ($columnsAdded == 0) {
        echo "<p>All required columns already exist. No changes needed.</p>";
    } else {
        echo "<p>Successfully added $columnsAdded columns to the announcements table.</p>";
    }
} else {
    // Create the announcements table with multilingual support
    $sql = "CREATE TABLE announcements (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        image_url VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        is_active TINYINT(1) DEFAULT 1,
        title_en VARCHAR(255) NOT NULL DEFAULT '',
        title_kh VARCHAR(255) NOT NULL DEFAULT '',
        content_en TEXT,
        content_kh TEXT
    )";
    
    $pdo->exec($sql);
    echo "<h3>Created announcements table with multilingual support.</h3>";
}

echo "<p>Database update completed. <a href='index.php'>Return to homepage</a></p>";
?>
