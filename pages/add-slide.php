<?php
// Check if user is logged in
if (!isLoggedIn()) {
    $_SESSION['message'] = "You must be logged in to access this page.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=login');
    exit;
}

// Define MACA_CMS constant to prevent direct access error
define('MACA_CMS', true);

// Page title
$pageTitle = "Add New Slide";

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $subtitle = $_POST['subtitle'] ?? '';
    $description = $_POST['description'] ?? '';
    $button_text = $_POST['button_text'] ?? '';
    $button_url = $_POST['button_url'] ?? '';
    $button_text_2 = $_POST['button_text_2'] ?? '';
    $button_url_2 = $_POST['button_url_2'] ?? '';
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Validate required fields
    if (empty($title)) {
        $_SESSION['message'] = "Title is required.";
        $_SESSION['message_type'] = "danger";
    } else {
        // Handle image upload
        $image_path = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'assets/images/slides/';
            
            // Create directory if it doesn't exist
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_name = basename($_FILES['image']['name']);
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $new_file_name = uniqid() . '.' . $file_ext;
            $upload_path = $upload_dir . $new_file_name;
            
            // Check if file is an image
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($file_ext, $allowed_types)) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    $image_path = $upload_path;
                } else {
                    $_SESSION['message'] = "Failed to upload image.";
                    $_SESSION['message_type'] = "danger";
                }
            } else {
                $_SESSION['message'] = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
                $_SESSION['message_type'] = "danger";
            }
        } else {
            $_SESSION['message'] = "Image is required.";
            $_SESSION['message_type'] = "danger";
        }
        
        // If no errors, insert the slide
        if (!isset($_SESSION['message'])) {
            try {
                // Get the highest display order
                $stmt = $pdo->query("SELECT MAX(display_order) as max_order FROM slides");
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $display_order = ($result['max_order'] ?? 0) + 1;
                
                // Insert the slide
                $stmt = $pdo->prepare("INSERT INTO slides (title, subtitle, description, image_path, button_text, button_url, button_text_2, button_url_2, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $subtitle, $description, $image_path, $button_text, $button_url, $button_text_2, $button_url_2, $display_order, $is_active]);
                
                $_SESSION['message'] = "Slide added successfully.";
                $_SESSION['message_type'] = "success";
                header('Location: index.php?page=manage-slides');
                exit;
            } catch(PDOException $e) {
                $_SESSION['message'] = "Error: " . $e->getMessage();
                $_SESSION['message_type'] = "danger";
            }
        }
    }
}

// Include header
include_once 'includes/header.php';
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Add New Slide</h1>
        <a href="index.php?page=manage-slides" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to Slides
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
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">Slide Information</h5>
        </div>
        <div class="card-body">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="col-md-6">
                        <label for="subtitle" class="form-label">Subtitle</label>
                        <input type="text" class="form-control" id="subtitle" name="subtitle">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="image" class="form-label">Slide Image <span class="text-danger">*</span></label>
                    <input type="file" class="form-control" id="image" name="image" required>
                    <small class="text-muted">Recommended size: 1920x800 pixels. Max file size: 2MB.</small>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="button_text" class="form-label">Primary Button Text</label>
                        <input type="text" class="form-control" id="button_text" name="button_text">
                    </div>
                    <div class="col-md-6">
                        <label for="button_url" class="form-label">Primary Button URL</label>
                        <input type="text" class="form-control" id="button_url" name="button_url">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="button_text_2" class="form-label">Secondary Button Text</label>
                        <input type="text" class="form-control" id="button_text_2" name="button_text_2">
                    </div>
                    <div class="col-md-6">
                        <label for="button_url_2" class="form-label">Secondary Button URL</label>
                        <input type="text" class="form-control" id="button_url_2" name="button_url_2">
                    </div>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="reset" class="btn btn-secondary me-md-2">Reset</button>
                    <button type="submit" class="btn btn-primary">Save Slide</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>
