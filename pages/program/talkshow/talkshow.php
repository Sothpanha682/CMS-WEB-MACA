<?php
require_once 'includes/functions.php'; // Include the functions file
require_once 'config/database.php'; // Include the database connection

// Define translation array (English to Khmer)
global $pdo; // Ensure PDO object is accessible
$talkshows = [];
$searchTerm = '';
$isSearching = false;

// Check if search was submitted
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = trim($_GET['search']);
    $isSearching = true;
    
    try {
        // Search in title, summary, location, and description
        $stmt = $pdo->prepare("SELECT * FROM talkshows 
                              WHERE is_active = 1 
                              AND (title LIKE :search 
                                  OR summary LIKE :search 
                                  OR location LIKE :search
                                  OR content LIKE :search)
                              ORDER BY created_at DESC");
        $searchParam = "%{$searchTerm}%";
        $stmt->bindParam(':search', $searchParam);
        $stmt->execute();
        $talkshows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error searching talkshows: " . $e->getMessage());
        $talkshows = [];
    }
} else {
    // No search, get all talkshows
    try {
        $stmt = $pdo->query("SELECT * FROM talkshows WHERE is_active = 1 ORDER BY created_at DESC");
        $talkshows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error fetching talkshows: " . $e->getMessage());
    }
}
?>



  <style>

        /* CONTAINER */
        .container {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* GENERAL SECTION STYLING */
        section {
            padding: 6rem 1rem;
        }

        .bg-light-gray {
            background-color: var(--gray-50);
        }

        /* TYPOGRAPHY */
        .section-title-wrapper {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title {
            font-size: 2.25rem;
            font-weight: 700;
            color: var(--brand-red);
        }

        .title-underline {
            height: 0.3rem; /* Further increased height for better visibility */
            width: 10rem; /* Further increased width for better visibility */
            background-color: #dc3545; /* Explicit danger red color */
            margin: 1rem auto 0;
            border-radius: 9999px;
        }
        
        /* ANIMATIONS */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .animate-fade-in-up,
        .animate-fade-in-left,
        .animate-fade-in-right {
            opacity: 0; /* Start hidden */
            animation-duration: 1s;
            animation-timing-function: ease-out;
            animation-fill-mode: forwards;
        }
        .animate-fade-in-up { animation-name: fadeInUp; }
        .animate-fade-in-left { animation-name: fadeInLeft; }
        .animate-fade-in-right { animation-name: fadeInRight; }
        
        /* UTILITIES */
        img {
            max-width: 100%;
            height: auto;
            display: block;
        }
        ul { list-style: none; }
        .text-lg { font-size: 1.125rem; }
        .leading-relaxed { line-height: 1.75; }

        /* --- SECTION 1: HERO --- */
        .hero-section {
            border-radius: 18px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .hero-title {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--brand-red);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .hero-description {
            margin-top: 1.5rem;
            font-size: 1.125rem;
            max-width: 56rem;
            margin-left: auto;
            margin-right: auto;
            color: var(--gray-600);
        }
        .hero-banner {
            margin-top: 3rem;
            box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.25);
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .hero-banner img { width: 100%; }

        /* --- SECTION 2, 4: IMAGE GRID --- */
        .image-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }
        .image-grid-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            transition: transform 0.3s ease;
        }
        .image-grid-item img:hover {
            transform: scale(1.05);
        }

        /* --- SECTION 2, 4: DESCRIPTION BLOCK --- */
        .description-block {
            max-width: 56rem;
            margin: 0 auto;
            color: var(--gray-700);
        }
        .description-block p {
            margin-bottom: 1.5rem;
        }
        .description-block-center { text-align: center; }

        /* --- SECTION 3: OBJECTIVES CARD GRID --- */
        .cardctn-grid-3 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        .cardctn {
            text-align: center;
            padding: 2rem;
            background-color: var(--gray-50);
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            transition: transform 0.3s ease;
        }
        .cardctn:hover {
            transform: translateY(-0.5rem);
        }
        .cardctn-icon-wrapper {
            margin: 0 auto 1.5rem auto;
            height: 5rem;
            width: 5rem;
            border-radius: 9999px;
            background-color: var(--brand-red);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .cardctn-icon-wrapper svg {
            height: 2.5rem;
            width: 2.5rem;
        }
        .cardctn h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 1rem;
        }
        .cardctn p, .cardctn div {
            color: var(--gray-600);
        }
        .cardctn ul {
            text-align: left;
            list-style-type: disc;
            list-style-position: inside;
        }
        .cardctn li { margin-bottom: 0.75rem; }

        /* --- SECTION 4: TOPIC GRID --- */
        .topic-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            margin-top: 4rem;
        }
        .topic-item { padding: 1.5rem; }
        .topic-item-header {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .topic-item-header .icon { color: var(--brand-red); }
        .topic-item-header .icon svg { height: 2rem; width: 2rem; }
        .topic-item-header h3 { font-size: 1.5rem; font-weight: 700; color: var(--gray-900); }
        .topic-item ul {
            margin-top: 1rem;
            margin-left: 0.5rem;
            list-style-type: disc;
            list-style-position: inside;
            color: var(--gray-600);
        }
        .topic-item li, .topic-item p { margin-top: 1rem; color: var(--gray-600); }

        /* --- SECTION 5: PROBLEMS --- */
        .problems-wrapper {
            display: flex;
            flex-direction: column;
            gap: 5rem;
        }
        .problem-row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 3rem;
            align-items: center;
        }
        .problem-text-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .problem-text-header .icon { color: var(--brand-red); }
        .problem-text-header .icon svg { height: 2.5rem; width: 2.5rem; }
        .problem-text-header h3 { font-size: 1.5rem; font-weight: 700; color: var(--gray-900); }
        .problem-text p { color: var(--gray-600); }
        .problem-text p:not(:last-child) { margin-bottom: 1rem; }
        .problem-image img {
            width: 495px;
            height: 330px;
            object-fit: cover;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        /* RESPONSIVE STYLES */
        @media (max-width: 767px) {
            .hero-section {
                padding: 4rem 1rem; /* Adjust padding for smaller screens */
                min-height: auto; /* Allow height to adjust based on content */
            }
            .hero-title {
                font-size: 1.75rem; /* Smaller font size for phones */
            }
            .hero-description {
                font-size: 1rem; /* Smaller font size for phones */
                margin-top: 1rem; /* Reduce top margin */
            }
            .hero-banner {
                margin-top: 2rem; /* Reduce top margin for the banner */
            }
            .section-title { font-size: 2rem; } /* Adjust other section titles for mobile */
            .image-grid { grid-template-columns: 1fr; } /* Ensure single column on mobile */
            .cardctn-grid-3 { grid-template-columns: 1fr; } /* Ensure single column on mobile */
            .topic-grid { grid-template-columns: 1fr; } /* Ensure single column on mobile */
            .problem-row { grid-template-columns: 1fr; } /* Ensure single column on mobile */
            .problem-image img {
                width: 100%; /* Make images fill width on mobile */
                height: auto;
            }
        }

        @media (min-width: 768px) {
            .section-title { font-size: 2.75rem; }
            .hero-title { font-size: 3.75rem; }
            .hero-description { font-size: 1.25rem; }
            .image-grid { grid-template-columns: repeat(2, 1fr); }
            .cardctn-grid-3 { grid-template-columns: repeat(2, 1fr); }
            .topic-grid { grid-template-columns: repeat(3, 1fr); } /* Show 3 cols earlier */
            .problem-row { grid-template-columns: repeat(2, 1fr); }
        }
        @media (min-width: 1024px) {
            .cardctn-grid-3 { grid-template-columns: repeat(3, 1fr); }
        }
    </style>
</head>
<body>

    <?php if (!$isSearching): ?>
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title animate-fade-in-up display-4 fw-bold text-danger" style="animation-delay: 0.2s;">
                <?php echo getLangText('MAJOR & CAREER TALK SHOW', 'កម្មវិធីសន្ទនាអំពីមុខជំនាញ និងអាជីព'); ?>
            </h1>
            <div class="title-underline animate-fade-in-up" style="animation-delay: 0.4s;"></div>
            <p class="hero-description animate-fade-in-up" style="animation-delay: 0.4s;">
                <?php echo getLangText('An educational video production program for students and the general public to access useful and relevant topics on professional and professional skills.', 'កម្មវិធីផលិតវីដេអូអប់រំសម្រាប់សិស្ស និស្សិត និងសាធារណជនទូទៅ ដើម្បីទទួលបានប្រធានបទមានប្រយោជន៍ និងពាក់ព័ន្ធនឹងជំនាញវិជ្ជាជីវៈ និងអាជីព។'); ?>
            </p>
            <div class="hero-banner animate-fade-in-up" style="animation-delay: 0.6s;">
                <img src="assets/images/talkshow_banner.jpg" alt="Talk Show Banner">
            </div>
        </div>
    </section>

    <section class="bg-light-gray">
        <div class="container">
            <div class="section-title-wrapper">
                <h2 class="section-title animate-fade-in-up display-4 fw-bold text-danger"><?php echo getLangText('About The Program', 'អំពីកម្មវិធី'); ?></h2>
                <div class="title-underline animate-fade-in-up" style="animation-delay: 0.2s;"></div>
            </div>

            <div class="image-grid">
                <div class="image-grid-item animate-fade-in-left" style="animation-delay: 0.4s;">
                    <img src="assets/images/ab_image_left.jpg" alt="Students collaborating">
                </div>
                <div class="image-grid-item animate-fade-in-right" style="animation-delay: 0.6s;">
                    <img src="assets/images/ab_image_right.jpg" alt="Professional speaker">
                </div>
            </div>

            <div class="description-block text-lg leading-relaxed animate-fade-in-up" style="animation-delay: 0.8s;">
                <p><?php echo getLangText('The "Major & Career Talk Show" is a video interview program that allows students, the public, and the general public to use the content of the topic to their advantage and make professional and academic decisions.', '«កម្មវិធីសន្ទនាអំពីមុខជំនាញ និងអាជីព» គឺជាកម្មវិធីសម្ភាសន៍ជាវីដេអូ ដែលអនុញ្ញាតឱ្យសិស្ស និស្សិត សាធារណជន និងមហាជនទូទៅ អាចយកខ្លឹមសារនៃប្រធានបទទៅប្រើប្រាស់ជាប្រយោជន៍ និងធ្វើការសម្រេចចិត្តលើវិស័យអាជីព និងការសិក្សា។'); ?></p>
                <p><?php echo getLangText('Seeing the current context, Digital or artificial intelligence (AI) technology is very influential in helping the research of students, the public as well as general institutions. By launching the first phase of the Pentagon strategy, human resource development focuses on improving the quality of education, sports, science and technology...', 'ដោយមើលឃើញពីបរិបទបច្ចុប្បន្ន បច្ចេកវិទ្យាឌីជីថល ឬបញ្ញាសិប្បនិម្មិត (AI) មានឥទ្ធិពលយ៉ាងខ្លាំងក្នុងការជួយដល់ការស្រាវជ្រាវរបស់សិស្ស និស្សិត សាធារណជន ក៏ដូចជាស្ថាប័នទូទៅ។ តាមរយៈការដាក់ចេញនូវយុទ្ធសាស្ត្របញ្ចកោណដំណាក់កាលទី១ ការអភិវឌ្ឍធនធានមនុស្សផ្តោតលើការលើកកម្ពស់គុណភាពអប់រំ កីឡា វិទ្យាសាស្ត្រ និងបច្ចេកវិទ្យា...'); ?></p>
                <p><?php echo getLangText('The program will be conducted in the form of live interviews with keynote speakers, including high school students, students of each major of the university, as well as speakers from a variety of careers. The program will be advertised digitally on social media sites such as Facebook, TikTok, Telegram, Instagram and YouTube, which are expected to receive at least 1 million views.', 'កម្មវិធីនេះនឹងត្រូវធ្វើឡើងក្នុងទម្រង់ជាការសម្ភាសន៍ផ្ទាល់ជាមួយវាគ្មិនសំខាន់ៗ រួមមានសិស្សវិទ្យាល័យ និស្សិតនៃមុខជំនាញនីមួយៗនៃសាកលវិទ្យាល័យ ក៏ដូចជាវាគ្មិនមកពីអាជីពផ្សេងៗគ្នា។ កម្មវិធីនេះនឹងត្រូវបានផ្សព្វផ្សាយជាឌីជីថលនៅលើបណ្តាញសង្គមដូចជា Facebook, TikTok, Telegram, Instagram និង YouTube ដែលរំពឹងថានឹងទទួលបានការទស្សនាយ៉ាងតិច ១ លានដង។'); ?></p>
                <p><?php echo getLangText('Target students in high schools in the 25 provincial capitals with grades from 10th to 12th, students studying at universities from 3rd to 4th year and speakers Multidisciplinary careers will be selected to participate in this program.', 'គោលដៅសិស្សានុសិស្សនៅតាមវិទ្យាល័យក្នុងរាជធានី-ខេត្តទាំង ២៥ ដែលមានថ្នាក់ទី ១០ ដល់ទី ១២ និស្សិតកំពុងសិក្សានៅតាមសាកលវិទ្យាល័យចាប់ពីឆ្នាំទី ៣ ដល់ទី ៤ និងវាគ្មិនអាជីពពហុជំនាញ នឹងត្រូវបានជ្រើសរើសឱ្យចូលរួមក្នុងកម្មវិធីនេះ។'); ?></p>
                <p><?php echo getLangText('The program will take place in the MACA Studio or at the location of each target school according to the date set by the team. The program will produce a video of at least 60 minutes per video and post it on social media every Saturday at 8pm every week.', 'កម្មវិធីនេះនឹងប្រព្រឹត្តទៅនៅស្ទូឌីយោ MACA ឬនៅទីតាំងសាលាគោលដៅនីមួយៗ ទៅតាមកាលបរិច្ឆេទដែលក្រុមការងារបានកំណត់។ កម្មវិធីនេះនឹងផលិតវីដេអូយ៉ាងតិច ៦០ នាទីក្នុងមួយវីដេអូ ហើយបង្ហោះនៅលើបណ្តាញសង្គមរៀងរាល់ថ្ងៃសៅរ៍ វេលាម៉ោង ៨ យប់ ជារៀងរាល់សប្តាហ៍។'); ?></p>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="section-title-wrapper">
                <h2 class="section-title animate-fade-in-up display-4 fw-bold text-danger"><?php echo getLangText('Objective, Goals & Expected Results', 'គោលបំណង គោលដៅ និងលទ្ធផលរំពឹងទុក'); ?></h2>
                <div class="title-underline animate-fade-in-up" style="animation-delay: 0.2s;"></div>
            </div>

            <div class="cardctn-grid-3">
                <div class="cardctn animate-fade-in-up" style="animation-delay: 0.4s;">
                    <div class="cardctn-icon-wrapper text-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    </div>
                    <h3><?php echo getLangText('Objectives', 'គោលបំណង'); ?></h3>
                    <p><?php echo getLangText('Disseminate a wide range of career and career selection interviews to students, the public, so that they can use the content of the topic to their advantage in making educational and career decisions skills in the context of the digital age.', 'ផ្សព្វផ្សាយបទសម្ភាសន៍ជ្រើសរើសមុខជំនាញ និងអាជីពយ៉ាងទូលំទូលាយដល់សិស្ស និស្សិត សាធារណជន ដើម្បីឱ្យពួកគេអាចប្រើប្រាស់ខ្លឹមសារនៃប្រធានបទនេះជាប្រយោជន៍ក្នុងការសម្រេចចិត្តលើការអប់រំ និងអាជីពក្នុងបរិបទនៃយុគសម័យឌីជីថល។'); ?></p>
                </div>

                <div class="cardctn animate-fade-in-up" style="animation-delay: 0.6s;">
                    <div class="cardctn-icon-wrapper text-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" /></svg>
                    </div>
                    <h3><?php echo getLangText('Goals', 'គោលដៅ'); ?></h3>
                    <div>
                        <ul>
                           <li><?php echo getLangText('Share personal knowledge and experiences from students, public figures, media influencers and multidisciplinary successes.', 'ចែករំលែកចំណេះដឹង និងបទពិសោធន៍ផ្ទាល់ខ្លួនពីសិស្ស និស្សិត បុគ្គលសាធារណៈ អ្នកមានឥទ្ធិពលលើប្រព័ន្ធផ្សព្វផ្សាយ និងអ្នកជោគជ័យពហុជំនាញ។'); ?></li>
                           <li><?php echo getLangText('Provide a basis for students to better understand the skills they should choose, study readiness, and study location.', 'ផ្តល់មូលដ្ឋានគ្រឹះសម្រាប់សិស្ស និស្សិត ដើម្បីស្វែងយល់កាន់តែច្បាស់អំពីជំនាញដែលពួកគេគួរជ្រើសរើស ការត្រៀមខ្លួនសម្រាប់ការសិក្សា និងទីតាំងសិក្សា។'); ?></li>
                           <li><?php echo getLangText('Make a small contribution with the Ministry of Education, Youth and Sports to support the integration of the Digital Education Strategic Plan.', 'ចូលរួមចំណែកបន្តិចបន្តួចជាមួយក្រសួងអប់រំ យុវជន និងកីឡា ដើម្បីគាំទ្រការធ្វើសមាហរណកម្មផែនការយុទ្ធសាស្ត្រអប់រំឌីជីថល។'); ?></li>
                        </ul>
                    </div>
                </div>
                
                <div class="cardctn animate-fade-in-up" style="animation-delay: 0.8s;">
                    <div class="cardctn-icon-wrapper text-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    </div>
                    <h3><?php echo getLangText('Expected Results', 'លទ្ធផលរំពឹងទុក'); ?></h3>
                    <p><?php echo getLangText('Target a minimum of 1,000,000 people viewed through digital advertising on social media like Facebook, TikTok, Instagram and YouTube, addressing their concerns in deciding on career options.', 'កំណត់គោលដៅយ៉ាងតិច ១,០០០,០០០ នាក់បានទស្សនាតាមរយៈការផ្សព្វផ្សាយឌីជីថលនៅលើបណ្តាញសង្គមដូចជា Facebook, TikTok, Instagram និង YouTube ដោយដោះស្រាយកង្វល់របស់ពួកគេក្នុងការសម្រេចចិត្តលើជម្រើសអាជីព។'); ?></p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-light-gray">
        <div class="container">
            <div class="section-title-wrapper">
                <h2 class="section-title animate-fade-in-up display-4 fw-bold text-danger"><?php echo getLangText('Topic Forms and Interviews', 'ទម្រង់ប្រធានបទ និងបទសម្ភាសន៍'); ?></h2>
                <div class="title-underline animate-fade-in-up" style="animation-delay: 0.2s;"></div>
            </div>

            <div class="image-grid">
                <div class="image-grid-item animate-fade-in-left" style="animation-delay: 0.4s;">
                    <img src="assets/images/tfi_image_left.jpg" alt="Professional Interview">
                </div>
                <div class="image-grid-item animate-fade-in-right" style="animation-delay: 0.6s;">
                     <img src="assets/images/tfi_image_right.jpg" alt="Team Discussion">
                </div>
            </div>

            <div class="description-block description-block-center text-lg leading-relaxed animate-fade-in-up" style="animation-delay: 0.7s;">
                <p><?php echo getLangText('The program will be in the form of interviews with a facilitator and guest speakers, high school students, outstanding university students, public figures, influencers and celebrities in their careers who will share experiences in preparation for choosing a study and career.', 'កម្មវិធីនេះនឹងមានទម្រង់ជាបទសម្ភាសន៍ជាមួយអ្នកសម្របសម្រួល និងវាគ្មិនកិត្តិយស សិស្សវិទ្យាល័យ និស្សិតឆ្នើមនៅសាកលវិទ្យាល័យ បុគ្គលសាធារណៈ អ្នកមានឥទ្ធិពល និងតារាល្បីៗក្នុងអាជីពរបស់ពួកគេ ដែលនឹងចែករំលែកបទពិសោធន៍ក្នុងការត្រៀមខ្លួនសម្រាប់ការជ្រើសរើសការសិក្សា និងអាជីព។'); ?></p>
            </div>

            <div class="topic-grid">
                <div class="topic-item cardctn animate-fade-in-up" style="animation-delay: 0.8s;">
                    <div class="topic-item-header">
                        <div class="icon text-danger"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" /></svg></div>
                        <h3><?php echo getLangText('Interview on the topic', 'បទសម្ភាសន៍លើប្រធានបទ'); ?></h3>
                    </div>
                    <ul><li><?php echo getLangText('High School Planning', 'ការរៀបចំផែនការនៅវិទ្យាល័យ'); ?></li><li><?php echo getLangText('Tips for post-secondary education', 'គន្លឹះសម្រាប់ការអប់រំក្រោយមធ្យមសិក្សា'); ?></li><li><?php echo getLangText('Careers in government, private and civil society', 'អាជីពក្នុងរដ្ឋាភិបាល ឯកជន និងសង្គមស៊ីវិល'); ?></li></ul>
                </div>
                <div class="topic-item cardctn animate-fade-in-up" style="animation-delay: 1.0s;">
                    <div class="topic-item-header">
                        <div class="icon text-danger"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg></div>
                        <h3><?php echo getLangText('Sharing Experience', 'ការចែករំលែកបទពិសោធន៍'); ?></h3>
                    </div>
                    <p><?php echo getLangText('Share personal knowledge and experiences from students, public figures, media influencers and multidisciplinary successes.', 'ចែករំលែកចំណេះដឹង និងបទពិសោធន៍ផ្ទាល់ខ្លួនពីសិស្ស និស្សិត បុគ្គលសាធារណៈ អ្នកមានឥទ្ធិពលលើប្រព័ន្ធផ្សព្វផ្សាយ និងអ្នកជោគជ័យពហុជំនាញ។'); ?></p>
                </div>
                <div class="topic-item cardctn animate-fade-in-up" style="animation-delay: 1.2s;">
                    <div class="topic-item-header">
                        <div class="icon text-danger"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg></div>
                        <h3><?php echo getLangText('Quotes From Audience', 'សម្រង់ពីទស្សនិកជន'); ?></h3>
                    </div>
                    <ul><li><?php echo getLangText('Questionnaire through Audience Page', 'កម្រងសំណួរតាមរយៈទំព័រទស្សនិកជន'); ?></li><li><?php echo getLangText('Questions excerpted from comments two days before broadcast', 'សំណួរដកស្រង់ចេញពីមតិយោបល់ពីរថ្ងៃមុនការផ្សាយ'); ?></li></ul>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="section-title-wrapper">
                <h2 class="section-title animate-fade-in-up display-4 fw-bold text-danger"><?php echo getLangText('Problems To Be Solved', 'បញ្ហាដែលត្រូវដោះស្រាយ'); ?></h2>
                <div class="title-underline animate-fade-in-up" style="animation-delay: 0.2s;"></div>
            </div>
            
            <div class="problems-wrapper">
                <div class="problem-row cardctn animate-fade-in-up" style="animation-delay: 0.4s;">
                    <div class="problem-text">
                        <div class="problem-text-header">
                            <div class="icon text-danger"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg></div>
                            <h3><?php echo getLangText('Choosing the wrong skills you don’t like', 'ជ្រើសរើសជំនាញខុសពីចំណង់ចំណូលចិត្ត'); ?></h3>
                        </div>
                        <p class="leading-relaxed"><?php echo getLangText('The decision to choose a major by many youths remains a challenge due to limited access to information, following a friend or family’s decision, and lack of guidance in line with the turns of technology and new developments.', 'ការសម្រេចចិត្តជ្រើសរើសមុខជំនាញរបស់យុវជនជាច្រើននៅតែជាបញ្ហាប្រឈម ដោយសារការទទួលបានព័ត៌មានមានកម្រិត ការធ្វើតាមការសម្រេចចិត្តរបស់មិត្តភក្តិ ឬក្រុមគ្រួសារ និងកង្វះការណែនាំស្របតាមការវិវត្តនៃបច្ចេកវិទ្យា និងការអភិវឌ្ឍថ្មីៗ។'); ?></p>
                    </div>
                    <div class="problem-image"><img src="assets/images/ptbs_image_1.png" alt="Confused student"></div>
                </div>

                <div class="problem-row cardctn animate-fade-in-up" style="animation-delay: 0.6s;">
                    <div class="problem-text">
                        <div class="problem-text-header">
                            <div class="icon text-danger"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                            <h3><?php echo getLangText('Uncertain Skills or Dropout', 'ជំនាញមិនច្បាស់លាស់ ឬបោះបង់ការសិក្សា'); ?></h3>
                        </div>
                        <p class="leading-relaxed"><?php echo getLangText('Choosing majors that do not fit the hobbies and talents of youths resulted in many negative effects that waste their time, face family financial crisis and lack of confidence or may drop out of school.', 'ការជ្រើសរើសមុខជំនាញដែលមិនសមស្របនឹងចំណង់ចំណូលចិត្ត និងទេពកោសល្យរបស់យុវជន បានបណ្តាលឱ្យមានផលប៉ះពាល់អវិជ្ជមានជាច្រើន ដែលខ្ជះខ្ជាយពេលវេលា ប្រឈមមុខនឹងវិបត្តិហិរញ្ញវត្ថុគ្រួសារ និងខ្វះទំនុកចិត្ត ឬអាចបោះបង់ការសិក្សា។'); ?></p>
                        <p class="leading-relaxed"><?php echo getLangText('This leads to incapable youths stepping into the unskilled labor market and affects the national economy.', 'នេះនាំឱ្យយុវជនដែលគ្មានសមត្ថភាព ចូលទៅក្នុងទីផ្សារការងារដែលគ្មានជំនាញ និងប៉ះពាល់ដល់សេដ្ឋកិច្ចជាតិ។'); ?></p>
                    </div>
                    <div class="problem-image"><img src="assets/images/ptbs_image_2.png" alt="Student walking away"></div>
                </div>

                <div class="problem-row cardctn animate-fade-in-up" style="animation-delay: 0.8s;">
                    <div class="problem-text">
                        <div class="problem-text-header">
                            <div class="icon text-danger"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                            <h3><?php echo getLangText('Skills Do Not Match Market Needs', 'ជំនាញមិនត្រូវតាមតម្រូវការទីផ្សារ'); ?></h3>
                        </div>
                        <p class="leading-relaxed"><?php echo getLangText('Choosing a major does not always meet the needs of the current labor market. Choosing without thorough analysis is to follow someone else’s decision and to choose a general skill, opposed to current job requirements for specialized skills in electronics, mechanics, technology, production, and other scientific fields.', 'ការជ្រើសរើសមុខជំនាញមិនតែងតែឆ្លើយតបនឹងតម្រូវការទីផ្សារការងារបច្ចុប្បន្ននោះទេ។ ការជ្រើសរើសដោយគ្មានការវិភាគស៊ីជម្រៅ គឺធ្វើតាមការសម្រេចចិត្តរបស់អ្នកដទៃ និងជ្រើសរើសជំនាញទូទៅ ដែលផ្ទុយពីតម្រូវការការងារបច្ចុប្បន្នសម្រាប់ជំនាញឯកទេសក្នុងវិស័យអេឡិចត្រូនិក មេកានិក បច្ចេកវិទ្យា ផលិតកម្ម និងវិទ្យាសាស្ត្រផ្សេងទៀត។'); ?></p>
                    </div>
                    <div class="problem-image"><img src="assets/images/ptbs_image_3.png" alt="Job market"></div>
                </div>
            </div>
        </div>
    </section>

    <?php endif; ?>

<div class="container py-5" id="talkshow-program-section">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-4 fw-bold text-danger">Talkshow Program</h1>
            <p class="lead">Join our educational talkshows featuring industry experts and educational professionals discussing important topics in education and career development.</p>
        </div>
    </div>
    
    <!-- Search Form -->
    <div class="row mb-4">
        <div class="col-md-6">
            <form method="GET" action="index.php" class="d-flex">
                <input type="hidden" name="page" value="program/talkshow/talkshow">
                <input type="text" name="search" class="form-control me-2" placeholder="Search talkshows..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit" class="btn btn-danger">Search</button>
                <?php if ($isSearching): ?>
                    <a href="index.php?page=program/talkshow/talkshow#talkshow-program-section" class="btn btn-outline-secondary ms-2">Clear</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
    
    <?php if ($isSearching): ?>
        <div class="alert alert-info">
            <?php if (count($talkshows) > 0): ?>
                Found <?php echo count($talkshows); ?> result<?php echo count($talkshows) != 1 ? 's' : ''; ?> for "<?php echo htmlspecialchars($searchTerm); ?>"
            <?php else: ?>
                No talkshows found matching "<?php echo htmlspecialchars($searchTerm); ?>"
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (count($talkshows) > 0): ?>
        <div class="row">
            <?php foreach ($talkshows as $talkshow): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <?php if (!empty($talkshow['image_path'])): ?>
                            <img src="<?php echo htmlspecialchars($talkshow['image_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($talkshow['title']); ?>">
                        <?php endif; ?>
                        
                        <?php if (!empty($talkshow['video_url'])): ?>
                            <div class="ratio ratio-16x9">
                                <?php echo getVideoEmbedCode($talkshow['video_url']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($talkshow['title']); ?></h5>
                            <p class="card-text text-muted small">
                                <i class="fas fa-map-marker-alt me-1"></i> <?php echo htmlspecialchars($talkshow['location']); ?>
                                <br>
                                <i class="fas fa-calendar-alt me-1"></i> <?php echo formatDate($talkshow['event_date']); ?>
                            </p>
                            <div class="card-text mb-3">
                                <?php 
                                $summary = strip_tags($talkshow['summary']);
                                echo strlen($summary) > 150 ? substr($summary, 0, 150) . '...' : $summary;
                                ?>
                            </div>
                            <a href="index.php?page=talkshow-detail&id=<?php echo $talkshow['id']; ?>" class="btn btn-outline-danger">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <?php if ($isSearching): ?>
                <p>No talkshow content found matching your search. Please try different keywords.</p>
            <?php else: ?>
                <p>No talkshow content available at the moment. Please check back later.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
