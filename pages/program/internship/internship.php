<?php

// Get intern news from database
$featured_news = getInternNews($pdo, 1, 0, true, true); // Get 1 featured news
$recent_news = getInternNews($pdo, 6, 0, true, false); // Get 6 recent news

// Category labels and colors
$category_labels = [
    'success_story' => 'Success Story',
    'new_cohort' => 'New Cohort', 
    'achievement' => 'Achievement',
    'alumni_success' => 'Alumni Success',
    'project_spotlight' => 'Project Spotlight',
    'innovation' => 'Innovation',
    'program_update' => 'Program Update',
    'graduation' => 'Graduation'
];

$category_colors = [
    'success_story' => 'success',
    'new_cohort' => 'primary',
    'achievement' => 'warning', 
    'alumni_success' => 'info',
    'project_spotlight' => 'danger',
    'innovation' => 'dark',
    'program_update' => 'secondary',
    'graduation' => 'success'
];

?>



<div class="container">

    <?php if (!empty($featured_news)): ?>
        <?php $featured = $featured_news[0]; ?>
        <div class="position-relative overflow-hidden rounded-3 mb-5" style="height: 400px;">
            <?php if ($featured['image_path'] && file_exists($featured['image_path'])): ?>
                <img src="<?php echo $featured['image_path']; ?>" alt="<?php echo htmlspecialchars($featured['title']); ?>" 
                     class="w-100 h-100 object-fit-cover">
            <?php else: ?>
                <div class="w-100 h-100 bg-gradient-danger d-flex align-items-center justify-content-center">
                    <i class="fas fa-user-graduate fa-5x text-white opacity-50"></i>
                </div>
            <?php endif; ?>
            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50"></div>
            <div class="position-absolute bottom-0 start-0 p-4 text-white">
                <span class="badge bg-<?php echo $category_colors[$featured['category']]; ?> mb-2">
                    <?php echo $category_labels[$featured['category']]; ?>
                </span>
                <h2 class="display-6 fw-bold mb-2"><?php echo htmlspecialchars($featured['title']); ?></h2>
                <?php if ($featured['excerpt']): ?>
                    <p class="lead mb-3"><?php echo htmlspecialchars($featured['excerpt']); ?></p>
                <?php endif; ?>
                <?php if ($featured['intern_name']): ?>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-graduate me-2"></i>
                        <span><?php echo htmlspecialchars($featured['intern_name']); ?></span>
                        <?php if ($featured['intern_university']): ?>
                            <span class="mx-2">•</span>
                            <span><?php echo htmlspecialchars($featured['intern_university']); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <?php if (!empty($recent_news)): ?>
            <?php foreach ($recent_news as $news): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm hover-card">
                        <?php if ($news['image_path'] && file_exists($news['image_path'])): ?>
                            <img src="<?php echo $news['image_path']; ?>" alt="<?php echo htmlspecialchars($news['title']); ?>" 
                                 class="card-img-top" style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-user-graduate fa-3x text-muted"></i>
                            </div>
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-<?php echo $category_colors[$news['category']]; ?>">
                                    <?php echo $category_labels[$news['category']]; ?>
                                </span>
                                <small class="text-muted"><?php echo formatDate($news['created_at'], 'M d'); ?></small>
                            </div>
                            <h5 class="card-title"><?php echo htmlspecialchars($news['title']); ?></h5>
                            <?php if ($news['excerpt']): ?>
                                <p class="card-text text-muted flex-grow-1"><?php echo htmlspecialchars(truncateText($news['excerpt'], 100)); ?></p>
                            <?php endif; ?>
                            <?php if ($news['intern_name']): ?>
                                <div class="mt-auto">
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="fas fa-user-graduate me-2"></i>
                                        <small>
                                            <strong><?php echo htmlspecialchars($news['intern_name']); ?></strong>
                                            <?php if ($news['intern_university']): ?>
                                                <br><?php echo htmlspecialchars($news['intern_university']); ?>
                                            <?php endif; ?>
                                            <?php if ($news['intern_company']): ?>
                                                <br>@ <?php echo htmlspecialchars($news['intern_company']); ?>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No intern news available</h5>
                    <p class="text-muted">Check back soon for updates about our amazing interns!</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
