<?php
// Check if user is logged in and is admin
if (!isLoggedIn()) {
    header('Location: index.php?page=login');
    exit;
}

// Include database connection
require_once 'config/database.php';

// Get language preference
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $title_en = $_POST['title_en'];
    $title_kh = $_POST['title_kh'];
    $description_en = $_POST['description_en'];
    $description_kh = $_POST['description_kh'];
    $button_text_en = $_POST['button_text_en'];
    $button_text_kh = $_POST['button_text_kh'];
    $button_url = $_POST['button_url'];
    $active = isset($_POST['active']) ? 1 : 0;
    
    // Handle image upload
    $image_url = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/sliders/';
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_name = time() . '_' . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $file_name;
        
        // Move uploaded file
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_url = $target_file;
        } else {
            $_SESSION['message'] = "Failed to upload image.";
            $_SESSION['message_type'] = "danger";
        }
    }
    
    // Get the highest display order
    $stmt = $pdo->prepare("SELECT MAX(display_order) as max_order FROM slider_images");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $display_order = $result['max_order'] ? $result['max_order'] + 1 : 1;
    
    // Insert into database
    $stmt = $pdo->prepare("INSERT INTO slider_images (title_en, title_kh, description_en, description_kh, image_url, button_text_en, button_text_kh, button_url, display_order, active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $result = $stmt->execute([$title_en, $title_kh, $description_en, $description_kh, $image_url, $button_text_en, $button_text_kh, $button_url, $display_order, $active]);
    
    if ($result) {
        $_SESSION['message'] = "Slider added successfully.";
        $_SESSION['message_type'] = "success";
        header('Location: index.php?page=manage-slider');
        exit;
    } else {
        $_SESSION['message'] = "Failed to add slider.";
        $_SESSION['message_type'] = "danger";
    }
}

// Include admin header
include 'includes/admin-header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800 <?= $lang === 'kh' ? 'khmer-text' : '' ?>">
            <?= $lang === 'en' ? 'Add New Slider' : 'បន្ថែមស្លាយថ្មី' ?>
        </h1>
        <a href="index.php?page=manage-slider" class="btn btn-secondary <?= $lang === 'kh' ? 'khmer-text' : '' ?>">
            <i class="fas fa-arrow-left"></i> <?= $lang === 'en' ? 'Back to Sliders' : 'ត្រឡប់ទៅស្លាយ' ?>
        </a>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show" role="alert">
            <?= $_SESSION['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary <?= $lang === 'kh' ? 'khmer-text' : '' ?>">
                <?= $lang === 'en' ? 'Slider Information' : 'ព័ត៌មានស្លាយ' ?>
            </h6>
        </div>
        <div class="card-body">
            <form action="index.php?page=add-slider" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="title_en" class="form-label <?= $lang === 'kh' ? 'khmer-text' : '' ?>">
                            <?= $lang === 'en' ? 'Title (English)' : 'ចំណងជើង (អង់គ្លេស)' ?> <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="title_en" name="title_en" required>
                    </div>
                    <div class="col-md-6">
                        <label for="title_kh" class="form-label <?= $lang === 'kh' ? 'khmer-text' : '' ?>">
                            <?= $lang === 'en' ? 'Title (Khmer)' : 'ចំណងជើង (ខ្មែរ)' ?> <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="title_kh" name="title_kh" required>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="description_en" class="form-label <?= $lang === 'kh' ? 'khmer-text' : '' ?>">
                            <?= $lang === 'en' ? 'Description (English)' : 'ការពិពណ៌នា (អង់គ្លេស)' ?>
                        </label>
                        <textarea class="form-control" id="description_en" name="description_en" rows="3"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="description_kh" class="form-label <?= $lang === 'kh' ? 'khmer-text' : '' ?>">
                            <?= $lang === 'en' ? 'Description (Khmer)' : 'ការពិពណ៌នា (ខ្មែរ)' ?>
                        </label>
                        <textarea class="form-control" id="description_kh" name="description_kh" rows="3"></textarea>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="button_text_en" class="form-label <?= $lang === 'kh' ? 'khmer-text' : '' ?>">
                            <?= $lang === 'en' ? 'Button Text (English)' : 'អត្ថបទប៊ូតុង (អង់គ្លេស)' ?>
                        </label>
                        <input type="text" class="form-control" id="button_text_en" name="button_text_en">
                    </div>
                    <div class="col-md-6">
                        <label for="button_text_kh" class="form-label <?= $lang === 'kh' ? 'khmer-text' : '' ?>">
                            <?= $lang === 'en' ? 'Button Text (Khmer)' : 'អត្ថបទប៊ូតុង (ខ្មែរ)' ?>
                        </label>
                        <input type="text" class="form-control" id="button_text_kh" name="button_text_kh">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="button_url" class="form-label <?= $lang === 'kh' ? 'khmer-text' : '' ?>">
                        <?= $lang === 'en' ? 'Button URL' : 'URL ប៊ូតុង' ?>
                    </label>
                    <input type="text" class="form-control" id="button_url" name="button_url">
                </div>
                
                <div class="mb-3">
                    <label for="image" class="form-label <?= $lang === 'kh' ? 'khmer-text' : '' ?>">
                        <?= $lang === 'en' ? 'Slider Image' : 'រូបភាពស្លាយ' ?> <span class="text-danger">*</span>
                    </label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                    <small class="text-muted <?= $lang === 'kh' ? 'khmer-text' : '' ?>">
                        <?= $lang === 'en' ? 'Recommended size: 1920x600 pixels' : 'ទំហំដែលណែនាំ៖ 1920x600 ភីកសែល' ?>
                    </small>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="active" name="active" checked>
                    <label class="form-check-label <?= $lang === 'kh' ? 'khmer-text' : '' ?>" for="active">
                        <?= $lang === 'en' ? 'Active' : 'សកម្ម' ?>
                    </label>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="reset" class="btn btn-secondary <?= $lang === 'kh' ? 'khmer-text' : '' ?>">
                        <?= $lang === 'en' ? 'Reset' : 'កំណត់ឡើងវិញ' ?>
                    </button>
                    <button type="submit" class="btn btn-primary <?= $lang === 'kh' ? 'khmer-text' : '' ?>">
                        <?= $lang === 'en' ? 'Add Slider' : 'បន្ថែមស្លាយ' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/admin-footer.php'; ?>
