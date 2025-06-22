<?php
require_once 'includes/functions.php'; // Include the functions file
require_once 'config/database.php'; // Include the database connection

// Get all active roadshow entries
global $pdo; // Ensure PDO object is accessible
$roadshows = [];
$searchTerm = '';
$isSearching = false;

// Check if search was submitted
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = trim($_GET['search']);
    $isSearching = true;
    
    try {
        // Search in title, summary, location, and description
        $stmt = $pdo->prepare("SELECT * FROM roadshow 
                              WHERE is_active = 1 
                              AND (title LIKE :search 
                                  OR description LIKE :search 
                                  OR location LIKE :search)
                              ORDER BY created_at DESC");
        $searchParam = "%{$searchTerm}%";
        $stmt->bindParam(':search', $searchParam);
        $stmt->execute();
        $roadshows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error searching roadshows: " . $e->getMessage());
        $roadshows = [];
    }
} else {
    // No search, get all roadshows
    try {
        $stmt = $pdo->query("SELECT * FROM roadshow WHERE is_active = 1 ORDER BY created_at DESC");
        $roadshows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error fetching roadshows: " . $e->getMessage());
    }
}
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-4 fw-bold text-danger">Roadshow Program</h1>
            <p class="lead">Join our educational roadshows featuring industry experts and educational professionals discussing important topics in education and career development.</p>
        </div>
    </div>
    
    <!-- Search Form -->
    <div class="row mb-4">
        <div class="col-md-6">
            <form method="GET" action="index.php" class="d-flex">
                <input type="hidden" name="page" value="program/roadshow/roadshow">
                <input type="text" name="search" class="form-control me-2" placeholder="Search roadshows..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit" class="btn btn-danger">Search</button>
                <?php if ($isSearching): ?>
                    <a href="index.php?page=program/roadshow/roadshow" class="btn btn-outline-secondary ms-2">Clear</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
    
    <?php if ($isSearching): ?>
        <div class="alert alert-info">
            <?php if (count($roadshows) > 0): ?>
                Found <?php echo count($roadshows); ?> result<?php echo count($roadshows) != 1 ? 's' : ''; ?> for "<?php echo htmlspecialchars($searchTerm); ?>"
            <?php else: ?>
                No roadshows found matching "<?php echo htmlspecialchars($searchTerm); ?>"
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (count($roadshows) > 0): ?>
        <div class="row">
            <?php foreach ($roadshows as $roadshow): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <?php if (!empty($roadshow['image_path'])): ?>
                            <img src="<?php echo htmlspecialchars($roadshow['image_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($roadshow['title']); ?>">
                        <?php endif; ?>
                        
                        <?php if (!empty($roadshow['video_url'])): ?>
                            <div class="ratio ratio-16x9">
                                <?php echo getVideoEmbedCode($roadshow['video_url']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($roadshow['title']); ?></h5>
                            <p class="card-text text-muted small">
                                <i class="fas fa-map-marker-alt me-1"></i> <?php echo htmlspecialchars($roadshow['location']); ?>
                                <br>
                                <i class="fas fa-calendar-alt me-1"></i> <?php echo formatDate($roadshow['event_date']); ?>
                            </p>
                            <div class="card-text mb-3">
                                <?php 
                                $summary = strip_tags($roadshow['summary'] ?? '');
                                echo strlen($summary) > 150 ? substr($summary, 0, 150) . '...' : $summary;
                                ?>
                            </div>
                            <a href="index.php?page=roadshow-detail&id=<?php echo $roadshow['id']; ?>" class="btn btn-outline-danger">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <?php if ($isSearching): ?>
                <p>No roadshow content found matching your search. Please try different keywords.</p>
            <?php else: ?>
                <p>No roadshow content available at the moment. Please check back later.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
