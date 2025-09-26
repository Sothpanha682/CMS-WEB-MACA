<?php
// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    $_SESSION['message'] = "You must be logged in as an administrator to access this page.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=login');
    exit;
}

// Handle form actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_form':
                $title = sanitize($_POST['title']);
                $description = sanitize($_POST['description']);
                $display_order = (int)$_POST['display_order'];
                
                // Handle file upload
                if (isset($_FILES['form_file']) && $_FILES['form_file']['error'] === 0) {
                    $upload_result = uploadFile($_FILES['form_file'], 'uploads/forms/', ['pdf', 'doc', 'docx'], 10485760); // 10MB max
                    
                    if ($upload_result['status']) {
                        try {
                            $stmt = $pdo->prepare("INSERT INTO career_counselling_forms (title, description, file_path, file_name, file_size, display_order) VALUES (?, ?, ?, ?, ?, ?)");
                            $stmt->execute([
                                $title,
                                $description,
                                $upload_result['path'],
                                $_FILES['form_file']['name'],
                                $_FILES['form_file']['size'],
                                $display_order
                            ]);
                            
                            $_SESSION['message'] = "Form uploaded successfully!";
                            $_SESSION['message_type'] = "success";
                        } catch (PDOException $e) {
                            $_SESSION['message'] = "Error saving form: " . $e->getMessage();
                            $_SESSION['message_type'] = "danger";
                        }
                    } else {
                        $_SESSION['message'] = $upload_result['message'];
                        $_SESSION['message_type'] = "danger";
                    }
                } else {
                    $_SESSION['message'] = "Please select a file to upload.";
                    $_SESSION['message_type'] = "danger";
                }
                break;
                
            case 'update_form':
                $id = (int)$_POST['form_id'];
                $title = sanitize($_POST['title']);
                $description = sanitize($_POST['description']);
                $display_order = (int)$_POST['display_order'];
                $is_active = isset($_POST['is_active']) ? 1 : 0;
                
                try {
                    // Check if new file is uploaded
                    if (isset($_FILES['form_file']) && $_FILES['form_file']['error'] === 0) {
                        // Get old file path to delete it
                        $stmt = $pdo->prepare("SELECT file_path FROM career_counselling_forms WHERE id = ?");
                        $stmt->execute([$id]);
                        $old_form = $stmt->fetch();
                        
                        $upload_result = uploadFile($_FILES['form_file'], 'uploads/forms/', ['pdf', 'doc', 'docx'], 10485760);
                        
                        if ($upload_result['status']) {
                            // Delete old file
                            if ($old_form && file_exists($old_form['file_path'])) {
                                unlink($old_form['file_path']);
                            }
                            
                            $stmt = $pdo->prepare("UPDATE career_counselling_forms SET title = ?, description = ?, file_path = ?, file_name = ?, file_size = ?, display_order = ?, is_active = ? WHERE id = ?");
                            $stmt->execute([
                                $title,
                                $description,
                                $upload_result['path'],
                                $_FILES['form_file']['name'],
                                $_FILES['form_file']['size'],
                                $display_order,
                                $is_active,
                                $id
                            ]);
                        } else {
                            $_SESSION['message'] = $upload_result['message'];
                            $_SESSION['message_type'] = "danger";
                            break;
                        }
                    } else {
                        // Update without changing file
                        $stmt = $pdo->prepare("UPDATE career_counselling_forms SET title = ?, description = ?, display_order = ?, is_active = ? WHERE id = ?");
                        $stmt->execute([$title, $description, $display_order, $is_active, $id]);
                    }
                    
                    $_SESSION['message'] = "Form updated successfully!";
                    $_SESSION['message_type'] = "success";
                } catch (PDOException $e) {
                    $_SESSION['message'] = "Error updating form: " . $e->getMessage();
                    $_SESSION['message_type'] = "danger";
                }
                break;
                
            case 'upload_image':
                $form_id = (int)$_POST['form_id'];
                
                // Handle image upload
                if (isset($_FILES['form_image']) && $_FILES['form_image']['error'] === 0) {
                    $upload_result = uploadFile($_FILES['form_image'], 'uploads/forms/images/', ['jpg', 'jpeg', 'png', 'gif'], 5242880); // 5MB max
                    
                    if ($upload_result['status']) {
                        try {
                            // Get old image path to delete it
                            $stmt = $pdo->prepare("SELECT image_path FROM career_counselling_forms WHERE id = ?");
                            $stmt->execute([$form_id]);
                            $old_form = $stmt->fetch();
                            
                            // Delete old image if exists
                            if ($old_form && $old_form['image_path'] && file_exists($old_form['image_path'])) {
                                unlink($old_form['image_path']);
                            }
                            
                            $stmt = $pdo->prepare("UPDATE career_counselling_forms SET image_path = ? WHERE id = ?");
                            $stmt->execute([$upload_result['path'], $form_id]);
                            
                            $_SESSION['message'] = "Image uploaded successfully!";
                            $_SESSION['message_type'] = "success";
                        } catch (PDOException $e) {
                            $_SESSION['message'] = "Error saving image: " . $e->getMessage();
                            $_SESSION['message_type'] = "danger";
                        }
                    } else {
                        $_SESSION['message'] = $upload_result['message'];
                        $_SESSION['message_type'] = "danger";
                    }
                } else {
                    $_SESSION['message'] = "Please select an image to upload.";
                    $_SESSION['message_type'] = "danger";
                }
                break;
        }
        
        header('Location: index.php?page=manage-career-counselling-forms');
        exit;
    }
}

// Get all forms
try {
    $stmt = $pdo->query("SELECT * FROM career_counselling_forms ORDER BY display_order ASC, created_at DESC");
    $forms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $forms = [];
    $error_message = "Error fetching forms: " . $e->getMessage();
}
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 text-danger">Manage Career Counselling Forms</h1>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addFormModal">
                    <i class="fas fa-plus me-2"></i>Add New Form
                </button>
            </div>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <?php if (!empty($forms)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>File</th>
                                        <th>Order</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($forms as $form): ?>
                                        <tr>
                                            <td>
                                                <?php if (!empty($form['image_path']) && file_exists($form['image_path'])): ?>
                                                    <img src="<?php echo htmlspecialchars($form['image_path']); ?>" 
                                                         alt="Form Image" 
                                                         class="img-thumbnail" 
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                <?php else: ?>
                                                    <img src="https://via.placeholder.com/60x60?text=No+Image" 
                                                         alt="No Image" 
                                                         class="img-thumbnail" 
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                <?php endif; ?>
                                                <button type="button" class="btn btn-sm btn-outline-secondary mt-1 d-block" 
                                                        onclick="uploadImage(<?php echo $form['id']; ?>)">
                                                    <i class="fas fa-camera"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($form['title']); ?></strong>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars(truncateText($form['description'], 50)); ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo htmlspecialchars($form['file_path']); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-download me-1"></i>Download
                                                </a>
                                                <small class="d-block text-muted mt-1">
                                                    <?php echo htmlspecialchars($form['file_name']); ?>
                                                    (<?php echo number_format($form['file_size'] / 1024, 1); ?> KB)
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary"><?php echo $form['display_order']; ?></span>
                                            </td>
                                            <td>
                                                <?php if ($form['is_active']): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php echo formatDate($form['created_at']); ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary me-1" 
                                                        onclick="editForm(<?php echo htmlspecialchars(json_encode($form)); ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="actions/delete-career-counselling-form.php?id=<?php echo $form['id']; ?>" 
                                                   class="btn btn-sm btn-outline-danger"
                                                   onclick="return confirm('Are you sure you want to delete this form?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-file-pdf fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No forms uploaded yet</h5>
                            <p class="text-muted">Click "Add New Form" to upload your first career counselling form.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Form Modal -->
<div class="modal fade" id="addFormModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Career Counselling Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_form">
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Form Title *</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="form_file" class="form-label">Form File (PDF, DOC, DOCX) *</label>
                        <input type="file" class="form-control" id="form_file" name="form_file" accept=".pdf,.doc,.docx" required>
                        <div class="form-text">Maximum file size: 10MB</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="display_order" class="form-label">Display Order</label>
                        <input type="number" class="form-control" id="display_order" name="display_order" value="0" min="0">
                        <div class="form-text">Lower numbers appear first</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Upload Form</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Form Modal -->
<div class="modal fade" id="editFormModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Career Counselling Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="update_form">
                    <input type="hidden" name="form_id" id="edit_form_id">
                    
                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Form Title *</label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_form_file" class="form-label">Form File (PDF, DOC, DOCX)</label>
                        <input type="file" class="form-control" id="edit_form_file" name="form_file" accept=".pdf,.doc,.docx">
                        <div class="form-text">Leave empty to keep current file. Maximum file size: 10MB</div>
                        <div id="current_file_info" class="mt-2"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_display_order" class="form-label">Display Order</label>
                        <input type="number" class="form-control" id="edit_display_order" name="display_order" min="0">
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active">
                            <label class="form-check-label" for="edit_is_active">
                                Active (visible on website)
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Update Form</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upload Image Modal -->
<div class="modal fade" id="uploadImageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Form Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="upload_image">
                    <input type="hidden" name="form_id" id="image_form_id">
                    
                    <div class="mb-3">
                        <label for="form_image" class="form-label">Form Image (JPG, PNG, GIF) *</label>
                        <input type="file" class="form-control" id="form_image" name="form_image" accept=".jpg,.jpeg,.png,.gif" required>
                        <div class="form-text">Maximum file size: 5MB. Recommended size: 600x400 pixels</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            This image will be displayed on the Career Counselling page to represent this form.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Upload Image</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editForm(form) {
    document.getElementById('edit_form_id').value = form.id;
    document.getElementById('edit_title').value = form.title;
    document.getElementById('edit_description').value = form.description || '';
    document.getElementById('edit_display_order').value = form.display_order;
    document.getElementById('edit_is_active').checked = form.is_active == 1;
    
    // Show current file info
    const fileInfo = document.getElementById('current_file_info');
    fileInfo.innerHTML = `
        <div class="alert alert-info">
            <strong>Current file:</strong> ${form.file_name}<br>
            <strong>Size:</strong> ${(form.file_size / 1024).toFixed(1)} KB<br>
            <a href="${form.file_path}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                <i class="fas fa-download me-1"></i>Download Current File
            </a>
        </div>
    `;
    
    const editModal = new bootstrap.Modal(document.getElementById('editFormModal'));
    editModal.show();
}

function uploadImage(formId) {
    document.getElementById('image_form_id').value = formId;
    const uploadModal = new bootstrap.Modal(document.getElementById('uploadImageModal'));
    uploadModal.show();
}
</script>
