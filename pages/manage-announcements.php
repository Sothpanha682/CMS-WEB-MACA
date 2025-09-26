<?php
// Check if this file is included through index.php
if (!defined('INCLUDED')) {
    // If accessed directly, redirect to the homepage
    header('Location: ../index.php');
    exit;
}

// Include functions for file uploads and language text
require_once 'includes/functions.php';

// Get current language
$currentLang = getCurrentLanguage();

// Process form submission for adding/editing announcements
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $title_km = trim($_POST['title_km']);
    $content = $_POST['content']; // Rich text content
    $content_km = $_POST['content_km']; // Rich text content in Khmer
    $event_date = !empty($_POST['event_date']) ? $_POST['event_date'] : null;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $announcement_id = isset($_POST['announcement_id']) ? (int)$_POST['announcement_id'] : 0;
    
    $errors = [];

    if (empty($title)) {
        $errors[] = $currentLang == 'en' ? 'Title (English) is required' : 'ចំណងជើង (អង់គ្លេស) ត្រូវបានទាមទារ';
    }
    if (empty($content)) {
        $errors[] = $currentLang == 'en' ? 'Content (English) is required' : 'ខ្លឹមសារ (អង់គ្លេស) ត្រូវបានទាមទារ';
    }
    // Khmer fields are not strictly required for now, but can be added if needed.

    $image_path = null;
    // Handle image upload if provided
    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        $upload_result = uploadFile($_FILES['image'], 'uploads/announcements/');
        if ($upload_result['status']) {
            $image_path = $upload_result['path'];
        } else {
            $errors[] = $upload_result['message'];
        } 
    } elseif ($announcement_id > 0 && isset($_POST['existing_image_path'])) {
        // Keep existing image if no new one is uploaded during edit
        $image_path = $_POST['existing_image_path'];
    }

    if (empty($errors)) {
        try {
            if ($announcement_id > 0) {
                // Update existing announcement
                $sql = "UPDATE announcements SET title = ?, title_km = ?, content = ?, content_km = ?, event_date = ?, image_path = ?, is_active = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$title, $title_km, $content, $content_km, $event_date, $image_path, $is_active, $announcement_id]);
                $_SESSION['message'] = $currentLang == 'en' ? 'Announcement updated successfully' : 'សេចក្តីប្រកាសត្រូវបានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ';
            } else {
                // Add new announcement
                $sql = "INSERT INTO announcements (title, title_km, content, content_km, event_date, image_path, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$title, $title_km, $content, $content_km, $event_date, $image_path, $is_active]);
                $_SESSION['message'] = $currentLang == 'en' ? 'Announcement added successfully' : 'សេចក្តីប្រកាសត្រូវបានបន្ថែមដោយជោគជ័យ';
            }
            $_SESSION['message_type'] = 'success';
            echo "<script>window.location.href = 'index.php?page=manage-announcements';</script>";
            exit;
        } catch(PDOException $e) {
            $errors[] = $currentLang == 'en' ? 'Database error: ' . $e->getMessage() : 'កំហុសមូលដ្ឋានទិន្នន័យ៖ ' . $e->getMessage();
        }
    }
}

// Handle announcement deletion
if (isset($_GET['action']) && $_GET['action'] == 'delete-announcement' && isset($_GET['id'])) {
    $announcement_id = (int)$_GET['id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM announcements WHERE id = ?");
        $stmt->execute([$announcement_id]);
        $_SESSION['message'] = $currentLang == 'en' ? 'Announcement deleted successfully' : 'សេចក្តីប្រកាសត្រូវបានលុបដោយជោគជ័យ';
        $_SESSION['message_type'] = 'success';
        echo "<script>window.location.href = 'index.php?page=manage-announcements';</script>";
        exit;
    } catch(PDOException $e) {
        $_SESSION['message'] = $currentLang == 'en' ? 'Database error: ' . $e->getMessage() : 'កំហុសមូលដ្ឋានទិន្នន័យ៖ ' . $e->getMessage();
        $_SESSION['message_type'] = 'danger';
        echo "<script>window.location.href = 'index.php?page=manage-announcements';</script>";
        exit;
    }
}

// Get current page number from URL parameter
$current_page = isset($_GET['page_num']) ? max(1, intval($_GET['page_num'])) : 1;
$announcements_per_page = 20; // Display 20 announcements per page
$offset = ($current_page - 1) * $announcements_per_page;

// Get total count of announcements for pagination
$total_announcements = 0;
try {
    $count_stmt = $pdo->prepare("SELECT COUNT(*) FROM announcements");
    $count_stmt->execute();
    $total_announcements = $count_stmt->fetchColumn();
} catch (PDOException $e) {
    error_log("Error counting announcements: " . $e->getMessage());
}

$total_pages = ceil($total_announcements / $announcements_per_page);

// Get announcements for current page
$announcements = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM announcements ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $announcements_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = $currentLang == 'en' ? 'Error fetching announcements: ' . $e->getMessage() : 'កំហុសក្នុងការទាញយកសេចក្តីប្រកាស៖ ' . $e->getMessage();
}

// Get announcement for editing if ID is provided
$edit_announcement = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $announcement_id = (int)$_GET['id'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM announcements WHERE id = ?");
        $stmt->execute([$announcement_id]);
        $edit_announcement = $stmt->fetch();
    } catch(PDOException $e) {
        $error_message = $currentLang == 'en' ? 'Error fetching announcement for edit: ' . $e->getMessage() : 'កំហុសក្នុងការទាញយកសេចក្តីប្រកាសសម្រាប់កែសម្រួល៖ ' . $e->getMessage();
    }
}
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 mb-0"><?php echo $currentLang == 'en' ? 'Manage Announcements' : 'គ្រប់គ្រងសេចក្តីប្រកាស'; ?></h1>
        <a href="index.php?page=dashboard" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> <?php echo $currentLang == 'en' ? 'Back to Dashboard' : 'ត្រឡប់ទៅផ្ទាំងគ្រប់គ្រង'; ?>
        </a>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><?php echo $currentLang == 'en' ? ($edit_announcement ? 'Edit Announcement' : 'Add New Announcement') : ($edit_announcement ? 'កែសម្រួលសេចក្តីប្រកាស' : 'បន្ថែមសេចក្តីប្រកាសថ្មី'); ?></h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="index.php?page=manage-announcements" enctype="multipart/form-data">
                        <?php if ($edit_announcement): ?>
                            <input type="hidden" name="announcement_id" value="<?php echo htmlspecialchars($edit_announcement['id']); ?>">
                        <?php endif; ?>
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
                                    <label for="title" class="form-label"><?php echo getLangText('Title (English)', 'ចំណងជើង (អង់គ្លេស)'); ?> *</label>
                                    <input type="text" class="form-control" id="title" name="title" value="<?php echo $edit_announcement ? htmlspecialchars($edit_announcement['title']) : ''; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="content" class="form-label"><?php echo getLangText('Content (English)', 'ខ្លឹមសារ (អង់គ្លេស)'); ?> *</label>
                                    <textarea class="form-control rich-editor" id="content" name="content" rows="6" required><?php echo $edit_announcement ? htmlspecialchars($edit_announcement['content']) : ''; ?></textarea>
                                </div>
                            </div>
                            
                            <!-- Khmer Content -->
                            <div class="tab-pane fade" id="khmer" role="tabpanel" aria-labelledby="khmer-tab">
                                <div class="mb-3">
                                    <label for="title_km" class="form-label"><?php echo getLangText('Title (Khmer)', 'ចំណងជើង (ភាសាខ្មែរ)'); ?></label>
                                    <input type="text" class="form-control" id="title_km" name="title_km" value="<?php echo $edit_announcement ? htmlspecialchars($edit_announcement['title_km']) : ''; ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="content_km" class="form-label"><?php echo getLangText('Content (Khmer)', 'ខ្លឹមសារ (ភាសាខ្មែរ)'); ?></label>
                                    <textarea class="form-control rich-editor" id="content_km" name="content_km" rows="6"><?php echo $edit_announcement ? htmlspecialchars($edit_announcement['content_km']) : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="event_date" class="form-label"><?php echo $currentLang == 'en' ? 'Event Date' : 'កាលបរិច្ឆេទព្រឹត្តិការណ៍'; ?></label>
                            <input type="date" class="form-control" id="event_date" name="event_date" value="<?php echo $edit_announcement ? htmlspecialchars($edit_announcement['event_date']) : date('Y-m-d'); ?>">
                            <div class="form-text"><?php echo $currentLang == 'en' ? 'The date when the event takes place. Leave empty if not applicable.' : 'កាលបរិច្ឆេទដែលព្រឹត្តិការណ៍កើតឡើង។ ទុកឱ្យទទេប្រសិនបើមិនអាចអនុវត្តបាន។'; ?></div>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label"><?php echo $currentLang == 'en' ? 'Image (Optional)' : 'រូបភាព (ជម្រើស)'; ?></label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <div class="form-text"><?php echo $currentLang == 'en' ? 'Recommended size: 800x400 pixels' : 'ទំហំដែលណែនាំ៖ 800x400 ភីកសែល'; ?></div>
                            <?php if ($edit_announcement && $edit_announcement['image_path']): ?>
                                <div class="mt-2">
                                    <img src="<?php echo htmlspecialchars($edit_announcement['image_path']); ?>" alt="Current Image" class="img-thumbnail" style="max-width: 150px;">
                                    <input type="hidden" name="existing_image_path" value="<?php echo htmlspecialchars($edit_announcement['image_path']); ?>">
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" <?php echo ($edit_announcement && $edit_announcement['is_active']) || !$edit_announcement ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_active"><?php echo $currentLang == 'en' ? 'Active' : 'សកម្ម'; ?></label>
                        </div>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-bullhorn me-1"></i> <?php echo $currentLang == 'en' ? ($edit_announcement ? 'Update Announcement' : 'Add Announcement') : ($edit_announcement ? 'ធ្វើបច្ចុប្បន្នភាពសេចក្តីប្រកាស' : 'បន្ថែមសេចក្តីប្រកាស'); ?>
                        </button>
                        <?php if ($edit_announcement): ?>
                            <a href="index.php?page=manage-announcements" class="btn btn-outline-secondary ms-2">
                                <?php echo $currentLang == 'en' ? 'Cancel Edit' : 'បោះបង់ការកែសម្រួល'; ?>
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><?php echo $currentLang == 'en' ? 'Announcement List' : 'បញ្ជីសេចក្តីប្រកាស'; ?></h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php else: ?>
                        <?php if (count($announcements) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th><?php echo $currentLang == 'en' ? 'Title' : 'ចំណងជើង'; ?></th>
                                            <th><?php echo $currentLang == 'en' ? 'Content' : 'ខ្លឹមសារ'; ?></th>
                                            <th><?php echo $currentLang == 'en' ? 'Event Date' : 'កាលបរិច្ឆេទព្រឹត្តិការណ៍'; ?></th>
                                            <th><?php echo $currentLang == 'en' ? 'Image' : 'រូបភាព'; ?></th>
                                            <th><?php echo $currentLang == 'en' ? 'Active' : 'សកម្ម'; ?></th>
                                            <th><?php echo $currentLang == 'en' ? 'Created' : 'បានបង្កើត'; ?></th>
                                            <th><?php echo $currentLang == 'en' ? 'Actions' : 'សកម្មភាព'; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($announcements as $announcement): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($announcement['title']); ?></td>
                                                <td><?php echo htmlspecialchars(substr($announcement['content'], 0, 100)) . (strlen($announcement['content']) > 100 ? '...' : ''); ?></td>
                                                <td><?php echo $announcement['event_date'] ? formatDate($announcement['event_date']) : ($currentLang == 'en' ? 'N/A' : 'មិនមាន'); ?></td>
                                                <td>
                                                    <?php if ($announcement['image_path']): ?>
                                                        <img src="<?php echo htmlspecialchars($announcement['image_path']); ?>" alt="Announcement Image" style="width: 50px; height: auto;">
                                                    <?php else: ?>
                                                        <?php echo $currentLang == 'en' ? 'No Image' : 'គ្មានរូបភាព'; ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($announcement['is_active']): ?>
                                                        <span class="badge bg-success"><?php echo $currentLang == 'en' ? 'Yes' : 'បាទ/ចាស'; ?></span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger"><?php echo $currentLang == 'en' ? 'No' : 'ទេ'; ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo formatDate($announcement['created_at']); ?></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="index.php?page=manage-announcements&action=edit&id=<?php echo $announcement['id']; ?>" class="btn btn-outline-primary" title="<?php echo $currentLang == 'en' ? 'Edit Announcement' : 'កែសម្រួលសេចក្តីប្រកាស'; ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $announcement['id']; ?>, '<?php echo htmlspecialchars($announcement['title']); ?>')" class="btn btn-outline-danger" title="<?php echo $currentLang == 'en' ? 'Delete Announcement' : 'លុបសេចក្តីប្រកាស'; ?>">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- JavaScript for delete confirmation -->
                            <script>
                                function confirmDelete(announcementId, title) {
                                    const confirmMessage = '<?php echo $currentLang == 'en' ? 'Are you sure you want to delete the announcement' : 'តើអ្នកប្រាកដថាចង់លុបសេចក្តីប្រកាស'; ?> "' + title + '"?';
                                    
                                    if (confirm(confirmMessage)) {
                                        window.location.href = 'index.php?page=manage-announcements&action=delete-announcement&id=' + announcementId;
                                    }
                                }
                            </script>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <?php echo $currentLang == 'en' ? 'No announcements found.' : 'រកមិនឃើញសេចក្តីប្រកាសទេ។'; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <div class="row mt-5">
                <div class="col-12">
                    <nav aria-label="Announcements pagination">
                        <ul class="pagination justify-content-center">
                            <!-- Previous Button -->
                            <?php if ($current_page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link text-danger border-danger" href="?page=manage-announcements&page_num=<?php echo ($current_page - 1); ?>">
                                        <i class="bi bi-chevron-left"></i> <?php echo getLangText('Previous', 'មុន'); ?>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="bi bi-chevron-left"></i> <?php echo getLangText('Previous', 'មុន'); ?>
                                    </span>
                                </li>
                            <?php endif; ?>

                            <!-- Page Numbers: Always show 1, 2, 3 -->
                            <?php for ($i = 1; $i <= min(3, $total_pages); $i++): ?>
                                <?php if ($i == $current_page): ?>
                                    <li class="page-item active">
                                        <span class="page-link bg-danger border-danger"><?php echo $i; ?></span>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item">
                                        <a class="page-link text-danger border-danger" href="?page=manage-announcements&page_num=<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <!-- Show ellipsis if there are more than 3 pages -->
                            <?php if ($total_pages > 3): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            <?php endif; ?>

                            <!-- Next Page Button -->
                            <?php if ($current_page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link text-danger border-danger" href="?page=manage-announcements&page_num=<?php echo ($current_page + 1); ?>">
                                        <?php echo getLangText('Next Page', 'ទំព័របន្ទាប់'); ?> <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <?php echo getLangText('Next Page', 'ទំព័របន្ទាប់'); ?> <i class="bi bi-chevron-right"></i>
                                    </span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            </div>
            <?php endif; ?>
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
