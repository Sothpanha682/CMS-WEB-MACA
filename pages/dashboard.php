<?php
// Check if user is logged in
if (!isLoggedIn()) {
    $_SESSION['message'] = "You must be logged in to access this page.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=login');
    exit;
}

// Override the header and footer includes
ob_clean(); // Clear the output buffer to prevent the default header from being displayed
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MACA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Custom Admin Header -->
    <header class="bg-white shadow-sm mb-4">
        <div class="container-fluid">
            <div class="row py-3">
                <div class="col-md-6">
                    <h1 class="h3 text-danger mb-0">MACA Admin Dashboard</h1>
                </div>
                <div class="col-md-6 text-end">
                    <div class="d-flex justify-content-end align-items-center">
                        <span class="me-3">Welcome, <?php echo $_SESSION['username']; ?></span>
                        <a href="includes/logout.php" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-fluid py-4">
        <div class="row">
            <!-- Left Sidebar Menu -->
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">Admin Dashboard</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="#announcements-section" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-bullhorn me-2"></i> Announcements
                        </a>
                        <a href="#news-section" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-newspaper me-2"></i> News Articles
                        </a>
                        <a href="#messages-section" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-envelope me-2"></i> Messages
                            <?php 
                            $unread_count = countUnreadMessages();
                            if ($unread_count > 0): 
                            ?>
                            <span class="badge bg-warning rounded-pill ms-auto"><?php echo $unread_count; ?></span>
                            <?php endif; ?>
                        </a>
                        <a href="index.php?page=manage-team-members" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-users me-2"></i> Team Members
                        </a>
                        <a href="index.php?page=manage-site-settings" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-cog me-2"></i> Site Settings
                        </a>
                        <a href="index.php?page=manage-users" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-user-shield me-2"></i> Admin Management
                        </a>
                        <a href="index.php?page=manage-media" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-images me-2"></i> Media Library
                        </a>
                        
                        <!-- New Content Management links -->
                        <div class="dropdown-divider"></div>
                        <div class="list-group-item bg-light text-dark">
                            <strong>Content Management</strong>
                        </div>
                        <a href="index.php?page=manage-popular-career" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-briefcase me-2"></i> Popular Career
                        </a>
                        <a href="index.php?page=manage-popular-majors" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-graduation-cap me-2"></i> Popular Majors
                        </a>
                        
                        <a href="index.php?page=manage-talkshow" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-video me-2"></i> Manage Talkshow
                        </a>
                        <a href="index.php?page=manage-roadshow" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-map-marked-alt me-2"></i> Manage Roadshow
                        </a>
                        <a href="index.php?page=manage-intern-news" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-user-graduate me-2"></i> Manage Intern News
                        </a>
                        <a href="index.php?page=manage-online-courses" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-laptop me-2"></i> Online Courses
                        </a>
                        <a href="index.php?page=manage-recruitment-applications" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-briefcase me-2"></i> Job Applications
                            <?php 
                            try {
                                $stmt = $pdo->query("SELECT COUNT(*) FROM job_applications WHERE status = 'pending'");
                                $pending_applications = $stmt->fetchColumn();
                                if ($pending_applications > 0): 
                            ?>
                            <span class="badge bg-warning rounded-pill ms-auto"><?php echo $pending_applications; ?></span>
                            <?php endif; } catch(PDOException $e) { } ?>
                        </a>
                        
                        <div class="dropdown-divider"></div>
                        <a href="includes/logout.php" class="list-group-item list-group-item-action d-flex align-items-center text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </div>
                </div>
                
                <!-- Admin Profile Card -->
                <div class="card mt-4 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-0"><?php echo $_SESSION['username']; ?></h5>
                                <p class="text-muted mb-0">Administrator</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9">
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['message']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
                <?php endif; ?>

                
                <!-- Dashboard Overview -->

                <div class="row mb-4">
                    <div class="col-md-3 mb-4">
                        <div class="card bg-danger text-white h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Announcements</h6>
                                        <?php
                                        try {
                                            $stmt = $pdo->query("SELECT COUNT(*) FROM announcements");
                                            $announcement_count = $stmt->fetchColumn();
                                            echo '<h2 class="mb-0">' . $announcement_count . '</h2>';
                                        } catch(PDOException $e) {
                                            echo '<h2 class="mb-0">0</h2>';
                                        }
                                        ?>
                                    </div>
                                    <div class="bg-white rounded-circle p-3 text-danger">
                                        <i class="fas fa-bullhorn fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card bg-primary text-white h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">News Articles</h6>
                                        <?php
                                        try {
                                            $stmt = $pdo->query("SELECT COUNT(*) FROM news");
                                            $news_count = $stmt->fetchColumn();
                                            echo '<h2 class="mb-0">' . $news_count . '</h2>';
                                        } catch(PDOException $e) {
                                            echo '<h2 class="mb-0">0</h2>';
                                        }
                                        ?>
                                    </div>
                                    <div class="bg-white rounded-circle p-3 text-primary">
                                        <i class="fas fa-newspaper fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card bg-success text-white h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Team Members</h6>
                                        <?php
                                        try {
                                            $stmt = $pdo->query("SELECT COUNT(*) FROM team_members");
                                            $team_count = $stmt->fetchColumn();
                                            echo '<h2 class="mb-0">' . $team_count . '</h2>';
                                        } catch(PDOException $e) {
                                            echo '<h2 class="mb-0">0</h2>';
                                        }
                                        ?>
                                    </div>
                                    <div class="bg-white rounded-circle p-3 text-success">
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card bg-info text-white h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Online Courses</h6>
                                        <?php
                                        try {
                                            $stmt = $pdo->query("SELECT COUNT(*) FROM online_courses");
                                            $courses_count = $stmt->fetchColumn();
                                            echo '<h2 class="mb-0">' . $courses_count . '</h2>';
                                        } catch(PDOException $e) {
                                            echo '<h2 class="mb-0">0</h2>';
                                        }
                                        ?>
                                    </div>
                                    <div class="bg-white rounded-circle p-3 text-info">
                                        <i class="fas fa-laptop fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>




 <div class="col-md-3 mb-4">
                    <div class="card bg-warning text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Unread Messages</h6>
                                    <?php
                                    try {
                                        $unread_count = countUnreadMessages();
                                        echo '<h2 class="mb-0">' . $unread_count . '</h2>';
                                    } catch(PDOException $e) {
                                        echo '<h2 class="mb-0">0</h2>';
                                    }
                                    ?>
                                </div>
                                <div class="bg-white rounded-circle p-3 text-warning">
                                    <i class="fas fa-envelope fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card bg-secondary text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Intern News</h6>
                                    <?php
                                    try {
                                        $stmt = $pdo->query("SELECT COUNT(*) FROM intern_news");
                                        $intern_news_count = $stmt->fetchColumn();
                                        echo '<h2 class="mb-0">' . $intern_news_count . '</h2>';
                                    } catch(PDOException $e) {
                                        echo '<h2 class="mb-0">0</h2>';
                                    }
                                    ?>
                                </div>
                                <div class="bg-white rounded-circle p-3 text-secondary">
                                    <i class="fas fa-user-graduate fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card bg-info text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Job Applications</h6>
                                    <?php
                                    try {
                                        $stmt = $pdo->query("SELECT COUNT(*) FROM job_applications");
                                        $applications_count = $stmt->fetchColumn();
                                        echo '<h2 class="mb-0">' . $applications_count . '</h2>';
                                    } catch(PDOException $e) {
                                        echo '<h2 class="mb-0">0</h2>';
                                    }
                                    ?>
                                </div>
                                <div class="bg-white rounded-circle p-3 text-info">
                                    <i class="fas fa-briefcase fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                
                <!-- Quick Links -->
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Quick Links</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="index.php?page=manage-online-courses" class="btn btn-outline-secondary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                    <i class="fas fa-laptop fa-2x mb-2"></i>
                                    Online Courses
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="index.php?page=manage-intern-news" class="btn btn-outline-secondary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                    <i class="fas fa-user-graduate fa-2x mb-2"></i>
                                    Intern News
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="index.php?page=manage-media" class="btn btn-outline-secondary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                    <i class="fas fa-images fa-2x mb-2"></i>
                                    Media Library
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="index.php?page=manage-site-settings" class="btn btn-outline-secondary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                    <i class="fas fa-cog fa-2x mb-2"></i>
                                    Site Settings
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="#messages-section" class="btn btn-outline-secondary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                    <i class="fas fa-envelope fa-2x mb-2"></i>
                                    Messages
                                    <?php if ($unread_count > 0): ?>
                                    <span class="badge bg-warning rounded-pill"><?php echo $unread_count; ?></span>
                                    <?php endif; ?>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="index.php?page=manage-recruitment-applications" class="btn btn-outline-secondary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                    <i class="fas fa-briefcase fa-2x mb-2"></i>
                                    Job Applications
                                    <?php 
                                    try {
                                        $stmt = $pdo->query("SELECT COUNT(*) FROM job_applications WHERE status = 'pending'");
                                        $pending_count = $stmt->fetchColumn();
                                        if ($pending_count > 0): 
                                    ?>
                                    <span class="badge bg-warning rounded-pill"><?php echo $pending_count; ?></span>
                                    <?php endif; } catch(PDOException $e) { } ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                

                <!-- Announcements Section -->
                <div class="card shadow-sm mb-4" id="announcements-section">
                    <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Announcements</h5>
                        <a href="index.php?page=add-announcement" class="btn btn-sm btn-light">
                            <i class="fas fa-plus me-1"></i> Add New
                        </a>
                    </div>
                    <div class="card-body">
                        <?php
                        try {
                            $stmt = $pdo->query("SELECT * FROM announcements ORDER BY created_at DESC LIMIT 5");
                            $announcements = $stmt->fetchAll();
                            
                            if (count($announcements) > 0):
                        ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($announcements as $announcement): ?>
                                    <tr>
                                        <td><?php echo $announcement['title']; ?></td>
                                        <td>
                                            <?php if ($announcement['is_active']): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo formatDate($announcement['created_at']); ?></td>
                                        <td>
                                            <a href="index.php?page=edit-announcement&id=<?php echo $announcement['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <a href="actions/delete-announcement.php?id=<?php echo $announcement['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this announcement?')">Delete</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                            <div class="alert alert-info">No announcements found. Click "Add New" to create your first announcement.</div>
                        <?php 
                            endif;
                        } catch(PDOException $e) {
                            echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
                        }
                        ?>
                    </div>
                </div>

                <!-- News Section -->
                <div class="card shadow-sm mb-4" id="news-section">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">News Articles</h5>
                        <a href="index.php?page=add-news" class="btn btn-sm btn-light">
                            <i class="fas fa-plus me-1"></i> Add New
                        </a>
                    </div>
                    <div class="card-body">
                        <?php
                        try {
                            $stmt = $pdo->query("SELECT * FROM news ORDER BY created_at DESC LIMIT 5");
                            $news_items = $stmt->fetchAll();
                            
                            if (count($news_items) > 0):
                        ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($news_items as $news): ?>
                                    <tr>
                                        <td><?php echo $news['title']; ?></td>
                                        <td>
                                            <?php if ($news['is_active']): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo formatDate($news['created_at']); ?></td>
                                        <td>
                                            <a href="index.php?page=edit-news&id=<?php echo $news['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <a href="actions/delete-news.php?id=<?php echo $news['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this news article?')">Delete</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                            <div class="alert alert-info">No news articles found. Click "Add New" to create your first news article.</div>
                        <?php 
                            endif;
                        } catch(PDOException $e) {
                            echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
                        }
                        ?>
                    </div>
                </div>

                <!-- Messages Section -->
                <div class="card shadow-sm mb-4" id="messages-section">
                    <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Contact Messages</h5>
                        <a href="index.php?page=manage-messages" class="btn btn-sm btn-light">
                            <i class="fas fa-envelope-open me-1"></i> View All
                        </a>
                    </div>
                    <div class="card-body">
                        <?php
                        try {
                            $messages = getContactMessages(5);
                            
                            if (count($messages) > 0):
                        ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Subject</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($messages as $msg): ?>
                                    <tr class="<?php echo $msg['is_read'] ? '' : 'table-warning'; ?>">
                                        <td><?php echo $msg['name']; ?></td>
                                        <td><?php echo $msg['subject']; ?></td>
                                        <td><?php echo formatDate($msg['created_at']); ?></td>
                                        <td>
                                            <?php if ($msg['is_read']): ?>
                                                <span class="badge bg-secondary">Read</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Unread</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary view-message" 
                                                    data-bs-toggle="modal" data-bs-target="#messageModal<?php echo $msg['id']; ?>">
                                                View
                                            </button>
                                            <a href="index.php?action=delete-message&id=<?php echo $msg['id']; ?>" 
                                               class="btn btn-sm btn-outline-danger" 
                                               onclick="return confirm('Are you sure you want to delete this message?')">
                                                Delete
                                            </a>
                                            
                                            <!-- Message Modal -->
                                            <div class="modal fade" id="messageModal<?php echo $msg['id']; ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"><?php echo $msg['subject']; ?></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <strong>From:</strong> <?php echo $msg['name']; ?> (<?php echo $msg['email']; ?>)
                                                            </div>
                                                            <div class="mb-3">
                                                                <strong>Date:</strong> <?php echo formatDate($msg['created_at'], 'M d, Y h:i A'); ?>
                                                            </div>
                                                            <div class="mb-3">
                                                                <strong>Message:</strong>
                                                                <p class="mt-2"><?php echo nl2br($msg['message']); ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a href="index.php?action=mark-message-read&id=<?php echo $msg['id']; ?>" class="btn btn-success">
                                                                Mark as Read
                                                            </a>
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
                        <?php else: ?>
                            <div class="alert alert-info">No messages found.</div>
                        <?php 
                            endif;
                        } catch(PDOException $e) {
                            echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
                        }
                        ?>
                    </div>
                </div>
                
               
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- CKEditor 4 - Free Version -->
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    
    <script src="assets/js/script.js"></script>
</body>
</html>
<?php
// Prevent the footer from being included
exit;
?>
