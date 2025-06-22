<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is logged in
session_start();
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Initialize variables
$language = isset($_GET['language']) ? $_GET['language'] : (isset($_SESSION['language']) ? $_SESSION['language'] : 'en');
$news = [];

// Get news data
try {
    $conn = connectDB();
    
    $stmt = $conn->prepare("SELECT id, title, event_date, created_at FROM news WHERE language = ? ORDER BY created_at DESC");
    $stmt->bind_param("s", $language);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $news[] = $row;
    }
    
    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    // Handle error
    error_log('Database error: ' . $e->getMessage());
}

// Get available languages
$languages = getAvailableLanguages();

// Include header
$pageTitle = 'Manage News';
include_once '../includes/header.php';
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage News</h1>
        <a href="add-news.php" class="btn btn-primary">Add News</a>
    </div>
    
    <?php if (count($languages) > 1): ?>
    <div class="mb-4">
        <form method="get" class="d-flex">
            <label for="language" class="form-label me-2 pt-1">Language:</label>
            <select class="form-select me-2" id="language" name="language" style="width: auto;" onchange="this.form.submit()">
                <?php foreach ($languages as $code => $name): ?>
                    <option value="<?php echo $code; ?>" <?php echo $language === $code ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
    <?php endif; ?>
    
    <?php if (empty($news)): ?>
        <div class="alert alert-info">
            No news available for the selected language.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Event Date</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($news as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['title']); ?></td>
                            <td><?php echo date('Y-m-d', strtotime($item['event_date'])); ?></td>
                            <td><?php echo date('Y-m-d', strtotime($item['created_at'])); ?></td>
                            <td>
                                <a href="edit-news.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                <button type="button" class="btn btn-sm btn-danger" 
                                        onclick="confirmDelete(<?php echo $item['id']; ?>, '<?php echo addslashes($item['title']); ?>')">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
    function confirmDelete(id, title) {
        if (confirm('Are you sure you want to delete the news "' + title + '"?')) {
            window.location.href = '../actions/delete-news.php?id=' + id;
        }
    }
</script>

<?php include_once '../includes/footer.php'; ?>
