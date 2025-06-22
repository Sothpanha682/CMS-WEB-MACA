<?php
if (!isset($_GET['id'])) {
    $_SESSION['message'] = getLangText("No announcement specified.", "មិនមានសេចក្តីប្រកាសត្រូវបានបញ្ជាក់។");
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=dashboard');
    exit;
}

$announcement_id = $_GET['id'];
$announcement = getAnnouncementById($pdo, $announcement_id);

if (!$announcement) {
    $_SESSION['message'] = getLangText("Announcement not found.", "រកមិនឃើញសេចក្តីប្រកាស។");
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=dashboard');
    exit;
}
?>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><?php echo getLangText('Edit Announcement', 'កែសម្រួលសេចក្តីប្រកាស'); ?></h5>
            </div>
            <div class="card-body">
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $title = sanitize($_POST['title']);
                    $title_km = sanitize($_POST['title_km']);
                    $content = $_POST['content']; // Rich text content
                    $content_km = $_POST['content_km']; // Rich text content in Khmer
                    $is_active = isset($_POST['is_active']) ? 1 : 0;
                    
                    // Get event date
                    $event_date = !empty($_POST['event_date']) ? $_POST['event_date'] : null;
                    
                    // Handle image upload if provided
                    $image_path = $announcement['image_path'];
                    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
                        $upload_result = uploadFile($_FILES['image'], 'uploads/announcements/');
                        if ($upload_result['status']) {
                            // Delete old image if exists and new one was uploaded
                            if ($announcement['image_path'] && file_exists($announcement['image_path'])) {
                                unlink($announcement['image_path']);
                            }
                            $image_path = $upload_result['path'];
                        } else {
                            echo '<div class="alert alert-danger">' . $upload_result['message'] . '</div>';
                        }
                    }
                    
                    try {
                        $stmt = $pdo->prepare("UPDATE announcements SET title = :title, title_km = :title_km, content = :content, content_km = :content_km, event_date = :event_date, image_path = :image_path, is_active = :is_active WHERE id = :id");
                        $stmt->bindParam(':title', $title);
                        $stmt->bindParam(':title_km', $title_km);
                        $stmt->bindParam(':content', $content);
                        $stmt->bindParam(':content_km', $content_km);
                        $stmt->bindParam(':event_date', $event_date);
                        $stmt->bindParam(':image_path', $image_path);
                        $stmt->bindParam(':is_active', $is_active);
                        $stmt->bindParam(':id', $announcement_id);
                        $stmt->execute();
                        
                        $_SESSION['message'] = getLangText("Announcement updated successfully!", "សេចក្តីប្រកាសត្រូវបានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ!");
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
                                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($announcement['title']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="content" class="form-label">Content (English)</label>
                                <textarea class="form-control rich-editor" id="content" name="content" rows="6" required><?php echo $announcement['content']; ?></textarea>
                            </div>
                        </div>
                        
                        <!-- Khmer Content -->
                        <div class="tab-pane fade" id="khmer" role="tabpanel" aria-labelledby="khmer-tab">
                            <div class="mb-3">
                                <label for="title_km" class="form-label">Title (ភាសាខ្មែរ)</label>
                                <input type="text" class="form-control" id="title_km" name="title_km" value="<?php echo htmlspecialchars($announcement['title_km']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="content_km" class="form-label">Content (ភាសាខ្មែរ)</label>
                                <textarea class="form-control rich-editor" id="content_km" name="content_km" rows="6" required><?php echo $announcement['content_km']; ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="event_date" class="form-label"><?php echo getLangText('Event Date', 'កាលបរិច្ឆេទព្រឹត្តិការណ៍'); ?></label>
                        <input type="date" class="form-control" id="event_date" name="event_date" value="<?php echo !empty($announcement['event_date']) ? date('Y-m-d', strtotime($announcement['event_date'])) : ''; ?>">
                        <div class="form-text"><?php echo getLangText('The date when the event takes place. Leave empty if not applicable.', 'កាលបរិច្ឆេទដែលព្រឹត្តិការណ៍កើតឡើង។ ទុកឱ្យទទេប្រសិនបើមិនអាចអនុវត្តបាន។'); ?></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label"><?php echo getLangText('Image', 'រូបភាព'); ?></label>
                        <?php if ($announcement['image_path']): ?>
                            <div class="mb-2">
                                <img src="<?php echo htmlspecialchars($announcement['image_path']); ?>" alt="Announcement Image" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <div class="form-text"><?php echo getLangText('Leave empty to keep current image. Recommended size: 800x400 pixels', 'ទុកឱ្យទទេដើម្បីរក្សារូបភាពបច្ចុប្បន្ន។ ទំហំដែលណែនាំ៖ 800x400 ភីកសែល'); ?></div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" <?php echo $announcement['is_active'] ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="is_active"><?php echo getLangText('Active', 'សកម្ម'); ?></label>
                    </div>
                    <div class="d-flex">
                        <button type="submit" class="btn btn-danger me-2"><?php echo getLangText('Update Announcement', 'ធ្វើបច្ចុប្បន្នភាពសេចក្តីប្រកាស'); ?></button>
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
