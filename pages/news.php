<div class="container py-4">
    <h1 class="fw-bold text-danger mb-4"><?php echo getLangText('News', 'ព័ត៌មាន'); ?></h1>
    
    <?php
    // Pagination settings
    $items_per_page = 10;
    $current_page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
    $current_page = max(1, $current_page); // Ensure page is at least 1
    $offset = ($current_page - 1) * $items_per_page;
    
    // Get total count of news
    try {
        $count_stmt = $pdo->prepare("SELECT COUNT(*) FROM news");
        $count_stmt->execute();
        $total_items = $count_stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Error counting news: " . $e->getMessage());
        $total_items = 0;
    }
    
    $total_pages = ceil($total_items / $items_per_page);
    $current_page = min($current_page, max(1, $total_pages)); // Ensure page doesn't exceed total
    
    // Calculate display range
    $start_item = ($current_page - 1) * $items_per_page + 1;
    $end_item = min($current_page * $items_per_page, $total_items);
    
    // Get paginated news with explicit sorting by created_at in descending order (newest first)
    try {
        $stmt = $pdo->prepare("SELECT * FROM news ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $news_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching paginated news: " . $e->getMessage());
        $news_items = [];
    }
    ?>
    
    <div class="row">
        <?php
        if (count($news_items) > 0):
            foreach ($news_items as $news):
        ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 hover-shadow">
                <?php if (!empty($news['image_path'])): ?>
                <img src="<?php echo htmlspecialchars($news['image_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($news['title']); ?>" style="height: 200px; object-fit: cover;">
                <?php endif; ?>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-danger"><?php echo getLangText('News', 'ព័ត៌មាន'); ?></span>
                        <?php if (!empty($news['event_date'])): ?>
                            <small class="text-muted"><?php echo getLangText('Event Date: ', 'កាលបរិច្ឆេទព្រឹត្តិការណ៍: '); ?><?php echo formatDate($news['event_date']); ?></small>
                        <?php else: ?>
                            <small class="text-muted"><?php echo formatDate($news['created_at']); ?></small>
                        <?php endif; ?>
                    </div>
                    <h5 class="card-title"><?php echo htmlspecialchars($news['title']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($news['summary']); ?></p>
                </div>
                <div class="card-footer bg-white border-0">
                    <a href="index.php?page=news-detail&id=<?php echo $news['id']; ?>" class="btn btn-outline-danger"><?php echo getLangText('Read More', 'អានបន្ថែម'); ?></a>
                </div>
            </div>
        </div>
        <?php
            endforeach;
        else:
        ?>
        <div class="col-12">
            <div class="alert alert-info"><?php echo getLangText('No news available at this time.', 'មិនមានព័ត៌មាននៅពេលនេះទេ។'); ?></div>
        </div>
        <?php endif; ?>
    </div>
    
    <?php if ($total_pages > 1): ?>
<!-- Pagination -->
<div class="mt-4">
    <div class="text-center mb-3">
        <p class="text-muted mb-2">
            <?php echo getLangText(
                "Showing {$start_item}-{$end_item} of {$total_items} news",
                "បង្ហាញ {$start_item}-{$end_item} នៃព័ត៌មានសរុប {$total_items}"
            ); ?>
        </p>
        <small class="text-muted">
            <?php echo getLangText("Page $current_page of $total_pages", "ទំព័រ $current_page នៃ $total_pages"); ?>
        </small>
    </div>
    
    <div class="d-flex justify-content-center">
        <nav aria-label="News pagination">
            <ul class="pagination">
            <!-- Previous button -->
            <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                <a class="page-link text-danger" href="<?php echo ($current_page > 1) ? 'index.php?page=news&page_num=' . ($current_page - 1) : '#'; ?>" aria-label="Previous">
                    <span aria-hidden="true"><i class="bi bi-chevron-left"></i> <?php echo getLangText('Previous', 'មុន'); ?></span>
                </a>
            </li>
            
            <!-- Page 1 -->
            <li class="page-item <?php echo ($current_page == 1) ? 'active' : ''; ?>">
                <a class="page-link <?php echo ($current_page == 1) ? 'bg-danger border-danger' : 'text-danger border-danger'; ?>" href="index.php?page=news&page_num=1">1</a>
            </li>
            
            <!-- Page 2 (if exists) -->
            <?php if ($total_pages >= 2): ?>
            <li class="page-item <?php echo ($current_page == 2) ? 'active' : ''; ?>">
                <a class="page-link <?php echo ($current_page == 2) ? 'bg-danger border-danger' : 'text-danger border-danger'; ?>" href="index.php?page=news&page_num=2">2</a>
            </li>
            <?php endif; ?>
            
            <!-- Page 3 (if exists) -->
            <?php if ($total_pages >= 3): ?>
            <li class="page-item <?php echo ($current_page == 3) ? 'active' : ''; ?>">
                <a class="page-link <?php echo ($current_page == 3) ? 'bg-danger border-danger' : 'text-danger border-danger'; ?>" href="index.php?page=news&page_num=3">3</a>
            </li>
            <?php endif; ?>
            
            <!-- Ellipsis if more than 3 pages -->
            <?php if ($total_pages > 3): ?>
            <li class="page-item disabled">
                <span class="page-link">...</span>
            </li>
            <?php endif; ?>
            
            <!-- Next button -->
            <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                <a class="page-link text-danger" href="<?php echo ($current_page < $total_pages) ? 'index.php?page=news&page_num=' . ($current_page + 1) : '#'; ?>" aria-label="Next">
                    <span aria-hidden="true"><?php echo getLangText('Next Page', 'ទំព័របន្ទាប់'); ?> <i class="bi bi-chevron-right"></i></span>
                </a>
            </li>
        </ul>
    </nav>
</div>
</div>
<?php endif; ?>
</div>
