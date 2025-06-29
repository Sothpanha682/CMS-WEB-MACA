<?php

// Prevent direct access
if (!defined('INCLUDED')) {
    define('INCLUDED', true);
}

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    header('Location: index.php?page=login');
    exit;
}

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $company_name = trim($_POST['company_name'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $job_type = trim($_POST['job_type'] ?? '');
    $salary_min = (float)($_POST['salary_min'] ?? 0);
    $salary_max = (float)($_POST['salary_max'] ?? 0);
    $currency = trim($_POST['currency'] ?? 'USD');
    $description = trim($_POST['description'] ?? '');
    $requirements = trim($_POST['requirements'] ?? '');
    $responsibilities = trim($_POST['responsibilities'] ?? '');
    $benefits = trim($_POST['benefits'] ?? '');
    $application_instructions = trim($_POST['application_instructions'] ?? '');
    $application_deadline = trim($_POST['application_deadline'] ?? '');
    $contact_email = trim($_POST['contact_email'] ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_active = 1; // Force new job postings to be active by default

    $logo_path = '';
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $file_name = time() . '_' . uniqid() . '.' . $file_ext; // Use time() and uniqid() for more unique names
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_file)) {
            $logo_path = $target_file;
        } else {
            $error_message = "Failed to upload company logo.";
        }
    }

    if (empty($title) || empty($company_name) || empty($location) || empty($job_type) || empty($description)) {
        $error_message = "Please fill in all required fields: Title, Company Name, Location, Job Type, and Description.";
    } else {
        try {
            $sql = "INSERT INTO job_postings (
                        title, company_name, location, job_type, salary_min, salary_max, currency, 
                        description, requirements, responsibilities, benefits, application_instructions, 
                        application_deadline, contact_email, contact_number, logo_path, is_featured, is_active, created_at
                    ) VALUES (
                        :title, :company_name, :location, :job_type, :salary_min, :salary_max, :currency, 
                        :description, :requirements, :responsibilities, :benefits, :application_instructions, 
                        :application_deadline, :contact_email, :contact_number, :logo_path, :is_featured, :is_active, NOW()
                    )";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':company_name' => $company_name,
                ':location' => $location,
                ':job_type' => $job_type,
                ':salary_min' => $salary_min,
                ':salary_max' => $salary_max,
                ':currency' => $currency,
                ':description' => $description,
                ':requirements' => $requirements,
                ':responsibilities' => $responsibilities,
                ':benefits' => $benefits,
                ':application_instructions' => $application_instructions,
                ':application_deadline' => !empty($application_deadline) ? $application_deadline : null,
                ':contact_email' => $contact_email,
                ':contact_number' => $contact_number,
                ':logo_path' => $logo_path,
                ':is_featured' => $is_featured,
                ':is_active' => $is_active
            ]);

            $success_message = "Job opportunity added successfully!";
            debugLog($success_message, 'Job Add Success');
            // Clear form fields after successful submission
            $_POST = []; 

        } catch (PDOException $e) {
            $error_message = "Error adding job opportunity: " . $e->getMessage();
            debugLog($error_message, 'Job Add Error');
            // If there was a file uploaded, delete it on database error
            if (!empty($logo_path) && file_exists($logo_path)) {
                unlink($logo_path);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Job Opportunity - MACA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
   
        h1 {
            color: #1e293b;
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
        }
        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
            outline: none;
        }
        .btn-primary {
            background-color: #dc3545;
            border-color: #dc3545;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #c82333;
            border-color: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
        }
        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .form-check-input:checked {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .form-check-label {
            color: #374151;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4"><i class="fas fa-plus-circle me-3"></i>Add New Job Opportunity</h1>
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="title" class="form-label">Job Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="company_name" name="company_name" value="<?php echo htmlspecialchars($_POST['company_name'] ?? ''); ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($_POST['location'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="job_type" class="form-label">Job Type <span class="text-danger">*</span></label>
                    <select class="form-select" id="job_type" name="job_type" required>
                        <option value="">Select Job Type</option>
                        <option value="full-time" <?php echo (($_POST['job_type'] ?? '') === 'full-time') ? 'selected' : ''; ?>>Full-time</option>
                        <option value="part-time" <?php echo (($_POST['job_type'] ?? '') === 'part-time') ? 'selected' : ''; ?>>Part-time</option>
                        <option value="contract" <?php echo (($_POST['job_type'] ?? '') === 'contract') ? 'selected' : ''; ?>>Contract</option>
                        <option value="internship" <?php echo (($_POST['job_type'] ?? '') === 'internship') ? 'selected' : ''; ?>>Internship</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="salary_min" class="form-label">Salary Min</label>
                    <input type="number" step="0.01" class="form-control" id="salary_min" name="salary_min" value="<?php echo htmlspecialchars($_POST['salary_min'] ?? ''); ?>">
                </div>
                <div class="col-md-4">
                    <label for="salary_max" class="form-label">Salary Max</label>
                    <input type="number" step="0.01" class="form-control" id="salary_max" name="salary_max" value="<?php echo htmlspecialchars($_POST['salary_max'] ?? ''); ?>">
                </div>
                <div class="col-md-4">
                    <label for="currency" class="form-label">Currency</label>
                    <input type="text" class="form-control" id="currency" name="currency" value="<?php echo htmlspecialchars($_POST['currency'] ?? 'USD'); ?>">
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                <textarea class="form-control" id="description" name="description" rows="5" required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="requirements" class="form-label">Requirements</label>
                <textarea class="form-control" id="requirements" name="requirements" rows="4"><?php echo htmlspecialchars($_POST['requirements'] ?? ''); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="responsibilities" class="form-label">Responsibilities</label>
                <textarea class="form-control" id="responsibilities" name="responsibilities" rows="4"><?php echo htmlspecialchars($_POST['responsibilities'] ?? ''); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="benefits" class="form-label">Benefits</label>
                <textarea class="form-control" id="benefits" name="benefits" rows="4"><?php echo htmlspecialchars($_POST['benefits'] ?? ''); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="application_instructions" class="form-label">Application Instructions</label>
                <textarea class="form-control" id="application_instructions" name="application_instructions" rows="3"><?php echo htmlspecialchars($_POST['application_instructions'] ?? ''); ?></textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="application_deadline" class="form-label">Application Deadline</label>
                    <input type="date" class="form-control" id="application_deadline" name="application_deadline" value="<?php echo htmlspecialchars($_POST['application_deadline'] ?? ''); ?>">
                </div>
                <div class="col-md-6">
                    <label for="contact_email" class="form-label">Contact Email</label>
                    <input type="email" class="form-control" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($_POST['contact_email'] ?? ''); ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="contact_number" class="form-label">Contact Number</label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($_POST['contact_number'] ?? ''); ?>">
                </div>
                <div class="col-md-6">
                    <label for="logo" class="form-label">Company Logo</label>
                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                </input>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" <?php echo (isset($_POST['is_featured']) && $_POST['is_featured'] == '1') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="is_featured">
                            Mark as Featured
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active">
                            Active Job Posting
                        </label>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="index.php?page=program/online-recruitment" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Job Listings
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Add Job Opportunity
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
