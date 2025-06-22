<?php
// Check if user is logged in
if (!isLoggedIn()) {
    $_SESSION['message'] = "You must be logged in to access this page.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=login');
    exit;
}

// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = "Invalid career ID";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=manage-popular-career');
    exit;
}

$job_id = (int)$_GET['id'];

// Fetch job information
try {
    $stmt = $pdo->prepare("SELECT * FROM popular_jobs WHERE id = :id");
    $stmt->bindParam(':id', $job_id);
    $stmt->execute();
    $job = $stmt->fetch();
    
    if (!$job) {
        $_SESSION['message'] = "Career not found";
        $_SESSION['message_type'] = "danger";
        header('Location: index.php?page=manage-popular-career');
        exit;
    }
} catch(PDOException $e) {
    $_SESSION['message'] = "Database error: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=manage-popular-career');
    exit;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $display_order = (int)($_POST['display_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Optional fields - check if they exist in the database
    $title_kh = isset($_POST['title_kh']) ? trim($_POST['title_kh']) : null;
    $description_kh = isset($_POST['description_kh']) ? trim($_POST['description_kh']) : null;
    $salary_range = isset($_POST['salary_range']) ? trim($_POST['salary_range']) : null;
    $requirements = isset($_POST['requirements']) ? trim($_POST['requirements']) : null;

    // In the form processing section, after the existing field definitions, add these new fields:
    $company = isset($_POST['company']) ? trim($_POST['company']) : null;
    $location = isset($_POST['location']) ? trim($_POST['location']) : null;
    $job_type = isset($_POST['job_type']) ? trim($_POST['job_type']) : null;
    $benefits = isset($_POST['benefits']) ? trim($_POST['benefits']) : null;

    $openings = (int)($_POST['openings'] ?? 1);

    $status_tag = isset($_POST['status_tag']) ? trim($_POST['status_tag']) : '';
    
    // Validate input
    $errors = [];
    if (empty($title)) {
        $errors[] = "Title is required";
    }
    if (empty($description)) {
        $errors[] = "Description is required";
    }
    
    // Process image upload
    $image_path = $job['image_path'] ?? '';
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
                // If a new image is uploaded, delete the old one
                if (!empty($job['image_path']) && file_exists($job['image_path'])) {
                    unlink($job['image_path']);
                }
                $image_path = $target_file;
            } else {
                $errors[] = "Failed to upload image";
            }
        }
    }
    
    // If no errors, update database
    if (empty($errors)) {
        try {
            // Check which columns exist in the table
            $columns = [];
            $stmt = $pdo->query("DESCRIBE popular_jobs");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $columns[] = $row['Field'];
            }
            
            // Build SQL query based on existing columns
            $sql = "UPDATE popular_jobs SET title = :title, description = :description";
            $params = [
                ':title' => $title,
                ':description' => $description
            ];
            
            // Add optional columns if they exist in the table
            if (in_array('title_kh', $columns)) {
                $sql .= ", title_kh = :title_kh";
                $params[':title_kh'] = $title_kh;
            }
            
            if (in_array('description_kh', $columns)) {
                $sql .= ", description_kh = :description_kh";
                $params[':description_kh'] = $description_kh;
            }
            
            if (in_array('salary_range', $columns)) {
                $sql .= ", salary_range = :salary_range";
                $params[':salary_range'] = $salary_range;
            }
            
            if (in_array('requirements', $columns)) {
                $sql .= ", requirements = :requirements";
                $params[':requirements'] = $requirements;
            }

            // In the SQL query building section, add these new fields:
            if (in_array('company', $columns)) {
                $sql .= ", company = :company";
                $params[':company'] = $company;
            }

            if (in_array('location', $columns)) {
                $sql .= ", location = :location";
                $params[':location'] = $location;
            }

            if (in_array('job_type', $columns)) {
                $sql .= ", job_type = :job_type";
                $params[':job_type'] = $job_type;
            }

            if (in_array('benefits', $columns)) {
                $sql .= ", benefits = :benefits";
                $params[':benefits'] = $benefits;
            }

            if (in_array('openings', $columns)) {
                $sql .= ", openings = :openings";
                $params[':openings'] = $openings;
            }

            if (in_array('status_tag', $columns)) {
                $sql .= ", status_tag = :status_tag";
                $params[':status_tag'] = $status_tag;
            }
            
            if (in_array('image_path', $columns)) {
                $sql .= ", image_path = :image_path";
                $params[':image_path'] = $image_path;
            }
            
            if (in_array('is_active', $columns)) {
                $sql .= ", is_active = :is_active";
                $params[':is_active'] = $is_active;
            }
            
            if (in_array('display_order', $columns)) {
                $sql .= ", display_order = :display_order";
                $params[':display_order'] = $display_order;
            }
            
            $sql .= " WHERE id = :id";
            $params[':id'] = $job_id;
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            $_SESSION['message'] = "Career updated successfully";
            $_SESSION['message_type'] = "success";
            header('Location: index.php?page=manage-popular-career');
            exit;
        } catch(PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}

// Check which columns exist in the table
$columns = [];
try {
    $stmt = $pdo->query("DESCRIBE popular_jobs");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $columns[] = $row['Field'];
    }
} catch(PDOException $e) {
    $errors[] = "Database error: " . $e->getMessage();
}
?>

<div class="container-fluid py-4">
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Popular Career</h1>
        <a href="index.php?page=manage-popular-career" class="btn btn-secondary">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Careers
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

    <?php if (count($columns) < 9): ?>
        <div class="alert alert-warning">
            <strong>Notice:</strong> Some database columns are missing. Please run the database update script to enable all features.
            <a href="index.php?page=check-content-tables" class="btn btn-sm btn-primary ml-2">Update Database Structure</a>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">Career Information</h6>
        </div>
        <div class="card-body">
            <form action="index.php?page=edit-popular-career&id=<?php echo $job_id; ?>" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">Title (English) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($job['title']); ?>" required>
                    </div>
                    <?php if (in_array('title_kh', $columns)): ?>
                    <div class="col-md-6 mb-3">
                        <label for="title_kh" class="form-label">Title (Khmer)</label>
                        <input type="text" class="form-control" id="title_kh" name="title_kh" value="<?php echo htmlspecialchars($job['title_kh'] ?? ''); ?>">
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description (English) <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($job['description']); ?></textarea>
                </div>
                
                <?php if (in_array('description_kh', $columns)): ?>
                <div class="mb-3">
                    <label for="description_kh" class="form-label">Description (Khmer)</label>
                    <textarea class="form-control" id="description_kh" name="description_kh" rows="4"><?php echo htmlspecialchars($job['description_kh'] ?? ''); ?></textarea>
                </div>
                <?php endif; ?>
                
                <div class="row">
                    <?php if (in_array('salary_range', $columns)): ?>
                    <div class="col-md-6 mb-3">
                        <label for="salary_range" class="form-label">Salary Range</label>
                        <input type="text" class="form-control" id="salary_range" name="salary_range" value="<?php echo htmlspecialchars($job['salary_range'] ?? ''); ?>" placeholder="e.g. $50,000 - $80,000">
                    </div>
                    <?php endif; ?>
                    <?php if (in_array('display_order', $columns)): ?>
                    <div class="col-md-6 mb-3">
                        <label for="display_order" class="form-label">Display Order</label>
                        <input type="number" class="form-control" id="display_order" name="display_order" value="<?php echo (int)($job['display_order'] ?? 0); ?>" min="0">
                    </div>
                    <?php endif; ?>
                </div>

                <div class="row">
                    <?php if (in_array('company', $columns)): ?>
                    <div class="col-md-6 mb-3">
                        <label for="company" class="form-label">Company</label>
                        <input type="text" class="form-control" id="company" name="company" value="<?php echo htmlspecialchars($job['company'] ?? ''); ?>" placeholder="e.g. ABC Corporation">
                    </div>
                    <?php endif; ?>
                    <?php if (in_array('location', $columns)): ?>
                    <div class="col-md-6 mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($job['location'] ?? ''); ?>" placeholder="e.g. Phnom Penh, Cambodia">
                    </div>
                    <?php endif; ?>
                </div>

                <div class="row">
                    <?php if (in_array('job_type', $columns)): ?>
                    <div class="col-md-6 mb-3">
                        <label for="job_type" class="form-label">Job Type</label>
                        <select class="form-control" id="job_type" name="job_type">
                            <option value="">Select Job Type</option>
                            <option value="Full-time" <?php echo ($job['job_type'] ?? '') === 'Full-time' ? 'selected' : ''; ?>>Full-time</option>
                            <option value="Part-time" <?php echo ($job['job_type'] ?? '') === 'Part-time' ? 'selected' : ''; ?>>Part-time</option>
                            <option value="Contract" <?php echo ($job['job_type'] ?? '') === 'Contract' ? 'selected' : ''; ?>>Contract</option>
                            <option value="Freelance" <?php echo ($job['job_type'] ?? '') === 'Freelance' ? 'selected' : ''; ?>>Freelance</option>
                            <option value="Internship" <?php echo ($job['job_type'] ?? '') === 'Internship' ? 'selected' : ''; ?>>Internship</option>
                        </select>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="mb-3">
                    <label for="requirements" class="form-label">Requirements</label>
                    <textarea class="form-control" id="requirements" name="requirements" rows="4"><?php echo htmlspecialchars($job['requirements'] ?? ''); ?></textarea>
                    <small class="text-muted">List the qualifications, skills, and experience required for this position.</small>
                </div>

                <?php if (in_array('benefits', $columns)): ?>
                <div class="mb-3">
                    <label for="benefits" class="form-label">Benefits</label>
                    <textarea class="form-control" id="benefits" name="benefits" rows="4"><?php echo htmlspecialchars($job['benefits'] ?? ''); ?></textarea>
                </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="openings" class="form-label">Number of Openings</label>
                        <input type="number" class="form-control" id="openings" name="openings" value="<?php echo (int)($job['openings'] ?? 1); ?>" min="1" max="999">
                        <small class="text-muted">Number of available positions for this job.</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="status_tag" class="form-label">Status Tag</label>
                        <select class="form-control" id="status_tag" name="status_tag">
                            <option value="">No Tag</option>
                            <option value="trending" <?php echo ($job['status_tag'] ?? '') === 'trending' ? 'selected' : ''; ?>>🔥 Trending</option>
                            <option value="new" <?php echo ($job['status_tag'] ?? '') === 'new' ? 'selected' : ''; ?>>✨ New</option>
                            <option value="hot" <?php echo ($job['status_tag'] ?? '') === 'hot' ? 'selected' : ''; ?>>🔥 Hot</option>
                            <option value="urgent" <?php echo ($job['status_tag'] ?? '') === 'urgent' ? 'selected' : ''; ?>>⚡ Urgent</option>
                            <option value="featured" <?php echo ($job['status_tag'] ?? '') === 'featured' ? 'selected' : ''; ?>>⭐ Featured</option>
                        </select>
                        <small class="text-muted">Select a status tag to highlight this job opportunity.</small>
                    </div>
                </div>
                
                <?php if (in_array('image_path', $columns)): ?>
                <div class="mb-3">
                    <label for="image" class="form-label">Career Image</label>
                    <?php if (!empty($job['image_path'])): ?>
                        <div class="mb-2">
                            <img src="<?php echo $job['image_path']; ?>" alt="<?php echo $job['title']; ?>" class="img-thumbnail" style="max-height: 150px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" id="image" name="image">
                    <small class="text-muted">Recommended size: 800x600px. Max file size: 5MB. Leave empty to keep the current image.</small>
                </div>
                <?php endif; ?>
                
                <?php if (in_array('is_active', $columns)): ?>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" <?php echo ($job['is_active'] ?? 1) ? 'checked' : ''; ?> value="1">
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
                <?php endif; ?>
                
                <button type="submit" class="btn btn-danger">Update Career</button>
                <a href="index.php?page=manage-popular-career" class="btn btn-secondary">Cancel</a>
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
            
            <?php if (in_array('description_kh', $columns)): ?>
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
            <?php endif; ?>
            
            <?php if (in_array('requirements', $columns)): ?>
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
            <?php endif; ?>

            <?php if (in_array('benefits', $columns)): ?>
            CKEDITOR.replace('benefits', {
                height: 300,
                toolbar: [
                    ['Bold', 'Italic', 'Underline', 'Strike'],
                    ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'],
                    ['Link', 'Unlink'],
                    ['FontSize', 'TextColor', 'BGColor'],
                    ['Source', 'Maximize']
                ]
            });
            <?php endif; ?>
        }
    });
</script>
