<div class="list-group mb-4">
    <a href="index.php?page=dashboard" class="list-group-item list-group-item-action <?php echo $page === 'dashboard' ? 'active' : ''; ?>">
        <i class="bi bi-speedometer2 me-2"></i> <?php echo getLangText('Dashboard', 'ផ្ទាំងគ្រប់គ្រង'); ?>
    </a>
    
    <div class="list-group-item list-group-item-secondary fw-bold">
        <?php echo getLangText('Content Management', 'ការគ្រប់គ្រងមាតិកា'); ?>
    </div>
    
    <a href="index.php?page=manage-news" class="list-group-item list-group-item-action <?php echo $page === 'manage-news' ? 'active' : ''; ?>">
        <i class="bi bi-newspaper me-2"></i> <?php echo getLangText('News Articles', 'អត្ថបទព័ត៌មាន'); ?>
    </a>
    
    <a href="index.php?page=scheduled-news" class="list-group-item list-group-item-action <?php echo $page === 'scheduled-news' ? 'active' : ''; ?>">
        <i class="bi bi-calendar-event me-2"></i> <?php echo getLangText('Scheduled News', 'ព័ត៌មានដែលបានកំណត់ពេល'); ?>
        <?php
        // Count scheduled news
        $current_time = date('Y-m-d H:i:s');
        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM news WHERE publish_at IS NOT NULL AND publish_at > :current_time");
            $stmt->bindParam(':current_time', $current_time);
            $stmt->execute();
            $scheduled_count = $stmt->fetchColumn();
            
            if ($scheduled_count > 0) {
                echo '<span class="badge bg-warning text-dark float-end">' . $scheduled_count . '</span>';
            }
        } catch (PDOException $e) {
            // Silently fail
        }
        ?>
    </a>
    
    <a href="index.php?page=manage-announcements" class="list-group-item list-group-item-action <?php echo $page === 'manage-announcements' ? 'active' : ''; ?>">
        <i class="bi bi-megaphone me-2"></i> <?php echo getLangText('Announcements', 'សេចក្តីប្រកាស'); ?>
    </a>
    
    <a href="index.php?page=manage-slides" class="list-group-item list-group-item-action <?php echo $page === 'manage-slides' ? 'active' : ''; ?>">
        <i class="bi bi-images me-2"></i> <?php echo getLangText('Slides', 'ស្លាយ'); ?>
    </a>
    
    <a href="index.php?page=manage-media" class="list-group-item list-group-item-action <?php echo $page === 'manage-media' ? 'active' : ''; ?>">
        <i class="bi bi-file-earmark-image me-2"></i> <?php echo getLangText('Media Library', 'បណ្ណាល័យមេឌៀ'); ?>
    </a>
    
    <div class="list-group-item list-group-item-secondary fw-bold">
        <?php echo getLangText('Programs', 'កម្មវិធី'); ?>
    </div>
    
    <a href="index.php?page=manage-talkshow" class="list-group-item list-group-item-action <?php echo $page === 'manage-talkshow' ? 'active' : ''; ?>">
        <i class="bi bi-mic me-2"></i> <?php echo getLangText('Talkshow', 'កម្មវិធីសម្ភាសន៍'); ?>
    </a>
    
    <a href="index.php?page=manage-roadshow" class="list-group-item list-group-item-action <?php echo $page === 'manage-roadshow' ? 'active' : ''; ?>">
        <i class="bi bi-signpost-2 me-2"></i> <?php echo getLangText('Roadshow', 'កម្មវិធីផ្សព្វផ្សាយ'); ?>
    </a>
    
    <div class="list-group-item list-group-item-secondary fw-bold">
        <?php echo getLangText('Explore Content', 'មាតិកាស្វែងរក'); ?>
    </div>
    
    <a href="index.php?page=manage-popular-jobs" class="list-group-item list-group-item-action <?php echo $page === 'manage-popular-jobs' ? 'active' : ''; ?>">
        <i class="bi bi-briefcase me-2"></i> <?php echo getLangText('Popular Careers', 'អាជីពពេញនិយម'); ?>
    </a>
    
    <a href="index.php?page=manage-popular-majors" class="list-group-item list-group-item-action <?php echo $page === 'manage-popular-majors' ? 'active' : ''; ?>">
        <i class="bi bi-mortarboard me-2"></i> <?php echo getLangText('Popular Majors', 'មុខជំនាញពេញនិយម'); ?>
    </a>
    
    <a href="index.php?page=manage-career-paths" class="list-group-item list-group-item-action <?php echo $page === 'manage-career-paths' ? 'active' : ''; ?>">
        <i class="bi bi-diagram-3 me-2"></i> <?php echo getLangText('Career Paths', 'ផ្លូវអាជីព'); ?>
    </a>
    
    <div class="list-group-item list-group-item-secondary fw-bold">
        <?php echo getLangText('System', 'ប្រព័ន្ធ'); ?>
    </div>
    
    <a href="index.php?page=manage-users" class="list-group-item list-group-item-action <?php echo $page === 'manage-users' ? 'active' : ''; ?>">
        <i class="bi bi-people me-2"></i> <?php echo getLangText('Users', 'អ្នកប្រើប្រាស់'); ?>
    </a>
    
    <a href="index.php?page=manage-site-settings" class="list-group-item list-group-item-action <?php echo $page === 'manage-site-settings' ? 'active' : ''; ?>">
        <i class="bi bi-gear me-2"></i> <?php echo getLangText('Site Settings', 'ការកំណត់គេហទំព័រ'); ?>
    </a>
</div>
