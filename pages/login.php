<?php
// Make sure there's no output before this point
if (!defined('INCLUDED')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

// Process login form submission
$loginError = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitizeInput($_POST['username']); // Changed from sanitize() to sanitizeInput()
    $password = $_POST['password'];
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role']; // Store user role
            $_SESSION['is_admin'] = ($user['role'] === 'admin') ? 1 : 0; // Set is_admin session variable
            $_SESSION['message'] = "Welcome back, " . $user['username'] . "!";
            $_SESSION['message_type'] = "success";
            
            // Store the redirect in session instead of using header()
            $_SESSION['redirect_to'] = "index.php?page=dashboard";
            echo "<script>window.location.href = 'index.php?page=dashboard';</script>";
            exit;
        } else {
            $loginError = '<div class="alert alert-danger">Invalid username or password.</div>';
        }
    } catch(PDOException $e) {
        $loginError = '<div class="alert alert-danger">Login failed: ' . $e->getMessage() . '</div>';
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0"><?php echo getLangText('Admin Login', 'ចូលគណនីអ្នកគ្រប់គ្រង'); ?></h4>
            </div>
            <div class="card-body">
                <?php echo $loginError; ?>
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label"><?php echo getLangText('Username', 'ឈ្មោះអ្នកប្រើប្រាស់'); ?></label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label"><?php echo getLangText('Password', 'ពាក្យសម្ងាត់'); ?></label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-danger"><?php echo getLangText('Login', 'ចូល'); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>
