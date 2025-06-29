<?php
// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    $_SESSION['message'] = "Access denied. Admin privileges required.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=login');
    exit;
}

// Handle actions
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'toggle_status':
            if (isset($_GET['id'])) {
                $id = (int)$_GET['id'];
                try {
                    $stmt = $pdo->prepare("UPDATE online_courses SET is_active = NOT is_active WHERE id = ?");
                    $stmt->execute([$id]);
                    $_SESSION['message'] = "Course status updated successfully.";
                    $_SESSION['message_type'] = "success";
                } catch (PDOException $e) {
                    $_SESSION['message'] = "Error updating course status: " . $e->getMessage();
                    $_SESSION['message_type'] = "danger";
                }
            }
            header('Location: index.php?page=manage-online-courses');
            exit;
            break;
            
        case 'toggle_featured':
            if (isset($_GET['id'])) {
                $id = (int)$_GET['id'];
                try {
                    $stmt = $pdo->prepare("UPDATE online_courses SET is_featured = NOT is_featured WHERE id = ?");
                    $stmt->execute([$id]);
                    $_SESSION['message'] = "Featured status updated successfully.";
                    $_SESSION['message_type'] = "success";
                } catch (PDOException $e) {
                    $_SESSION['message'] = "Error updating featured status: " . $e->getMessage();
                    $_SESSION['message_type'] = "danger";
                }
            }
            header('Location: index.php?page=manage-online-courses');
            exit;
            break;
    }
}

// Pagination
$page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Get total count
try {
    $countStmt = $pdo->query("SELECT COUNT(*) FROM online_courses");
    $totalCourses = $countStmt->fetchColumn();
    $totalPages = ceil($totalCourses / $limit);
} catch (PDOException $e) {
    $totalCourses = 0;
    $totalPages = 1;
}

// Get courses
try {
    $stmt = $pdo->prepare("SELECT * FROM online_courses ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $courses = [];
    $_SESSION['message'] = "Error fetching courses: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 text-danger">Manage Online Courses</h1>
                <a href="index.php?page=add-online-course" class="btn btn-danger">
                    <i class="fas fa-plus me-2"></i>Add New Course
                </a>
            </div>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">All Courses (<?php echo $totalCourses; ?>)</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($courses)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No courses found</h5>
                            <p class="text-muted">Start by adding your first online course.</p>
                            <a href="index.php?page=add-online-course" class="btn btn-danger">Add Course</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Course</th>
                                        <th>Category</th>
                                        <th>Level</th>
                                        <th>Price</th>
                                        <th>Students</th>
                                        <th>Rating</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($courses as $course): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="<?php echo $course['course_image'] ? 'uploads/' . $course['course_image'] : 'https://via.placeholder.com/60x40?text=Course'; ?>" 
                                                         alt="<?php echo htmlspecialchars($course['title']); ?>" 
                                                         class="rounded me-3" style="width: 60px; height: 40px; object-fit: cover;">
                                                    <div>
                                                        <h6 class="mb-1"><?php echo htmlspecialchars($course['title']); ?></h6>
                                                        <small class="text-muted">by <?php echo htmlspecialchars($course['instructor_name']); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary"><?php echo htmlspecialchars($course['category']); ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary"><?php echo htmlspecialchars($course['level']); ?></span>
                                            </td>
                                            <td>
                                                <?php if ($course['price'] > 0): ?>
                                                    <strong>$<?php echo number_format($course['price'], 2); ?></strong>
                                                    <?php if ($course['original_price'] > $course['price']): ?>
                                                        <br><small class="text-muted text-decoration-line-through">$<?php echo number_format($course['original_price'], 2); ?></small>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="badge bg-success">Free</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo number_format($course['total_students']); ?></td>
                                            <td>
                                                <?php if ($course['rating'] > 0): ?>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-1"><?php echo $course['rating']; ?></span>
                                                        <div class="text-warning">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <i class="fas fa-star<?php echo $i <= $course['rating'] ? '' : '-o'; ?>"></i>
                                                            <?php endfor; ?>
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">(<?php echo number_format($course['total_reviews']); ?>)</small>
                                                <?php else: ?>
                                                    <span class="text-muted">No ratings</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column gap-1">
                                                    <?php if ($course['is_active']): ?>
                                                        <span class="badge bg-success">Active</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($course['is_featured']): ?>
                                                        <span class="badge bg-warning">Featured</span>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($course['is_bestseller']): ?>
                                                        <span class="badge bg-info">Bestseller</span>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($course['is_new']): ?>
                                                        <span class="badge bg-primary">New</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="index.php?page=edit-online-course&id=<?php echo $course['id']; ?>" 
                                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="index.php?page=manage-online-courses&action=toggle_status&id=<?php echo $course['id']; ?>" 
                                                       class="btn btn-sm btn-outline-<?php echo $course['is_active'] ? 'warning' : 'success'; ?>" 
                                                       title="<?php echo $course['is_active'] ? 'Deactivate' : 'Activate'; ?>">
                                                        <i class="fas fa-<?php echo $course['is_active'] ? 'eye-slash' : 'eye'; ?>"></i>
                                                    </a>
                                                    <a href="index.php?page=manage-online-courses&action=toggle_featured&id=<?php echo $course['id']; ?>" 
                                                       class="btn btn-sm btn-outline-<?php echo $course['is_featured'] ? 'warning' : 'info'; ?>" 
                                                       title="<?php echo $course['is_featured'] ? 'Remove from Featured' : 'Mark as Featured'; ?>">
                                                        <i class="fas fa-star"></i>
                                                    </a>
                                                    <a href="actions/delete-online-course.php?id=<?php echo $course['id']; ?>" 
                                                       class="btn btn-sm btn-outline-danger" 
                                                       onclick="return confirm('Are you sure you want to delete this course? This action cannot be undone.')" 
                                                       title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if ($totalPages > 1): ?>
                            <div class="card-footer">
                                <nav aria-label="Courses pagination">
                                    <ul class="pagination justify-content-center mb-0">
                                        <?php if ($page > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="index.php?page=manage-online-courses&p=<?php echo $page - 1; ?>">Previous</a>
                                            </li>
                                        <?php endif; ?>

                                        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                                <a class="page-link" href="index.php?page=manage-online-courses&p=<?php echo $i; ?>"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>

                                        <?php if ($page < $totalPages): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="index.php?page=manage-online-courses&p=<?php echo $page + 1; ?>">Next</a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
