<?php
require_once 'includes/functions.php'; // Include the functions file
require_once 'config/database.php'; // Include the database connection

// Get all active roadshow entries
global $pdo; // Ensure PDO object is accessible
$roadshows = [];
$searchTerm = '';
$isSearching = false;

// Check if search was submitted
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = trim($_GET['search']);
    $isSearching = true;
    
    try {
        // Search in title, summary, location, and description
        $stmt = $pdo->prepare("SELECT * FROM roadshow 
                              WHERE is_active = 1 
                              AND (title LIKE :search 
                                  OR description LIKE :search 
                                  OR location LIKE :search)
                              ORDER BY created_at DESC");
        $searchParam = "%{$searchTerm}%";
        $stmt->bindParam(':search', $searchParam);
        $stmt->execute();
        $roadshows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error searching roadshows: " . $e->getMessage());
        $roadshows = [];
    }
} else {
    // No search, get all roadshows
    try {
        $stmt = $pdo->query("SELECT * FROM roadshow WHERE is_active = 1 ORDER BY created_at DESC");
        $roadshows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error fetching roadshows: " . $e->getMessage());
    }
}
?>

    <?php if (!$isSearching): ?>
    <section class="hero-section">
        <div class="containerrds">
            <div class="section-title-wrapper">
                <h1 class="hero-title animate-fade-in-up display-4 fw-bold text-danger" style="animation-delay: 0.2s;">
                    <?php echo getLangText('MAJOR & CAREER ROADSHOW', 'កម្មវិធីបង្ហាញផ្លូវស្តីពី​ ជំនាញសិក្សា និងអាជីព'); ?>
                </h1>
                <div class="title-underline animate-fade-in-up" style="animation-delay: 0.4s;"></div>
            </div>
            <p class="animate-fade-in-up" style="animation-delay: 0.6s;"><?php echo getLangText('A program that helps youth to be confident about their study and career options to capture their future suitable career.', 'ជាកម្មវិធីដែលជួយយុវជនឱ្យមានភាពច្បាស់លាស់អំពីជម្រើសជំនាញសិក្សានិងអាជីព ដើម្បីចាប់យកអាជីពសមរមយនាពេលអនាគតរបស់ពួកគេ។'); ?></p>
            <div class="banner-image animate-fade-in-up" style="animation-delay: 0.8s;">
                <img src="assets/images/Roadshow-banner.jpg" alt="Major and Career Roadshow Banner">
            </div>
        </div>
    </section>

    <section class="info-section ">
        <div class="containerrds">
             <h2 class="text-danger"><?php echo getLangText('Objectives, Goals and Expected Results', 'គោលបំណង គោលដៅ និងលទ្ធផលរំពឹងទុក'); ?></h2>
            <div class="info-grid">
                <div class="info-card">
                    <i class="fas fa-bullseye icon"></i>
                    <h3><?php echo getLangText('Objectives', 'គោលបំណង'); ?></h3>
                    <p><?php echo getLangText('The main objective of Major and Career Roadshow is to build the capacity among youths in choosing the right major and career through an orientation session and to guide them to become leaders in those skills in the context of the digital age.', 'គោលបំណងសំខាន់នៃកម្មវិធីតាំងពិព័រណ៍មុខជំនាញ និងអាជីព គឺដើម្បីកសាងសមត្ថភាពក្នុងចំណោមយុវជនក្នុងការជ្រើសរើសមុខជំនាញ និងអាជីពត្រឹមត្រូវតាមរយៈវគ្គតម្រង់ទិស និងណែនាំពួកគេឱ្យក្លាយជាអ្នកដឹកនាំក្នុងជំនាញទាំងនោះក្នុងបរិបទនៃយុគសម័យឌីជីថល។'); ?></p>
                </div>
                <div class="info-card">
                    <i class="fas fa-trophy icon"></i>
                    <h3><?php echo getLangText('Goals', 'គោលដៅ'); ?></h3>
                    <p><?php echo getLangText('In responding to our objective, MACA has established the following goals:', 'ដើម្បីឆ្លើយតបទៅនឹងគោលបំណងរបស់យើង MACA បានបង្កើតគោលដៅដូចខាងក្រោម៖'); ?></p>
                    <ul>
                        <li><?php echo getLangText('Provide soft skills to students through presentations from experts.', 'ផ្តល់ជំនាញទន់ដល់សិស្សតាមរយៈបទបង្ហាញពីអ្នកជំនាញ។'); ?></li>
                        <li><?php echo getLangText('Provide self-discovery tests by using MACA Mobile technology.', 'ផ្តល់ការធ្វើតេស្តស្វែងយល់ពីខ្លួនឯងដោយប្រើបច្ចេកវិទ្យា MACA Mobile។'); ?></li>
                        <li><?php echo getLangText('Provide knowledge and personal experiences sharing sessions by public figures.', 'ផ្តល់វគ្គចែករំលែកចំណេះដឹង និងបទពិសោធន៍ផ្ទាល់ខ្លួនដោយឥស្សរជនសាធារណៈ។'); ?></li>
                        <li><?php echo getLangText('Provide the opportunity for participants to ask questions and seek advice.', 'ផ្តល់ឱកាសឱ្យអ្នកចូលរួមសួរសំណួរ និងស្វែងរកដំបូន្មាន។'); ?></li>
                    </ul>
                </div>
                <div class="info-card">
                    <i class="fas fa-chart-line icon"></i>
                    <h3><?php echo getLangText('Expected Results', 'លទ្ធផលរំពឹងទុក'); ?></h3>
                    <p><?php echo getLangText('A total of 10,000 high school students will be guided and helped. They will be confident about their study and career options.', 'សិស្សវិទ្យាល័យសរុបចំនួន ១០,០០០ នាក់ នឹងត្រូវបានណែនាំ និងជួយ។ ពួកគេនឹងមានទំនុកចិត្តលើជម្រើសសិក្សា និងអាជីពរបស់ពួកគេ។'); ?></p>
                    <p><?php echo getLangText('At least 5 million Cambodians will understand the importance of choosing a major and career through content advertised on social media.', 'ប្រជាជនកម្ពុជាយ៉ាងហោចណាស់ ៥ លាននាក់ នឹងយល់ពីសារៈសំខាន់នៃការជ្រើសរើសមុខជំនាញ និងអាជីពតាមរយៈខ្លឹមសារដែលផ្សព្វផ្សាយនៅលើបណ្តាញសង្គម។'); ?></p>
                </div>
            </div>
        </div>
    </section>

    <section class="topics-section">
        <div class="containerrds">
             <h2 class="text-danger"><?php echo getLangText('Topics and Trainers', 'ប្រធានបទ និងអ្នកបណ្តុះបណ្តាល'); ?></h2>
            <div class="trainers-visual">
                <img src="assets/images/tat-image-left.jpg" alt="Trainer 1" id="tat-image-left">
                <img src="assets/images/tat-image-right.jpg" alt="Trainer 2" id="tat-image-right">
            </div>
            <p class="topics-description">
                <?php echo getLangText('The program will be presented by a trainer who specializes in career counseling and graduated with a master\'s degree from Singapore and a US Certified Professional Counselor. The event also features guest speakers, public figures, influencers and outstanding persons in their careers who will share their experiences.', 'កម្មវិធីនេះនឹងត្រូវបានបង្ហាញដោយអ្នកបណ្តុះបណ្តាលដែលមានឯកទេសក្នុងការប្រឹក្សាអាជីព និងបានបញ្ចប់ថ្នាក់អនុបណ្ឌិតពីប្រទេសសិង្ហបុរី និងជាទីប្រឹក្សាអាជីពដែលមានការបញ្ជាក់ពីសហរដ្ឋអាមេរិក។ ព្រឹត្តិការណ៍នេះក៏មានវាគ្មិនកិត្តិយស ឥស្សរជនសាធារណៈ អ្នកមានឥទ្ធិពល និងបុគ្គលឆ្នើមក្នុងអាជីពរបស់ពួកគេដែលនឹងចែករំលែកបទពិសោធន៍របស់ពួកគេ។'); ?>
            </p>

            <div class="topics-grid">
                <div class="topic-card">
                    <i class="fas fa-chalkboard topic-icon"></i>
                    <h3><?php echo getLangText('Presentation', 'បទបង្ហាញ'); ?></h3>
                    <p><?php echo getLangText('Understanding yourself and the importance of choosing a major and career. Youth Leadership in the Digital Age.', 'ការយល់ដឹងពីខ្លួនឯង និងសារៈសំខាន់នៃការជ្រើសរើសមុខជំនាញ និងអាជីព។ ភាពជាអ្នកដឹកនាំយុវជនក្នុងយុគសម័យឌីជីថល។'); ?></p>
                </div>
                <div class="topic-card">
                    <i class="fas fa-clipboard-check topic-icon"></i>
                    <h3><?php echo getLangText('Career Test', 'ការធ្វើតេស្តអាជីព'); ?></h3>
                    <p><?php echo getLangText('Test to find a major and career that suit your preferences through the MACA Mobile app.', 'ធ្វើតេស្តដើម្បីស្វែងរកមុខជំនាញ និងអាជីពដែលស័ក្តិសមនឹងចំណូលចិត្តរបស់អ្នកតាមរយៈកម្មវិធីទូរស័ព្ទ MACA។'); ?></p>
                </div>
                <div class="topic-card">
                    <i class="fas fa-star topic-icon"></i>
                    <h3><?php echo getLangText('Experience', 'បទពិសោធន៍'); ?></h3>
                    <p><?php echo getLangText('Sharing knowledge and experience by public figures, influencers, and outstanding persons in their careers.', 'ការចែករំលែកចំណេះដឹង និងបទពិសោធន៍ដោយឥស្សរជនសាធារណៈ អ្នកមានឥទ្ធិពល និងបុគ្គលឆ្នើមក្នុងអាជីពរបស់ពួកគេ។'); ?></p>
                </div>
            </div>
        </div>
    </section>

   <section class="problems-section">
    <div class="containerrds">
         <h2 class="text-danger"><?php echo getLangText('Problem To be Solved', 'បញ្ហាដែលត្រូវដោះស្រាយ'); ?></h2>
        <div class="problems-grid">
            <div class="problem-card">
                <div class="card-image">
                    <img src="assets/images/Choosing-the-wrong-skills_roadshow.jpg" alt="Wrong choice icon">
                </div>
                <div class="card-content">
                    <i class="fas fa-circle-xmark problem-icon"></i>
                    <h3><?php echo getLangText('Choosing the wrong skills you don’t like', 'ការជ្រើសរើសជំនាញខុសដែលអ្នកមិនចូលចិត្ត'); ?></h3>
                    <p><?php echo getLangText('The decision to choose a major and a career by a large number of youths remains a challenge due to limited access to information, following a friend or family’s decision, and lack of guidance in line with technology and new developments.', 'ការសម្រេចចិត្តជ្រើសរើសមុខជំនាញ និងអាជីពដោយយុវជនមួយចំនួនធំនៅតែជាបញ្ហាប្រឈមដោយសារកង្វះព័ត៌មាន ការធ្វើតាមការសម្រេចចិត្តរបស់មិត្តភ័ក្តិ ឬក្រុមគ្រួសារ និងកង្វះការណែនាំស្របតាមបច្ចេកវិទ្យា និងការអភិវឌ្ឍន៍ថ្មីៗ។'); ?></p>
                </div>
            </div>
            <div class="problem-card">
                <div class="card-image">
                    <img src="assets/images/Uncertain-Skills-or-Dropout_roadshow.jpg" alt="Uncertain skills icon">
                </div>
                <div class="card-content">
                    <i class="fas fa-triangle-exclamation problem-icon"></i>
                    <h3><?php echo getLangText('Uncertain Skills or Dropout', 'ជំនាញមិនច្បាស់លាស់ ឬបោះបង់ការសិក្សា'); ?></h3>
                    <p><?php echo getLangText('Choosing majors that do not fit the hobbies and talents of youths resulted in many negative effects that waste their time, face family financial crisis and lack of confidence or may drop out of school, which leads to incapable youths stepping into the unskilled labor market.', 'ការជ្រើសរើសមុខជំនាញដែលមិនសមស្របនឹងចំណង់ចំណូលចិត្ត និងទេពកោសល្យរបស់យុវជនបានបណ្តាលឱ្យមានផលប៉ះពាល់អវិជ្ជមានជាច្រើនដែលខ្ជះខ្ជាយពេលវេលារបស់ពួកគេ ប្រឈមមុខនឹងវិបត្តិហិរញ្ញវត្ថុគ្រួសារ និងកង្វះទំនុកចិត្ត ឬអាចបោះបង់ការសិក្សា ដែលនាំឱ្យយុវជនដែលគ្មានសមត្ថភាពចូលទៅក្នុងទីផ្សារការងារដែលគ្មានជំនាញ។'); ?></p>
                </div>
            </div>
            <div class="problem-card">
                <div class="card-image">
                    <img src="assets/images/Skills-Do-Not-Match_roadshow.jpg" alt="Mismatch icon">
                </div>
                <div class="card-content">
                    <i class="fas fa-arrows-left-right problem-icon"></i>
                    <h3><?php echo getLangText('Skills Do Not Match Market Needs', 'ជំនាញមិនត្រូវតាមតម្រូវការទីផ្សារ'); ?></h3>
                    <p><?php echo getLangText('Choosing a Major and Career of youth does not meet the needs of the current labor market. Choosing without a thorough analysis and long-term vision is to follow someone else’s decision and to choose a general skill that is not a specific specialty, opposed to current job requirements.', 'ការជ្រើសរើសមុខជំនាញ និងអាជីពរបស់យុវជនមិនទាន់ឆ្លើយតបនឹងតម្រូវការទីផ្សារការងារបច្ចុប្បន្នទេ។ ការជ្រើសរើសដោយគ្មានការវិភាគស៊ីជម្រៅ និងចក្ខុវិស័យរយៈពេលវែង គឺធ្វើតាមការសម្រេចចិត្តរបស់អ្នកដទៃ និងជ្រើសរើសជំនាញទូទៅដែលមិនមែនជាជំនាញជាក់លាក់ ប្រឆាំងនឹងតម្រូវការការងារបច្ចុចុប្បន្ន។'); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>
    <?php endif; ?>
    <style>
      /* General Styling & Variables */
:root {
    --primary-color: #dc3545;
    --white-color: #ffffff;
    --dark-text: #333;
    --light-gray-bg: #f8f9fa;
    --shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}



.containerrds {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
}

.section-title-wrapper {
    text-align: center;
    margin-bottom: 4rem; /* Adjust as needed */
}

.title-underline {
    height: 0.3rem; /* Further increased height for better visibility */
    width: 10rem; /* Further increased width for better visibility */
    background-color: #dc3545; /* Explicit danger red color */
    margin: 1rem auto 0;
    border-radius: 9999px;
}

h1, h2, h3 {
    font-weight: 700;
    color: var(--dark-text);
}

h2 {
    text-align: center;
    font-size: 2.5rem;
    margin-bottom: 50px;
    position: relative;
}

/* Underline effect for section titles */
h2::after {
    content: '';
    display: block;
    width: 80px;
    height: 4px;
    background-color: var(--primary-color);
    margin: 10px auto 0;
    border-radius: 2px;
}

/* ANIMATIONS */
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in-up {
    opacity: 0; /* Start hidden */
    animation-duration: 1s;
    animation-timing-function: ease-out;
    animation-fill-mode: forwards;
    animation-name: fadeInUp;
}

/* --- Section 1: Hero / Banner --- */
.hero-section {
    background-color: var(--white-color);
    padding: 0px 0;
    text-align: center;
}

.hero-section h1 {
    font-size: 2.25rem; /* Default for mobile */
    margin-bottom: 15px;
    color: var(--primary-color);
    font-weight: 700;
}

@media (min-width: 768px) {
    .hero-section h1 {
        font-size: 2.5rem; /* Larger for desktop */
    }
}

.hero-section p {
    font-size: 1.2rem;
    max-width: 700px;
    margin: 0 auto 30px auto;
    color: var(--dark-text);
}

.banner-image {
    max-width: 1080px;
    margin: 0 auto;
    box-shadow: var(--shadow);
    border-radius: 10px;
    overflow: hidden;
}

.banner-image img {
    width: 100%;
    height: auto;
    display: block;
}

/* --- Section 2: Objectives, Goals, and Results --- */
.info-section {
    background-color: var(--light-gray-bg);
    padding: 60px 0;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

.info-card {
    background-color: var(--white-color);
    padding: 30px;
    border-radius: 10px;
    box-shadow: var(--shadow);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease, opacity 0.6s ease, transform 0.6s ease;
    opacity: 0;
    transform: translateY(20px);
}

.info-card.animate-in {
    opacity: 1;
    transform: translateY(0);
}

.info-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.info-card .icon {
    font-size: 3rem;
    color: var(--primary-color);
    margin-bottom: 20px;
}

.info-card h3 {
    font-size: 1.5rem;
    margin-bottom: 15px;
}

.info-card ul {
    list-style-type: none;
    padding: 0;
    text-align: left;
}

.info-card ul li {
    margin-bottom: 10px;
    padding-left: 20px;
    position: relative;
}

.info-card ul li::before {
    content: '\f00c';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    color: var(--primary-color);
    position: absolute;
    left: 0;
}

/* --- Section 3: Topics and Trainers --- */
.topics-section {
    padding: 60px 0;
    background-color: var(--white-color);
}

.trainers-visual {
    display: flex;
    justify-content: center;
    gap: 20px; /* Adjusted gap */
    margin-bottom: 30px;
}

/* Styling for individual trainer images */
#tat-image-left {
    width: 350px; /* Square width */
    height: 350px; /* Square height */
   
    border-radius: 12px;
    object-fit: cover;
    box-shadow: var(--shadow);
}

#tat-image-right {
     width: 710px; /* Rectangular width */
    height: 350px; /* Rectangular height */
    border-radius: 12px;
    object-fit: cover;
    box-shadow: var(--shadow);
    transition: transform 0.5s ease;
}

#tat-image-left:hover {
    transform: scale(1.03) rotate(-2deg);
}

#tat-image-right:hover {
    transform: scale(1.03) rotate(2deg);
}

.topics-description {
    text-align: center;
    max-width: 800px;
    margin: 0 auto 50px auto;
    font-size: 1.1rem;
}

.topics-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

.topic-card {
    background-color: var(--primary-color);
    color: var(--white-color);
    padding: 30px;
    border-radius: 10px;
    text-align: center;
    transition: transform 0.3s ease, opacity 0.6s ease, transform 0.6s ease;
    opacity: 0;
    transform: translateY(20px);
}

.topic-card.animate-in {
    opacity: 1;
    transform: translateY(0);
}

.topic-card:hover {
    transform: scale(1.05) translateY(-5px);
}

.topic-card .topic-icon {
    font-size: 3rem;
    margin-bottom: 20px;
}

.topic-card h3 {
    font-size: 1.5rem;
    margin-bottom: 15px;
    color: var(--white-color);
}

/* --- Section 4: Problems To Be Solved --- */
.problems-section {
    background-color: var(--light-gray-bg);
    padding: 60px 0;
}

.problems-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

/* <<< CHANGED THIS ENTIRE SECTION FOR PROBLEM CARD LAYOUT >>> */
.problem-card {
    background-color: var(--white-color);
    border-radius: 10px;
    box-shadow: var(--shadow);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease, opacity 0.6s ease, transform 0.6s ease;
    overflow: hidden; /* Important for containing the image */
    display: flex;
    flex-direction: column;
    opacity: 0;
    transform: translateY(20px);
}

.problem-card.animate-in {
    opacity: 1;
    transform: translateY(0);
}

.problem-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.problem-card .card-image {
    width: 100%;
    height: 220px; /* Controls the height of the image area */
}

.problem-card .card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.problem-card .card-content {
    padding: 25px; /* Padding for the text content */
    flex-grow: 1; /* Allows cards to have same height if content differs */
    display: flex;
    flex-direction: column;
}

.problem-card .problem-icon {
    font-size: 2.5rem;
    color: var(--primary-color);
    margin-bottom: 15px;
}

.problem-card h3 {
    font-size: 1.4rem;
    margin-bottom: 15px;
    color: var(--dark-text);
}
/* <<< END OF CHANGE >>> */


/* --- Responsive Design for Mobile Devices --- */
@media (max-width: 992px) {
    .info-grid, .topics-grid, .problems-grid {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 768px) {
    h1 {
        font-size: 2.5rem !important;
    }
    h2 {
        font-size: 2rem;
    }

    .info-grid, .topics-grid, .problems-grid {
        grid-template-columns: 1fr;
    }

    .trainers-visual {
        flex-direction: column;
        align-items: center;
        gap: 20px;
    }
    
    .trainers-visual #tat-image-left,
    .trainers-visual #tat-image-right {
        width: 100%; /* Make trainer images responsive */
        max-width: 350px; /* Max width for left image */
        height: auto;
    }
}
    </style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.info-card, .topic-card, .problem-card').forEach(card => {
        observer.observe(card);
    });
});
</script>


<div class="containerrds py-5" id="roadshow-program-section">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-4 fw-bold text-danger"><?php echo getLangText('Roadshow Program', 'កម្មវិធីបង្ហាញផ្លូវ'); ?></h1>
            <p class="lead"><?php echo getLangText('Join our educational roadshows featuring industry experts and educational professionals discussing important topics in education and career development.', 'ចូលរួមកម្មវិធីតាំងពិព័រណ៍អប់រំរបស់យើងដែលមានអ្នកជំនាញក្នុងឧស្សាហកម្ម និងអ្នកជំនាញអប់រំពិភាក្សាអំពីប្រធានបទសំខាន់ៗក្នុងការអប់រំ និងការអភិវឌ្ឍអាជីព។'); ?></p>
        </div>
    </div>
    
    <!-- Search Form -->
    <div class="row mb-4">
        <div class="col-md-6">
            <form method="GET" action="index.php" class="d-flex">
                <input type="hidden" name="page" value="program/roadshow/roadshow">
                <input type="text" name="search" class="form-control me-2" placeholder="Search roadshows..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit" class="btn btn-danger"><?php echo getLangText('Search', 'ស្វែងរក'); ?></button>
                <?php if ($isSearching): ?>
                    <a href="index.php?page=program/roadshow/roadshow#roadshow-program-section" class="btn btn-outline-secondary ms-2"><?php echo getLangText('Clear', 'សម្អាត'); ?></a>
                <?php endif; ?>
            </form>
        </div>
    </div>
    
    <?php if ($isSearching): ?>
        <div class="alert alert-info">
            <?php if (count($roadshows) > 0): ?>
                <?php echo getLangText('Found', 'រកឃើញ'); ?> <?php echo count($roadshows); ?> <?php echo getLangText('result', 'លទ្ធផល'); ?><?php echo count($roadshows) != 1 ? 's' : ''; ?> <?php echo getLangText('for', 'សម្រាប់'); ?> "<?php echo htmlspecialchars($searchTerm); ?>"
            <?php else: ?>
                <?php echo getLangText('No roadshows found matching', 'មិនមានកម្មវិធីតាំងពិព័រណ៍ណាដែលត្រូវនឹង'); ?> "<?php echo htmlspecialchars($searchTerm); ?>"
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (count($roadshows) > 0): ?>
        <div class="row">
            <?php foreach ($roadshows as $roadshow): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <?php if (!empty($roadshow['image_path'])): ?>
                            <img src="<?php echo htmlspecialchars($roadshow['image_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($roadshow['title']); ?>">
                        <?php endif; ?>
                        
                        <?php if (!empty($roadshow['video_url'])): ?>
                            <div class="ratio ratio-16x9">
                                <?php echo getVideoEmbedCode($roadshow['video_url']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($roadshow['title']); ?></h5>
                            <p class="card-text text-muted small">
                                <i class="fas fa-map-marker-alt me-1"></i> <?php echo htmlspecialchars($roadshow['location']); ?>
                                <br>
                                <i class="fas fa-calendar-alt me-1"></i> <?php echo formatDate($roadshow['event_date']); ?>
                            </p>
                            <div class="card-text mb-3">
                                <?php 
                                $summary = strip_tags($roadshow['summary'] ?? '');
                                echo strlen($summary) > 150 ? substr($summary, 0, 150) . '...' : $summary;
                                ?>
                            </div>
                            <a href="index.php?page=roadshow-detail&id=<?php echo $roadshow['id']; ?>" class="btn btn-outline-danger"><?php echo getLangText('View Details', 'មើលលម្អិត'); ?></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <?php if ($isSearching): ?>
                <p><?php echo getLangText('No roadshow content found matching your search. Please try different keywords.', 'មិនមានខ្លឹមសារកម្មវិធីតាំងពិព័រណ៍ណាដែលត្រូវនឹងការស្វែងរករបស់អ្នកទេ។ សូមព្យាយាមប្រើពាក្យគន្លឹះផ្សេងទៀត។'); ?></p>
            <?php else: ?>
                <p><?php echo getLangText('No roadshow content available at the moment. Please check back later.', 'មិនមានខ្លឹមសារកម្មវិធីតាំងពិព័រណ៍នៅពេលនេះទេ។ សូមពិនិត្យមើលម្តងទៀតនៅពេលក្រោយ។'); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
