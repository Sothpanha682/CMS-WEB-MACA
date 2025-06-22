<?php
// Check if user is logged in
if (!isLoggedIn()) {
    $_SESSION['message'] = getLangText("You must be logged in to access this page.", "អ្នកត្រូវតែចូលគណនីដើម្បីចូលប្រើទំព័រនេះ។");
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=login');
    exit;
}
?>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><?php echo getLangText('Add News Article', 'បន្ថែមអត្ថបទព័ត៌មាន'); ?></h5>
            </div>
            <div class="card-body">
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $title = sanitize($_POST['title']);
                    $title_km = sanitize($_POST['title_km']);
                    $summary = sanitize($_POST['summary']);
                    $summary_km = sanitize($_POST['summary_km']);
                    $content = $_POST['content']; // Rich text content
                    $content_km = $_POST['content_km']; // Rich text content in Khmer
                    $is_active = isset($_POST['is_active']) ? 1 : 0;
                    
                    // Handle event date
                    $event_date = null;
                    if (!empty($_POST['event_date'])) {
                        $event_date = sanitize($_POST['event_date']);
                    }
                    
                    // Handle image upload
                    $image_path = null;
                    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
                        $upload_result = uploadFile($_FILES['image'], 'uploads/news/');
                        if ($upload_result['status']) {
                            $image_path = $upload_result['path'];
                        } else {
                            echo '<div class="alert alert-danger">' . $upload_result['message'] . '</div>';
                        }
                    }
                    
                    try {
                        $stmt = $pdo->prepare("INSERT INTO news (title, title_km, summary, summary_km, content, content_km, image_path, is_active, event_date, created_at) VALUES (:title, :title_km, :summary, :summary_km, :content, :content_km, :image_path, :is_active, :event_date, NOW())");
                        $stmt->bindParam(':title', $title);
                        $stmt->bindParam(':title_km', $title_km);
                        $stmt->bindParam(':summary', $summary);
                        $stmt->bindParam(':summary_km', $summary_km);
                        $stmt->bindParam(':content', $content);
                        $stmt->bindParam(':content_km', $content_km);
                        $stmt->bindParam(':image_path', $image_path);
                        $stmt->bindParam(':is_active', $is_active);
                        $stmt->bindParam(':event_date', $event_date);
                        $stmt->execute();
                        
                        $_SESSION['message'] = getLangText("News article added successfully!", "អត្ថបទព័ត៌មានត្រូវបានបន្ថែមដោយជោគជ័យ!");
                        $_SESSION['message_type'] = "success";
                        header('Location: index.php?page=dashboard');
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
                                <div class="form-text">A brief summary that will appear in news listings.</div>
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
                                <div class="form-text">សេចក្តីសង្ខេបខ្លីដែលនឹងបង្ហាញនៅក្នុងបញ្ជីព័ត៌មាន។</div>
                            </div>
                            <div class="mb-3">
                                <label for="content_km" class="form-label">Content (ភាសាខ្មែរ)</label>
                                <textarea class="form-control rich-editor" id="content_km" name="content_km" rows="10" required></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="event_date" class="form-label"><?php echo getLangText('Event Date', 'កាលបរិច្ឆេទព្រឹត្តិការណ៍'); ?></label>
                        <input type="date" class="form-control" id="event_date" name="event_date" value="<?php echo date('Y-m-d'); ?>">
                        <div class="form-text"><?php echo getLangText('The date when the event takes place. Leave empty if not applicable.', 'កាលបរិច្ឆេទដែលព្រឹត្តិការណ៍កើតឡើង។ ទុកឱ្យទទេប្រសិនបើមិនអាចអនុវត្តបាន។'); ?></div>
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
                        <button type="submit" class="btn btn-danger me-2"><?php echo getLangText('Add News', 'បន្ថែមព័ត៌មាន'); ?></button>
                        <a href="index.php?page=dashboard" class="btn btn-outline-secondary"><?php echo getLangText('Cancel', 'បោះបង់'); ?></a>
                    </div>
                </form>
            </div>
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
