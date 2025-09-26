<?php
if (!defined('INCLUDED')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}
require_once 'config/database.php';
require_once 'includes/functions.php';

debugLog('Online Recruitment page loaded.', 'Page Load');

// Get filter parameters
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$job_type = isset($_GET['job_type']) ? sanitize($_GET['job_type']) : '';
$location = isset($_GET['location']) ? sanitize($_GET['location']) : '';

// Build query conditions
$where_conditions = ["is_active = 1"];
$params = [];

if (!empty($search)) {
    $where_conditions[] = "(title LIKE ? OR description LIKE ? OR company_name LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
}

if (!empty($job_type)) {
    $where_conditions[] = "job_type = ?";
    $params[] = $job_type;
}

if (!empty($location)) {
    $where_conditions[] = "location LIKE ?";
    $params[] = "%$location%";
}

debugLog(['where_conditions' => $where_conditions, 'params' => $params], 'Filter Parameters');
$where_clause = "WHERE " . implode(" AND ", $where_conditions);

// Get job postings
try {
    $query = "SELECT * FROM job_postings $where_clause ORDER BY is_featured DESC, created_at DESC";
    debugLog(['query' => $query, 'params' => $params], 'Main Job Query');
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    debugLog($jobs, 'Fetched Jobs');
} catch (PDOException $e) {
    $jobs = [];
    error_log("Error fetching jobs: " . $e->getMessage());
    debugLog("Error fetching jobs: " . $e->getMessage(), 'Main Job Query Error');
}

// Get featured jobs
try {
    $stmt = $pdo->query("SELECT * FROM job_postings WHERE is_active = 1 AND is_featured = 1 ORDER BY created_at DESC LIMIT 3");
    $featured_jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    debugLog($featured_jobs, 'Fetched Featured Jobs');
} catch (PDOException $e) {
    $featured_jobs = [];
    error_log("Error fetching featured jobs: " . $e->getMessage());
    debugLog("Error fetching featured jobs: " . $e->getMessage(), 'Featured Job Query Error');
}

// Get recruitment settings
try {
    $stmt = $pdo->query("SELECT * FROM recruitment_settings LIMIT 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    debugLog($settings, 'Recruitment Settings');
} catch (PDOException $e) {
    $settings = [];
    error_log("Error fetching recruitment settings: " . $e->getMessage());
    debugLog("Error fetching recruitment settings: " . $e->getMessage(), 'Recruitment Settings Error');
}

// Get job statistics
try {
    $stmt = $pdo->query("SELECT COUNT(*) as total_jobs FROM job_postings WHERE is_active = 1");
    $total_jobs = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(DISTINCT company_name) as total_companies FROM job_postings WHERE is_active = 1");
    $total_companies = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) as total_applications FROM job_applications");
    $total_applications = $stmt->fetchColumn();

    debugLog([
        'total_jobs' => $total_jobs,
        'total_companies' => $total_companies,
        'total_applications' => $total_applications
    ], 'Job Statistics');

} catch (PDOException $e) {
    $total_jobs = 0;
    $total_companies = 0;
    $total_applications = 0;
    error_log("Error fetching job statistics: " . $e->getMessage());
    debugLog("Error fetching job statistics: " . $e->getMessage(), 'Job Statistics Error');
}
?>

<?php

require_once 'includes/header.php';

?>

    <style>
        :root {
            --primary-color: #dc3545;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --info-color: #0dcaf0;
            --warning-color: #ffc107;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }

       

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #e74c3c 100%);
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="white" opacity="0.1"><polygon points="0,0 1000,0 1000,100 0,80"/></svg>') no-repeat bottom;
            background-size: cover;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .search-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: -50px;
            position: relative;
            z-index: 3;
        }

        .job-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: none;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .job-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        .job-card.featured {
            border-left: 5px solid var(--warning-color);
            position: relative;
        }

        .job-card.featured::before {
            content: 'FEATURED';
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--warning-color);
            color: var(--dark-color);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: bold;
        }

        .company-logo {
            width: 60px;
            height: 60px;
            background: var(--light-color);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--primary-color);
            font-weight: bold;
        }

        .job-meta {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin: 15px 0;
        }

        .job-meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
            color: var(--secondary-color);
            font-size: 0.9rem;
        }

        .salary-range {
            background: linear-gradient(45deg, var(--success-color), #20c997);
            color: white;
            padding: 8px 15px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .job-type-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .job-type-full-time { background: #e3f2fd; color: #1976d2; }
        .job-type-part-time { background: #f3e5f5; color: #7b1fa2; }
        .job-type-contract { background: #fff3e0; color: #f57c00; }
        .job-type-internship { background: #e8f5e8; color: #388e3c; }

        .stats-section {
            background: var(--light-color);
            padding: 60px 0;
        }

        .stat-card {
            text-align: center;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .stat-label {
            color: var(--secondary-color);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .filter-section {
            background: white;
            padding: 20px 0;
            border-bottom: 1px solid #eee;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .btn-apply {
            background: linear-gradient(45deg, var(--primary-color), #e74c3c);
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* Subtle shadow */
        }

        .btn-apply:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
            color: white;
        }

        /* Small button adjustments for btn-apply */
        .btn-apply.btn-sm {
            padding: 6px 12px; /* Adjust padding for smaller size */
            font-size: 0.875rem; /* Smaller font size */
            line-height: 1.5;
            border-radius: 15px; /* Slightly less rounded for small buttons */
        }

        .no-jobs {
            text-align: center;
            padding: 60px 20px;
            color: var(--secondary-color);
        }

        .no-jobs i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .company-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .btn-outline-primary {
            border-color: var(--primary-color);
            color: var(--primary-color);
            border-radius: 20px; /* More rounded corners */
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white; /* Text color changes to white on hover */
            box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3); /* Subtle shadow on hover */
        }

        /* Small button adjustments for better appearance */
        .btn-sm {
            padding: 6px 12px; /* Adjust padding for smaller size */
            font-size: 0.875rem; /* Smaller font size */
            line-height: 1.5;
            border-radius: 15px; /* Slightly less rounded for small buttons */
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 60px 0;
            }
            
            .search-card {
                margin: 20px;
                padding: 20px;
            }
            
            .job-meta {
                flex-direction: column;
                gap: 10px;
            }
            
            .stat-number {
                font-size: 2rem;
            }
        }
    </style>
    

   
    <section class="hero-section" style=" border-radius: 18px;">
        <div class="container hero-content">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-4"><?php echo getLangText('Find Your Dream Career', 'ស្វែងរកអាជីពក្នុងក្តីស្រមៃរបស់អ្នក'); ?></h1>
                    <p class="lead mb-4">
                        <?php echo getLangText('Join ' . htmlspecialchars($settings['company_name'] ?? 'MACA Education') . ' and be part of our mission to transform education and empower students worldwide.', 'ចូលរួមជាមួយ ' . htmlspecialchars($settings['company_name'] ?? 'MACA Education') . ' និងក្លាយជាផ្នែកមួយនៃបេសកកម្មរបស់យើងក្នុងការផ្លាស់ប្តូរការអប់រំ និងផ្តល់អំណាចដល់សិស្សានុសិស្សទូទាំងពិភពលោក។'); ?>
                    </p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <span class="badge bg-light text-dark px-3 py-2">
                            <i class="fas fa-briefcase me-2"></i><?php echo $total_jobs; ?> <?php echo getLangText('Open Positions', 'មុខតំណែងទំនេរ'); ?>
                        </span>
                        <span class="badge bg-light text-dark px-3 py-2">
                            <i class="fas fa-building me-2"></i><?php echo $total_companies; ?> <?php echo getLangText('Companies', 'ក្រុមហ៊ុន'); ?>
                        </span>
                        <span class="badge bg-light text-dark px-3 py-2">
                            <i class="fas fa-users me-2"></i><?php echo $total_applications; ?> <?php echo getLangText('Applications', 'ពាក្យសុំ'); ?>
                        </span>
                    </div>
                  
                </div>
            </div>
        </div>
    </section>

    <!-- Search Section -->
    <section class="container">
        <div class="search-card">
            <form method="GET" action="index.php">
                <input type="hidden" name="page" value="program/online-recruitment">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="<?php echo htmlspecialchars($search); ?>" placeholder="">
                            <label for="search"><i class="fas fa-search me-2"></i><?php echo getLangText('Job title, keywords...', 'ចំណងជើងការងារ, ពាក្យគន្លឹះ...'); ?></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating">
                            <select class="form-select" id="job_type" name="job_type">
                                <option value=""><?php echo getLangText('All Types', 'គ្រប់ប្រភេទ'); ?></option>
                                <option value="full-time" <?php echo ($job_type == 'full-time') ? 'selected' : ''; ?>><?php echo getLangText('Full-time', 'ពេញម៉ោង'); ?></option>
                                <option value="part-time" <?php echo ($job_type == 'part-time') ? 'selected' : ''; ?>><?php echo getLangText('Part-time', 'ក្រៅម៉ោង'); ?></option>
                                <option value="contract" <?php echo ($job_type == 'contract') ? 'selected' : ''; ?>><?php echo getLangText('Contract', 'កិច្ចសន្យា'); ?></option>
                                <option value="internship" <?php echo ($job_type == 'internship') ? 'selected' : ''; ?>><?php echo getLangText('Internship', 'កម្មសិក្សា'); ?></option>
                            </select>
                            <label for="job_type"><i class="fas fa-clock me-2"></i><?php echo getLangText('Job Type', 'ប្រភេទការងារ'); ?></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="location" name="location" 
                                   value="<?php echo htmlspecialchars($location); ?>" placeholder="">
                            <label for="location"><i class="fas fa-map-marker-alt me-2"></i><?php echo getLangText('Location', 'ទីតាំង'); ?></label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary h-100 w-100">
                            <i class="fas fa-search me-2"></i><?php echo getLangText('Search', 'ស្វែងរក'); ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Featured Jobs Section -->
    <?php if (!empty($featured_jobs)): ?>
    <section class="container my-5">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-5">
                    <i class="fas fa-star text-warning me-2"></i><?php echo getLangText('Featured Opportunities', 'ឱកាសពិសេស'); ?>
                </h2>
            </div>
        </div>
        <div class="row">
            <?php foreach ($featured_jobs as $job): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="job-card featured h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="company-logo me-3">
                                <?php
                                    if (!empty($job['logo_path']) && file_exists($job['logo_path'])) {
                                        echo '<img src="' . htmlspecialchars($job['logo_path']) . '" alt="' . htmlspecialchars($job['company_name']) . ' Logo" style="max-width: 100%; max-height: 100%;">';
                                    } else {
                                        echo strtoupper(substr($job['company_name'], 0, 2));
                                    }
                                ?>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1"><?php echo htmlspecialchars($job['title']); ?></h5>
                                <p class="text-muted mb-0"><?php echo htmlspecialchars($job['company_name']); ?></p>
                            </div>
                        </div>
                        
                        <div class="job-meta">
                            <div class="job-meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?php echo htmlspecialchars($job['location']); ?></span>
                            </div>
                            <div class="job-meta-item">
                                <i class="fas fa-clock"></i>
                                <span class="job-type-badge job-type-<?php echo $job['job_type']; ?>">
                                    <?php echo ucfirst(str_replace('-', ' ', $job['job_type'])); ?>
                                </span>
                            </div>
                        </div>

                        <?php if ($job['salary_min'] && $job['salary_max']): ?>
                        <div class="salary-range mb-3">
                            <i class="fas fa-dollar-sign me-1"></i>
                            <?php echo number_format($job['salary_min']); ?> - <?php echo number_format($job['salary_max']); ?> <?php echo $job['currency']; ?>
                        </div>
                        <?php endif; ?>

                        <p class="card-text text-muted">
                            <?php echo truncateText(strip_tags($job['description']), 120); ?>
                        </p>

                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt me-1"></i>
                                <?php echo formatDate($job['created_at'], 'M d, Y'); ?>
                            </small>
                            <div class="d-flex gap-2 mt-3"> <!-- Added mt-3 for spacing -->
                                <a href="index.php?page=recruitment-job-view&id=<?php echo $job['id']; ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i><?php echo getLangText('View Detail', 'មើលលម្អិត'); ?>
                                </a>
                                <a href="index.php?page=job-apply&id=<?php echo $job['id']; ?>" class=" btn btn-apply btn-sm">
                                    <?php echo getLangText('Apply Now', 'ដាក់ពាក្យឥឡូវនេះ'); ?> <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

 
    <!-- All Jobs Section -->
    <section class="container my-5" id="all-job-opportunities">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>
                        <i class="fas fa-briefcase text-primary me-2"></i>
                        <?php echo getLangText('All Job Opportunities', 'ឱកាសការងារទាំងអស់'); ?>
                        <span class="badge bg-primary ms-2"><?php echo count($jobs); ?></span>
                    </h2>
                    <?php if (!empty($search) || !empty($job_type) || !empty($location)): ?>
                    <a href="index.php?page=program/online-recruitment" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i><?php echo getLangText('Clear Filters', 'ជម្រះតម្រង'); ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php 
        debugLog(count($jobs), 'Number of Jobs Before Display Check');
        if (empty($jobs)): ?>
        <div class="no-jobs">
            <i class="fas fa-search"></i>
            <h3><?php echo getLangText('No Jobs Found', 'រកមិនឃើញការងារ'); ?></h3>
            <p><?php echo getLangText('We couldn\'t find any jobs matching your criteria. Try adjusting your search filters.', 'យើងរកមិនឃើញការងារណាមួយដែលត្រូវនឹងលក្ខណៈវិនិច្ឆ័យរបស់អ្នកទេ។ សូមព្យាយាមកែសម្រួលតម្រងស្វែងរករបស់អ្នក។'); ?></p>
            </a>
        </div>
        <?php else: ?>
        <div class="row">
            <?php foreach ($jobs as $job): ?>
            <?php debugLog($job, 'Job Data in Loop'); ?>
            <div class="col-12 mb-4">
                <div class="job-card <?php echo $job['is_featured'] ? 'featured' : ''; ?>">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-start">
                                    <div class="company-logo me-3">
                                        <?php 
                                            if (!empty($job['logo_path']) && file_exists($job['logo_path'])) {
                                                echo '<img src="' . htmlspecialchars($job['logo_path']) . '" alt="' . htmlspecialchars($job['company_name']) . ' Logo" style="max-width: 100%; max-height: 100%;">';
                                            } else {
                                                echo strtoupper(substr($job['company_name'], 0, 2));
                                            }
                                        ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h4 class="card-title mb-2">
                                            <a href="index.php?page=recruitment-job-view&id=<?php echo $job['id']; ?>" 
                                               class="text-decoration-none text-dark">
                                                <?php echo htmlspecialchars($job['title']); ?>
                                            </a>
                                        </h4>
                                        <h6 class="text-primary mb-3"><?php echo htmlspecialchars($job['company_name']); ?></h6>
                                        
                                        <div class="job-meta">
                                            <div class="job-meta-item">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <span><?php echo htmlspecialchars($job['location']); ?></span>
                                            </div>
                                            <div class="job-meta-item">
                                                <i class="fas fa-clock"></i>
                                                <span class="job-type-badge job-type-<?php echo $job['job_type']; ?>">
                                                    <?php echo ucfirst(str_replace('-', ' ', $job['job_type'])); ?>
                                                </span>
                                            </div>
                                            <div class="job-meta-item">
                                                <i class="fas fa-calendar-alt"></i>
                                                <span>Posted <?php echo formatDate($job['created_at'], 'M d, Y'); ?></span>
                                            </div>
                                            <?php if ($job['application_deadline']): ?>
                                            <div class="job-meta-item">
                                                <i class="fas fa-hourglass-end"></i>
                                                <span>Deadline: <?php echo formatDate($job['application_deadline'], 'M d, Y'); ?></span>
                                            </div>
                                            <?php endif; ?>
                                        </div>

                                        <p class="card-text text-muted mt-3">
                                            <?php echo truncateText(strip_tags($job['description']), 200); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <?php if ($job['salary_min'] && $job['salary_max']): ?>
                                <div class="salary-range mb-3">
                                    <i class="fas fa-dollar-sign me-1"></i>
                                    <?php echo number_format($job['salary_min']); ?> - <?php echo number_format($job['salary_max']); ?> <?php echo $job['currency']; ?>
                                </div>
                                <?php endif; ?>
                                
                                <div class="d-flex flex-column gap-2 mt-3"> <!-- Added mt-3 for spacing -->
                                <a href="index.php?page=recruitment-job-view&id=<?php echo $job['id']; ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i><?php echo getLangText('View Details', 'មើលលម្អិត'); ?>
                                </a>
                                <a href="index.php?page=job-apply&id=<?php echo $job['id']; ?>" class="btn-apply btn btn-sm">
                                    <i class="fas fa-paper-plane me-1"></i><?php echo getLangText('Apply Now', 'ដាក់ពាក្យឥឡូវនេះ'); ?>
                                </a>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </section>

    <!-- Company Info Section -->
    <?php if (!empty($settings['company_description'])): ?>
    
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            console.log('Form submitted!');
            // Store a flag in sessionStorage to indicate that a search was performed
            sessionStorage.setItem('scrollToJobs', 'true');
        });

        // Prevent browser from restoring scroll position
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }

        // Scroll to top immediately when the DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            window.scrollTo(0, 0);

            // Check if we need to scroll to the jobs section after a search
            if (sessionStorage.getItem('scrollToJobs') === 'true') {
                sessionStorage.removeItem('scrollToJobs'); // Clear the flag
                const allJobsSection = document.getElementById('all-job-opportunities');
                if (allJobsSection) {
                    allJobsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });
    </script>

