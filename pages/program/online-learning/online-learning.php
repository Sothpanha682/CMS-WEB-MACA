<?php
require_once 'config/database.php';
require_once 'includes/functions.php'; // Assuming isLoggedIn and isAdmin are here, or other useful functions

// Initialize variables
$category = isset($_GET['category']) ? $_GET['category'] : '';
$level = isset($_GET['level']) ? $_GET['level'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$limit = 12; // Number of courses per page
$offset = ($page - 1) * $limit;

$courses = [];
$featured_courses = [];
$total_courses = 0;
$total_pages = 1;

try {
    // Fetch categories and levels for filters
    $stmtCategories = $pdo->query("SELECT DISTINCT category FROM online_courses WHERE is_active = 1 ORDER BY category ASC");
    $categories = $stmtCategories->fetchAll(PDO::FETCH_COLUMN);

    $stmtLevels = $pdo->query("SELECT DISTINCT level FROM online_courses WHERE is_active = 1 ORDER BY level ASC");
    $levels = $stmtLevels->fetchAll(PDO::FETCH_COLUMN);

    // Build the WHERE clause for filtering and searching
    $where_clauses = ["is_active = 1"];
    $params = [];

    if (!empty($category)) {
        $where_clauses[] = "category = :category";
        $params[':category'] = $category;
    }
    if (!empty($level)) {
        $where_clauses[] = "level = :level";
        $params[':level'] = $level;
    }
    if (!empty($search)) {
        $where_clauses[] = "(title LIKE :search OR short_description LIKE :search OR instructor_name LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }

    $where_sql = count($where_clauses) > 0 ? "WHERE " . implode(" AND ", $where_clauses) : "";

    // Build the ORDER BY clause for sorting
    $order_sql = "";
    switch ($sort) {
        case 'popular':
            $order_sql = "ORDER BY total_students DESC";
            break;
        case 'rating':
            $order_sql = "ORDER BY rating DESC";
            break;
        case 'price_low':
            $order_sql = "ORDER BY price ASC";
            break;
        case 'price_high':
            $order_sql = "ORDER BY price DESC";
            break;
        case 'newest':
        default:
            $order_sql = "ORDER BY created_at DESC";
            break;
    }

    // Get total count of filtered courses
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM online_courses " . $where_sql);
    $countStmt->execute($params);
    $total_courses = $countStmt->fetchColumn();
    $total_pages = ceil($total_courses / $limit);

    // Fetch filtered and paginated courses
    $stmt = $pdo->prepare("SELECT * FROM online_courses " . $where_sql . " " . $order_sql . " LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    foreach ($params as $key => &$val) {
        $stmt->bindParam($key, $val);
    }
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch featured courses (always active and featured)
    $featuredStmt = $pdo->prepare("SELECT * FROM online_courses WHERE is_active = 1 AND is_featured = 1 ORDER BY created_at DESC LIMIT 3");
    $featuredStmt->execute();
    $featured_courses = $featuredStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Log error or display a user-friendly message
    error_log("Error fetching online courses: " . $e->getMessage());
    // Optionally, set a message for the user
    // $_SESSION['message'] = "Could not load courses. Please try again later.";
    // $_SESSION['message_type'] = "danger";
}
?>

<div class="filter-bar-wrapper bg-light">
    <div class="container py-4">
        <form method="GET" class="row g-3 align-items-end">
            <input type="hidden" name="page" value="program/online-learning">
            
            <div class="col-md-4">
                <label for="search" class="form-label fw-bold"><?php echo getLangText('Search Courses', 'ស្វែងរកវគ្គសិក្សា'); ?></label>
                <input type="text" class="form-control form-control-lg" id="search" name="search" value="<?= htmlspecialchars($search); ?>"  placeholder="What do you want to learn?">
            </div>
            
            <div class="col-md-2">
                <label for="category" class="form-label"><?php echo getLangText('Category', 'ប្រភេទ'); ?></label>
                <select class="form-select" id="category" name="category">
                    <option value=""><?php echo getLangText('All', 'ទាំងអស់'); ?></option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat); ?>" <?= $category == $cat ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($cat); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="level" class="form-label"><?php echo getLangText('Level', 'កម្រិត'); ?></label>
                <select class="form-select" id="level" name="level">
                    <option value=""><?php echo getLangText('All', 'ទាំងអស់'); ?></option>
                    <?php foreach ($levels as $lvl): ?>
                        <option value="<?= $lvl; ?>" <?= $level == $lvl ? 'selected' : ''; ?>><?= $lvl; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="sort" class="form-label"><?php echo getLangText('Sort By', 'តម្រៀបតាម'); ?></label>
                <select class="form-select" id="sort" name="sort">
                    <option value="newest" <?= $sort == 'newest' ? 'selected' : ''; ?>><?php echo getLangText('Newest', 'ថ្មីបំផុត'); ?></option>
                    <option value="popular" <?= $sort == 'popular' ? 'selected' : ''; ?>><?php echo getLangText('Popular', 'ពេញនិយម'); ?></option>
                    <option value="rating" <?= $sort == 'rating' ? 'selected' : ''; ?>><?php echo getLangText('Rated', 'វាយតម្លៃ'); ?></option>
                    <option value="price_low" <?= $sort == 'price_low' ? 'selected' : ''; ?>><?php echo getLangText('Price: Low-High', 'តម្លៃ: ទាប-ខ្ពស់'); ?></option>
                    <option value="price_high" <?= $sort == 'price_high' ? 'selected' : ''; ?>><?php echo getLangText('Price: High-Low', 'តម្លៃ: ខ្ពស់-ទាប'); ?></option>
                </select>
            </div>
            
            <div class="col-md-2">
                <button type="submit" class="btn btn-danger btn-lg w-100 search-btn">
                    <i class="fas fa-search me-2"></i><?php echo getLangText('Search', 'ស្វែងរក'); ?>
                </button>
            </div>
        </form>
    </div>
</div>

<div class="container py-5">
    <?php if (!empty($featured_courses) && empty($search)): ?>
    <div class="mb-5">
        <div class="row mb-3 align-items-center">
            <div class="col">
                <h2 class="h3 fw-bold mb-0"><?php echo getLangText('Featured Courses', 'វគ្គសិក្សាពិសេស'); ?></h2>
                <p class="text-muted mb-0"><?php echo getLangText('Hand-picked courses from our catalog', 'វគ្គសិក្សាដែលបានជ្រើសរើសយ៉ាងយកចិត្តទុកដាក់ពីកាតាឡុករបស់យើង'); ?></p>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($featured_courses as $course): ?>
                <div class="col">
                    <div class="card h-100 course-card border-0 shadow-sm">
                        <div class="position-relative">
                            <img src="<?= $course['course_image'] ? '../../uploads/courses/' . htmlspecialchars($course['course_image']) : 'https://i.pravatar.cc/400?u=' . urlencode($course['title']); ?>" class="card-img-top" alt="<?= htmlspecialchars($course['title']); ?>">
                            <?php if ($course['is_bestseller']): ?>
                                <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2"><?php echo getLangText('Bestseller', 'លក់ដាច់បំផុត'); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body d-flex flex-column pb-0">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge bg-danger-soft text-danger"><?= htmlspecialchars($course['category']); ?></span>
                                <span class="badge bg-secondary-soft text-secondary"><?= htmlspecialchars($course['level']); ?></span>
                            </div>
                            <h5 class="card-title fw-bold">
                                <a href="#" class="text-dark stretched-link text-decoration-none"><?= htmlspecialchars($course['title']); ?></a>
                            </h5>
                            <p class="card-text text-muted small mb-2"><?php echo getLangText('by', 'ដោយ'); ?> <?= htmlspecialchars($course['instructor_name']); ?></p>
                        </div>
                        <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="text-warning">
                                    <small class="text-dark me-1"><?= $course['rating']; ?></small>
                                    <?php for ($i = 1; $i <= 5; $i++): ?><i class="fas fa-star<?= $i <= round($course['rating']) ? '' : ' fa-regular'; ?>"></i><?php endfor; ?>
                                    <small class="text-muted">(<?= number_format($course['total_reviews']); ?>)</small>
                                </div>
                                <div class="d-flex gap-3 text-muted small">
                                    <span><i class="fas fa-clock me-1"></i> 8h</span>
                                    <span><i class="fas fa-users me-1"></i> 12k</span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <?php if ($course['price'] > 0): ?>
                                    <span class="h5 text-danger fw-bold mb-0">$<?= number_format($course['price'], 2); ?>
                                        <small class="text-muted text-decoration-line-through ms-1 fs-6">$<?= number_format($course['original_price'], 2); ?></small>
                                    </span>
                                <?php else: ?>
                                    <span class="h5 text-success fw-bold mb-0"><?php echo getLangText('Free', 'ឥតគិតថ្លៃ'); ?></span>
                                <?php endif; ?>
                                <span class="learn-more-btn"><i class="fas fa-arrow-right"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <hr class="my-5">
    </div>
    <?php endif; ?>

    <?php if (empty($courses) && empty($featured_courses)): ?>
        <div class="text-center py-5">
            <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
            <h3 class="mt-4"><?php echo getLangText('No Courses Found', 'រកមិនឃើញវគ្គសិក្សា'); ?></h3>
            <p class="text-muted"><?php echo getLangText('We couldn\'t find any courses matching your criteria.', 'យើងរកមិនឃើញវគ្គសិក្សាណាមួយដែលត្រូវនឹងលក្ខណៈវិនិច្ឆ័យរបស់អ្នកទេ។'); ?> <br><?php echo getLangText('Try adjusting your filters.', 'ព្យាយាមកែសម្រួលតម្រងរបស់អ្នក។'); ?></p>
            <a href="index.php?page=program/online-learning" class="btn btn-danger mt-2"><?php echo getLangText('Browse All Courses', 'រុករកវគ្គសិក្សាទាំងអស់'); ?></a>
        </div>
    <?php else: ?>
        <div class="mb-5">
            <div class="row mb-3 align-items-center">
                <div class="col">
                    <h2 class="h3 fw-bold mb-0"><?php echo getLangText('All Online Courses', 'វគ្គសិក្សាអនឡាញទាំងអស់'); ?></h2>
                    <p class="text-muted mb-0"><?php echo getLangText('Showing', 'បង្ហាញ'); ?> <?= count($courses); ?> <?php echo getLangText('of', 'នៃ'); ?> <?= $total_courses; ?> <?php echo getLangText('courses', 'វគ្គសិក្សា'); ?></p>
                </div>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php foreach ($courses as $course): ?>
                    <div class="col">
                        <div class="card h-100 course-card border-0 shadow-sm">
                            <div class="position-relative">
                                <img src="<?= $course['course_image'] ? '../../uploads/courses/' . htmlspecialchars($course['course_image']) : 'https://i.pravatar.cc/400?u=' . urlencode($course['title']); ?>" class="card-img-top" alt="<?= htmlspecialchars($course['title']); ?>">
                                <?php if ($course['is_bestseller']): ?>
                                    <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2"><?php echo getLangText('Bestseller', 'លក់ដាច់បំផុត'); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="card-body d-flex flex-column pb-0">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="badge bg-danger-soft text-danger"><?= htmlspecialchars($course['category']); ?></span>
                                    <span class="badge bg-secondary-soft text-secondary"><?= htmlspecialchars($course['level']); ?></span>
                                </div>
                                <h5 class="card-title fw-bold">
                                    <a href="#" class="text-dark stretched-link text-decoration-none"><?= htmlspecialchars($course['title']); ?></a>
                                </h5>
                                <p class="card-text text-muted small mb-2"><?php echo getLangText('by', 'ដោយ'); ?> <?= htmlspecialchars($course['instructor_name']); ?></p>
                            </div>
                            <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="text-warning">
                                        <small class="text-dark me-1"><?= $course['rating']; ?></small>
                                        <?php for ($i = 1; $i <= 5; $i++): ?><i class="fas fa-star<?= $i <= round($course['rating']) ? '' : ' fa-regular'; ?>"></i><?php endfor; ?>
                                        <small class="text-muted">(<?= number_format($course['total_reviews']); ?>)</small>
                                    </div>
                                    <div class="d-flex gap-3 text-muted small">
                                        <span><i class="fas fa-clock me-1"></i> 8h</span>
                                        <span><i class="fas fa-users me-1"></i> <?= number_format($course['total_students'] ?? 0); ?></span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <?php if ($course['price'] > 0): ?>
                                        <span class="h5 text-danger fw-bold mb-0">$<?= number_format($course['price'], 2); ?>
                                            <?php if ($course['original_price'] > $course['price']): ?>
                                                <small class="text-muted text-decoration-line-through ms-1 fs-6">$<?= number_format($course['original_price'], 2); ?></small>
                                            <?php endif; ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="h5 text-success fw-bold mb-0"><?php echo getLangText('Free', 'ឥតគិតថ្លៃ'); ?></span>
                                    <?php endif; ?>
                                    <span class="learn-more-btn"><i class="fas fa-arrow-right"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if ($total_pages > 1): ?>
            <nav aria-label="Courses pagination" class="mt-5">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item"><a class="page-link" href="?page=program/online-learning&p=<?= $page - 1; ?>&<?= http_build_query(array_filter(['category' => $category, 'level' => $level, 'search' => $search, 'sort' => $sort])); ?>"><i class="fas fa-chevron-left"></i></a></li>
                    <?php endif; ?>

                    <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : ''; ?>"><a class="page-link" href="?page=program/online-learning&p=<?= $i; ?>&<?= http_build_query(array_filter(['category' => $category, 'level' => $level, 'search' => $search, 'sort' => $sort])); ?>"><?= $i; ?></a></li>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <li class="page-item"><a class="page-link" href="?page=program/online-learning&p=<?= $page + 1; ?>&<?= http_build_query(array_filter(['category' => $category, 'level' => $level, 'search' => $search, 'sort' => $sort])); ?>"><i class="fas fa-chevron-right"></i></a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>

<style>
/* ---------------------------------- */
/* Enhanced Styles                    */
/* ---------------------------------- */
body { background-color: #f8f9fa; }

/* Filter Bar Styling */
.filter-bar-wrapper { border-bottom: 1px solid #e9ecef; }
.filter-bar-wrapper .form-control,
.filter-bar-wrapper .form-select {
    border-radius: 0.5rem;
    border: 1px solid #ced4da;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}
.filter-bar-wrapper .form-control:focus,
.filter-bar-wrapper .form-select:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.2);
}
.search-btn {
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2);
}
.search-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(220, 53, 69, 0.3);
}


/* Custom soft badges */
.bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); }
.text-danger { color: #dc3545 !important; }
.bg-secondary-soft { background-color: rgba(108, 117, 125, 0.1); }
.text-secondary { color: #6c757d !important; }

/* Course Card Styling */
.course-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 0.75rem !important;
    overflow: hidden;
}
.course-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 28px rgba(0,0,0,0.12) !important;
}
.course-card .card-img-top { height: 180px; object-fit: cover; }
.course-card .card-title { font-size: 1.1rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 48px; }
.course-card .stretched-link::after { content: ""; position: absolute; top: 0; right: 0; bottom: 0; left: 0; z-index: 1; background-color: transparent; }
.learn-more-btn { display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 50%; background-color: #f1f1f1; color: #6c757d; transition: all 0.3s ease; }
.course-card:hover .learn-more-btn { background-color: #dc3545; color: #fff; transform: translateX(4px); }
.text-warning .fas, .text-warning .fa-regular { font-size: 0.85rem; }

/* Pagination Styling */
.pagination .page-item .page-link { color: #6c757d; border-radius: 50% !important; width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; margin: 0 5px; border: none; }
.pagination .page-item.active .page-link { background-color: #dc3545; color: white; box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3); }
.pagination .page-item .page-link:hover { background-color: #e9ecef; }
.pagination .page-item.active .page-link:hover { background-color: #c82333; }
</style>
