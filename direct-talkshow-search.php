<?php
// Direct talkshow search page that bypasses the index.php routing system
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Set default language if not set
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Talkshow Search - Direct Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-md-8">
                <h1 class="display-4 fw-bold text-danger">Talkshow Program - Direct Search Test</h1>
                <p class="lead">This is a direct test page to check if the search functionality works.</p>
            </div>
        </div>
        
        <!-- Search Form -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Search talkshows..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" class="btn btn-danger">Search</button>
                    <?php if (isset($_GET['search'])): ?>
                        <a href="direct-talkshow-search.php" class="btn btn-outline-secondary ms-2">Clear</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        
        <?php
        // Get talkshows
        $talkshows = [];
        $searchTerm = '';
        $isSearching = false;
        
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $searchTerm = trim($_GET['search']);
            $isSearching = true;
            
            echo "<div class='alert alert-info'>Searching for: \"" . htmlspecialchars($searchTerm) . "\"</div>";
            
            try {
                $stmt = $pdo->prepare("SELECT * FROM talkshows 
                                      WHERE is_active = 1 
                                      AND (title LIKE :search 
                                          OR summary LIKE :search 
                                          OR location LIKE :search)
                                      ORDER BY created_at DESC");
                $searchParam = "%{$searchTerm}%";
                $stmt->bindParam(':search', $searchParam);
                $stmt->execute();
                $talkshows = $stmt->fetchAll();
                
                echo "<div class='alert alert-success'>Search completed. Found " . count($talkshows) . " results.</div>";
            } catch(PDOException $e) {
                echo "<div class='alert alert-danger'>Search error: " . $e->getMessage() . "</div>";
            }
        } else {
            echo "<div class='alert alert-info'>Loading all talkshows...</div>";
            
            try {
                $stmt = $pdo->query("SELECT * FROM talkshows WHERE is_active = 1 ORDER BY created_at DESC");
                $talkshows = $stmt->fetchAll();
                
                echo "<div class='alert alert-success'>Loaded " . count($talkshows) . " talkshows.</div>";
            } catch(PDOException $e) {
                echo "<div class='alert alert-danger'>Database error: " . $e->getMessage() . "</div>";
            }
        }
        
        if (count($talkshows) > 0): ?>
            <div class="row">
                <?php foreach ($talkshows as $talkshow): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <?php if (!empty($talkshow['image_path'])): ?>
                                <img src="<?php echo htmlspecialchars($talkshow['image_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($talkshow['title']); ?>" style="height: 200px; object-fit: cover;">
                            <?php endif; ?>
                            
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($talkshow['title']); ?></h5>
                                <p class="card-text text-muted small">
                                    <i class="fas fa-map-marker-alt me-1"></i> <?php echo htmlspecialchars($talkshow['location']); ?>
                                    <br>
                                    <i class="fas fa-calendar-alt me-1"></i> <?php echo date('F j, Y', strtotime($talkshow['event_date'])); ?>
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
            <div class="alert alert-warning">
                <?php if ($isSearching): ?>
                    <p>No talkshow content found matching your search. Please try different keywords.</p>
                <?php else: ?>
                    <p>No talkshow content available at the moment.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <hr>
        <p><a href="index.php?page=program/talkshow" class="btn btn-secondary">← Back to Main Talkshow Page</a></p>
    </div>
</body>
</html>
