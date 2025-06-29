<?php
// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    $_SESSION['message'] = "Access denied. Admin privileges required.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=login');
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

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO job_postings (
                    title, company_name, location, job_type, salary_min, salary_max, currency,
                    description, requirements, benefits, application_deadline, contact_email, 
                    contact_phone, is_featured, is_active
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            if ($stmt->execute([
                $title, $company_name, $location, $job_type, $salary_min, $salary_max, $currency,
                $description, $requirements, $benefits, $application_deadline, $contact_email,
                $contact_phone, $is_featured, $is_active
            ])) {
                $_SESSION['message'] = "Job posting created successfully!";
                $_SESSION['message_type'] = "success";
                header('Location: index.php?page=manage-recruitment');
                exit;
            } else {
                $errors[] = "Error creating job posting.";
            }
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}

// Get recruitment settings for default values
try {
    $stmt = $pdo->query("SELECT * FROM recruitment_settings LIMIT 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $settings = [];
}
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 text-danger">Add New Job Posting</h1>
                <a href="index.php?page=manage-recruitment" class="btn btn-outline-secondary">
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
                    <h5 class="mb-0">Job Posting Details</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Job Title *</label>
                                    <input type="text" class="form-control" id="title" name="title" 
                                           value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="company_name" class="form-label">Company Name *</label>
                                            <input type="text" class="form-control" id="company_name" name="company_name" 
                                                   value="<?php echo isset($_POST['company_name']) ? htmlspecialchars($_POST['company_name']) : ($settings['company_name'] ?? 'MACA Education'); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="location" class="form-label">Location *</label>
                                            <input type="text" class="form-control" id="location" name="location" 
                                                   value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>" 
                                                   placeholder="e.g., Phnom Penh, Cambodia" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="job_type" class="form-label">Job Type</label>
                                            <select class="form-select" id="job_type" name="job_type">
                                                <option value="full-time" <?php echo (isset($_POST['job_type']) && $_POST['job_type'] == 'full-time') ? 'selected' : ''; ?>>Full-time</option>
                                                <option value="part-time" <?php echo (isset($_POST['job_type']) && $_POST['job_type'] == 'part-time') ? 'selected' : ''; ?>>Part-time</option>
                                                <option value="contract" <?php echo (isset($_POST['job_type']) && $_POST['job_type'] == 'contract') ? 'selected' : ''; ?>>Contract</option>
                                                <option value="internship" <?php echo (isset($_POST['job_type']) && $_POST['job_type'] == 'internship') ? 'selected' : ''; ?>>Internship</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="salary_min" class="form-label">Min Salary</label>
                                            <input type="number" class="form-control" id="salary_min" name="salary_min" 
                                                   value="<?php echo isset($_POST['salary_min']) ? $_POST['salary_min'] : ''; ?>" 
                                                   step="0.01" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="salary_max" class="form-label">Max Salary</label>
                                            <input type="number" class="form-control" id="salary_max" name="salary_max" 
                                                   value="<?php echo isset($_POST['salary_max']) ? $_POST['salary_max'] : ''; ?>" 
                                                   step="0.01" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="currency" class="form-label">Currency</label>
                                            <select class="form-select" id="currency" name="currency">
                                                <option value="USD" <?php echo (isset($_POST['currency']) && $_POST['currency'] == 'USD') ? 'selected' : ''; ?>>USD</option>
                                                <option value="KHR" <?php echo (isset($_POST['currency']) && $_POST['currency'] == 'KHR') ? 'selected' : ''; ?>>KHR</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Job Description *</label>
                                    <textarea class="form-control" id="description" name="description" rows="6" required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="requirements" class="form-label">Requirements *</label>
                                    <textarea class="form-control" id="requirements" name="requirements" rows="6" required><?php echo isset($_POST['requirements']) ? htmlspecialchars($_POST['requirements']) : ''; ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="benefits" class="form-label">Benefits</label>
                                    <textarea class="form-control" id="benefits" name="benefits" rows="4"><?php echo isset($_POST['benefits']) ? htmlspecialchars($_POST['benefits']) : ''; ?></textarea>
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
                                                   value="<?php echo isset($_POST['contact_email']) ? htmlspecialchars($_POST['contact_email']) : ($settings['hr_email'] ?? ''); ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="contact_phone" class="form-label">Contact Phone</label>
                                            <input type="text" class="form-control" id="contact_phone" name="contact_phone" 
                                                   value="<?php echo isset($_POST['contact_phone']) ? htmlspecialchars($_POST['contact_phone']) : ($settings['hr_phone'] ?? ''); ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="application_deadline" class="form-label">Application Deadline</label>
                                            <input type="date" class="form-control" id="application_deadline" name="application_deadline" 
                                                   value="<?php echo isset($_POST['application_deadline']) ? $_POST['application_deadline'] : ''; ?>">
                                        </div>

                                        <hr>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                                                       <?php echo (isset($_POST['is_featured']) && $_POST['is_featured']) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="is_featured">
                                                    <i class="fas fa-star text-warning"></i> Featured Job
                                                </label>
                                                <small class="form-text text-muted">Featured jobs appear at the top of listings</small>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                                       <?php echo (!isset($_POST['is_active']) || $_POST['is_active']) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="is_active">
                                                    <i class="fas fa-check-circle text-success"></i> Active
                                                </label>
                                                <small class="form-text text-muted">Only active jobs are visible to applicants</small>
                                            </div>
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
                                        <i class="fas fa-save me-1"></i> Create Job Posting
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
