<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Media Library</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6>Upload New Media</h6>
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        if (isset($_FILES['media']) && $_FILES['media']['size'] > 0) {
                            $upload_result = uploadFile($_FILES['media']);
                            if ($upload_result['success']) {
                                $file_path = $upload_result['file_path'];
                                $title = sanitize($_POST['title']);
                                $description = sanitize($_POST['description']);
                                
                                try {
                                    $stmt = $pdo->prepare("INSERT INTO media (title, description, file_path, uploaded_at) VALUES (:title, :description, :file_path, NOW())");
                                    $stmt->bindParam(':title', $title);
                                    $stmt->bindParam(':description', $description);
                                    $stmt->bindParam(':file_path', $file_path);
                                    $stmt->execute();
                                    
                                    $_SESSION['message'] = "Media uploaded successfully!";
                                    $_SESSION['message_type'] = "success";
                                    header('Location: index.php?page=manage-media');
                                    exit;
                                } catch(PDOException $e) {
                                    echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
                                }
                            } else {
                                echo '<div class="alert alert-danger">' . $upload_result['message'] . '</div>';
                            }
                        }
                    }
                    ?>
                    <form method="post" action="" enctype="multipart/form-data" class="row g-3">
                        <div class="col-md-4">
                            <label for="media" class="form-label">Select Image</label>
                            <input type="file" class="form-control" id="media" name="media" accept="image/*" required>
                        </div>
                        <div class="col-md-4">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="col-md-4">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="description" name="description">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-danger">Upload</button>
                        </div>
                    </form>
                </div>
                
                <hr>
                
                <h6>Media Library</h6>
                <div class="row">
                    <?php
                    $media_files = getMedia($pdo);
                    if (count($media_files) > 0):
                        foreach ($media_files as $media):
                    ?>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card h-100">
                            <img src="<?php echo $media['file_path']; ?>" class="card-img-top" alt="<?php echo $media['title']; ?>" style="height: 150px; object-fit: cover;">
                            <div class="card-body">
                                <h6 class="card-title"><?php echo $media['title']; ?></h6>
                                <p class="card-text small"><?php echo $media['description']; ?></p>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-sm btn-outline-primary copy-url" data-url="<?php echo $media['file_path']; ?>">Copy URL</button>
                                    <a href="actions/delete-media.php?id=<?php echo $media['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this media?')">Delete</a>
                                </div>
                            </div>
                            <div class="card-footer text-muted small">
                                Uploaded: <?php echo formatDate($media['uploaded_at']); ?>
                            </div>
                        </div>
                    </div>
                    <?php
                        endforeach;
                    else:
                    ?>
                    <div class="col-12">
                        <div class="alert alert-info">No media files found. Upload some images to get started.</div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Copy URL functionality
    document.querySelectorAll('.copy-url').forEach(button => {
        button.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            navigator.clipboard.writeText(url).then(() => {
                // Change button text temporarily
                const originalText = this.textContent;
                this.textContent = 'Copied!';
                setTimeout(() => {
                    this.textContent = originalText;
                }, 2000);
            });
        });
    });
});
</script>
