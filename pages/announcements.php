<?php
// Include functions for file uploads and language text
require_once 'includes/functions.php';

// Get current language
$currentLang = getCurrentLanguage();

// Get current page number from URL parameter
$current_page = isset($_GET['page_num']) ? max(1, intval($_GET['page_num'])) : 1;
$announcements_per_page = 10;
$offset = ($current_page - 1) * $announcements_per_page;

// Get total count of announcements for pagination
$total_announcements = 0;
try {
    $count_stmt = $pdo->prepare("SELECT COUNT(*) FROM announcements WHERE is_active = 1");
    $count_stmt->execute();
    $total_announcements = $count_stmt->fetchColumn();
} catch (PDOException $e) {
    error_log("Error counting announcements: " . $e->getMessage());
}

$total_pages = ceil($total_announcements / $announcements_per_page);

// Get announcements for current page
$announcements = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM announcements WHERE is_active = 1 ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $announcements_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching announcements: " . $e->getMessage());
}
?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold text-danger mb-3"><?php echo getLangText('Announcements', 'សេចក្តីប្រកាស'); ?></h1>
            
            <!-- Results Summary -->
            <?php if ($total_announcements > 0): ?>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <p class="text-muted mb-0">
                        <?php 
                        $start = $offset + 1;
                        $end = min($offset + $announcements_per_page, $total_announcements);
                        echo getLangText(
                            "Showing $start-$end of $total_announcements announcements",
                            "បង្ហាញ $start-$end នៃ $total_announcements សេចក្តីប្រកាស"
                        );
                        ?>
                    </p>
                    <small class="text-muted">
                        <?php echo getLangText("Page $current_page of $total_pages", "ទំព័រ $current_page នៃ $total_pages"); ?>
                    </small>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="row">
        <?php if (count($announcements) > 0): ?>
            <?php foreach ($announcements as $announcement): ?>
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-0 hover-shadow">
                    <?php if (!empty($announcement['image_path']) && file_exists($announcement['image_path'])): ?>
                    <img src="<?php echo htmlspecialchars($announcement['image_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($announcement['title']); ?>" style="height: 250px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-danger"><?php echo getLangText('Announcement', 'សេចក្តីប្រកាស'); ?></span>
                            <?php if (!empty($announcement['event_date'])): ?>
                                <small class="text-muted"><i class="bi bi-calendar-event me-1"></i><?php echo formatDate($announcement['event_date']); ?></small>
                            <?php else: ?>
                                <small class="text-muted"><?php echo formatDate($announcement['created_at']); ?></small>
                            <?php endif; ?>
                        </div>
                        <h4 class="card-title">
                            <?php echo $currentLang == 'en' ? htmlspecialchars($announcement['title']) : htmlspecialchars($announcement['title_km']); ?>
                        </h4>
                        <div class="card-text mb-3">
                            <?php echo truncateText(strip_tags($currentLang == 'en' ? $announcement['content'] : $announcement['content_km']), 200); ?>
                        </div>
                        <a href="index.php?page=announcement-detail&id=<?php echo $announcement['id']; ?>" class="btn btn-outline-danger">
                            <?php echo getLangText('Read More', 'អានបន្ថែម'); ?>
                        </a>
                    </div>
                </div>
            </div>
            
            <?php endforeach; ?>
        <?php else: ?>
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle me-2"></i>
                <?php echo getLangText('No announcements available at this time.', 'មិនមានសេចក្តីប្រកាសនៅពេលនេះទេ។'); ?>
            </div>
        </div>
        <?php endif; ?>
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
                            <a class="page-link text-danger border-danger" href="?page=announcements&page_num=<?php echo ($current_page - 1); ?>">
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
                                <a class="page-link text-danger border-danger" href="?page=announcements&page_num=<?php echo $i; ?>"><?php echo $i; ?></a>
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
                            <a class="page-link text-danger border-danger" href="?page=announcements&page_num=<?php echo ($current_page + 1); ?>">
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

<style>
.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.page-link:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white !important;
}

.page-item.active .page-link {
    background-color: #dc3545;
    border-color: #dc3545;
}

.pagination {
    margin-bottom: 0;
}

.pagination .page-link {
    margin: 0 2px;
    border-radius: 8px;
    padding: 8px 12px;
    font-weight: 500;
}

.card {
    border-radius: 12px;
    overflow: hidden;
}

.card-img-top {
    border-radius: 0;
}

.badge {
    font-size: 0.75rem;
    padding: 6px 12px;
    border-radius: 20px;
}
</style>
