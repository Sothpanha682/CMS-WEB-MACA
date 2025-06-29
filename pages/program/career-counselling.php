<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item">Programs</li>
            <li class="breadcrumb-item active" aria-current="page">Career Counselling</li>
        </ol>
    </nav>

    <h1 class="fw-bold text-danger mb-4">Career Counselling</h1>
    
    <div class="row mb-5 align-items-center">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <img src="/assets/images/logomaca.png" alt="Career Counselling" class="img-fluid rounded shadow-sm" onerror="this.src='https://scontent.fpnh11-1.fna.fbcdn.net/v/t39.30808-6/326250982_1536743920139114_9196559976363727837_n.jpg?_nc_cat=107&ccb=1-7&_nc_sid=6ee11a&_nc_eui2=AeH-HrvvX9odH9Eo1OMPO92sx1W6y2B-21HHVbrLYH7bUcdx28rT5WYIKdJZutcww4z7nkCizU7LKk4crySvFI3h&_nc_ohc=ZCWRsz-ecXcQ7kNvwGkOi2O&_nc_oc=Adksgm8QzUpyU_4BWuXFcwu3a43aFodLUAA4ophLiXZouDxskBPTmAT6aUr6wYndX1c&_nc_zt=23&_nc_ht=scontent.fpnh11-1.fna&_nc_gid=pmn1iWKIR08AkGwBSKj0Ew&oh=00_AfOJG7yz-EOeuk3r24lrSSISnW1pLNqfGpZahWEXbLEXvg&oe=6862CA0C'">
        </div>
        <div class="col-lg-6">
            <h2 class="text-danger mb-3">Find Your Path to Success</h2>
            <p class="lead">Get expert guidance to make informed decisions about your academic and career path.</p>
            <p>Our career counseling services are designed to help you explore your interests, assess your skills, and identify career paths that align with your goals and values. Our experienced counselors provide personalized guidance to help you navigate your educational and professional journey.</p>
            <ul class="list-group list-group-flush mb-4">
                <li class="list-group-item"><i class="fas fa-check-circle text-danger me-2"></i> One-on-one career counseling sessions</li>
                <li class="list-group-item"><i class="fas fa-check-circle text-danger me-2"></i> Career assessment tools</li>
                <li class="list-group-item"><i class="fas fa-check-circle text-danger me-2"></i> Resume and cover letter review</li>
                <li class="list-group-item"><i class="fas fa-check-circle text-danger me-2"></i> Interview preparation</li>
                <li class="list-group-item"><i class="fas fa-check-circle text-danger me-2"></i> Job search strategies</li>
            </ul>
            <a href="index.php?page=contact" class="btn btn-danger">Schedule a Consultation</a>
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="text-danger mb-4 text-center">Our Career Counseling Process</h2>
                    <div class="row text-center">
                        <div class="col-md-3 mb-4">
                            <div class="icon-circle bg-danger text-white mx-auto mb-4">
                                <i class="fas fa-search fa-2x"></i>
                            </div>
                            <h4>1. Assessment</h4>
                            <p>Identify your interests, skills, values, and personality traits.</p>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="icon-circle bg-danger text-white mx-auto mb-4">
                                <i class="fas fa-lightbulb fa-2x"></i>
                            </div>
                            <h4>2. Exploration</h4>
                            <p>Explore career options that align with your assessment results.</p>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="icon-circle bg-danger text-white mx-auto mb-4">
                                <i class="fas fa-map-marked-alt fa-2x"></i>
                            </div>
                            <h4>3. Planning</h4>
                            <p>Develop a personalized action plan to achieve your career goals.</p>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="icon-circle bg-danger text-white mx-auto mb-4">
                                <i class="fas fa-handshake fa-2x"></i>
                            </div>
                            <h4>4. Implementation</h4>
                            <p>Execute your plan with ongoing support and guidance.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <h2 class="text-danger mb-4 text-center">Our Career Counseling Services</h2>
    
    <div class="row mb-5">
        <?php
        $services = [
            [
                'title' => 'Individual Career Counseling',
                'description' => 'One-on-one sessions with a career counselor to discuss your career goals and develop a personalized plan.',
                'icon' => 'user'
            ],
            [
                'title' => 'Career Assessment',
                'description' => 'Take assessment tests to identify your interests, skills, values, and personality traits to find suitable career options.',
                'icon' => 'clipboard-check'
            ],
            [
                'title' => 'Resume & Cover Letter Review',
                'description' => 'Get expert feedback on your resume and cover letter to make them stand out to potential employers.',
                'icon' => 'file-alt'
            ],
            [
                'title' => 'Interview Preparation',
                'description' => 'Practice interviews with feedback and tips to help you perform your best during job interviews.',
                'icon' => 'comments'
            ],
            [
                'title' => 'Job Search Strategies',
                'description' => 'Learn effective strategies for finding job opportunities and networking with professionals in your field.',
                'icon' => 'search'
            ],
            [
                'title' => 'Career Transition Guidance',
                'description' => 'Get support and guidance if you\'re considering changing careers or industries.',
                'icon' => 'exchange-alt'
            ]
        ];
        
        foreach ($services as $service) {
            echo '<div class="col-md-6 col-lg-4 mb-4">';
            echo '<div class="card h-100 shadow-sm hover-shadow">';
            echo '<div class="card-body text-center">';
            echo '<div class="icon-circle bg-danger text-white mx-auto mb-4">';
            echo '<i class="fas fa-' . $service['icon'] . ' fa-2x"></i>';
            echo '</div>';
            echo '<h4>' . $service['title'] . '</h4>';
            echo '<p>' . $service['description'] . '</p>';
            echo '</div>';
            echo '<div class="card-footer bg-white text-center">';
            echo '<a href="index.php?page=contact" class="btn btn-outline-danger">Learn More</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
    
    <div class="row mb-5">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body p-4">
                    <h2 class="text-danger mb-4">Success Stories</h2>
                    <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                            $testimonials = [
                                [
                                    'name' => 'Sarah Johnson',
                                    'position' => 'Software Developer',
                                    'testimonial' => 'The career counseling services at MACA helped me discover my passion for programming. With their guidance, I was able to choose the right courses and internships, which led to my dream job as a software developer.',
                                    'image' => 'testimonial-1.jpg'
                                ],
                                [
                                    'name' => 'Michael Chen',
                                    'position' => 'Marketing Manager',
                                    'testimonial' => 'I was unsure about my career path after graduation. The career assessment at MACA helped me identify my strengths and interests, leading me to pursue a career in marketing. Their resume review and interview preparation services were invaluable in landing my current position.',
                                    'image' => 'testimonial-2.jpg'
                                ],
                                [
                                    'name' => 'Emily Rodriguez',
                                    'position' => 'Healthcare Administrator',
                                    'testimonial' => 'After working in a different field for several years, I wanted to transition to healthcare administration. MACA\'s career transition guidance helped me identify transferable skills and develop a plan to make the switch. I\'m now happily employed in my new field.',
                                    'image' => 'testimonial-3.jpg'
                                ]
                            ];
                            
                            foreach ($testimonials as $index => $testimonial) {
                                echo '<div class="carousel-item ' . ($index === 0 ? 'active' : '') . '">';
                                echo '<div class="row align-items-center">';
                                echo '<div class="col-md-3 text-center">';
                                echo '<img src="assets/images/' . $testimonial['image'] . '" class="rounded-circle img-fluid mx-auto" style="max-width: 150px;" alt="' . $testimonial['name'] . '" onerror="this.src=\'' . substr($testimonial['name'], 0, 1) . '\'">';
                                echo '</div>';
                                echo '<div class="col-md-9">';
                                echo '<div class="p-3">';
                                echo '<p class="lead"><i class="fas fa-quote-left text-danger me-2"></i>' . $testimonial['testimonial'] . '<i class="fas fa-quote-right text-danger ms-2"></i></p>';
                                echo '<p class="mb-0 fw-bold">' . $testimonial['name'] . '</p>';
                                echo '<p class="text-muted">' . $testimonial['position'] . '</p>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon bg-danger rounded-circle" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon bg-danger rounded-circle" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card bg-danger text-white">
                <div class="card-body text-center py-5">
                    <h2 class="mb-3">Ready to Take the Next Step in Your Career?</h2>
                    <p class="lead mb-4">Our career counselors are here to help you navigate your educational and professional journey.</p>
                    <a href="index.php?page=contact" class="btn btn-light text-danger btn-lg">Schedule a Consultation Today</a>
                </div>
            </div>
        </div>
    </div>
</div>
