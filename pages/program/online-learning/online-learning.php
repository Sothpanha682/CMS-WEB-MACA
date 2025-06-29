<?php
// Get courses with filters
$category = isset($_GET['category']) ? sanitize($_GET['category']) : '';
$level = isset($_GET['level']) ? sanitize($_GET['level']) : '';
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$sort = isset($_GET['sort']) ? sanitize($_GET['sort']) : 'newest';

// Pagination
$page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$limit = 12;
$offset = ($page - 1) * $limit;

// Build query
$where_conditions = ["is_active = 1"];
$params = [];

if ($category) {
    $where_conditions[] = "category = ?";
    $params[] = $category;
}

if ($level) {
    $where_conditions[] = "level = ?";
    $params[] = $level;
}

if ($search) {
    $where_conditions[] = "(title LIKE ? OR description LIKE ? OR instructor_name LIKE ?)";
    $search_term = "%$search%";
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
}

$where_clause = implode(" AND ", $where_conditions);

// Sort options
$order_by = "created_at DESC";
switch ($sort) {
    case 'popular':
        $order_by = "total_students DESC";
        break;
    case 'rating':
        $order_by = "rating DESC";
        break;
    case 'price_low':
        $order_by = "price ASC";
        break;
    case 'price_high':
        $order_by = "price DESC";
        break;
    case 'newest':
    default:
        $order_by = "created_at DESC";
        break;
}

// Get total count
try {
    $count_sql = "SELECT COUNT(*) FROM online_courses WHERE $where_clause";
    $count_stmt = $pdo->prepare($count_sql);
    $count_stmt->execute($params);
    $total_courses = $count_stmt->fetchColumn();
    $total_pages = ceil($total_courses / $limit);
} catch (PDOException $e) {
    $total_courses = 0;
    $total_pages = 1;
}

// Get courses
try {
    $sql = "SELECT * FROM online_courses WHERE $where_clause ORDER BY $order_by LIMIT ? OFFSET ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_merge($params, [$limit, $offset]));
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $courses = [];
}

// Get featured courses
try {
    $featured_stmt = $pdo->prepare("SELECT * FROM online_courses WHERE is_active = 1 AND is_featured = 1 ORDER BY created_at DESC LIMIT 3");
    $featured_stmt->execute();
    $featured_courses = $featured_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $featured_courses = [];
}

// Get categories for filter
try {
    $cat_stmt = $pdo->query("SELECT DISTINCT category FROM online_courses WHERE is_active = 1 ORDER BY category");
    $categories = $cat_stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $categories = [];
}

$levels = ['Beginner', 'Intermediate', 'Advanced', 'All Levels'];
?>

<div class="bg-light">
    <!-- Hero Section -->
    <div class="bg-danger text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-3">Learn Without Limits</h1>
                    <p class="lead mb-4">Start, switch, or advance your career with more than 5,000 courses, Professional Certificates, and degrees from world-class universities and companies.</p>
                    <div class="d-flex gap-3">
                        <a href="#courses" class="btn btn-light btn-lg text-danger">Browse Courses</a>
                        <a href="#featured" class="btn btn-outline-light btn-lg">Featured Courses</a>
                    </div>
                </div>
                <div class="col-lg-4 text-center">
                    <img src="https://via.placeholder.com/400x300?text=Online+Learning" alt="Online Learning" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white py-4 shadow-sm">
        <div class="container">
            <form method="GET" class="row g-3 align-items-end">
                <input type="hidden" name="page" value="program/online-learning">
                
                <div class="col-md-4">
                    <label for="search" class="form-label">Search Courses</label>
                    <input type="text" class="form-control" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="What do you want to learn?">
                </div>
                
                <div class="col-md-2">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-select" id="category" name="category">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $category == $cat ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="level" class="form-label">Level</label>
                    <select class="form-select" id="level" name="level">
                        <option value="">All Levels</option>
                        <?php foreach ($levels as $lvl): ?>
                            <option value="<?php echo $lvl; ?>" <?php echo $level == $lvl ? 'selected' : ''; ?>>
                                <?php echo $lvl; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="sort" class="form-label">Sort By</label>
                    <select class="form-select" id="sort" name="sort">
                        <option value="newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Newest</option>
                        <option value="popular" <?php echo $sort == 'popular' ? 'selected' : ''; ?>>Most Popular</option>
                        <option value="rating" <?php echo $sort == 'rating' ? 'selected' : ''; ?>>Highest Rated</option>
                        <option value="price_low" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price_high" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Featured Courses -->
    <?php if (!empty($featured_courses)): ?>
    <div class="container py-5" id="featured">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="h3 fw-bold text-danger mb-3">Featured Courses</h2>
                <p class="text-muted">Hand-picked courses from our catalog</p>
            </div>
        </div>
        
        <div class="row">
            <?php foreach ($featured_courses as $course): ?>
                <div class="col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm border-0 course-card">
                        <div class="position-relative">
                            <img src="<?php echo $course['course_image'] ? '../../uploads/courses/' . htmlspecialchars($course['course_image']) : 'https://via.placeholder.com/400x200?text=' . urlencode($course['title']); ?>" 
                                 class="card-img-top" alt="<?php echo htmlspecialchars($course['title']); ?>" style="height: 200px; object-fit: cover;">
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-warning text-dark">Featured</span>
                            </div>
                            <?php if ($course['is_bestseller']): ?>
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-success">Bestseller</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <span class="badge bg-primary me-2"><?php echo htmlspecialchars($course['category']); ?></span>
                                <span class="badge bg-secondary"><?php echo htmlspecialchars($course['level']); ?></span>
                            </div>
                            
                            <h5 class="card-title fw-bold"><?php echo htmlspecialchars($course['title']); ?></h5>
                            <p class="card-text text-muted small mb-2">by <?php echo htmlspecialchars($course['instructor_name']); ?></p>
                            <p class="card-text flex-grow-1"><?php echo htmlspecialchars($course['short_description']); ?></p>
                            
                            <div class="d-flex align-items-center mb-3">
                                <?php if ($course['rating'] > 0): ?>
                                    <div class="text-warning me-2">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star<?php echo $i <= $course['rating'] ? '' : '-o'; ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="me-2"><?php echo $course['rating']; ?></span>
                                    <small class="text-muted">(<?php echo number_format($course['total_reviews']); ?>)</small>
                                <?php endif; ?>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <?php if ($course['price'] > 0): ?>
                                        <span class="h5 text-danger fw-bold">$<?php echo number_format($course['price'], 2); ?></span>
                                        <?php if ($course['original_price'] > $course['price']): ?>
                                            <small class="text-muted text-decoration-line-through ms-2">$<?php echo number_format($course['original_price'], 2); ?></small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="h5 text-success fw-bold">Free</span>
                                    <?php endif; ?>
                                </div>
                                <a href="#" class="btn btn-outline-danger btn-sm">Learn More</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- All Courses -->
    <div class="container py-5" id="courses">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="h3 fw-bold text-danger mb-3">
                    <?php if ($search || $category || $level): ?>
                        Search Results
                    <?php else: ?>
                        All Courses
                    <?php endif; ?>
                </h2>
                <p class="text-muted">
                    Showing <?php echo number_format($total_courses); ?> courses
                    <?php if ($search): ?>
                        for "<?php echo htmlspecialchars($search); ?>"
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <?php if (empty($courses)): ?>
            <div class="text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No courses found</h4>
                <p class="text-muted">Try adjusting your search criteria or browse all courses.</p>
                <a href="index.php?page=program/online-learning" class="btn btn-danger">Browse All Courses</a>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($courses as $course): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-0 course-card">
                            <div class="position-relative">
                                <img src="<?php echo $course['course_image'] ? '../../uploads/courses/' . htmlspecialchars($course['course_image']) : 'https://via.placeholder.com/400x200?text=' . urlencode($course['title']); ?>" 
                                     class="card-img-top" alt="<?php echo htmlspecialchars($course['title']); ?>" style="height: 200px; object-fit: cover;">
                                
                                <?php if ($course['is_new']): ?>
                                    <div class="position-absolute top-0 start-0 m-2">
                                        <span class="badge bg-info">New</span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($course['is_bestseller']): ?>
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-success">Bestseller</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-body d-flex flex-column">
                                <div class="mb-2">
                                    <span class="badge bg-primary me-2"><?php echo htmlspecialchars($course['category']); ?></span>
                                    <span class="badge bg-secondary"><?php echo htmlspecialchars($course['level']); ?></span>
                                </div>
                                
                                <h5 class="card-title fw-bold"><?php echo htmlspecialchars($course['title']); ?></h5>
                                <p class="card-text text-muted small mb-2">by <?php echo htmlspecialchars($course['instructor_name']); ?></p>
                                <p class="card-text flex-grow-1"><?php echo htmlspecialchars($course['short_description']); ?></p>
                                
                                <div class="mb-2">
                                    <?php if ($course['duration_weeks'] > 0 || $course['duration_hours'] > 0): ?>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            <?php if ($course['duration_weeks'] > 0): ?>
                                                <?php echo $course['duration_weeks']; ?> weeks
                                            <?php endif; ?>
                                            <?php if ($course['duration_hours'] > 0): ?>
                                                <?php echo $course['duration_hours']; ?> hours
                                            <?php endif; ?>
                                        </small>
                                    <?php endif; ?>
                                    
                                    <?php if ($course['total_students'] > 0): ?>
                                        <small class="text-muted ms-3">
                                            <i class="fas fa-users me-1"></i>
                                            <?php echo number_format($course['total_students']); ?> students
                                        </small>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="d-flex align-items-center mb-3">
                                    <?php if ($course['rating'] > 0): ?>
                                        <div class="text-warning me-2">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star<?php echo $i <= $course['rating'] ? '' : '-o'; ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="me-2"><?php echo $course['rating']; ?></span>
                                        <small class="text-muted">(<?php echo number_format($course['total_reviews']); ?>)</small>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <?php if ($course['price'] > 0): ?>
                                            <span class="h5 text-danger fw-bold">$<?php echo number_format($course['price'], 2); ?></span>
                                            <?php if ($course['original_price'] > $course['price']): ?>
                                                <small class="text-muted text-decoration-line-through ms-2">$<?php echo number_format($course['original_price'], 2); ?></small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="h5 text-success fw-bold">Free</span>
                                        <?php endif; ?>
                                    </div>
                                    <a href="#" class="btn btn-outline-danger btn-sm">Learn More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <nav aria-label="Courses pagination" class="mt-5">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=program/online-learning&p=<?php echo $page - 1; ?>&<?php echo http_build_query(array_filter(['category' => $category, 'level' => $level, 'search' => $search, 'sort' => $sort])); ?>">Previous</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=program/online-learning&p=<?php echo $i; ?>&<?php echo http_build_query(array_filter(['category' => $category, 'level' => $level, 'search' => $search, 'sort' => $sort])); ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=program/online-learning&p=<?php echo $page + 1; ?>&<?php echo http_build_query(array_filter(['category' => $category, 'level' => $level, 'search' => $search, 'sort' => $sort])); ?>">Next</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<style>
.course-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.course-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.badge {
    font-size: 0.75rem;
}

.text-warning .fas {
    font-size: 0.875rem;
}
</style>
