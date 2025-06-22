<?php
// Check if user is logged in
if (!isLoggedIn()) {
    $_SESSION['message'] = "You must be logged in to access this page.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=login');
    exit;
}

// Handle actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    switch ($action) {
        case 'mark-read':
            if ($id > 0) {
                try {
                    $stmt = $pdo->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = :id");
                    $stmt->bindParam(':id', $id);
                    $stmt->execute();
                    $_SESSION['message'] = "Message marked as read.";
                    $_SESSION['message_type'] = "success";
                } catch(PDOException $e) {
                    $_SESSION['message'] = "Error updating message.";
                    $_SESSION['message_type'] = "danger";
                }
            }
            break;
            
        case 'mark-unread':
            if ($id > 0) {
                try {
                    $stmt = $pdo->prepare("UPDATE contact_messages SET is_read = 0 WHERE id = :id");
                    $stmt->bindParam(':id', $id);
                    $stmt->execute();
                    $_SESSION['message'] = "Message marked as unread.";
                    $_SESSION['message_type'] = "success";
                } catch(PDOException $e) {
                    $_SESSION['message'] = "Error updating message.";
                    $_SESSION['message_type'] = "danger";
                }
            }
            break;
            
        case 'delete':
            if ($id > 0) {
                try {
                    $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = :id");
                    $stmt->bindParam(':id', $id);
                    $stmt->execute();
                    $_SESSION['message'] = "Message deleted successfully.";
                    $_SESSION['message_type'] = "success";
                } catch(PDOException $e) {
                    $_SESSION['message'] = "Error deleting message.";
                    $_SESSION['message_type'] = "danger";
                }
            }
            break;
    }
    
    // Redirect to avoid resubmission
    header('Location: index.php?page=manage-messages');
    exit;
}

// Pagination
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Filter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build query
$where_conditions = [];
$params = [];

if ($filter == 'unread') {
    $where_conditions[] = "is_read = 0";
} elseif ($filter == 'read') {
    $where_conditions[] = "is_read = 1";
}

if (!empty($search)) {
    $where_conditions[] = "(name LIKE :search OR email LIKE :search OR subject LIKE :search OR message LIKE :search)";
    $params[':search'] = '%' . $search . '%';
}

$where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// Get total count
try {
    $count_sql = "SELECT COUNT(*) FROM contact_messages $where_clause";
    $count_stmt = $pdo->prepare($count_sql);
    foreach ($params as $key => $value) {
        $count_stmt->bindValue($key, $value);
    }
    $count_stmt->execute();
    $total_messages = $count_stmt->fetchColumn();
    $total_pages = ceil($total_messages / $limit);
} catch(PDOException $e) {
    $total_messages = 0;
    $total_pages = 0;
}

// Get messages
try {
    $sql = "SELECT * FROM contact_messages $where_clause ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $messages = [];
}
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-danger mb-0">Manage Contact Messages</h1>
        <a href="index.php?page=dashboard" class="btn btn-outline-danger">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
    <?php endif; ?>

    <!-- Filters and Search -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <input type="hidden" name="page" value="manage-messages">
                
                <div class="col-md-3">
                    <label for="filter" class="form-label">Filter by Status</label>
                    <select class="form-select" id="filter" name="filter">
                        <option value="all" <?php echo $filter == 'all' ? 'selected' : ''; ?>>All Messages</option>
                        <option value="unread" <?php echo $filter == 'unread' ? 'selected' : ''; ?>>Unread Only</option>
                        <option value="read" <?php echo $filter == 'read' ? 'selected' : ''; ?>>Read Only</option>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label for="search" class="form-label">Search Messages</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="<?php echo htmlspecialchars($search); ?>" 
                           placeholder="Search by name, email, subject, or message content...">
                </div>
                
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-danger me-2">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                    <a href="index.php?page=manage-messages" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Messages Summary -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Total Messages</h6>
                            <h2 class="mb-0"><?php echo $total_messages; ?></h2>
                        </div>
                        <i class="fas fa-envelope fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Unread Messages</h6>
                            <h2 class="mb-0"><?php echo countUnreadMessages(); ?></h2>
                        </div>
                        <i class="fas fa-envelope-open fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Read Messages</h6>
                            <?php
                            try {
                                $read_count = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE is_read = 1")->fetchColumn();
                                echo '<h2 class="mb-0">' . $read_count . '</h2>';
                            } catch(PDOException $e) {
                                echo '<h2 class="mb-0">0</h2>';
                            }
                            ?>
                        </div>
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Contact Messages</h5>
        </div>
        <div class="card-body">
            <?php if (count($messages) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $msg): ?>
                            <tr class="<?php echo $msg['is_read'] ? '' : 'table-warning'; ?>">
                                <td><?php echo htmlspecialchars($msg['name']); ?></td>
                                <td><?php echo htmlspecialchars($msg['email']); ?></td>
                                <td><?php echo htmlspecialchars(truncateText($msg['subject'], 50)); ?></td>
                                <td><?php echo formatDate($msg['created_at'], 'M d, Y h:i A'); ?></td>
                                <td>
                                    <?php if ($msg['is_read']): ?>
                                        <span class="badge bg-success">Read</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Unread</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="modal" data-bs-target="#messageModal<?php echo $msg['id']; ?>">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                        
                                        <?php if ($msg['is_read']): ?>
                                            <a href="index.php?page=manage-messages&action=mark-unread&id=<?php echo $msg['id']; ?>" 
                                               class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-envelope"></i> Mark Unread
                                            </a>
                                        <?php else: ?>
                                            <a href="index.php?page=manage-messages&action=mark-read&id=<?php echo $msg['id']; ?>" 
                                               class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-envelope-open"></i> Mark Read
                                            </a>
                                        <?php endif; ?>
                                        
                                        <a href="index.php?page=manage-messages&action=delete&id=<?php echo $msg['id']; ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('Are you sure you want to delete this message?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>

                                    <!-- Message Modal -->
                                    <div class="modal fade" id="messageModal<?php echo $msg['id']; ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"><?php echo htmlspecialchars($msg['subject']); ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <strong>From:</strong> <?php echo htmlspecialchars($msg['name']); ?>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>Email:</strong> <?php echo htmlspecialchars($msg['email']); ?>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <strong>Date:</strong> <?php echo formatDate($msg['created_at'], 'M d, Y h:i A'); ?>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>Status:</strong> 
                                                            <?php if ($msg['is_read']): ?>
                                                                <span class="badge bg-success">Read</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-warning">Unread</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <strong>Message:</strong>
                                                        <div class="mt-2 p-3 bg-light rounded">
                                                            <?php echo nl2br(htmlspecialchars($msg['message'])); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <?php if (!$msg['is_read']): ?>
                                                        <a href="index.php?page=manage-messages&action=mark-read&id=<?php echo $msg['id']; ?>" 
                                                           class="btn btn-success">
                                                            <i class="fas fa-envelope-open me-1"></i> Mark as Read
                                                        </a>
                                                    <?php endif; ?>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Messages pagination" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="index.php?page=manage-messages&p=<?php echo $page - 1; ?>&filter=<?php echo $filter; ?>&search=<?php echo urlencode($search); ?>">
                                        Previous
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="index.php?page=manage-messages&p=<?php echo $i; ?>&filter=<?php echo $filter; ?>&search=<?php echo urlencode($search); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="index.php?page=manage-messages&p=<?php echo $page + 1; ?>&filter=<?php echo $filter; ?>&search=<?php echo urlencode($search); ?>">
                                        Next
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>

            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <?php if (!empty($search) || $filter != 'all'): ?>
                        No messages found matching your search criteria.
                    <?php else: ?>
                        No contact messages found.
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
