<?php
session_start();
require_once 'config/database.php';

echo "<h2>Login Test Tool</h2>";

// Function to sanitize input
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    
    echo "<h3>Testing Login for: $username</h3>";
    
    try {
        // Check if user exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "<p style='color:green'>✓ User found in database</p>";
            
            // Test password verification
            echo "<p>Testing password verification...</p>";
            echo "<p>Stored hash: " . $user['password'] . "</p>";
            
            if (password_verify($password, $user['password'])) {
                echo "<p style='color:green'>✓ Password verification successful!</p>";
                echo "<p>Login should work correctly. If you're still having issues, there might be a problem with the session handling.</p>";
            } else {
                echo "<p style='color:red'>✗ Password verification failed!</p>";
                echo "<p>The password doesn't match the stored hash.</p>";
                
                // Create a new hash for comparison
                $new_hash = password_hash($password, PASSWORD_DEFAULT);
                echo "<p>New hash for '$password': $new_hash</p>";
                
                // Update the password in the database
                echo "<p>Updating password in database...</p>";
                $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE username = :username");
                $stmt->bindParam(':password', $new_hash);
                $stmt->bindParam(':username', $username);
                $stmt->execute();
                echo "<p style='color:green'>✓ Password updated successfully!</p>";
            }
        } else {
            echo "<p style='color:red'>✗ User not found in database!</p>";
            
            // Create the user
            echo "<p>Creating user...</p>";
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $email = $username . '@maca.edu';
            
            $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            echo "<p style='color:green'>✓ User created successfully!</p>";
        }
        
    } catch(PDOException $e) {
        echo "<p style='color:red'>Database Error: " . $e->getMessage() . "</p>";
    }
}
?>

<form method="post" action="">
    <div style="margin-bottom: 10px;">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="admin" required>
    </div>
    <div style="margin-bottom: 10px;">
        <label for="password">Password:</label>
        <input type="text" id="password" name="password" value="admin123" required>
    </div>
    <button type="submit">Test Login</button>
</form>

<hr>
<h3>Manual Login Link</h3>
<p>After running the tests above, try the direct login link:</p>
<a href="index.php?page=login" target="_blank">Go to Login Page</a>
