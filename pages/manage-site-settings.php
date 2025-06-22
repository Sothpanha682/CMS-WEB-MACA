<?php
// Check if user is logged in
if (!isLoggedIn()) {
    $_SESSION['message'] = "You must be logged in to access this page.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=login');
    exit;
}
?>

<div class="container py-4">
    <h1 class="fw-bold text-danger mb-4">Manage Site Settings</h1>
    
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="list-group">
                <a href="#hero-image-section" class="list-group-item list-group-item-action active" data-bs-toggle="list">Hero Image</a>
                <a href="#about-banner-section" class="list-group-item list-group-item-action" data-bs-toggle="list">About Banner</a>
                <a href="index.php?page=dashboard" class="list-group-item list-group-item-action text-danger">Back to Dashboard</a>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="tab-content">
                <!-- Hero Image Section -->
                <div class="tab-pane fade show active" id="hero-image-section">
    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Home Page Slideshow</h5>
        </div>
        <div class="card-body">
            <p class="alert alert-info">
                <i class="fas fa-info-circle"></i> You can upload up to 5 images for the homepage slideshow. Each slide can have optional text overlay and a button.
            </p>
            
            <?php
            // Handle slideshow image uploads
            for ($i = 1; $i <= 5; $i++) {
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["hero_image_{$i}_submit"])) {
                    $update_success = false;
                    $update_message = '';
                    
                    // Handle image upload if provided
                    if (isset($_FILES["hero_image_{$i}"]) && $_FILES["hero_image_{$i}"]['size'] > 0) {
                        $upload_result = uploadFile($_FILES["hero_image_{$i}"], 'uploads/slideshow/');
                        if ($upload_result['status']) {
                            $image_path = $upload_result['path'];
                            
                            try {
                                // Check if hero image setting exists
                                $stmt = $pdo->prepare("SELECT * FROM site_settings WHERE setting_key = 'hero_image_{$i}'");
                                $stmt->execute();
                                
                                if ($stmt->rowCount() > 0) {
                                    // Update existing setting
                                    $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = :value WHERE setting_key = 'hero_image_{$i}'");
                                } else {
                                    // Insert new setting
                                    $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES ('hero_image_{$i}', :value)");
                                }
                                
                                $stmt->bindParam(':value', $image_path);
                                $stmt->execute();
                                $update_success = true;
                                $update_message = 'Slide image updated successfully!';
                            } catch(PDOException $e) {
                                $update_message = 'Error updating slide image: ' . $e->getMessage();
                            }
                        } else {
                            $update_message = $upload_result['message'];
                        }
                    }
                    
                    // Display update message
                    if (!empty($update_message)) {
                        echo '<div class="alert alert-' . ($update_success ? 'success' : 'danger') . '">' . $update_message . '</div>';
                    }
                }
                
                // Get current slide settings
                try {
                    // Get image
                    $stmt = $pdo->prepare("SELECT * FROM site_settings WHERE setting_key = 'hero_image_{$i}'");
                    $stmt->execute();
                    $slide_image = $stmt->fetch();
                    
                    // We only need the image now
                    $slide_text = $button_text = $button_link = false;
                } catch(PDOException $e) {
                    $slide_image = $slide_text = $button_text = $button_link = false;
                }
                ?>
                
                <div class="slide-settings-card mb-4">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Slide <?php echo $i; ?></h5>
                        </div>
                        <div class="card-body">
                            <form method="post" action="" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php if ($slide_image && $slide_image['setting_value']): ?>
                                            <div class="mb-3">
                                                <label class="form-label">Current Image:</label>
                                                <div class="slide-preview-container">
                                                    <img src="<?php echo $slide_image['setting_value']; ?>" alt="Slide <?php echo $i; ?>" class="img-fluid rounded mb-3">
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="mb-3">
                                            <label for="hero_image_<?php echo $i; ?>" class="form-label">Upload Slide Image</label>
                                            <input type="file" class="form-control" id="hero_image_<?php echo $i; ?>" name="hero_image_<?php echo $i; ?>" accept="image/*">
                                            <div class="form-text">Recommended size: 1200x500 pixels. Larger images will be cropped to fit.</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-end">
                                    <button type="submit" name="hero_image_<?php echo $i; ?>_submit" class="btn btn-danger">
                                        <i class="fas fa-save"></i> Update Slide <?php echo $i; ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
            
            <div class="slideshow-settings mt-4">
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Slideshow Settings</h5>
        </div>
        <div class="card-body">
            <?php
            // Handle slideshow settings submission
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["slideshow_settings_submit"])) {
                $slideshow_speed = isset($_POST["slideshow_speed"]) ? intval($_POST["slideshow_speed"]) : 6;
                $slideshow_effect = isset($_POST["slideshow_effect"]) ? $_POST["slideshow_effect"] : 'fade';
                
                try {
                    // Save slideshow speed
                    $stmt = $pdo->prepare("SELECT * FROM site_settings WHERE setting_key = 'slideshow_speed'");
                    $stmt->execute();
                    
                    if ($stmt->rowCount() > 0) {
                        $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = :value WHERE setting_key = 'slideshow_speed'");
                    } else {
                        $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES ('slideshow_speed', :value)");
                    }
                    $stmt->bindParam(':value', $slideshow_speed);
                    $stmt->execute();
                    
                    // Save slideshow effect
                    $stmt = $pdo->prepare("SELECT * FROM site_settings WHERE setting_key = 'slideshow_effect'");
                    $stmt->execute();
                    
                    if ($stmt->rowCount() > 0) {
                        $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = :value WHERE setting_key = 'slideshow_effect'");
                    } else {
                        $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES ('slideshow_effect', :value)");
                    }
                    $stmt->bindParam(':value', $slideshow_effect);
                    $stmt->execute();
                    
                    echo '<div class="alert alert-success">Slideshow settings updated successfully!</div>';
                } catch(PDOException $e) {
                    echo '<div class="alert alert-danger">Error updating slideshow settings: ' . $e->getMessage() . '</div>';
                }
            }
            
            // Get current slideshow settings
            try {
                $stmt = $pdo->prepare("SELECT * FROM site_settings WHERE setting_key = 'slideshow_speed'");
                $stmt->execute();
                $slideshow_speed_setting = $stmt->fetch();
                $current_speed = ($slideshow_speed_setting && $slideshow_speed_setting['setting_value']) ? $slideshow_speed_setting['setting_value'] : 6;
                
                $stmt = $pdo->prepare("SELECT * FROM site_settings WHERE setting_key = 'slideshow_effect'");
                $stmt->execute();
                $slideshow_effect_setting = $stmt->fetch();
                $current_effect = ($slideshow_effect_setting && $slideshow_effect_setting['setting_value']) ? $slideshow_effect_setting['setting_value'] : 'fade';
            } catch(PDOException $e) {
                $current_speed = 6;
                $current_effect = 'fade';
            }
            ?>
            
            <form method="post" action="">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="slideshow_speed" class="form-label">Slide Duration (seconds)</label>
                            <input type="number" class="form-control" id="slideshow_speed" name="slideshow_speed" min="3" max="15" value="<?php echo $current_speed; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="slideshow_effect" class="form-label">Transition Effect</label>
                            <select class="form-select" id="slideshow_effect" name="slideshow_effect">
                                <option value="fade" <?php echo ($current_effect == 'fade') ? 'selected' : ''; ?>>Fade</option>
                                <option value="slide" <?php echo ($current_effect == 'slide') ? 'selected' : ''; ?>>Slide</option>
                                <option value="zoom" <?php echo ($current_effect == 'zoom') ? 'selected' : ''; ?>>Zoom</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <button type="submit" name="slideshow_settings_submit" class="btn btn-danger">
                        <i class="fas fa-save"></i> Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
        </div>
    </div>
</div>
                
                <!-- About Banner Section -->
                <div class="tab-pane fade" id="about-banner-section">
                    <div class="card shadow-sm">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0">About Page Banner</h5>
                        </div>
                        <div class="card-body">
                            <?php
                            // Handle about banner upload
                            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['about_banner_submit'])) {
                                if (isset($_FILES['about_banner']) && $_FILES['about_banner']['size'] > 0) {
                                    $upload_result = uploadFile($_FILES['about_banner'], 'uploads/banners/');
                                    if ($upload_result['status']) {
                                        $image_path = $upload_result['path'];
                                        
                                        try {
                                            // Check if about banner setting exists
                                            $stmt = $pdo->prepare("SELECT * FROM site_settings WHERE setting_key = 'about_banner'");
                                            $stmt->execute();
                                            
                                            if ($stmt->rowCount() > 0) {
                                                // Update existing setting
                                                $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = :value WHERE setting_key = 'about_banner'");
                                            } else {
                                                // Insert new setting
                                                $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES ('about_banner', :value)");
                                            }
                                            
                                            $stmt->bindParam(':value', $image_path);
                                            $stmt->execute();
                                            
                                            echo '<div class="alert alert-success">About banner updated successfully!</div>';
                                        } catch(PDOException $e) {
                                            echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
                                        }
                                    } else {
                                        echo '<div class="alert alert-danger">' . $upload_result['message'] . '</div>';
                                    }
                                }
                            }
                            
                            // Get current about banner
                            try {
                                $stmt = $pdo->prepare("SELECT * FROM site_settings WHERE setting_key = 'about_banner'");
                                $stmt->execute();
                                $about_banner = $stmt->fetch();
                            } catch(PDOException $e) {
                                $about_banner = false;
                            }
                            ?>
                            
                            <?php if ($about_banner && $about_banner['setting_value']): ?>
                                <div class="mb-4">
                                    <h6>Current About Banner:</h6>
                                    <img src="<?php echo $about_banner['setting_value']; ?>" alt="Current About Banner" class="img-fluid rounded mb-3" style="max-height: 300px;">
                                </div>
                            <?php endif; ?>
                            
                            <form method="post" action="" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="about_banner" class="form-label">Upload New About Banner</label>
                                    <input type="file" class="form-control" id="about_banner" name="about_banner" accept="image/*" required>
                                    <div class="form-text">Recommended size: 1200x600 pixels.</div>
                                </div>
                                <button type="submit" name="about_banner_submit" class="btn btn-danger">Update About Banner</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
