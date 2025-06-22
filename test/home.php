<!-- Hero Section with Modern Slideshow -->
<div class="hero-slideshow-modern">
    <div class="slideshow-container-modern">
        <?php
        // Get all hero slides from database
        try {
            $stmt = $pdo->prepare("SELECT * FROM site_settings WHERE setting_key LIKE 'hero_image_%' ORDER BY setting_key ASC");
            $stmt->execute();
            $hero_slides = $stmt->fetchAll();
            
            if (count($hero_slides) > 0) {
                foreach ($hero_slides as $index => $slide) {
                    echo '<div class="slide-modern">';
                    echo '<div class="slide-image-container">';
                    echo '<img src="' . $slide['setting_value'] . '" alt="MACA Slide ' . ($index + 1) . '" class="slide-image-modern">';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                // Default slides if none found in database
                $default_slides = [
                    ['image' => 'assets/images/hero-image-1.jpg'],
                    ['image' => 'assets/images/hero-image-2.jpg'],
                    ['image' => 'assets/images/hero-image-3.jpg']
                ];
                
                foreach ($default_slides as $index => $slide) {
                    echo '<div class="slide-modern">';
                    echo '<div class="slide-image-container">';
                    echo '<img src="' . $slide['image'] . '" alt="MACA Slide ' . ($index + 1) . '" class="slide-image-modern" onerror="this.src=\'https://via.placeholder.com/1200x500?text=MACA+Education\'">';
                    echo '</div>';
                    echo '</div>';
                }
            }
        } catch(PDOException $e) {
            // Display default slide on error
            echo '<div class="slide-modern">';
            echo '<div class="slide-image-container">';
            echo '<img src="assets/images/hero-image-1.jpg" alt="MACA Education" class="slide-image-modern" onerror="this.src=\'https://via.placeholder.com/1200x500?text=MACA+Education\'">';
            echo '</div>';
            echo '</div>';
        }
        ?>

        <!-- Navigation arrows -->
        <button class="slide-arrow prev-arrow" aria-label="Previous slide">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="slide-arrow next-arrow" aria-label="Next slide">
            <i class="fas fa-chevron-right"></i>
        </button>

        <!-- Progress bar -->
        <div class="slide-progress-container">
            <div class="slide-progress-bar"></div>
        </div>
    </div>
    
    <!-- Navigation dots -->
    <div class="slideshow-dots-modern">
        <?php
        $slide_count = count($hero_slides) > 0 ? count($hero_slides) : 3; // Default to 3 if no slides found
        for ($i = 0; $i < $slide_count; $i++) {
            echo '<button class="dot-modern" aria-label="Go to slide ' . ($i + 1) . '"></button>';
        }
        ?>
    </div>
</div>

<div class="container mb-5">
    <div class="row text-center mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-danger">Latest Announcements</h2>
            <p class="text-muted">Stay updated with important information from MACA</p>
        </div>
    </div>
    
    <div class="row">
        <?php
        $announcements = getAnnouncements($pdo, 3);
        if (count($announcements) > 0):
            foreach ($announcements as $announcement):
        ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 hover-shadow">
                <?php if ($announcement['image_path']): ?>
                <img src="<?php echo $announcement['image_path']; ?>" class="card-img-top" alt="<?php echo $announcement['title']; ?>" style="height: 200px; object-fit: cover;">
                <?php endif; ?>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-danger">Announcement</span>
                        <small class="text-muted"><?php echo formatDate($announcement['created_at']); ?></small>
                    </div>
                    <h5 class="card-title"><?php echo $announcement['title']; ?></h5>
                    <p class="card-text"><?php echo truncateText(strip_tags($announcement['content']), 120); ?></p>
                </div>
                <div class="card-footer bg-white border-0">
                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#announcementModal<?php echo $announcement['id']; ?>">
        Read More
    </button>
                </div>
                <!-- Modal for full announcement -->
<div class="modal fade" id="announcementModal<?php echo $announcement['id']; ?>" tabindex="-1" aria-labelledby="announcementModalLabel<?php echo $announcement['id']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="announcementModalLabel<?php echo $announcement['id']; ?>"><?php echo $announcement['title']; ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if ($announcement['image_path']): ?>
                <img src="<?php echo $announcement['image_path']; ?>" class="img-fluid rounded mb-3" alt="<?php echo $announcement['title']; ?>">
                <?php endif; ?>
                <div class="announcement-content">
                    <?php echo $announcement['content']; ?>
                </div>
                <div class="text-muted mt-3">
                    <small>Posted on: <?php echo formatDate($announcement['created_at']); ?></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
            </div>
        </div>
        <?php
            endforeach;
        else:
        ?>
        <div class="col-12">
            <div class="alert alert-info">No announcements available at this time.</div>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="text-center mt-3">
        <a href="index.php?page=announcements" class="btn btn-outline-danger">View All Announcements</a>
    </div>
</div>

<div class="bg-light py-5 mb-5">
    <div class="container">
        <div class="row text-center mb-4">
            <div class="col-12">
                <h2 class="fw-bold text-danger">Our Educational Programs</h2>
                <p class="text-muted">Discover the various educational opportunities we offer</p>
            </div>
        </div>
        
        <div class="row">
            <?php
            $programs = [
                [
                    'title' => 'Online Learning',
                    'description' => 'Access quality education from anywhere with our comprehensive online courses.',
                    'icon' => 'fa-laptop',
                    'link' => 'index.php?page=program/online-learning'
                ],
                [
                    'title' => 'Career Counselling',
                    'description' => 'Get expert guidance to make informed decisions about your academic and career path.',
                    'icon' => 'fa-compass',
                    'link' => 'index.php?page=program/career-counselling'
                ],
                [
                    'title' => 'Internship Program',
                    'description' => 'Gain practical experience through our partnerships with leading organizations.',
                    'icon' => 'fa-briefcase',
                    'link' => '#'
                ],
                [
                    'title' => 'Online Recruitment',
                    'description' => 'Connect with employers looking for talented individuals like you.',
                    'icon' => 'fa-handshake',
                    'link' => '#'
                ]
            ];
            
            foreach ($programs as $program):
            ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm border-0 text-center hover-shadow">
                    <div class="card-body">
                        <div class="icon-circle bg-danger text-white mx-auto mb-4">
                            <i class="fas <?php echo $program['icon']; ?> fa-2x"></i>
                        </div>
                        <h5 class="card-title"><?php echo $program['title']; ?></h5>
                        <p class="card-text"><?php echo $program['description']; ?></p>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <a href="<?php echo $program['link']; ?>" class="btn btn-sm btn-outline-danger">Learn More</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Popular Majors Section - New Design -->
<div class="popular-majors-section py-5 mb-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <div class="section-icon-container">
                <div class="section-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
            </div>
            <h2 class="section-title">Popular <span class="text-danger">Majors</span></h2>
            <p class="section-subtitle">Explore top academic fields chosen by our students</p>
            <div class="section-divider"></div>
        </div>
        
        <div class="majors-grid">
            <?php
            // Fetch popular majors from database
            try {
                $stmt = $pdo->query("SELECT * FROM popular_majors WHERE is_active = 1 ORDER BY display_order ASC LIMIT 4");
                $popular_majors = $stmt->fetchAll();
                
                if (count($popular_majors) > 0):
                    foreach ($popular_majors as $index => $major):
                        // Generate random salary range for demonstration
                        $min_salary = rand(35, 70) * 1000;
                        $max_salary = $min_salary + rand(20, 50) * 1000;
                        $salary_range = '$' . number_format($min_salary) . ' - $' . number_format($max_salary);
            ?>
            <div class="major-card-new">
                <div class="major-image-container">
                    <?php if (!empty($major['image_path'])): ?>
                        <img src="<?php echo $major['image_path']; ?>" alt="<?php echo $major['title']; ?>" class="major-image">
                    <?php else: ?>
                        <div class="major-placeholder">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                    <?php endif; ?>
                    
                    
<div class="major-overlay">
    <button type="button" class="btn-explore" data-bs-toggle="modal" data-bs-target="#majorModal<?php echo $major['id']; ?>">
        Explore Major
    </button>
</div>

                </div>
                <div class="major-content">
                    <h3 class="major-title"><?php echo $major['title']; ?></h3>
                    <div class="major-meta">
                        <?php if (!empty($major['institutions'])): ?>
                        <div class="major-meta-item">
                            <i class="fas fa-university"></i>
                            <span><?php echo mb_substr($major['institutions'], 0, 30) . (strlen($major['institutions']) > 30 ? '...' : ''); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="major-meta-item">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Avg. Salary: <?php echo $salary_range; ?></span>
                        </div>
                    </div>
                    <div class="major-description">
                        <?php echo mb_substr(strip_tags($major['description']), 0, 100) . '...'; ?>
                    </div>
                    <div class="major-skills">
                        <?php 
                        // Generate sample skills if none provided
                        $skills = [];
                        if (!empty($major['skills_gained'])) {
                            $skills_text = strip_tags($major['skills_gained']);
                            $skills = explode(',', $skills_text);
                            $skills = array_slice($skills, 0, 3);
                        } else {
                            // Sample skills based on major title
                            $sample_skills = [
                                'Critical Thinking', 'Research', 'Analysis', 'Communication', 
                                'Problem Solving', 'Teamwork', 'Leadership', 'Technical Skills',
                                'Data Analysis', 'Project Management', 'Design', 'Programming'
                            ];
                            shuffle($sample_skills);
                            $skills = array_slice($sample_skills, 0, 3);
                        }
                        
                        foreach ($skills as $skill):
                        ?>
                        <span class="skill-tag"><?php echo trim($skill); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
<!-- Modal for major details -->
<div class="modal fade" id="majorModal<?php echo $major['id']; ?>" tabindex="-1" aria-labelledby="majorModalLabel<?php echo $major['id']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="majorModalLabel<?php echo $major['id']; ?>"><?php echo $major['title']; ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <?php if (!empty($major['image_path'])): ?>
                            <img src="<?php echo $major['image_path']; ?>" class="img-fluid rounded" alt="<?php echo $major['title']; ?>">
                        <?php else: ?>
                            <div class="major-placeholder-modal">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mt-3">
                            <h6 class="fw-bold">Key Information</h6>
                            <ul class="list-unstyled">
                                <?php if (!empty($major['institutions'])): ?>
                                <li class="mb-2">
                                    <i class="fas fa-university text-danger me-2"></i>
                                    <strong>Institutions:</strong> <?php echo $major['institutions']; ?>
                                </li>
                                <?php endif; ?>
                                <li class="mb-2">
                                    <i class="fas fa-money-bill-wave text-danger me-2"></i>
                                    <strong>Avg. Salary:</strong> <?php echo $salary_range; ?>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-clock text-danger me-2"></i>
                                    <strong>Duration:</strong> <?php echo !empty($major['duration']) ? $major['duration'] : '4 years'; ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h5 class="border-bottom pb-2 mb-3">About this Major</h5>
                        <div class="major-full-description mb-4">
                            <?php echo !empty($major['description']) ? $major['description'] : 'No detailed description available.'; ?>
                        </div>
                        
                        <h5 class="border-bottom pb-2 mb-3">Skills Gained</h5>
                        <div class="major-skills-container mb-4">
                            <?php 
                            if (!empty($major['skills_gained'])) {
                                $skills_text = strip_tags($major['skills_gained']);
                                $skills = explode(',', $skills_text);
                                foreach ($skills as $skill): 
                            ?>
                                <span class="badge bg-light text-danger border border-danger me-2 mb-2 p-2"><?php echo trim($skill); ?></span>
                            <?php 
                                endforeach;
                            } else {
                                echo '<p class="text-muted">No specific skills listed.</p>';
                            }
                            ?>
                        </div>
                        
                        <h5 class="border-bottom pb-2 mb-3">Career Opportunities</h5>
                        <div class="major-careers mb-4">
                            <?php echo !empty($major['career_opportunities']) ? $major['career_opportunities'] : 'No specific career opportunities listed.'; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" class="btn btn-danger">Apply for This Major</a>
            </div>
        </div>
    </div>
</div>
<?php 
                    endforeach;
                else:
                    // Display sample data if no majors found
                    $sample_majors = [
                        [
                            'title' => 'Computer Science',
                            'description' => 'Learn programming, algorithms, and software development to build the digital future.',
                            'institutions' => 'MIT, Stanford, UC Berkeley',
                            'salary' => '$75,000 - $120,000',
                            'skills' => ['Programming', 'Algorithms', 'Problem Solving'],
                            'image' => 'assets/images/major-cs.jpg'
                        ],
                        [
                            'title' => 'Business Administration',
                            'description' => 'Develop management, marketing, and entrepreneurial skills for the business world.',
                            'institutions' => 'Harvard, Wharton, London Business School',
                            'salary' => '$65,000 - $110,000',
                            'skills' => ['Leadership', 'Marketing', 'Finance'],
                            'image' => 'assets/images/major-business.jpg'
                        ],
                        [
                            'title' => 'Engineering',
                            'description' => 'Design and build solutions to complex technical problems across various industries.',
                            'institutions' => 'Caltech, Georgia Tech, Purdue',
                            'salary' => '$70,000 - $115,000',
                            'skills' => ['Design', 'Analysis', 'Technical Skills'],
                            'image' => 'assets/images/major-engineering.jpg'
                        ],
                        [
                            'title' => 'Healthcare Sciences',
                            'description' => 'Prepare for careers in medicine, nursing, and other healthcare professions.',
                            'institutions' => 'Johns Hopkins, Mayo Clinic College',
                            'salary' => '$60,000 - $100,000',
                            'skills' => ['Patient Care', 'Medical Knowledge', 'Communication'],
                            'image' => 'assets/images/major-healthcare.jpg'
                        ]
                    ];
                    
                    foreach ($sample_majors as $index => $major):
            ?>
            <div class="major-card-new">
                <div class="major-image-container">
                    <div class="major-placeholder">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    
<div class="major-overlay">
    <button type="button" class="btn-explore" data-bs-toggle="modal" data-bs-target="#majorModalSample<?php echo $index; ?>">
        Explore Major
    </button>
</div>

                </div>
                <div class="major-content">
                    <h3 class="major-title"><?php echo $major['title']; ?></h3>
                    <div class="major-meta">
                        <div class="major-meta-item">
                            <i class="fas fa-university"></i>
                            <span><?php echo $major['institutions']; ?></span>
                        </div>
                        <div class="major-meta-item">
                            <i class="fas fa-money-bill-wave"></i>
                            <span><?php echo $major['salary']; ?></span>
                        </div>
                    </div>
                    <div class="major-description">
                        <?php echo $major['description']; ?>
                    </div>
                    <div class="major-skills">
                        <?php foreach ($major['skills'] as $skill): ?>
                        <span class="skill-tag"><?php echo $skill; ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
<!-- Modal for sample major details -->
<div class="modal fade" id="majorModalSample<?php echo $index; ?>" tabindex="-1" aria-labelledby="majorModalSampleLabel<?php echo $index; ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="majorModalSampleLabel<?php echo $index; ?>"><?php echo $major['title']; ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="major-placeholder-modal">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        
                        <div class="mt-3">
                            <h6 class="fw-bold">Key Information</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-university text-danger me-2"></i>
                                    <strong>Institutions:</strong> <?php echo $major['institutions']; ?>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-money-bill-wave text-danger me-2"></i>
                                    <strong>Avg. Salary:</strong> <?php echo $major['salary']; ?>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-clock text-danger me-2"></i>
                                    <strong>Duration:</strong> 4 years
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h5 class="border-bottom pb-2 mb-3">About this Major</h5>
                        <div class="major-full-description mb-4">
                            <p><?php echo $major['description']; ?></p>
                            <p>This program provides students with a comprehensive education in <?php echo strtolower($major['title']); ?>, preparing them for various career paths in the field.</p>
                        </div>
                        
                        <h5 class="border-bottom pb-2 mb-3">Skills Gained</h5>
                        <div class="major-skills-container mb-4">
                            <?php foreach ($major['skills'] as $skill): ?>
                                <span class="badge bg-light text-danger border border-danger me-2 mb-2 p-2"><?php echo $skill; ?></span>
                            <?php endforeach; ?>
                        </div>
                        
                        <h5 class="border-bottom pb-2 mb-3">Career Opportunities</h5>
                        <div class="major-careers mb-4">
                            <p>Graduates of this program can pursue careers in various sectors including:</p>
                            <ul>
                                <?php 
                                $careers = [
                                    'Computer Science' => ['Software Developer', 'Data Scientist', 'Systems Analyst', 'IT Consultant'],
                                    'Business Administration' => ['Business Analyst', 'Marketing Manager', 'Project Manager', 'Entrepreneur'],
                                    'Engineering' => ['Design Engineer', 'Project Engineer', 'Systems Engineer', 'Research & Development'],
                                    'Healthcare Sciences' => ['Clinical Specialist', 'Healthcare Administrator', 'Research Scientist', 'Medical Consultant']
                                ];
                                
                                $title = $major['title'];
                                $career_list = isset($careers[$title]) ? $careers[$title] : ['Industry Specialist', 'Consultant', 'Researcher', 'Manager'];
                                
                                foreach ($career_list as $career):
                                ?>
                                <li><?php echo $career; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" class="btn btn-danger">Apply for This Major</a>
            </div>
        </div>
    </div>
</div>
<?php 
                    endforeach;
                endif;
            } catch(PDOException $e) {
                echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
            }
            ?>
        </div>
        
        <div class="text-center mt-5">
            <a href="#" class="btn-view-all">
                <span>View All Majors</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Popular Jobs Section - New Modern Design -->
<div class="popular-jobs-section-new py-5 mb-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <div class="section-icon-container">
                <div class="section-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
            </div>
            <h2 class="section-title">Popular <span class="text-danger">Career</span></h2>
            <p class="section-subtitle">Discover in-demand career opportunities for our graduates</p>
            <div class="section-divider"></div>
        </div>
        
        <div class="jobs-container">
            <?php
            // Fetch popular jobs from database
            try {
                $stmt = $pdo->query("SELECT * FROM popular_jobs WHERE is_active = 1 ORDER BY display_order ASC LIMIT 4");
                $popular_jobs = $stmt->fetchAll();
                
                if (count($popular_jobs) > 0):
                    foreach ($popular_jobs as $index => $job):
                        // Set default badge
                        $badges = ['Hot', 'New', 'Trending', ''];
                        $badge = $badges[array_rand($badges)];
                        
                        // Alternate layout for even/odd items
                        $isEven = $index % 2 == 0;
            ?>
            <div class="job-item <?php echo $isEven ? 'job-item-even' : 'job-item-odd'; ?>">
                <div class="job-image-wrapper">
                    <?php if (!empty($job['image_path'])): ?>
                        <img src="<?php echo $job['image_path']; ?>" alt="<?php echo $job['title']; ?>" class="job-image">
                    <?php else: ?>
                        <div class="job-placeholder">
                            <i class="fas fa-briefcase"></i>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($badge)): ?>
                        <div class="job-badge"><?php echo $badge; ?></div>
                    <?php endif; ?>
                </div>
                <div class="job-content">
                    <h3 class="job-title"><?php echo $job['title']; ?></h3>
                    <div class="job-company">
                        <i class="fas fa-building"></i>
                        <span><?php echo !empty($job['company']) ? $job['company'] : 'Various Companies'; ?></span>
                    </div>
                    <div class="job-details-row">
                        <?php if (!empty($job['salary_range'])): ?>
                        <div class="job-detail">
                            <i class="fas fa-money-bill-wave"></i>
                            <span><?php echo $job['salary_range']; ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="job-detail">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo !empty($job['location']) ? $job['location'] : 'Remote / Phnom Penh'; ?></span>
                        </div>
                        <div class="job-detail">
                            <i class="fas fa-users"></i>
                            <span><?php echo rand(20, 150); ?>+ openings</span>
                        </div>
                    </div>
                    <div class="job-description">
                        <?php echo mb_substr(strip_tags($job['description']), 0, 150) . '...'; ?>
                    </div>
                    <div class="job-actions">
                        <button type="button" class="btn-job-details" data-bs-toggle="modal" data-bs-target="#jobModal<?php echo $job['id']; ?>">View Details</button>
                    </div>
                </div>
            </div>
            
            <!-- Modal for job details -->
            <div class="modal fade" id="jobModal<?php echo $job['id']; ?>" tabindex="-1" aria-labelledby="jobModalLabel<?php echo $job['id']; ?>" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="jobModalLabel<?php echo $job['id']; ?>"><?php echo $job['title']; ?></h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <?php if (!empty($job['image_path'])): ?>
                                        <img src="<?php echo $job['image_path']; ?>" class="img-fluid rounded" alt="<?php echo $job['title']; ?>">
                                    <?php else: ?>
                                        <div class="job-placeholder-modal">
                                            <i class="fas fa-briefcase"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="mt-3">
                                        <h6 class="fw-bold">Job Details</h6>
                                        <ul class="list-unstyled">
                                            <li class="mb-2">
                                                <i class="fas fa-building text-danger me-2"></i>
                                                <strong>Company:</strong> <?php echo !empty($job['company']) ? $job['company'] : 'Various Companies'; ?>
                                            </li>
                                            <?php if (!empty($job['salary_range'])): ?>
                                            <li class="mb-2">
                                                <i class="fas fa-money-bill-wave text-danger me-2"></i>
                                                <strong>Salary:</strong> <?php echo $job['salary_range']; ?>
                                            </li>
                                            <?php endif; ?>
                                            <li class="mb-2">
                                                <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                                <strong>Location:</strong> <?php echo !empty($job['location']) ? $job['location'] : 'Remote / Phnom Penh'; ?>
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-clock text-danger me-2"></i>
                                                <strong>Job Type:</strong> <?php echo !empty($job['job_type']) ? $job['job_type'] : 'Full-time'; ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h5 class="border-bottom pb-2 mb-3">Job Description</h5>
                                    <div class="job-full-description mb-4">
                                        <?php echo !empty($job['description']) ? $job['description'] : 'No detailed description available.'; ?>
                                    </div>
                                    
                                    <h5 class="border-bottom pb-2 mb-3">Requirements</h5>
                                    <div class="job-requirements mb-4">
                                        <?php if (!empty($job['requirements'])): ?>
                                            <?php echo $job['requirements']; ?>
                                        <?php else: ?>
                                            <ul>
                                                <li>Bachelor's degree in a relevant field</li>
                                                <li>2+ years of experience in a similar role</li>
                                                <li>Strong communication and teamwork skills</li>
                                                <li>Ability to work independently and meet deadlines</li>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <h5 class="border-bottom pb-2 mb-3">Benefits</h5>
                                    <div class="job-benefits">
                                        <?php if (!empty($job['benefits'])): ?>
                                            <?php echo $job['benefits']; ?>
                                        <?php else: ?>
                                            <ul>
                                                <li>Competitive salary package</li>
                                                <li>Health insurance</li>
                                                <li>Professional development opportunities</li>
                                                <li>Flexible working arrangements</li>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal for job application -->
            <div class="modal fade" id="applyJobModal<?php echo $job['id']; ?>" tabindex="-1" aria-labelledby="applyJobModalLabel<?php echo $job['id']; ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="applyJobModalLabel<?php echo $job['id']; ?>">Apply for <?php echo $job['title']; ?></h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="jobApplicationForm<?php echo $job['id']; ?>" class="job-application-form">
                                <div class="mb-3">
                                    <label for="fullName<?php echo $job['id']; ?>" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" id="fullName<?php echo $job['id']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email<?php echo $job['id']; ?>" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="email<?php echo $job['id']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone<?php echo $job['id']; ?>" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control" id="phone<?php echo $job['id']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="resume<?php echo $job['id']; ?>" class="form-label">Upload Resume (PDF) *</label>
                                    <input type="file" class="form-control" id="resume<?php echo $job['id']; ?>" accept=".pdf" required>
                                </div>
                                <div class="mb-3">
                                    <label for="coverLetter<?php echo $job['id']; ?>" class="form-label">Cover Letter</label>
                                    <textarea class="form-control" id="coverLetter<?php echo $job['id']; ?>" rows="4"></textarea>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="agreeTerms<?php echo $job['id']; ?>" required>
                                    <label class="form-check-label" for="agreeTerms<?php echo $job['id']; ?>">
                                        I agree to the terms and conditions
                                    </label>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" form="jobApplicationForm<?php echo $job['id']; ?>" class="btn btn-danger">Submit Application</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                    endforeach;
                else:
                    // Display sample data if no jobs found
                    $sample_jobs = [
                        [
                            'id' => 'sample1',
                            'title' => 'Software Developer',
                            'company' => 'Various Tech Companies',
                            'salary' => '$75,000 - $120,000',
                            'location' => 'Remote / Phnom Penh',
                            'icon' => 'fa-laptop-code',
                            'openings' => '120+',
                            'badge' => 'Hot',
                            'description' => 'Design, develop and maintain software applications using various programming languages and frameworks. Collaborate with cross-functional teams to deliver high-quality software solutions.',
                            'requirements' => '<ul>
                                <li>Bachelor\'s degree in Computer Science or related field</li>
                                <li>2+ years of experience in software development</li>
                                <li>Proficiency in at least one programming language (e.g., Java, Python, JavaScript)</li>
                                <li>Experience with web development frameworks</li>
                                <li>Strong problem-solving skills and attention to detail</li>
                            </ul>',
                            'benefits' => '<ul>
                                <li>Competitive salary and performance bonuses</li>
                                <li>Health, dental, and vision insurance</li>
                                <li>Flexible working hours and remote work options</li>
                                <li>Professional development opportunities</li>
                                <li>Modern office with recreational facilities</li>
                            </ul>'
                        ],
                        [
                            'id' => 'sample2',
                            'title' => 'Digital Marketing Specialist',
                            'company' => 'Marketing Agencies',
                            'salary' => '$45,000 - $80,000',
                            'location' => 'Phnom Penh',
                            'icon' => 'fa-bullhorn',
                            'openings' => '85+',
                            'badge' => 'New',
                            'description' => 'Create and implement digital marketing strategies across various platforms. Analyze campaign performance and optimize for better results.',
                            'requirements' => '<ul>
                                <li>Bachelor\'s degree in Marketing, Communications, or related field</li>
                                <li>1-3 years of experience in digital marketing</li>
                                <li>Experience with SEO, SEM, and social media marketing</li>
                                <li>Proficiency with marketing analytics tools</li>
                                <li>Strong creative and analytical skills</li>
                            </ul>',
                            'benefits' => '<ul>
                                <li>Competitive salary package</li>
                                <li>Health insurance and wellness programs</li>
                                <li>Professional development budget</li>
                                <li>Collaborative work environment</li>
                                <li>Opportunities for career advancement</li>
                            </ul>'
                        ],
                        [
                            'id' => 'sample3',
                            'title' => 'Financial Analyst',
                            'company' => 'Banking & Finance Sector',
                            'salary' => '$60,000 - $95,000',
                            'location' => 'Phnom Penh / Siem Reap',
                            'icon' => 'fa-chart-pie',
                            'openings' => '65+',
                            'badge' => '',
                            'description' => 'Analyze financial data, prepare reports, and provide recommendations to improve financial performance. Support budgeting and forecasting activities.',
                            'requirements' => '<ul>
                                <li>Bachelor\'s degree in Finance, Accounting, or related field</li>
                                <li>2+ years of experience in financial analysis</li>
                                <li>Strong analytical and quantitative skills</li>
                                <li>Proficiency in financial modeling and data analysis</li>
                                <li>Knowledge of financial regulations and reporting standards</li>
                            </ul>',
                            'benefits' => '<ul>
                                <li>Competitive salary and performance bonuses</li>
                                <li>Comprehensive benefits package</li>
                                <li>Professional certification support</li>
                                <li>Career development opportunities</li>
                                <li>Work-life balance initiatives</li>
                            </ul>'
                        ],
                        [
                            'id' => 'sample4',
                            'title' => 'Healthcare Administrator',
                            'company' => 'Hospitals & Clinics',
                            'salary' => '$55,000 - $90,000',
                            'location' => 'Nationwide',
                            'icon' => 'fa-hospital',
                            'openings' => '50+',
                            'badge' => 'Trending',
                            'description' => 'Oversee daily operations of healthcare facilities. Manage staff, budgets, and ensure compliance with healthcare regulations and policies.',
                            'requirements' => '<ul>
                                <li>Bachelor\'s degree in Healthcare Administration or related field</li>
                                <li>3+ years of experience in healthcare management</li>
                                <li>Knowledge of healthcare regulations and compliance</li>
                                <li>Strong leadership and organizational skills</li>
                                <li>Experience with healthcare information systems</li>
                            </ul>',
                            'benefits' => '<ul>
                                <li>Competitive salary package</li>
                                <li>Comprehensive health benefits</li>
                                <li>Retirement savings plan</li>
                                <li>Continuing education opportunities</li>
                                <li>Professional development support</li>
                            </ul>'
                        ]
                    ];
                    
                    foreach ($sample_jobs as $index => $job):
                        $isEven = $index % 2 == 0;
            ?>
            <div class="job-item <?php echo $isEven ? 'job-item-even' : 'job-item-odd'; ?>">
                <div class="job-image-wrapper">
                    <div class="job-placeholder">
                        <i class="fas <?php echo $job['icon']; ?>"></i>
                    </div>
                    <?php if (!empty($job['badge'])): ?>
                        <div class="job-badge"><?php echo $job['badge']; ?></div>
                    <?php endif; ?>
                </div>
                <div class="job-content">
                    <h3 class="job-title"><?php echo $job['title']; ?></h3>
                    <div class="job-company">
                        <i class="fas fa-building"></i>
                        <span><?php echo $job['company']; ?></span>
                    </div>
                    <div class="job-details-row">
                        <div class="job-detail">
                            <i class="fas fa-money-bill-wave"></i>
                            <span><?php echo $job['salary']; ?></span>
                        </div>
                        <div class="job-detail">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo $job['location']; ?></span>
                        </div>
                        <div class="job-detail">
                            <i class="fas fa-users"></i>
                            <span><?php echo $job['openings']; ?> openings</span>
                        </div>
                    </div>
                    <div class="job-description">
                        <?php echo $job['description']; ?>
                    </div>
                    <div class="job-actions">
                        <button type="button" class="btn-job-details" data-bs-toggle="modal" data-bs-target="#jobModal<?php echo $job['id']; ?>">View Details</button>
                    </div>
                </div>
            </div>
            
            <!-- Modal for sample job details -->
            <div class="modal fade" id="jobModal<?php echo $job['id']; ?>" tabindex="-1" aria-labelledby="jobModalLabel<?php echo $job['id']; ?>" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="jobModalLabel<?php echo $job['id']; ?>"><?php echo $job['title']; ?></h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <div class="job-placeholder-modal">
                                        <i class="fas <?php echo $job['icon']; ?>"></i>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <h6 class="fw-bold">Job Details</h6>
                                        <ul class="list-unstyled">
                                            <li class="mb-2">
                                                <i class="fas fa-building text-danger me-2"></i>
                                                <strong>Company:</strong> <?php echo $job['company']; ?>
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-money-bill-wave text-danger me-2"></i>
                                                <strong>Salary:</strong> <?php echo $job['salary']; ?>
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                                <strong>Location:</strong> <?php echo $job['location']; ?>
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-clock text-danger me-2"></i>
                                                <strong>Job Type:</strong> Full-time
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h5 class="border-bottom pb-2 mb-3">Job Description</h5>
                                    <div class="job-full-description mb-4">
                                        <p><?php echo $job['description']; ?></p>
                                        <p>This is an excellent opportunity for professionals looking to advance their career in <?php echo strtolower($job['title']); ?>. The role offers a dynamic work environment with opportunities for growth and development.</p>
                                    </div>
                                    
                                    <h5 class="border-bottom pb-2 mb-3">Requirements</h5>
                                    <div class="job-requirements mb-4">
                                        <?php echo $job['requirements']; ?>
                                    </div>
                                    
                                    <h5 class="border-bottom pb-2 mb-3">Benefits</h5>
                                    <div class="job-benefits">
                                        <?php echo $job['benefits']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                    endforeach;
                endif;
            } catch(PDOException $e) {
                echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
            }
            ?>
        </div>
        
        <div class="text-center mt-5">
            <a href="#" class="btn-view-all">
                <span>View All Career Opportunities</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row text-center mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-danger">Latest News</h2>
            <p class="text-muted">Stay updated with the latest news and events from MACA</p>
        </div>
    </div>
    
    <div class="row">
        <?php
        $news_items = getNews($pdo, 3);
        if (count($news_items) > 0):
            foreach ($news_items as $news):
        ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 hover-shadow">
                <?php if ($news['image_path']): ?>
                <img src="<?php echo $news['image_path']; ?>" class="card-img-top" alt="<?php echo $news['title']; ?>" style="height: 200px; object-fit: cover;">
                <?php endif; ?>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-danger">News</span>
                        <small class="text-muted"><?php echo formatDate($news['created_at']); ?></small>
                    </div>
                    <h5 class="card-title"><?php echo $news['title']; ?></h5>
                    <p class="card-text"><?php echo $news['summary']; ?></p>
                </div>
                <div class="card-footer bg-white border-0">
                    <a href="index.php?page=news-detail&id=<?php echo $news['id']; ?>" class="btn btn-sm btn-outline-danger">Read More</a>
                </div>
            </div>
        </div>
        <?php
            endforeach;
        else:
        ?>
        <div class="col-12">
            <div class="alert alert-info">No news available at this time.</div>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="text-center mt-3">
        <a href="index.php?page=news" class="btn btn-outline-danger">View All News</a>
    </div>
</div>

<div class="bg-danger text-white py-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <h2 class="fw-bold">Ready to Start Your Educational Journey?</h2>
                <p class="lead">Contact us today to learn more about our programs and how we can help you achieve your academic and career goals.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="index.php?page=contact" class="btn btn-light text-danger">Contact Us</a>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Hero Slideshow Styles */
.hero-slideshow-modern {
    position: relative;
    margin-bottom: 3rem;
    overflow: hidden;
    background-color: #000;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
    border-radius: 12px;
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
}

.slideshow-container-modern {
    position: relative;
    width: 100%;
    height: 500px;
    overflow: hidden;
}

.slide-modern {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 1s ease;
    z-index: 1;
}

.slide-modern.active {
    opacity: 1;
    z-index: 2;
}

.slide-image-container {
    position: absolute;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.slide-image-modern {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 8s ease;
}

.slide-modern.active .slide-image-modern {
    transform: scale(1.1);
}

.slide-content {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    padding: 40px;
    background: linear-gradient(to top, rgba(0,0,0,0.7), rgba(0,0,0,0));
    color: white;
    z-index: 3;
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.8s ease, transform 0.8s ease;
    transition-delay: 0.3s;
}

.slide-modern.active .slide-content {
    opacity: 1;
    transform: translateY(0);
}

.slide-text {
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 20px;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    max-width: 80%;
}

.slide-button {
    display: inline-block;
    padding: 12px 30px;
    background-color: #dc3545;
    color: white;
    text-decoration: none;
    border-radius: 30px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);
}

.slide-button:hover {
    background-color: #c82333;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(220, 53, 69, 0.4);
}

.slide-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 50px;
    height: 50px;
    background-color: rgba(255, 255, 255, 0.2);
    border: none;
    border-radius: 50%;
    color: white;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: all 0.3s ease;
    backdrop-filter: blur(5px);
}

.slide-arrow:hover {
    background-color: rgba(255, 255, 255, 0.3);
    transform: translateY(-50%) scale(1.1);
}

.prev-arrow {
    left: 20px;
}

.next-arrow {
    right: 20px;
}

.slideshow-dots-modern {
    position: absolute;
    bottom: 15px;
    right: 20px;
    display: flex;
    gap: 10px;
    z-index: 10;
}

.dot-modern {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.4);
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.dot-modern.active {
    background-color: #dc3545;
    transform: scale(1.2);
}

.dot-modern:hover {
    background-color: rgba(255, 255, 255, 0.8);
}

.slide-progress-container {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background-color: rgba(255, 255, 255, 0.2);
    z-index: 10;
}

.slide-progress-bar {
    height: 100%;
    width: 0;
    background-color: #dc3545;
    transition: width 0.1s linear;
}

@media (max-width: 991px) {
    .slideshow-container-modern {
        height: 400px;
    }
    
    .slide-text {
        font-size: 1.8rem;
        max-width: 100%;
    }
}

@media (max-width: 768px) {
    .slideshow-container-modern {
        height: 350px;
    }
    
    .slide-content {
        padding: 30px;
    }
    
    .slide-text {
        font-size: 1.5rem;
    }
    
    .slide-button {
        padding: 10px 25px;
    }
    
    .slide-arrow {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
}

@media (max-width: 576px) {
    .slideshow-container-modern {
        height: 300px;
    }
    
    .slide-content {
        padding: 20px;
    }
    
    .slide-text {
        font-size: 1.2rem;
        margin-bottom: 15px;
    }
    
    .slide-button {
        padding: 8px 20px;
        font-size: 14px;
    }
    
    .slide-arrow {
        width: 35px;
        height: 35px;
        font-size: 14px;
    }
    
    .prev-arrow {
        left: 10px;
    }
    
    .next-arrow {
        right: 10px;
    }
}
/* Hero Slideshow Styles */
.hero-slideshow {
    position: relative;
    margin-bottom: 3rem;
    overflow: hidden;
    background-color: #f8f9fa; /* Light background to prevent blank appearance */
    max-width: 1000px; /* Reduced width */
    margin-left: auto;
    margin-right: auto;
}

.slideshow-container {
    width: 100%;
    position: relative;
    max-height: 400px; /* Reduced height */
}

.slide {
    position: absolute;
    width: 100%;
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
}

/* First slide visible by default */
.slide:first-child {
    position: relative;
    opacity: 1;
}

.slide-image {
    width: 100%;
    height: auto;
    max-height: 400px; /* Ensure image doesn't exceed container height */
    display: block;
    object-fit: cover;
}

.slide.active {
    opacity: 1;
    z-index: 2;
}

.slide.active .slide-image {
    animation: slideLeft 1s ease-out;
}

@keyframes slideLeft {
    0% {
        transform: translateX(100%);
    }
    100% {
        transform: translateX(0);
    }
}

/* Navigation Arrows */
.prev, .next {
    cursor: pointer;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: auto;
    padding: 16px;
    color: white;
    font-weight: bold;
    font-size: 18px;
    transition: 0.6s ease;
    border-radius: 0 3px 3px 0;
    user-select: none;
    background-color: rgba(0, 0, 0, 0.3);
    z-index: 10;
}

.next {
    right: 0;
    border-radius: 3px 0 0 3px;
}

.prev {
    left: 0;
    border-radius: 0 3px 3px 0;
}

.prev:hover, .next:hover {
    background-color: rgba(0, 0, 0, 0.8);
}

.slideshow-dots {
    text-align: center;
    position: absolute;
    bottom: 20px;
    width: 100%;
    z-index: 10;
}

.dot {
    height: 12px;
    width: 12px;
    margin: 0 5px;
    background-color: rgba(255, 255, 255, 0.5);
    border-radius: 50%;
    display: inline-block;
    transition: background-color 0.6s ease;
    cursor: pointer;
}

.dot.active, .dot:hover {
    background-color: white;
}

/* New Popular Jobs Section Styles */
.popular-jobs-section-new {
    background-color: #f8f9fa;
    padding: 80px 0;
    position: relative;
}

.section-header {
    position: relative;
    padding-bottom: 30px;
}

.section-icon-container {
    margin-bottom: 20px;
}

.section-icon {
    width: 70px;
    height: 70px;
    background-color: #dc3545;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    color: white;
    font-size: 28px;
    box-shadow: 0 10px 20px rgba(220, 53, 69, 0.3);
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 10px;
    position: relative;
    display: inline-block;
}

.section-subtitle {
    color: #6c757d;
    font-size: 1.1rem;
    max-width: 600px;
    margin: 0 auto;
}

.section-divider {
    width: 80px;
    height: 4px;
    background-color: #dc3545;
    margin: 20px auto 0;
    border-radius: 2px;
}

.jobs-container {
    max-width: 1000px;
    margin: 0 auto;
}

.job-item {
    display: flex;
    background-color: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    margin-bottom: 30px;
    transition: all 0.3s ease;
    position: relative;
}

.job-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
}

.job-item-even {
    flex-direction: row;
}

.job-item-odd {
    flex-direction: row-reverse;
}

.job-image-wrapper {
    width: 300px;
    position: relative;
    overflow: hidden;
}

.job-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.job-item:hover .job-image {
    transform: scale(1.1);
}

.job-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #dc3545;
    color: white;
    font-size: 4rem;
}

.job-placeholder-modal {
    height: 200px;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #dc3545;
    color: white;
    font-size: 3rem;
    border-radius: 8px;
}

.job-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    background-color: #dc3545;
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.8rem;
    box-shadow: 0 5px 10px rgba(0,0,0,0.1);
}

.job-item-odd .job-badge {
    right: auto;
    left: 20px;
}

.job-content {
    flex: 1;
    padding: 30px;
    position: relative;
}

.job-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 10px;
    color: #333;
}

.job-company {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    color: #6c757d;
}

.job-company i {
    margin-right: 8px;
    color: #dc3545;
}

.job-details-row {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 15px;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
    padding: 15px 0;
}

.job-detail {
    display: flex;
    align-items: center;
    margin-right: 20px;
    margin-bottom: 5px;
}

.job-detail i {
    margin-right: 8px;
    color: #dc3545;
}

.job-description {
    color: #6c757d;
    margin-bottom: 20px;
    line-height: 1.6;
}

.job-actions {
    display: flex;
    gap: 15px;
}

.btn-job-details, .btn-job-apply {
    padding: 10px 20px;
    border-radius: 30px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    cursor: pointer;
}

.btn-job-details {
    background-color: transparent;
    color: #dc3545;
    border: 2px solid #dc3545;
}

.btn-job-details:hover {
    background-color: rgba(220, 53, 69, 0.1);
}

.btn-job-apply {
    background-color: #dc3545;
    color: white;
    border: 2px solid #dc3545;
}

.btn-job-apply:hover {
    background-color: #c82333;
    border-color: #c82333;
    transform: translateY(-2px);
}

.btn-view-all {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 30px;
    background-color: #dc3545;
    color: white;
    border-radius: 30px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
}

.btn-view-all:hover {
    background-color: #c82333;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(220, 53, 69, 0.4);
}

.btn-view-all i {
    transition: transform 0.3s ease;
}

.btn-view-all:hover i {
    transform: translateX(5px);
}

/* Popular Majors Section - New Design */
.popular-majors-section {
    background-color: #f8f9fa;
    padding: 80px 0;
    position: relative;
}

.majors-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
}

.major-card-new {
    background-color: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.major-card-new:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
}

.major-image-container {
    height: 200px;
    position: relative;
    overflow: hidden;
}

.major-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.major-card-new:hover .major-image {
    transform: scale(1.1);
}

.major-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #dc3545, #fd7e14);
    color: white;
    font-size: 4rem;
}

.major-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(220, 53, 69, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.major-card-new:hover .major-overlay {
    opacity: 1;
}

.btn-explore {
    padding: 10px 20px;
    background-color: white;
    color: #dc3545;
    border-radius: 30px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    transform: translateY(20px);
    opacity: 0;
    border: none;
    cursor: pointer;
}

.major-card-new:hover .btn-explore {
    transform: translateY(0);
    opacity: 1;
    transition: all 0.3s ease 0.1s;
}

.btn-explore:hover {
    background-color: #f8f9fa;
    transform: scale(1.05);
}

.major-content {
    padding: 25px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.major-title {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 15px;
    color: #333;
    position: relative;
    padding-bottom: 10px;
}

.major-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 40px;
    height: 3px;
    background-color: #dc3545;
}

.major-meta {
    margin-bottom: 15px;
}

.major-meta-item {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
    color: #6c757d;
}

.major-meta-item i {
    margin-right: 8px;
    color: #dc3545;
    width: 16px;
}

.major-description {
    color: #6c757d;
    margin-bottom: 15px;
    line-height: 1.6;
    flex: 1;
}

.major-skills {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: auto;
}

.skill-tag {
    padding: 5px 12px;
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

/* Career Paths Section - New Design */
.career-paths-section {
    background-color: #fff;
    padding: 80px 0;
    position: relative;
}

.career-paths-container-new {
    max-width: 1000px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 40px;
}

.career-path-card-new {
    background-color: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.career-path-card-new:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
}

.career-path-header {
    display: flex;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #f0f0f0;
    background-color: #f8f9fa;
}

.career-path-image {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    overflow: hidden;
    margin-right: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    flex-shrink: 0;
}

.career-path-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.career-path-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #dc3545, #fd7e14);
    color: white;
    font-size: 2.5rem;
}

.career-path-placeholder-modal {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #dc3545, #fd7e14);
    color: white;
    font-size: 3rem;
    border-radius: 8px;
}

.career-path-title-container {
    flex: 1;
}

.career-path-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 10px;
    color: #333;
}

.career-path-stats {
    display: flex;
    gap: 20px;
}

.career-stat {
    display: flex;
    align-items: center;
    color: #6c757d;
}

.career-stat i {
    margin-right: 8px;
    color: #dc3545;
}

.career-path-content {
    padding: 30px;
}

.career-path-description {
    color: #6c757d;
    margin-bottom: 30px;
    line-height: 1.6;
}

.career-progression-new {
    margin-bottom: 30px;
    position: relative;
}

.progression-step-new {
    display: flex;
    margin-bottom: 20px;
    position: relative;
}

.step-indicator {
    position: relative;
    margin-right: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #f8f9fa;
    border: 2px solid #dc3545;
    color: #dc3545;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-bottom: 10px;
    transition: all 0.3s ease;
    z-index: 2;
}

.progression-step-new.active .step-number {
    background-color: #dc3545;
    color: white;
}

.step-line {
    position: absolute;
    top: 40px;
    left: 50%;
    transform: translateX(-50%);
    width: 2px;
    height: calc(100% - 30px);
    background-color: #dc3545;
    z-index: 1;
}

.step-line.last {
    display: none;
}

.step-details {
    flex: 1;
    background-color: #f8f9fa;
    border-radius: 10px;
    padding: 15px;
    transition: all 0.3s ease;
}

.progression-step-new.active .step-details {
    background-color: rgba(220, 53, 69, 0.05);
    border-left: 3px solid #dc3545;
}

.step-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 10px;
    color: #333;
}

.step-info {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
}

.step-detail {
    display: flex;
    align-items: center;
    color: #6c757d;
    font-size: 0.9rem;
}

.step-detail i {
    margin-right: 5px;
    color: #dc3545;
}

.career-path-actions {
    display: flex;
    gap: 15px;
}

.btn-career-details, .btn-career-compare {
    padding: 10px 20px;
    border-radius: 30px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    cursor: pointer;
}

.btn-career-details {
    background-color: #dc3545;
    color: white;
    border: 2px solid #dc3545;
}

.btn-career-details:hover {
    background-color: #c82333;
    border-color: #c82333;
    transform: translateY(-2px);
}

.btn-career-compare {
    background-color: transparent;
    color: #dc3545;
    border: 2px solid #dc3545;
}

.btn-career-compare:hover {
    background-color: rgba(220, 53, 69, 0.1);
}

/* Career Path Timeline */
.career-progression-timeline {
    position: relative;
    max-width: 900px;
    margin: 0 auto;
}

.progression-timeline-item {
    display: flex;
    margin-bottom: 30px;
    position: relative;
}

.timeline-marker {
    position: relative;
    margin-right: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.timeline-step-number {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: #dc3545;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
    margin-bottom: 10px;
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
    z-index: 2;
}

.timeline-content {
    flex: 1;
    background-color: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.timeline-title {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 10px;
    color: #333;
}

.timeline-period {
    margin-bottom: 10px;
}

.timeline-salary {
    margin-bottom: 10px;
    font-size: 1.1rem;
}

/* Comparison Table */
.comparison-table-container {
    overflow-x: auto;
}

.comparison-table th:first-child,
.comparison-table td:first-child {
    position: sticky;
    left: 0;
    background-color: #fff;
    z-index: 1;
}

.comparison-table th {
    font-weight: 700;
    text-align: center;
}

.comparison-table td {
    vertical-align: middle;
}

@media (max-width: 991px) {
    .job-item {
        flex-direction: column !important;
    }
    
    .job-image-wrapper {
        width: 100%;
        height: 200px;
    }
    
    .job-badge {
        right: 20px !important;
        left: auto !important;
    }
    
    .career-path-header {
        flex-direction: column;
        text-align: center;
    }
    
    .career-path-image {
        margin-right: 0;
        margin-bottom: 15px;
    }
    
    .career-path-stats {
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .slideshow-dots {
        bottom: 10px;
    }
    
    .dot {
        height: 10px;
        width: 10px;
        margin: 0 4px;
    }
    
    .prev, .next {
        padding: 10px;
        font-size: 16px;
    }
    
    .majors-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .major-image-container {
        height: 180px;
    }
    
    .major-content {
        padding: 20px;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .job-details-row {
        flex-direction: column;
    }
    
    .job-detail {
        margin-bottom: 10px;
    }
    
    .step-info {
        flex-direction: column;
        gap: 5px;
    }
}

@media (max-width: 576px) {
    .majors-grid {
        grid-template-columns: 1fr;
    }
    
    .career-path-actions {
        flex-direction: column;
        gap: 10px;
    }
    
    .btn-career-details, .btn-career-compare {
        width: 100%;
    }
}

.major-placeholder-modal {
    height: 200px;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #dc3545, #fd7e14);
    color: white;
    font-size: 3rem;
    border-radius: 8px;
}

.major-full-description {
    line-height: 1.6;
    color: #333;
}
</style>

<!-- Replace the existing slideshow JavaScript with this new script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll(".slide-modern");
    const dots = document.querySelectorAll(".dot-modern");
    const prevArrow = document.querySelector(".prev-arrow");
    const nextArrow = document.querySelector(".next-arrow");
    const progressBar = document.querySelector(".slide-progress-bar");
    
    let slideIndex = 0;
    let slideInterval;
    let progressInterval;
    const slideDuration = 6000; // 6 seconds per slide
    
    // Set initial state
    showSlide(0);
    
    // Start automatic slideshow
    startSlideshow();
    
    // Function to start automatic slideshow
    function startSlideshow() {
        // Clear any existing intervals
        if (slideInterval) {
            clearInterval(slideInterval);
        }
        if (progressInterval) {
            clearInterval(progressInterval);
        }
        
        // Reset and start progress bar
        resetProgressBar();
        startProgressBar();
        
        // Set interval to change slides
        slideInterval = setInterval(function() {
            nextSlide();
        }, slideDuration);
    }
    
    // Function to reset progress bar
    function resetProgressBar() {
        if (progressBar) {
            progressBar.style.width = '0%';
        }
    }
    
    // Function to start progress bar animation
    function startProgressBar() {
        if (progressBar) {
            let width = 0;
            const increment = 100 / (slideDuration / 100); // Calculate increment for smooth animation
            
            progressInterval = setInterval(function() {
                if (width >= 100) {
                    clearInterval(progressInterval);
                } else {
                    width += increment;
                    progressBar.style.width = width + '%';
                }
            }, 100);
        }
    }
    
    // Function to show a specific slide
    function showSlide(index) {
        // Update slideIndex
        slideIndex = index;
        
        // Handle index bounds
        if (slideIndex >= slides.length) {
            slideIndex = 0;
        }
        if (slideIndex < 0) {
            slideIndex = slides.length - 1;
        }
        
        // Remove active class from all slides and dots
        for (let i = 0; i < slides.length; i++) {
            slides[i].classList.remove("active");
            if (dots[i]) {
                dots[i].classList.remove("active");
            }
        }
        
        // Make current slide and dot active
        slides[slideIndex].classList.add("active");
        if (dots[slideIndex]) {
            dots[slideIndex].classList.add("active");
        }
        
        // Reset and restart progress bar
        resetProgressBar();
        startProgressBar();
    }
    
    // Function to advance to next slide
    function nextSlide() {
        showSlide(slideIndex + 1);
    }
    
    // Function to go to previous slide
    function prevSlide() {
        showSlide(slideIndex - 1);
    }
    
    // Set up event listeners for dots
    dots.forEach((dot, index) => {
        dot.addEventListener('click', function() {
            showSlide(index);
            startSlideshow(); // Restart the timer when manually changing slides
        });
    });
    
    // Set up event listeners for arrows
    if (prevArrow) {
        prevArrow.addEventListener('click', function() {
            prevSlide();
            startSlideshow(); // Restart the timer when manually changing slides
        });
    }
    
    if (nextArrow) {
        nextArrow.addEventListener('click', function() {
            nextSlide();
            startSlideshow(); // Restart the timer when manually changing slides
        });
    }
    
    // Pause slideshow on hover
    const slideshowContainer = document.querySelector(".slideshow-container-modern");
    if (slideshowContainer) {
        slideshowContainer.addEventListener('mouseenter', function() {
            clearInterval(slideInterval);
            clearInterval(progressInterval);
        });
        
        slideshowContainer.addEventListener('mouseleave', function() {
            startSlideshow();
        });
    }
    
    // Handle keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') {
            prevSlide();
            startSlideshow();
        } else if (e.key === 'ArrowRight') {
            nextSlide();
            startSlideshow();
        }
    });
    
    // Handle touch events for mobile
    let touchStartX = 0;
    let touchEndX = 0;
    
    if (slideshowContainer) {
        slideshowContainer.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, false);
        
        slideshowContainer.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, false);
    }
    
    function handleSwipe() {
        const swipeThreshold = 50; // Minimum distance for swipe
        
        if (touchEndX < touchStartX - swipeThreshold) {
            // Swipe left, go to next slide
            nextSlide();
            startSlideshow();
        } else if (touchEndX > touchStartX + swipeThreshold) {
            // Swipe right, go to previous slide
            prevSlide();
            startSlideshow();
        }
    }
});
</script>
