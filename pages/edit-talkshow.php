<?php
// Check if user is logged in
if (!isLoggedIn()) {
    $_SESSION['message'] = "You must be logged in to access this page.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=login');
    exit;
}

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['message'] = "No talkshow specified.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=manage-talkshow');
    exit;
}

$id = $_GET['id'];

// Get talkshow data
try {
    // Updated to use talkshows table (plural)
    $stmt = $pdo->prepare("SELECT * FROM talkshows WHERE id = ?");
    $stmt->execute([$id]);
    $talkshow = $stmt->fetch();
    
    if (!$talkshow) {
        $_SESSION['message'] = "Talkshow not found.";
        $_SESSION['message_type'] = "danger";
        header('Location: index.php?page=manage-talkshow');
        exit;
    }
} catch(PDOException $e) {
    $_SESSION['message'] = "Error: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=manage-talkshow');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $title_km = trim($_POST['title_km']);
    $summary = trim($_POST['summary']);
    $summary_km = trim($_POST['summary_km']);
    $content = $_POST['content']; // Rich text content
    $content_km = $_POST['content_km']; // Rich text content in Khmer
    $location = trim($_POST['location']);
    $location_km = trim($_POST['location_km']);
    $event_date = trim($_POST['event_date']);
    $video_url = trim($_POST['video_url']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    
    // Validate input
    if (empty($title) || empty($summary) || empty($content) || empty($location) || empty($event_date)) {
        $_SESSION['message'] = "All required fields must be filled.";
        $_SESSION['message_type'] = "danger";
    } else {
        try {
            // Update database
            $stmt = $pdo->prepare("UPDATE talkshows SET 
                title = ?, 
                title_km = ?, 
                summary = ?, 
                summary_km = ?, 
                content = ?, 
                content_km = ?, 
                location = ?, 
                location_km = ?, 
                event_date = ?, 
                video_url = ?, 
                is_active = ? 
                WHERE id = ?");
            $stmt->execute([
                $title, 
                $title_km, 
                $summary, 
                $summary_km, 
                $content, 
                $content_km, 
                $location, 
                $location_km, 
                $event_date, 
                $video_url, 
                $is_active, 
                $id
            ]);
            
            $_SESSION['message'] = "Talkshow updated successfully.";
            $_SESSION['message_type'] = "success";
            header('Location: index.php?page=manage-talkshow');
            exit;
        } catch(PDOException $e) {
            $_SESSION['message'] = "Error: " . $e->getMessage();
            $_SESSION['message_type'] = "danger";
        }
    }
}
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Talkshow</h1>
        <a href="index.php?page=manage-talkshow" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Talkshow List
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
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($talkshow['title']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="summary" class="form-label">Summary (English)</label>
                            <textarea class="form-control" id="summary" name="summary" rows="2" required><?php echo htmlspecialchars($talkshow['summary']); ?></textarea>
                            <div class="form-text">A brief summary that will appear in talkshow listings.</div>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Location (English)</label>
                            <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($talkshow['location']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Content (English)</label>
                            <textarea class="form-control rich-editor" id="content" name="content" rows="10" required><?php echo htmlspecialchars($talkshow['content']); ?></textarea>
                        </div>
                    </div>
                    
                    <!-- Khmer Content -->
                    <div class="tab-pane fade" id="khmer" role="tabpanel" aria-labelledby="khmer-tab">
                        <div class="mb-3">
                            <label for="title_km" class="form-label">Title (ភាសាខ្មែរ)</label>
                            <input type="text" class="form-control" id="title_km" name="title_km" value="<?php echo htmlspecialchars($talkshow['title_km']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="summary_km" class="form-label">Summary (ភាសាខ្មែរ)</label>
                            <textarea class="form-control" id="summary_km" name="summary_km" rows="2" required><?php echo htmlspecialchars($talkshow['summary_km']); ?></textarea>
                            <div class="form-text">សេចក្តីសង្ខេបខ្លីដែលនឹងបង្ហាញនៅក្នុងបញ្ជីកម្មវិធីសន្ទនា។</div>
                        </div>
                        <div class="mb-3">
                            <label for="location_km" class="form-label">Location (ភាសាខ្មែរ)</label>
                            <input type="text" class="form-control" id="location_km" name="location_km" value="<?php echo htmlspecialchars($talkshow['location_km']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="content_km" class="form-label">Content (ភាសាខ្មែរ)</label>
                            <textarea class="form-control rich-editor" id="content_km" name="content_km" rows="10" required><?php echo htmlspecialchars($talkshow['content_km']); ?></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="event_date" class="form-label">Event Date</label>
                    <input type="date" class="form-control" id="event_date" name="event_date" value="<?php echo htmlspecialchars($talkshow['event_date']); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="video_url" class="form-label">Video URL (YouTube or Facebook)</label>
                    <input type="url" class="form-control" id="video_url" name="video_url" value="<?php echo htmlspecialchars($talkshow['video_url']); ?>">
                    <div class="form-text">Enter a YouTube or Facebook video URL to embed the video.</div>
                </div>
                
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" <?php echo $talkshow['is_active'] ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Update Talkshow
                </button>
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
