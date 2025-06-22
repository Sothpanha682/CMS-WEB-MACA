<?php
// Check if user is logged in
if (!isLoggedIn()) {
    $_SESSION['message'] = "You must be logged in to access this page.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=login');
    exit;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $title_kh = trim($_POST['title_kh'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $description_kh = trim($_POST['description_kh'] ?? '');
    $salary_range = trim($_POST['salary_range'] ?? '');
    $requirements = trim($_POST['requirements'] ?? '');
    $display_order = (int)($_POST['display_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Validate input
    $errors = [];
    if (empty($title)) {
        $errors[] = "Title is required";
    }
    if (empty($description)) {
        $errors[] = "Description is required";
    }
    
    // Process image upload
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/jobs/';
        
        // Create directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $file_name = time() . '_' . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $file_name;
        
        // Check if file is an actual image
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check === false) {
            $errors[] = "File is not an image";
        }
        
        // Check file size (limit to 5MB)
        if ($_FILES['image']['size'] > 5000000) {
            $errors[] = "File is too large (max 5MB)";
        }
        
        // Allow certain file formats
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (!in_array($file_ext, $allowed_types)) {
            $errors[] = "Only JPG, JPEG, PNG, and GIF files are allowed";
        }
        
        // If no errors, upload file
        if (empty($errors)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_path = $target_file;
            } else {
                $errors[] = "Failed to upload image";
            }
        }
    }
    
    // If no errors, insert into database
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO popular_jobs (
                    title, title_kh, description, description_kh, image_path, 
                    salary_range, requirements, is_active, display_order
                ) VALUES (
                    :title, :title_kh, :description, :description_kh, :image_path, 
                    :salary_range, :requirements, :is_active, :display_order
                )
            ");
            
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':title_kh', $title_kh);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':description_kh', $description_kh);
            $stmt->bindParam(':image_path', $image_path);
            $stmt->bindParam(':salary_range', $salary_range);
            $stmt->bindParam(':requirements', $requirements);
            $stmt->bindParam(':is_active', $is_active);
            $stmt->bindParam(':display_order', $display_order);
            
            $stmt->execute();
            
            $_SESSION['message'] = "Job added successfully";
            $_SESSION['message_type'] = "success";
            header('Location: index.php?page=manage-popular-jobs');
            exit;
        } catch(PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>

<div class="container-fluid py-4">
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add Popular Job</h1>
        <a href="index.php?page=manage-popular-jobs" class="btn btn-secondary">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Jobs
        </a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">Job Information</h6>
        </div>
        <div class="card-body">
            <form action="index.php?page=add-popular-job" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">Title (English) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo $title ?? ''; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="title_kh" class="form-label">Title (Khmer)</label>
                        <input type="text" class="form-control" id="title_kh" name="title_kh" value="<?php echo $title_kh ?? ''; ?>">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description (English) <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="description" name="description" rows="4" required><?php echo $description ?? ''; ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="description_kh" class="form-label">Description (Khmer)</label>
                    <textarea class="form-control" id="description_kh" name="description_kh" rows="4"><?php echo $description_kh ?? ''; ?></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="salary_range" class="form-label">Salary Range</label>
                        <input type="text" class="form-control" id="salary_range" name="salary_range" value="<?php echo $salary_range ?? ''; ?>" placeholder="e.g. $50,000 - $80,000">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="display_order" class="form-label">Display Order</label>
                        <input type="number" class="form-control" id="display_order" name="display_order" value="<?php echo $display_order ?? 0; ?>" min="0">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="requirements" class="form-label">Requirements</label>
                    <textarea class="form-control" id="requirements" name="requirements" rows="4"><?php echo $requirements ?? ''; ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="image" class="form-label">Job Image</label>
                    <input type="file" class="form-control" id="image" name="image">
                    <small class="text-muted">Recommended size: 800x600px. Max file size: 5MB</small>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" <?php echo isset($is_active) && $is_active ? 'checked' : ''; ?> value="1">
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
                
                <button type="submit" class="btn btn-danger">Save Job</button>
                <a href="index.php?page=manage-popular-jobs" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<script>
    // Initialize CKEditor for rich text editing
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof CKEDITOR !== 'undefined') {
            CKEDITOR.replace('description', {
                height: 300,
                toolbar: [
                    ['Bold', 'Italic', 'Underline', 'Strike'],
                    ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'],
                    ['Link', 'Unlink'],
                    ['FontSize', 'TextColor', 'BGColor'],
                    ['Source', 'Maximize']
                ]
            });
            
            CKEDITOR.replace('description_kh', {
                height: 300,
                toolbar: [
                    ['Bold', 'Italic', 'Underline', 'Strike'],
                    ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'],
                    ['Link', 'Unlink'],
                    ['FontSize', 'TextColor', 'BGColor'],
                    ['Source', 'Maximize']
                ]
            });
            
            CKEDITOR.replace('requirements', {
                height: 300,
                toolbar: [
                    ['Bold', 'Italic', 'Underline', 'Strike'],
                    ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'],
                    ['Link', 'Unlink'],
                    ['FontSize', 'TextColor', 'BGColor'],
                    ['Source', 'Maximize']
                ]
            });
        }
    });
</script>
