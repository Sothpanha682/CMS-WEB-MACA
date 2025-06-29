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
    $description = sanitize($_POST['description']);
    $short_description = sanitize($_POST['short_description']);
    $instructor_name = sanitize($_POST['instructor_name']);
    $instructor_bio = sanitize($_POST['instructor_bio']);
    $category = sanitize($_POST['category']);
    $level = sanitize($_POST['level']);
    $duration_weeks = (int)$_POST['duration_weeks'];
    $duration_hours = (int)$_POST['duration_hours'];
    $price = (float)$_POST['price'];
    $original_price = (float)$_POST['original_price'];
    $skills_gained = sanitize($_POST['skills_gained']);
    $prerequisites = sanitize($_POST['prerequisites']);
    $course_outline = sanitize($_POST['course_outline']);
    $learning_outcomes = sanitize($_POST['learning_outcomes']);
    $video_url = sanitize($_POST['video_url']);
    $demo_video_url = sanitize($_POST['demo_video_url']);
    $language = sanitize($_POST['language']);
    $subtitles_available = sanitize($_POST['subtitles_available']);
    $certificate_available = isset($_POST['certificate_available']) ? 1 : 0;
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_bestseller = isset($_POST['is_bestseller']) ? 1 : 0;
    $is_new = isset($_POST['is_new']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Handle file uploads
    $course_image = '';
    $instructor_image = '';
    
    if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] == 0) {
        $upload_result = uploadFile($_FILES['course_image'], 'uploads/', ['jpg', 'jpeg', 'png', 'gif']);
        if ($upload_result['status']) {
            $course_image = basename($upload_result['path']);
        } else {
            $_SESSION['message'] = "Course image upload failed: " . $upload_result['message'];
            $_SESSION['message_type'] = "danger";
        }
    }
    
    if (isset($_FILES['instructor_image']) && $_FILES['instructor_image']['error'] == 0) {
        $upload_result = uploadFile($_FILES['instructor_image'], 'uploads/', ['jpg', 'jpeg', 'png', 'gif']);
        if ($upload_result['status']) {
            $instructor_image = basename($upload_result['path']);
        } else {
            $_SESSION['message'] = "Instructor image upload failed: " . $upload_result['message'];
            $_SESSION['message_type'] = "danger";
        }
    }
    
    // Insert course
    try {
        $stmt = $pdo->prepare("INSERT INTO online_courses (
            title, description, short_description, instructor_name, instructor_bio, instructor_image,
            course_image, category, level, duration_weeks, duration_hours, price, original_price,
            skills_gained, prerequisites, course_outline, learning_outcomes, video_url, demo_video_url,
            certificate_available, language, subtitles_available, is_featured, is_bestseller, is_new, is_active
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $title, $description, $short_description, $instructor_name, $instructor_bio, $instructor_image,
            $course_image, $category, $level, $duration_weeks, $duration_hours, $price, $original_price,
            $skills_gained, $prerequisites, $course_outline, $learning_outcomes, $video_url, $demo_video_url,
            $certificate_available, $language, $subtitles_available, $is_featured, $is_bestseller, $is_new, $is_active
        ]);
        
        $_SESSION['message'] = "Course added successfully!";
        $_SESSION['message_type'] = "success";
        header('Location: index.php?page=manage-online-courses');
        exit;
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error adding course: " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }
}

$categories = ['Technology', 'Business', 'Design', 'Marketing', 'Data Science', 'Health', 'Arts', 'Language', 'Personal Development'];
$levels = ['Beginner', 'Intermediate', 'Advanced', 'All Levels'];
$languages = ['English', 'Spanish', 'French', 'German', 'Chinese', 'Japanese', 'Korean'];
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 text-danger">Add New Online Course</h1>
                <a href="index.php?page=manage-online-courses" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Courses
                </a>
            </div>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Course Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Course Title *</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="short_description" class="form-label">Short Description *</label>
                                    <textarea class="form-control" id="short_description" name="short_description" rows="2" required></textarea>
                                    <div class="form-text">Brief description for course cards (max 500 characters)</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Full Description *</label>
                                    <textarea class="form-control" id="description" name="description" rows="6" required></textarea>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="category" class="form-label">Category *</label>
                                        <select class="form-select" id="category" name="category" required>
                                            <option value="">Select Category</option>
                                            <?php foreach ($categories as $cat): ?>
                                                <option value="<?php echo $cat; ?>"><?php echo $cat; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="level" class="form-label">Level *</label>
                                        <select class="form-select" id="level" name="level" required>
                                            <option value="">Select Level</option>
                                            <?php foreach ($levels as $lvl): ?>
                                                <option value="<?php echo $lvl; ?>"><?php echo $lvl; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="duration_weeks" class="form-label">Duration (Weeks)</label>
                                        <input type="number" class="form-control" id="duration_weeks" name="duration_weeks" min="0">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="duration_hours" class="form-label">Duration (Hours)</label>
                                        <input type="number" class="form-control" id="duration_hours" name="duration_hours" min="0">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="price" class="form-label">Price ($)</label>
                                        <input type="number" class="form-control" id="price" name="price" step="0.01" min="0">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="original_price" class="form-label">Original Price ($)</label>
                                        <input type="number" class="form-control" id="original_price" name="original_price" step="0.01" min="0">
                                        <div class="form-text">Leave empty if no discount</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Instructor Information -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Instructor Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="instructor_name" class="form-label">Instructor Name *</label>
                                    <input type="text" class="form-control" id="instructor_name" name="instructor_name" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="instructor_bio" class="form-label">Instructor Bio</label>
                                    <textarea class="form-control" id="instructor_bio" name="instructor_bio" rows="3"></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="instructor_image" class="form-label">Instructor Photo</label>
                                    <input type="file" class="form-control" id="instructor_image" name="instructor_image" accept="image/*">
                                </div>
                            </div>
                        </div>

                        <!-- Course Content -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Course Content</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="skills_gained" class="form-label">Skills You'll Gain</label>
                                    <textarea class="form-control" id="skills_gained" name="skills_gained" rows="2"></textarea>
                                    <div class="form-text">Comma-separated list of skills</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="prerequisites" class="form-label">Prerequisites</label>
                                    <textarea class="form-control" id="prerequisites" name="prerequisites" rows="2"></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="course_outline" class="form-label">Course Outline</label>
                                    <textarea class="form-control" id="course_outline" name="course_outline" rows="6"></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="learning_outcomes" class="form-label">Learning Outcomes</label>
                                    <textarea class="form-control" id="learning_outcomes" name="learning_outcomes" rows="4"></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="video_url" class="form-label">Course Video URL</label>
                                    <input type="url" class="form-control" id="video_url" name="video_url">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="demo_video_url" class="form-label">Demo Video URL</label>
                                    <input type="url" class="form-control" id="demo_video_url" name="demo_video_url">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <!-- Course Image -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Course Image</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="course_image" class="form-label">Course Thumbnail</label>
                                    <input type="file" class="form-control" id="course_image" name="course_image" accept="image/*">
                                    <div class="form-text">Recommended size: 400x300px</div>
                                </div>
                            </div>
                        </div>

                        <!-- Settings -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Course Settings</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="language" class="form-label">Language</label>
                                    <select class="form-select" id="language" name="language">
                                        <?php foreach ($languages as $lang): ?>
                                            <option value="<?php echo $lang; ?>" <?php echo $lang == 'English' ? 'selected' : ''; ?>><?php echo $lang; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="subtitles_available" class="form-label">Subtitles Available</label>
                                    <input type="text" class="form-control" id="subtitles_available" name="subtitles_available" placeholder="e.g., English, Spanish">
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="certificate_available" name="certificate_available" checked>
                                    <label class="form-check-label" for="certificate_available">
                                        Certificate Available
                                    </label>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured">
                                    <label class="form-check-label" for="is_featured">
                                        Featured Course
                                    </label>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_bestseller" name="is_bestseller">
                                    <label class="form-check-label" for="is_bestseller">
                                        Bestseller
                                    </label>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_new" name="is_new">
                                    <label class="form-check-label" for="is_new">
                                        New Course
                                    </label>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-save me-2"></i>Add Course
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Initialize CKEditor for textareas
document.addEventListener('DOMContentLoaded', function() {
    if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.replace('description');
        CKEDITOR.replace('course_outline');
        CKEDITOR.replace('learning_outcomes');
    }
});
</script>
