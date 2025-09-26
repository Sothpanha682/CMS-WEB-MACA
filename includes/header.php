<?php
// Prevent direct access to this file
if (!defined('INCLUDED')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

// Get site settings
$site_name = '';
$site_logo = '';

// Check if database connection exists before trying to use it
if (isset($conn) && $conn) {
    $query = "SELECT value FROM site_settings WHERE setting_name = 'site_name'";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $site_name = $row['value'];
    }

    $query = "SELECT value FROM site_settings WHERE setting_name = 'site_logo'";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $site_logo = $row['value'];
    }
}

// Default values if not found in database
if (empty($site_name)) {
    $site_name = 'MACA';
}

// Default logo if not found in database
if (empty($site_logo) || !file_exists($site_logo)) {
    $site_logo = 'assets/images/maca-logo.png';
}

// Current page for active menu
$current_page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Check if there's a message to display
$message = '';
$message_type = '';
if (isset($_SESSION['message']) && isset($_SESSION['message_type'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

// Check if there's a redirect
if (isset($_SESSION['redirect_to'])) {
    $redirect_to = $_SESSION['redirect_to'];
    unset($_SESSION['redirect_to']);
    echo "<script>window.location.href = '$redirect_to';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="<?php echo isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $page_title = $site_name;
    $page_description = "MACA is Cambodia's leading platform for students to explore popular university majors, discover fulfilling career paths, and enhance skills. We offer online learning, personalized career counseling, internship programs, online recruitment, insightful talkshows, and engaging roadshows. Empowering your academic and professional journey.";
    $page_keywords = "MACA, Cambodia education, university majors, career guidance, online courses, career counseling, student internships, job placement, talk shows, road shows, popular careers, academic planning, skill development, higher education, student success, career development, job opportunities Cambodia, study abroad, professional training, youth empowerment";
    $og_title = $site_name . " - Cambodia's Premier Platform for Majors & Careers";
    $og_description = "MACA is Cambodia's leading platform for students to explore popular university majors, discover fulfilling career paths, and enhance skills. We offer online learning, personalized career counseling, internship programs, online recruitment, insightful talkshows, and engaging roadshows. Empowering your academic and professional journey.";

    switch ($current_page) {
        case 'home':
            $page_title = $site_name . " - Home";
            $page_description = "Welcome to MACA, your gateway to academic and career success in Cambodia. Explore popular majors, career paths, and skill development programs.";
            $page_keywords = "MACA home, Cambodia education, university, careers, skill development";
            $og_title = $site_name . " - Home";
            $og_description = "Welcome to MACA, your gateway to academic and career success in Cambodia. Explore popular majors, career paths, and skill development programs.";
            break;
        case 'about':
            $page_title = $site_name . " - About Us";
            $page_description = "Learn more about MACA's mission to empower Cambodian students through education, career guidance, and professional development.";
            $page_keywords = "About MACA, MACA mission, education Cambodia, career guidance";
            $og_title = $site_name . " - About Us";
            $og_description = "Learn more about MACA's mission to empower Cambodian students through education, career guidance, and professional development.";
            break;
        case 'program/online-learning':
            $page_title = $site_name . " - Online Learning";
            $page_description = "Discover flexible online learning opportunities with MACA. Enhance your skills and knowledge from anywhere, at your own pace.";
            $page_keywords = "Online learning Cambodia, online courses, skill enhancement, flexible education";
            $og_title = $site_name . " - Online Learning";
            $og_description = "Discover flexible online learning opportunities with MACA. Enhance your skills and knowledge from anywhere, at your own pace.";
            break;
        case 'program/career-counselling':
            $page_title = $site_name . " - Career Counselling";
            $page_description = "Get personalized career counseling from MACA experts. Plan your academic and professional journey with tailored advice.";
            $page_keywords = "Career counseling Cambodia, career guidance, professional advice, academic planning";
            $og_title = $site_name . " - Career Counselling";
            $og_description = "Get personalized career counseling from MACA experts. Plan your academic and professional journey with tailored advice.";
            break;
        case 'talkshow':
            $page_title = $site_name . " - Talkshow";
            $page_description = "Watch insightful talk shows from MACA, featuring industry leaders and experts sharing their experiences and advice.";
            $page_keywords = "MACA talk show, industry experts, career insights, educational talks";
            $og_title = $site_name . " - Talkshow";
            $og_description = "Watch insightful talk shows from MACA, featuring industry leaders and experts sharing their experiences and advice.";
            break;
        case 'roadshow':
            $page_title = $site_name . " - Roadshow";
            $page_description = "Join MACA's engaging roadshows to connect with universities, employers, and educational institutions across Cambodia.";
            $page_keywords = "MACA roadshow, education fair Cambodia, university events, career expos";
            $og_title = $site_name . " - Roadshow";
            $og_description = "Join MACA's engaging roadshows to connect with universities, employers, and educational institutions across Cambodia.";
            break;
        case 'program/internship/internship':
            $page_title = $site_name . " - Internship Program";
            $page_description = "Gain practical experience with MACA's internship programs. Connect with leading companies and kickstart your career.";
            $page_keywords = "Internship Cambodia, student internships, work experience, career kickstart";
            $og_title = $site_name . " - Internship Program";
            $og_description = "Gain practical experience with MACA's internship programs. Connect with leading companies and kickstart your career.";
            break;
        case 'program/online-recruitment':
            $page_title = $site_name . " - Online Recruitment";
            $page_description = "Find your next job opportunity with MACA's online recruitment platform. Connect with top employers in Cambodia.";
            $page_keywords = "Online recruitment Cambodia, job search, career opportunities, employment platform";
            $og_title = $site_name . " - Online Recruitment";
            $og_description = "Find your next job opportunity with MACA's online recruitment platform. Connect with top employers in Cambodia.";
            break;
        case 'explore/popular-majors':
            $page_title = $site_name . " - Popular Majors";
            $page_description = "Explore popular university majors in Cambodia with MACA. Find the right academic path for your future.";
            $page_keywords = "Popular majors Cambodia, university degrees, academic paths, study options";
            $og_title = $site_name . " - Popular Majors";
            $og_description = "Explore popular university majors in Cambodia with MACA. Find the right academic path for your future.";
            break;
        case 'explore/popular-jobs':
            $page_title = $site_name . " - Popular Careers";
            $page_description = "Discover popular career paths and job opportunities in Cambodia with MACA. Plan your professional future.";
            $page_keywords = "Popular careers Cambodia, job opportunities, career paths, employment trends";
            $og_title = $site_name . " - Popular Careers";
            $og_description = "Discover popular career paths and job opportunities in Cambodia with MACA. Plan your professional future.";
            break;
        case 'announcements':
            $page_title = $site_name . " - Announcements";
            $page_description = "Stay updated with the latest announcements from MACA, including events, programs, and important news.";
            $page_keywords = "MACA announcements, news, events, updates";
            $og_title = $site_name . " - Announcements";
            $og_description = "Stay updated with the latest announcements from MACA, including events, programs, and important news.";
            break;
        case 'news':
            $page_title = $site_name . " - News";
            $page_description = "Read the latest news and articles from MACA, covering education, career development, and youth empowerment in Cambodia.";
            $page_keywords = "MACA news, education articles, career development news, Cambodia youth";
            $og_title = $site_name . " - News";
            $og_description = "Read the latest news and articles from MACA, covering education, career development, and youth empowerment in Cambodia.";
            break;
        case 'contact':
            $page_title = $site_name . " - Contact Us";
            $page_description = "Get in touch with MACA. Contact us for inquiries about our programs, services, or partnerships.";
            $page_keywords = "Contact MACA, MACA support, inquiry, partnership";
            $og_title = $site_name . " - Contact Us";
            $og_description = "Get in touch with MACA. Contact us for inquiries about our programs, services, or partnerships.";
            break;
        default:
            // Default values are already set above
            break;
    }
    ?>
    <title><?php echo $page_title; ?></title>
    
    <meta name="description" content="<?php echo $page_description; ?>">
    <meta name="keywords" content="<?php echo $page_keywords; ?>">
    <meta property="og:title" content="<?php echo $og_title; ?>">
    <meta property="og:description" content="<?php echo $og_description; ?>">
    <meta property="og:image" content="https://mymaca.asia/assets/images/logomaca.png<?php echo $site_logo; ?>">
    <meta property="og:url" content="https://mymaca.asia/index.php?page=home<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
    <meta property="og:type" content="website">
    <meta name="author" content="MACA Team">
    <meta name="robots" content="index, follow">
    
    <link rel="canonical" href="https://www.yourwebsite.com/<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/styles.css" rel="stylesheet">
    <!-- Google Fonts - Kantumruy Pro -->
     <link rel="stylesheet" href="/assets/css/style.css">
     <link rel="stylesheet" href="/assets/css/styles.css">
     <link rel="stylesheet" href="/styles/globals.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
      <link
      rel="shortcut icon"
      href="./assets/images/logomaca.png"
      type="image/x-icon"
    />
    <style>
        /* Apply Kantumruy Pro to lang-kh class */
        .lang-kh {
            font-family: "Kantumruy Pro", sans-serif;
            font-optical-sizing: auto;
            font-weight: 400; /* Default weight, can be adjusted */
            font-style: normal;
        }

         .khmer-text {
            font-family: "Kantumruy Pro", sans-serif;
            font-optical-sizing: auto;
            font-weight: 400; /* Default weight, can be adjusted */
            font-style: normal;
        }

        /* Custom header styles */
        .main-header {
            background-color: #dc3545;
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .logo-container img {
            height: 50px;
            width: auto;
        }
        
        .nav-link {
            color: gray !important;
            font-weight: 500;
            padding: 10px 15px !important;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-link:hover, .nav-link:focus {
            color: #f5a425 !important;
        }
        
        .nav-item.active .nav-link {
            color: #f5a425 !important;
            font-weight: 600;
        }
        
        .dropdown-menu {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 0.5rem 0;
            margin-top: 0;
            border-top: 3px solid #f5a425;
            display: none; /* Hide by default for hover effect */
            position: absolute; /* Ensure it positions correctly */
            z-index: 1000; /* Ensure it's above other content */
        }
        
        .dropdown-item {
            padding: 0.5rem 1.5rem;
            color: #333;
            transition: all 0.2s ease;
        }
        
        .dropdown-item:hover, .dropdown-item:focus {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        
        /* Hover effect for dropdowns */
        .nav-item.dropdown:hover .dropdown-menu {
            display: block;
        }

        /* Ensure dropdown toggle doesn't show arrow on hover for desktop */
        @media (min-width: 992px) {
            .nav-item.dropdown:hover .nav-link.dropdown-toggle::after {
                transform: rotate(180deg); /* Rotate arrow on hover */
            }
        }

        .get-started-btn {
            background-color: white;
            color: #dc3545 !important;
            border-radius: 30px;
            padding: 8px 20px !important;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .get-started-btn:hover {
            background-color: #f5a425;
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        /* Language switcher */
        .language-switcher {
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            padding: 3px;
            margin-right: 10px;
        }
        
        .language-switcher a {
            color: white;
            text-decoration: none;
            font-size: 0.9rem;
            padding: 5px 10px;
            border-radius: 15px;
            transition: all 0.3s ease;
        }
        
        .language-switcher a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .language-switcher a.active {
            background-color: white;
            color: #dc3545;
            font-weight: 600;
        }
        
        /* Mobile menu */
        .navbar-toggler {
            border: none;
            color: white;
            padding: 0;
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        @media (max-width: 991.98px) {
            .navbar-collapse {
                background-color: #dc3545;
                padding: 1rem;
                border-radius: 0.5rem;
                margin-top: 0.5rem;
            }
            
            .dropdown-menu {
                background-color: rgba(255, 255, 255, 0.1);
                border: none;
                box-shadow: none;
            }
            
            .dropdown-item {
                color: white;
            }
            
            .dropdown-item:hover {
                background-color: rgba(255, 255, 255, 0.2);
                color: #f5a425;
            }
            
            .get-started-btn {
                margin-top: 10px;
                display: inline-block;
            }
            
            /* Adjust language switcher for mobile when outside collapse */
            .language-switcher.d-lg-none {
                margin-right: 10px; /* Space between language switcher and toggler */
                margin-left: auto; /* Push to the left of the toggler */
            }
        }

        /* New rule to make header text white without changing .nav-link directly */
        nav.navbar-dark .navbar-nav .nav-link {
            color: white !important;
        }
    </style>
</head>
<body class="<?php echo isset($_SESSION['lang']) && $_SESSION['lang'] == 'kh' ? 'lang-kh' : 'lang-en'; ?>">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container">
            <a class="navbar-brand" href="index.php">
            <?php
$site_logo = "assets/images/maca_logo.png"; // adjust this path
$site_name = "SunLove";
?>
<img src="<?php echo $site_logo; ?>" alt="<?php echo $site_name; ?>" height="50">

            </a>
            <!-- Language Switcher for mobile -->
            <div class="language-switcher d-lg-none me-2">
                <a href="actions/change-language.php?lang=en&redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="<?php echo (!isset($_SESSION['lang']) || $_SESSION['lang'] == 'en') ? 'active' : ''; ?>">EN</a>
                <a href="actions/change-language.php?lang=kh&redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="<?php echo (isset($_SESSION['lang']) && $_SESSION['lang'] == 'kh') ? 'active' : ''; ?>">ខ្មែរ</a>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'home' ? 'active' : ''; ?>" href="index.php?page=home">
                            <?php echo getLangText('Home', 'ទំព័រដើម'); ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'about' ? 'active' : ''; ?>" href="index.php?page=about">
                            <?php echo getLangText('About Us', 'អំពីយើង'); ?>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo strpos($current_page, 'program/') === 0 ? 'active' : ''; ?>" href="#" id="programDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo getLangText('Program', 'កម្មវិធី'); ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="programDropdown">
                            <li>
                                <a class="dropdown-item" href="index.php?page=talkshow">
                                    <?php echo getLangText('Talkshow', 'កម្មវិធីសន្ទនា'); ?>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="index.php?page=roadshow">
                                    <?php echo getLangText('Roadshow', 'កម្មវិធីបង្ហាញផ្លូវ'); ?>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="index.php?page=program/internship/internship">
                                    <?php echo getLangText('Internship Program', 'កម្មវិធីកម្មសិក្សា'); ?>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="index.php?page=program/online-recruitment">
                                    <?php echo getLangText('Online Recruitment', 'ការជ្រើសរើសតាមអនឡាញ'); ?>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="index.php?page=program/online-learning">
                                    <?php echo getLangText('Online Learning', 'រៀនតាមអនឡាញ'); ?>
                                </a>
                            </li>  
                             <li>
                                <a class="dropdown-item" href="index.php?page=program/career-counselling">
                                    <?php echo getLangText('Career Counselling', 'ពិភាក្សាតាមអនឡាញ'); ?>
                                </a>
                            </li>                             
                            
                        </ul>
                    </li>
                    <!-- Explore Dropdown Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo strpos($current_page, 'explore/') === 0 ? 'active' : ''; ?>" href="#" id="exploreDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo getLangText('Explore', 'រុករក'); ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="exploreDropdown">
                            <li>
                                <a class="dropdown-item" href="index.php?page=explore/popular-majors">
                                    <?php echo getLangText('Popular Majors', 'មុខជំនាញពេញនិយម'); ?>
                                </a>
                            </li>
                            <li>
                                 <a class="dropdown-item" href="index.php?page=explore/popular-jobs">
                                    <?php echo getLangText('Popular Careers', 'អាជីពពេញនិយម'); ?>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'announcements' ? 'active' : ''; ?>" href="index.php?page=announcements">
                            <?php echo getLangText('Announcements', 'សេចក្តីប្រកាស'); ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'news' ? 'active' : ''; ?>" href="index.php?page=news">
                            <?php echo getLangText('News', 'ព័ត៌មាន'); ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'contact' ? 'active' : ''; ?>" href="index.php?page=contact">
                            <?php echo getLangText('Contact Us', 'ទំនាក់ទំនងយើង'); ?>
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center d-none d-lg-flex"> <!-- Hide on small screens, show on large -->
                    <!-- Language Switcher -->
                    <div class="language-switcher">
                        <a href="actions/change-language.php?lang=en&redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="<?php echo (!isset($_SESSION['lang']) || $_SESSION['lang'] == 'en') ? 'active' : ''; ?>">EN</a>
                        <a href="actions/change-language.php?lang=kh&redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="<?php echo (isset($_SESSION['lang']) && $_SESSION['lang'] == 'kh') ? 'active' : ''; ?>">ខ្មែរ</a>
                    </div>
                    <?php if (isLoggedIn()): ?>
                        <div class="dropdown ms-2">
                            <button class="btn btn-light dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-1"></i> <?php echo $_SESSION['username']; ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="index.php?page=dashboard"><i class="fas fa-tachometer-alt me-1"></i> Dashboard</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="includes/logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="index.php?page=contact" class="btn get-started-btn">
                            <i class="fas fa-rocket me-1"></i> <?php echo getLangText('Get Started', 'ចាប់ផ្តើម'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Display message if any -->
    <?php if (!empty($message)): ?>
        <div class="container mt-3">
            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="py-4">

    <div class="container mt-4">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type'] ?? 'info'; ?> alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
        <?php endif; ?>
