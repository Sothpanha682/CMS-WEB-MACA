<?php
// Check if user is logged in
if (!isLoggedIn()) {
    $_SESSION['message'] = "You must be logged in to access this page.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=login');
    exit;
}

// Check database columns to see what fields are available
$columns = [];
try {
    $stmt = $pdo->query("DESCRIBE popular_jobs");
    while ($row = $stmt->fetch()) {
        $columns[] = $row['Field'];
    }
} catch (PDOException $e) {
    // Table might not exist or might be named differently
    $columns = ['title', 'description', 'image_path', 'salary_range', 'display_order', 'requirements', 'company', 'location', 'job_type', 'benefits'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $salary_range = isset($_POST['salary_range']) ? trim($_POST['salary_range']) : '';
    $display_order = isset($_POST['display_order']) ? (int)$_POST['display_order'] : 0;
    $requirements = isset($_POST['requirements']) ? trim($_POST['requirements']) : '';
    $company = isset($_POST['company']) ? trim($_POST['company']) : '';
    $location = isset($_POST['location']) ? trim($_POST['location']) : '';
    $job_type = isset($_POST['job_type']) ? trim($_POST['job_type']) : '';
    $benefits = isset($_POST['benefits']) ? trim($_POST['benefits']) : '';

    $errors = [];

    if (empty($title)) {
        $errors[] = 'Title is required.';
    }

    if (empty($description)) {
        $errors[] = 'Description is required.';
    }

    // Handle image upload
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Use the uploadFile function from includes/functions.php
        $upload_result = uploadFile($_FILES['image'], 'uploads/careers/', ['jpg', 'jpeg', 'png', 'gif', 'webp'], 5242880);
        
        if ($upload_result['status']) {
            $image_path = $upload_result['path'];
        } else {
            $errors[] = $upload_result['message'];
        }
    } elseif (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Handle other upload errors
        $upload_errors = [
            UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
            UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive.',
            UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.'
        ];
        
        $error_code = $_FILES['image']['error'];
        $errors[] = isset($upload_errors[$error_code]) ? $upload_errors[$error_code] : 'Unknown upload error.';
    }

    if (count($errors) === 0) {
        try {
            // Build SQL query dynamically based on available columns
            $sql = "INSERT INTO popular_jobs (title, description";
            $placeholders = ":title, :description";
            $params = [
                ':title' => $title,
                ':description' => $description
            ];

            // Check for both possible image column names
            if (!empty($image_path)) {
                if (in_array('image_path', $columns)) {
                    $sql .= ", image_path";
                    $placeholders .= ", :image_path";
                    $params[':image_path'] = $image_path;
                } elseif (in_array('image', $columns)) {
                    $sql .= ", image";
                    $placeholders .= ", :image";
                    $params[':image'] = $image_path;
                }
            }

            if (in_array('salary_range', $columns)) {
                $sql .= ", salary_range";
                $placeholders .= ", :salary_range";
                $params[':salary_range'] = $salary_range;
            }

            if (in_array('display_order', $columns)) {
                $sql .= ", display_order";
                $placeholders .= ", :display_order";
                $params[':display_order'] = $display_order;
            }

            if (in_array('requirements', $columns)) {
                $sql .= ", requirements";
                $placeholders .= ", :requirements";
                $params[':requirements'] = $requirements;
            }

            if (in_array('company', $columns)) {
                $sql .= ", company";
                $placeholders .= ", :company";
                $params[':company'] = $company;
            }

            if (in_array('location', $columns)) {
                $sql .= ", location";
                $placeholders .= ", :location";
                $params[':location'] = $location;
            }

            if (in_array('job_type', $columns)) {
                $sql .= ", job_type";
                $placeholders .= ", :job_type";
                $params[':job_type'] = $job_type;
            }

            if (in_array('benefits', $columns)) {
                $sql .= ", benefits";
                $placeholders .= ", :benefits";
                $params[':benefits'] = $benefits;
            }

            // Add created_at if column exists
            if (in_array('created_at', $columns)) {
                $sql .= ", created_at";
                $placeholders .= ", :created_at";
                $params[':created_at'] = date('Y-m-d H:i:s');
            }

            // Add lang if column exists (default to 'en')
            if (in_array('lang', $columns)) {
                $sql .= ", lang";
                $placeholders .= ", :lang";
                $params[':lang'] = $_SESSION['lang'] ?? 'en';
            }

            // Add is_active if column exists (default to 1)
            if (in_array('is_active', $columns)) {
                $sql .= ", is_active";
                $placeholders .= ", :is_active";
                $params[':is_active'] = 1;
            }

            $sql .= ") VALUES (" . $placeholders . ")";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            $_SESSION['message'] = "Career added successfully!";
            $_SESSION['message_type'] = "success";
            header('Location: index.php?page=manage-popular-career');
            exit;

        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
            // If there was an uploaded file and database insert failed, clean up the file
            if (!empty($image_path) && file_exists($image_path)) {
                unlink($image_path);
            }
        }
    }
}
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add New Career</h1>
        <a href="index.php?page=manage-popular-career" class="btn btn-secondary">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Manage Career
        </a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Career Information</h6>
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="title" class="form-label">Career Title *</label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="<?php echo htmlspecialchars($title ?? ''); ?>" required>
                        </div>
                    </div>
                    <?php if (in_array('company', $columns)): ?>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="company" class="form-label">Company</label>
                            <input type="text" class="form-control" id="company" name="company" 
                                   value="<?php echo htmlspecialchars($company ?? ''); ?>" 
                                   placeholder="e.g. ABC Corporation">
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description *</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($description ?? ''); ?></textarea>
                </div>

                <div class="row">
                    <?php if (in_array('salary_range', $columns)): ?>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="salary_range" class="form-label">Salary Range</label>
                            <input type="text" class="form-control" id="salary_range" name="salary_range" 
                                   value="<?php echo htmlspecialchars($salary_range ?? ''); ?>" 
                                   placeholder="e.g. $50,000 - $70,000">
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if (in_array('location', $columns)): ?>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" name="location" 
                                   value="<?php echo htmlspecialchars($location ?? ''); ?>" 
                                   placeholder="e.g. Phnom Penh, Cambodia">
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="row">
                    <?php if (in_array('job_type', $columns)): ?>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="job_type" class="form-label">Job Type</label>
                            <select class="form-control" id="job_type" name="job_type">
                                <option value="">Select Job Type</option>
                                <option value="Full-time" <?php echo ($job_type ?? '') === 'Full-time' ? 'selected' : ''; ?>>Full-time</option>
                                <option value="Part-time" <?php echo ($job_type ?? '') === 'Part-time' ? 'selected' : ''; ?>>Part-time</option>
                                <option value="Contract" <?php echo ($job_type ?? '') === 'Contract' ? 'selected' : ''; ?>>Contract</option>
                                <option value="Freelance" <?php echo ($job_type ?? '') === 'Freelance' ? 'selected' : ''; ?>>Freelance</option>
                                <option value="Internship" <?php echo ($job_type ?? '') === 'Internship' ? 'selected' : ''; ?>>Internship</option>
                            </select>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if (in_array('display_order', $columns)): ?>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="display_order" class="form-label">Display Order</label>
                            <input type="number" class="form-control" id="display_order" name="display_order" 
                                   value="<?php echo htmlspecialchars($display_order ?? '0'); ?>">
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Career Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    <small class="text-muted">Upload an image for this career (JPG, PNG, GIF, WEBP - Max 5MB)</small>
                    <div class="mt-2">
                        <small class="text-info">
                            <i class="fas fa-info-circle"></i> 
                            Recommended image size: 400x300 pixels for best display quality
                        </small>
                    </div>
                </div>

                <?php if (in_array('requirements', $columns)): ?>
                <div class="mb-3">
                    <label for="requirements" class="form-label">Requirements</label>
                    <textarea class="form-control" id="requirements" name="requirements" rows="4"><?php echo htmlspecialchars($requirements ?? ''); ?></textarea>
                    <small class="text-muted">List the qualifications and skills required for this position</small>
                </div>
                <?php endif; ?>

                <?php if (in_array('benefits', $columns)): ?>
                <div class="mb-3">
                    <label for="benefits" class="form-label">Benefits</label>
                    <textarea class="form-control" id="benefits" name="benefits" rows="4"><?php echo htmlspecialchars($benefits ?? ''); ?></textarea>
                    <small class="text-muted">List the benefits and perks offered for this position</small>
                </div>
                <?php endif; ?>

                <div class="d-flex justify-content-between">
                    <a href="index.php?page=manage-popular-career" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Add Career</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('description', {
        height: 200,
        toolbar: [
            ['Bold', 'Italic', 'Underline', 'Strike'],
            ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'],
            ['Link', 'Unlink'],
            ['FontSize', 'TextColor', 'BGColor'],
            ['Source', 'Maximize']
        ]
    });

    <?php if (in_array('requirements', $columns)): ?>
    CKEDITOR.replace('requirements', {
        height: 150,
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
        height: 150,
        toolbar: [
            ['Bold', 'Italic', 'Underline', 'Strike'],
            ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'],
            ['Link', 'Unlink'],
            ['FontSize', 'TextColor', 'BGColor'],
            ['Source', 'Maximize']
        ]
    });
    <?php endif; ?>
</script>
