<?php
// Get job ID
$job_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$job_id) {
    header('Location: index.php?page=program/online-recruitment');
    exit;
}

// Get job details
try {
    $stmt = $pdo->prepare("SELECT * FROM job_postings WHERE id = ? AND is_active = 1");
    $stmt->execute([$job_id]);
    $job = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$job) {
        $_SESSION['message'] = "Job not found or no longer available.";
        $_SESSION['message_type'] = "warning";
        header('Location: index.php?page=program/online-recruitment');
        exit;
    }
    
    // Update view count
    $stmt = $pdo->prepare("UPDATE job_postings SET views_count = views_count + 1 WHERE id = ?");
    $stmt->execute([$job_id]);
    
} catch (PDOException $e) {
    $_SESSION['message'] = "Error loading job details.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=program/online-recruitment');
    exit;
}

// Get related jobs
try {
    $stmt = $pdo->prepare("
        SELECT * FROM job_postings 
        WHERE id != ? AND is_active = 1 AND (
            job_type = ? OR 
            location LIKE ? OR 
            company_name = ?
        ) 
        ORDER BY is_featured DESC, created_at DESC 
        LIMIT 3
    ");
    $stmt->execute([$job_id, $job['job_type'], '%' . $job['location'] . '%', $job['company_name']]);
    $related_jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $related_jobs = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($job['title']); ?> - <?php echo htmlspecialchars($job['company_name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }

        .job-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #e74c3c 100%);
            color: white;
            padding: 60px 0;
        }

        .company-logo {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            font-weight: bold;
            backdrop-filter: blur(10px);
        }

        .job-meta {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin: 20px 0;
        }

        .job-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.1);
            padding: 8px 15px;
            border-radius: 25px;
            font-size: 0.9rem;
            backdrop-filter: blur(10px);
        }

        .main-content {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-top: -30px;
            position: relative;
            z-index: 2;
        }

        .sidebar {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            position: sticky;
            top: 20px;
        }

        .section-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--light-color);
        }

        .salary-highlight {
            background: linear-gradient(45deg, var(--success-color), #20c997);
            color: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 20px;
        }

        .salary-amount {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .btn-apply-large {
            background: linear-gradient(45deg, var(--primary-color), #e74c3c);
            border: none;
            color: white;
            padding: 15px 30px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            width: 100%;
            text-align: center;
        }

        .btn-apply-large:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
            color: white;
        }

        .job-type-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .job-type-full-time { background: #e3f2fd; color: #1976d2; }
        .job-type-part-time { background: #f3e5f5; color: #7b1fa2; }
        .job-type-contract { background: #fff3e0; color: #f57c00; }
        .job-type-internship { background: #e8f5e8; color: #388e3c; }

        .info-card {
            background: var(--light-color);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .info-card h6 {
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .related-job-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
            text-decoration: none;
            color: inherit;
        }

        .related-job-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            color: inherit;
            text-decoration: none;
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 0;
        }

        .breadcrumb-item a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: white;
        }

        .deadline-warning {
            background: linear-gradient(45deg, var(--warning-color), #ffb347);
            color: var(--dark-color);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
        }

        .content-section {
            margin-bottom: 30px;
        }

        .content-section ul {
            padding-left: 0;
            list-style: none;
        }

        .content-section ul li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
            position: relative;
            padding-left: 25px;
        }

        .content-section ul li:before {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: var(--success-color);
            position: absolute;
            left: 0;
            top: 8px;
        }

        .content-section ul li:last-child {
            border-bottom: none;
        }

        @media (max-width: 768px) {
            .job-header {
                padding: 40px 0;
            }
            
            .job-meta {
                flex-direction: column;
                gap: 10px;
            }
            
            .main-content {
                margin-top: 20px;
            }
            
            .sidebar {
                position: static;
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Job Header -->
    <section class="job-header">
        <div class="container">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.php?page=program/online-recruitment">Jobs</a></li>
                    <li class="breadcrumb-item active"><?php echo htmlspecialchars($job['title']); ?></li>
                </ol>
            </nav>

            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-start">
                        <div class="company-logo me-4">
                            <?php echo strtoupper(substr($job['company_name'], 0, 2)); ?>
                        </div>
                        <div>
                            <h1 class="display-5 fw-bold mb-2"><?php echo htmlspecialchars($job['title']); ?></h1>
                            <h4 class="mb-3 opacity-75"><?php echo htmlspecialchars($job['company_name']); ?></h4>
                            
                            <div class="job-meta">
                                <div class="job-meta-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?php echo htmlspecialchars($job['location']); ?></span>
                                </div>
                                <div class="job-meta-item">
                                    <i class="fas fa-clock"></i>
                                    <span><?php echo ucfirst(str_replace('-', ' ', $job['job_type'])); ?></span>
                                </div>
                                <div class="job-meta-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Posted <?php echo formatDate($job['created_at'], 'M d, Y'); ?></span>
                                </div>
                                <div class="job-meta-item">
                                    <i class="fas fa-eye"></i>
                                    <span><?php echo $job['views_count']; ?> views</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-4 mt-md-0">
                    <?php if ($job['is_featured']): ?>
                    <div class="badge bg-warning text-dark px-3 py-2 mb-3">
                        <i class="fas fa-star me-1"></i>FEATURED JOB
                    </div>
                    <?php endif; ?>
                    <div>
                        <a href="index.php?page=job-apply&id=<?php echo $job['id']; ?>" class="btn btn-light btn-lg">
                            <i class="fas fa-paper-plane me-2"></i>Apply Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container py-5">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="main-content p-4">
                    <!-- Job Description -->
                    <div class="content-section">
                        <h3 class="section-title">
                            <i class="fas fa-file-alt me-2"></i>Job Description
                        </h3>
                        <div class="job-description">
                            <?php echo nl2br(htmlspecialchars($job['description'])); ?>
                        </div>
                    </div>

                    <!-- Requirements -->
                    <div class="content-section">
                        <h3 class="section-title">
                            <i class="fas fa-list-check me-2"></i>Requirements
                        </h3>
                        <div class="job-requirements">
                            <?php 
                            $requirements = explode("\n", $job['requirements']);
                            if (count($requirements) > 1): ?>
                                <ul>
                                    <?php foreach ($requirements as $req): ?>
                                        <?php if (trim($req)): ?>
                                            <li><?php echo htmlspecialchars(trim($req)); ?></li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <?php echo nl2br(htmlspecialchars($job['requirements'])); ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Benefits -->
                    <?php if (!empty($job['benefits'])): ?>
                    <div class="content-section">
                        <h3 class="section-title">
                            <i class="fas fa-gift me-2"></i>Benefits & Perks
                        </h3>
                        <div class="job-benefits">
                            <?php 
                            $benefits = explode("\n", $job['benefits']);
                            if (count($benefits) > 1): ?>
                                <ul>
                                    <?php foreach ($benefits as $benefit): ?>
                                        <?php if (trim($benefit)): ?>
                                            <li><?php echo htmlspecialchars(trim($benefit)); ?></li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <?php echo nl2br(htmlspecialchars($job['benefits'])); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Apply Button -->
                    <div class="text-center mt-5">
                        <a href="index.php?page=job-apply&id=<?php echo $job['id']; ?>" class="btn-apply-large">
                            <i class="fas fa-paper-plane me-2"></i>Apply for This Position
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sidebar p-4">
                    <!-- Salary Information -->
                    <?php if ($job['salary_min'] && $job['salary_max']): ?>
                    <div class="salary-highlight">
                        <div class="salary-amount">
                            $<?php echo number_format($job['salary_min']); ?> - $<?php echo number_format($job['salary_max']); ?>
                        </div>
                        <div><?php echo $job['currency']; ?> per month</div>
                    </div>
                    <?php endif; ?>

                    <!-- Application Deadline -->
                    <?php if ($job['application_deadline']): ?>
                    <div class="deadline-warning">
                        <i class="fas fa-hourglass-end me-2"></i>
                        Application Deadline: <?php echo formatDate($job['application_deadline'], 'M d, Y'); ?>
                    </div>
                    <?php endif; ?>

                    <!-- Job Information -->
                    <div class="info-card">
                        <h6><i class="fas fa-info-circle me-2"></i>Job Information</h6>
                        <div class="row">
                            <div class="col-6">
                                <strong>Job Type:</strong>
                            </div>
                            <div class="col-6">
                                <span class="job-type-badge job-type-<?php echo $job['job_type']; ?>">
                                    <?php echo ucfirst(str_replace('-', ' ', $job['job_type'])); ?>
                                </span>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <strong>Location:</strong>
                            </div>
                            <div class="col-6">
                                <?php echo htmlspecialchars($job['location']); ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <strong>Posted:</strong>
                            </div>
                            <div class="col-6">
                                <?php echo formatDate($job['created_at'], 'M d, Y'); ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <strong>Applications:</strong>
                            </div>
                            <div class="col-6">
                                <span class="badge bg-info"><?php echo $job['applications_count']; ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="info-card">
                        <h6><i class="fas fa-envelope me-2"></i>Contact Information</h6>
                        <p class="mb-2">
                            <strong>Email:</strong><br>
                            <a href="mailto:<?php echo htmlspecialchars($job['contact_email']); ?>">
                                <?php echo htmlspecialchars($job['contact_email']); ?>
                            </a>
                        </p>
                        <?php if ($job['contact_phone']): ?>
                        <p class="mb-0">
                            <strong>Phone:</strong><br>
                            <a href="tel:<?php echo htmlspecialchars($job['contact_phone']); ?>">
                                <?php echo htmlspecialchars($job['contact_phone']); ?>
                            </a>
                        </p>
                        <?php endif; ?>
                    </div>

                    <!-- Share Job -->
                    <div class="info-card">
                        <h6><i class="fas fa-share-alt me-2"></i>Share This Job</h6>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" 
                               target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($job['title'] . ' at ' . $job['company_name']); ?>" 
                               target="_blank" class="btn btn-outline-info btn-sm">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" 
                               target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <button class="btn btn-outline-secondary btn-sm" onclick="copyToClipboard()">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Related Jobs -->
                <?php if (!empty($related_jobs)): ?>
                <div class="sidebar p-4 mt-4">
                    <h5 class="section-title">
                        <i class="fas fa-briefcase me-2"></i>Related Jobs
                    </h5>
                    <?php foreach ($related_jobs as $related): ?>
                    <a href="index.php?page=recruitment-job-detail&id=<?php echo $related['id']; ?>" class="related-job-card d-block">
                        <h6 class="mb-2"><?php echo htmlspecialchars($related['title']); ?></h6>
                        <p class="text-muted mb-2"><?php echo htmlspecialchars($related['company_name']); ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                <?php echo htmlspecialchars($related['location']); ?>
                            </small>
                            <?php if ($related['salary_min'] && $related['salary_max']): ?>
                            <small class="text-success fw-bold">
                                $<?php echo number_format($related['salary_min']); ?>-<?php echo number_format($related['salary_max']); ?>
                            </small>
                            <?php endif; ?>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function copyToClipboard() {
            navigator.clipboard.writeText(window.location.href).then(function() {
                alert('Job link copied to clipboard!');
            });
        }

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>
