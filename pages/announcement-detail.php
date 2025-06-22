<?php


// Get announcement ID from URL
$announcement_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Check if ID is valid
if ($announcement_id <= 0) {
    // Redirect to announcements page if ID is invalid
    header('Location: index.php?page=announcements');
    exit;
}

// Get announcement details
$announcement = getAnnouncementById($pdo, $announcement_id);

// If announcement not found, redirect to announcements page
if (!$announcement) {
    header('Location: index.php?page=announcements');
    exit;
}

// Set page title
$page_title = $announcement['title'];
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.php?page=announcements">Announcements</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $announcement['title']; ?></li>
                </ol>
            </nav>
            
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-danger">Announcement</span>
                        <small class="text-muted"><?php echo formatDate($announcement['created_at']); ?></small>
                    </div>
                    
                    <h1 class="card-title h2 mb-4"><?php echo $announcement['title']; ?></h1>
                    
                    <?php if ($announcement['image_path']): ?>
                    <div class="text-center mb-4">
                        <img src="<?php echo $announcement['image_path']; ?>" class="img-fluid rounded" alt="<?php echo $announcement['title']; ?>" style="max-height: 400px;">
                    </div>
                    <?php endif; ?>
                    
                    <div class="announcement-content">
                        <?php echo $announcement['content']; ?>
                    </div>
                    
                    <?php if (!empty($announcement['attachment_path'])): ?>
                    <div class="mt-4 p-3 bg-light rounded">
                        <h5 class="mb-3">Attachments</h5>
                        <a href="<?php echo $announcement['attachment_path']; ?>" class="btn btn-sm btn-outline-secondary" target="_blank">
                            <i class="fas fa-file-download me-2"></i> Download Attachment
                        </a>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="index.php?page=announcements" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Back to Announcements
                            </a>
                            <div class="share-buttons">
                                <span class="me-2">Share:</span>
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" class="text-primary me-2">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($announcement['title']); ?>" target="_blank" class="text-info me-2">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="mailto:?subject=<?php echo urlencode($announcement['title']); ?>&body=<?php echo urlencode('Check out this announcement: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" class="text-secondary">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Related Announcements -->
            <?php
            try {
                $stmt = $pdo->prepare("SELECT * FROM announcements WHERE id != :current_id ORDER BY created_at DESC LIMIT 3");
                $stmt->bindParam(':current_id', $announcement_id, PDO::PARAM_INT);
                $stmt->execute();
                $related_announcements = $stmt->fetchAll();
                
                if (count($related_announcements) > 0):
            ?>
            <div class="mt-5">
                <h3 class="mb-4">Other Announcements</h3>
                <div class="row">
                    <?php foreach ($related_announcements as $related): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm hover-shadow">
                            <?php if ($related['image_path']): ?>
                            <img src="<?php echo $related['image_path']; ?>" class="card-img-top" alt="<?php echo $related['title']; ?>" style="height: 160px; object-fit: cover;">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $related['title']; ?></h5>
                                <p class="card-text small text-muted"><?php echo formatDate($related['created_at']); ?></p>
                                <a href="index.php?page=announcement-detail&id=<?php echo $related['id']; ?>" class="btn btn-sm btn-outline-danger">Read More</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php 
                endif;
            } catch(PDOException $e) {
                // Silently handle error
            }
            ?>
        </div>
    </div>
</div>
