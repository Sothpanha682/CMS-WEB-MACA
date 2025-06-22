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
    $_SESSION['message'] = "Invalid major ID";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=manage-popular-majors');
    exit;
}

$major_id = (int)$_GET['id'];

// Fetch major information
try {
    $stmt = $pdo->prepare("SELECT * FROM popular_majors WHERE id = :id");
    $stmt->bindParam(':id', $major_id);
    $stmt->execute();
    $major = $stmt->fetch();
    
    if (!$major) {
        $_SESSION['message'] = "Major not found";
        $_SESSION['message_type'] = "danger";
        header('Location: index.php?page=manage-popular-majors');
        exit;
    }
} catch(PDOException $e) {
    $_SESSION['message'] = "Database error: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=manage-popular-majors');
    exit;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug: Log the POST data
    error_log("POST data: " . print_r($_POST, true));
    
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $display_order = (int)($_POST['display_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Optional fields - check if they exist in the database
    $title_kh = isset($_POST['title_kh']) ? trim($_POST['title_kh']) : null;
    $description_kh = isset($_POST['description_kh']) ? trim($_POST['description_kh']) : null;
    $institutions = isset($_POST['institutions']) ? trim($_POST['institutions']) : null;
    
    // Get skills_gained directly from POST without trimming (to preserve formatting)
    $skills_gained = isset($_POST['skills_gained']) ? $_POST['skills_gained'] : null;
    
    // New fields
    $avg_salary = isset($_POST['avg_salary']) ? trim($_POST['avg_salary']) : null;
    $duration = isset($_POST['duration']) ? trim($_POST['duration']) : null;
    $about_major = isset($_POST['about_major']) ? trim($_POST['about_major']) : null;
    $career_opportunities = isset($_POST['career_opportunities']) ? trim($_POST['career_opportunities']) : null;
    
    // Validate input
    $errors = [];
    if (empty($title)) {
        $errors[] = "Title is required";
    }
    if (empty($description)) {
        $errors[] = "Description is required";
    }
    
    // Process image upload
    $image_path = $major['image_path'] ?? '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/majors/';
        
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
                if (!empty($major['image_path']) && file_exists($major['image_path'])) {
                    unlink($major['image_path']);
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
            $stmt = $pdo->query("DESCRIBE popular_majors");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $columns[] = $row['Field'];
            }
            
            // Build SQL query based on existing columns
            $sql = "UPDATE popular_majors SET title = :title, description = :description";
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
            
            if (in_array('institutions', $columns)) {
                $sql .= ", institutions = :institutions";
                $params[':institutions'] = $institutions;
            }
            
            if (in_array('skills_gained', $columns)) {
                $sql .= ", skills_gained = :skills_gained";
                $params[':skills_gained'] = $skills_gained;
                // Debug: Log the skills_gained value
                error_log("Skills Gained value: " . $skills_gained);
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
            
            // Add new fields if they exist in the table
            if (in_array('avg_salary', $columns)) {
                $sql .= ", avg_salary = :avg_salary";
                $params[':avg_salary'] = $avg_salary;
            }
            
            if (in_array('duration', $columns)) {
                $sql .= ", duration = :duration";
                $params[':duration'] = $duration;
            }
            
            if (in_array('about_major', $columns)) {
                $sql .= ", about_major = :about_major";
                $params[':about_major'] = $about_major;
            }
            
            if (in_array('career_opportunities', $columns)) {
                $sql .= ", career_opportunities = :career_opportunities";
                $params[':career_opportunities'] = $career_opportunities;
            }
            
            $sql .= " WHERE id = :id";
            $params[':id'] = $major_id;
            
            // Debug: Log the SQL query
            error_log("SQL Query: " . $sql);
            error_log("Params: " . print_r($params, true));
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            $_SESSION['message'] = "Major updated successfully";
            $_SESSION['message_type'] = "success";
            header('Location: index.php?page=manage-popular-majors');
            exit;
        } catch(PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
            // Debug: Log the database error
            error_log("Database error: " . $e->getMessage());
        }
    }
}

// Check which columns exist in the table
$columns = [];
try {
    $stmt = $pdo->query("DESCRIBE popular_majors");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $columns[] = $row['Field'];
    }
} catch(PDOException $e) {
    $errors[] = "Database error: " . $e->getMessage();
}

// Check if new columns exist, if not, show a message to run the update script
$new_columns = ['avg_salary', 'duration', 'about_major', 'career_opportunities', 'skills_gained'];
$missing_columns = array_diff($new_columns, $columns);
$needs_update = !empty($missing_columns);

// Check if skills_gained column exists, if not, add it
if (!in_array('skills_gained', $columns)) {
    try {
        $pdo->exec("ALTER TABLE popular_majors ADD COLUMN skills_gained TEXT AFTER institutions");
        $columns[] = 'skills_gained';
        $_SESSION['message'] = "Skills Gained column added to the database. Please refresh the page.";
        $_SESSION['message_type'] = "success";
    } catch(PDOException $e) {
        $errors[] = "Failed to add Skills Gained column: " . $e->getMessage();
    }
}
?>

<div class="container-fluid py-4">
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Popular Major</h1>
        <a href="index.php?page=manage-popular-majors" class="btn btn-secondary">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Majors
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

    <?php if ($needs_update): ?>
        <div class="alert alert-warning">
            <strong>Notice:</strong> Some database columns are missing. Please run the database update script to enable all features.
            <a href="index.php?page=update-major-fields" class="btn btn-sm btn-primary ml-2">Update Database Structure</a>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">Major Information</h6>
        </div>
        <div class="card-body">
            <form action="index.php?page=edit-popular-major&id=<?php echo $major_id; ?>" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">Title (English) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($major['title']); ?>" required>
                    </div>
                    <?php if (in_array('title_kh', $columns)): ?>
                    <div class="col-md-6 mb-3">
                        <label for="title_kh" class="form-label">Title (Khmer)</label>
                        <input type="text" class="form-control" id="title_kh" name="title_kh" value="<?php echo htmlspecialchars($major['title_kh'] ?? ''); ?>">
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description (English) <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($major['description']); ?></textarea>
                </div>
                
                <?php if (in_array('description_kh', $columns)): ?>
                <div class="mb-3">
                    <label for="description_kh" class="form-label">Description (Khmer)</label>
                    <textarea class="form-control" id="description_kh" name="description_kh" rows="4"><?php echo htmlspecialchars($major['description_kh'] ?? ''); ?></textarea>
                </div>
                <?php endif; ?>
                
                <!-- New fields -->
                <?php if (in_array('about_major', $columns)): ?>
                <div class="mb-3">
                    <label for="about_major" class="form-label">About This Major</label>
                    <textarea class="form-control" id="about_major" name="about_major" rows="4"><?php echo htmlspecialchars($major['about_major'] ?? ''); ?></textarea>
                    <small class="text-muted">Provide detailed information about what students will learn in this major</small>
                </div>
                <?php endif; ?>
                
                <div class="row">
                    <?php if (in_array('avg_salary', $columns)): ?>
                    <div class="col-md-6 mb-3">
                        <label for="avg_salary" class="form-label">Average Salary</label>
                        <input type="text" class="form-control" id="avg_salary" name="avg_salary" value="<?php echo htmlspecialchars($major['avg_salary'] ?? ''); ?>">
                        <small class="text-muted">Example: $45,000 - $75,000 per year</small>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (in_array('duration', $columns)): ?>
                    <div class="col-md-6 mb-3">
                        <label for="duration" class="form-label">Duration</label>
                        <input type="text" class="form-control" id="duration" name="duration" value="<?php echo htmlspecialchars($major['duration'] ?? ''); ?>">
                        <small class="text-muted">Example: 4 years (Bachelor's Degree)</small>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="row">
                    <?php if (in_array('institutions', $columns)): ?>
                    <div class="col-md-6 mb-3">
                        <label for="institutions" class="form-label">Institutions</label>
                        <textarea class="form-control" id="institutions" name="institutions" rows="3"><?php echo htmlspecialchars($major['institutions'] ?? ''); ?></textarea>
                        <small class="text-muted">Enter the institutions that offer this major, separated by commas</small>
                    </div>
                    <?php endif; ?>
                    <?php if (in_array('display_order', $columns)): ?>
                    <div class="col-md-6 mb-3">
                        <label for="display_order" class="form-label">Display Order</label>
                        <input type="number" class="form-control" id="display_order" name="display_order" value="<?php echo (int)($major['display_order'] ?? 0); ?>" min="0">
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Skills Gained field - always show this field -->
                <div class="mb-3">
                    <label for="skills_gained" class="form-label">Skills Gained</label>
                    <textarea class="form-control" id="skills_gained" name="skills_gained" rows="4"><?php echo htmlspecialchars($major['skills_gained'] ?? ''); ?></textarea>
                    <small class="text-muted">List key skills students will acquire from this major (e.g., critical thinking, data analysis, communication)</small>
                </div>
                
                <?php if (in_array('career_opportunities', $columns)): ?>
                <div class="mb-3">
                    <label for="career_opportunities" class="form-label">Career Opportunities</label>
                    <textarea class="form-control" id="career_opportunities" name="career_opportunities" rows="4"><?php echo htmlspecialchars($major['career_opportunities'] ?? ''); ?></textarea>
                    <small class="text-muted">List potential career paths and job opportunities for graduates</small>
                </div>
                <?php endif; ?>
                
                <?php if (in_array('image_path', $columns)): ?>
                <div class="mb-3">
                    <label for="image" class="form-label">Major Image</label>
                    <?php if (!empty($major['image_path'])): ?>
                        <div class="mb-2">
                            <img src="<?php echo $major['image_path']; ?>" alt="<?php echo $major['title']; ?>" class="img-thumbnail" style="max-height: 150px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" id="image" name="image">
                    <small class="text-muted">Recommended size: 800x600px. Max file size: 5MB. Leave empty to keep the current image.</small>
                </div>
                <?php endif; ?>
                
                <?php if (in_array('is_active', $columns)): ?>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" <?php echo ($major['is_active'] ?? 1) ? 'checked' : ''; ?> value="1">
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
                <?php endif; ?>
                
                <button type="submit" class="btn btn-danger">Update Major</button>
                <a href="index.php?page=manage-popular-majors" class="btn btn-secondary">Cancel</a>
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
            
            <?php if (in_array('about_major', $columns)): ?>
            CKEDITOR.replace('about_major', {
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
            
            <?php if (in_array('institutions', $columns)): ?>
            CKEDITOR.replace('institutions', {
                height: 200,
                toolbar: [
                    ['Bold', 'Italic', 'Underline', 'Strike'],
                    ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'],
                    ['Link', 'Unlink'],
                    ['Source', 'Maximize']
                ]
            });
            <?php endif; ?>
            
            // Always initialize CKEditor for skills_gained
            CKEDITOR.replace('skills_gained', {
                height: 200,
                toolbar: [
                    ['Bold', 'Italic', 'Underline', 'Strike'],
                    ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'],
                    ['Link', 'Unlink'],
                    ['FontSize', 'TextColor', 'BGColor'],
                    ['Source', 'Maximize']
                ]
            });
            
            <?php if (in_array('career_opportunities', $columns)): ?>
            CKEDITOR.replace('career_opportunities', {
                height: 200,
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
