<?php
if (!isset($_GET['id'])) {
    $_SESSION['message'] = getLangText("No news article specified.", "មិនមានអត្ថបទព័ត៌មានត្រូវបានបញ្ជាក់។");
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=news');
    exit;
}

$news_id = $_GET['id'];
$news = getNewsById($pdo, $news_id);

if (!$news) {
    $_SESSION['message'] = getLangText("News article not found.", "រកមិនឃើញអត្ថបទព័ត៌មាន។");
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=news');
    exit;
}

// Get language-specific content
$lang = getCurrentLanguage();
$title = ($lang == 'en') ? $news['title'] : $news['title_km'];
$content = ($lang == 'en') ? $news['content'] : $news['content_km'];
?>

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php"><?php echo getLangText('Home', 'ទំព័រដើម'); ?></a></li>
            <li class="breadcrumb-item"><a href="index.php?page=news"><?php echo getLangText('News', 'ព័ត៌មាន'); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($title); ?></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <article class="blog-post">
                <h1 class="blog-post-title mb-3"><?php echo htmlspecialchars($title); ?></h1>
                
                <div class="d-flex align-items-center mb-4">
                    <span class="badge bg-danger me-2"><?php echo getLangText('News', 'ព័ត៌មាន'); ?></span>
                    
                    <?php if (!empty($news['event_date'])): ?>
                    <div>
                        <i class="bi bi-calendar-event text-muted me-1"></i>
                        <span class="text-muted"><?php echo getLangText('Event Date: ', 'កាលបរិច្ឆេទព្រឹត្តិការណ៍: '); ?><?php echo formatDate($news['event_date'], 'F j, Y'); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($news['image_path'])): ?>
                <div class="mb-4">
                    <img src="<?php echo htmlspecialchars($news['image_path']); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($title); ?>">
                </div>
                <?php endif; ?>
                
                <div class="blog-post-content">
                    <?php echo $content; ?>
                </div>
            </article>
            
            <div class="mt-5">
                <a href="index.php?page=news" class="btn btn-outline-danger">
                    <i class="bi bi-arrow-left me-1"></i> <?php echo getLangText('Back to News', 'ត្រឡប់ទៅព័ត៌មាន'); ?>
                </a>
            </div>
        </div>
        
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><?php echo getLangText('Recent News', 'ព័ត៌មានថ្មីៗ'); ?></h5>
                </div>
                <div class="card-body">
                    <?php
                    $recent_news = getNews($pdo, 5);
                    if (count($recent_news) > 0):
                    ?>
                    <ul class="list-unstyled">
                        <?php foreach ($recent_news as $item): 
                            if ($item['id'] == $news_id) continue; // Skip current article
                            $item_title = ($lang == 'en') ? $item['title'] : $item['title_km'];
                        ?>
                        <li class="mb-3 pb-3 border-bottom">
                            <a href="index.php?page=news-detail&id=<?php echo $item['id']; ?>" class="text-decoration-none">
                                <div class="d-flex">
                                    <?php if (!empty($item['image_path'])): ?>
                                    <div class="flex-shrink-0 me-3">
                                        <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item_title); ?>" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                    </div>
                                    <?php endif; ?>
                                    <div>
                                        <h6 class="mb-1"><?php echo htmlspecialchars($item_title); ?></h6>
                                        <small class="text-muted">
                                            <?php if (!empty($item['event_date'])): ?>
                                                <?php echo formatDate($item['event_date']); ?>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                    <p class="mb-0"><?php echo getLangText('No recent news available.', 'មិនមានព័ត៌មានថ្មីៗទេ។'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-white">
                    <a href="index.php?page=news" class="btn btn-outline-danger btn-sm w-100">
                        <?php echo getLangText('View All News', 'មើលព័ត៌មានទាំងអស់'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
