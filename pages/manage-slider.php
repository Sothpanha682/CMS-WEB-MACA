<?php
// Check if user is logged in and is admin
if (!isLoggedIn()) {
    header('Location: index.php?page=login');
    exit;
}

// Include database connection
require_once 'config/database.php';

// Get language preference
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';

// Check if slider_images table exists
$stmt = $pdo->prepare("SHOW TABLES LIKE 'slider_images'");
$stmt->execute();
$tableExists = $stmt->rowCount() > 0;

// Create the table if it doesn't exist
if (!$tableExists) {
    $sql = file_get_contents('sql/create_slider_images_table.sql');
    $pdo->exec($sql);
}

// Fetch all slider images
$stmt = $pdo->prepare("SELECT * FROM slider_images ORDER BY display_order ASC");
$stmt->execute();
$sliderImages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM slider_images WHERE id = ?");
    $stmt->execute([$id]);
    
    $_SESSION['message'] = "Slider image deleted successfully.";
    $_SESSION['message_type'] = "success";
    
    header('Location: index.php?page=manage-slider');
    exit;
}

// Handle toggle active status
if (isset($_GET['action']) && $_GET['action'] == 'toggle-status' && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Get current status
    $stmt = $pdo->prepare("SELECT active FROM slider_images WHERE id = ?");
    $stmt->execute([$id]);
    $slider = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Toggle status
    $newStatus = $slider['active'] ? 0 : 1;
    $stmt = $pdo->prepare("UPDATE slider_images SET active = ? WHERE id = ?");
    $stmt->execute([$newStatus, $id]);
    
    $_SESSION['message'] = "Slider status updated successfully.";
    $_SESSION['message_type'] = "success";
    
    header('Location: index.php?page=manage-slider');
    exit;
}

// Handle reorder
if (isset($_POST['action']) && $_POST['action'] == 'reorder') {
    $ids = $_POST['ids'];
    
    foreach ($ids as $index => $id) {
        $stmt = $pdo->prepare("UPDATE slider_images SET display_order = ? WHERE id = ?");
        $stmt->execute([$index, $id]);
    }
    
    echo json_encode(['success' => true]);
    exit;
}

// Include admin header
include 'includes/admin-header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800 <?= $lang === 'kh' ? 'khmer-text' : '' ?>">
            <?= $lang === 'en' ? 'Manage Slider Images' : 'គ្រប់គ្រងរូបភាពស្លាយ' ?>
        </h1>
        <a href="index.php?page=add-slider" class="btn btn-primary <?= $lang === 'kh' ? 'khmer-text' : '' ?>">
            <i class="fas fa-plus-circle"></i> <?= $lang === 'en' ? 'Add New Slider' : 'បន្ថែមស្លាយថ្មី' ?>
        </a>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show" role="alert">
            <?= $_SESSION['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary <?= $lang === 'kh' ? 'khmer-text' : '' ?>">
                <?= $lang === 'en' ? 'Slider Images' : 'រូបភាពស្លាយ' ?>
            </h6>
            <div>
                <button id="saveOrder" class="btn btn-sm btn-success d-none <?= $lang === 'kh' ? 'khmer-text' : '' ?>">
                    <i class="fas fa-save"></i> <?= $lang === 'en' ? 'Save Order' : 'រក្សាទុកលំដាប់' ?>
                </button>
            </div>
        </div>
        <div class="card-body">
            <?php if (count($sliderImages) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%"><?= $lang === 'en' ? 'Image' : 'រូបភាព' ?></th>
                                <th width="20%"><?= $lang === 'en' ? 'Title' : 'ចំណងជើង' ?></th>
                                <th width="30%"><?= $lang === 'en' ? 'Description' : 'ការពិពណ៌នា' ?></th>
                                <th width="10%"><?= $lang === 'en' ? 'Status' : 'ស្ថានភាព' ?></th>
                                <th width="20%"><?= $lang === 'en' ? 'Actions' : 'សកម្មភាព' ?></th>
                            </tr>
                        </thead>
                        <tbody id="sortable-list">
                            <?php foreach ($sliderImages as $index => $slider): ?>
                                <tr data-id="<?= $slider['id'] ?>">
                                    <td class="handle text-center">
                                        <i class="fas fa-grip-vertical text-muted"></i>
                                    </td>
                                    <td>
                                        <img src="<?= $slider['image_url'] ?>" alt="Slider Image" class="img-thumbnail" style="max-height: 80px;">
                                    </td>
                                    <td><?= htmlspecialchars($slider["title_$lang"]) ?></td>
                                    <td><?= substr(htmlspecialchars($slider["description_$lang"]), 0, 100) ?>...</td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $slider['active'] ? 'success' : 'danger' ?>">
                                            <?= $slider['active'] ? ($lang === 'en' ? 'Active' : 'សកម្ម') : ($lang === 'en' ? 'Inactive' : 'អសកម្ម') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="index.php?page=edit-slider&id=<?= $slider['id'] ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> <?= $lang === 'en' ? 'Edit' : 'កែប្រែ' ?>
                                        </a>
                                        <a href="index.php?page=manage-slider&action=toggle-status&id=<?= $slider['id'] ?>" class="btn btn-sm btn-<?= $slider['active'] ? 'warning' : 'success' ?>">
                                            <i class="fas fa-<?= $slider['active'] ? 'eye-slash' : 'eye' ?>"></i> 
                                            <?= $slider['active'] ? ($lang === 'en' ? 'Deactivate' : 'បិទដំណើរការ') : ($lang === 'en' ? 'Activate' : 'បើកដំណើរការ') ?>
                                        </a>
                                        <a href="index.php?page=manage-slider&action=delete&id=<?= $slider['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('<?= $lang === 'en' ? 'Are you sure you want to delete this slider?' : 'តើអ្នកប្រាកដថាចង់លុបស្លាយនេះមែនទេ?' ?>')">
                                            <i class="fas fa-trash"></i> <?= $lang === 'en' ? 'Delete' : 'លុប' ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info <?= $lang === 'kh' ? 'khmer-text' : '' ?>">
                    <?= $lang === 'en' ? 'No slider images found. Please add some.' : 'រកមិនឃើញរូបភាពស្លាយទេ។ សូមបន្ថែមខ្លះ។' ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/admin-footer.php'; ?>

<!-- jQuery UI for sortable functionality -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
$(document).ready(function() {
    // Make the table rows sortable
    $("#sortable-list").sortable({
        handle: ".handle",
        update: function() {
            $("#saveOrder").removeClass("d-none");
        }
    });
    
    // Save the new order
    $("#saveOrder").click(function() {
        var ids = [];
        $("#sortable-list tr").each(function() {
            ids.push($(this).data("id"));
        });
        
        $.ajax({
            url: "index.php?page=manage-slider",
            method: "POST",
            data: {
                action: "reorder",
                ids: ids
            },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    $("#saveOrder").addClass("d-none");
                    alert("<?= $lang === 'en' ? 'Order saved successfully!' : 'បានរក្សាទុកលំដាប់ដោយជោគជ័យ!' ?>");
                }
            }
        });
    });
});
</script>

<style>
.handle {
    cursor: move;
}
</style>
