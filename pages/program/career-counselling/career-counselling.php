
    <style>
        /* General Setup */
        :root {
            --maca-red: #dc3545;
            --gray-800: #1f2937;
            --gray-600: #4b5563;
            --gray-50: #f9fafb;
            --white: #ffffff;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--white);
            color: var(--gray-800);
            margin: 0;
            line-height: 1.6;
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }

        /* Layout */
        .containerccs {
            width: 100%;
            max-width: 1140px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
            text-align: center; /* Added to center text within this container */
        }

        section {
            padding-top: 5rem;
            padding-bottom: 5rem;
        }

        .bg-gray-50 {
            background-color: var(--gray-50);
        }

        /* Typography */
        h1, h2, h3, h4 {
            font-weight: 800;
            line-height: 1.2;
        }
        .text-maca-red {
            color: var(--maca-red);
        }

        /* Hero Section */
        .herocc {
            min-height: 80vh;
            display: flex;
            align-items: center;
            text-align: center;
        }
        .herocc h1 {
            font-size: 2.5rem;
        }
        .herocc h2 {
            font-size: 2.5rem;
        }
        .herocc p {
            font-size: 1.125rem;
            color: var(--gray-600);
            max-width: 42rem;
            margin: 1.5rem auto 0;
        }
        .herocc .cta-button {
            display: inline-block;
            background-color: var(--maca-red);
            color: var(--white);
            font-weight: 700;
            padding: 0.75rem 2rem;
            border-radius: 9999px;
            text-decoration: none;
            font-size: 1.125rem;
            margin-top: 2rem;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .herocc .cta-button:hover {
            background-color: #c82333;
            transform: scale(1.1);
        }
        
        /* Section Heading */
        .section-heading {
            text-align: center;
            margin-bottom: 3rem;
        }
        .section-heading h2 {
            font-size: 2rem;
            color: var(--gray-800);
        }
        .section-heading p {
            color: var(--maca-red);
            font-weight: 600;
            margin-top: 0.25rem;
        }
        .section-heading .divider {
            width: 6rem;
            height: 4px;
            background-color: var(--maca-red);
            margin: 1rem auto 0;
            border-radius: 2px;
        }

        /* About Section */
        .about-grid {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            margin: 0 -1rem;
        }
        .about-grid > div {
            width: 100%;
            padding: 0 1rem;
        }
        .about-story, .about-mission {
            background-color: var(--white);
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }
        .about-story h3 {
            color: var(--maca-red);
        }
        .about-story p {
            color: var(--gray-600);
        }
        .about-mission {
            background-color: var(--maca-red);
            color: var(--white);
            transition: transform 0.5s, box-shadow 0.5s;
        }
        .about-mission:hover {
            transform: rotate(0) scale(1.05);
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        }
        
        /* Services Section */
        .services-grid {
            display: grid;
            gap: 2rem;
        }
        .service-card {
            background-color: var(--gray-50);
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.5s, box-shadow 0.5s;
        }
        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }
        .service-card .service-number {
            font-size: 3rem;
            font-weight: 800;
            color: var(--maca-red);
            opacity: 0.៩;
            margin-bottom: 0.5rem;
        }
        .service-card h3 {
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
        }
        .service-card p {
            color: var(--gray-600);
        }

        /* Counselling Section */
        .counselling-wrapper {
            max-width: 56rem;
            margin: 0 auto;
        }
        .counselling-box {
            background-color: var(--white);
            border-radius: 0.5rem;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
            padding: 2rem;
        }
        .counselling-grid {
            display: grid;
            gap: 2rem;
            text-align: center;
        }
        .counselling-feature .icon-wrapper {
            background-color: var(--maca-red);
            color: var(--white);
            width: 4rem;
            height: 4rem;
            margin: 0 auto 1rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .counselling-feature .icon-wrapper svg {
            width: 2rem;
            height: 2rem;
        }
        .counselling-feature h4 {
            font-size: 1.25rem;
            color: var(--gray-800);
        }
        .counselling-feature p {
            color: var(--gray-600);
            margin-top: 0.5rem;
        }
        
        /* Media Queries for Responsiveness */
        @media (min-width: 768px) {
            .herocc h1, .herocc h2 {
                font-size: 4rem;
            }
            .about-grid > div {
                width: 50%;
            }
            .about-mission {
                 transform: rotate(2deg);
            }
            .services-grid, .counselling-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            .counselling-box {
                padding: 3rem;
            }
        }

        /* Keyframe animations */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Animation utility classes */
        .animate-fade-in-down { animation: fadeInDown 0.8s ease-out forwards; }
        .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
        .animation-delay-200 { animation-delay: 200ms; }
        .animation-delay-400 { animation-delay: 400ms; }
        .animation-delay-600 { animation-delay: 600ms; }
        
        .hidden-for-animation { opacity: 0; }
    </style>


    <!-- Main Content -->
    <main>
        <!-- Hero Section -->
        <section class="herocc">
            <div class="containerccs">
                <h1 class="animate-fade-in-down">
                    <?php echo getLangText('Find Your ', 'ស្វែងរក'); ?><span class="text-maca-red"><?php echo getLangText('Right Path', 'ផ្លូវត្រូវ'); ?></span><?php echo getLangText(' with Expert', 'ជាមួយអ្នកជំនាញ'); ?>
                </h1>
                <h2 class="text-maca-red animate-fade-in-down animation-delay-200"><?php echo getLangText('Career Counselling', 'ការប្រឹក្សាអាជីព'); ?></h2>
                <p class="animate-fade-in-up animation-delay-400">
                    <?php echo getLangText('Get expert guidance to make informed decisions about your academic and career path with personalized advice.', 'ទទួលបានការណែនាំពីអ្នកជំនាញដើម្បីធ្វើការសម្រេចចិត្តប្រកបដោយការយល់ដឹងអំពីផ្លូវសិក្សា និងអាជីពរបស់អ្នកជាមួយនឹងដំបូន្មានផ្ទាល់ខ្លួន។'); ?>
                </p>
                <div class="animate-fade-in-up animation-delay-600">
                    <a href="index.php?page=contact" class="cta-button">
                        <?php echo getLangText('Contact Us', 'ទំនាក់ទំនងយើង'); ?>
                    </a>
                </div>
            </div>
        </section>

        <!-- About MACA Section -->
        <section id="about" class="bg-gray-50">
            <div class="containerccs">
                <div class="section-heading hidden-for-animation">
                    <h2><?php echo getLangText('About MACA', 'អំពី MACA'); ?></h2>
                    <p><?php echo getLangText('Empowering Cambodia\'s Future Leaders', 'ពង្រឹងភាពជាអ្នកដឹកនាំនាពេលអនាគតរបស់កម្ពុជា'); ?></p>
                    <div class="divider"></div>
                </div>
                <div class="about-grid">
                    <div class="hidden-for-animation">
                        <div class="about-story">
                            <h3><?php echo getLangText('Our Story', 'រឿងរបស់យើង'); ?></h3>
                            <p>
                                <?php echo getLangText('At MACA, we believe that education is the key to personal and professional growth. Our mission is to provide accessible, high-quality educational resources and career guidance to help students make informed decisions about their future.', 'នៅ MACA យើងជឿជាក់ថាការអប់រំគឺជាគន្លឹះនៃការរីកចម្រើនផ្ទាល់ខ្លួន និងអាជីព។ បេសកកម្មរបស់យើងគឺផ្តល់ធនធានអប់រំដែលមានគុណភាពខ្ពស់ និងការណែនាំអាជីព ដើម្បីជួយសិស្សធ្វើការសម្រេចចិត្តប្រកបដោយការយល់ដឹងអំពីអនាគតរបស់ពួកគេ។'); ?>
                            </p>
                            <p>
                                <?php echo getLangText('MACA was established in 2015 by a group of young Cambodians who graduated from overseas, driven by a passion to empower the next generation through comprehensive education and career development.', 'MACA ត្រូវបានបង្កើតឡើងក្នុងឆ្នាំ 2015 ដោយក្រុមយុវជនខ្មែរដែលបានបញ្ចប់ការសិក្សាពីបរទេស ដោយជំរុញដោយចំណង់ចំណូលចិត្តក្នុងការផ្តល់អំណាចដល់មនុស្សជំនាន់ក្រោយតាមរយៈការអប់រំ និងការអភិវឌ្ឍន៍អាជីពដ៏ទូលំទូលាយ។'); ?>
                            </p>
                        </div>
                    </div>
                    <div class="hidden-for-animation">
                        <div class="about-mission">
                           <h3><?php echo getLangText('Our Mission', 'បេសកកម្មរបស់យើង'); ?></h3>
                           <p>
                               <?php echo getLangText('Empowering the next generation through comprehensive education and career development. We are committed to guiding youth in choosing their majors and careers, providing essential skills, and offering invaluable practical experience.', 'ពង្រឹងភាពជាអ្នកដឹកនាំជំនាន់ក្រោយតាមរយៈការអប់រំ និងការអភិវឌ្ឍន៍អាជីពដ៏ទូលំទូលាយ។ យើងប្តេជ្ញាណែនាំយុវជនក្នុងការជ្រើសរើសជំនាញ និងអាជីពរបស់ពួកគេ ផ្តល់ជំនាញសំខាន់ៗ និងផ្តល់បទពិសោធន៍ជាក់ស្តែងដ៏មានតម្លៃ។'); ?>
                           </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section id="services">
            <div class="containerccs">
                <div class="section-heading hidden-for-animation">
                    <h2><?php echo getLangText('What We Offer', 'អ្វីដែលយើងផ្តល់ជូន'); ?></h2>
                    <p><?php echo getLangText('A Holistic Approach to Career Development', 'វិធីសាស្រ្តរួមក្នុងការអភិវឌ្ឍន៍អាជីព'); ?></p>
                    <div class="divider"></div>
                </div>
                <div class="services-grid">
                    <!-- Service Card 1 -->
                    <div class="service-card hidden-for-animation">
                        <div class="service-number">01</div>
                        <h3><?php echo getLangText('Career Guidance', 'ការណែនាំអាជីព'); ?></h3>
                        <p><?php echo getLangText('We help you navigate the complexities of career choices, aligning your passions and skills with future opportunities.', 'យើងជួយអ្នកក្នុងការរុករកភាពស្មុគស្មាញនៃការជ្រើសរើសអាជីព ដោយតម្រូវចំណង់ចំណូលចិត្ត និងជំនាញរបស់អ្នកជាមួយនឹងឱកាសនាពេលអនាគត។'); ?></p>
                    </div>
                    <!-- Service Card 2 -->
                    <div class="service-card hidden-for-animation">
                        <div class="service-number">02</div>
                        <h3><?php echo getLangText('Skills Training', 'ការបណ្តុះបណ្តាលជំនាញ'); ?></h3>
                        <p><?php echo getLangText('Our targeted short courses provide you with the in-demand skills needed to excel in today\'s competitive job market.', 'វគ្គសិក្សារយៈពេលខ្លីរបស់យើងផ្តល់ឱ្យអ្នកនូវជំនាញដែលត្រូវការដើម្បីពូកែនៅក្នុងទីផ្សារការងារដែលមានការប្រកួតប្រជែងនាពេលបច្ចុប្បន្ននេះ។'); ?></p>
                    </div>
                    <!-- Service Card 3 -->
                    <div class="service-card hidden-for-animation">
                        <div class="service-number">03</div>
                        <h3><?php echo getLangText('Practical Experience', 'បទពិសោធន៍ជាក់ស្តែង'); ?></h3>
                        <p><?php echo getLangText('Gain a competitive edge with hands-on training, internships, and apprenticeship programs with our industry partners.', 'ទទួលបានអត្ថប្រយោជន៍ប្រកួតប្រជែងជាមួយនឹងការបណ្តុះបណ្តាលជាក់ស្តែង កម្មសិក្សា និងកម្មវិធីសិក្ខាកាមជាមួយដៃគូឧស្សាហកម្មរបស់យើង។'); ?></p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Counselling Section -->
        <section id="counselling" class="bg-gray-50">
            <div class="containerccs">
                <div class="section-heading hidden-for-animation">
                    <h2><?php echo getLangText('Personalized Career Counselling', 'ការប្រឹក្សាអាជីពផ្ទាល់ខ្លួន'); ?></h2>
                    <p><?php echo getLangText('Your Journey, Our Expertise', 'ដំណើររបស់អ្នក ជំនាញរបស់យើង'); ?></p>
                    <div class="divider"></div>
                </div>
                <div class="counselling-wrapper">
                    <div class="counselling-box">
                        <div class="counselling-grid">
                            <!-- Feature 1 -->
                            <div class="counselling-feature hidden-for-animation">
                                <div class="icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                </div>
                                <h4><?php echo getLangText('Personal Guidance', 'ការណែនាំផ្ទាល់ខ្លួន'); ?></h4>
                                <p><?php echo getLangText('One-on-one sessions tailored to your unique personality, strengths, and goals.', 'វគ្គមួយទល់មួយដែលត្រូវបានរៀបចំឡើងតាមបុគ្គលិកលក្ខណៈ ភាពខ្លាំង និងគោលដៅតែមួយគត់របស់អ្នក។'); ?></p>
                            </div>
                            <!-- Feature 2 -->
                             <div class="counselling-feature hidden-for-animation">
                                <div class="icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l5.447 2.724A1 1 0 0021 16.382V5.618a1 1 0 00-1.447-.894L15 7m-6 13v-6.5m6 10V7" /></svg>
                                </div>
                                <h4><?php echo getLangText('Career Planning', 'ការរៀបចំផែនការអាជីព'); ?></h4>
                                <p><?php echo getLangText('Develop a strategic roadmap for your academic and professional future.', 'បង្កើតផែនទីបង្ហាញផ្លូវយុទ្ធសាស្ត្រសម្រាប់អនាគតសិក្សា និងអាជីពរបស់អ្នក។'); ?></p>
                            </div>
                            <!-- Feature 3 -->
                             <div class="counselling-feature hidden-for-animation">
                                <div class="icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                </div>
                                <h4><?php echo getLangText('Industry Insights', 'ការយល់ដឹងអំពីឧស្សាហកម្ម'); ?></h4>
                                <p><?php echo getLangText('Gain up-to-date knowledge about job trends, market demands, and salary expectations.', 'ទទួលបានចំណេះដឹងទាន់សម័យអំពីនិន្នាការការងារ តម្រូវការទីផ្សារ និងការរំពឹងទុកប្រាក់ខែ។'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
    </main>

    <script>
        // JavaScript for scroll animations
        document.addEventListener("DOMContentLoaded", function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-in-up');
                        entry.target.classList.remove('hidden-for-animation');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1
            });

            const elementsToAnimate = document.querySelectorAll('.hidden-for-animation');
            elementsToAnimate.forEach(el => {
                observer.observe(el);
            });
        });
    </script>
