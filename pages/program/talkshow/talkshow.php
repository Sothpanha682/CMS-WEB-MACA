<?php
require_once 'includes/functions.php'; // Include the functions file
require_once 'config/database.php'; // Include the database connection

// Get all active talkshow entries
global $pdo; // Ensure PDO object is accessible
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
                                  OR content LIKE :search)
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
    // No search, get all talkshows
    try {
        $stmt = $pdo->query("SELECT * FROM talkshows WHERE is_active = 1 ORDER BY created_at DESC");
        $talkshows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error fetching talkshows: " . $e->getMessage());
    }
}
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-4 fw-bold text-danger">Talkshow Program</h1>
            <p class="lead">Join our educational talkshows featuring industry experts and educational professionals discussing important topics in education and career development.</p>
        </div>
    </div>
    
    <!-- Search Form -->
    <div class="row mb-4">
        <div class="col-md-6">
            <form method="GET" action="index.php" class="d-flex">
                <input type="hidden" name="page" value="program/talkshow/talkshow">
                <input type="text" name="search" class="form-control me-2" placeholder="Search talkshows..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit" class="btn btn-danger">Search</button>
                <?php if ($isSearching): ?>
                    <a href="index.php?page=program/talkshow/talkshow" class="btn btn-outline-secondary ms-2">Clear</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
    
    <?php if ($isSearching): ?>
        <div class="alert alert-info">
            <?php if (count($talkshows) > 0): ?>
                Found <?php echo count($talkshows); ?> result<?php echo count($talkshows) != 1 ? 's' : ''; ?> for "<?php echo htmlspecialchars($searchTerm); ?>"
            <?php else: ?>
                No talkshows found matching "<?php echo htmlspecialchars($searchTerm); ?>"
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (count($talkshows) > 0): ?>
        <div class="row">
            <?php foreach ($talkshows as $talkshow): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <?php if (!empty($talkshow['image_path'])): ?>
                            <img src="<?php echo htmlspecialchars($talkshow['image_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($talkshow['title']); ?>">
                        <?php endif; ?>
                        
                        <?php if (!empty($talkshow['video_url'])): ?>
                            <div class="ratio ratio-16x9">
                                <?php echo getVideoEmbedCode($talkshow['video_url']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($talkshow['title']); ?></h5>
                            <p class="card-text text-muted small">
                                <i class="fas fa-map-marker-alt me-1"></i> <?php echo htmlspecialchars($talkshow['location']); ?>
                                <br>
                                <i class="fas fa-calendar-alt me-1"></i> <?php echo formatDate($talkshow['event_date']); ?>
                            </p>
                            <div class="card-text mb-3">
                                <?php 
                                $summary = strip_tags($talkshow['summary']);
                                echo strlen($summary) > 150 ? substr($summary, 0, 150) . '...' : $summary;
                                ?>
                            </div>
                            <a href="index.php?page=talkshow-detail&id=<?php echo $talkshow['id']; ?>" class="btn btn-outline-danger">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <?php if ($isSearching): ?>
                <p>No talkshow content found matching your search. Please try different keywords.</p>
            <?php else: ?>
                <p>No talkshow content available at the moment. Please check back later.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
