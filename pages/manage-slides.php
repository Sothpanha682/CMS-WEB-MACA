<?php
// Include necessary files
require_once 'core/init.php';

// Check if the user is logged in
if (!is_logged_in()) {
    login_error_redirect();
}

// Fetch slide data if an ID is provided
if (isset($_GET['edit'])) {
    $slide_id = (int)$_GET['edit'];
    $slide_query = $db->query("SELECT * FROM slides WHERE id = '$slide_id'");
    $slide = mysqli_fetch_assoc($slide_query);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($slide) ? 'Edit Slide' : 'Add Slide'; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2><?php echo isset($slide) ? 'Edit Slide' : 'Add Slide'; ?></h2>

        <form action="actions/save-slide.php" method="POST" enctype="multipart/form-data" class="mb-4">
            <input type="hidden" name="id" value="<?php echo $slide['id'] ?? ''; ?>">
            
            <!-- Current Image Display -->
            <?php if (isset($slide) && !empty($slide['image_url'])): ?>
            <div class="mb-3">
                <label class="form-label">Current Image</label>
                <div>
                    <img src="<?php echo $slide['image_url']; ?>" alt="Current Slide" class="img-thumbnail" style="max-height: 200px;">
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Upload New Image -->
            <div class="mb-3">
                <label for="image" class="form-label">Upload Slide Image</label>
                <input type="file" class="form-control" id="image" name="image" <?php echo isset($slide) ? '' : 'required'; ?>>
                <small class="form-text text-muted">Recommended size: 1920x700 pixels. Format: JPG, PNG.</small>
            </div>
            
            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">
                <?php echo isset($slide) ? 'Update Slide' : 'Add Slide'; ?>
            </button>
        </form>

        <a href="admin.php" class="btn btn-secondary">Cancel</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
