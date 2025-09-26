<?php
// Separate talkshow search handler to bypass routing issues
session_start();

// Define a constant to prevent direct access to included files
if (!defined('INCLUDED')) {
    define('INCLUDED', true);
}

// Include database connection and functions
require_once 'config/database.php';
require_once 'includes/functions.php';

// Set default language if not set
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en';
}

// Handle language change
if (isset($_GET['lang']) && ($_GET['lang'] == 'en' || $_GET['lang'] == 'kh')) {
    $_SESSION['lang'] = $_GET['lang'];
}

// Get all active talkshow entries
$talkshows = [];
$searchTerm = '';
$isSearching = false;

// Check if search was submitted
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = trim($_GET['search']);
    $isSearching = true;
    
    try {
        // Search in title, summary, location, and description
        $stmt = $pdo->prepare("SELECT * FROM talkshows 
                              WHERE is_active = 1 
                              AND (title LIKE :search 
                                  OR summary LIKE :search 
                                  OR location LIKE :search
                                  OR description LIKE :search)
                              ORDER BY created_at DESC");
        $searchParam = "%{$searchTerm}%";
        $stmt->bindParam(':search', $searchParam);
        $stmt->execute();
        $talkshows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error searching talkshows: " . $e->getMessage());
        $talkshows = [];
    }
} else {
    // No search, redirect to main talkshow page
    header('Location: index.php?page=program/talkshow');
    exit;
}

// Function to extract video ID and create proper embed code
function getVideoEmbedCode($video_url) {
    if (empty($video_url)) {
        return '';
    }
    
    $embed_code = '';
    
    // YouTube handling
    if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
        $video_id = '';
        
        // Extract video ID from different YouTube URL formats
        if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $video_url, $matches)) {
            $video_id = $matches[1];
        } elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $video_url, $matches)) {
            $video_id = $matches[1];
        } elseif (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $video_url, $matches)) {
            $video_id = $matches[1];
        }
        
        if ($video_id) {
            $embed_code = '<iframe width="100%" height="315" src="https://www.youtube.com/embed/' . $video_id . '" 
                          title="YouTube video player" frameborder="0" 
                          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                          allowfullscreen></iframe>';
        }
    }
    // Facebook handling
    elseif (strpos($video_url, 'facebook.com') !== false || strpos($video_url, 'fb.watch') !== false) {
        $embed_code = '<iframe src="https://www.facebook.com/plugins/video.php?href=' . urlencode($video_url) . 
                     '&width=500&show_text=false&height=280&appId" width="100%" height="280" 
                     style="border:none;overflow:hidden" scrolling="no" frameborder="0" 
                     allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>';
    }
    // Vimeo handling
    elseif (strpos($video_url, 'vimeo.com') !== false) {
        if (preg_match('/vimeo\.com\/(\d+)/', $video_url, $matches)) {
            $video_id = $matches[1];
            $embed_code = '<iframe src="https://player.vimeo.com/video/' . $video_id . '" 
                          width="100%" height="315" frameborder="0" 
                          allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
        }
    }
    // Generic video file handling
    elseif (preg_match('/\.(mp4|webm|ogg)$/i', $video_url)) {
        $embed_code = '<video width="100%" height="315" controls>
                      <source src="' . htmlspecialchars($video_url) . '" type="video/mp4">
                      Your browser does not support the video tag.
                      </video>';
    }
    
    return $embed_code;
}

// Include header
include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-4 fw-bold text-danger">Talkshow Program - Search Results</h1>
            <p class="lead">Search results for your talkshow query.</p>
        </div>
    </div>
    
    <!-- Search Form -->
    <div class="row mb-4">
        <div class="col-md-6">
            <form method="GET" action="talkshow-search.php" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search talkshows..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-search me-1"></i>Search
                </button>
                <a href="index.php?page=program/talkshow" class="btn btn-outline-secondary ms-2">
                    <i class="fas fa-times me-1"></i>Clear
                </a>
            </form>
        </div>
    </div>
    
    <!-- Search Results Info -->
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        <?php if (count($talkshows) > 0): ?>
            Found <?php echo count($talkshows); ?> result<?php echo count($talkshows) != 1 ? 's' : ''; ?> for "<?php echo htmlspecialchars($searchTerm); ?>"
        <?php else: ?>
            No talkshows found matching "<?php echo htmlspecialchars($searchTerm); ?>"
        <?php endif; ?>
    </div>

    <!-- Navigation -->
    <div class="mb-3">
        <a href="index.php?page=program/talkshow" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to All Talkshows
        </a>
    </div>

    <!-- Talkshow Results -->
    <?php if (count($talkshows) > 0): ?>
        <div class="row">
            <?php foreach ($talkshows as $talkshow): ?>
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card h-100 shadow-sm">
                        <!-- Video Section -->
                        <?php if (!empty($talkshow['video_url'])): ?>
                            <div class="ratio ratio-16x9">
                                <?php echo getVideoEmbedCode($talkshow['video_url']); ?>
                            </div>
                        <?php elseif (!empty($talkshow['image_path'])): ?>
                            <!-- Fallback to image if no video -->
                            <img src="<?php echo htmlspecialchars($talkshow['image_path']); ?>" 
                                 class="card-img-top" 
                                 alt="<?php echo htmlspecialchars($talkshow['title']); ?>"
                                 style="height: 250px; object-fit: cover;">
                        <?php else: ?>
                            <!-- Placeholder if no video or image -->
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                                <i class="fas fa-video fa-3x text-muted"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($talkshow['title']); ?></h5>
                            
                            <!-- Event Details -->
                            <div class="mb-3">
                                <?php if (!empty($talkshow['location'])): ?>
                                    <p class="card-text text-muted small mb-1">
                                        <i class="fas fa-map-marker-alt me-1"></i> 
                                        <?php echo htmlspecialchars($talkshow['location']); ?>
                                    </p>
                                <?php endif; ?>
                                
                                <?php if (!empty($talkshow['event_date'])): ?>
                                    <p class="card-text text-muted small mb-1">
                                        <i class="fas fa-calendar-alt me-1"></i> 
                                        <?php echo formatDate($talkshow['event_date']); ?>
                                    </p>
                                <?php endif; ?>
                                
                                <?php if (!empty($talkshow['video_url'])): ?>
                                    <p class="card-text text-success small mb-1">
                                        <i class="fas fa-play-circle me-1"></i> 
                                        Video Available
                                    </p>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Summary -->
                            <div class="card-text mb-3">
                                <?php 
                                $summary = strip_tags($talkshow['summary']);
                                echo strlen($summary) > 150 ? substr($summary, 0, 150) . '...' : $summary;
                                ?>
                            </div>
                            
                            <!-- Action Button -->
                            <a href="index.php?page=talkshow-detail&id=<?php echo $talkshow['id']; ?>" 
                               class="btn btn-outline-danger">
                                <i class="fas fa-eye me-1"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>No results found</strong><br>
            No talkshow content found matching your search. Please try different keywords or 
            <a href="index.php?page=program/talkshow" class="alert-link">view all talkshows</a>.
        </div>
    <?php endif; ?>
</div>

<!-- Additional CSS for better video display -->
<style>
.ratio-16x9 {
    position: relative;
    width: 100%;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
}

.ratio-16x9 iframe,
.ratio-16x9 video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}
</style>

<?php
// Include footer
include 'includes/footer.php';
?>
