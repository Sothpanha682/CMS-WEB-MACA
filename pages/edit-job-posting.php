<?php
// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    $_SESSION['message'] = "Access denied. Admin privileges required.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=login');
    exit;
}

// Get job ID
$job_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$job_id) {
    $_SESSION['message'] = "Invalid job ID.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=manage-recruitment');
    exit;
}

// Get job posting data
try {
    $stmt = $pdo->prepare("SELECT * FROM job_postings WHERE id = ?");
    $stmt->execute([$job_id]);
    $job = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$job) {
        $_SESSION['message'] = "Job posting not found.";
        $_SESSION['message_type'] = "danger";
        header('Location: index.php?page=manage-recruitment');
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['message'] = "Database error: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=manage-recruitment');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitize($_POST['title']);
    $company_name = sanitize($_POST['company_name']);
    $location = sanitize($_POST['location']);
    $job_type = sanitize($_POST['job_type']);
    $salary_min = !empty($_POST['salary_min']) ? (float)$_POST['salary_min'] : null;
    $salary_max = !empty($_POST['salary_max']) ? (float)$_POST['salary_max'] : null;
    $currency = sanitize($_POST['currency']);
    $description = $_POST['description']; // Don't sanitize HTML content
    $requirements = $_POST['requirements']; // Don't sanitize HTML content
    $benefits = $_POST['benefits']; // Don't sanitize HTML content
    $application_deadline = !empty($_POST['application_deadline']) ? $_POST['application_deadline'] : null;
    $contact_email = sanitize($_POST['contact_email']);
    $contact_phone = sanitize($_POST['contact_phone']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $logo_path = $job['logo_path'] ?? null; // Keep existing logo path by default, safely access

    // Validation
    $errors = [];
    if (empty($title)) $errors[] = "Job title is required.";
    if (empty($company_name)) $errors[] = "Company name is required.";
    if (empty($location)) $errors[] = "Location is required.";
    if (empty($description)) $errors[] = "Job description is required.";
    if (empty($requirements)) $errors[] = "Job requirements are required.";
    if (empty($contact_email)) $errors[] = "Contact email is required.";
    if (!filter_var($contact_email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid contact email is required.";
    if ($salary_min && $salary_max && $salary_min > $salary_max) $errors[] = "Minimum salary cannot be greater than maximum salary.";

    // Handle logo upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = basename($_FILES['logo']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $max_file_size = 2 * 1024 * 1024; // 2MB

        if (!in_array($file_ext, $allowed_ext)) {
            $errors[] = "Invalid file type. Only JPG, JPEG, PNG, GIF are allowed.";
        }
        if ($_FILES['logo']['size'] > $max_file_size) {
            $errors[] = "File size exceeds 2MB limit.";
        }

        if (empty($errors)) {
            // Delete old logo if exists
            if (!empty($job['logo_path']) && file_exists($job['logo_path'])) {
                unlink($job['logo_path']);
            }
            $new_file_name = time() . '_' . uniqid() . '.' . $file_ext;
            $new_file_path = $upload_dir . $new_file_name;

            if (move_uploaded_file($_FILES['logo']['tmp_name'], $new_file_path)) {
                $logo_path = $new_file_path;
            } else {
                $errors[] = "Failed to upload logo.";
            }
        }
    } elseif (isset($_POST['delete_logo']) && $_POST['delete_logo'] == '1') {
        // Handle logo deletion
        if (!empty($job['logo_path']) && file_exists($job['logo_path'])) {
            unlink($job['logo_path']);
        }
        $logo_path = null; // Set logo_path to null in DB
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                UPDATE job_postings SET 
                    title = ?, company_name = ?, location = ?, job_type = ?, 
                    salary_min = ?, salary_max = ?, currency = ?, description = ?, 
                    requirements = ?, benefits = ?, application_deadline = ?, 
                    contact_email = ?, contact_phone = ?, is_featured = ?, is_active = ?,
                    logo_path = ?, updated_at = CURRENT_TIMESTAMP
                WHERE id = ?
            ");
            
            if ($stmt->execute([
                $title, $company_name, $location, $job_type, $salary_min, $salary_max, 
                $currency, $description, $requirements, $benefits, $application_deadline, 
                $contact_email, $contact_phone, $is_featured, $is_active, $logo_path, $job_id
            ])) {
                $_SESSION['message'] = "Job posting updated successfully!";
                $_SESSION['message_type'] = "success";
                header('Location: index.php?page=manage-recruitment');
                exit;
            } else {
                $errors[] = "Error updating job posting.";
            }
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 text-danger">Edit Job Posting</h1>
                <a href="index.php?page=manage-recruitment-applications" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Recruitment
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

            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Edit Job Posting Details</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Job Title *</label>
                                    <input type="text" class="form-control" id="title" name="title" 
                                           value="<?php echo htmlspecialchars($job['title']); ?>" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="company_name" class="form-label">Company Name *</label>
                                            <input type="text" class="form-control" id="company_name" name="company_name" 
                                                   value="<?php echo htmlspecialchars($job['company_name']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="location" class="form-label">Location *</label>
                                            <input type="text" class="form-control" id="location" name="location" 
                                                   value="<?php echo htmlspecialchars($job['location']); ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="job_type" class="form-label">Job Type</label>
                                            <select class="form-select" id="job_type" name="job_type">
                                                <option value="full-time" <?php echo ($job['job_type'] == 'full-time') ? 'selected' : ''; ?>>Full-time</option>
                                                <option value="part-time" <?php echo ($job['job_type'] == 'part-time') ? 'selected' : ''; ?>>Part-time</option>
                                                <option value="contract" <?php echo ($job['job_type'] == 'contract') ? 'selected' : ''; ?>>Contract</option>
                                                <option value="internship" <?php echo ($job['job_type'] == 'internship') ? 'selected' : ''; ?>>Internship</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="salary_min" class="form-label">Min Salary</label>
                                            <input type="number" class="form-control" id="salary_min" name="salary_min" 
                                                   value="<?php echo $job['salary_min']; ?>" step="0.01" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="salary_max" class="form-label">Max Salary</label>
                                            <input type="number" class="form-control" id="salary_max" name="salary_max" 
                                                   value="<?php echo $job['salary_max']; ?>" step="0.01" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="currency" class="form-label">Currency</label>
                                            <select class="form-select" id="currency" name="currency">
                                                <option value="USD" <?php echo ($job['currency'] == 'USD') ? 'selected' : ''; ?>>USD</option>
                                                <option value="KHR" <?php echo ($job['currency'] == 'KHR') ? 'selected' : ''; ?>>KHR</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Job Description *</label>
                                    <textarea class="form-control" id="description" name="description" rows="6" required><?php echo htmlspecialchars($job['description']); ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="requirements" class="form-label">Requirements *</label>
                                    <textarea class="form-control" id="requirements" name="requirements" rows="6" required><?php echo htmlspecialchars($job['requirements']); ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="benefits" class="form-label">Benefits</label>
                                    <textarea class="form-control" id="benefits" name="benefits" rows="4"><?php echo htmlspecialchars($job['benefits']); ?></textarea>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="mb-0">Contact & Settings</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="contact_email" class="form-label">Contact Email *</label>
                                            <input type="email" class="form-control" id="contact_email" name="contact_email" 
                                                   value="<?php echo htmlspecialchars($job['contact_email']); ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="contact_phone" class="form-label">Contact Phone</label>
                                            <input type="text" class="form-control" id="contact_phone" name="contact_phone" 
                                                   value="<?php echo htmlspecialchars($job['contact_phone']); ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="application_deadline" class="form-label">Application Deadline</label>
                                            <input type="date" class="form-control" id="application_deadline" name="application_deadline" 
                                                   value="<?php echo $job['application_deadline']; ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="logo" class="form-label">Company Logo</label>
                                            <?php if (!empty($job['logo_path'] ?? '')): // Safely check for logo_path ?>
                                                <div class="mb-2">
                                                    <img src="<?php echo htmlspecialchars($job['logo_path']); ?>" alt="Company Logo" class="img-thumbnail" style="max-width: 150px;">
                                                    <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="deleteLogo(<?php echo $job['id']; ?>)">
                                                        <i class="fas fa-trash"></i> Remove Logo
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                            <small class="form-text text-muted">Upload a new logo (max 2MB, JPG, PNG, GIF)</small>
                                        </div>

                                        <hr>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                                                       <?php echo $job['is_featured'] ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="is_featured">
                                                    <i class="fas fa-star text-warning"></i> Featured Job
                                                </label>
                                                <small class="form-text text-muted">Featured jobs appear at the top of listings</small>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                                       <?php echo $job['is_active'] ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="is_active">
                                                    <i class="fas fa-check-circle text-success"></i> Active
                                                </label>
                                                <small class="form-text text-muted">Only active jobs are visible to applicants</small>
                                            </div>
                                        </div>

                                        <div class="alert alert-info">
                                            <small>
                                                <strong>Created:</strong> <?php echo formatDate($job['created_at']); ?><br>
                                                <strong>Last Updated:</strong> <?php echo formatDate($job['updated_at']); ?><br>
                                                <strong>Applications:</strong> <?php echo $job['applications_count']; ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="index.php?page=manage-recruitment" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-save me-1"></i> Update Job Posting
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include CKEditor for rich text editing -->
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize CKEditor for description, requirements, and benefits
    CKEDITOR.replace('description', {
        height: 200,
        toolbar: [
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
            { name: 'links', items: ['Link', 'Unlink'] },
            { name: 'tools', items: ['Maximize'] }
        ]
    });
    
    CKEDITOR.replace('requirements', {
        height: 200,
        toolbar: [
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
            { name: 'links', items: ['Link', 'Unlink'] },
            { name: 'tools', items: ['Maximize'] }
        ]
    });
    
    CKEDITOR.replace('benefits', {
        height: 150,
        toolbar: [
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
            { name: 'links', items: ['Link', 'Unlink'] },
            { name: 'tools', items: ['Maximize'] }
        ]
    });
});

function deleteLogo(jobId) {
    if (confirm('Are you sure you want to remove the company logo?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = ''; // Submits to the same page
        
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'delete_logo';
        actionInput.value = '1';
        form.appendChild(actionInput);
        
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'id';
        idInput.value = jobId;
        form.appendChild(idInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<style>
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.form-check-label {
    font-weight: 500;
}

.bg-light {
    background-color: #f8f9fa !important;
}

.cke_chrome {
    border: 1px solid #ced4da !important;
    border-radius: 0.375rem !important;
}
</style>
