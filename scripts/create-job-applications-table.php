<?php
// Create job applications table script
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration
$config_path = __DIR__ . '/../config/database.php';
if (!file_exists($config_path)) {
    die("❌ Database configuration file not found at: $config_path\n");
}

require_once $config_path;

if (!isset($pdo)) {
    die("❌ Database connection not established. Check your database configuration.\n");
}

try {
    echo "🔄 Creating job applications table...\n\n";
    
    // Drop existing table if it exists
    $pdo->exec("DROP TABLE IF EXISTS job_applications");
    echo "✅ Dropped existing job_applications table (if it existed)\n";
    
    // Create the job_applications table with correct structure
    $sql = "CREATE TABLE job_applications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        job_id INT DEFAULT NULL,
        full_name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(50) NOT NULL,
        telegram VARCHAR(100) NOT NULL,
        portfolio_url TEXT DEFAULT NULL,
        resume_path VARCHAR(500) NOT NULL,
        cover_letter_path VARCHAR(500) DEFAULT NULL,
        application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        status ENUM('pending', 'reviewed', 'shortlisted', 'rejected', 'hired') DEFAULT 'pending',
        notes TEXT DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        
        INDEX idx_job_id (job_id),
        INDEX idx_email (email),
        INDEX idx_status (status),
        INDEX idx_application_date (application_date),
        INDEX idx_full_name (full_name)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($sql);
    
    echo "✅ Job applications table created successfully!\n\n";
    echo "📋 Table structure:\n";
    echo "   - id (Primary Key, Auto Increment)\n";
    echo "   - job_id (Foreign Key, Optional)\n";
    echo "   - full_name (Required)\n";
    echo "   - email (Required)\n";
    echo "   - phone (Required)\n";
    echo "   - telegram (Required)\n";
    echo "   - portfolio_url (Optional)\n";
    echo "   - resume_path (Required)\n";
    echo "   - cover_letter_path (Optional)\n";
    echo "   - application_date (Auto-generated)\n";
    echo "   - status (pending, reviewed, shortlisted, rejected, hired)\n";
    echo "   - notes (Optional)\n";
    echo "   - created_at (Auto-generated)\n";
    echo "   - updated_at (Auto-updated)\n\n";
    
    // Create upload directories if they don't exist
    $upload_dirs = [
        __DIR__ . '/../uploads',
        __DIR__ . '/../uploads/resumes',
        __DIR__ . '/../uploads/cover-letters'
    ];
    
    echo "📁 Creating upload directories...\n";
    foreach ($upload_dirs as $dir) {
        if (!is_dir($dir)) {
            if (mkdir($dir, 0755, true)) {
                echo "✅ Created directory: $dir\n";
            } else {
                echo "❌ Failed to create directory: $dir\n";
            }
        } else {
            echo "✅ Directory already exists: $dir\n";
        }
        
        // Check if directory is writable
        if (is_writable($dir)) {
            echo "✅ Directory is writable: $dir\n";
        } else {
            echo "⚠️  Directory is not writable: $dir (you may need to set permissions)\n";
        }
    }
    
    echo "\n🎉 Setup completed successfully!\n";
    echo "You can now use the job application form.\n";
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
    echo "Error Code: " . $e->getCode() . "\n";
    
    // Provide helpful error messages for common issues
    if (strpos($e->getMessage(), 'Access denied') !== false) {
        echo "\n💡 Tip: Check your database credentials in config/database.php\n";
    } elseif (strpos($e->getMessage(), 'Unknown database') !== false) {
        echo "\n💡 Tip: Make sure your database exists and the name is correct\n";
    } elseif (strpos($e->getMessage(), "Can't connect") !== false) {
        echo "\n💡 Tip: Check if your database server is running and accessible\n";
    }
    
} catch (Exception $e) {
    echo "❌ General Error: " . $e->getMessage() . "\n";
}
?>
