<div class="container py-4">
    <h1 class="fw-bold text-danger mb-4"><?php echo getLangText('About MACA', 'អំពី MACA'); ?></h1>
    
    <div class="row mb-5">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <?php
            // Get about banner image from database
            try {
                $stmt = $pdo->prepare("SELECT * FROM site_settings WHERE setting_key = 'about_banner' LIMIT 1");
                $stmt->execute();
                $about_banner = $stmt->fetch();
                
                if ($about_banner && $about_banner['setting_value']) {
                    echo '<img src="' . $about_banner['setting_value'] . '" alt="MACA Campus" class="img-fluid rounded shadow-sm">';
                } else {
                    echo '<img src="assets/images/about-banner.jpg" alt="MACA Campus" class="img-fluid rounded shadow-sm" onerror="this.src=\'https://via.placeholder.com/600x400?text=MACA+Campus\'">';
                }
            } catch(PDOException $e) {
                echo '<img src="assets/images/about-banner.jpg" alt="MACA Campus" class="img-fluid rounded shadow-sm" onerror="this.src=\'https://via.placeholder.com/600x400?text=MACA+Campus\'">';
            }
            ?>
        </div>
        <div class="col-lg-6">
            <h2 class="text-danger mb-3"><?php echo getLangText('Our Mission', 'បេសកកម្មរបស់យើង'); ?></h2>
            <p class="lead"><?php echo getLangText('Empowering students through quality education and career guidance.', 'ផ្តល់អំណាចដល់សិស្សតាមរយៈការអប់រំគុណភាពខ្ពស់ និងការណែនាំអាជីព។'); ?></p>
            <p><?php echo getLangText('At MACA, we believe that education is the key to personal and professional growth. Our mission is to provide accessible, high-quality educational resources and career guidance to help students make informed decisions about their future.', 'នៅ MACA យើងជឿថាការអប់រំគឺជាគន្លឹះសម្រាប់ការរីកចម្រើនផ្ទាល់ខ្លួន និងវិជ្ជាជីវៈ។ បេសកកម្មរបស់យើងគឺផ្តល់ធនធានអប់រំគុណភាពខ្ពស់ និងការណែនាំអាជីពដែលអាចចូលប្រើបាន ដើម្បីជួយសិស្សធ្វើការសម្រេចចិត្តដោយមានព័ត៌មានគ្រប់គ្រាន់អំពីអនាគតរបស់ពួកគេ។'); ?></p>
            <p><?php echo getLangText('Founded in 2005, MACA has grown from a small career counseling center to a comprehensive educational institution serving thousands of students each year.', 'បង្កើតឡើងនៅឆ្នាំ 2005 MACA បានរីកចម្រើនពីមជ្ឈមណ្ឌលប្រឹក្សាអាជីពតូចមួយ ទៅជាស្ថាប័នអប់រំគ្រប់ជ្រុងជ្រោយដែលបម្រើសិស្សរាប់ពាន់នាក់ជារៀងរាល់ឆ្នាំ។'); ?></p>
        </div>
    </div>
    
    <div class="row mb-5">
    <div class="col-12">
        <div class="mission-section">
            <div class="text-center mb-5">
                <h2 class="text-danger mb-3 position-relative d-inline-block">
                    <?php echo getLangText('Our Mission', 'បេសកកម្មរបស់យើង'); ?>
                    <div class="title-underline"></div>
                </h2>
                <p class="lead text-muted"><?php echo getLangText('Empowering the next generation through comprehensive education and career development', 'ផ្តល់អំណាចដល់មនុស្សជំនាន់ក្រោយតាមរយៈការអប់រំ និងការអភិវឌ្ឍអាជីពគ្រប់ជ្រុងជ្រោយ'); ?></p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="mission-card h-100">
                        <div class="mission-icon-wrapper">
                            <div class="mission-icon">
                                <i class="fas fa-compass"></i>
                            </div>
                        </div>
                        <div class="mission-content">
                            <h4 class="mission-title"><?php echo getLangText('Career Guidance', 'ការណែនាំអាជីព'); ?></h4>
                            <p class="mission-description"><?php echo getLangText('Guide youth in choosing their majors and careers.', 'ណែនាំយុវជនក្នុងការជ្រើសរើសមុខវិជ្ជា និងអាជីពរបស់ពួកគេ។'); ?></p>
                        </div>
                        <div class="mission-number">01</div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="mission-card h-100">
                        <div class="mission-icon-wrapper">
                            <div class="mission-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                        </div>
                        <div class="mission-content">
                            <h4 class="mission-title"><?php echo getLangText('Skills Training', 'ការបណ្តុះបណ្តាលជំនាញ'); ?></h4>
                            <p class="mission-description"><?php echo getLangText('Provide short courses and skills training.', 'ផ្តល់វគ្គសិក្សាខ្លីៗ និងការបណ្តុះបណ្តាលជំនាញ។'); ?></p>
                        </div>
                        <div class="mission-number">02</div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="mission-card h-100">
                        <div class="mission-icon-wrapper">
                            <div class="mission-icon">
                                <i class="fas fa-hands-helping"></i>
                            </div>
                        </div>
                        <div class="mission-content">
                            <h4 class="mission-title"><?php echo getLangText('Practical Experience', 'បទពិសោធន៍ជាក់ស្តែង'); ?></h4>
                            <p class="mission-description"><?php echo getLangText('Offer practical training, internships, and apprenticeships.', 'ផ្តល់ការបណ្តុះបណ្តាលជាក់ស្តែង កម្មសិក្សា និងការហ្វឹកហាត់។'); ?></p>
                        </div>
                        <div class="mission-number">03</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.mission-section {
    padding: 60px 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 20px;
    position: relative;
    overflow: hidden;
}

.mission-section::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle, rgba(220, 53, 69, 0.05) 0%, transparent 70%);
    z-index: 1;
}

.mission-section > * {
    position: relative;
    z-index: 2;
}

.title-underline {
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, #dc3545, #ff6b7a);
    border-radius: 2px;
}

.mission-card {
    background: white;
    border-radius: 20px;
    padding: 40px 30px;
    text-align: center;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(220, 53, 69, 0.1);
}

.mission-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #dc3545, #ff6b7a);
    transform: scaleX(0);
    transition: transform 0.4s ease;
}

.mission-card:hover::before {
    transform: scaleX(1);
}

.mission-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 60px rgba(220, 53, 69, 0.15);
}

.mission-icon-wrapper {
    margin-bottom: 30px;
    position: relative;
}

.mission-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #dc3545, #ff6b7a);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3);
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
}

.mission-icon::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), transparent);
    border-radius: 50%;
}

.mission-card:hover .mission-icon {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 12px 35px rgba(220, 53, 69, 0.4);
}

.mission-icon i {
    font-size: 32px;
    color: white;
    z-index: 1;
    position: relative;
}

.mission-content {
    margin-bottom: 20px;
}

.mission-title {
    color: #2c3e50;
    font-weight: 700;
    margin-bottom: 15px;
    font-size: 1.4rem;
    transition: color 0.3s ease;
}

.mission-card:hover .mission-title {
    color: #dc3545;
}

.mission-description {
    color: #6c757d;
    line-height: 1.6;
    font-size: 1rem;
    margin: 0;
}

.mission-number {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 40px;
    height: 40px;
    background: rgba(220, 53, 69, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 18px;
    color: #dc3545;
    transition: all 0.3s ease;
}

.mission-card:hover .mission-number {
    background: #dc3545;
    color: white;
    transform: scale(1.1);
}

@media (max-width: 768px) {
    .mission-section {
        padding: 40px 20px;
    }
    
    .mission-card {
        padding: 30px 20px;
        margin-bottom: 20px;
    }
    
    .mission-icon {
        width: 70px;
        height: 70px;
    }
    
    .mission-icon i {
        font-size: 28px;
    }
    
    .mission-title {
        font-size: 1.2rem;
    }
}
</style>
    
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="text-danger mb-4 text-center"><?php echo getLangText('Our Team', 'ក្រុមរបស់យើង'); ?></h2>
            <div class="row">
                <?php
                // Get team members from database
                try {
                    $stmt = $pdo->prepare("SELECT * FROM team_members WHERE is_active = 1 ORDER BY display_order ASC, name ASC");
                    $stmt->execute();
                    $team_members = $stmt->fetchAll();
                    
                    if (count($team_members) > 0) {
                        foreach ($team_members as $member) {
                            echo '<div class="col-md-6 col-lg-3 mb-4">';
                            echo '<div class="card h-100 shadow-sm">';
                            
                            if (!empty($member['image_path'])) {
                                echo '<img src="' . $member['image_path'] . '" class="card-img-top" alt="' . $member['name'] . '" style="height: 250px; object-fit: cover;">';
                            } else {
                                echo '<img src="https://via.placeholder.com/300x300?text=' . str_replace(' ', '+', $member['name']) . '" class="card-img-top" alt="' . $member['name'] . '" style="height: 250px; object-fit: cover;">';
                            }
                            
                            echo '<div class="card-body text-center">';
                            echo '<h5 class="card-title text-danger">' . (function_exists('getCurrentLanguage') && getCurrentLanguage() == 'kh' && !empty($member['name_km']) ? $member['name_km'] : $member['name']) . '</h5>';
                            echo '<p class="card-subtitle mb-2 text-muted">' . (function_exists('getCurrentLanguage') && getCurrentLanguage() == 'kh' && !empty($member['position_km']) ? $member['position_km'] : $member['position']) . '</p>';
                            echo '<p class="card-text small">' . (function_exists('getCurrentLanguage') && getCurrentLanguage() == 'kh' && !empty($member['bio_km']) ? $member['bio_km'] : $member['bio']) . '</p>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        // Fallback to static team members if none in database
                        $default_team = [
                            [
                                'name' => getLangText('Dr. Sarah Johnson', 'បណ្ឌិត សារ៉ា ចនសុន'),
                                'position' => getLangText('Founder & Director', 'ស្ថាបនិក និងនាយក'),
                                'bio' => getLangText('With over 20 years of experience in education, Dr. Johnson founded MACA to help students navigate their educational journey.', 'ជាមួយនឹងបទពិសោធន៍ជាង 20 ឆ្នាំក្នុងវិស័យអប់រំ បណ្ឌិត ចនសុន បានបង្កើត MACA ដើម្បីជួយសិស្សក្នុងដំណើរការអប់រំរបស់ពួកគេ។'),
                                'image' => 'team-1.jpg'
                            ],
                            [
                                'name' => getLangText('Prof. Michael Chen', 'សាស្ត្រាចារ្យ មីឆែល ឆេន'),
                                'position' => getLangText('Academic Advisor', 'ទីប្រឹក្សាសិក្សា'),
                                'bio' => getLangText('Prof. Chen specializes in career development and helps students align their education with their career goals.', 'សាស្ត្រាចារ្យ ឆេន ឯកទេសក្នុងការអភិវឌ្ឍអាជីព និងជួយសិស្សតម្រឹមការអប់រំរបស់ពួកគេជាមួយគោលដៅអាជីពរបស់ពួកគេ។'),
                                'image' => 'team-2.jpg'
                            ],
                            [
                                'name' => getLangText('Emily Rodriguez', 'អេមីលី រ៉ូឌ្រីហ្គេស'),
                                'position' => getLangText('Career Counselor', 'អ្នកប្រឹក្សាអាជីព'),
                                'bio' => getLangText('Emily has helped hundreds of students find internships and job opportunities in their desired fields.', 'អេមីលី បានជួយសិស្សរាប់រយនាក់ឱ្យរកឃើញកម្មសិក្សា និងឱកាសការងារក្នុងវិស័យដែលពួកគេចង់បាន។'),
                                'image' => 'team-3.jpg'
                            ],
                            [
                                'name' => getLangText('David Kim', 'ដេវីដ គីម'),
                                'position' => getLangText('Online Learning Director', 'នាយកសិក្សាតាមអនឡាញ'),
                                'bio' => getLangText('David oversees our online learning platform and ensures students have access to quality education from anywhere.', 'ដេវីដ ត្រួតពិនិត្យវេទិកាសិក្សាតាមអនឡាញរបស់យើង និងធានាថាសិស្សអាចចូលប្រើការអប់រំគុណភាពខ្ពស់ពីគ្រប់ទីកន្លែង។'),
                                'image' => 'team-4.jpg'
                            ]
                        ];
                        
                        foreach ($default_team as $member) {
                            echo '<div class="col-md-6 col-lg-3 mb-4">';
                            echo '<div class="card h-100 shadow-sm">';
                            echo '<img src="assets/images/' . $member['image'] . '" class="card-img-top" alt="' . $member['name'] . '" onerror="this.src=\'https://via.placeholder.com/300x300?text=' . str_replace(' ', '+', $member['name']) . '\'" style="height: 250px; object-fit: cover;">';
                            echo '<div class="card-body text-center">';
                            echo '<h5 class="card-title text-danger">' . $member['name'] . '</h5>';
                            echo '<p class="card-subtitle mb-2 text-muted">' . $member['position'] . '</p>';
                            echo '<p class="card-text small">' . $member['bio'] . '</p>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    }
                } catch(PDOException $e) {
                    echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
                }
                ?>
            </div>
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col-12">
            <div class="card bg-danger text-white">
                <div class="card-body text-center py-5">
                    <h2 class="mb-3"><?php echo getLangText('Join Our Educational Community', 'ចូលរួមសហគមន៍អប់រំរបស់យើង'); ?></h2>
                    <p class="lead mb-4"><?php echo getLangText('Whether you\'re a student looking for guidance or an educator interested in joining our team, we\'d love to hear from you.', 'មិនថាអ្នកជាសិស្សដែលកំពុងស្វែងរកការណែនាំ ឬជាអ្នកអប់រំដែលចាប់អារម្មណ៍ក្នុងការចូលរួមក្រុមរបស់យើង យើងចង់ឮពីអ្នក។'); ?></p>
                    <a href="index.php?page=contact" class="btn btn-light text-danger btn-lg"><?php echo getLangText('Contact Us Today', 'ទាក់ទងយើងថ្ងៃនេះ'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
