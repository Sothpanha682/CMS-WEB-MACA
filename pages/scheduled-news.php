<?php
// Check if user is logged in
if (!isLoggedIn()) {
    $_SESSION['message'] = "You must be logged in to access this page.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=login');
    exit;
}
 
// Include necessary functions

// Get current time
$current_time = date('Y-m-d H:i:s');

// Get scheduled news articles
try {
    $stmt = $pdo->prepare("SELECT id, title, title_km, is_active, created_at, publish_at FROM news WHERE publish_at IS NOT NULL AND publish_at > :current_time ORDER BY publish_at ASC");
    $stmt->bindParam(':current_time', $current_time);
    $stmt->execute();
    $scheduled_news = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $scheduled_news = [];
    error_log("Error fetching scheduled news: " . $e->getMessage());
}
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><?php echo getLangText('Scheduled News', 'ព័ត៌មានដែលបានកំណត់ពេល'); ?></h1>
        <a href="index.php?page=add-news" class="btn btn-danger">
            <i class="bi bi-plus-circle me-1"></i> <?php echo getLangText('Add New Article', 'បន្ថែមអត្ថបទថ្មី'); ?>
        </a>
    </div>
    
    <?php if (empty($scheduled_news)): ?>
        <div class="alert alert-info">
            <?php echo getLangText('No scheduled news articles found.', 'មិនមានអត្ថបទព័ត៌មានដែលបានកំណត់ពេលទេ។'); ?>
        </div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><?php echo getLangText('Title', 'ចំណងជើង'); ?></th>
                                <th><?php echo getLangText('Status', 'ស្ថានភាព'); ?></th>
                                <th><?php echo getLangText('Scheduled For', 'កំណត់ពេលសម្រាប់'); ?></th>
                                <th><?php echo getLangText('Time Remaining', 'ពេលវេលានៅសល់'); ?></th>
                                <th><?php echo getLangText('Actions', 'សកម្មភាព'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($scheduled_news as $news): ?>
                                <tr>
                                    <td>
                                        <?php echo htmlspecialchars($news['title']); ?>
                                        <?php if (!empty($news['title_km'])): ?>
                                            <br><small class="text-muted"><?php echo htmlspecialchars($news['title_km']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!$news['is_active']): ?>
                                            <span class="badge bg-secondary"><?php echo getLangText('Inactive', 'អសកម្ម'); ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark"><?php echo getLangText('Scheduled', 'កំណត់ពេល'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo formatDate($news['publish_at'], 'M d, Y - H:i'); ?></td>
                                    <td>
                                        <?php
                                        $publish_time = new DateTime($news['publish_at']);
                                        $now = new DateTime();
                                        $interval = $now->diff($publish_time);
                                        
                                        if ($interval->days > 0) {
                                            echo $interval->format('%a days, %h hours');
                                        } else if ($interval->h > 0) {
                                            echo $interval->format('%h hours, %i minutes');
                                        } else {
                                            echo $interval->format('%i minutes');
                                        }
                                        ?>
                                        
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="index.php?page=edit-news&id=<?php echo $news['id']; ?>" class="btn btn-outline-primary" title="<?php echo getLangText('Edit', 'កែសម្រួល'); ?>">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#publishNowModal<?php echo $news['id']; ?>" title="<?php echo getLangText('Publish Now', 'ផ្សាយឥឡូវនេះ'); ?>">
                                                <i class="bi bi-lightning"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $news['id']; ?>" title="<?php echo getLangText('Delete', 'លុប'); ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Publish Now Confirmation Modal -->
                                        <div class="modal fade" id="publishNowModal<?php echo $news['id']; ?>" tabindex="-1" aria-labelledby="publishNowModalLabel<?php echo $news['id']; ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="publishNowModalLabel<?php echo $news['id']; ?>"><?php echo getLangText('Publish Now', 'ផ្សាយឥឡូវនេះ'); ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <?php echo getLangText('Are you sure you want to publish this news article immediately?', 'តើអ្នកពិតជាចង់ផ្សាយអត្ថបទព័ត៌មាននេះភ្លាមៗមែនទេ?'); ?>
                                                        <p class="mt-2 mb-0 fw-bold"><?php echo htmlspecialchars($news['title']); ?></p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo getLangText('Cancel', 'បោះបង់'); ?></button>
                                                        <a href="index.php?page=actions/publish-news-now&id=<?php echo $news['id']; ?>" class="btn btn-success"><?php echo getLangText('Publish Now', 'ផ្សាយឥឡូវនេះ'); ?></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade" id="deleteModal<?php echo $news['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $news['id']; ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel<?php echo $news['id']; ?>"><?php echo getLangText('Confirm Delete', 'បញ្ជាក់ការលុប'); ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <?php echo getLangText('Are you sure you want to delete this news article?', 'តើអ្នកពិតជាចង់លុបអត្ថបទព័ត៌មាននេះមែនទេ?'); ?>
                                                        <p class="mt-2 mb-0 fw-bold"><?php echo htmlspecialchars($news['title']); ?></p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo getLangText('Cancel', 'បោះបង់'); ?></button>
                                                        <a href="index.php?page=actions/delete-news&id=<?php echo $news['id']; ?>" class="btn btn-danger"><?php echo getLangText('Delete', 'លុប'); ?></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
