<?php
// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['message'] = "No roadshow specified.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=program/roadshow');
    exit;
}

$id = $_GET['id'];

// Get roadshow data
try {
    $stmt = $pdo->prepare("SELECT * FROM roadshow WHERE id = ? AND is_active = 1");
    $stmt->execute([$id]);
    $roadshow = $stmt->fetch();
    
    if (!$roadshow) {
        $_SESSION['message'] = "Roadshow not found.";
        $_SESSION['message_type'] = "danger";
        header('Location: index.php?page=program/roadshow');
        exit;
    }
} catch(PDOException $e) {
    $_SESSION['message'] = "Error: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=program/roadshow');
    exit;
}

// Get related roadshows
$related_roadshows = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM roadshow WHERE id != ? AND is_active = 1 ORDER BY event_date DESC LIMIT 3");
    $stmt->execute([$id]);
    $related_roadshows = $stmt->fetchAll();
} catch(PDOException $e) {
    // Handle error
    error_log("Error fetching related roadshows: " . $e->getMessage());
}
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=home">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.php?page=program/roadshow">Roadshow</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($roadshow['title']); ?></li>
                </ol>
            </nav>
            
            <h1 class="mb-4"><?php echo htmlspecialchars($roadshow['title']); ?></h1>
            
            <div class="mb-3 text-muted">
                <i class="fas fa-map-marker-alt me-2"></i> <?php echo htmlspecialchars($roadshow['location']); ?>
                <span class="mx-2">|</span>
                <i class="fas fa-calendar-alt me-2"></i> <?php echo formatDate($roadshow['event_date']); ?>
            </div>
            
            <?php if (!empty($roadshow['image_path']) && file_exists($roadshow['image_path'])): ?>
                <img src="<?php echo htmlspecialchars($roadshow['image_path']); ?>" class="img-fluid rounded mb-4" alt="<?php echo htmlspecialchars($roadshow['title']); ?>">
            <?php endif; ?>
            
            <?php if (!empty($roadshow['video_url'])): ?>
                <div class="ratio ratio-16x9 mb-4">
                    <?php
                    // Extract video ID and create embed code
                    $video_url = $roadshow['video_url'];
                    $embed_code = '';
                    
                    if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
                        // YouTube
                        if (strpos($video_url, 'youtube.com/watch?v=') !== false) {
                            $video_id = explode('v=', $video_url)[1];
                            if (strpos($video_id, '&') !== false) {
                                $video_id = explode('&', $video_id)[0];
                            }
                        } elseif (strpos($video_url, 'youtu.be/') !== false) {
                            $video_id = explode('youtu.be/', $video_url)[1];
                        }
                        
                        if (isset($video_id)) {
                            $embed_code = '<iframe src="https://www.youtube.com/embed/' . $video_id . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                        }
                    } elseif (strpos($video_url, 'facebook.com') !== false) {
                        // Facebook
                        $embed_code = '<iframe src="https://www.facebook.com/plugins/video.php?href=' . urlencode($video_url) . '&show_text=false" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>';
                    }
                    
                    echo $embed_code;
                    ?>
                </div>
            <?php endif; ?>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Description</h5>
                    <div class="card-text">
                        <?php echo $roadshow['description']; ?>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <a href="index.php?page=program/roadshow" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Roadshows
                </a>
                
                <!-- Social sharing buttons -->
                <div>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" class="btn btn-primary me-2" target="_blank">
                        <i class="fab fa-facebook-f"></i> Share
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($roadshow['title']); ?>" class="btn btn-info text-white" target="_blank">
                        <i class="fab fa-twitter"></i> Tweet
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Related Roadshows</h5>
                </div>
                <div class="card-body">
                    <?php if (count($related_roadshows) > 0): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($related_roadshows as $related): ?>
                                <li class="list-group-item px-0">
                                    <a href="index.php?page=roadshow-detail&id=<?php echo $related['id']; ?>" class="text-decoration-none">
                                        <h6 class="mb-1"><?php echo htmlspecialchars($related['title']); ?></h6>
                                    </a>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i> <?php echo formatDate($related['event_date']); ?>
                                    </small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="mb-0">No related roadshows found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
