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
    $institutions = trim($_POST['institutions'] ?? '');
    $skills_gained = trim($_POST['skills_gained'] ?? '');
    $display_order = (int)($_POST['display_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // New fields
    $avg_salary = trim($_POST['avg_salary'] ?? '');
    $duration = trim($_POST['duration'] ?? '');
    $about_major = trim($_POST['about_major'] ?? '');
    $career_opportunities = trim($_POST['career_opportunities'] ?? '');
    
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
                $image_path = $target_file;
            } else {
                $errors[] = "Failed to upload image";
            }
        }
    }
    
    // If no errors, insert into database
    if (empty($errors)) {
        try {
            // Check which columns exist in the table
            $columns = [];
            $stmt = $pdo->query("DESCRIBE popular_majors");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $columns[] = $row['Field'];
            }
            
            // Build column and value lists based on existing columns
            $column_list = "title, description";
            $value_list = ":title, :description";
            $params = [
                ':title' => $title,
                ':description' => $description
            ];
            
            // Add optional columns if they exist in the table
            if (in_array('title_kh', $columns)) {
                $column_list .= ", title_kh";
                $value_list .= ", :title_kh";
                $params[':title_kh'] = $title_kh;
            }
            
            if (in_array('description_kh', $columns)) {
                $column_list .= ", description_kh";
                $value_list .= ", :description_kh";
                $params[':description_kh'] = $description_kh;
            }
            
            if (in_array('institutions', $columns)) {
                $column_list .= ", institutions";
                $value_list .= ", :institutions";
                $params[':institutions'] = $institutions;
            }
            
            if (in_array('skills_gained', $columns)) {
                $column_list .= ", skills_gained";
                $value_list .= ", :skills_gained";
                $params[':skills_gained'] = $skills_gained;
            }
            
            if (in_array('image_path', $columns)) {
                $column_list .= ", image_path";
                $value_list .= ", :image_path";
                $params[':image_path'] = $image_path;
            }
            
            if (in_array('is_active', $columns)) {
                $column_list .= ", is_active";
                $value_list .= ", :is_active";
                $params[':is_active'] = $is_active;
            }
            
            if (in_array('display_order', $columns)) {
                $column_list .= ", display_order";
                $value_list .= ", :display_order";
                $params[':display_order'] = $display_order;
            }
            
            // Add new fields if they exist in the table
            if (in_array('avg_salary', $columns)) {
                $column_list .= ", avg_salary";
                $value_list .= ", :avg_salary";
                $params[':avg_salary'] = $avg_salary;
            }
            
            if (in_array('duration', $columns)) {
                $column_list .= ", duration";
                $value_list .= ", :duration";
                $params[':duration'] = $duration;
            }
            
            if (in_array('about_major', $columns)) {
                $column_list .= ", about_major";
                $value_list .= ", :about_major";
                $params[':about_major'] = $about_major;
            }
            
            if (in_array('career_opportunities', $columns)) {
                $column_list .= ", career_opportunities";
                $value_list .= ", :career_opportunities";
                $params[':career_opportunities'] = $career_opportunities;
            }
            
            $sql = "INSERT INTO popular_majors ($column_list) VALUES ($value_list)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            $_SESSION['message'] = "Major added successfully";
            $_SESSION['message_type'] = "success";
            header('Location: index.php?page=manage-popular-majors');
            exit;
        } catch(PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
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
$new_columns = ['avg_salary', 'duration', 'about_major', 'career_opportunities'];
$missing_columns = array_diff($new_columns, $columns);
$needs_update = !empty($missing_columns);
?>

<div class="container-fluid py-4">
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add Popular Major</h1>
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
            <form action="index.php?page=add-popular-major" method="POST" enctype="multipart/form-data">
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
                
                <!-- New fields -->
                <?php if (in_array('about_major', $columns)): ?>
                <div class="mb-3">
                    <label for="about_major" class="form-label">About This Major</label>
                    <textarea class="form-control" id="about_major" name="about_major" rows="4"><?php echo $about_major ?? ''; ?></textarea>
                    <small class="text-muted">Provide detailed information about what students will learn in this major</small>
                </div>
                <?php endif; ?>
                
                <div class="row">
                    <?php if (in_array('avg_salary', $columns)): ?>
                    <div class="col-md-6 mb-3">
                        <label for="avg_salary" class="form-label">Average Salary</label>
                        <input type="text" class="form-control" id="avg_salary" name="avg_salary" value="<?php echo $avg_salary ?? ''; ?>">
                        <small class="text-muted">Example: $45,000 - $75,000 per year</small>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (in_array('duration', $columns)): ?>
                    <div class="col-md-6 mb-3">
                        <label for="duration" class="form-label">Duration</label>
                        <input type="text" class="form-control" id="duration" name="duration" value="<?php echo $duration ?? ''; ?>">
                        <small class="text-muted">Example: 4 years (Bachelor's Degree)</small>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="institutions" class="form-label">Institutions</label>
                        <textarea class="form-control" id="institutions" name="institutions" rows="3"><?php echo $institutions ?? ''; ?></textarea>
                        <small class="text-muted">Enter the institutions that offer this major, separated by commas</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="display_order" class="form-label">Display Order</label>
                        <input type="number" class="form-control" id="display_order" name="display_order" value="<?php echo $display_order ?? 0; ?>" min="0">
                    </div>
                </div>
                
                <?php if (in_array('skills_gained', $columns)): ?>
                <div class="mb-3">
                    <label for="skills_gained" class="form-label">Skills Gained</label>
                    <textarea class="form-control" id="skills_gained" name="skills_gained" rows="3"><?php echo $skills_gained ?? ''; ?></textarea>
                    <small class="text-muted">List key skills students will acquire from this major</small>
                </div>
                <?php endif; ?>
                
                <?php if (in_array('career_opportunities', $columns)): ?>
                <div class="mb-3">
                    <label for="career_opportunities" class="form-label">Career Opportunities</label>
                    <textarea class="form-control" id="career_opportunities" name="career_opportunities" rows="4"><?php echo $career_opportunities ?? ''; ?></textarea>
                    <small class="text-muted">List potential career paths and job opportunities for graduates</small>
                </div>
                <?php endif; ?>
                
                <div class="mb-3">
                    <label for="image" class="form-label">Major Image</label>
                    <input type="file" class="form-control" id="image" name="image">
                    <small class="text-muted">Recommended size: 800x600px. Max file size: 5MB</small>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" <?php echo isset($is_active) && $is_active ? 'checked' : ''; ?> value="1">
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
                
                <button type="submit" class="btn btn-danger">Save Major</button>
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
            
            CKEDITOR.replace('institutions', {
                height: 200,
                toolbar: [
                    ['Bold', 'Italic', 'Underline', 'Strike'],
                    ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'],
                    ['Link', 'Unlink'],
                    ['Source', 'Maximize']
                ]
            });
            
            <?php if (in_array('skills_gained', $columns)): ?>
            CKEDITOR.replace('skills_gained', {
                height: 200,
                toolbar: [
                    ['Bold', 'Italic', 'Underline', 'Strike'],
                    ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'],
                    ['Link', 'Unlink'],
                    ['Source', 'Maximize']
                ]
            });
            <?php endif; ?>
            
            <?php if (in_array('career_opportunities', $columns)): ?>
            CKEDITOR.replace('career_opportunities', {
                height: 200,
                toolbar: [
                    ['Bold', 'Italic', 'Underline', 'Strike'],
                    ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'],
                    ['Link', 'Unlink'],
                    ['Source', 'Maximize']
                ]
            });
            <?php endif; ?>
        }
    });
</script>
