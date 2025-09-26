<!-- Hero Section with Modern Slideshow -->
<div class="hero-slideshow-modern">
    <div class="slideshow-container-modern">
        <?php
        // Get all hero slides from database
        try {
            $stmt = $pdo->prepare("SELECT * FROM site_settings WHERE setting_key LIKE 'hero_image_%' ORDER BY setting_key ASC");
            $stmt->execute();
            $hero_slides = $stmt->fetchAll();
            
            $valid_slides = [];
            foreach ($hero_slides as $slide) {
                if (!empty($slide['setting_value'])) {
                    $valid_slides[] = $slide;
                }
            }
            
            if (count($valid_slides) > 0) {
                foreach ($valid_slides as $index => $slide) {
                    echo '<div class="slide-modern' . ($index === 0 ? ' active' : '') . '">';
                    echo '<div class="slide-image-container">';
                    echo '<img src="' . $slide['setting_value'] . '" alt="MACA Slide ' . ($index + 1) . '" class="slide-image-modern">';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                // Default slides if none found in database
                $default_slides = [
                    ['image' => 'assets/images/hero-image-1.jpg'],
                    ['image' => 'assets/images/hero-image-2.jpg'],
                    ['image' => 'assets/images/hero-image-3.jpg']
                ];
                
                foreach ($default_slides as $index => $slide) {
                    echo '<div class="slide-modern' . ($index === 0 ? ' active' : '') . '">';
                    echo '<div class="slide-image-container">';
                    echo '<img src="' . $slide['image'] . '" alt="MACA Slide ' . ($index + 1) . '" class="slide-image-modern" onerror="this.src=\'https://via.placeholder.com/1200x500?text=MACA+Education\'">';
                    echo '</div>';
                    echo '</div>';
                }
            }
        } catch(PDOException $e) {
            // Display default slide on error
            echo '<div class="slide-modern active">';
            echo '<div class="slide-image-container">';
            echo '<img src="assets/images/hero-image-1.jpg" alt="MACA Education" class="slide-image-modern" onerror="this.src=\'https://via.placeholder.com/1200x500?text=MACA+Education\'">';
            echo '</div>';
            echo '</div>';
        }
        ?>

        <!-- Navigation arrows -->
        <button class="slide-arrow prev-arrow" aria-label="Previous slide">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="slide-arrow next-arrow" aria-label="Next slide">
            <i class="fas fa-chevron-right"></i>
        </button>

        <!-- Progress bar -->
        <div class="slide-progress-container">
            <div class="slide-progress-bar"></div>
        </div>
    </div>
    
    <!-- Navigation dots -->
    <div class="slideshow-dots-modern">
        <?php
        $slide_count = count($valid_slides) > 0 ? count($valid_slides) : 3; // Default to 3 if no slides found
        for ($i = 0; $i < $slide_count; $i++) {
            echo '<button class="dot-modern' . ($i === 0 ? ' active' : '') . '" aria-label="Go to slide ' . ($i + 1) . '"></button>';
        }
        ?>
    </div>
</div>

  

  <style>
/* --- Global & Font Styles --- */
:root {
  --primary-color-start: #e63946;
  --primary-color-end: #c12a37;
  --light-color: #ffffff;
  --light-text-color: #f8f9fa;
  --dark-text-color: #333;
  --hover-shadow: 0 10px 20px rgba(41, 23, 25, 0.25);
  --base-shadow: 0 4px 6px rgba(41, 23, 25, 0.1);
  --font-family: "Segoe UI", system-ui, -apple-system, BlinkMacSystemFont,
    Roboto, "Helvetica Neue", Arial, sans-serif;
}


/* --- Main Preview Section --- */
.preview-section {
  background: linear-gradient(
    135deg,
    var(--primary-color-start),
    var(--primary-color-end)
  );
  color: var(--light-text-color);
  padding: 60px 30px;
  border-radius: 24px; /* Softer corners */
  margin-bottom: 3rem;
  box-shadow: var(--hover-shadow);
  overflow: hidden; /* Ensures content respects the border-radius */
  position: relative;
}

/* --- Typography --- */
.preview-section h2 {
  font-size: 1.8rem;
  color: var(--light-color);
  margin-bottom: 15px;
  font-weight: 700;
  text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2); /* Adds depth to text */
}

.preview-section p {
  font-size: 1.1rem;
  margin: 10px auto;
  max-width: 600px;
  line-height: 1.7; /* Increased for better readability */
  opacity: 0.9;
}

/* --- Custom List with SVG Icon --- */
.preview-list {
  margin-top: 25px;
  font-size: 1.05rem;
  list-style: none;
  padding: 0;
}

.preview-list li {
  margin-bottom: 12px; /* Adds spacing between items */
  display: flex;
  align-items: center;
}

.preview-list li::before {
  content: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%2334D399" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>');
  width: 24px;
  height: 24px;
  margin-right: 12px;
  display: inline-block;
  vertical-align: middle;
}

/* --- Action Buttons --- */
.preview-buttons {
  margin: 40px 0 20px;
  display: flex;
  flex-direction: column;
  gap: 15px;
  align-items: center;
}

.preview-buttons a {
  text-decoration: none;
  background-color: var(--light-color);
  color: var(--primary-color-start);
  border: none;
  border-radius: 50px; /* Fully rounded pill shape */
  padding: 14px 28px;
  font-weight: bold;
  display: flex;
  align-items: center;
  gap: 10px;
  width: 220px;
  justify-content: center;
  transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
  box-shadow: var(--base-shadow);
}

.preview-buttons a img {
  filter: brightness(0) saturate(100%) invert(26%) sepia(92%)
    saturate(1811%) hue-rotate(337deg) brightness(98%) contrast(97%);
  transition: filter 0.3s ease;
}

.preview-buttons a:hover {
  background-color: var(--light-color);
  color: #a1202b; /* A darker red on hover for better contrast */
  box-shadow: var(--hover-shadow);
  transform: translateY(-5px); /* More pronounced hover effect */
}

.preview-buttons a:hover img {
  /* No filter change needed if icon color is desirable on hover */
}

/* --- Content & Image Layout --- */
.preview-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 40px;
  text-align: center; /* Center text on mobile */
}

.preview-image {
  max-width: 100%;
  width: 400px;
  animation: float 4s ease-in-out infinite;
  filter: drop-shadow(
    0 20px 25px rgba(0, 0, 0, 0.3)
  ); /* More realistic shadow */
}

@keyframes float {
  0% { transform: translateY(0); }
  50% { transform: translateY(-20px); }
  100% { transform: translateY(0); }
}

/* --- Responsive Design for Desktop --- */
@media (min-width: 768px) {
  .preview-content {
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    text-align: left;
    max-width: 1100px;
    margin: 0 auto;
  }

  .preview-text {
    max-width: 50%;
  }

  .preview-buttons {
    flex-direction: row;
    justify-content: flex-start; /* Align to the left on desktop */
  }

  .preview-buttons a {
    margin-right: 15px;
  }

  .preview-image {
    width: 450px; /* Slightly smaller for better balance */
  }
}
    </style>
  </head>
  <body>
    
    <section class="preview-section">
      <div class="preview-content">
        <div class="preview-text">
          <h2><?php echo getLangText('MACA MOBILE APP gfbjvdsbjsd', 'កម្មវិធីទូរស័ព្ទ MACA'); ?></h2>
          <p><strong><?php echo getLangText('Career Assessment to Uncover Your Potential', 'ការវាយតម្លៃអាជីពដើម្បីស្វែងរកសក្តានុពលរបស់អ្នក'); ?></strong></p>
          <p>
            <?php echo getLangText('MACA has developed the MACA Mobile application to assess which majors and careers best suit each individual and to provide detailed information on various academic and career options.', 'MACA បានបង្កើតកម្មវិធីទូរស័ព្ទ MACA ដើម្បីវាយតម្លៃថាតើមុខជំនាញ និងអាជីពណាដែលស័ក្តិសមបំផុតសម្រាប់បុគ្គលម្នាក់ៗ និងផ្តល់ព័ត៌មានលម្អិតអំពីជម្រើសសិក្សា និងអាជីពផ្សេងៗ។'); ?>
          </p>
          <p><strong><?php echo getLangText('MACA App Knowledge:', 'ចំណេះដឹងអំពីកម្មវិធី MACA៖'); ?></strong></p>
          <ul class="preview-list">
            <li><?php echo getLangText('Learn about yourself', 'ស្វែងយល់ពីខ្លួនឯង'); ?></li>
            <li><?php echo getLangText('Learn about academic skills', 'ស្វែងយល់ពីជំនាញសិក្សា'); ?></li>
            <li><?php echo getLangText('Learn about careers', 'ស្វែងយល់ពីអាជីព'); ?></li>
          </ul>

          <div class="preview-buttons">
            <a
              href="https://play.google.com/store/apps/details?id=maca.app.org.com"
            >
              <img
                src="https://img.icons8.com/ios-filled/24/000000/android-os.png"
                alt="Android Icon"
              />
              Android
            </a>
            <a href="https://apps.apple.com/in/app/maca-mobile/id1670034546">
              <img
                src="https://img.icons8.com/ios-filled/24/000000/mac-os.png"
                alt="iOS Icon"
              />
              iOS
            </a>
          </div>
        </div>

        <!-- MACA App Preview PNG image -->
        <img
          src="./assets/images/maca_app.png"
          alt="MACA App Preview"
          class="preview-image"
        />
      </div>
    </section>


<div class="educational-programs-section py-5 mb-5">
    <div class="container">
        <!-- Enhanced Section Header -->
        <div class="section-header-enhanced text-center mb-5">
            <div class="section-icon-wrapper">
                <div class="section-icon-enhanced">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="icon-glow"></div>
            </div>
            <h2 class="section-title-enhanced"><?php echo getLangText('Our Educational', 'កម្មវិធីអប់រំរបស់យើង'); ?> <span class="text-danger"><?php echo getLangText('Programs', 'កម្មវិធី'); ?></span></h2>
            <p class="section-subtitle-enhanced"><?php echo getLangText('Discover the various educational opportunities we offer to shape your future', 'ស្វែងយល់ពីឱកាសអប់រំផ្សេងៗដែលយើងផ្តល់ជូនដើម្បីកសាងអនាគតរបស់អ្នក'); ?></p>
            <div class="section-divider-enhanced"></div>
        </div>
        
        <!-- Enhanced Programs Grid -->
        <div class="programs-grid-enhanced">
            <?php
            $programs = [
                [
                    'title' => getLangText('Online Learning', 'ការសិក្សាតាមអនឡាញ'),
                    'description' => getLangText('Access quality education from anywhere with our comprehensive online courses and interactive learning platforms.', 'ទទួលបានការអប់រំប្រកបដោយគុណភាពពីគ្រប់ទីកន្លែងជាមួយនឹងវគ្គសិក្សាអនឡាញដ៏ទូលំទូលាយ និងវេទិកាសិក្សាអន្តរកម្មរបស់យើង។'),
                    'icon' => 'fa-laptop',
                    'link' => 'index.php?page=program/online-learning',
                    'color' => 'blue',
                    'features' => [getLangText('24/7 Access', 'ចូលប្រើ 24/7'), getLangText('Interactive Content', 'មាតិកាអន្តរកម្ម'), getLangText('Expert Instructors', 'គ្រូបង្រៀនជំនាញ')]
                ],
                [
                    'title' => getLangText('Career Counselling', 'ការប្រឹក្សាអាជីព'),
                    'description' => getLangText('Get expert guidance to make informed decisions about your academic and career path with personalized advice.', 'ទទួលបានការណែនាំពីអ្នកជំនាញដើម្បីធ្វើការសម្រេចចិត្តប្រកបដោយព័ត៌មានអំពីផ្លូវសិក្សា និងអាជីពរបស់អ្នកជាមួយនឹងដំបូន្មានផ្ទាល់ខ្លួន។'),
                    'icon' => 'fa-compass',
                    'link' => 'index.php?page=program/career-counselling',
                    'color' => 'green',
                    'features' => [getLangText('Personal Guidance', 'ការណែនាំផ្ទាល់ខ្លួន'), getLangText('Career Planning', 'ការរៀបចំផែនការអាជីព'), getLangText('Industry Insights', 'ការយល់ដឹងអំពីឧស្សាហកម្ម')]
                ],
                [
                    'title' => getLangText('Internship Program', 'កម្មវិធីកម្មសិក្សា'),
                    'description' => getLangText('Gain practical experience through our partnerships with leading organizations and build your professional network.', 'ទទួលបានបទពិសោធន៍ជាក់ស្តែងតាមរយៈភាពជាដៃគូរបស់យើងជាមួយអង្គការឈានមុខគេ និងកសាងបណ្តាញវិជ្ជាជីវៈរបស់អ្នក។'),
                    'icon' => 'fa-briefcase',
                    'link' => 'index.php?page=program/internship/internship',
                    'color' => 'purple',
                    'features' => [getLangText('Real Experience', 'បទពិសោធន៍ពិតប្រាកដ'), getLangText('Industry Partners', 'ដៃគូឧស្សាហកម្ម'), getLangText('Skill Development', 'ការអភិវឌ្ឍជំនាញ')]
                ],
                [
                    'title' => getLangText('Online Recruitment', 'ការជ្រើសរើសតាមអនឡាញ'),
                    'description' => getLangText('Connect with employers looking for talented individuals like you through our comprehensive job placement platform.', 'ភ្ជាប់ទំនាក់ទំនងជាមួយនិយោជកដែលកំពុងស្វែងរកបុគ្គលដែលមានទេពកោសល្យដូចអ្នកតាមរយៈវេទិកាស្វែងរកការងារដ៏ទូលំទូលាយរបស់យើង។'),
                    'icon' => 'fa-handshake',
                    'link' => 'index.php?page=program/online-recruitment',
                    'color' => 'orange',
                    'features' => [getLangText('Job Matching', 'ការផ្គូផ្គងការងារ'), getLangText('Employer Network', 'បណ្តាញនិយោជក'), getLangText('Career Support', 'ការគាំទ្រអាជីព')]
                ],
                 [
                    'title' => getLangText('Career Counselling', 'ការប្រឹក្សាអាជីព'),
                    'description' => getLangText('Get expert guidance to make informed decisions about your academic and career path with personalized advice.', 'ទទួលបានការណែនាំពីអ្នកជំនាញដើម្បីធ្វើការសម្រេចចិត្តប្រកបដោយការយល់ដឹងអំពីផ្លូវសិក្សា និងអាជីពរបស់អ្នកជាមួយនឹងដំបូន្មានផ្ទាល់ខ្លួន។'),
                    'icon' => 'fa-user-tie', 
                    'link' => 'index.php?page=program/career-counselling',
                    'color' => 'orange',
                    'features' => [getLangText('Education & Academic Plannin', 'ការប្រឹក្សាផ្នែកអប់រំ'), getLangText('Career Development & Guidanc', 'ការប្រឹក្សាផ្នែកអាជីព'), getLangText('Personal Growth & Decision-Making', ' ការអភិវឌ្ឍផ្ទាល់ខ្លួន និងការសម្រេចចិត្ត')]
                ]
            ];
            
            foreach ($programs as $index => $program):
            ?>
            <div class="program-card-enhanced" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                <div class="program-card-inner">
                    <!-- Card Header with Icon -->
                    <div class="program-header <?php echo $program['color']; ?>">
                        <div class="program-icon-container">
                            <div class="program-icon">
                                <i class="fas <?php echo $program['icon']; ?>"></i>
                            </div>
                        </div>
                        <div class="program-number">0<?php echo $index + 1; ?></div>
                    </div>
                    
                    <!-- Card Content -->
                    <div class="program-content">
                        <h3 class="program-title"><?php echo $program['title']; ?></h3>
                        <p class="program-description"><?php echo $program['description']; ?></p>
                        
                        <!-- Features List -->
                        <div class="program-features">
                            <?php foreach ($program['features'] as $feature): ?>
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span><?php echo $feature; ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Card Footer -->
                    <div class="program-footer">
                        <a href="<?php echo $program['link']; ?>" class="btn-program-learn">
                            <span><?php echo getLangText('Learn More', 'ស្វែងយល់បន្ថែម'); ?></span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    
                    <!-- Hover Effect Overlay -->
                    <div class="program-overlay <?php echo $program['color']; ?>"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
    </div>
</div>

<!-- Popular Majors Section - New Design -->
<div class="popular-majors-section py-5 mb-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <div class="section-icon-container">
                <div class="section-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
            </div>
            <h2 class="section-title"><?php echo getLangText('Popular', 'មុខជំនាញ'); ?> <span class="text-danger"><?php echo getLangText('Majors', 'ពេញនិយម'); ?></span></h2>
            <p class="section-subtitle"><?php echo getLangText('Explore top academic fields chosen by our students', 'ស្វែងយល់ពីមុខជំនាញសិក្សាដែលពេញនិយមបំផុតដែលជ្រើសរើសដោយសិស្សរបស់យើង'); ?></p>
            <div class="section-divider"></div>
        </div>
        
        <div class="majors-grid">
            <?php
            // Fetch popular majors from database
            try {
                $stmt = $pdo->query("SELECT * FROM popular_majors WHERE is_active = 1 ORDER BY display_order ASC LIMIT 6");
                $popular_majors = $stmt->fetchAll();
                
                if (count($popular_majors) > 0):
                    foreach ($popular_majors as $index => $major):
                        // Use actual salary data from database, or generate random if not available
                        if (!empty($major['avg_salary'])) {
                            $salary_range = $major['avg_salary'];
                        } else {
                            // Fallback to random generation if no salary data
                            $min_salary = rand(35, 70) * 1000;
                            $max_salary = $min_salary + rand(20, 50) * 1000;
                            $salary_range = '$' . number_format($min_salary) . ' - $' . number_format($max_salary);
                        }
            ?>
            <div class="major-card-new">
                <div class="major-image-container">
                    <?php if (!empty($major['image_path'])): ?>
                        <img src="<?php echo $major['image_path']; ?>" alt="<?php echo $major['title']; ?>" class="major-image">
                    <?php else: ?>
                        <div class="major-placeholder">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                    <?php endif; ?>
                    
                    
<div class="major-overlay">
    <button type="button" class="btn-explore" data-bs-toggle="modal" data-bs-target="#majorModal<?php echo $major['id']; ?>">
        <?php echo getLangText('Explore Major', 'ស្វែងយល់ពីមុខជំនាញ'); ?>
    </button>
</div>

                </div>
                <div class="major-content">
                    <h3 class="major-title"><?php echo $major['title']; ?></h3>
                    <div class="major-meta">
                        <?php if (!empty($major['institutions'])): ?>
                        <div class="major-meta-item">
                            <i class="fas fa-university"></i>
                            <span><?php echo mb_substr($major['institutions'], 0, 30) . (strlen($major['institutions']) > 30 ? '...' : ''); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="major-meta-item">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Avg. Salary: <?php echo $salary_range; ?></span>
                        </div>
                    </div>
                    <div class="major-description">
                        <?php echo mb_substr(strip_tags($major['description']), 0, 100) . '...'; ?>
                    </div>
                    <div class="major-skills">
                        <?php 
                        // Generate sample skills if none provided
                        $skills = [];
                        if (!empty($major['skills_gained'])) {
                            $skills_text = strip_tags($major['skills_gained']);
                            $skills = explode(',', $skills_text);
                            $skills = array_slice($skills, 0, 3);
                        } else {
                            // Sample skills based on major title
                            $sample_skills = [
                                'Critical Thinking', 'Research', 'Analysis', 'Communication', 
                                'Problem Solving', 'Teamwork', 'Leadership', 'Technical Skills',
                                'Data Analysis', 'Project Management', 'Design', 'Programming'
                            ];
                            shuffle($sample_skills);
                            $skills = array_slice($sample_skills, 0, 3);
                        }
                        
                        foreach ($skills as $skill):
                        ?>
                        <span class="skill-tag"><?php echo trim($skill); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
<!-- Modal for major details -->
<div class="modal fade" id="majorModal<?php echo $major['id']; ?>" tabindex="-1" aria-labelledby="majorModalLabel<?php echo $major['id']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="majorModalLabel<?php echo $major['id']; ?>"><?php echo $major['title']; ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <?php if (!empty($major['image_path'])): ?>
                            <img src="<?php echo $major['image_path']; ?>" class="img-fluid rounded" alt="<?php echo $major['title']; ?>">
                        <?php else: ?>
                            <div class="major-placeholder-modal">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mt-3">
                    <h6 class="fw-bold"><?php echo getLangText('Key Information', 'ព័ត៌មានសំខាន់ៗ'); ?></h6>
                    <ul class="list-unstyled">
                        <?php if (!empty($major['institutions'])): ?>
                        <li class="mb-2">
                            <i class="fas fa-university text-danger me-2"></i>
                            <strong><?php echo getLangText('Institutions:', 'ស្ថាប័ន៖'); ?></strong> <?php echo $major['institutions']; ?>
                        </li>
                        <?php endif; ?>
                        <li class="mb-2">
                            <i class="fas fa-money-bill-wave text-danger me-2"></i>
                            <strong><?php echo getLangText('Avg. Salary:', 'ប្រាក់ខែជាមធ្យម៖'); ?></strong> <?php echo $salary_range; ?>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-clock text-danger me-2"></i>
                            <strong><?php echo getLangText('Duration:', 'រយៈពេល៖'); ?></strong> <?php echo !empty($major['duration']) ? $major['duration'] : getLangText('4 years', '៤ ឆ្នាំ'); ?>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-8">
                <h5 class="border-bottom pb-2 mb-3"><?php echo getLangText('About this Major', 'អំពីមុខជំនាញនេះ'); ?></h5>
                <div class="major-full-description mb-4">
                    <?php 
                    if (!empty($major['about_major'])) {
                        echo $major['about_major'];
                    } elseif (!empty($major['description'])) {
                        echo $major['description'];
                    } else {
                        echo getLangText('No detailed description available.', 'មិនមានការពិពណ៌នាលម្អិតទេ។');
                    }
                    ?>
                </div>
                
                <h5 class="border-bottom pb-2 mb-3"><?php echo getLangText('Skills Gained', 'ជំនាញដែលទទួលបាន'); ?></h5>
                <div class="major-skills-container mb-4">
                    <?php 
                    if (!empty($major['skills_gained'])) {
                        $skills_text = strip_tags($major['skills_gained']);
                        $skills = explode(',', $skills_text);
                        foreach ($skills as $skill): 
                    ?>
                        <span class="badge bg-light text-danger border border-danger me-2 mb-2 p-2"><?php echo trim($skill); ?></span>
                    <?php 
                        endforeach;
                    } else {
                        echo '<p class="text-muted">' . getLangText('No specific skills listed.', 'មិនមានជំនាញជាក់លាក់ណាមួយត្រូវបានរាយបញ្ជីទេ។') . '</p>';
                    }
                    ?>
                </div>
                
                <h5 class="border-bottom pb-2 mb-3"><?php echo getLangText('Career Opportunities', 'ឱកាសអាជីព'); ?></h5>
                <div class="major-careers mb-4">
                    <?php 
                    if (!empty($major['career_opportunities'])) {
                        echo $major['career_opportunities'];
                    } else {
                        echo getLangText('No specific career opportunities listed.', 'មិនមានឱកាសអាជីពជាក់លាក់ណាមួយត្រូវបានរាយបញ្ជីទេ។');
                    }
                    ?>
                </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php 
                    endforeach;
                else:
                    // Display sample data if no majors found
                    $sample_majors = [
                        [
                            'title' => 'Computer Science',
                            'description' => 'Learn programming, algorithms, and software development to build the digital future.',
                            'institutions' => 'MIT, Stanford, UC Berkeley',
                            'salary' => '$75,000 - $120,000',
                            'skills' => ['Programming', 'Algorithms', 'Problem Solving'],
                            'image' => 'assets/images/major-cs.jpg'
                        ],
                        [
                            'title' => 'Business Administration',
                            'description' => 'Develop management, marketing, and entrepreneurial skills for the business world.',
                            'institutions' => 'Harvard, Wharton, London Business School',
                            'salary' => '$65,000 - $110,000',
                            'skills' => ['Leadership', 'Marketing', 'Finance'],
                            'image' => 'assets/images/major-business.jpg'
                        ],
                        [
                            'title' => 'Engineering',
                            'description' => 'Design and build solutions to complex technical problems across various industries.',
                            'institutions' => 'Caltech, Georgia Tech, Purdue',
                            'salary' => '$70,000 - $115,000',
                            'skills' => ['Design', 'Analysis', 'Technical Skills'],
                            'image' => 'assets/images/major-engineering.jpg'
                        ],
                        [
                            'title' => 'Healthcare Sciences',
                            'description' => 'Prepare for careers in medicine, nursing, and other healthcare professions.',
                            'institutions' => 'Johns Hopkins, Mayo Clinic College',
                            'salary' => '$60,000 - $100,000',
                            'skills' => ['Patient Care', 'Medical Knowledge', 'Communication'],
                            'image' => 'assets/images/major-healthcare.jpg'
                        ]
                    ];
                    
                    foreach ($sample_majors as $index => $major):
            ?>
            <div class="major-card-new">
                <div class="major-image-container">
                    <div class="major-placeholder">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    
<div class="major-overlay">
    <button type="button" class="btn-explore" data-bs-toggle="modal" data-bs-target="#majorModalSample<?php echo $index; ?>">
        <?php echo getLangText('Explore Major', 'ស្វែងយល់ពីមុខជំនាញ'); ?>
    </button>
</div>

                </div>
                <div class="major-content">
                    <h3 class="major-title"><?php echo $major['title']; ?></h3>
                    <div class="major-meta">
                        <div class="major-meta-item">
                            <i class="fas fa-university"></i>
                            <span><?php echo $major['institutions']; ?></span>
                        </div>
                        <div class="major-meta-item">
                            <i class="fas fa-money-bill-wave"></i>
                            <span><?php echo $major['salary']; ?></span>
                        </div>
                    </div>
                    <div class="major-description">
                        <?php echo $major['description']; ?>
                    </div>
                    <div class="major-skills">
                        <?php foreach ($major['skills'] as $skill): ?>
                        <span class="skill-tag"><?php echo $skill; ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
<!-- Modal for sample major details -->
<div class="modal fade" id="majorModalSample<?php echo $index; ?>" tabindex="-1" aria-labelledby="majorModalSampleLabel<?php echo $index; ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="majorModalSampleLabel<?php echo $index; ?>"><?php echo $major['title']; ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="major-placeholder-modal">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        
                        <div class="mt-3">
                            <h6 class="fw-bold"><?php echo getLangText('Key Information', 'ព័ត៌មានសំខាន់ៗ'); ?></h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-university text-danger me-2"></i>
                                    <strong><?php echo getLangText('Institutions:', 'ស្ថាប័ន៖'); ?></strong> <?php echo $major['institutions']; ?>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-money-bill-wave text-danger me-2"></i>
                                    <strong><?php echo getLangText('Avg. Salary:', 'ប្រាក់ខែជាមធ្យម៖'); ?></strong> <?php echo $major['salary']; ?>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-clock text-danger me-2"></i>
                                    <strong><?php echo getLangText('Duration:', 'រយៈពេល៖'); ?></strong> <?php echo getLangText('4 years', '៤ ឆ្នាំ'); ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h5 class="border-bottom pb-2 mb-3"><?php echo getLangText('About this Major', 'អំពីមុខជំនាញនេះ'); ?></h5>
                        <div class="major-full-description mb-4">
                            <p><?php echo $major['description']; ?></p>
                            <p><?php echo getLangText('This program provides students with a comprehensive education in', 'កម្មវិធីនេះផ្តល់ជូនសិស្សនូវការអប់រំដ៏ទូលំទូលាយក្នុង'); ?> <?php echo strtolower($major['title']); ?>, <?php echo getLangText('preparing them for various career paths in the field.', 'រៀបចំពួកគេសម្រាប់ផ្លូវអាជីពផ្សេងៗក្នុងវិស័យនេះ។'); ?></p>
                        </div>
                        
                        <h5 class="border-bottom pb-2 mb-3"><?php echo getLangText('Skills Gained', 'ជំនាញដែលទទួលបាន'); ?></h5>
                        <div class="major-skills-container mb-4">
                            <?php foreach ($major['skills'] as $skill): ?>
                                <span class="badge bg-light text-danger border border-danger me-2 mb-2 p-2"><?php echo $skill; ?></span>
                            <?php endforeach; ?>
                        </div>
                        
                        <h5 class="border-bottom pb-2 mb-3"><?php echo getLangText('Career Opportunities', 'ឱកាសអាជីព'); ?></h5>
                        <div class="major-careers mb-4">
                            <p><?php echo getLangText('Graduates of this program can pursue careers in various sectors including:', 'និស្សិតបញ្ចប់ការសិក្សាពីកម្មវិធីនេះអាចបន្តអាជីពក្នុងវិស័យផ្សេងៗរួមមាន៖'); ?></p>
                            <ul>
                                <?php 
                                $careers = [
                                    'Computer Science' => [getLangText('Software Developer', 'អ្នកអភិវឌ្ឍន៍កម្មវិធី'), getLangText('Data Scientist', 'អ្នកវិទ្យាសាស្ត្រទិន្នន័យ'), getLangText('Systems Analyst', 'អ្នកវិភាគប្រព័ន្ធ'), getLangText('IT Consultant', 'ទីប្រឹក្សាបច្ចេកវិទ្យាព័ត៌មាន')],
                                    'Business Administration' => [getLangText('Business Analyst', 'អ្នកវិភាគអាជីវកម្ម'), getLangText('Marketing Manager', 'អ្នកគ្រប់គ្រងទីផ្សារ'), getLangText('Project Manager', 'អ្នកគ្រប់គ្រងគម្រោង'), getLangText('Entrepreneur', 'សហគ្រិន')],
                                    'Engineering' => [getLangText('Design Engineer', 'វិស្វកររចនា'), getLangText('Project Engineer', 'វិស្វករគម្រោង'), getLangText('Systems Engineer', 'វិស្វករប្រព័ន្ធ'), getLangText('Research & Development', 'ស្រាវជ្រាវ និងអភិវឌ្ឍន៍')],
                                    'Healthcare Sciences' => [getLangText('Clinical Specialist', 'អ្នកឯកទេសគ្លីនិក'), getLangText('Healthcare Administrator', 'អ្នកគ្រប់គ្រងសុខភាព'), getLangText('Research Scientist', 'អ្នកវិទ្យាសាស្ត្រស្រាវជ្រាវ'), getLangText('Medical Consultant', 'ទីប្រឹក្សាវេជ្ជសាស្ត្រ')]
                                ];
                                
                                $title = $major['title'];
                                $career_list = isset($careers[$title]) ? $careers[$title] : [getLangText('Industry Specialist', 'អ្នកឯកទេសឧស្សាហកម្ម'), getLangText('Consultant', 'ទីប្រឹក្សា'), getLangText('Researcher', 'អ្នកស្រាវជ្រាវ'), getLangText('Manager', 'អ្នកគ្រប់គ្រង')];
                                
                                foreach ($career_list as $career):
                                ?>
                                <li><?php echo $career; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php 
                    endforeach;
                endif;
            } catch(PDOException $e) {
                echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
            }
            ?>
        </div>
        
        <div class="text-center mt-5">
            <a href="index.php?page=popular-majors" class="btn-view-all">
                <span><?php echo getLangText('View All Majors', 'មើលមុខជំនាញទាំងអស់'); ?></span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Popular Jobs Section - New Modern Design -->
<div class="popular-jobs-section-new py-5 mb-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <div class="section-icon-container">
                <div class="section-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
            </div>
            <h2 class="section-title"><?php echo getLangText('Popular', 'អាជីព'); ?> <span class="text-danger"><?php echo getLangText('Career', 'ពេញនិយម'); ?></span></h2>
            <p class="section-subtitle"><?php echo getLangText('Discover in-demand career opportunities for our graduates', 'ស្វែងយល់ពីឱកាសអាជីពដែលមានតម្រូវការសម្រាប់និស្សិតបញ្ចប់ការសិក្សារបស់យើង'); ?></p>
            <div class="section-divider"></div>
        </div>
        
        <div class="jobs-container">
            <?php
            // Fetch popular jobs from database
            try {
                $stmt = $pdo->query("SELECT * FROM popular_jobs WHERE is_active = 1 ORDER BY display_order ASC LIMIT 4");
                $popular_jobs = $stmt->fetchAll();
                
                if (count($popular_jobs) > 0):
                    foreach ($popular_jobs as $index => $job):
                        // Set default badge
                        // Use actual status tag from database
                        $badge = '';
                        if (!empty($job['status_tag'])) {
                            switch($job['status_tag']) {
                                case 'trending':
                                    $badge = 'Trending';
                                    break;
                                case 'new':
                                    $badge = 'New';
                                    break;
                                case 'hot':
                                    $badge = 'Hot';
                                    break;
                                case 'urgent':
                                    $badge = 'Urgent';
                                    break;
                                case 'featured':
                                    $badge = 'Featured';
                                    break;
                                default:
                                    $badge = ucfirst($job['status_tag']);
                            }
                        }
                        
                        // Alternate layout for even/odd items
                        $isEven = $index % 2 == 0;
            ?>
            <div class="job-item <?php echo $isEven ? 'job-item-even' : 'job-item-odd'; ?>">
                <div class="job-image-wrapper">
                    <?php if (!empty($job['image_path'])): ?>
                        <img src="<?php echo $job['image_path']; ?>" alt="<?php echo $job['title']; ?>" class="job-image">
                    <?php else: ?>
                        <div class="job-placeholder">
                            <i class="fas fa-briefcase"></i>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($badge)): ?>
                        <div class="job-badge job-badge-<?php echo strtolower($job['status_tag']); ?>"><?php echo $badge; ?></div>
                    <?php endif; ?>
                </div>
                <div class="job-content">
                    <h3 class="job-title"><?php echo $job['title']; ?></h3>
                    <div class="job-company">
                        <i class="fas fa-building"></i>
                        <span><?php echo !empty($job['company']) ? $job['company'] : 'Various Companies'; ?></span>
                    </div>
                    <div class="job-details-row">
                        <?php if (!empty($job['salary_range'])): ?>
                        <div class="job-detail">
                            <i class="fas fa-money-bill-wave"></i>
                            <span><?php echo $job['salary_range']; ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="job-detail">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo !empty($job['location']) ? $job['location'] : 'Remote / Phnom Penh'; ?></span>
                        </div>
                        <div class="job-detail">
                            <i class="fas fa-users"></i>
                            <span><?php echo !empty($job['openings']) ? $job['openings'] : rand(20, 150); ?>+ openings</span>
                        </div>
                    </div>
                    <div class="job-description">
                        <?php echo mb_substr(strip_tags($job['description']), 0, 150) . '...'; ?>
                    </div>
                    <div class="job-actions">
                        <button type="button" class="btn-job-details" data-bs-toggle="modal" data-bs-target="#jobModal<?php echo $job['id']; ?>"><?php echo getLangText('View Details', 'មើលលម្អិត'); ?></button>
                    </div>
                </div>
            </div>
            
            <!-- Modal for job details -->
            <div class="modal fade" id="jobModal<?php echo $job['id']; ?>" tabindex="-1" aria-labelledby="jobModalLabel<?php echo $job['id']; ?>" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="jobModalLabel<?php echo $job['id']; ?>"><?php echo $job['title']; ?></h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <?php if (!empty($job['image_path'])): ?>
                                        <img src="<?php echo $job['image_path']; ?>" class="img-fluid rounded" alt="<?php echo $job['title']; ?>">
                                    <?php else: ?>
                                        <div class="job-placeholder-modal">
                                            <i class="fas fa-briefcase"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="mt-3">
                                        <h6 class="fw-bold"><?php echo getLangText('Job Details', 'ព័ត៌មានលម្អិតការងារ'); ?></h6>
                                        <ul class="list-unstyled">
                                            <li class="mb-2">
                                                <i class="fas fa-building text-danger me-2"></i>
                                                <strong><?php echo getLangText('Company:', 'ក្រុមហ៊ុន៖'); ?></strong> <?php echo !empty($job['company']) ? $job['company'] : getLangText('Various Companies', 'ក្រុមហ៊ុនផ្សេងៗ'); ?>
                                            </li>
                                            <?php if (!empty($job['salary_range'])): ?>
                                            <li class="mb-2">
                                                <i class="fas fa-money-bill-wave text-danger me-2"></i>
                                                <strong><?php echo getLangText('Salary:', 'ប្រាក់ខែ៖'); ?></strong> <?php echo $job['salary_range']; ?>
                                            </li>
                                            <?php endif; ?>
                                            <li class="mb-2">
                                                <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                                <strong><?php echo getLangText('Location:', 'ទីតាំង៖'); ?></strong> <?php echo !empty($job['location']) ? $job['location'] : getLangText('Remote / Phnom Penh', 'ពីចម្ងាយ / ភ្នំពេញ'); ?>
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-clock text-danger me-2"></i>
                                                <strong><?php echo getLangText('Job Type:', 'ប្រភេទការងារ៖'); ?></strong> <?php echo !empty($job['job_type']) ? $job['job_type'] : getLangText('Full-time', 'ពេញម៉ោង'); ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h5 class="border-bottom pb-2 mb-3"><?php echo getLangText('Job Description', 'ការពិពណ៌នាការងារ'); ?></h5>
                                    <div class="job-full-description mb-4">
                                        <?php echo !empty($job['description']) ? $job['description'] : getLangText('No detailed description available.', 'មិនមានការពិពណ៌នាលម្អិតទេ។'); ?>
                                    </div>
                                    
                                    <h5 class="border-bottom pb-2 mb-3"><?php echo getLangText('Requirements', 'តម្រូវការ'); ?></h5>
                                    <div class="job-requirements mb-4">
                                        <?php if (!empty($job['requirements'])): ?>
                                            <?php echo $job['requirements']; ?>
                                        <?php else: ?>
                                            <ul>
                                                <li><?php echo getLangText('Bachelor\'s degree in a relevant field', 'បរិញ្ញាបត្រក្នុងវិស័យពាក់ព័ន្ធ'); ?></li>
                                                <li><?php echo getLangText('2+ years of experience in a similar role', 'បទពិសោធន៍ ២+ ឆ្នាំក្នុងតួនាទីស្រដៀងគ្នា'); ?></li>
                                                <li><?php echo getLangText('Strong communication and teamwork skills', 'ជំនាញទំនាក់ទំនង និងការងារជាក្រុមល្អ'); ?></li>
                                                <li><?php echo getLangText('Ability to work independently and meet deadlines', 'សមត្ថភាពធ្វើការដោយឯករាជ្យ និងបំពេញតាមកាលកំណត់'); ?></li>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <h5 class="border-bottom pb-2 mb-3"><?php echo getLangText('Benefits', 'អត្ថប្រយោជន៍'); ?></h5>
                                    <div class="job-benefits">
                                        <?php if (!empty($job['benefits'])): ?>
                                            <?php echo $job['benefits']; ?>
                                        <?php else: ?>
                                            <ul>
                                                <li><?php echo getLangText('Competitive salary package', 'កញ្ចប់ប្រាក់ខែប្រកួតប្រជែង'); ?></li>
                                                <li><?php echo getLangText('Health insurance', 'ធានារ៉ាប់រងសុខភាព'); ?></li>
                                                <li><?php echo getLangText('Professional development opportunities', 'ឱកាសអភិវឌ្ឍន៍វិជ្ជាជីវៈ'); ?></li>
                                                <li><?php echo getLangText('Flexible working arrangements', 'ការរៀបចំការងារបត់បែន'); ?></li>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal for job application -->
            <div class="modal fade" id="applyJobModal<?php echo $job['id']; ?>" tabindex="-1" aria-labelledby="applyJobModalLabel<?php echo $job['id']; ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="applyJobModalLabel<?php echo $job['id']; ?>">Apply for <?php echo $job['title']; ?></h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="jobApplicationForm<?php echo $job['id']; ?>" class="job-application-form">
                                <div class="mb-3">
                                    <label for="fullName<?php echo $job['id']; ?>" class="form-label"><?php echo getLangText('Full Name', 'ឈ្មោះពេញ'); ?> *</label>
                                    <input type="text" class="form-control" id="fullName<?php echo $job['id']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email<?php echo $job['id']; ?>" class="form-label"><?php echo getLangText('Email Address', 'អាសយដ្ឋានអ៊ីមែល'); ?> *</label>
                                    <input type="email" class="form-control" id="email<?php echo $job['id']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone<?php echo $job['id']; ?>" class="form-label"><?php echo getLangText('Phone Number', 'លេខទូរស័ព្ទ'); ?> *</label>
                                    <input type="tel" class="form-control" id="phone<?php echo $job['id']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="resume<?php echo $job['id']; ?>" class="form-label"><?php echo getLangText('Upload Resume (PDF)', 'ផ្ទុកប្រវត្តិរូបសង្ខេប (PDF)'); ?> *</label>
                                    <input type="file" class="form-control" id="resume<?php echo $job['id']; ?>" accept=".pdf" required>
                                </div>
                                <div class="mb-3">
                                    <label for="coverLetter<?php echo $job['id']; ?>" class="form-label"><?php echo getLangText('Cover Letter', 'លិខិតអម'); ?></label>
                                    <textarea class="form-control" id="coverLetter<?php echo $job['id']; ?>" rows="4"></textarea>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="agreeTerms<?php echo $job['id']; ?>" required>
                                    <label class="form-check-label" for="agreeTerms<?php echo $job['id']; ?>">
                                        <?php echo getLangText('I agree to the terms and conditions', 'ខ្ញុំយល់ព្រមតាមលក្ខខណ្ឌ'); ?>
                                    </label>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo getLangText('Cancel', 'បោះបង់'); ?></button>
                            <button type="submit" form="jobApplicationForm<?php echo $job['id']; ?>" class="btn btn-danger"><?php echo getLangText('Submit Application', 'ដាក់ពាក្យសុំ'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                    endforeach;
                else:
                    // Display sample data if no jobs found
                    $sample_jobs = [
                        [
                            'id' => 'sample1',
                            'title' => 'Software Developer',
                            'company' => 'Various Tech Companies',
                            'salary' => '$75,000 - $120,000',
                            'location' => 'Remote / Phnom Penh',
                            'icon' => 'fa-laptop-code',
                            'openings' => '120+',
                            'badge' => 'Hot',
                            'description' => 'Design, develop and maintain software applications using various programming languages and frameworks. Collaborate with cross-functional teams to deliver high-quality software solutions.',
                            'requirements' => '<ul>
                                <li>Bachelor\'s degree in Computer Science or related field</li>
                                <li>2+ years of experience in software development</li>
                                <li>Proficiency in at least one programming language (e.g., Java, Python, JavaScript)</li>
                                <li>Experience with web development frameworks</li>
                                <li>Strong problem-solving skills and attention to detail</li>
                            </ul>',
                            'benefits' => '<ul>
                                <li>Competitive salary and performance bonuses</li>
                                <li>Health, dental, and vision insurance</li>
                                <li>Flexible working hours and remote work options</li>
                                <li>Professional development opportunities</li>
                                <li>Modern office with recreational facilities</li>
                            </ul>'
                        ],
                        [
                            'id' => 'sample2',
                            'title' => 'Digital Marketing Specialist',
                            'company' => 'Marketing Agencies',
                            'salary' => '$45,000 - $80,000',
                            'location' => 'Phnom Penh',
                            'icon' => 'fa-bullhorn',
                            'openings' => '85+',
                            'badge' => 'New',
                            'description' => 'Create and implement digital marketing strategies across various platforms. Analyze campaign performance and optimize for better results.',
                            'requirements' => '<ul>
                                <li>Bachelor\'s degree in Marketing, Communications, or related field</li>
                                <li>1-3 years of experience in digital marketing</li>
                                <li>Experience with SEO, SEM, and social media marketing</li>
                                <li>Proficiency with marketing analytics tools</li>
                                <li>Strong creative and analytical skills</li>
                            </ul>',
                            'benefits' => '<ul>
                                <li>Competitive salary package</li>
                                <li>Health insurance and wellness programs</li>
                                <li>Professional development budget</li>
                                <li>Collaborative work environment</li>
                                <li>Opportunities for career advancement</li>
                            </ul>'
                        ],
                        [
                            'id' => 'sample3',
                            'title' => 'Financial Analyst',
                            'company' => 'Banking & Finance Sector',
                            'salary' => '$60,000 - $95,000',
                            'location' => 'Phnom Penh / Siem Reap',
                            'icon' => 'fa-chart-pie',
                            'openings' => '65+',
                            'badge' => '',
                            'description' => 'Analyze financial data, prepare reports, and provide recommendations to improve financial performance. Support budgeting and forecasting activities.',
                            'requirements' => '<ul>
                                <li>Bachelor\'s degree in Finance, Accounting, or related field</li>
                                <li>2+ years of experience in financial analysis</li>
                                <li>Strong analytical and quantitative skills</li>
                                <li>Proficiency in financial modeling and data analysis</li>
                                <li>Knowledge of financial regulations and reporting standards</li>
                            </ul>',
                            'benefits' => '<ul>
                                <li>Competitive salary and performance bonuses</li>
                                <li>Comprehensive benefits package</li>
                                <li>Professional certification support</li>
                                <li>Career development opportunities</li>
                                <li>Work-life balance initiatives</li>
                            </ul>'
                        ],
                        [
                            'id' => 'sample4',
                            'title' => 'Healthcare Administrator',
                            'company' => 'Hospitals & Clinics',
                            'salary' => '$55,000 - $90,000',
                            'location' => 'Nationwide',
                            'icon' => 'fa-hospital',
                            'openings' => '50+',
                            'badge' => 'Trending',
                            'description' => 'Oversee daily operations of healthcare facilities. Manage staff, budgets, and ensure compliance with healthcare regulations and policies.',
                            'requirements' => '<ul>
                                <li>Bachelor\'s degree in Healthcare Administration or related field</li>
                                <li>3+ years of experience in healthcare management</li>
                                <li>Knowledge of healthcare regulations and compliance</li>
                                <li>Strong leadership and organizational skills</li>
                                <li>Experience with healthcare information systems</li>
                            </ul>',
                            'benefits' => '<ul>
                                <li>Competitive salary package</li>
                                <li>Comprehensive health benefits</li>
                                <li>Retirement savings plan</li>
                                <li>Continuing education opportunities</li>
                                <li>Professional development support</li>
                            </ul>'
                        ]
                    ];
                    
                    foreach ($sample_jobs as $index => $job):
                        $isEven = $index % 2 == 0;
            ?>
            <div class="job-item <?php echo $isEven ? 'job-item-even' : 'job-item-odd'; ?>">
                <div class="job-image-wrapper">
                    <div class="job-placeholder">
                        <i class="fas <?php echo $job['icon']; ?>"></i>
                    </div>
                    <?php if (!empty($job['badge'])): ?>
                        <div class="job-badge"><?php echo $job['badge']; ?></div>
                    <?php endif; ?>
                </div>
                <div class="job-content">
                    <h3 class="job-title"><?php echo $job['title']; ?></h3>
                    <div class="job-company">
                        <i class="fas fa-building"></i>
                        <span><?php echo $job['company']; ?></span>
                    </div>
                    <div class="job-details-row">
                        <div class="job-detail">
                            <i class="fas fa-money-bill-wave"></i>
                            <span><?php echo $job['salary']; ?></span>
                        </div>
                        <div class="job-detail">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo $job['location']; ?></span>
                        </div>
                        <div class="job-detail">
                            <i class="fas fa-users"></i>
                            <span><?php echo $job['openings']; ?> openings</span>
                        </div>
                    </div>
                    <div class="job-description">
                        <?php echo $job['description']; ?>
                    </div>
                    <div class="job-actions">
                        <button type="button" class="btn-job-details" data-bs-toggle="modal" data-bs-target="#jobModal<?php echo $job['id']; ?>"><?php echo getLangText('View Details', 'មើលលម្អិត'); ?></button>
                    </div>
                </div>
            </div>
            


            <!-- Modal for sample job details -->
            <div class="modal fade" id="jobModal<?php echo $job['id']; ?>" tabindex="-1" aria-labelledby="jobModalLabel<?php echo $job['id']; ?>" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="jobModalLabel<?php echo $job['id']; ?>"><?php echo $job['title']; ?></h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <div class="job-placeholder-modal">
                                        <i class="fas <?php echo $job['icon']; ?>"></i>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <h6 class="fw-bold"><?php echo getLangText('Job Details', 'ព័ត៌មានលម្អិតការងារ'); ?></h6>
                                        <ul class="list-unstyled">
                                            <li class="mb-2">
                                                <i class="fas fa-building text-danger me-2"></i>
                                                <strong><?php echo getLangText('Company:', 'ក្រុមហ៊ុន៖'); ?></strong> <?php echo $job['company']; ?>
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-money-bill-wave text-danger me-2"></i>
                                                <strong><?php echo getLangText('Salary:', 'ប្រាក់ខែ៖'); ?></strong> <?php echo $job['salary']; ?>
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                                <strong><?php echo getLangText('Location:', 'ទីតាំង៖'); ?></strong> <?php echo $job['location']; ?>
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-clock text-danger me-2"></i>
                                                <strong><?php echo getLangText('Job Type:', 'ប្រភេទការងារ៖'); ?></strong> <?php echo getLangText('Full-time', 'ពេញម៉ោង'); ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h5 class="border-bottom pb-2 mb-3"><?php echo getLangText('Job Description', 'ការពិពណ៌នាការងារ'); ?></h5>
                                    <div class="job-full-description mb-4">
                                        <p><?php echo $job['description']; ?></p>
                                        <p><?php echo getLangText('This is an excellent opportunity for professionals looking to advance their career in', 'នេះគឺជាឱកាសដ៏ល្អសម្រាប់អ្នកជំនាញដែលចង់អភិវឌ្ឍអាជីពរបស់ពួកគេក្នុង'); ?> <?php echo strtolower($job['title']); ?>. <?php echo getLangText('The role offers a dynamic work environment with opportunities for growth and development.', 'តួនាទីនេះផ្តល់ជូននូវបរិយាកាសការងារដែលមានភាពរស់រវើកជាមួយនឹងឱកាសសម្រាប់ការរីកចម្រើន និងការអភិវឌ្ឍន៍។'); ?></p>
                                    </div>
                                    
                                    <h5 class="border-bottom pb-2 mb-3"><?php echo getLangText('Requirements', 'តម្រូវការ'); ?></h5>
                                    <div class="job-requirements mb-4">
                                        <?php echo $job['requirements']; ?>
                                    </div>
                                    
                                    <h5 class="border-bottom pb-2 mb-3"><?php echo getLangText('Benefits', 'អត្ថប្រយោជន៍'); ?></h5>
                                    <div class="job-benefits">
                                        <?php echo $job['benefits']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                    endforeach;
                endif;
            } catch(PDOException $e) {
                echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
            }
            ?>
        </div>
        
        <div class="text-center mt-5">
            <a href="index.php?page=popular-jobs" class="btn-view-all">
                <span><?php echo getLangText('View All Career Opportunities', 'មើលឱកាសអាជីពទាំងអស់'); ?></span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>


<div class="container mb-5">
    <div class="row text-center mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-danger"><?php echo getLangText('Latest Announcements', 'សេចក្តីប្រកាសថ្មីៗ'); ?></h2>
            <p class="text-muted"><?php echo getLangText('Stay updated with important information from MACA', 'ទទួលបានព័ត៌មានសំខាន់ៗពី MACA'); ?></p>
        </div>
    </div>
    
    <div class="row">
        <?php
        $announcements = getAnnouncements($pdo, 3);
        if (count($announcements) > 0):
            foreach ($announcements as $announcement):
        ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 hover-shadow">
                <?php if ($announcement['image_path']): ?>
                <img src="<?php echo $announcement['image_path']; ?>" class="card-img-top" alt="<?php echo $announcement['title']; ?>" style="height: 200px; object-fit: cover;">
                <?php endif; ?>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-danger"><?php echo getLangText('Announcement', 'សេចក្តីប្រកាស'); ?></span>
                        <small class="text-muted"><?php echo formatDate($announcement['created_at']); ?></small>
                    </div>
                    <h5 class="card-title"><?php echo $announcement['title']; ?></h5>
                    <p class="card-text"><?php echo truncateText(strip_tags($announcement['content']), 120); ?></p>
                </div>
                <div class="card-footer bg-white border-0">
                    <a href="index.php?page=announcement-detail&id=<?php echo $announcement['id']; ?>" class="btn btn-sm btn-outline-danger">
        <?php echo getLangText('Read More', 'អានបន្ថែម'); ?>
    </a>
                </div>
            </div>
        </div>
        <?php
            endforeach;
        else:
        ?>
        <div class="col-12">
            <div class="alert alert-info"><?php echo getLangText('No announcements available at this time.', 'មិនមានសេចក្តីប្រកាសនៅពេលនេះទេ។'); ?></div>
        </div>
        <?php endif; ?>
    </div>
    
    
    <div class="text-center mt-3">
        <a href="index.php?page=announcements" class="btn btn-outline-danger"><?php echo getLangText('View All Announcements', 'មើលសេចក្តីប្រកាសទាំងអស់'); ?></a>
    </div>
</div>

<div class="container mb-5">
    <div class="row text-center mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-danger"><?php echo getLangText('Latest News', 'ព័ត៌មានថ្មីៗ'); ?></h2>
            <p class="text-muted"><?php echo getLangText('Stay updated with the latest news and events from MACA', 'ទទួលបានព័ត៌មាន និងព្រឹត្តិការណ៍ថ្មីៗពី MACA'); ?></p>
        </div>
    </div>
    
    <div class="row">
        <?php
        $news_items = getNews($pdo, 3);
        if (count($news_items) > 0):
            foreach ($news_items as $news):
        ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 hover-shadow">
                <?php if ($news['image_path']): ?>
                <img src="<?php echo $news['image_path']; ?>" class="card-img-top" alt="<?php echo $news['title']; ?>" style="height: 200px; object-fit: cover;">
                <?php endif; ?>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-danger"><?php echo getLangText('News', 'ព័ត៌មាន'); ?></span>
                        <small class="text-muted"><?php echo formatDate($news['created_at']); ?></small>
                    </div>
                    <h5 class="card-title"><?php echo $news['title']; ?></h5>
                    <p class="card-text"><?php echo $news['summary']; ?></p>
                </div>
                <div class="card-footer bg-white border-0">
                    <a href="index.php?page=news-detail&id=<?php echo $news['id']; ?>" class="btn btn-sm btn-outline-danger"><?php echo getLangText('Read More', 'អានបន្ថែម'); ?></a>
                </div>
            </div>
        </div>
        <?php
            endforeach;
        else:
        ?>
        <div class="col-12">
            <div class="alert alert-info"><?php echo getLangText('No news available at this time.', 'មិនមានព័ត៌មាននៅពេលនេះទេ។'); ?></div>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="text-center mt-3">
        <a href="index.php?page=news" class="btn btn-outline-danger"><?php echo getLangText('View All News', 'មើលព័ត៌មានទាំងអស់'); ?></a>
    </div>
</div>

<div class="bg-danger text-white py-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <h2 class="fw-bold"><?php echo getLangText('Ready to Start Your Educational Journey?', 'ត្រៀមខ្លួនចាប់ផ្តើមដំណើរអប់រំរបស់អ្នកហើយឬនៅ?'); ?></h2>
                <p class="lead"><?php echo getLangText('Contact us today to learn more about our programs and how we can help you achieve your academic and career goals.', 'ទាក់ទងមកយើងថ្ងៃនេះដើម្បីស្វែងយល់បន្ថែមអំពីកម្មវិធីរបស់យើង និងរបៀបដែលយើងអាចជួយអ្នកឱ្យសម្រេចបាននូវគោលដៅសិក្សា និងអាជីពរបស់អ្នក។'); ?></p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="index.php?page=contact" class="btn btn-light text-danger"><?php echo getLangText('Contact Us', 'ទំនាក់ទំនងយើង'); ?></a>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Hero Slideshow Styles */
.hero-slideshow-modern {
    position: relative;
    margin-bottom: 3rem;
    overflow: hidden;
    background-color: #000;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
    border-radius: 12px;
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
}

.slideshow-container-modern {
    position: relative;
    width: 100%;
    height: 500px;
    overflow: hidden;
}

.slide-modern {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 1s ease;
    z-index: 1;
}

.slide-modern.active {
    opacity: 1;
    z-index: 2;
}

.slide-image-container {
    position: absolute;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.slide-image-modern {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 8s ease;
}

.slide-modern.active .slide-image-modern {
    transform: scale(1.1);
}

.slide-content {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    padding: 40px;
    background: linear-gradient(to top, rgba(0,0,0,0.7), rgba(0,0,0,0));
    color: white;
    z-index: 3;
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.8s ease, transform 0.8s ease;
    transition-delay: 0.3s;
}

.slide-modern.active .slide-content {
    opacity: 1;
    transform: translateY(0);
}

.slide-text {
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 20px;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    max-width: 80%;
}

.slide-button {
    display: inline-block;
    padding: 12px 30px;
    background-color: #dc3545;
    color: white;
    text-decoration: none;
    border-radius: 30px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);
}

.slide-button:hover {
    background-color: #c82333;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(220, 53, 69, 0.4);
}

.slide-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 50px;
    height: 50px;
    background-color: rgba(255, 255, 255, 0.2);
    border: none;
    border-radius: 50%;
    color: white;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: all 0.3s ease;
    backdrop-filter: blur(5px);
}

.slide-arrow:hover {
    background-color: rgba(255, 255, 255, 0.3);
    transform: translateY(-50%) scale(1.1);
}

.prev-arrow {
    left: 20px;
}

.next-arrow {
    right: 20px;
}

.slideshow-dots-modern {
    position: absolute;
    bottom: 15px;
    right: 20px;
    display: flex;
    gap: 10px;
    z-index: 10;
}

.dot-modern {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.4);
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.dot-modern.active {
    background-color: #dc3545;
    transform: scale(1.2);
}

.dot-modern:hover {
    background-color: rgba(255, 255, 255, 0.8);
}

.slide-progress-container {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background-color: rgba(255, 255, 255, 0.2);
    z-index: 10;
}

.slide-progress-bar {
    height: 100%;
    width: 0;
    background-color: #dc3545;
    transition: width 0.1s linear;
}

@media (max-width: 991px) {
    .slideshow-container-modern {
        height: 400px;
    }
    
    .slide-text {
        font-size: 1.8rem;
        max-width: 100%;
    }
}

@media (max-width: 768px) {
    .slideshow-container-modern {
        height: 350px;
    }
    
    .slide-content {
        padding: 30px;
    }
    
    .slide-text {
        font-size: 1.5rem;
    }
    
    .slide-button {
        padding: 10px 25px;
    }
    
    .slide-arrow {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
}

@media (max-width: 576px) {
    .slideshow-container-modern {
        height: 300px;
    }
    
    .slide-content {
        padding: 20px;
    }
    
    .slide-text {
        font-size: 1.2rem;
        margin-bottom: 15px;
    }
    
    .slide-button {
        padding: 8px 20px;
        font-size: 14px;
    }
    
    .slide-arrow {
        width: 35px;
        height: 35px;
        font-size: 14px;
    }
    
    .prev-arrow {
        left: 10px;
    }
    
    .next-arrow {
        right: 10px;
    }
}
/* Hero Slideshow Styles */
.hero-slideshow {
    position: relative;
    margin-bottom: 3rem;
    overflow: hidden;
    background-color: #f8f9fa; /* Light background to prevent blank appearance */
    max-width: 1000px; /* Reduced width */
    margin-left: auto;
    margin-right: auto;
}

.slideshow-container {
    width: 100%;
    position: relative;
    max-height: 400px; /* Reduced height */
}

.slide {
    position: absolute;
    width: 100%;
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
}

/* First slide visible by default */
.slide:first-child {
    position: relative;
    opacity: 1;
}

.slide-image {
    width: 100%;
    height: auto;
    max-height: 400px; /* Ensure image doesn't exceed container height */
    display: block;
    object-fit: cover;
}

.slide.active {
    opacity: 1;
    z-index: 2;
}

.slide.active .slide-image {
    animation: slideLeft 1s ease-out;
}

@keyframes slideLeft {
    0% {
        transform: translateX(100%);
    }
    100% {
        transform: translateX(0);
    }
}

/* Navigation Arrows */
.prev, .next {
    cursor: pointer;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: auto;
    padding: 16px;
    color: white;
    font-weight: bold;
    font-size: 18px;
    transition: 0.6s ease;
    border-radius: 0 3px 3px 0;
    user-select: none;
    background-color: rgba(0, 0, 0, 0.3);
    z-index: 10;
}

.next {
    right: 0;
    border-radius: 3px 0 0 3px;
}

.prev {
    left: 0;
    border-radius: 0 3px 3px 0;
}

.prev:hover, .next:hover {
    background-color: rgba(0, 0, 0, 0.8);
}

.slideshow-dots {
    text-align: center;
    position: absolute;
    bottom: 20px;
    width: 100%;
    z-index: 10;
}

.dot {
    height: 12px;
    width: 12px;
    margin: 0 5px;
    background-color: rgba(255, 255, 255, 0.5);
    border-radius: 50%;
    display: inline-block;
    transition: background-color 0.6s ease;
    cursor: pointer;
}

.dot.active, .dot:hover {
    background-color: white;
}

/* New Popular Jobs Section Styles */
.popular-jobs-section-new {
    background-color: #f8f9fa;
    padding: 80px 0;
    position: relative;
}

.section-header {
    position: relative;
    padding-bottom: 30px;
}

.section-icon-container {
    margin-bottom: 20px;
}

.section-icon {
    width: 70px;
    height: 70px;
    background-color: #dc3545;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    color: white;
    font-size: 28px;
    box-shadow: 0 10px 20px rgba(220, 53, 69, 0.3);
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 10px;
    position: relative;
    display: inline-block;
}

.section-subtitle {
    color: #6c757d;
    font-size: 1.1rem;
    max-width: 600px;
    margin: 0 auto;
}

.section-divider {
    width: 80px;
    height: 4px;
    background-color: #dc3545;
    margin: 20px auto 0;
    border-radius: 2px;
}

.jobs-container {
    max-width: 1000px;
    margin: 0 auto;
}

.job-item {
    display: flex;
    background-color: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    margin-bottom: 30px;
    transition: all 0.3s ease;
    position: relative;
}

.job-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
}

.job-item-even {
    flex-direction: row;
}

.job-item-odd {
    flex-direction: row-reverse;
}

.job-image-wrapper {
    width: 300px;
    position: relative;
    overflow: hidden;
}

.job-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.job-item:hover .job-image {
    transform: scale(1.1);
}

.job-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #dc3545;
    color: white;
    font-size: 4rem;
}

.job-placeholder-modal {
    height: 200px;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #dc3545;
    color: white;
    font-size: 3rem;
    border-radius: 8px;
}

.job-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    background-color: #dc3545;
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.8rem;
    box-shadow: 0 5px 10px rgba(0,0,0,0.1);
}

.job-badge-trending {
    background-color: #ff6b35 !important;
}

.job-badge-new {
    background-color: #28a745 !important;
}

.job-badge-hot {
    background-color: #dc3545 !important;
}

.job-badge-urgent {
    background-color: #ffc107 !important;
    color: #000 !important;
}

.job-badge-featured {
    background-color: #6f42c1 !important;
}

.job-item-odd .job-badge {
    right: auto;
    left: 20px;
}

.job-content {
    flex: 1;
    padding: 30px;
    position: relative;
}

.job-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 10px;
    color: #333;
}

.job-company {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    color: #6c757d;
}

.job-company i {
    margin-right: 8px;
    color: #dc3545;
}

.job-details-row {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 15px;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
    padding: 15px 0;
}

.job-detail {
    display: flex;
    align-items: center;
    margin-right: 20px;
    margin-bottom: 5px;
}

.job-detail i {
    margin-right: 8px;
    color: #dc3545;
}

.job-description {
    color: #6c757d;
    margin-bottom: 20px;
    line-height: 1.6;
}

.job-actions {
    display: flex;
    gap: 15px;
}

.btn-job-details, .btn-job-apply {
    padding: 10px 20px;
    border-radius: 30px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    cursor: pointer;
}

.btn-job-details {
    background-color: transparent;
    color: #dc3545;
    border: 2px solid #dc3545;
}

.btn-job-details:hover {
    background-color: rgba(220, 53, 69, 0.1);
}

.btn-job-apply {
    background-color: #dc3545;
    color: white;
    border: 2px solid #dc3545;
}

.btn-job-apply:hover {
    background-color: #c82333;
    border-color: #c82333;
    transform: translateY(-2px);
}

.btn-view-all {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 30px;
    background-color: #dc3545;
    color: white;
    border-radius: 30px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
}

.btn-view-all:hover {
    background-color: #c82333;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(220, 53, 69, 0.4);
}

.btn-view-all i {
    transition: transform 0.3s ease;
}

.btn-view-all:hover i {
    transform: translateX(5px);
}

/* Popular Majors Section - New Design */
.popular-majors-section {
    background-color: #f8f9fa;
    padding: 80px 0;
    position: relative;
}

.majors-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
}

.major-card-new {
    background-color: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.major-card-new:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
}

.major-image-container {
    height: 200px;
    position: relative;
    overflow: hidden;
}

.major-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.major-card-new:hover .major-image {
    transform: scale(1.1);
}

.major-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #dc3545, #fd7e14);
    color: white;
    font-size: 4rem;
}

.major-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(220, 53, 69, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.major-card-new:hover .major-overlay {
    opacity: 1;
}

.btn-explore {
    padding: 10px 20px;
    background-color: white;
    color: #dc3545;
    border-radius: 30px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    transform: translateY(20px);
    opacity: 0;
    border: none;
    cursor: pointer;
}

.major-card-new:hover .btn-explore {
    transform: translateY(0);
    opacity: 1;
    transition: all 0.3s ease 0.1s;
}

.btn-explore:hover {
    background-color: #f8f9fa;
    transform: scale(1.05);
}

.major-content {
    padding: 25px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.major-title {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 15px;
    color: #333;
    position: relative;
    padding-bottom: 10px;
}

.major-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 40px;
    height: 3px;
    background-color: #dc3545;
}

.major-meta {
    margin-bottom: 15px;
}

.major-meta-item {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
    color: #6c757d;
}

.major-meta-item i {
    margin-right: 8px;
    color: #dc3545;
    width: 16px;
}

.major-description {
    color: #6c757d;
    margin-bottom: 15px;
    line-height: 1.6;
    flex: 1;
}

.major-skills {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: auto;
}

.skill-tag {
    padding: 5px 12px;
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.major-placeholder-modal {
    height: 200px;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #dc3545, #fd7e14);
    color: white;
    font-size: 3rem;
    border-radius: 8px;
}

.major-full-description {
    line-height: 1.6;
    color: #333;
}

@media (max-width: 991px) {
    .job-item {
        flex-direction: column !important;
    }
    
    .job-image-wrapper {
        width: 100%;
        height: 200px;
    }
    
    .job-badge {
        right: 20px !important;
        left: auto !important;
    }
}

@media (max-width: 768px) {
    .slideshow-dots {
        bottom: 10px;
    }
    
    .dot {
        height: 10px;
        width: 10px;
        margin: 0 4px;
    }
    
    .prev, .next {
        padding: 10px;
        font-size: 16px;
    }
    
    .majors-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .major-image-container {
        height: 180px;
    }
    
    .major-content {
        padding: 20px;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .job-details-row {
        flex-direction: column;
    }
    
    .job-detail {
        margin-bottom: 10px;
    }
}

@media (max-width: 576px) {
    .majors-grid {
        grid-template-columns: 1fr;
    }
}

.job-badge-trending {
    background-color: #ff6b35 !important;
}

.job-badge-new {
    background-color: #28a745 !important;
}

.job-badge-hot {
    background-color: #dc3545 !important;
}

.job-badge-urgent {
    background-color: #ffc107 !important;
    color: #000 !important;
}

.job-badge-featured {
    background-color: #6f42c1 !important;
}

/* Enhanced Educational Programs Section */
.educational-programs-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    position: relative;
    overflow: hidden;
}

.educational-programs-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="%23dc354520" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
    z-index: 1;
}

.educational-programs-section .container {
    position: relative;
    z-index: 2;
}

/* Enhanced Section Header */
.section-header-enhanced {
    position: relative;
    margin-bottom: 4rem;
}

.section-icon-wrapper {
    position: relative;
    display: inline-block;
    margin-bottom: 2rem;
}

.section-icon-enhanced {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #dc3545, #c82333);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    box-shadow: 0 15px 30px rgba(220, 53, 69, 0.3);
    position: relative;
    z-index: 2;
    animation: float 3s ease-in-out infinite;
}

.icon-glow {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100px;
    height: 100px;
    background: radial-gradient(circle, rgba(220, 53, 69, 0.2) 0%, transparent 70%);
    border-radius: 50%;
    animation: pulse 2s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

@keyframes pulse {
    0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.7; }
    50% { transform: translate(-50%, -50%) scale(1.2); opacity: 0.3; }
}

.section-title-enhanced {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1rem;
    background: linear-gradient(135deg, #333, #666);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.section-subtitle-enhanced {
    font-size: 1.2rem;
    color: #6c757d;
    max-width: 600px;
    margin: 0 auto 2rem;
    line-height: 1.6;
}

.section-divider-enhanced {
    width: 100px;
    height: 4px;
    background: linear-gradient(135deg, #dc3545, #fd7e14);
    margin: 0 auto;
    border-radius: 2px;
    position: relative;
}

.section-divider-enhanced::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 8px;
    height: 8px;
    background: #dc3545;
    border-radius: 50%;
}

/* Enhanced Programs Grid */
.programs-grid-enhanced {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

/* Enhanced Program Cards */
.program-card-enhanced {
    position: relative;
    height: 100%;
    perspective: 1000px;
}

.program-card-inner {
    position: relative;
    height: 100%;
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    transform-style: preserve-3d;
}

.program-card-enhanced:hover .program-card-inner {
    transform: translateY(-15px) rotateX(5deg);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
}

/* Program Header */
.program-header {
    height: 120px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.program-header.blue {
    background: linear-gradient(135deg, #dc3545, #c82333);
}

.program-header.green {
   background: linear-gradient(135deg, #dc3545, #c82333);
}

.program-header.purple {
    background: linear-gradient(135deg, #dc3545, #c82333);
}

.program-header.orange {
    background: linear-gradient(135deg, #dc3545, #c82333);
}

.program-icon-container {
    position: relative;
    z-index: 2;
}

.program-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.program-number {
    position: absolute;
    top: 15px;
    right: 20px;
    color: rgba(255, 255, 255, 0.7);
    font-size: 2rem;
    font-weight: 800;
    z-index: 1;
}

/* Program Content */
.program-content {
    padding: 2rem;
    flex: 1;
}

.program-title {
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #333;
    position: relative;
}

.program-title::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 40px;
    height: 3px;
    background: #dc3545;
    border-radius: 2px;
}

.program-description {
    color: #6c757d;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

/* Program Features */
.program-features {
    margin-bottom: 1.5rem;
}

.feature-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.feature-item i {
    color: #28a745;
    margin-right: 0.5rem;
    font-size: 0.8rem;
}

/* Program Footer */
.program-footer {
    padding: 0 2rem 2rem;
}

.btn-program-learn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 12px 24px;
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
    text-decoration: none;
    border-radius: 30px;
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-program-learn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.btn-program-learn:hover::before {
    left: 100%;
}

.btn-program-learn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(220, 53, 69, 0.4);
}

.btn-program-learn i {
    transition: transform 0.3s ease;
}

.btn-program-learn:hover i {
    transform: translateX(5px);
}

/* Program Overlay */
.program-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.program-overlay.blue {
    background: linear-gradient(135deg, rgba(0, 123, 255, 0.1), rgba(0, 86, 179, 0.1));
}

.program-overlay.green {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(30, 126, 52, 0.1));
}

.program-overlay.purple {
    background: linear-gradient(135deg, rgba(111, 66, 193, 0.1), rgba(90, 50, 163, 0.1));
}

.program-overlay.orange {
    background: linear-gradient(135deg, rgba(253, 126, 20, 0.1), rgba(229, 90, 0, 0.1));
}

.program-card-enhanced:hover .program-overlay {
    opacity: 1;
}

/* Responsive Design */
@media (max-width: 768px) {
    .section-title-enhanced {
        font-size: 2rem;
    }
    
    .programs-grid-enhanced {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .program-content {
        padding: 1.5rem;
    }
    
    
    
    .cta-title {
        font-size: 1.5rem;
    }
}

@media (max-width: 576px) {
    .section-icon-enhanced {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
    
    .section-title-enhanced {
        font-size: 1.8rem;
    }
    
    .program-header {
        height: 100px;
    }
    
    .program-icon {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }
}

.py-5 {
  border-radius: 13px;
  padding: 3rem 3rem;
  padding-top: 3rem !important;
  padding-bottom: 3rem !important;
}
</style>

<!-- Replace the existing slideshow JavaScript with this new script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll(".slide-modern");
    const dots = document.querySelectorAll(".dot-modern");
    const prevArrow = document.querySelector(".prev-arrow");
    const nextArrow = document.querySelector(".next-arrow");
    const progressBar = document.querySelector(".slide-progress-bar");
    
    // If no slides are found, exit early
    if (slides.length === 0) {
        console.log("No slides found in the slideshow");
        return;
    }
    
    let slideIndex = 0;
    let slideInterval;
    let progressInterval;
    
    <?php
    // Get slideshow settings from database
    try {
        $stmt = $pdo->prepare("SELECT * FROM site_settings WHERE setting_key = 'slideshow_speed'");
        $stmt->execute();
        $slideshow_speed_setting = $stmt->fetch();
        $slide_duration = ($slideshow_speed_setting && $slideshow_speed_setting['setting_value']) ? intval($slideshow_speed_setting['setting_value']) * 1000 : 6000;
        
        $stmt = $pdo->prepare("SELECT * FROM site_settings WHERE setting_key = 'slideshow_effect'");
        $stmt->execute();
        $slideshow_effect_setting = $stmt->fetch();
        $slide_effect = ($slideshow_effect_setting && $slideshow_effect_setting['setting_value']) ? $slideshow_effect_setting['setting_value'] : 'fade';
    } catch(PDOException $e) {
        $slide_duration = 6000; // Default to 6 seconds
        $slide_effect = 'fade'; // Default effect
    }
    ?>
    
    const slideDuration = <?php echo $slide_duration; ?>; // Duration from database or default
    const slideEffect = '<?php echo $slide_effect; ?>'; // Effect from database or default
    
    // Set initial state
    showSlide(0);
    
    // Start automatic slideshow
    startSlideshow();
    
    // Function to start automatic slideshow
    function startSlideshow() {
        // Clear any existing intervals
        if (slideInterval) {
            clearInterval(slideInterval);
        }
        if (progressInterval) {
            clearInterval(progressInterval);
        }
        
        // Reset and start progress bar
        resetProgressBar();
        startProgressBar();
        
        // Set interval to change slides
        slideInterval = setInterval(function() {
            nextSlide();
        }, slideDuration);
    }
    
    // Function to reset progress bar
    function resetProgressBar() {
        if (progressBar) {
            progressBar.style.width = '0%';
        }
    }
    
    // Function to start progress bar animation
    function startProgressBar() {
        if (progressBar) {
            let width = 0;
            const increment = 100 / (slideDuration / 100); // Calculate increment for smooth animation
            
            progressInterval = setInterval(function() {
                if (width >= 100) {
                    clearInterval(progressInterval);
                } else {
                    width += increment;
                    progressBar.style.width = width + '%';
                }
            }, 100);
        }
    }
    
    // Function to show a specific slide
    function showSlide(index) {
        // Update slideIndex
        slideIndex = index;
        
        // Handle index bounds
        if (slideIndex >= slides.length) {
            slideIndex = 0;
        }
        if (slideIndex < 0) {
            slideIndex = slides.length - 1;
        }
        
        // Remove active class from all slides and dots
        for (let i = 0; i < slides.length; i++) {
            slides[i].classList.remove("active");
            if (dots[i]) {
                dots[i].classList.remove("active");
            }
        }
        
        // Apply transition effect based on setting
        if (slideEffect === 'fade') {
            // Default fade effect
            slides[slideIndex].classList.add("active");
        } else if (slideEffect === 'slide') {
            // Slide effect
            slides.forEach(slide => {
                slide.style.transition = 'transform 0.5s ease-in-out';
                slide.style.transform = `translateX(-${slideIndex * 100}%)`;
            });
        } else if (slideEffect === 'zoom') {
            // Zoom effect
            slides[slideIndex].style.transform = 'scale(1)';
            slides[slideIndex].classList.add("active");
        }
        
        // Make current dot active
        if (dots[slideIndex]) {
            dots[slideIndex].classList.add("active");
        }
        
        // Reset and restart progress bar
        resetProgressBar();
        startProgressBar();
    }
    
    // Function to advance to next slide
    function nextSlide() {
        showSlide(slideIndex + 1);
    }
    
    // Function to go to previous slide
    function prevSlide() {
        showSlide(slideIndex - 1);
    }
    
    // Set up event listeners for dots
    dots.forEach((dot, index) => {
        dot.addEventListener('click', function() {
            showSlide(index);
            startSlideshow(); // Restart the timer when manually changing slides
        });
    });
    
    // Set up event listeners for arrows
    if (prevArrow) {
        prevArrow.addEventListener('click', function() {
            prevSlide();
            startSlideshow(); // Restart the timer when manually changing slides
        });
    }
    
    if (nextArrow) {
        nextArrow.addEventListener('click', function() {
            nextSlide();
            startSlideshow(); // Restart the timer when manually changing slides
        });
    }
    
    // Pause slideshow on hover
    const slideshowContainer = document.querySelector(".slideshow-container-modern");
    if (slideshowContainer) {
        slideshowContainer.addEventListener('mouseenter', function() {
            clearInterval(slideInterval);
            clearInterval(progressInterval);
        });
        
        slideshowContainer.addEventListener('mouseleave', function() {
            startSlideshow();
        });
    }
    
    // Handle keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') {
            prevSlide();
            startSlideshow();
        } else if (e.key === 'ArrowRight') {
            nextSlide();
            startSlideshow();
        }
    });
    
    // Handle touch events for mobile
    let touchStartX = 0;
    let touchEndX = 0;
    
    if (slideshowContainer) {
        slideshowContainer.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, false);
        
        slideshowContainer.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, false);
    }
    
    function handleSwipe() {
        const swipeThreshold = 50; // Minimum distance for swipe
        
        if (touchEndX < touchStartX - swipeThreshold) {
            // Swipe left, go to next slide
            nextSlide();
            startSlideshow();
        } else if (touchEndX > touchStartX + swipeThreshold) {
            // Swipe right, go to previous slide
            prevSlide();
            startSlideshow();
        }
    }
});
</script>
