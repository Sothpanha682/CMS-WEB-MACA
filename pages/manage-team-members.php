<?php
// Check if user is logged in
if (!isLoggedIn()) {
    $_SESSION['message'] = getLangText("You must be logged in to access this page.", "អ្នកត្រូវតែចូលគណនីដើម្បីចូលប្រើទំព័រនេះ។");
    $_SESSION['message_type'] = "danger";
    header('Location: index.php?page=login');
    exit;
}

// Handle delete action directly in this page
if (isset($_GET['delete']) && $_GET['delete'] > 0) {
    $member_id = (int)$_GET['delete'];
    
    try {
        // Get the team member to check if it has an image
        $stmt = $pdo->prepare("SELECT image_path FROM team_members WHERE id = :id");
        $stmt->bindParam(':id', $member_id);
        $stmt->execute();
        $member = $stmt->fetch();
        
        // Delete the image file if it exists
        if ($member && !empty($member['image_path']) && file_exists($member['image_path'])) {
            unlink($member['image_path']);
        }
        
        // Delete the team member from the database
        $stmt = $pdo->prepare("DELETE FROM team_members WHERE id = :id");
        $stmt->bindParam(':id', $member_id);
        $stmt->execute();
        
        $_SESSION['message'] = getLangText("Team member deleted successfully!", "សមាជិកក្រុមត្រូវបានលុបដោយជោគជ័យ!");
        $_SESSION['message_type'] = "success";
    } catch(PDOException $e) {
        $_SESSION['message'] = "Error deleting team member: " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }
    
    // Redirect to avoid resubmission
    header('Location: index.php?page=manage-team-members');
    exit;
}

// Clear form if requested
if (isset($_GET['clear']) || isset($_GET['add'])) {
    header('Location: index.php?page=manage-team-members');
    exit;
}

// Display any messages
if (isset($_SESSION['message'])) {
    echo '<div class="alert alert-' . $_SESSION['message_type'] . ' alert-dismissible fade show" role="alert">
            ' . $_SESSION['message'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>

<div class="container py-4">
    <h1 class="fw-bold text-danger mb-4"><?php echo getLangText('Manage Team Members', 'គ្រប់គ្រងសមាជិកក្រុម'); ?></h1>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><?php echo isset($_GET['edit']) ? getLangText('Edit Team Member', 'កែសម្រួលសមាជិកក្រុម') : getLangText('Add New Team Member', 'បន្ថែមសមាជិកក្រុមថ្មី'); ?></h5>
                </div>
                <div class="card-body">
                    <?php
                    // Handle form submission
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['team_member_submit'])) {
                        $name = sanitize($_POST['name']);
                        $name_km = sanitize($_POST['name_km']);
                        $position = sanitize($_POST['position']);
                        $position_km = sanitize($_POST['position_km']);
                        $bio = sanitize($_POST['bio']);
                        $bio_km = sanitize($_POST['bio_km']);
                        $is_active = isset($_POST['is_active']) ? 1 : 0;
                        $display_order = (int)$_POST['display_order'];
                        
                        // Handle image upload if provided
                        $image_path = null;
                        if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
                            $upload_dir = 'uploads/team/';
                            
                            // Create directory if it doesn't exist
                            if (!file_exists($upload_dir)) {
                                mkdir($upload_dir, 0777, true);
                            }
                            
                            $file_name = time() . '_' . basename($_FILES['image']['name']);
                            $target_file = $upload_dir . $file_name;
                            
                            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                                $image_path = $target_file;
                            } else {
                                echo '<div class="alert alert-danger">Error uploading image.</div>';
                            }
                        }
                        
                        try {
                            if (isset($_POST['member_id']) && $_POST['member_id'] > 0) {
                                // Update existing team member
                                $member_id = (int)$_POST['member_id'];
                                
                                if ($image_path) {
                                    // If a new image was uploaded, update the image path
                                    $stmt = $pdo->prepare("UPDATE team_members SET name = :name, name_km = :name_km, position = :position, position_km = :position_km, bio = :bio, bio_km = :bio_km, image_path = :image_path, is_active = :is_active, display_order = :display_order WHERE id = :id");
                                    $stmt->bindParam(':image_path', $image_path);
                                } else {
                                    // If no new image was uploaded, keep the existing image
                                    $stmt = $pdo->prepare("UPDATE team_members SET name = :name, name_km = :name_km, position = :position, position_km = :position_km, bio = :bio, bio_km = :bio_km, is_active = :is_active, display_order = :display_order WHERE id = :id");
                                }
                                
                                $stmt->bindParam(':name', $name);
                                $stmt->bindParam(':name_km', $name_km);
                                $stmt->bindParam(':position', $position);
                                $stmt->bindParam(':position_km', $position_km);
                                $stmt->bindParam(':bio', $bio);
                                $stmt->bindParam(':bio_km', $bio_km);
                                $stmt->bindParam(':is_active', $is_active);
                                $stmt->bindParam(':display_order', $display_order);
                                $stmt->bindParam(':id', $member_id);
                                $stmt->execute();
                                
                                $_SESSION['message'] = getLangText("Team member updated successfully!", "សមាជិកក្រុមត្រូវបានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ!");
                                $_SESSION['message_type'] = "success";
                                header('Location: index.php?page=manage-team-members');
                                exit;
                            } else {
                                // Add new team member
                                $stmt = $pdo->prepare("INSERT INTO team_members (name, name_km, position, position_km, bio, bio_km, image_path, is_active, display_order) VALUES (:name, :name_km, :position, :position_km, :bio, :bio_km, :image_path, :is_active, :display_order)");
                                $stmt->bindParam(':name', $name);
                                $stmt->bindParam(':name_km', $name_km);
                                $stmt->bindParam(':position', $position);
                                $stmt->bindParam(':position_km', $position_km);
                                $stmt->bindParam(':bio', $bio);
                                $stmt->bindParam(':bio_km', $bio_km);
                                $stmt->bindParam(':image_path', $image_path);
                                $stmt->bindParam(':is_active', $is_active);
                                $stmt->bindParam(':display_order', $display_order);
                                $stmt->execute();
                                
                                $_SESSION['message'] = getLangText("Team member added successfully!", "សមាជិកក្រុមត្រូវបានបន្ថែមដោយជោគជ័យ!");
                                $_SESSION['message_type'] = "success";
                                header('Location: index.php?page=manage-team-members');
                                exit;
                            }
                        } catch(PDOException $e) {
                            echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
                        }
                    }
                    
                    // Get team member for editing if specified
                    $edit_member = null;
                    if (isset($_GET['edit']) && $_GET['edit'] > 0) {
                        $member_id = (int)$_GET['edit'];
                        
                        try {
                            $stmt = $pdo->prepare("SELECT * FROM team_members WHERE id = :id");
                            $stmt->bindParam(':id', $member_id);
                            $stmt->execute();
                            $edit_member = $stmt->fetch();
                        } catch(PDOException $e) {
                            echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
                        }
                    }
                    ?>
                    
                    <form method="post" action="" enctype="multipart/form-data">
                        <?php if ($edit_member): ?>
                            <input type="hidden" name="member_id" value="<?php echo $edit_member['id']; ?>">
                        <?php endif; ?>
                        
                        <ul class="nav nav-tabs mb-3" id="languageTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="english-tab" data-bs-toggle="tab" data-bs-target="#english" type="button" role="tab" aria-controls="english" aria-selected="true">English</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="khmer-tab" data-bs-toggle="tab" data-bs-target="#khmer" type="button" role="tab" aria-controls="khmer" aria-selected="false">ភាសាខ្មែរ</button>
                            </li>
                        </ul>
                        
                        <div class="tab-content" id="languageTabsContent">
                            <!-- English Content -->
                            <div class="tab-pane fade show active" id="english" role="tabpanel" aria-labelledby="english-tab">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name (English)</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $edit_member ? $edit_member['name'] : ''; ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="position" class="form-label">Position (English)</label>
                                    <input type="text" class="form-control" id="position" name="position" value="<?php echo $edit_member ? $edit_member['position'] : ''; ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="bio" class="form-label">Bio (English)</label>
                                    <textarea class="form-control" id="bio" name="bio" rows="4" required><?php echo $edit_member ? $edit_member['bio'] : ''; ?></textarea>
                                </div>
                            </div>
                            
                            <!-- Khmer Content -->
                            <div class="tab-pane fade" id="khmer" role="tabpanel" aria-labelledby="khmer-tab">
                                <div class="mb-3">
                                    <label for="name_km" class="form-label">Name (ភាសាខ្មែរ)</label>
                                    <input type="text" class="form-control" id="name_km" name="name_km" value="<?php echo $edit_member ? $edit_member['name_km'] : ''; ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="position_km" class="form-label">Position (ភាសាខ្មែរ)</label>
                                    <input type="text" class="form-control" id="position_km" name="position_km" value="<?php echo $edit_member ? $edit_member['position_km'] : ''; ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="bio_km" class="form-label">Bio (ភាសាខ្មែរ)</label>
                                    <textarea class="form-control" id="bio_km" name="bio_km" rows="4" required><?php echo $edit_member ? $edit_member['bio_km'] : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label"><?php echo getLangText('Profile Image', 'រូបភាពប្រវត្តិរូប'); ?></label>
                            <?php if ($edit_member && $edit_member['image_path']): ?>
                                <div class="mb-2">
                                    <img src="<?php echo $edit_member['image_path']; ?>" alt="<?php echo $edit_member['name']; ?>" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                                <div class="form-text mb-2"><?php echo getLangText('Leave empty to keep current image.', 'ទុកឱ្យទទេដើម្បីរក្សារូបភាពបច្ចុប្បន្ន។'); ?></div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" <?php echo !$edit_member ? 'required' : ''; ?>>
                            <div class="form-text"><?php echo getLangText('Recommended size: 300x300 pixels (square).', 'ទំហំដែលណែនាំ៖ 300x300 ភីកសែល (ការេ)។'); ?></div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="display_order" class="form-label"><?php echo getLangText('Display Order', 'លំដាប់បង្ហាញ'); ?></label>
                                <input type="number" class="form-control" id="display_order" name="display_order" value="<?php echo $edit_member ? $edit_member['display_order'] : '0'; ?>" min="0">
                                <div class="form-text"><?php echo getLangText('Lower numbers appear first.', 'លេខតូចជាងបង្ហាញមុន។'); ?></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" <?php echo (!$edit_member || $edit_member['is_active']) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_active"><?php echo getLangText('Active', 'សកម្ម'); ?></label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex">
                            <button type="submit" name="team_member_submit" class="btn btn-danger me-2"><?php echo $edit_member ? getLangText('Update', 'ធ្វើបច្ចុប្បន្នភាព') : getLangText('Add', 'បន្ថែម'); ?> <?php echo getLangText('Team Member', 'សមាជិកក្រុម'); ?></button>
                            <?php if ($edit_member): ?>
                                <a href="index.php?page=manage-team-members" class="btn btn-outline-secondary"><?php echo getLangText('Cancel', 'បោះបង់'); ?></a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><?php echo getLangText('Team Members List', 'បញ្ជីសមាជិកក្រុម'); ?></h5>
                    <a href="index.php?page=manage-team-members" class="btn btn-sm btn-light"><?php echo getLangText('Add New', 'បន្ថែមថ្មី'); ?></a>
                </div>
                <div class="card-body">
                    <?php
                    // Get all team members
                    try {
                        $stmt = $pdo->prepare("SELECT * FROM team_members ORDER BY display_order ASC, name ASC");
                        $stmt->execute();
                        $team_members = $stmt->fetchAll();
                        
                        if (count($team_members) > 0):
                    ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><?php echo getLangText('Image', 'រូបភាព'); ?></th>
                                    <th><?php echo getLangText('Name', 'ឈ្មោះ'); ?></th>
                                    <th><?php echo getLangText('Position', 'តួនាទី'); ?></th>
                                    <th><?php echo getLangText('Status', 'ស្ថានភាព'); ?></th>
                                    <th><?php echo getLangText('Order', 'លំដាប់'); ?></th>
                                    <th><?php echo getLangText('Actions', 'សកម្មភាព'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($team_members as $member): ?>
                                <tr>
                                    <td>
                                        <?php if ($member['image_path']): ?>
                                            <img src="<?php echo $member['image_path']; ?>" alt="<?php echo $member['name']; ?>" class="img-thumbnail" style="max-height: 50px;">
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?php echo getLangText('No Image', 'គ្មានរូបភាព'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $member['name']; ?></td>
                                    <td><?php echo $member['position']; ?></td>
                                    <td>
                                        <?php if ($member['is_active']): ?>
                                            <span class="badge bg-success"><?php echo getLangText('Active', 'សកម្ម'); ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?php echo getLangText('Inactive', 'អសកម្ម'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $member['display_order']; ?></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="index.php?page=manage-team-members&edit=<?php echo $member['id']; ?>" class="btn btn-sm btn-outline-primary"><?php echo getLangText('Edit', 'កែ'); ?></a>
                                            <a href="index.php?page=manage-team-members&delete=<?php echo $member['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('<?php echo getLangText('Are you sure you want to delete this team member?', ''); ?>')"><?php echo getLangText('Delete', 'លុប'); ?></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                        <div class="alert alert-info"><?php echo getLangText('No team members found. Add some team members to get started.', 'រកមិនឃើញសមាជិកក្រុម។ បន្ថែមសមាជិកក្រុមខ្លះដើម្បីចាប់ផ្តើម។'); ?></div>
                    <?php 
                        endif;
                    } catch(PDOException $e) {
                        echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
                    }
                    ?>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="index.php?page=manage-team-members" class="btn btn-danger"><?php echo getLangText('Add New Team Member', 'បន្ថែមសមាជិកក្រុមថ្មី'); ?></a>
                        <a href="index.php?page=dashboard" class="btn btn-outline-secondary"><?php echo getLangText('Back to Dashboard', 'ត្រឡប់ទៅផ្ទាំងគ្រប់គ្រង'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
