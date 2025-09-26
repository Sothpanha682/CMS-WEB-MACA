<?php
// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    $_SESSION['message'] = "You must be logged in as an admin to access this page.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=login');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitize($_POST['title']);
    $content = $_POST['content']; // Don't sanitize content as it may contain HTML
    $excerpt = sanitize($_POST['excerpt']);
    $intern_name = sanitize($_POST['intern_name']);
    $intern_university = sanitize($_POST['intern_university']);
    $intern_company = sanitize($_POST['intern_company']);
    $category = sanitize($_POST['category']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $errors = [];
    
    // Validation
    if (empty($title)) {
        $errors[] = "Title is required.";
    }
    if (empty($content)) {
        $errors[] = "Content is required.";
    }
    if (empty($category)) {
        $errors[] = "Category is required.";
    }
    
    // Handle video URL
    $video_url = sanitize($_POST['image_url'] ?? ''); // Use 'image_url' from form for video_url column
    
    // If no errors, insert into database
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO intern_news (title, content, excerpt, intern_name, intern_university, intern_company, category, video_url, is_featured, is_active) VALUES (:title, :content, :excerpt, :intern_name, :intern_university, :intern_company, :category, :video_url, :is_featured, :is_active)");
            
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':excerpt', $excerpt);
            $stmt->bindParam(':intern_name', $intern_name);
            $stmt->bindParam(':intern_university', $intern_university);
            $stmt->bindParam(':intern_company', $intern_company);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':video_url', $video_url); // Bind to video_url
            $stmt->bindParam(':is_featured', $is_featured);
            $stmt->bindParam(':is_active', $is_active);
            
            if ($stmt->execute()) {
                $_SESSION['message'] = "Intern news added successfully!";
                $_SESSION['message_type'] = "success";
                header('Location: index.php?page=manage-intern-news');
                exit;
            } else {
                $errors[] = "Error adding intern news to database.";
            }
        } catch(PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}

// Category options
$categories = [
    'success_story' => 'Success Story',
    'new_cohort' => 'New Cohort',
    'achievement' => 'Achievement',
    'alumni_success' => 'Alumni Success',
    'project_spotlight' => 'Project Spotlight',
    'innovation' => 'Innovation',
    'program_update' => 'Program Update',
    'graduation' => 'Graduation'
];
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 text-danger">Add New Intern News</h1>
                <a href="index.php?page=manage-intern-news" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Manage
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
                <div class="card-header bg-light">
                    <h5 class="mb-0">Intern News Details</h5>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title" 
                                           value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="excerpt" class="form-label">Excerpt</label>
                                    <textarea class="form-control" id="excerpt" name="excerpt" rows="3" 
                                              placeholder="Brief summary of the news..."><?php echo isset($_POST['excerpt']) ? htmlspecialchars($_POST['excerpt']) : ''; ?></textarea>
                                    <div class="form-text">This will be displayed in news listings and previews.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="content" name="content" rows="15" required><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-select" id="category" name="category" required>
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories as $value => $label): ?>
                                            <option value="<?php echo $value; ?>" 
                                                    <?php echo (isset($_POST['category']) && $_POST['category'] == $value) ? 'selected' : ''; ?>>
                                                <?php echo $label; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="image_url" class="form-label">Featured Image URL (Facebook/YouTube)</label>
                                    <input type="text" class="form-control" id="image_url" name="image_url" 
                                           value="<?php echo isset($_POST['image_url']) ? htmlspecialchars($_POST['image_url']) : ''; ?>" 
                                           placeholder="Enter image URL from Facebook or YouTube">
                                    <div class="form-text">Provide a direct URL to an image. This will override any uploaded file.</div>
                                </div>


                                <hr>

                                <h6 class="text-muted mb-3">Intern Information</h6>

                                <div class="mb-3">
                                    <label for="intern_name" class="form-label">Intern Name</label>
                                    <input type="text" class="form-control" id="intern_name" name="intern_name" 
                                           value="<?php echo isset($_POST['intern_name']) ? htmlspecialchars($_POST['intern_name']) : ''; ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="intern_university" class="form-label">University</label>
                                    <input type="text" class="form-control" id="intern_university" name="intern_university" 
                                           value="<?php echo isset($_POST['intern_university']) ? htmlspecialchars($_POST['intern_university']) : ''; ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="intern_company" class="form-label">Company</label>
                                    <input type="text" class="form-control" id="intern_company" name="intern_company" 
                                           value="<?php echo isset($_POST['intern_company']) ? htmlspecialchars($_POST['intern_company']) : ''; ?>">
                                </div>

                                <hr>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                                               <?php echo (isset($_POST['is_featured'])) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="is_featured">
                                            Featured Article
                                        </label>
                                    </div>
                                    <div class="form-text">Featured articles appear prominently on the internship page.</div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               <?php echo (!isset($_POST['is_active']) || isset($_POST['is_active'])) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                    <div class="form-text">Only active articles are visible to visitors.</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="index.php?page=manage-intern-news" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-save me-2"></i>Add Intern News
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize CKEditor for content
CKEDITOR.replace('content', {
    height: 400,
    toolbar: [
        { name: 'document', items: ['Source'] },
        { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'Undo', 'Redo'] },
        { name: 'editing', items: ['Find', 'Replace'] },
        '/',
        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike'] },
        { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote'] },
        { name: 'links', items: ['Link', 'Unlink'] },
        { name: 'insert', items: ['Image', 'Table', 'HorizontalRule'] },
        '/',
        { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
        { name: 'colors', items: ['TextColor', 'BGColor'] },
        { name: 'tools', items: ['Maximize'] }
    ]
});
</script>
