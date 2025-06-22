<?php
// Prevent direct access
if (!defined('INCLUDED')) {
    define('INCLUDED', true);
}

// Page title
$page_title = "Popular Careers";

// Include header
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Popular Careers</li>
                </ol>
            </nav>
            <h1 class="display-4 text-danger">Popular Careers</h1>
            <p class="lead">Explore in-demand career paths for graduates</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="input-group">
                <input type="text" id="careerSearch" class="form-control" placeholder="Search careers...">
                <button class="btn btn-danger" type="button" id="searchButton">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
        </div>
        <div class="col-md-4">
            <select class="form-select" id="careerFilter">
                <option value="all">All Industries</option>
                <option value="technology">Technology</option>
                <option value="healthcare">Healthcare</option>
                <option value="finance">Finance & Banking</option>
                <option value="education">Education</option>
                <option value="engineering">Engineering</option>
            </select>
        </div>
    </div>

    <div class="careers-grid">
        <?php
        // Fetch all popular jobs from database
        try {
            $stmt = $pdo->query("SELECT * FROM popular_jobs WHERE is_active = 1 ORDER BY display_order ASC");
            $popular_jobs = $stmt->fetchAll();
            
            if (count($popular_jobs) > 0):
                foreach ($popular_jobs as $index => $job):
                    // Set default industry if not specified
                    $industry = !empty($job['industry']) ? $job['industry'] : 'all';
                    
                    // Set default badge
                    $badges = ['Hot', 'New', 'Trending', ''];
                    $badge = $badges[array_rand($badges)];
        ?>
        <div class="career-card-new" data-industry="<?php echo $industry; ?>">
            <div class="career-image-container">
                <?php if (!empty($job['image_path'])): ?>
                    <img src="<?php echo $job['image_path']; ?>" alt="<?php echo $job['title']; ?>" class="career-image">
                <?php else: ?>
                    <div class="career-placeholder">
                        <i class="fas fa-briefcase"></i>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($badge)): ?>
                    <div class="career-badge"><?php echo $badge; ?></div>
                <?php endif; ?>
                
                <div class="career-overlay">
                    <button type="button" class="btn-explore" data-bs-toggle="modal" data-bs-target="#careerModal<?php echo $job['id']; ?>">
                        View Details
                    </button>
                </div>
            </div>
            <div class="career-content">
                <h3 class="career-title"><?php echo $job['title']; ?></h3>
                <div class="career-meta">
                    <div class="career-meta-item">
                        <i class="fas fa-building"></i>
                        <span><?php echo !empty($job['company']) ? $job['company'] : 'Various Companies'; ?></span>
                    </div>
                    <?php if (!empty($job['salary_range'])): ?>
                    <div class="career-meta-item">
                        <i class="fas fa-money-bill-wave"></i>
                        <span><?php echo $job['salary_range']; ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="career-description">
                    <?php echo mb_substr(strip_tags($job['description']), 0, 100) . '...'; ?>
                </div>
                <div class="career-skills">
                    <?php 
                    // Generate sample requirements if none provided
                    $requirements = [];
                    if (!empty($job['requirements'])) {
                        $req_text = strip_tags($job['requirements']);
                        // Try to extract list items or split by periods
                        if (strpos($req_text, '<li>') !== false) {
                            preg_match_all('/<li>(.*?)<\/li>/s', $job['requirements'], $matches);
                            if (!empty($matches[1])) {
                                $requirements = array_slice($matches[1], 0, 3);
                            }
                        } else {
                            $sentences = explode('.', $req_text);
                            $requirements = array_slice(array_filter($sentences), 0, 3);
                        }
                    }
                    
                    if (empty($requirements)) {
                        // Sample requirements based on job title
                        $sample_requirements = [
                            'Bachelor\'s Degree', 'Communication Skills', 'Problem Solving', 
                            'Team Leadership', 'Project Management', 'Technical Knowledge',
                            'Data Analysis', 'Customer Service', 'Critical Thinking', 
                            'Attention to Detail', 'Time Management', 'Creativity'
                        ];
                        shuffle($sample_requirements);
                        $requirements = array_slice($sample_requirements, 0, 3);
                    }
                    
                    foreach ($requirements as $req):
                    ?>
                    <span class="skill-tag"><?php echo trim($req); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <!-- Modal for career details -->
        <div class="modal fade" id="careerModal<?php echo $job['id']; ?>" tabindex="-1" aria-labelledby="careerModalLabel<?php echo $job['id']; ?>" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="careerModalLabel<?php echo $job['id']; ?>"><?php echo $job['title']; ?></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <?php if (!empty($job['image_path'])): ?>
                                    <img src="<?php echo $job['image_path']; ?>" class="img-fluid rounded" alt="<?php echo $job['title']; ?>">
                                <?php else: ?>
                                    <div class="career-placeholder-modal">
                                        <i class="fas fa-briefcase"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="mt-3">
                                    <h6 class="fw-bold">Career Details</h6>
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
                                        <li class="mb-2">
                                            <i class="fas fa-users text-danger me-2"></i>
                                            <strong>Openings:</strong> <?php echo rand(5, 50); ?>+
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h5 class="border-bottom pb-2 mb-3">Job Description</h5>
                                <div class="career-full-description mb-4">
                                    <?php echo !empty($job['description']) ? $job['description'] : 'No detailed description available.'; ?>
                                </div>
                                
                                <h5 class="border-bottom pb-2 mb-3">Requirements</h5>
                                <div class="career-requirements mb-4">
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
                                <div class="career-benefits">
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
                        <a href="#" class="btn btn-danger">Apply Now</a>
                    </div>
                </div>
            </div>
        </div>
        <?php 
                endforeach;
            else:
                // Display message if no careers found
        ?>
        <div class="col-12">
            <div class="alert alert-info">No careers available at this time. Please check back later.</div>
        </div>
        <?php endif;
        } catch(PDOException $e) {
            echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
        }
        ?>
    </div>

    <!-- Pagination -->
    <div class="row mt-5">
        <div class="col-12">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<div class="bg-light py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <h2 class="fw-bold text-danger">Need Help Finding the Right Career?</h2>
                <p class="lead">Our career counselors can help you explore career options based on your skills, interests, and educational background.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="index.php?page=contact" class="btn btn-danger">Contact a Counselor</a>
            </div>
        </div>
    </div>
</div>

<style>
/* Popular Careers Grid Styles */
.careers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
    margin-top: 30px;
}

/* Career Card Styles - Matching Major Cards */
.career-card-new {
    background-color: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
}

.career-card-new:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
}

.career-image-container {
    height: 200px;
    position: relative;
    overflow: hidden;
}

.career-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.career-card-new:hover .career-image {
    transform: scale(1.1);
}

.career-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #dc3545, #fd7e14);
    color: white;
    font-size: 4rem;
}

.career-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background-color: #dc3545;
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.75rem;
    z-index: 2;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.career-overlay {
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

.career-card-new:hover .career-overlay {
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

.career-card-new:hover .btn-explore {
    transform: translateY(0);
    opacity: 1;
    transition: all 0.3s ease 0.1s;
}

.btn-explore:hover {
    background-color: #f8f9fa;
    transform: scale(1.05);
}

.career-content {
    padding: 25px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.career-title {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 15px;
    color: #333;
    position: relative;
    padding-bottom: 10px;
}

.career-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 40px;
    height: 3px;
    background-color: #dc3545;
}

.career-meta {
    margin-bottom: 15px;
}

.career-meta-item {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
    color: #6c757d;
}

.career-meta-item i {
    margin-right: 8px;
    color: #dc3545;
    width: 16px;
}

.career-description {
    color: #6c757d;
    margin-bottom: 15px;
    line-height: 1.6;
    flex: 1;
}

.career-skills {
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

.career-placeholder-modal {
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

.career-full-description {
    line-height: 1.6;
    color: #333;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .careers-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .career-image-container {
        height: 180px;
    }
    
    .career-content {
        padding: 20px;
    }
}

@media (max-width: 576px) {
    .careers-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('careerSearch');
    const searchButton = document.getElementById('searchButton');
    const careerCards = document.querySelectorAll('.career-card-new');
    
    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase();
        
        careerCards.forEach(card => {
            const title = card.querySelector('.career-title').textContent.toLowerCase();
            const description = card.querySelector('.career-description').textContent.toLowerCase();
            const skills = Array.from(card.querySelectorAll('.skill-tag')).map(tag => tag.textContent.toLowerCase());
            
            if (title.includes(searchTerm) || description.includes(searchTerm) || skills.some(skill => skill.includes(searchTerm))) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    searchButton.addEventListener('click', performSearch);
    searchInput.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });
    
    // Filter functionality
    const filterSelect = document.getElementById('careerFilter');
    
    filterSelect.addEventListener('change', function() {
        const selectedIndustry = this.value;
        
        careerCards.forEach(card => {
            if (selectedIndustry === 'all' || card.dataset.industry === selectedIndustry) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
</script>

<?php
// Include footer
?>
