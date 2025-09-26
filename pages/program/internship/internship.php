<?php
require_once 'includes/functions.php';
require_once 'config/database.php';

global $pdo;

// 1. Check for a search term from the URL, default to an empty string if not found.
$searchTerm = $_GET['search'] ?? '';

try {
    // 2. Build the database query dynamically
    // Base query to get active news
    $sql = "SELECT * FROM intern_news WHERE is_active = 1";

    // If a search term was provided, add a WHERE clause to filter the results
    if (!empty($searchTerm)) {
        // Search in both the title and the excerpt for the term
        $sql .= " AND (title LIKE :searchTerm OR excerpt LIKE :searchTerm)";
    }

    // Add ordering and a limit
    $sql .= " ORDER BY created_at DESC LIMIT 99";

    // Prepare and execute the statement
    $stmt = $pdo->prepare($sql);

    // If searching, bind the search term to the query to prevent SQL injection
    if (!empty($searchTerm)) {
        $searchQuery = "%" . $searchTerm . "%"; // Add wildcards for partial matches
        $stmt->bindParam(':searchTerm', $searchQuery, PDO::PARAM_STR);
    }

    $stmt->execute();
    $intern_news = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // In case of a database error, set news to empty and you might want to log the error
    $intern_news = [];
    // error_log($e->getMessage()); // Optional: for debugging
}
?>

<style>
    :root {
        --brand-red: #dc3545;
    }
    .card-hover {
        border: 1px solid rgba(0,0,0,0.125);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }
    .card-hover:hover {
        transform: translateY(-5px);
        border-color: var(--brand-red);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }
    .card-img-top, .ratio iframe {
        border-radius: 0.375rem 0.375rem 0 0;
    }
    .card-hover .card-title a {
        color: #212529;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .card-hover:hover .card-title a {
        color: var(--brand-red);
    }
    .company-logo {
        width: 50px;
        height: 50px;
        object-fit: contain;
    }
    .logo-placeholder {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
        font-weight: bold;
    }
</style>

<div class="container py-0">

    <div class="row mb-4 text-center">
        <div class="col-lg-8 mx-auto">
            <h1 class="display-6 fs-2 fw-bold text-danger"><?php echo getLangText('Internship Insights', 'ការយល់ដឹងអំពីកម្មសិក្សា'); ?></h1>
            <p class="lead text-muted"><?php echo getLangText('Discover the latest stories, achievements, and updates from our internship program.', 'ស្វែងយល់ពីរឿងរ៉ាវ សមិទ្ធផល និងព័ត៌មានថ្មីៗបំផុតពីកម្មវិធីកម្មសិក្សារបស់យើង។'); ?></p>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-8 col-lg-6 mx-auto">
            <form action="" method="GET" class="input-group">
                <input type="text" name="search" class="form-control form-control-lg" placeholder="Search news..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button class="btn btn-danger" type="submit" id="button-search">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    <?php if (!empty($intern_news)): ?>
        <div class="row g-4">
            <?php foreach ($intern_news as $news): ?>
                <div class="col-md-6 col-lg-4 d-flex align-items-stretch">
                    <div class="card h-100 shadow-sm card-hover w-100">
                        <?php if (!empty($news['video_url'])): ?>
                            <div class="ratio ratio-16x9">
                                <?php echo getVideoEmbedCode($news['video_url']); ?>
                            </div>
                        <?php elseif (!empty($news['image_path']) && file_exists($news['image_path'])): ?>
                            <img src="<?php echo htmlspecialchars($news['image_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($news['title']); ?>">
                        <?php else: ?>
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-newspaper fa-3x text-danger opacity-50"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold">
                                <a href="index.php?page=intership-detail&id=<?php echo $news['id']; ?>" class="stretched-link">
                                    <?php echo htmlspecialchars($news['title']); ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted small mb-3">
                                <i class="fas fa-calendar-alt me-1 text-danger"></i> <?php echo formatDate($news['created_at']); ?>
                            </p>
                            <p class="card-text small flex-grow-1">
                                <?php 
                                $excerpt = strip_tags($news['excerpt']);
                                echo strlen($excerpt) > 120 ? substr($excerpt, 0, 120) . '...' : $excerpt;
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-search fa-4x text-muted mb-3"></i>
            <?php if (!empty($searchTerm)): ?>
                <h4 class="text-muted"><?php echo getLangText('No Results Found', 'រកមិនឃើញលទ្ធផល'); ?></h4>
                <p class="text-muted"><?php echo getLangText('We couldn\'t find any news matching', 'យើងរកមិនឃើញព័ត៌មានណាមួយដែលត្រូវនឹង'); ?> "<strong><?php echo htmlspecialchars($searchTerm); ?></strong>".</p>
            <?php else: ?>
                <h4 class="text-muted"><?php echo getLangText('No News Yet', 'មិនទាន់មានព័ត៌មាន'); ?></h4>
                <p class="text-muted"><?php echo getLangText('There is no internship news available at the moment.', 'មិនទាន់មានព័ត៌មានកម្មសិក្សាណាមួយនៅពេលនេះទេ។'); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <hr class="my-5">

    <div class="row mb-5 text-center">
        <div class="col-lg-8 mx-auto">
            <h1 class="display-6 fs-2 fw-bold text-danger"><?php echo getLangText('Latest Job Openings', 'ឱកាសការងារថ្មីៗ'); ?></h1>
            <p class="lead text-muted"><?php echo getLangText('Explore the latest job opportunities from our online recruitment platform.', 'ស្វែងយល់ពីឱកាសការងារថ្មីៗបំផុតពីវេទិកាជ្រើសរើសបុគ្គលិកអនឡាញរបស់យើង។'); ?></p>
        </div>
    </div>
    
    <?php
    try {
        $stmt = $pdo->prepare("SELECT id, title, company_name, location, job_type, created_at, logo_path FROM job_postings WHERE is_active = 1 ORDER BY created_at DESC LIMIT 6");
        $stmt->execute();
        $job_postings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $job_postings = [];
    }
    ?>
    <?php if (!empty($job_postings)): ?>
        <div class="row g-4">
            <?php foreach ($job_postings as $job): ?>
                <div class="col-md-6 col-lg-4 d-flex align-items-stretch">
                    <div class="card h-100 shadow-sm card-hover w-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-start mb-3">
                                <?php if (!empty($job['logo_path']) && file_exists($job['logo_path'])): ?>
                                    <img src="<?php echo htmlspecialchars($job['logo_path']); ?>" alt="<?php echo htmlspecialchars($job['company_name']); ?> Logo" class="me-3 company-logo border rounded p-1">
                                <?php else: ?>
                                    <div class="border text-danger d-flex align-items-center justify-content-center me-3 rounded logo-placeholder">
                                        <?php echo strtoupper(substr($job['company_name'], 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-0 fw-bold">
                                        <a href="index.php?page=recruitment-job-view&id=<?php echo $job['id']; ?>" class="stretched-link">
                                            <?php echo htmlspecialchars($job['title']); ?>
                                        </a>
                                    </h5>
                                    <p class="card-text text-muted small"><?php echo htmlspecialchars($job['company_name']); ?></p>
                                </div>
                            </div>
                            <div class="mt-auto pt-3">
                                <div class="d-flex justify-content-between align-items-center mb-2 small text-muted">
                                    <span>
                                        <i class="fas fa-map-marker-alt me-1 text-danger"></i><?php echo htmlspecialchars($job['location']); ?>
                                    </span>
                                    <span class="badge bg-danger"><?php echo ucfirst(str_replace('-', ' ', $job['job_type'])); ?></span>
                                </div>
                                <p class="card-text text-muted small">
                                    <i class="fas fa-calendar-alt me-1 text-danger"></i> <?php echo getLangText('Posted', 'បានប្រកាស'); ?> <?php echo formatDate($job['created_at']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-briefcase fa-4x text-muted mb-3"></i>
            <h4 class="text-muted"><?php echo getLangText('No Jobs Found', 'រកមិនឃើញការងារ'); ?></h4>
            <p class="text-muted"><?php echo getLangText('No job opportunities available at the moment. Please check back later.', 'មិនមានឱកាសការងារនៅពេលនេះទេ។ សូមពិនិត្យមើលម្តងទៀតនៅពេលក្រោយ។'); ?></p>
        </div>
    <?php endif; ?>
</div>
