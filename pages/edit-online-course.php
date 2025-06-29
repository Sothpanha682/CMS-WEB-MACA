<?php
require_once __DIR__ . '/../config/database.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../index.php?page=login');
    exit();
}

// Get course ID from URL
$course_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($course_id === 0) {
    header('Location: ../index.php?page=manage-online-courses');
    exit();
}

// Fetch course data
try {
    $stmt = $pdo->prepare("SELECT * FROM online_courses WHERE id = ?");
    $stmt->execute([$course_id]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$course) {
        header('Location: ../index.php?page=manage-online-courses');
        exit();
    }
} catch (PDOException $e) {
    $error = "Error fetching course: " . $e->getMessage();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $instructor_name = trim($_POST['instructor_name'] ?? '');
    $instructor_bio = trim($_POST['instructor_bio'] ?? '');
    $category = $_POST['category'] ?? '';
    $level = $_POST['level'] ?? '';
    $duration = trim($_POST['duration'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $original_price = floatval($_POST['original_price'] ?? 0);
    $rating = floatval($_POST['rating'] ?? 0);
    $students_count = intval($_POST['students_count'] ?? 0);
    $lessons_count = intval($_POST['lessons_count'] ?? 0);
    $skills_gained = trim($_POST['skills_gained'] ?? '');
    $course_outline = trim($_POST['course_outline'] ?? '');
    $prerequisites = trim($_POST['prerequisites'] ?? '');
    $language = $_POST['language'] ?? '';
    $certificate_available = isset($_POST['certificate_available']) ? 1 : 0;
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_bestseller = isset($_POST['is_bestseller']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 0;
    
    $errors = [];
    
    // Validation
    if (empty($title)) $errors[] = "Title is required";
    if (empty($description)) $errors[] = "Description is required";
    if (empty($instructor_name)) $errors[] = "Instructor name is required";
    if (empty($category)) $errors[] = "Category is required";
    if (empty($level)) $errors[] = "Level is required";
    if (empty($duration)) $errors[] = "Duration is required";
    if ($price < 0) $errors[] = "Price must be non-negative";
    if ($rating < 0 || $rating > 5) $errors[] = "Rating must be between 0 and 5";
    
    // Handle file uploads
    $course_image = $course['course_image'];
    $instructor_image = $course['instructor_image'];
    
    // Course image upload
    if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES['course_image']['type'];
        $file_size = $_FILES['course_image']['size'];
        
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = "Course image must be JPG, PNG, or GIF";
        } elseif ($file_size > 5 * 1024 * 1024) {
            $errors[] = "Course image must be less than 5MB";
        } else {
            $upload_dir = '../uploads/courses/';
            if (!file_exists($upload_dir)) {
                if (!mkdir($upload_dir, 0755, true)) {
                    $errors[] = "Failed to create upload directory for courses: " . error_get_last()['message'];
                }
            }
            
            if (empty($errors)) { // Only proceed if directory creation was successful or already existed
                $file_extension = pathinfo($_FILES['course_image']['name'], PATHINFO_EXTENSION);
                $new_filename = 'course_' . $course_id . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;
                
                if (move_uploaded_file($_FILES['course_image']['tmp_name'], $upload_path)) {
                    // Delete old image if it exists
                    if ($course_image && file_exists('../uploads/courses/' . $course_image)) {
                        unlink('../uploads/courses/' . $course_image);
                    }
                    $course_image = $new_filename;
                } else {
                    $errors[] = "Failed to upload course image: " . error_get_last()['message'];
                }
            }
        }
    }
    
    // Instructor image upload
    if (isset($_FILES['instructor_image']) && $_FILES['instructor_image']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES['instructor_image']['type'];
        $file_size = $_FILES['instructor_image']['size'];
        
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = "Instructor image must be JPG, PNG, or GIF";
        } elseif ($file_size > 5 * 1024 * 1024) {
            $errors[] = "Instructor image must be less than 5MB";
        } else {
            $upload_dir = '../uploads/instructors/';
            if (!file_exists($upload_dir)) {
                if (!mkdir($upload_dir, 0755, true)) {
                    $errors[] = "Failed to create upload directory for instructors: " . error_get_last()['message'];
                }
            }
            
            if (empty($errors)) { // Only proceed if directory creation was successful or already existed
                $file_extension = pathinfo($_FILES['instructor_image']['name'], PATHINFO_EXTENSION);
                $new_filename = 'instructor_' . $course_id . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;
                
                if (move_uploaded_file($_FILES['instructor_image']['tmp_name'], $upload_path)) {
                    // Delete old image if it exists
                    if ($instructor_image && file_exists('../uploads/instructors/' . $instructor_image)) {
                        unlink('../uploads/instructors/' . $instructor_image);
                    }
                    $instructor_image = $new_filename;
                } else {
                    $errors[] = "Failed to upload instructor image: " . error_get_last()['message'];
                }
            }
        }
    }
    
    // Handle image removal
    if (isset($_POST['remove_course_image']) && $course_image) {
        if (file_exists('../uploads/courses/' . $course_image)) {
            unlink('../uploads/courses/' . $course_image);
        }
        $course_image = null;
    }
    
    if (isset($_POST['remove_instructor_image']) && $instructor_image) {
        if (file_exists('../uploads/instructors/' . $instructor_image)) {
            unlink('../uploads/instructors/' . $instructor_image);
        }
        $instructor_image = null;
    }
    
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                UPDATE online_courses SET 
                title = ?, description = ?, instructor_name = ?, instructor_bio = ?, 
                category = ?, level = ?, duration = ?, price = ?, original_price = ?, 
                rating = ?, students_count = ?, lessons_count = ?, skills_gained = ?,
                course_outline = ?, prerequisites = ?, language = ?, certificate_available = ?,
                is_featured = ?, is_bestseller = ?, is_active = ?, course_image = ?, 
                instructor_image = ?, updated_at = NOW()
                WHERE id = ?
            ");
            
            $stmt->execute([
                $title, $description, $instructor_name, $instructor_bio,
                $category, $level, $duration, $price, $original_price,
                $rating, $students_count, $lessons_count, $skills_gained,
                $course_outline, $prerequisites, $language, $certificate_available,
                $is_featured, $is_bestseller, $is_active, $course_image,
                $instructor_image, $course_id
            ]);
            
            $success = "Course updated successfully!";
            
            // Refresh course data
            $stmt = $pdo->prepare("SELECT * FROM online_courses WHERE id = ?");
            $stmt->execute([$course_id]);
            $course = $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            $errors[] = "Error updating course: " . $e->getMessage();
        }
    }
}

$page_title = "Edit Online Course";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - MACA Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->

            <!-- Main content -->
            <main class="col-md-15 ms-sm-auto col-lg-15 px-md-2">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Edit Online Course</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="../index.php?page=manage-online-courses" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Courses
                        </a>
                    </div>
                </div>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Course Information</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <label for="title" class="form-label">Course Title *</label>
                                                <input type="text" class="form-control" id="title" name="title" 
                                                       value="<?php echo htmlspecialchars($course['title'] ?? ''); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="is_active" class="form-label">Status</label>
                                                <select class="form-select" id="is_active" name="is_active">
                                                    <option value="1" <?php echo ($course['is_active'] ?? '') == '1' ? 'selected' : ''; ?>>Active</option>
                                                    <option value="0" <?php echo ($course['is_active'] ?? '') == '0' ? 'selected' : ''; ?>>Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description *</label>
                                        <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($course['description'] ?? ''); ?></textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="category" class="form-label">Category *</label>
                                                <select class="form-select" id="category" name="category" required>
                                                    <option value="">Select Category</option>
                                                    <option value="Technology" <?php echo ($course['category'] ?? '') === 'Technology' ? 'selected' : ''; ?>>Technology</option>
                                                    <option value="Business" <?php echo ($course['category'] ?? '') === 'Business' ? 'selected' : ''; ?>>Business</option>
                                                    <option value="Design" <?php echo ($course['category'] ?? '') === 'Design' ? 'selected' : ''; ?>>Design</option>
                                                    <option value="Marketing" <?php echo ($course['category'] ?? '') === 'Marketing' ? 'selected' : ''; ?>>Marketing</option>
                                                    <option value="Data Science" <?php echo ($course['category'] ?? '') === 'Data Science' ? 'selected' : ''; ?>>Data Science</option>
                                                    <option value="Personal Development" <?php echo ($course['category'] ?? '') === 'Personal Development' ? 'selected' : ''; ?>>Personal Development</option>
                                                    <option value="Health & Fitness" <?php echo ($course['category'] ?? '') === 'Health & Fitness' ? 'selected' : ''; ?>>Health & Fitness</option>
                                                    <option value="Language" <?php echo ($course['category'] ?? '') === 'Language' ? 'selected' : ''; ?>>Language</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="level" class="form-label">Level *</label>
                                                <select class="form-select" id="level" name="level" required>
                                                    <option value="">Select Level</option>
                                                    <option value="Beginner" <?php echo ($course['level'] ?? '') === 'Beginner' ? 'selected' : ''; ?>>Beginner</option>
                                                    <option value="Intermediate" <?php echo ($course['level'] ?? '') === 'Intermediate' ? 'selected' : ''; ?>>Intermediate</option>
                                                    <option value="Advanced" <?php echo ($course['level'] ?? '') === 'Advanced' ? 'selected' : ''; ?>>Advanced</option>
                                                    <option value="All Levels" <?php echo ($course['level'] ?? '') === 'All Levels' ? 'selected' : ''; ?>>All Levels</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="duration" class="form-label">Duration *</label>
                                                <input type="text" class="form-control" id="duration" name="duration" 
                                                       value="<?php echo htmlspecialchars($course['duration'] ?? ''); ?>" 
                                                       placeholder="e.g., 8 weeks" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="lessons_count" class="form-label">Number of Lessons</label>
                                                <input type="number" class="form-control" id="lessons_count" name="lessons_count" 
                                                       value="<?php echo $course['lessons_count'] ?? '0'; ?>" min="0">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="language" class="form-label">Language</label>
                                                <select class="form-select" id="language" name="language">
                                                    <option value="English" <?php echo ($course['language'] ?? '') === 'English' ? 'selected' : ''; ?>>English</option>
                                                    <option value="Indonesian" <?php echo ($course['language'] ?? '') === 'Indonesian' ? 'selected' : ''; ?>>Indonesian</option>
                                                    <option value="Mandarin" <?php echo ($course['language'] ?? '') === 'Mandarin' ? 'selected' : ''; ?>>Mandarin</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="price" class="form-label">Current Price ($)</label>
                                                <input type="text" class="form-control" id="price" name="price" 
                                                       value="<?php echo htmlspecialchars($course['price'] ?? '0.00'); ?>">
                                                <!-- Changed to type="text" to allow free text input as requested. 
                                                     Ensure server-side validation handles non-numeric input if necessary. -->
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="original_price" class="form-label">Original Price ($)</label>
                                                <input type="text" class="form-control" id="original_price" name="original_price" 
                                                       value="<?php echo htmlspecialchars($course['original_price'] ?? '0.00'); ?>">
                                                <!-- Changed to type="text" to allow free text input as requested. 
                                                     Ensure server-side validation handles non-numeric input if necessary. -->
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="rating" class="form-label">Rating (0-5)</label>
                                                <input type="number" class="form-control" id="rating" name="rating" 
                                                       value="<?php echo $course['rating'] ?? '0'; ?>" min="0" max="5" step="0.1">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="students_count" class="form-label">Number of Students</label>
                                        <input type="number" class="form-control" id="students_count" name="students_count" 
                                               value="<?php echo $course['students_count'] ?? '0'; ?>" min="0">
                                    </div>

                                    <div class="mb-3">
                                        <label for="skills_gained" class="form-label">Skills You'll Learn</label>
                                        <textarea class="form-control" id="skills_gained" name="skills_gained" rows="3"
                                                  placeholder="Separate skills with commas"><?php echo htmlspecialchars($course['skills_gained'] ?? ''); ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="prerequisites" class="form-label">Prerequisites</label>
                                        <textarea class="form-control" id="prerequisites" name="prerequisites" rows="3"><?php echo htmlspecialchars($course['prerequisites'] ?? ''); ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="course_outline" class="form-label">Course Outline</label>
                                        <textarea class="form-control" id="course_outline" name="course_outline" rows="6"><?php echo htmlspecialchars($course['course_outline'] ?? ''); ?></textarea>
                                    </div>

                                    <h5 class="mt-4 mb-3">Instructor Information</h5>
                                    
                                    <div class="mb-3">
                                        <label for="instructor_name" class="form-label">Instructor Name *</label>
                                        <input type="text" class="form-control" id="instructor_name" name="instructor_name" 
                                               value="<?php echo htmlspecialchars($course['instructor_name'] ?? ''); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="instructor_bio" class="form-label">Instructor Bio</label>
                                        <textarea class="form-control" id="instructor_bio" name="instructor_bio" rows="4"><?php echo htmlspecialchars($course['instructor_bio'] ?? ''); ?></textarea>
                                    </div>

                                    <h5 class="mt-4 mb-3">Course Settings</h5>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                                                       <?php echo $course['is_featured'] ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="is_featured">
                                                    Featured Course
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="is_bestseller" name="is_bestseller" 
                                                       <?php echo $course['is_bestseller'] ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="is_bestseller">
                                                    Bestseller
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="certificate_available" name="certificate_available" 
                                                       <?php echo isset($course['certificate_available']) && $course['certificate_available'] ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="certificate_available">
                                                    Provides Certificate
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <h5 class="mt-4 mb-3">Course Images</h5>
                                    
                                    <div class="mb-3">
                                        <label for="course_image" class="form-label">Course Image</label>
                                        <?php if ($course['course_image']): ?>
                                            <div class="mb-2">
                                                <img src="../uploads/courses/<?php echo htmlspecialchars($course['course_image']); ?>" 
                                                     alt="Course Image" class="img-fluid rounded" style="max-height: 200px;">
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="checkbox" id="remove_course_image" name="remove_course_image">
                                                    <label class="form-check-label" for="remove_course_image">
                                                        Remove current image
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control" id="course_image" name="course_image" accept="image/*">
                                        <div class="form-text">JPG, PNG, or GIF. Max 5MB.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="instructor_image" class="form-label">Instructor Image</label>
                                        <?php if (isset($course['instructor_image']) && $course['instructor_image']): ?>
                                            <div class="mb-2">
                                                <img src="../uploads/instructors/<?php echo htmlspecialchars((string)$course['instructor_image']); ?>" 
                                                     alt="Instructor Image" class="img-fluid rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="checkbox" id="remove_instructor_image" name="remove_instructor_image">
                                                    <label class="form-check-label" for="remove_instructor_image">
                                                        Remove current image
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control" id="instructor_image" name="instructor_image" accept="image/*">
                                        <div class="form-text">JPG, PNG, or GIF. Max 5MB.</div>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <a href="../index.php?page=manage-online-courses" class="btn btn-secondary me-md-2">Cancel</a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Update Course
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Course Preview</h5>
                            </div>
                            <div class="card-body">
                                <div class="course-preview">
                                    <h6><?php echo htmlspecialchars((string)$course['title']); ?></h6>
                                    <p class="text-muted small"><?php echo htmlspecialchars((string)$course['instructor_name']); ?></p>
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="badge bg-primary me-2"><?php echo htmlspecialchars((string)$course['level']); ?></span>
                                        <span class="badge bg-secondary"><?php echo htmlspecialchars((string)$course['category']); ?></span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="text-warning me-1">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star<?php echo $i <= $course['rating'] ? '' : '-o'; ?>"></i>
                                            <?php endfor; ?>
                                        </span>
                                        <span class="small text-muted"><?php echo isset($course['rating']) ? htmlspecialchars((string)$course['rating']) : '0'; ?> (<?php echo isset($course['students_count']) ? number_format((float)$course['students_count']) : '0'; ?> students)</span>
                                    </div>
                                    <div class="mt-2">
                                        <span class="h6 text-success">$<?php echo isset($course['price']) ? number_format((float)$course['price'], 2) : '0.00'; ?></span>
                                        <?php if (isset($course['original_price']) && isset($course['price']) && $course['original_price'] > $course['price']): ?>
                                            <span class="text-muted text-decoration-line-through ms-2">$<?php echo number_format((float)$course['original_price'], 2); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize CKEditor for course outline
        ClassicEditor
            .create(document.querySelector('#course_outline'))
            .catch(error => {
                console.error(error);
            });

        // Initialize CKEditor for instructor bio
        ClassicEditor
            .create(document.querySelector('#instructor_bio'))
            .catch(error => {
                console.error(error);
            });
    </script>
</body>
</html>
