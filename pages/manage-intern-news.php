<?php
// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    $_SESSION['message'] = "You must be logged in as an admin to access this page.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=login');
    exit;
}

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    try {
        // No need to get video_url before deleting, as it's not a local file
        // Delete the record
        $stmt = $pdo->prepare("DELETE FROM intern_news WHERE id = :id");
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Intern news deleted successfully.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error deleting intern news.";
            $_SESSION['message_type'] = "danger";
        }
    } catch(PDOException $e) {
        $_SESSION['message'] = "Database error: " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }
    header('Location: index.php?page=manage-intern-news');
    exit;
}

// Handle toggle featured action
if (isset($_GET['action']) && $_GET['action'] == 'toggle_featured' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    try {
        $stmt = $pdo->prepare("UPDATE intern_news SET is_featured = NOT is_featured WHERE id = :id");
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Featured status updated successfully.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error updating featured status.";
            $_SESSION['message_type'] = "danger";
        }
    } catch(PDOException $e) {
        $_SESSION['message'] = "Database error: " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }
    header('Location: index.php?page=manage-intern-news');
    exit;
}

// Handle toggle active action
if (isset($_GET['action']) && $_GET['action'] == 'toggle_active' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    try {
        $stmt = $pdo->prepare("UPDATE intern_news SET is_active = NOT is_active WHERE id = :id");
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Status updated successfully.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error updating status.";
            $_SESSION['message_type'] = "danger";
        }
    } catch(PDOException $e) {
        $_SESSION['message'] = "Database error: " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }
    header('Location: index.php?page=manage-intern-news');
    exit;
}

// Pagination
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Get total count
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM intern_news");
    $total_news = $stmt->fetchColumn();
    $total_pages = ceil($total_news / $limit);
} catch(PDOException $e) {
    $total_news = 0;
    $total_pages = 1;
}

// Get intern news
try {
    $stmt = $pdo->prepare("SELECT id, title, content, excerpt, intern_name, intern_university, intern_company, category, video_url, is_featured, is_active, created_at, updated_at FROM intern_news ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $intern_news = $stmt->fetchAll();
} catch(PDOException $e) {
    $intern_news = [];
    $error_message = "Error fetching intern news: " . $e->getMessage();
}

// Category labels
$category_labels = [
    'success_story' => 'Success Story',
    'new_cohort' => 'New Cohort',
    'achievement' => 'Achievement',
    'alumni_success' => 'Alumni Success',
    'project_spotlight' => 'Project Spotlight',
    'innovation' => 'Innovation',
    'program_update' => 'Program Update',
    'graduation' => 'Graduation'
];

// Category colors
$category_colors = [
    'success_story' => 'success',
    'new_cohort' => 'primary',
    'achievement' => 'warning',
    'alumni_success' => 'info',
    'project_spotlight' => 'danger',
    'innovation' => 'dark',
    'program_update' => 'secondary',
    'graduation' => 'success'
];
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 text-danger">Manage Intern News</h1>
                <a href="index.php?page=add-intern-news" class="btn btn-danger">
                    <i class="fas fa-plus me-2"></i>Add New Intern News
                </a>
            </div>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Intern News Articles (<?php echo $total_news; ?> total)</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (count($intern_news) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Title</th>
                                        <th>Intern</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Featured</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($intern_news as $news): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if (isset($news['video_url']) && $news['video_url']): ?>
                                                        <?php
                                                            $video_id = '';
                                                            $thumbnail_url = '';
                                                            if (strpos($news['video_url'], 'youtube.com') !== false || strpos($news['video_url'], 'youtu.be') !== false) {
                                                                preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $news['video_url'], $matches);
                                                                if (isset($matches[1])) {
                                                                    $video_id = $matches[1];
                                                                    $thumbnail_url = "https://img.youtube.com/vi/{$video_id}/default.jpg";
                                                                }
                                                            } elseif (strpos($news['video_url'], 'facebook.com') !== false) {
                                                                // Facebook video thumbnails are harder to get directly without API.
                                                                // For simplicity, we'll use a generic placeholder or try to embed directly.
                                                                // For now, just a placeholder.
                                                                $thumbnail_url = 'https://via.placeholder.com/50x50?text=FB+Video';
                                                            }
                                                        ?>
                                                        <?php if ($thumbnail_url): ?>
                                                            <img src="<?php echo htmlspecialchars($thumbnail_url); ?>" alt="Video Thumbnail" 
                                                                 class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                        <?php else: ?>
                                                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                                 style="width: 50px; height: 50px;">
                                                                <i class="fas fa-video text-muted"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                             style="width: 50px; height: 50px;">
                                                            <i class="fas fa-user-graduate text-muted"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <strong><?php echo htmlspecialchars($news['title']); ?></strong>
                                                        <?php if ($news['excerpt']): ?>
                                                            <br><small class="text-muted"><?php echo htmlspecialchars(truncateText($news['excerpt'], 60)); ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if ($news['intern_name']): ?>
                                                    <strong><?php echo htmlspecialchars($news['intern_name']); ?></strong><br>
                                                    <small class="text-muted">
                                                        <?php echo htmlspecialchars($news['intern_university'] ?? ''); ?>
                                                        <?php if ($news['intern_company']): ?>
                                                            <br>@ <?php echo htmlspecialchars($news['intern_company']); ?>
                                                        <?php endif; ?>
                                                    </small>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $category_colors[$news['category']]; ?>">
                                                    <?php echo $category_labels[$news['category']]; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="index.php?page=manage-intern-news&action=toggle_active&id=<?php echo $news['id']; ?>" 
                                                   class="btn btn-sm btn-outline-<?php echo $news['is_active'] ? 'success' : 'secondary'; ?>">
                                                    <?php echo $news['is_active'] ? 'Active' : 'Inactive'; ?>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="index.php?page=manage-intern-news&action=toggle_featured&id=<?php echo $news['id']; ?>" 
                                                   class="btn btn-sm btn-outline-<?php echo $news['is_featured'] ? 'warning' : 'secondary'; ?>">
                                                    <?php echo $news['is_featured'] ? 'Featured' : 'Regular'; ?>
                                                </a>
                                            </td>
                                            <td>
                                                <small><?php echo formatDate($news['created_at'], 'M d, Y'); ?></small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="index.php?page=edit-intern-news&id=<?php echo $news['id']; ?>" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="index.php?page=manage-intern-news&action=delete&id=<?php echo $news['id']; ?>" 
                                                       class="btn btn-sm btn-outline-danger"
                                                       onclick="return confirm('Are you sure you want to delete this intern news? This action cannot be undone.')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if ($total_pages > 1): ?>
                            <div class="card-footer">
                                <nav aria-label="Intern news pagination">
                                    <ul class="pagination justify-content-center mb-0">
                                        <?php if ($page > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="index.php?page=manage-intern-news&p=<?php echo ($page - 1); ?>">Previous</a>
                                            </li>
                                        <?php endif; ?>

                                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                                <a class="page-link" href="index.php?page=manage-intern-news&p=<?php echo $i; ?>"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>

                                        <?php if ($page < $total_pages): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="index.php?page=manage-intern-news&p=<?php echo ($page + 1); ?>">Next</a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                        <?php endif; ?>

                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No intern news found</h5>
                            <p class="text-muted">Start by adding your first intern news article.</p>
                            <a href="index.php?page=add-intern-news" class="btn btn-danger">
                                <i class="fas fa-plus me-2"></i>Add Intern News
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
