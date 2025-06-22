<?php
// Prevent direct access
if (!defined('INCLUDED')) {
    define('INCLUDED', true);
}

// Page title
$page_title = "Popular Majors";

// Include header
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Popular Majors</li>
                </ol>
            </nav>
            <h1 class="display-4 text-danger">Popular Majors</h1>
            <p class="lead">Explore top academic fields chosen by our students</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="input-group">
                <input type="text" id="majorSearch" class="form-control" placeholder="Search majors...">
                <button class="btn btn-danger" type="button" id="searchButton">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
        </div>
        <div class="col-md-4">
            <select class="form-select" id="majorFilter">
                <option value="all">All Categories</option>
                <option value="science">Science & Technology</option>
                <option value="business">Business & Economics</option>
                <option value="arts">Arts & Humanities</option>
                <option value="health">Health Sciences</option>
                <option value="engineering">Engineering</option>
            </select>
        </div>
    </div>

    <div class="majors-grid">
        <?php
        // Fetch all popular majors from database
        try {
            $stmt = $pdo->query("SELECT * FROM popular_majors WHERE is_active = 1 ORDER BY display_order ASC");
            $popular_majors = $stmt->fetchAll();
            
            if (count($popular_majors) > 0):
                foreach ($popular_majors as $index => $major):
                    // Generate random salary range for demonstration
                    $min_salary = rand(35, 70) * 1000;
                    $max_salary = $min_salary + rand(20, 50) * 1000;
                    $salary_range = '$' . number_format($min_salary) . ' - $' . number_format($max_salary);
        ?>
        <div class="major-card-new" data-category="<?php echo !empty($major['category']) ? $major['category'] : 'all'; ?>">
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
                // Display message if no majors found
        ?>
        <div class="col-12">
            <div class="alert alert-info">No majors available at this time. Please check back later.</div>
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
                <h2 class="fw-bold text-danger">Need Help Choosing a Major?</h2>
                <p class="lead">Our career counselors can help you find the perfect major based on your interests, skills, and career goals.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="index.php?page=contact" class="btn btn-danger">Contact a Counselor</a>
            </div>
        </div>
    </div>
</div>

<style>
/* Popular Majors Grid Styles */
.majors-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
    margin-top: 30px;
}

/* Major Card Styles - Same as on homepage */
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

/* Responsive adjustments */
@media (max-width: 768px) {
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
}

@media (max-width: 576px) {
    .majors-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('majorSearch');
    const searchButton = document.getElementById('searchButton');
    const majorCards = document.querySelectorAll('.major-card-new');
    
    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase();
        
        majorCards.forEach(card => {
            const title = card.querySelector('.major-title').textContent.toLowerCase();
            const description = card.querySelector('.major-description').textContent.toLowerCase();
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
    const filterSelect = document.getElementById('majorFilter');
    
    filterSelect.addEventListener('change', function() {
        const selectedCategory = this.value;
        
        majorCards.forEach(card => {
            if (selectedCategory === 'all' || card.dataset.category === selectedCategory) {
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
