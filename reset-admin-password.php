<?php
// Database configuration - update these with your actual database details
$host = 'localhost';
$dbname = 'maca_cms';
$username = 'root'; // Change if your MySQL username is different
$password = ''; // Change if your MySQL password is different

try {
    // Create PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Admin Password Reset Tool</h2>";
    
    // Admin credentials
    $admin_username = 'Maca@Admin';
    $admin_password = 'Maca@(Admin)#*'; // This is the password that will be set
    $admin_email = 'admin@maca.edu';
    
    // Hash the password with the current PHP version
    $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
    
    echo "<p>Generated new password hash: " . $hashed_password . "</p>";
    
    // Check if admin already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $admin_username);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        // Update existing admin
        $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE username = :username");
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':username', $admin_username);
        $stmt->execute();
        echo "<p style='color:green'>✓ Admin password updated successfully!</p>";
    } else {
        // Create new admin
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role, is_active) VALUES (:username, :password, :email, 'admin', 1)");
        $stmt->bindParam(':username', $admin_username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $admin_email);
        $stmt->execute();
        echo "<p style='color:green'>✓ Admin user created successfully!</p>";
    }
    
    echo "<div style='margin-top: 20px; padding: 10px; background-color: #f8f9fa; border: 1px solid #ddd; border-radius: 5px;'>";
    echo "<h3>Login Credentials</h3>";
    echo "<p><strong>Username:</strong> " . $admin_username . "</p>";
    echo "<p><strong>Password:</strong> " . $admin_password . "</p>";
    echo "<p>You can now log in with these credentials.</p>";
    echo "<a href='index.php?page=login' style='display: inline-block; margin-top: 10px; padding: 8px 16px; background-color: #dc3545; color: white; text-decoration: none; border-radius: 4px;'>Go to Login Page</a>";
    echo "</div>";
    
    echo "<p style='margin-top: 20px; color: #6c757d;'><em>For security, delete this file after successful login.</em></p>";
    
} catch(PDOException $e) {
    die("<p style='color:red'>Database Error: " . $e->getMessage() . "</p>");
}
?>
