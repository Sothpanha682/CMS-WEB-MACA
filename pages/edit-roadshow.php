<?php
// Check if user is logged in (removed the admin check)
if (!isLoggedIn()) {
    $_SESSION['message'] = "You must be logged in to access this page.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=login');
    exit;
}

// Get roadshow ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    $_SESSION['message'] = "Invalid roadshow ID.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=manage-roadshow');
    exit;
}

// Create uploads directory if it doesn't exist
$uploadsDir = 'uploads/roadshow/';
if (!file_exists($uploadsDir)) {
    mkdir($uploadsDir, 0755, true);
}

// Get roadshow data
try {
    $stmt = $pdo->prepare("SELECT * FROM roadshow WHERE id = ?");
    $stmt->execute([$id]);
    $roadshow = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$roadshow) {
        $_SESSION['message'] = "Roadshow not found.";
        $_SESSION['message_type'] = "danger";
        header('Location: index.php?page=manage-roadshow');
        exit;
    }
} catch(PDOException $e) {
    $_SESSION['message'] = "Error: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=manage-roadshow');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $video_url = trim($_POST['video_url']);
    $location = trim($_POST['location']);
    $event_date = trim($_POST['event_date']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Initialize image_path variable
    $image_path = $roadshow['image_path']; // Keep existing image by default
    
    // Validate input
    if (empty($title) || empty($description) || empty($location) || empty($event_date)) {
        $_SESSION['message'] = "Title, description, location, and event date are required.";
        $_SESSION['message_type'] = "danger";
    } else {
        // Handle image upload if present
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            // Debug information
            error_log("Image upload attempt: " . print_r($_FILES['image'], true));
            
            $upload_result = uploadFile($_FILES['image'], $uploadsDir, ['jpg', 'jpeg', 'png', 'gif']);
            
            if ($upload_result['status']) {
                // Delete old image if exists
                if (!empty($roadshow['image_path']) && file_exists($roadshow['image_path'])) {
                    unlink($roadshow['image_path']);
                }
                
                $image_path = $upload_result['path'];
                error_log("Image uploaded successfully: " . $image_path);
            } else {
                $_SESSION['message'] = "Error uploading image: " . $upload_result['message'];
                $_SESSION['message_type'] = "danger";
                error_log("Image upload failed: " . $upload_result['message']);
            }
        }
        
        try {
            // Update database
            $stmt = $pdo->prepare("UPDATE roadshow SET title = ?, description = ?, video_url = ?, location = ?, event_date = ?, is_active = ?, image_path = ? WHERE id = ?");
            $stmt->execute([$title, $description, $video_url, $location, $event_date, $is_active, $image_path, $id]);
            
            $_SESSION['message'] = "Roadshow updated successfully.";
            $_SESSION['message_type'] = "success";
            header('Location: index.php?page=manage-roadshow');
            exit;
        } catch(PDOException $e) {
            $_SESSION['message'] = "Database Error: " . $e->getMessage();
            $_SESSION['message_type'] = "danger";
            error_log("Database error when updating roadshow: " . $e->getMessage());
        }
    }
}
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Roadshow</h1>
        <a href="index.php?page=manage-roadshow" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Roadshow List
        </a>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($roadshow['title']); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control summernote" id="description" name="description" rows="5" required><?php echo htmlspecialchars($roadshow['description']); ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="video_url" class="form-label">Video URL (Facebook or YouTube)</label>
<input type="url" class="form-control" id="video_url" name="video_url" value="<?php echo htmlspecialchars($roadshow['video_url'] ?? ''); ?>">
                    <div class="form-text">Enter the full URL of the Facebook or YouTube video (optional).</div>
                </div>
                
                <div class="mb-3">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($roadshow['location']); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="event_date" class="form-label">Event Date</label>
                    <input type="date" class="form-control" id="event_date" name="event_date" value="<?php echo htmlspecialchars(date('Y-m-d', strtotime($roadshow['event_date']))); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="image" class="form-label">Featured Image</label>
                    <?php if (!empty($roadshow['image_path']) && file_exists($roadshow['image_path'])): ?>
                        <div class="mb-2">
                            <img src="<?php echo htmlspecialchars($roadshow['image_path']); ?>" alt="Current image" class="img-thumbnail" style="max-height: 200px;">
                            <p class="form-text">Current image. Upload a new one to replace it.</p>
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    <div class="form-text">Upload an image to be displayed with the roadshow. Recommended size: 800x450 pixels.</div>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" <?php echo $roadshow['is_active'] ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Update Roadshow
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Include Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

<!-- Include Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Summernote
    $('.summernote').summernote({
        placeholder: 'Write your description here...',
        tabsize: 2,
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
});
</script>
