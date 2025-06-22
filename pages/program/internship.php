<?php
// Prevent direct access
if (!defined('INCLUDED')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

// Set page title
$pageTitle = "Internship Program - MACA";

// Sample internship data (in a real application, this would come from a database)
$all_internships = [
    [
        'id' => 1,
        'title' => 'Software Engineering Internship',
        'company' => 'Google',
        'location' => 'Mountain View, CA',
        'salary' => '$8,000/month',
        'summary' => 'Join Google\'s world-class engineering team and work on products used by billions of people worldwide. This internship offers hands-on experience with cutting-edge technology.',
        'image_path' => '/placeholder.svg?height=200&width=400',
        'deadline' => '2024-02-15',
        'created_at' => '2024-01-10 10:00:00',
        'type' => 'Technology',
        'duration' => '12 weeks',
        'applicants' => 2847,
        'remote' => false
    ],
    [
        'id' => 2,
        'title' => 'Marketing Internship',
        'company' => 'Microsoft',
        'location' => 'Seattle, WA',
        'salary' => '$6,500/month',
        'summary' => 'Drive marketing campaigns for Microsoft\'s cloud products and gain experience in digital marketing strategies with industry-leading professionals.',
        'image_path' => '/placeholder.svg?height=200&width=400',
        'deadline' => '2024-02-20',
        'created_at' => '2024-01-12 14:30:00',
        'type' => 'Marketing',
        'duration' => '10 weeks',
        'applicants' => 1523,
        'remote' => true
    ],
    [
        'id' => 3,
        'title' => 'Data Science Internship',
        'company' => 'Netflix',
        'location' => 'Los Gatos, CA',
        'salary' => '$7,200/month',
        'summary' => 'Analyze user behavior data and build recommendation algorithms for Netflix\'s streaming platform. Work with big data and machine learning.',
        'image_path' => '/placeholder.svg?height=200&width=400',
        'deadline' => '2024-02-25',
        'created_at' => '2024-01-15 09:15:00',
        'type' => 'Technology',
        'duration' => '12 weeks',
        'applicants' => 892,
        'remote' => false
    ],
    [
        'id' => 4,
        'title' => 'UX Design Internship',
        'company' => 'Apple',
        'location' => 'Cupertino, CA',
        'salary' => '$7,800/month',
        'summary' => 'Design intuitive user experiences for Apple\'s next-generation products and services. Collaborate with world-class design teams.',
        'image_path' => '/placeholder.svg?height=200&width=400',
        'deadline' => '2024-03-01',
        'created_at' => '2024-01-18 11:45:00',
        'type' => 'Design',
        'duration' => '14 weeks',
        'applicants' => 1247,
        'remote' => false
    ],
    [
        'id' => 5,
        'title' => 'Finance Internship',
        'company' => 'Goldman Sachs',
        'location' => 'New York, NY',
        'salary' => '$9,000/month',
        'summary' => 'Work with investment banking teams on high-profile deals and financial analysis. Gain exposure to global financial markets.',
        'image_path' => '/placeholder.svg?height=200&width=400',
        'deadline' => '2024-02-18',
        'created_at' => '2024-01-20 16:20:00',
        'type' => 'Finance',
        'duration' => '10 weeks',
        'applicants' => 3421,
        'remote' => false
    ],
    [
        'id' => 6,
        'title' => 'Cybersecurity Internship',
        'company' => 'Amazon',
        'location' => 'Seattle, WA',
        'salary' => '$7,500/month',
        'summary' => 'Protect AWS infrastructure and develop security solutions for cloud services. Work on cutting-edge cybersecurity challenges.',
        'image_path' => '/placeholder.svg?height=200&width=400',
        'deadline' => '2024-03-05',
        'created_at' => '2024-01-22 13:10:00',
        'type' => 'Technology',
        'duration' => '12 weeks',
        'applicants' => 756,
        'remote' => true
    ],
    [
        'id' => 7,
        'title' => 'Product Management Internship',
        'company' => 'Meta',
        'location' => 'Menlo Park, CA',
        'salary' => '$8,200/month',
        'summary' => 'Lead product development initiatives and work with cross-functional teams to build products used by billions of people worldwide.',
        'image_path' => '/placeholder.svg?height=200&width=400',
        'deadline' => '2024-03-10',
        'created_at' => '2024-01-25 08:30:00',
        'type' => 'Technology',
        'duration' => '12 weeks',
        'applicants' => 1876,
        'remote' => false
    ],
    [
        'id' => 8,
        'title' => 'Healthcare Analytics Internship',
        'company' => 'Johnson & Johnson',
        'location' => 'New Brunswick, NJ',
        'salary' => '$6,800/month',
        'summary' => 'Analyze healthcare data to improve patient outcomes and develop insights for pharmaceutical research and development.',
        'image_path' => '/placeholder.svg?height=200&width=400',
        'deadline' => '2024-03-15',
        'created_at' => '2024-01-28 12:00:00',
        'type' => 'Healthcare',
        'duration' => '10 weeks',
        'applicants' => 654,
        'remote' => true
    ],
    [
        'id' => 9,
        'title' => 'Mechanical Engineering Internship',
        'company' => 'Tesla',
        'location' => 'Fremont, CA',
        'salary' => '$7,000/month',
        'summary' => 'Work on electric vehicle design and manufacturing processes. Contribute to sustainable transportation solutions.',
        'image_path' => '/placeholder.svg?height=200&width=400',
        'deadline' => '2024-03-20',
        'created_at' => '2024-01-30 15:45:00',
        'type' => 'Engineering',
        'duration' => '12 weeks',
        'applicants' => 1123,
        'remote' => false
    ],
    [
        'id' => 10,
        'title' => 'Digital Marketing Internship',
        'company' => 'Adobe',
        'location' => 'San Jose, CA',
        'salary' => '$6,200/month',
        'summary' => 'Create and execute digital marketing campaigns for Adobe\'s creative software products. Learn from marketing experts.',
        'image_path' => '/placeholder.svg?height=200&width=400',
        'deadline' => '2024-03-25',
        'created_at' => '2024-02-01 10:20:00',
        'type' => 'Marketing',
        'duration' => '10 weeks',
        'applicants' => 987,
        'remote' => true
    ]
];
?>

<div class="container py-4">
    <h1 class="fw-bold text-danger mb-4"><?php echo getLangText('Internship Program', 'កម្មវិធីកម្មសិក្សា'); ?></h1>
    
    <?php
    // Pagination settings
    $items_per_page = 9;
    $current_page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
    $current_page = max(1, $current_page); // Ensure page is at least 1
    $offset = ($current_page - 1) * $items_per_page;
    
    // Get total count of internships
    $total_items = count($all_internships);
    $total_pages = ceil($total_items / $items_per_page);
    $current_page = min($current_page, max(1, $total_pages)); // Ensure page doesn't exceed total
    
    // Calculate display range
    $start_item = ($current_page - 1) * $items_per_page + 1;
    $end_item = min($current_page * $items_per_page, $total_items);
    
    // Get paginated internships
    $internship_items = array_slice($all_internships, $offset, $items_per_page);
    ?>
    
    <div class="row">
        <?php
        if (count($internship_items) > 0):
            foreach ($internship_items as $internship):
        ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 hover-shadow">
                <?php if (!empty($internship['image_path'])): ?>
                <img src="<?php echo htmlspecialchars($internship['image_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($internship['title']); ?>" style="height: 200px; object-fit: cover;">
                <?php endif; ?>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-danger"><?php echo htmlspecialchars($internship['type']); ?></span>
                        <small class="text-muted"><?php echo getLangText('Deadline: ', 'ថ្ងៃផុតកំណត់: '); ?><?php echo formatDate($internship['deadline']); ?></small>
                    </div>
                    <h5 class="card-title"><?php echo htmlspecialchars($internship['title']); ?></h5>
                    <h6 class="text-primary mb-2"><?php echo htmlspecialchars($internship['company']); ?></h6>
                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($internship['location']); ?>
                            <?php if ($internship['remote']): ?>
                                <span class="badge bg-success ms-1">Remote</span>
                            <?php endif; ?>
                        </small>
                    </div>
                    <div class="mb-2">
                        <span class="badge bg-success"><?php echo htmlspecialchars($internship['salary']); ?></span>
                        <span class="badge bg-info ms-1"><?php echo htmlspecialchars($internship['duration']); ?></span>
                    </div>
                    <p class="card-text"><?php echo htmlspecialchars($internship['summary']); ?></p>
                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="fas fa-users"></i> <?php echo number_format($internship['applicants']); ?> <?php echo getLangText('applicants', 'អ្នកដាក់ពាក្យ'); ?>
                        </small>
                    </div>
                </div>
                <div class="card-footer bg-white border-0">
                    <a href="index.php?page=internship-detail&id=<?php echo $internship['id']; ?>" class="btn btn-outline-danger me-2"><?php echo getLangText('View Details', 'មើលព័ត៌មានលម្អិត'); ?></a>
                    <a href="#" class="btn btn-danger"><?php echo getLangText('Apply Now', 'ដាក់ពាក្យឥឡូវ'); ?></a>
                </div>
            </div>
        </div>
        <?php
            endforeach;
        else:
        ?>
        <div class="col-12">
            <div class="alert alert-info"><?php echo getLangText('No internship opportunities available at this time.', 'មិនមានឱកាសកម្មសិក្សានៅពេលនេះទេ។'); ?></div>
        </div>
        <?php endif; ?>
    </div>
    
    <?php if ($total_pages > 1): ?>
    <!-- Pagination -->
    <div class="mt-4">
        <div class="text-center mb-3">
            <p class="text-muted mb-2">
                <?php echo getLangText(
                    "Showing {$start_item}-{$end_item} of {$total_items} internships",
                    "បង្ហាញ {$start_item}-{$end_item} នៃកម្មសិក្សាសរុប {$total_items}"
                ); ?>
            </p>
            <small class="text-muted">
                <?php echo getLangText("Page $current_page of $total_pages", "ទំព័រ $current_page នៃ $total_pages"); ?>
            </small>
        </div>
        
        <div class="d-flex justify-content-center">
            <nav aria-label="Internship pagination">
                <ul class="pagination">
                <!-- Previous button -->
                <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link text-danger" href="<?php echo ($current_page > 1) ? 'index.php?page=program/internship&page_num=' . ($current_page - 1) : '#'; ?>" aria-label="Previous">
                        <span aria-hidden="true"><i class="bi bi-chevron-left"></i> <?php echo getLangText('Previous', 'មុន'); ?></span>
                    </a>
                </li>
                
                <!-- Page 1 -->
                <li class="page-item <?php echo ($current_page == 1) ? 'active' : ''; ?>">
                    <a class="page-link <?php echo ($current_page == 1) ? 'bg-danger border-danger' : 'text-danger border-danger'; ?>" href="index.php?page=program/internship&page_num=1">1</a>
                </li>
                
                <!-- Page 2 (if exists) -->
                <?php if ($total_pages >= 2): ?>
                <li class="page-item <?php echo ($current_page == 2) ? 'active' : ''; ?>">
                    <a class="page-link <?php echo ($current_page == 2) ? 'bg-danger border-danger' : 'text-danger border-danger'; ?>" href="index.php?page=program/internship&page_num=2">2</a>
                </li>
                <?php endif; ?>
                
                <!-- Page 3 (if exists) -->
                <?php if ($total_pages >= 3): ?>
                <li class="page-item <?php echo ($current_page == 3) ? 'active' : ''; ?>">
                    <a class="page-link <?php echo ($current_page == 3) ? 'bg-danger border-danger' : 'text-danger border-danger'; ?>" href="index.php?page=program/internship&page_num=3">3</a>
                </li>
                <?php endif; ?>
                
                <!-- Ellipsis if more than 3 pages -->
                <?php if ($total_pages > 3): ?>
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
                <?php endif; ?>
                
                <!-- Next button -->
                <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                    <a class="page-link text-danger" href="<?php echo ($current_page < $total_pages) ? 'index.php?page=program/internship&page_num=' . ($current_page + 1) : '#'; ?>" aria-label="Next">
                        <span aria-hidden="true"><?php echo getLangText('Next Page', 'ទំព័របន្ទាប់'); ?> <i class="bi bi-chevron-right"></i></span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    </div>
    <?php endif; ?>
</div>

<style>
.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.card-footer {
    padding: 1rem;
}

.badge {
    font-size: 0.75rem;
}
</style>
