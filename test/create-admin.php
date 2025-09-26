<?php
// Database configuration
$host = 'localhost';
$dbname = 'maca_cms';
$username = 'root';
$password = '';

try {
    // Create PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Admin credentials
    $admin_username = 'admin';
    $admin_password = 'admin123';
    $admin_email = 'admin@maca.edu';
    
    // Hash the password
    $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
    
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
        echo "Admin password updated successfully!<br>";
    } else {
        // Create new admin
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
        $stmt->bindParam(':username', $admin_username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $admin_email);
        $stmt->execute();
        echo "Admin user created successfully!<br>";
    }
    
    echo "Username: " . $admin_username . "<br>";
    echo "Password: " . $admin_password . "<br>";
    echo "You can now log in with these credentials.";
    
} catch(PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>
