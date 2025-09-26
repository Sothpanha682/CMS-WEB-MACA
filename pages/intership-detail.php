<?php
// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['message'] = "No internship news specified.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=program/internship/internship');
    exit;
}

$id = $_GET['id'];

// Get intern news data
try {
    $stmt = $pdo->prepare("SELECT * FROM intern_news WHERE id = ? AND is_active = 1");
    $stmt->execute([$id]);
    $news_item = $stmt->fetch();
    
    if (!$news_item) {
        $_SESSION['message'] = "Internship news not found.";
        $_SESSION['message_type'] = "danger";
        header('Location: index.php?page=program/internship/internship');
        exit;
    }
} catch(PDOException $e) {
    $_SESSION['message'] = "Error: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=program/internship/internship');
    exit;
}

// Get related intern news
$related_news = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM intern_news WHERE id != ? AND is_active = 1 ORDER BY created_at DESC LIMIT 3");
    $stmt->execute([$id]);
    $related_news = $stmt->fetchAll();
} catch(PDOException $e) {
    // Handle error
    error_log("Error fetching related intern news: " . $e->getMessage());
}
?>

<div class="container py-0">
    <div class="row">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=home">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.php?page=program/internship/internship">Internship News</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($news_item['title']); ?></li>
                </ol>
            </nav>
            
            <h1 class="mb-4"><?php echo htmlspecialchars($news_item['title']); ?></h1>
            
            <div class="mb-3 text-muted">
                <i class="fas fa-calendar-alt me-2"></i> <?php echo formatDate($news_item['created_at']); ?>
            </div>
            
            <?php if (!empty($news_item['video_url'])): ?>
                <div class="ratio ratio-16x9 mb-4">
                    <?php echo getVideoEmbedCode($news_item['video_url']); ?>
                </div>
            <?php elseif (!empty($news_item['image_path'])): ?>
                <img src="<?php echo htmlspecialchars($news_item['image_path']); ?>" class="img-fluid rounded mb-4" alt="<?php echo htmlspecialchars($news_item['title']); ?>">
            <?php endif; ?>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Details</h5>
                    <div class="card-text">
                        <?php echo $news_item['content']; ?>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <a href="index.php?page=program/internship/internship" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Internship News
                </a>
                
                <!-- Social sharing buttons -->
                <div>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" class="btn btn-primary me-2" target="_blank">
                        <i class="fab fa-facebook-f"></i> Share
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($news_item['title']); ?>" class="btn btn-info text-white" target="_blank">
                        <i class="fab fa-twitter"></i> Tweet
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 pt-3">
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Related News</h5>
                </div>
                <div class="card-body">
                    <?php if (count($related_news) > 0): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($related_news as $related): ?>
                                <li class="list-group-item px-0">
                                    <a href="index.php?page=intership-detail&id=<?php echo $related['id']; ?>" class="text-decoration-none">
                                        <h6 class="mb-1"><?php echo htmlspecialchars($related['title']); ?></h6>
                                    </a>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i> <?php echo formatDate($related['created_at']); ?>
                                    </small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="mb-0">No related news found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
