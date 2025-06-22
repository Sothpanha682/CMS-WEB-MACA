<?php
// Check if this file is included through index.php
if (!defined('INCLUDED')) {
    // If accessed directly, redirect to the homepage
    header('Location: ../index.php');
    exit;
}

// Get current language
$currentLang = getCurrentLanguage();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;
    
    // Validate input
    $errors = [];
    
    if (empty($username)) {
        $errors[] = $currentLang == 'en' ? 'Username is required' : 'ឈ្មោះអ្នកប្រើប្រាស់ត្រូវបានទាមទារ';
    }
    
    if (empty($password)) {
        $errors[] = $currentLang == 'en' ? 'Password is required' : 'ពាក្យសម្ងាត់ត្រូវបានទាមទារ';
    } elseif (strlen($password) < 8) {
        $errors[] = $currentLang == 'en' ? 'Password must be at least 8 characters' : 'ពាក្យសម្ងាត់ត្រូវតែមានយ៉ាងហោចណាស់ 8 តួអក្សរ';
    }
    
    if (empty($email)) {
        $errors[] = $currentLang == 'en' ? 'Email is required' : 'អ៊ីមែលត្រូវបានទាមទារ';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = $currentLang == 'en' ? 'Invalid email format' : 'ទម្រង់អ៊ីមែលមិនត្រឹមត្រូវ';
    }
    
    // Check if username already exists
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $count = $stmt->fetchColumn();
        
        if ($count > 0) {
            $errors[] = $currentLang == 'en' ? 'Username already exists' : 'ឈ្មោះអ្នកប្រើប្រាស់មានរួចហើយ';
        }
    } catch(PDOException $e) {
        $errors[] = $currentLang == 'en' ? 'Database error: ' . $e->getMessage() : 'កំហុសមូលដ្ឋានទិន្នន័យ៖ ' . $e->getMessage();
    }
    
    // If no errors, insert new user
    if (empty($errors)) {
        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = $is_admin ? 'admin' : 'editor';
            
            $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role, is_active, created_at) VALUES (?, ?, ?, ?, 1, NOW())");
            $stmt->execute([$username, $hashed_password, $email, $role]);
            
            $_SESSION['message'] = $currentLang == 'en' ? 'User added successfully' : 'អ្នកប្រើប្រាស់ត្រូវបានបន្ថែមដោយជោគជ័យ';
            $_SESSION['message_type'] = 'success';
            
            // Use JavaScript redirect instead of header()
            echo "<script>window.location.href = 'index.php?page=manage-users';</script>";
            exit;
        } catch(PDOException $e) {
            $errors[] = $currentLang == 'en' ? 'Database error: ' . $e->getMessage() : 'កំហុសមូលដ្ឋានទិន្នន័យ៖ ' . $e->getMessage();
        }
    }
}

// Get all users
try {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll();
} catch(PDOException $e) {
    $error_message = $currentLang == 'en' ? 'Error fetching users: ' . $e->getMessage() : 'កំហុសក្នុងការទាញយកអ្នកប្រើប្រាស់៖ ' . $e->getMessage();
}
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 mb-0"><?php echo $currentLang == 'en' ? 'Manage Users' : 'គ្រប់គ្រងអ្នកប្រើប្រាស់'; ?></h1>
        <a href="index.php?page=dashboard" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> <?php echo $currentLang == 'en' ? 'Back to Dashboard' : 'ត្រឡប់ទៅផ្ទាំងគ្រប់គ្រង'; ?>
        </a>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><?php echo $currentLang == 'en' ? 'Add New User' : 'បន្ថែមអ្នកប្រើប្រាស់ថ្មី'; ?></h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="index.php?page=manage-users">
                        <div class="mb-3">
                            <label for="username" class="form-label"><?php echo $currentLang == 'en' ? 'Username' : 'ឈ្មោះអ្នកប្រើប្រាស់'; ?> *</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label"><?php echo $currentLang == 'en' ? 'Email' : 'អ៊ីមែល'; ?> *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label"><?php echo $currentLang == 'en' ? 'Password' : 'ពាក្យសម្ងាត់'; ?> *</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="form-text"><?php echo $currentLang == 'en' ? 'Minimum 8 characters' : 'យ៉ាងហោចណាស់ 8 តួអក្សរ'; ?></div>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_admin" name="is_admin">
                            <label class="form-check-label" for="is_admin">
                                <?php echo $currentLang == 'en' ? 'Administrator privileges' : 'សិទ្ធិអ្នកគ្រប់គ្រង'; ?>
                            </label>
                        </div>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-user-plus me-1"></i> <?php echo $currentLang == 'en' ? 'Add User' : 'បន្ថែមអ្នកប្រើប្រាស់'; ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><?php echo $currentLang == 'en' ? 'User List' : 'បញ្ជីអ្នកប្រើប្រាស់'; ?></h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php else: ?>
                        <?php if (count($users) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th><?php echo $currentLang == 'en' ? 'Username' : 'ឈ្មោះអ្នកប្រើប្រាស់'; ?></th>
                                            <th><?php echo $currentLang == 'en' ? 'Email' : 'អ៊ីមែល'; ?></th>
                                            <th><?php echo $currentLang == 'en' ? 'Role' : 'តួនាទី'; ?></th>
                                            <th><?php echo $currentLang == 'en' ? 'Created' : 'បានបង្កើត'; ?></th>
                                            <th><?php echo $currentLang == 'en' ? 'Actions' : 'សកម្មភាព'; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $user): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                                <td>
                                                    <?php if (isset($user['role']) && $user['role'] == 'admin'): ?>
                                                        <span class="badge bg-danger"><?php echo $currentLang == 'en' ? 'Admin' : 'អ្នកគ្រប់គ្រង'; ?></span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary"><?php echo $currentLang == 'en' ? 'Editor' : 'អ្នកកែសម្រួល'; ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo formatDate($user['created_at']); ?></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="index.php?action=toggle-admin-status&id=<?php echo $user['id']; ?>" class="btn btn-outline-primary" title="<?php echo $currentLang == 'en' ? 'Toggle Admin Status' : 'ប្ដូរស្ថានភាពអ្នកគ្រប់គ្រង'; ?>">
                                                            <i class="fas <?php echo (isset($user['role']) && $user['role'] == 'admin') ? 'fa-user-minus' : 'fa-user-plus'; ?>"></i>
                                                        </a>
                                                        <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username']); ?>')" class="btn btn-outline-danger" title="<?php echo $currentLang == 'en' ? 'Delete User' : 'លុបអ្នកប្រើប្រាស់'; ?>">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- JavaScript for delete confirmation -->
                            <script>
                                function confirmDelete(userId, username) {
                                    const confirmMessage = '<?php echo $currentLang == 'en' ? 'Are you sure you want to delete the user' : 'តើអ្នកប្រាកដថាចង់លុបអ្នកប្រើប្រាស់'; ?> ' + username + '?';
                                    
                                    if (confirm(confirmMessage)) {
                                        window.location.href = 'index.php?action=delete-user&id=' + userId;
                                    }
                                }
                            </script>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <?php echo $currentLang == 'en' ? 'No users found.' : 'រកមិនឃើញអ្នកប្រើប្រាស់ទេ។'; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
