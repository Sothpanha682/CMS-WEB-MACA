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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><?php echo getLangText('Add New Talkshow', 'បន្ថែមកម្មវិធីសន្ទនាថ្មី'); ?></h1>
        <a href="index.php?page=manage-talkshow" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> <?php echo getLangText('Back to Talkshow List', 'ត្រលប់ទៅបញ្ជីកម្មវិធីសន្ទនា'); ?>
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
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $title = sanitize($_POST['title']);
                $title_km = sanitize($_POST['title_km']);
                $summary = sanitize($_POST['summary']);
                $summary_km = sanitize($_POST['summary_km']);
                $content = $_POST['content']; // Rich text content
                $content_km = $_POST['content_km']; // Rich text content in Khmer
                $location = sanitize($_POST['location']);
                $location_km = sanitize($_POST['location_km']);
                $event_date = sanitize($_POST['event_date']);
                $video_url = sanitize($_POST['video_url']);
                $is_active = isset($_POST['is_active']) ? 1 : 0;
                
                // Handle image upload if provided
                $image_path = null;
                if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
                    $upload_result = uploadFile($_FILES['image']);
                    // Fix for undefined array key "success"
                    if (isset($upload_result['success']) && $upload_result['success']) {
                        $image_path = $upload_result['file_path'];
                    } else {
                        echo '<div class="alert alert-danger">' . (isset($upload_result['message']) ? $upload_result['message'] : 'Error uploading file.') . '</div>';
                    }
                }
                
                try {
                    // First check if the table exists, if not create it
                    $stmt = $pdo->query("SHOW TABLES LIKE 'talkshows'");
                    if ($stmt->rowCount() == 0) {
                        // Table doesn't exist, create it
                        $pdo->exec("CREATE TABLE IF NOT EXISTS `talkshows` (
                            `id` int(11) NOT NULL AUTO_INCREMENT,
                            `title` varchar(255) NOT NULL,
                            `title_km` varchar(255) NOT NULL,
                            `summary` text NOT NULL,
                            `summary_km` text NOT NULL,
                            `content` text NOT NULL,
                            `content_km` text NOT NULL,
                            `location` varchar(255) NOT NULL,
                            `location_km` varchar(255) NOT NULL,
                            `event_date` date NOT NULL,
                            `video_url` varchar(255) NOT NULL,
                            `image_path` varchar(255) DEFAULT NULL,
                            `is_active` tinyint(1) NOT NULL DEFAULT 1,
                            `created_at` datetime NOT NULL,
                            PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
                    }
                    
                    $stmt = $pdo->prepare("INSERT INTO talkshows (title, title_km, summary, summary_km, content, content_km, location, location_km, event_date, video_url, image_path, is_active, created_at) VALUES (:title, :title_km, :summary, :summary_km, :content, :content_km, :location, :location_km, :event_date, :video_url, :image_path, :is_active, NOW())");
                    $stmt->bindParam(':title', $title);
                    $stmt->bindParam(':title_km', $title_km);
                    $stmt->bindParam(':summary', $summary);
                    $stmt->bindParam(':summary_km', $summary_km);
                    $stmt->bindParam(':content', $content);
                    $stmt->bindParam(':content_km', $content_km);
                    $stmt->bindParam(':location', $location);
                    $stmt->bindParam(':location_km', $location_km);
                    $stmt->bindParam(':event_date', $event_date);
                    $stmt->bindParam(':video_url', $video_url);
                    $stmt->bindParam(':image_path', $image_path);
                    $stmt->bindParam(':is_active', $is_active);
                    $stmt->execute();
                    
                    $_SESSION['message'] = getLangText("Talkshow added successfully!", "កម្មវិធីសន្ទនាត្រូវបានបន្ថែមដោយជោគជ័យ!");
                    $_SESSION['message_type'] = "success";
                    header('Location: index.php?page=manage-talkshow');
                    exit;
                } catch(PDOException $e) {
                    echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
                }
            }
            ?>
            <form method="post" action="" enctype="multipart/form-data">
                <ul class="nav nav-tabs mb-3" id="languageTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="english-tab" data-bs-toggle="tab" data-bs-target="#english" type="button" role="tab" aria-controls="english" aria-selected="true">English</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="khmer-tab" data-bs-toggle="tab" data-bs-target="#khmer" type="button" role="tab" aria-controls="khmer" aria-selected="false">ភាសាខ្មែរ</button>
                    </li>
                </ul>
                
                <div class="tab-content" id="languageTabsContent">
                    <!-- English Content -->
                    <div class="tab-pane fade show active" id="english" role="tabpanel" aria-labelledby="english-tab">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title (English)</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="summary" class="form-label">Summary (English)</label>
                            <textarea class="form-control" id="summary" name="summary" rows="2" required></textarea>
                            <div class="form-text">A brief summary that will appear in talkshow listings.</div>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Location (English)</label>
                            <input type="text" class="form-control" id="location" name="location" required>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Content (English)</label>
                            <textarea class="form-control rich-editor" id="content" name="content" rows="10" required></textarea>
                        </div>
                    </div>
                    
                    <!-- Khmer Content -->
                    <div class="tab-pane fade" id="khmer" role="tabpanel" aria-labelledby="khmer-tab">
                        <div class="mb-3">
                            <label for="title_km" class="form-label">Title (ភាសាខ្មែរ)</label>
                            <input type="text" class="form-control" id="title_km" name="title_km" required>
                        </div>
                        <div class="mb-3">
                            <label for="summary_km" class="form-label">Summary (ភាសាខ្មែរ)</label>
                            <textarea class="form-control" id="summary_km" name="summary_km" rows="2" required></textarea>
                            <div class="form-text">សេចក្តីសង្ខេបខ្លីដែលនឹងបង្ហាញនៅក្នុងបញ្ជីកម្មវិធីសន្ទនា។</div>
                        </div>
                        <div class="mb-3">
                            <label for="location_km" class="form-label">Location (ភាសាខ្មែរ)</label>
                            <input type="text" class="form-control" id="location_km" name="location_km" required>
                        </div>
                        <div class="mb-3">
                            <label for="content_km" class="form-label">Content (ភាសាខ្មែរ)</label>
                            <textarea class="form-control rich-editor" id="content_km" name="content_km" rows="10" required></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="event_date" class="form-label"><?php echo getLangText('Event Date', 'កាលបរិច្ឆេទព្រឹត្តិការណ៍'); ?></label>
                    <input type="date" class="form-control" id="event_date" name="event_date" required>
                </div>
                
                <div class="mb-3">
                    <label for="video_url" class="form-label"><?php echo getLangText('Video URL (YouTube or Facebook)', 'URL វីដេអូ (YouTube ឬ Facebook)'); ?></label>
                    <input type="url" class="form-control" id="video_url" name="video_url">
                    <div class="form-text"><?php echo getLangText('Enter a YouTube or Facebook video URL to embed the video.', 'បញ្ចូល URL វីដេអូ YouTube ឬ Facebook ដើម្បីបង្កប់វីដេអូ។'); ?></div>
                </div>
                
                <div class="mb-3">
                    <label for="image" class="form-label"><?php echo getLangText('Featured Image', 'រូបភាពសំខាន់'); ?></label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    <div class="form-text"><?php echo getLangText('Recommended size: 1200x600 pixels', 'ទំហំដែលណែនាំ៖ 1200x600 ភីកសែល'); ?></div>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked>
                    <label class="form-check-label" for="is_active"><?php echo getLangText('Active', 'សកម្ម'); ?></label>
                </div>
                
                <div class="d-flex">
                    <button type="submit" class="btn btn-danger me-2"><?php echo getLangText('Publish Talkshow', 'ផ្សាយកម្មវិធីសន្ទនា'); ?></button>
                    <a href="index.php?page=manage-talkshow" class="btn btn-outline-secondary"><?php echo getLangText('Cancel', 'បោះបង់'); ?></a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize CKEditor for English content
    CKEDITOR.replace('content', {
        toolbar: [
            { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat' ] },
            { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
            { name: 'links', items: [ 'Link', 'Unlink' ] },
            { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'SpecialChar' ] },
            { name: 'styles', items: [ 'Styles', 'Format' ] },
            { name: 'tools', items: [ 'Maximize' ] },
        ]
    });
    
    // Initialize CKEditor for Khmer content
    CKEDITOR.replace('content_km', {
        toolbar: [
            { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat' ] },
            { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
            { name: 'links', items: [ 'Link', 'Unlink' ] },
            { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'SpecialChar' ] },
            { name: 'styles', items: [ 'Styles', 'Format' ] },
            { name: 'tools', items: [ 'Maximize' ] },
        ]
    });
});
</script>
