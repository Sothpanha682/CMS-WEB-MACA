<?php
// Prevent direct access
if (!defined('INCLUDED')) {
    define('INCLUDED', true);
}

// Page title
$page_title = "Popular Majors";

// Include header
require_once __DIR__ . '/../../config/api-keys.php'; // Include API keys
require_once __DIR__ . '/../../config/database.php'; // Include database connection

// Start session to use session variables for caching
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to fetch image from Pexels with caching
function fetchPexelsImage($query) {
    // Check if image is already in cache
    if (isset($_SESSION['pexels_cache'][$query])) {
        return $_SESSION['pexels_cache'][$query];
    }

    if (!defined('PEXELS_API_KEY') || PEXELS_API_KEY === 'YOUR_PEXELS_API_KEY') {
        $error_msg = 'Pexels API key is not defined or is a placeholder. Please set it in config/api-keys.php';
        error_log($error_msg);
        return 'assets/images/placeholder.jpg'; // Return placeholder image path
    }

    $api_key = PEXELS_API_KEY;
    $url = "https://api.pexels.com/v1/search?query=" . urlencode($query) . "&per_page=1";

    error_log("Pexels API: Attempting to fetch image for query '" . $query . "' from URL: " . $url . " with API Key: " . substr($api_key, 0, 5) . '...');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: ' . $api_key
    ));
    // Set CAINFO to the cacert.pem file for SSL verification
    curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . '/../cacert.pem');
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if ($response === false) {
        $curl_error = curl_error($ch);
        error_log("Pexels API cURL Error for query '" . $query . "': " . $curl_error);
        curl_close($ch);
        return 'assets/images/placeholder.jpg'; // Return placeholder image path on cURL error
    }
    
    curl_close($ch);

    if ($http_code === 200) {
        $data = json_decode($response, true);
        if (!empty($data['photos'][0]['src']['medium'])) {
            $image_url = $data['photos'][0]['src']['medium'];
            $_SESSION['pexels_cache'][$query] = $image_url; // Store in cache
            error_log("Pexels API: Successfully fetched image for query '" . $query . "': " . $image_url);
            return $image_url;
        } else {
            $error_msg = "Pexels API: No photos found for query '" . $query . "' or invalid response structure. Response: " . $response;
            error_log($error_msg);
            return 'assets/images/placeholder.jpg'; // Return placeholder image path
        }
    } else {
        $error_msg = "Pexels API Error: HTTP " . $http_code . " for query '" . $query . "' - Response: " . $response;
        error_log($error_msg);
        return 'assets/images/placeholder.jpg'; // Return placeholder image path
    }
    error_log("Pexels API: Fallback to placeholder for query '" . $query . "'");
    return 'assets/images/placeholder.jpg'; // Fallback to placeholder if all else fails
}

// Database caching for ONET API data
$cache_expiry_time = 60 * 60 * 24; // 24 hours

$all_popular_majors = [];
$needs_refresh = false;

try {
    // Check if data in popular_majors table is stale or empty
    $stmt = $pdo->query("SELECT created_at FROM popular_majors ORDER BY created_at ASC LIMIT 1");
    $oldest_entry = $stmt->fetchColumn();

    if (!$oldest_entry || (strtotime($oldest_entry) + $cache_expiry_time < time())) {
        $needs_refresh = true;
        error_log("Popular majors cache in database is stale or empty. Refreshing from ONET API.");
    } else {
        // Load from database
        $stmt = $pdo->query("SELECT * FROM popular_majors");
        $all_popular_majors = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("Loaded popular majors from database cache.");
    }
} catch (PDOException $e) {
    error_log("Database error when checking/loading cache: " . $e->getMessage());
    $needs_refresh = true; // Force refresh if database error
}


if ($needs_refresh) {
    error_log("Fetching popular majors from ONET API.");
    require_once __DIR__ . '/../../api/php/OnetWebService.php';

    $username = 'mymaca_asia'; // Provided by user
    $password = '9385emk'; // Provided by user
    $onet_ws = new OnetWebService($username, $password);

    $unique_occupation_codes = []; // To prevent duplicate occupations
    $fetched_majors_from_api = [];

    $search_keywords = [
        'Business Administration',
        'Computer Science',
        'Nursing',
        'Electrical Engineering',
        'Mechanical Engineering',
        'Civil Engineering',
        'Biomedical Engineering',
        'Accounting',
        'Finance',
        'Psychology',
        'Education',
        'Biology',
        'Chemistry',
        'Physics',
        'Economics',
        'Communications',
        'Journalism',
        'Law',
        'Marketing',
        'Information Systems',
        'Mathematics',
        'Statistics',
        'Pharmacy',
        'Environmental Science',
        'Graphic Design',
        'Hospitality Management',
        'Supply Chain Management',
        'Human Resources',
        'Public Health',
    ];
    
    // Filter out keywords that are too job-specific
    $job_specific_keywords = ['Technician', 'Specialist', 'Manager', 'Analyst', 'Developer', 'Administrator'];

    foreach ($search_keywords as $keyword) {
        // Fetch a smaller set of results for each keyword to aggregate data
        $kwresults = $onet_ws->call('online/search', array('keyword' => $keyword, 'end' => 5)); // Fetch top 5 related occupations

        if (property_exists($kwresults, 'error')) {
            error_log('Error fetching data from ONET API for keyword "' . $keyword . '": ' . $kwresults->error);
            continue; // Skip to next keyword on error
        }

        if (property_exists($kwresults, 'occupation') && is_array($kwresults->occupation) && !empty($kwresults->occupation)) {
            $all_skills = [];
            $all_career_ops = [];
            $descriptions = [];

            foreach ($kwresults->occupation as $onet_occupation) {
                $occupation_code = $onet_occupation->code ?? null;
                if (!$occupation_code) {
                    continue;
                }

                // Add occupation title to career opportunities
                if (property_exists($onet_occupation, 'title')) {
                    $all_career_ops[] = $onet_occupation->title;
                }

                // Fetch detailed occupation information
                $occupation_details = $onet_ws->call('online/occupations/' . $occupation_code);
                if (property_exists($occupation_details, 'error')) {
                    error_log('Error fetching detailed data from ONET API for occupation code "' . $occupation_code . '": ' . $occupation_details->error);
                } else {
                    if (property_exists($occupation_details, 'description') && !empty($occupation_details->description)) {
                        $descriptions[] = $occupation_details->description;
                    }

                    // Extract Skills Gained
                    if (property_exists($occupation_details, 'skills') && is_array($occupation_details->skills)) {
                        foreach ($occupation_details->skills as $skill_group) {
                            if (property_exists($skill_group, 'skill') && is_array($skill_group->skill)) {
                                foreach ($skill_group->skill as $skill_item) {
                                    if (property_exists($skill_item, 'name')) {
                                        $all_skills[] = $skill_item->name;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // Create a single major entry for the keyword
            $major = [
                'id' => count($fetched_majors_from_api) + 1,
                'title' => $keyword, // Use the keyword as the major title
                'description' => !empty($descriptions) ? $descriptions[0] : 'A diverse field with numerous career paths. Skills in this area are highly sought after in various industries.', // Use first description or a generic one
                'image_path' => 'assets/images/placeholder.jpg',
                'institutions' => 'Various Institutions',
                'duration' => '4 years',
                'skills_gained' => implode(', ', array_unique($all_skills)),
                'career_opportunities' => implode(', ', array_unique($all_career_ops)),
                'category' => strtolower(str_replace(' & ', '_', str_replace(' ', '', $keyword))),
            ];
            
            // Filter out results that are too job-specific
            $is_job_specific = false;
            foreach ($job_specific_keywords as $job_keyword) {
                if (strpos($major['title'], $job_keyword) !== false) {
                    $is_job_specific = true;
                    break;
                }
            }
            
            if (!$is_job_specific) {
                $fetched_majors_from_api[] = $major;
            }

            $min_salary = rand(35, 70) * 1000;
            $max_salary = $min_salary + rand(20, 50) * 1000;
            $major['avg_salary'] = '$' . number_format($min_salary) . ' - $' . number_format($max_salary);

            $fetched_majors_from_api[] = $major;
        }
    }

    // Clear existing data and insert new data into the database
    try {
        $pdo->beginTransaction();
        $pdo->exec("DELETE FROM popular_majors"); // Clear existing data

        $insert_stmt = $pdo->prepare("INSERT INTO popular_majors (title, description, students, color, image_path, is_active, display_order, created_at, title_kh, description_kh, avg_salary, duration, about_major, career_opportunities, skills_gained) VALUES (:title, :description, :students, :color, :image_path, :is_active, :display_order, NOW(), :title_kh, :description_kh, :avg_salary, :duration, :about_major, :career_opportunities, :skills_gained)");

        foreach ($fetched_majors_from_api as $major) {
            $insert_stmt->execute([
                ':title' => $major['title'],
                ':description' => $major['description'],
                ':students' => 'N/A', // Placeholder, as ONET API doesn't provide this
                ':color' => 'primary', // Default color
                ':image_path' => $major['image_path'],
                ':is_active' => 1,
                ':display_order' => 0,
                ':title_kh' => $major['title'], // Using English title for Khmer for now
                ':description_kh' => $major['description'], // Using English description for Khmer for now
                ':avg_salary' => $major['avg_salary'],
                ':duration' => $major['duration'],
                ':about_major' => $major['description'], // Using description for about_major
                ':career_opportunities' => $major['career_opportunities'],
                ':skills_gained' => $major['skills_gained'],
            ]);
        }
        $pdo->commit();
        $all_popular_majors = $fetched_majors_from_api; // Use newly fetched data
        error_log("Saved popular majors to database cache.");
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Database error when saving cache: " . $e->getMessage());
        // Fallback to using fetched_majors_from_api for current request if saving fails
        $all_popular_majors = $fetched_majors_from_api;
    }
}

// The rest of the pagination and display logic remains the same
$items_per_page = 12; // Number of majors to display per page
$current_page = isset($_GET['p']) ? (int)$_GET['p'] : 1; // Corrected to use 'p' for pagination
if ($current_page < 1) $current_page = 1;

// Apply search and filter from URL parameters
$search_term = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
$filter_category = isset($_GET['filter']) ? strtolower(trim($_GET['filter'])) : 'all';

$filtered_majors = [];
foreach ($all_popular_majors as $major) {
    $match_search = true;
    $match_filter = true;

    // Check search term
    if ($search_term) {
        $title = strtolower($major['title']);
        $description = strtolower($major['description']);
        $skills_gained = strtolower($major['skills_gained']);
        if (!str_contains($title, $search_term) && !str_contains($description, $search_term) && !str_contains($skills_gained, $search_term)) {
            $match_search = false;
        }
    }

    // Check filter category
    if ($filter_category !== 'all') {
        if ($major['category'] !== $filter_category) {
            $match_filter = false;
        }
    }

    if ($match_search && $match_filter) {
        $filtered_majors[] = $major;
    }
}

$total_majors = count($filtered_majors);
$total_pages = ceil($total_majors / $items_per_page);

// Get the majors for the current page
$offset = ($current_page - 1) * $items_per_page;
$popular_majors = array_slice($filtered_majors, $offset, $items_per_page);

// Fetch images for the currently displayed majors
foreach ($popular_majors as $index => $major_item) {
    $pexels_image_url = fetchPexelsImage($major_item['title'] . ' major');
    $popular_majors[$index]['image_path'] = $pexels_image_url ?: 'assets/images/placeholder.jpg';
}
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
        <?php if (count($popular_majors) > 0):
            foreach ($popular_majors as $index => $major):
        ?>
        <div class="major-card-new" data-category="<?php echo !empty($major['category']) ? $major['category'] : 'all'; ?>">
            <div class="major-image-container">
                <?php 
                $image_path = $major['image_path'];
                // Check if the image_path is a valid URL (starts with http/https)
                if (!empty($image_path) && (str_starts_with($image_path, 'http://') || str_starts_with($image_path, 'https://'))): 
                ?>
                    <img src="<?php echo $image_path; ?>" alt="<?php echo $major['title']; ?>" class="major-image">
                <?php else: ?>
                    <div class="major-placeholder">
                        <i class="fas fa-graduation-cap"></i>
                        <?php if (!empty($image_path)): ?>
                            <p class="text-white mt-2" style="font-size: 0.8rem;"><?php echo htmlspecialchars($image_path); ?></p>
                        <?php endif; ?>
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
                        <span>Avg. Salary: <?php echo $major['avg_salary']; ?></span>
                    </div>
                </div>
                <div class="major-description">
                    <?php echo mb_substr(strip_tags($major['description']), 0, 100) . '...'; ?>
                </div>
                <div class="major-skills">
                    <?php 
                    // Generate sample skills if none provided or extract from API
                    $skills = [];
                    if (!empty($major['skills_gained'])) {
                        $skills_text = strip_tags($major['skills_gained']);
                        $skills = explode(',', $skills_text);
                        $skills = array_slice($skills, 0, 3);
                    } else {
                        // Fallback sample skills
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
                                <?php 
                                $image_path_modal = $major['image_path'];
                                if (!empty($image_path_modal) && (str_starts_with($image_path_modal, 'http://') || str_starts_with($image_path_modal, 'https://'))): 
                                ?>
                                    <img src="<?php echo $image_path_modal; ?>" class="img-fluid rounded" alt="<?php echo $major['title']; ?>">
                                <?php else: ?>
                                    <div class="major-placeholder-modal">
                                        <i class="fas fa-graduation-cap"></i>
                                        <?php if (!empty($image_path_modal)): ?>
                                            <p class="text-white mt-2" style="font-size: 0.8rem;"><?php echo htmlspecialchars($image_path_modal); ?></p>
                                        <?php endif; ?>
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
                                            <strong>Avg. Salary:</strong> <?php echo $major['avg_salary']; ?>
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
        ?>
    </div>

    <!-- Pagination -->
    <div class="row mt-5">
        <div class="col-12">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php
                    $base_url = 'index.php?page=explore/popular-majors'; // Base URL for this page

                    // Append search and filter parameters to base URL for pagination
                    if ($search_term) {
                        $base_url .= '&search=' . urlencode($search_term);
                    }
                    if ($filter_category !== 'all') {
                        $base_url .= '&filter=' . urlencode($filter_category);
                    }

                    // Previous button
                    echo '<li class="page-item ' . (($current_page <= 1) ? 'disabled' : '') . '">';
                    echo '<a class="page-link" href="' . $base_url . '&p=' . ($current_page - 1) . '" tabindex="-1" aria-disabled="' . (($current_page <= 1) ? 'true' : 'false') . '">Previous</a>';
                    echo '</li>';

                    $range = 2; // Number of pages to show around the current page
                    $start_page = max(1, $current_page - $range);
                    $end_page = min($total_pages, $current_page + $range);

                    // First page and ellipsis
                    if ($start_page > 1) {
                        echo '<li class="page-item"><a class="page-link" href="' . $base_url . '&p=1">1</a></li>';
                        if ($start_page > 2) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                    }

                    // Page numbers
                    for ($i = $start_page; $i <= $end_page; $i++): ?>
                        <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo $base_url . '&p=' . $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor;

                    // Last page and ellipsis
                    if ($end_page < $total_pages) {
                        if ($end_page < $total_pages - 1) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                        echo '<li class="page-item"><a class="page-link" href="' . $base_url . '&p=' . $total_pages . '">' . $total_pages . '</a></li>';
                    }

                    // Next button
                    echo '<li class="page-item ' . (($current_page >= $total_pages) ? 'disabled' : '') . '">';
                    echo '<a class="page-link" href="' . $base_url . '&p=' . ($current_page + 1) . '">Next</a>';
                    echo '</li>';
                    ?>
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

/* Fix for disabled pagination links */
.pagination .page-item.disabled .page-link {
    color: #6c757d; /* Bootstrap's default disabled link color */
    pointer-events: none; /* Ensure it's not clickable */
    background-color: #e9ecef; /* Bootstrap's default disabled background */
    border-color: #dee2e6; /* Bootstrap's default disabled border */
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('majorSearch');
    const searchButton = document.getElementById('searchButton');
    const filterSelect = document.getElementById('majorFilter');
    const majorCardsContainer = document.querySelector('.majors-grid');
    const majorCards = Array.from(document.querySelectorAll('.major-card-new')); // Convert NodeList to Array for easier filtering

    // Function to update URL and trigger page reload for server-side filtering
    function updateUrlAndReload() {
        const searchTerm = searchInput.value;
        const selectedCategory = filterSelect.value;
        let url = 'index.php?page=explore/popular-majors';

        if (searchTerm) {
            url += '&search=' + encodeURIComponent(searchTerm);
        }
        if (selectedCategory !== 'all') {
            url += '&filter=' + encodeURIComponent(selectedCategory);
        }
        window.location.href = url;
    }

    // Event listeners for server-side filtering
    searchButton.addEventListener('click', updateUrlAndReload);
    searchInput.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            updateUrlAndReload();
        }
    });
    filterSelect.addEventListener('change', updateUrlAndReload);

    // Set initial values for search and filter inputs from URL parameters
    // This part still uses URL params for initial load, but subsequent filtering is live.
    const urlParams = new URLSearchParams(window.location.search);
    const initialSearchTerm = urlParams.get('search');
    const initialFilterCategory = urlParams.get('filter');

    if (initialSearchTerm) {
        searchInput.value = initialSearchTerm;
    }
    if (initialFilterCategory) {
        filterSelect.value = initialFilterCategory;
    }

    // Perform initial filtering based on URL parameters or default values
    liveFilterMajors();
});
</script>

<?php
// Include footer
?>
