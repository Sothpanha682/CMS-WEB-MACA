<?php
// Prevent direct access
if (!defined('INCLUDED')) {
    define('INCLUDED', true);
}

// Initialize variables to avoid undefined variable warnings
$full_name = '';
$email = '';
$phone = '';
$telegram = '';
$portfolio_url = '';
$errors = [];
$success = false;

// Get job ID from URL parameter
$job_id = isset($_GET['job_id']) ? (int)$_GET['job_id'] : 0;
$job = null;

// Fetch job details if job_id is provided and PDO connection exists
if ($job_id > 0 && isset($pdo)) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM popular_jobs WHERE id = :id AND is_active = 1");
        $stmt->bindParam(':id', $job_id, PDO::PARAM_INT);
        $stmt->execute();
        $job = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Job fetch error: " . $e->getMessage());
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $telegram = trim($_POST['telegram'] ?? '');
    $portfolio_url = trim($_POST['portfolio_url'] ?? '');
    
    // Validation
    if (empty($full_name)) $errors[] = 'Full name is required';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email address is required';
    if (empty($phone)) $errors[] = 'Phone number is required';
    if (empty($telegram)) $errors[] = 'Telegram number is required';
    
    // Validate portfolio URL if provided
    if (!empty($portfolio_url) && !filter_var($portfolio_url, FILTER_VALIDATE_URL)) {
        $errors[] = 'Please enter a valid portfolio/website URL';
    }
    
    // Handle file uploads
    $resume_path = '';
    $cover_letter_path = '';
    
    // Resume upload (required)
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['pdf', 'doc', 'docx'];
        $file_ext = strtolower(pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION));
        
        if (in_array($file_ext, $allowed_types) && $_FILES['resume']['size'] <= 10485760) { // 10MB limit
            $upload_dir = 'uploads/resumes/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $filename = time() . '_resume_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['resume']['name']);
            $resume_path = $upload_dir . $filename;
            
            if (!move_uploaded_file($_FILES['resume']['tmp_name'], $resume_path)) {
                $errors[] = 'Failed to upload resume';
                $resume_path = '';
            }
        } else {
            $errors[] = 'Resume must be PDF, DOC, or DOCX format and under 10MB';
        }
    } else {
        $errors[] = 'Resume/CV is required';
    }
    
    // Cover letter upload (optional)
    if (isset($_FILES['cover_letter']) && $_FILES['cover_letter']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['pdf', 'doc', 'docx'];
        $file_ext = strtolower(pathinfo($_FILES['cover_letter']['name'], PATHINFO_EXTENSION));
        
        if (in_array($file_ext, $allowed_types) && $_FILES['cover_letter']['size'] <= 10485760) { // 10MB limit
            $upload_dir = 'uploads/cover-letters/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $filename = time() . '_cover_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['cover_letter']['name']);
            $cover_letter_path = $upload_dir . $filename;
            
            if (!move_uploaded_file($_FILES['cover_letter']['tmp_name'], $cover_letter_path)) {
                $errors[] = 'Failed to upload cover letter';
                $cover_letter_path = '';
            }
        } else {
            $errors[] = 'Cover letter must be PDF, DOC, or DOCX format and under 10MB';
        }
    }
    
    // If no errors and PDO connection exists, save to database
    if (empty($errors) && isset($pdo)) {
        try {
            $sql = "INSERT INTO job_applications (
                job_id, full_name, email, phone, telegram, portfolio_url, 
                resume_path, cover_letter_path, application_date, status
            ) VALUES (
                :job_id, :full_name, :email, :phone, :telegram, :portfolio_url,
                :resume_path, :cover_letter_path, NOW(), 'pending'
            )";
            
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([
                ':job_id' => $job_id,
                ':full_name' => $full_name,
                ':email' => $email,
                ':phone' => $phone,
                ':telegram' => $telegram,
                ':portfolio_url' => $portfolio_url,
                ':resume_path' => $resume_path,
                ':cover_letter_path' => $cover_letter_path
            ]);
            
            if ($result) {
                $success = true;
                // Clear form data on success
                $full_name = '';
                $email = '';
                $phone = '';
                $telegram = '';
                $portfolio_url = '';
            } else {
                $errors[] = 'Failed to submit application. Please try again.';
            }
            
        } catch (PDOException $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
            error_log("Database error in job application: " . $e->getMessage());
            
            // Clean up uploaded files if database insert failed
            if (!empty($resume_path) && file_exists($resume_path)) {
                unlink($resume_path);
            }
            if (!empty($cover_letter_path) && file_exists($cover_letter_path)) {
                unlink($cover_letter_path);
            }
        }
    } elseif (empty($errors) && !isset($pdo)) {
        $errors[] = 'Database connection not available. Please try again later.';
    }
}
?>

<?php

require_once 'includes/header.php';

?>

<div class="application-wrapper">
    <!-- Header Section -->
    <div class="application-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="header-content">
                        <h1 class="application-title">
                            <i class="fas fa-briefcase me-3"></i>
                            Job Application
                        </h1>
                        <?php if ($job): ?>
                            <div class="job-details">
                                <h2 class="job-title"><?php echo htmlspecialchars($job['title']); ?></h2>
                                <div class="job-meta">
                                    <?php if (!empty($job['company'])): ?>
                                        <span class="meta-badge">
                                            <i class="fas fa-building"></i>
                                            <?php echo htmlspecialchars($job['company']); ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!empty($job['location'])): ?>
                                        <span class="meta-badge">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?php echo htmlspecialchars($job['location']); ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!empty($job['job_type'])): ?>
                                        <span class="meta-badge">
                                            <i class="fas fa-clock"></i>
                                            <?php echo htmlspecialchars($job['job_type']); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <p class="header-subtitle">Apply for your dream job with us</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-4 text-end">
                    <a href="index.php?page=program/online-recruitment" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Jobs
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-5">
        <?php if ($success): ?>
            <!-- Success Message -->
            <div class="success-container">
                <div class="success-card">
                    <div class="success-animation">
                        <div class="checkmark-circle">
                            <div class="checkmark"></div>
                        </div>
                    </div>
                    <h3>Application Submitted Successfully!</h3>
                    <p>Thank you for your interest in joining our team. We have received your application and will review it carefully. You should receive a confirmation email shortly.</p>
                    <div class="success-actions">
                        <a href="index.php?page=program/online-recruitment" class="btn btn-primary btn-lg">
                            <i class="fas fa-search me-2"></i>
                            Browse More Jobs
                        </a>
                        <a href="index.php" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-home me-2"></i>
                            Go to Homepage
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Application Form -->
            <div class="application-form-container">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="alert-header">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Please correct the following errors:</strong>
                        </div>
                        <ul class="error-list">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="application-form" id="applicationForm">
                    <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
                    
                    <!-- Personal Information Section -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="section-title">
                                <h3>Personal Information</h3>
                                <p>Tell us about yourself</p>
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="full_name" class="form-label">
                                    <i class="fas fa-user me-2"></i>
                                    Full Name <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" id="full_name" name="full_name" 
                                       value="<?php echo htmlspecialchars($full_name); ?>" 
                                       placeholder="Enter your full name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>
                                    Email Address <span class="required">*</span>
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($email); ?>" 
                                       placeholder="your.email@example.com" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone me-2"></i>
                                    Phone Number <span class="required">*</span>
                                </label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($phone); ?>" 
                                       placeholder="+855 12 345 678" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="telegram" class="form-label">
                                    <i class="fab fa-telegram me-2"></i>
                                    Telegram Number <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" id="telegram" name="telegram" 
                                       value="<?php echo htmlspecialchars($telegram); ?>" 
                                       placeholder="@username or +855123456789" required>
                            </div>
                            
                            <div class="form-group full-width">
                                <label for="portfolio_url" class="form-label">
                                    <i class="fas fa-globe me-2"></i>
                                    Portfolio / Website URL <span class="optional">(Optional)</span>
                                </label>
                                <input type="url" class="form-control" id="portfolio_url" name="portfolio_url" 
                                       value="<?php echo htmlspecialchars($portfolio_url); ?>" 
                                       placeholder="https://your-portfolio.com">
                            </div>
                        </div>
                    </div>

                    <!-- Uploads Section -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="section-title">
                                <h3>Document Uploads</h3>
                                <p>Upload your documents</p>
                            </div>
                        </div>
                        
                        <div class="upload-grid">
                            <!-- Resume Upload -->
                            <div class="upload-group">
                                <label class="upload-label">
                                    <i class="fas fa-file-alt me-2"></i>
                                    Resume / CV <span class="required">*</span>
                                </label>
                                <div class="file-upload-area" id="resumeUpload">
                                    <input type="file" class="file-input" id="resume" name="resume" 
                                           accept=".pdf,.doc,.docx" required>
                                    <div class="upload-content">
                                        <div class="upload-icon">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                        </div>
                                        <div class="upload-text">
                                            <h4>Upload Resume/CV</h4>
                                            <p>Drag & drop your file here or <span class="upload-link">browse</span></p>
                                            <small>PDF, DOC, DOCX (Max 10MB)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Cover Letter Upload -->
                            <div class="upload-group">
                                <label class="upload-label">
                                    <i class="fas fa-file-text me-2"></i>
                                    Cover Letter <span class="optional">(Optional)</span>
                                </label>
                                <div class="file-upload-area" id="coverLetterUpload">
                                    <input type="file" class="file-input" id="cover_letter" name="cover_letter" 
                                           accept=".pdf,.doc,.docx">
                                    <div class="upload-content">
                                        <div class="upload-icon">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                        </div>
                                        <div class="upload-text">
                                            <h4>Upload Cover Letter</h4>
                                            <p>Drag & drop your file here or <span class="upload-link">browse</span></p>
                                            <small>PDF, DOC, DOCX (Max 10MB)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Section -->
                    <div class="submit-section">
                        <div class="submit-content">
                            <div class="submit-info">
                                <h4>Ready to Submit Your Application?</h4>
                                <p>Please review your information before submitting. We'll get back to you soon!</p>
                            </div>
                            <div class="submit-actions">
                                <button type="submit" class="btn btn-primary btn-lg submit-btn">
                                    <span class="btn-text">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        Submit Application
                                    </span>
                                    <span class="btn-loading" style="display: none;">
                                        <i class="fas fa-spinner fa-spin me-2"></i>
                                        Submitting...
                                    </span>
                                </button>
                                <button type="reset" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-redo me-2"></i>
                                    Reset Form
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
* {
    font-family: 'Inter', sans-serif;
    box-sizing: border-box;
}

body {
    margin: 0;
    padding: 0;
    background: #f8fafc;
}

.application-wrapper {
    min-height: 100vh;
}

/* Header Styles with #dc3545 color */
.application-header {
    border-radius: 18px;
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    padding: 3rem 3rem;
    position: relative;
    overflow: hidden;
}

.application-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.header-content {
    position: relative;
    z-index: 2;
}

.application-title {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
}

.header-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    margin: 0;
}

.job-details {
    margin-top: 1.5rem;
}

.job-title {
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.job-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

.meta-badge {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Form Container */
.application-form-container {
    max-width: 900px;
    margin: 0 auto;
}

.application-form {
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

/* Form Sections */
.form-section {
    padding: 3rem;
    border-bottom: 1px solid #f1f5f9;
}

.form-section:last-child {
    border-bottom: none;
}

.section-header {
    display: flex;
    align-items: center;
    margin-bottom: 2.5rem;
    text-align: left;
}

.section-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1.5rem;
    flex-shrink: 0;
}

.section-icon i {
    font-size: 1.5rem;
    color: white;
}

.section-title h3 {
    font-size: 1.8rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.section-title p {
    color: #64748b;
    margin: 0;
    font-size: 1rem;
}

/* Form Grid */
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group {
    margin-bottom: 0;
}

.form-label {
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    font-size: 0.95rem;
}

.required {
    color: #dc3545;
    margin-left: 0.25rem;
}

.optional {
    color: #6b7280;
    font-weight: 400;
    margin-left: 0.25rem;
}

.form-control {
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    background-color: #f9fafb;
    width: 100%;
}

.form-control:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.1);
    background-color: white;
    outline: none;
}

.form-control::placeholder {
    color: #9ca3af;
}

/* Upload Styles */
.upload-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
}

.upload-group {
    position: relative;
}

.upload-label {
    font-weight: 500;
    color: #374151;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    font-size: 0.95rem;
}

.file-upload-area {
    position: relative;
    border: 2px dashed #d1d5db;
    border-radius: 16px;
    padding: 2.5rem 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
    background: #f9fafb;
    cursor: pointer;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.file-upload-area:hover {
    border-color: #dc3545;
    background: rgba(220, 53, 69, 0.05);
}

.file-upload-area.dragover {
    border-color: #dc3545;
    background: rgba(220, 53, 69, 0.1);
    transform: scale(1.02);
}

.file-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.upload-content {
    pointer-events: none;
}

.upload-icon {
    font-size: 3rem;
    color: #dc3545;
    margin-bottom: 1rem;
}

.upload-text h4 {
    font-size: 1.2rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.upload-text p {
    color: #6b7280;
    margin-bottom: 0.5rem;
}

.upload-link {
    color: #dc3545;
    font-weight: 500;
}

.upload-text small {
    color: #9ca3af;
    font-size: 0.85rem;
}

/* File Selected State */
.file-selected {
    border-color: #10b981;
    background: rgba(16, 185, 129, 0.05);
}

.file-selected .upload-icon {
    color: #10b981;
}

.file-selected .upload-text h4 {
    color: #10b981;
}

/* Submit Section */
.submit-section {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    padding: 3rem;
    text-align: center;
}

.submit-content {
    max-width: 600px;
    margin: 0 auto;
}

.submit-info h4 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.75rem;
}

.submit-info p {
    color: #64748b;
    margin-bottom: 2.5rem;
    font-size: 1.05rem;
}

.submit-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-lg {
    padding: 1rem 2.5rem;
    font-size: 1.1rem;
    font-weight: 500;
    border-radius: 12px;
    transition: all 0.3s ease;
    border: none;
    position: relative;
    overflow: hidden;
}

.btn-primary {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
    transform: translateY(-2px);
    box-shadow: 0 15px 30px rgba(220, 53, 69, 0.4);
}

.btn-outline-secondary {
    border: 2px solid #e5e7eb;
    color: #6b7280;
    background: white;
}

.btn-outline-secondary:hover {
    background: #f9fafb;
    border-color: #d1d5db;
    color: #374151;
}

.submit-btn {
    min-width: 200px;
}

/* Success Styles */
.success-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 60vh;
}

.success-card {
    background: white;
    border-radius: 20px;
    padding: 3rem;
    text-align: center;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    width: 100%;
}

.success-animation {
    margin-bottom: 2rem;
}

.checkmark-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #dc3545;
    margin: 0 auto 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: scaleIn 0.5s ease-out;
}

.checkmark {
    width: 30px;
    height: 30px;
    border: 3px solid white;
    border-top: none;
    border-right: none;
    transform: rotate(-45deg);
    animation: checkmarkDraw 0.3s ease-out 0.2s both;
}

@keyframes scaleIn {
    from { transform: scale(0); }
    to { transform: scale(1); }
}

@keyframes checkmarkDraw {
    from { 
        width: 0;
        height: 0;
    }
    to { 
        width: 30px;
        height: 15px;
    }
}

.success-card h3 {
    font-size: 1.8rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 1rem;
}

.success-card p {
    color: #64748b;
    margin-bottom: 2rem;
    line-height: 1.6;
    font-size: 1.05rem;
}

.success-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.success-actions .btn-primary {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
}

.success-actions .btn-outline-primary {
    border: 2px solid #dc3545;
    color: #dc3545;
    background: white;
}

.success-actions .btn-outline-primary:hover {
    background: #dc3545;
    color: white;
}

/* Alert Styles */
.alert-danger {
    border: none;
    border-radius: 12px;
    background: rgba(220, 53, 69, 0.1);
    border-left: 4px solid #dc3545;
    margin-bottom: 2rem;
}

.alert-header {
    display: flex;
    align-items: center;
    margin-bottom: 0.75rem;
    color: #dc3545;
}

.error-list {
    margin: 0;
    padding-left: 1.5rem;
    color: #dc3545;
}

.error-list li {
    margin-bottom: 0.25rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .application-title {
        font-size: 2rem;
    }
    
    .job-title {
        font-size: 1.5rem;
    }
    
    .job-meta {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .form-section {
        padding: 2rem 1.5rem;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
    }
    
    .section-icon {
        margin-right: 0;
        margin-bottom: 1rem;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .upload-grid {
        grid-template-columns: 1fr;
    }
    
    .submit-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .btn-lg {
        width: 100%;
        max-width: 300px;
    }
    
    .file-upload-area {
        padding: 2rem 1rem;
        min-height: 150px;
    }
}

@media (max-width: 480px) {
    .application-header {
        padding: 2rem 0;
    }
    
    .container {
        padding: 0 1rem;
    }
    
    .form-section {
        padding: 1.5rem 1rem;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // File upload handling
    const fileInputs = document.querySelectorAll('.file-input');
    
    fileInputs.forEach(input => {
        const uploadArea = input.closest('.file-upload-area');
        
        // File selection
        input.addEventListener('change', function() {
            handleFileSelect(this, uploadArea);
        });
        
        // Drag and drop
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('dragover');
        });
        
        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
        });
        
        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                input.files = files;
                handleFileSelect(input, uploadArea);
            }
        });
    });
    
    function handleFileSelect(input, uploadArea) {
        if (input.files.length > 0) {
            const file = input.files[0];
            const fileName = file.name;
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            
            uploadArea.classList.add('file-selected');
            uploadArea.innerHTML = `
                <div class="upload-content">
                    <div class="upload-icon">
                        <i class="fas fa-file-check"></i>
                    </div>
                    <div class="upload-text">
                        <h4>${fileName}</h4>
                        <p>File size: ${fileSize} MB</p>
                        <small>Click to change file</small>
                    </div>
                </div>
                <input type="file" class="file-input" name="${input.name}" accept="${input.accept}" ${input.required ? 'required' : ''}>
            `;
            
            // Re-attach event listeners to new input
            const newInput = uploadArea.querySelector('.file-input');
            newInput.files = input.files;
            newInput.addEventListener('change', function() {
                handleFileSelect(this, uploadArea);
            });
        }
    }
    
    // Form submission with loading state
    const form = document.getElementById('applicationForm');
    const submitBtn = document.querySelector('.submit-btn');
    
    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');
            
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline-flex';
            submitBtn.disabled = true;
        });
    }
    
    // Form validation
    const requiredInputs = document.querySelectorAll('[required]');
    
    requiredInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.style.borderColor = '#dc3545';
            } else {
                this.style.borderColor = '#e5e7eb';
            }
        });
        
        input.addEventListener('input', function() {
            if (this.value.trim()) {
                this.style.borderColor = '#10b981';
            }
        });
    });
    
    // Email validation
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !emailRegex.test(this.value)) {
                this.style.borderColor = '#dc3545';
            } else if (this.value) {
                this.style.borderColor = '#10b981';
            }
        });
    }
    
    // URL validation
    const urlInput = document.getElementById('portfolio_url');
    if (urlInput) {
        urlInput.addEventListener('blur', function() {
            if (this.value) {
                try {
                    new URL(this.value);
                    this.style.borderColor = '#10b981';
                } catch {
                    this.style.borderColor = '#dc3545';
                }
            }
        });
    }
});
</script>
