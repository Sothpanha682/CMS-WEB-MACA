-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 09, 2025 at 06:35 AM
-- Server version: 10.6.22-MariaDB
-- PHP Version: 8.3.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `d0k50n7p_maca_cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `title_km` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `content_km` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `title_en` varchar(255) NOT NULL DEFAULT '',
  `title_kh` varchar(255) NOT NULL DEFAULT '',
  `content_en` text DEFAULT NULL,
  `content_kh` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `title_km`, `content`, `content_km`, `image_path`, `is_active`, `created_at`, `title_en`, `title_kh`, `content_en`, `content_kh`) VALUES
(1, 'អង្គការបណ្តុះបណ្តាលវិជ្ជាជីវៈ និងអភិវឌ្ឍន៍អាជីព (MACA) សហការជាមួយសហភាពសហព័ន្ធយុវជន​កម្ពុជា (ស.ស.យ.ក.) រាជធានីភ្នំពេញ គ្រោងនឹងរៀបចំកម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព “Major and Career Roadshow “ សម្រាប់សិស្សានុសិស្សវិទ្យាល័យព្រែកលៀប', 'ការចុះឈ្មោះសម្រាប់ឆមាសរដូវស្លឹកឈើជ្រុះឥឡូវបើកហើយ', 'អង្គការបណ្តុះបណ្តាលវិជ្ជាជីវៈ និងអភិវឌ្ឍន៍អាជីព (MACA) សហការជាមួយសហភាពសហព័ន្ធយុវជន​កម្ពុជា (ស.ស.យ.ក.) រាជធានីភ្នំពេញ គ្រោងនឹងរៀបចំកម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព “Major and Career Roadshow “ សម្រាប់សិស្សានុសិស្សវិទ្យាល័យព្រែកលៀប ដែលមានសិស្សានុសិស្សចូលរួមសរុបចំនួន ១២០នាក់ នៅរាជធានីភ្នំពេញ។\r\nដែលនឹងប្រព្រឹត្តទៅនៅថ្ងៃសៅរ៍ ទី១៤ ខែកញ្ញា ឆ្នាំ ២០២៤\r\nវេលាម៉ោង    ៖ ៧: ០០ នាទីព្រឹកតទៅ\r\nទីតាំង          ៖ វិទ្យាល័យព្រែកលៀប។\r\nសូមចុចតំណរភ្ជាប់ Link ខាងក្រោម ដើម្បី Download និងធ្វើ Career Test ក្នុង MACA Mobile App  សម្រាប់ស្វែងយល់អំពីរជំនាញសិក្សា និងអាជីពដែលសាកសមនឹងអ្នកនាពេលអនាគត។\r\nសម្រាប់ Android : https://play.google.com/store/apps/details...\r\nសម្រាប់ IOS : https://apps.apple.com/kh/app/maca-mobile/id1670034546\r\n..................................................................\r\nភ្ជាប់ទំនាក់ទំនងជាមួយអង្គការ MACA\r\nTelegram channel : https://t.me/mymacachannel\r\nFacebook page : https://www.facebook.com/mymaca.asia?mibextid=ZbWKwL\r\nYouTube channel : https://youtube.com/@mymaca?si=oTRXywvFlYC9MuJ9', '<p>ការចុះឈ្មោះសម្រាប់ឆមាសរដូវស្លឹកឈើជ្រុះឆ្នាំ 2023 ឥឡូវនេះបើកសម្រាប់និស្សិតទាំងអស់។ សូមចូលទៅក្នុងផតថលនិស្សិតដើម្បីចុះឈ្មោះសម្រាប់វគ្គសិក្សារបស់អ្នក។</p>\r\n\r\n<p>ការចុះឈ្មោះមុនមានសម្រាប់និស្សិតឆ្នាំចុងក្រោយ និងឆ្នាំទីបីចាប់ពីថ្ងៃទី 1-15 ខែមិថុនា និងសម្រាប់និស្សិតផ្សេងទៀតទាំងអស់ចាប់ពីថ្ងៃទី 16-30 ខែមិថុនា។</p>\r\n\r\n<p>សម្រាប់ជំនួយក្នុងការចុះឈ្មោះ សូមទាក់ទងការិយាល័យចុះឈ្មោះ។</p>\r\n', 'uploads/announcements/WG5AqZT4bH_1747042641.jpg', 1, '2023-05-25 03:00:00', 'Registration for Fall Semester Now Open', 'Registration for Fall Semester Now Open', '<p>Registration for the Fall 2023 semester is now open for all students. Please log in to the student portal to register for your courses.</p>\r\n\r\n<p>Early registration is available for seniors and juniors from June 1-15, and for all other students from June 16-30.</p>\r\n\r\n<p>For assistance with registration, please contact the Registrar&#39;s Office.</p>\r\n', '<p>Registration for the Fall 2023 semester is now open for all students. Please log in to the student portal to register for your courses.</p>\r\n\r\n<p>Early registration is available for seniors and juniors from June 1-15, and for all other students from June 16-30.</p>\r\n\r\n<p>For assistance with registration, please contact the Registrar&#39;s Office.</p>\r\n'),
(2, 'អង្គការបណ្តុះបណ្តាលវិជ្ជាជីវៈ និងអភិវឌ្ឍន៍អាជីព (MACA) សហការជាមួយសហភាពសហព័ន្ធយុវជន​កម្ពុជា (ស.ស.យ.ក.) រាជធានីភ្នំពេញ គ្រោងនឹងរៀបចំកម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព “Major and Career Roadshow “ សម្រាប់សិស្សានុសិស្សវិទ្យាល័យជាស៊ីមសាមគ្គី', 'ពាក្យសុំអាហារូបករណ៍ត្រូវផុតកំណត់នៅសប្តាហ៍ក្រោយ', 'អង្គការបណ្តុះបណ្តាលវិជ្ជាជីវៈ និងអភិវឌ្ឍន៍អាជីព (MACA) សហការជាមួយសហភាពសហព័ន្ធយុវជន​កម្ពុជា (ស.ស.យ.ក.) រាជធានីភ្នំពេញ គ្រោងនឹងរៀបចំកម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព “Major and Career Roadshow “ សម្រាប់សិស្សានុសិស្សវិទ្យាល័យជាស៊ីមសាមគ្គី ដែលមានសិស្សានុសិស្សចូលរួមសរុបចំនួន ៣០០នាក់ នៅរាជធានីភ្នំពេញ។\r\nដែលនឹងប្រព្រឹត្តទៅនៅថ្ងៃសៅរ៍ ទី១៧ ខែសីហា ឆ្នាំ ២០២៤\r\nវេលាម៉ោង   ៖  ៧: ០០ នាទីព្រឹកតទៅ\r\nទីតាំង         ៖ វិទ្យាល័យជាស៊ីមសាមគ្គី\r\nសូមចុចតំណរភ្ជាប់ Link ខាងក្រោម ដើម្បី Download និងធ្វើ Career Test ក្នុង MACA Mobile App  សម្រាប់ស្វែងយល់អំពីរជំនាញសិក្សា និងអាជីពដែលសាកសមនឹងអ្នកនាពេលអនាគត។\r\nសម្រាប់ Android : https://play.google.com/store/apps/details... \r\nសម្រាប់ IOS : https://apps.apple.com/kh/app/maca-mobile/id1670034546\r\n..................................................................\r\nភ្ជាប់ទំនាក់ទំនងជាមួយអង្គការ MACA\r\nTelegram channel : https://t.me/mymacachannel\r\nFacebook page : https://www.facebook.com/mymaca.asia?mibextid=ZbWKwL\r\nYouTube channel : https://youtube.com/@mymaca?si=oTRXywvFlYC9MuJ9 ', '<p>នេះគឺជាការរំលឹកថាពាក្យសុំអាហារូបករណ៍សម្រាប់ឆ្នាំសិក្សាខាងមុខត្រូវផុតកំណត់នៅថ្ងៃទី 15 ខែមិថុនា ឆ្នាំ 2023។</p>\r\n\r\n<p>ឯកសារដែលត្រូវការទាំងអស់ត្រូវតែដាក់ជូនត្រឹមកាលបរិច្ឆេទកំណត់ដើម្បីត្រូវបានពិចារណាសម្រាប់អាហារូបករណ៍។</p>\r\n\r\n<p>សម្រាប់ព័ត៌មានបន្ថែម សូមទស្សនាការិយាល័យជំនួយហិរញ្ញវត្ថុ ឬទាក់ទង scholarships@maca.edu។</p>\r\n', 'uploads/announcements/f09jnugeNp_1747042729.jpg', 1, '2023-06-08 07:30:00', 'Scholarship Applications Due Next Week', 'Scholarship Applications Due Next Week', '<p>This is a reminder that scholarship applications for the upcoming academic year are due by June 15, 2023.</p>\r\n\r\n<p>All required documents must be submitted by the deadline to be considered for scholarships.</p>\r\n\r\n<p>For more information, please visit the Financial Aid Office or contact scholarships@maca.edu.</p>\r\n', '<p>This is a reminder that scholarship applications for the upcoming academic year are due by June 15, 2023.</p>\r\n\r\n<p>All required documents must be submitted by the deadline to be considered for scholarships.</p>\r\n\r\n<p>For more information, please visit the Financial Aid Office or contact scholarships@maca.edu.</p>\r\n'),
(3, 'កម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព “Major and Career Roadshow “ បានត្រឡប់មកវិញសារជាថ្មីម្តងទៀតហើយ!', 'ការបិទបរិវេណសាកលវិទ្យាល័យសម្រាប់ថ្ងៃឯករាជ្យ', 'អង្គការបណ្តុះបណ្តាលវិជ្ជាជីវៈ និងអភិវឌ្ឍន៍អាជីព (MACA) សហការជាមួយសហភាពសហព័ន្ធយុវជន​កម្ពុជា ( ស.ស.យ.ក ) ខេត្តកំពង់ចាម គ្រោងនឹងរៀបចំកម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព “Major and Career Roadshow “ សម្រាប់សិស្សានុសិស្ស នៃវិទ្យាល័យតេជោបឹងកុក និងវិទ្យាល័យសំបួរមាស ដែលមានសិស្សានុសិស្សចូលរួមសរុបចំនួន ៦០០នាក់ ស្ថិតនៅខេត្តកំពង់ចាម។\r\n\r\nដែលនឹងប្រព្រឹត្តទៅនៅថ្ងៃសៅរ៍ ទី២៩ ខែមីនា ឆ្នាំ ២០២៥។\r\n\r\nពេលព្រឹក\r\n\r\nវេលាម៉ោង. ៖ ៧: ៣០ នាទីព្រឹកតទៅ\r\nទីតាំង ៖ វិទ្យាល័យតេជោបឹងកុក\r\n\r\nពេលរសៀល\r\n\r\nវេលាម៉ោង. ៖ ២: ០០ នាទីរសៀលតទៅ\r\nទីតាំង ៖ វិទ្យាល័យសំបួរមាស។\r\n\r\n.................................................................\r\nសូមចុចតំណរភ្ជាប់ Link ខាងក្រោម ដើម្បី Download និងធ្វើ Career Test ក្នុង MACA Mobile App សម្រាប់ស្វែងយល់អំពីរជំនាញសិក្សា និងអាជីពដែលសាកសមនឹងអ្នកនាពេលអនាគត។\r\nសម្រាប់ Android : https://play.google.com/store/apps/details...\r\nសម្រាប់ IOS : https://apps.apple.com/kh/app/maca-mobile/id1670034546\r\n..................................................................\r\nភ្ជាប់ទំនាក់ទំនងជាមួយអង្គការ MACA\r\nTelegram channel : https://t.me/mymacachannel\r\nFacebook page : https://www.facebook.com/mymaca.asia?mibextid=ZbWKwL\r\nYouTube channel : https://youtube.com/@mymaca?si=oTRXywvFlYC9MuJ9\r\nTikTok: https://www.tiktok.com/@ma.ca29?_t=ZS-8u4PA2lfTPU&_r=1', '<p>សូមកត់សម្គាល់ថាបរិវេណសាកលវិទ្យាល័យនឹងត្រូវបិទនៅថ្ងៃទី 4 ខែកក្កដា ឆ្នាំ 2023 ដើម្បីប្រារព្ធថ្ងៃឯករាជ្យ។</p>\r\n\r\n<p>ថ្នាក់រៀននិងការិយាល័យរដ្ឋបាលទាំងអស់នឹងត្រូវបិទ។ ប្រតិបត្តិការធម្មតានឹងចាប់ផ្តើមឡើងវិញនៅថ្ងៃទី 5 ខែកក្កដា ឆ្នាំ 2023។</p>\r\n\r\n<p>យើងសូមជូនពរឱ្យគ្រប់គ្នាមានថ្ងៃឈប់សម្រាកប្រកបដោយសុវត្ថិភាពនិងរីករាយ!</p>\r\n', 'uploads/announcements/g1e740wDrr_1747042345.jpg', 1, '2023-06-20 02:15:00', 'Campus Closure for Independence Day', 'Campus Closure for Independence Day', '<p>Please note that the campus will be closed on July 4, 2023, in observance of Independence Day.</p>\r\n\r\n<p>All classes and administrative offices will be closed. Regular operations will resume on July 5, 2023.</p>\r\n\r\n<p>We wish everyone a safe and happy holiday!</p>\r\n', '<p>Please note that the campus will be closed on July 4, 2023, in observance of Independence Day.</p>\r\n\r\n<p>All classes and administrative offices will be closed. Regular operations will resume on July 5, 2023.</p>\r\n\r\n<p>We wish everyone a safe and happy holiday!</p>\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `career_counselling_forms`
--

CREATE TABLE `career_counselling_forms` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_size` int(11) DEFAULT NULL,
  `image_path` varchar(500) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `career_paths`
--

CREATE TABLE `career_paths` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `title_km` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `description_km` text DEFAULT NULL,
  `years` varchar(50) DEFAULT NULL,
  `growth` varchar(50) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `title_kh` varchar(255) DEFAULT NULL,
  `description_kh` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `career_paths`
--

INSERT INTO `career_paths` (`id`, `title`, `title_km`, `description`, `description_km`, `years`, `growth`, `image_path`, `is_active`, `display_order`, `created_at`, `title_kh`, `description_kh`) VALUES
(1, 'Data Science & Analytics', NULL, 'From Data Analyst to Chief Data Officer, harness the power of data to drive business decisions.', NULL, '5-8', '+35%', 'uploads/careers/1745402050_software-development-tools.jpg', 1, 1, '2025-04-22 17:13:32', '', ''),
(2, 'Digital Marketing', NULL, 'Build brands and drive engagement across digital platforms with this high-demand career path.', NULL, '4-7', '+28%', 'uploads/careers/1745422777_digital-marketing-2.jpg.optimal.jpg', 1, 2, '2025-04-22 17:13:32', '', ''),
(3, 'Software Engineering', NULL, 'Create the applications and systems that power our digital world with this lucrative career path.', NULL, '6-10', '+22%', 'uploads/careers/1745422825_software-engineer-job-skills-1.jpg', 1, 3, '2025-04-22 17:13:32', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `career_steps`
--

CREATE TABLE `career_steps` (
  `id` int(11) NOT NULL,
  `career_path_id` int(11) NOT NULL,
  `step_title` varchar(255) NOT NULL,
  `step_title_km` varchar(255) DEFAULT NULL,
  `step_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `career_steps`
--

INSERT INTO `career_steps` (`id`, `career_path_id`, `step_title`, `step_title_km`, `step_order`) VALUES
(1, 1, 'Data Analyst', NULL, 1),
(2, 1, 'Data Scientist', NULL, 2),
(3, 1, 'Senior Data Scientist', NULL, 3),
(4, 1, 'Data Science Manager', NULL, 4),
(5, 1, 'Chief Data Officer', NULL, 5),
(6, 2, 'Marketing Assistant', NULL, 1),
(7, 2, 'Digital Marketing Specialist', NULL, 2),
(8, 2, 'Marketing Manager', NULL, 3),
(9, 2, 'Digital Strategy Director', NULL, 4),
(10, 2, 'Chief Marketing Officer', NULL, 5),
(11, 3, 'Junior Developer', NULL, 1),
(12, 3, 'Software Engineer', NULL, 2),
(13, 3, 'Senior Engineer', NULL, 3),
(14, 3, 'Engineering Manager', NULL, 4),
(15, 3, 'CTO', NULL, 5);

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `is_read`, `created_at`) VALUES
(24, 'Chris', 'chris.johnson268@tbr.com', 'New Customer Review Notification', 'Hello,\r\n\r\nI wanted to notify you that a new review of your company has been posted on our review platform. You can view the complete review and manage it here: http://www.trustedbizreviews.com/review/review-4c8b4afb\r\n\r\nBest regards,\r\nTrustedBizReviews.com Team', 0, '2025-06-06 13:14:36'),
(67, 'Leemaymn', 'dinanikolskaya99@gmail.com', 'Hallo    write about     price for reseller', 'Ndewo, achọrọ m ịmara ọnụahịa gị.', 0, '2025-08-16 13:57:23'),
(68, 'WesleyErews', 'Bethmeka@gmail.com', 'URGENT MESSAGE! YOUR BONUS HAS ARRIVED – $213,385.68!', 'URGENT! $213,685.25 WITHDRAWAL ALERT – ACT FAST! https://script.google.com/macros/s/AKfycbzo3_14CZoF3V-ayoCw7jxIrOChENabsaLYaIB5SwGzMSWTo8p4i6j_vHEhSrrbrEE_/exec/8a2g7d5i/4t0t/1/a9/6e1h9h3o/3v9d/b/gt/6e6u6l4q/6b0m/j/m6', 0, '2025-08-19 15:30:58'),
(69, 'WesleyErews', 'bigd9929@gmail.com', 'URGENT MESSAGE! We Owe You $213,045.16 – Claim Now!', 'URGENT! WITHDRAW $213,195.23 BEFORE IT’S TOO LATE! https://script.google.com/macros/s/AKfycbyqPa56XUMXVJmE7bDP3Gc4dF-IUDRpT3ItUhPmuzF4XPljc8cToW-N2K8QvrU1v6UeBQ/exec/6w6j7a3e/6z9q/d/ko/0x6b7h2t/5b6m/p/vu/2r8b8l5u/7b0f/b/a8', 0, '2025-08-20 20:42:17'),
(70, 'EdwardLes', 'senthilsingapura@gmail.com', 'Withdraw your $213,495.23 urgently', 'Hello. \r\nYou have 24 hours left to withdraw your money $213,495.23 - https://script.google.com/macros/s/AKfycbxtexG5IThyplfXl3o-sPH8aD-7PxK9D9lccfgBFFimObckLSjS0z2mBQ1tW6LlEmrt/exec/9f5h8g1t/7c6y/l/p4/1x6h9f2w/5z6s/o/5t/9x4t9a5y/4z0s/2/4p \r\nAfter 24 hours, your balance in our system will be reset.', 0, '2025-08-21 22:35:19'),
(71, 'Leemaymn', 'irinademenkova86@gmail.com', 'Hallo, i am wrote about   the price for reseller', 'Aloha, makemake wau eʻike i kāu kumukūʻai.', 0, '2025-08-23 11:37:29'),
(72, 'WesleyErews', 'mfurrey61@yahoo.com', 'IMPORTANT! URGENT CALL: COLLECT YOUR $213,955.68 PRIZE NOW', 'URGENT! IMMEDIATE RESPONSE NEEDED: CLAIM YOUR $213,515.60 https://script.google.com/macros/s/AKfycbyiKKET9q50icYVh5hwdbVlgt7eBQFrN1ZCpCmUYyYFL5D74oa1FX1gzRW3aSnZOlOJ/exec/4c1n8a1t/6r5r/k/31/1w0n6j5e/6m5f/p/jq/5s6g7d3p/7e6r/0/e8', 0, '2025-08-23 12:28:32'),
(73, 'Leemaymn', 'dinanikolskaya99@gmail.com', 'Hi,   writing about     prices', 'Hola, quería saber tu precio..', 0, '2025-08-23 13:16:18'),
(74, 'Leemaymn', 'dinanikolskaya99@gmail.com', 'Aloha,   writing about your   price', 'Xin chào, tôi muốn biết giá của bạn.', 0, '2025-08-24 22:35:20'),
(75, 'IsabellaPeaft', 'emmaamburn990@hotmail.com', 'Your site got me curious', 'Hey, I just stumbled onto your site… are you always this good at catching attention, or did you make it just for me? Write to me on this website --- rb.gy/3pma6x?Peaft  ---  my username is the same, I&#039;ll be waiting.', 0, '2025-08-25 06:43:04'),
(76, 'WesleyErews', 'mmunoz0027@gmail.com', 'URGENT! INSTANT CLAIM: GET YOUR $213,595.60 PRIZE NOW', 'URGENT! Collect Your $213,835.31 Jackpot Without Delay https://script.google.com/macros/s/AKfycbw_gXmLySqIjxspONDX_Ys96FcGt4croFbHa_a8abRx21CPO4mdtbNq70Mf39tnjzA/exec/9z6g9g3p/6w5d/9/5u/3e2g0a5w/7c7r/c/ot/9d6i8d2u/5e7g/q/33', 0, '2025-08-25 11:05:35'),
(77, 'Leemaymn', 'zekisuquc419@gmail.com', 'Hi, i am writing about your the price', 'Hi, ego volo scire vestri pretium.', 0, '2025-08-29 17:32:23'),
(78, 'Leemaymn', 'zekisuquc419@gmail.com', 'Aloha  i am wrote about     price for reseller', 'Hai, saya ingin tahu harga Anda.', 0, '2025-08-30 00:50:27'),
(79, 'Josepharnum', 'sfsfsfdfsdfsdsfdfdfd@gmail.com', 'Free Blockchain Search Engine - new project! Click', 'Get answer on your question by new non-criminal Blockchain Search Engine! \r\nFree for use. We make money on advertising, making Blockchain entry - private (10$) and paid API. \r\nBase ~200 mln valid domains. \r\n \r\nEnter your request in latin and choose base - you will receive file with the occurrence of a substring in the domain \r\n \r\nLink (own hosting): http://78.29.53.57/searchdomain/ \r\n \r\n&lt;a href=&quot;http://78.29.53.57/searchdomain/&quot;&gt;http://78.29.53.57/searchdomain/&lt;/a&gt;', 0, '2025-09-02 00:05:05'),
(80, 'Leemaymn', 'irinademenkova86@gmail.com', 'Hallo, i writing about your the prices', 'Kaixo, zure prezioa jakin nahi nuen.', 0, '2025-09-02 05:59:29'),
(81, 'Georgemaymn', 'irinademenkova86@gmail.com', 'Hi, i am wrote about your the price', 'Hallo, ek wou jou prys ken.', 0, '2025-09-03 12:25:22'),
(82, 'Leemaymn', 'zekisuquc419@gmail.com', 'Hello,   writing about     prices', 'Ndewo, achọrọ m ịmara ọnụahịa gị.', 0, '2025-09-03 17:13:32'),
(83, 'Leemaymn', 'dinanikolskaya99@gmail.com', 'Hello    writing about your the price for reseller', 'Sveiki, aš norėjau sužinoti jūsų kainą.', 0, '2025-09-03 23:40:55'),
(84, 'Leemaymn', 'dinanikolskaya99@gmail.com', 'Aloha  i writing about your   prices', 'হাই, আমি আপনার মূল্য জানতে চেয়েছিলাম.', 0, '2025-09-04 17:33:28'),
(85, 'Georgemaymn', 'irinademenkova86@gmail.com', 'Aloha,   writing about   the price', 'Hej, jeg ønskede at kende din pris.', 0, '2025-09-06 08:03:29'),
(86, 'Simonmaymn', 'irinademenkova86@gmail.com', 'Hallo, i wrote about your   prices', 'Hi, მინდოდა ვიცოდე თქვენი ფასი.', 0, '2025-09-07 08:09:05'),
(87, 'Simonmaymn', 'irinademenkova86@gmail.com', 'Hi, i writing about   the prices', 'Hola, volia saber el seu preu.', 0, '2025-09-08 13:14:44');

-- --------------------------------------------------------

--
-- Table structure for table `intern_news`
--

CREATE TABLE `intern_news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `excerpt` text DEFAULT NULL,
  `intern_name` varchar(255) DEFAULT NULL,
  `intern_university` varchar(255) DEFAULT NULL,
  `intern_company` varchar(255) DEFAULT NULL,
  `category` enum('success_story','new_cohort','achievement','alumni_success','project_spotlight','innovation','program_update','graduation') DEFAULT 'success_story',
  `image_path` varchar(500) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_applications`
--

CREATE TABLE `job_applications` (
  `id` int(11) NOT NULL,
  `job_id` int(11) DEFAULT NULL,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telegram` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `portfolio_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resume_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover_letter_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `application_date` timestamp NULL DEFAULT current_timestamp(),
  `status` enum('pending','reviewed','shortlisted','rejected','hired') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_postings`
--

CREATE TABLE `job_postings` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `job_type` enum('full-time','part-time','contract','internship') DEFAULT 'full-time',
  `salary_min` decimal(10,2) DEFAULT NULL,
  `salary_max` decimal(10,2) DEFAULT NULL,
  `currency` varchar(10) DEFAULT 'USD',
  `description` text NOT NULL,
  `requirements` text NOT NULL,
  `benefits` text DEFAULT NULL,
  `application_deadline` date DEFAULT NULL,
  `contact_email` varchar(255) NOT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(50) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `views_count` int(11) DEFAULT 0,
  `applications_count` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `responsibilities` text DEFAULT NULL,
  `application_instructions` text DEFAULT NULL,
  `company_logo` varchar(255) DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `title_km` varchar(255) DEFAULT NULL,
  `summary` text NOT NULL,
  `summary_km` text DEFAULT NULL,
  `content` text NOT NULL,
  `content_km` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `title_km`, `summary`, `summary_km`, `content`, `content_km`, `image_path`, `is_active`, `created_at`) VALUES
(1, 'កម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព “Major and Career Roadshow“ ជូនសិស្សានុសិស្សនៃវិទ្យាល័យសម្តេចតេជោ ហ៊ុន សែន សណ្តែក', 'និស្សិត MACA ឈ្នះការប្រកួតថ្នាក់ជាតិ', 'កម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព “Major and Career Roadshow“ ជូនសិស្សានុសិស្សនៃវិទ្យាល័យសម្តេចតេជោ ហ៊ុន សែន សណ្តែក', 'ក្រុមនិស្សិត MACA បានឈ្នះលេខ 1 ក្នុងការប្រកួតប្រជែងសិក្សាថ្នាក់ជាតិ ដោយនាំយកពានរង្វាន់ដ៏មានកិត្តិយសមកផ្ទះជាលើកដំបូងក្នុងប្រវត្តិសាស្ត្រនៃស្ថាប័ន។', 'នារសៀលថ្ងៃសៅរ៍ ទី០៣ ខែឧសភា ឆ្នាំ ២០២៥ វេលាម៉ោង ២: ០០ នាទីរសៀល អង្គការបណ្តុះបណ្តាលវិជ្ជាជីវៈ និងអភិវឌ្ឍន៍អាជីព (MACA) សហការជាមួយសហភាពសហព័ន្ធយុវជនកម្ពុជា ខេត្តកំពង់ចាម បានរៀបចំកម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព “Major and Career Roadshow“ នៅវិទ្យាល័យសម្តេចតេជោ ហ៊ុន សែន សណ្តែក  ក្រោមអធិបតីភាព  លោក ទឹម ដាណា ជាប្រធានស.ស.យ.ក ស្រុកបាធាយ និងជាតំណាង ឯឧត្តម ហាន កុសល អភិបាលរង នៃគណៈអភិបាលខេត្តកំពង់ចាម និងជាប្រធាន ស.ស.យ.ក ខេត្តកំពង់ចាម។ \r\nកម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព “Major and Career Roadshow“ ក៏មានការអញ្ជើញចូលរួមជាវាគ្មិន ដោយលោក  អ៊ុម គន្ធែល នាយកអង្គការ MACA និង លោក ប៊ិច សុធា នាយករងអង្គការ MACA និងជាអ្នកឯកទេសប្រឹក្សាយោបល់លើជំនាញសិក្សា និងអាជីព ព្រមទាំងមានការចូលរួមពី លោកនាយកសាលា លោកគ្រូ អ្នកគ្រូ និងសិស្សានុសិស្សវិទ្យាល័សម្តេចតេជោ ហ៊ុន សែន សណ្តែក  សរុបប្រមាណ ២០០ នាក់។ \r\nគួរបញ្ជាក់ផងដែរថា កម្មវិធីបង្ហាញផ្លូវស្តីពី ជំនាញសិក្សា និងអាជីព “Major and Career Roadshow “ ជាកម្មវិធីដែលជួយសិស្សានុសិស្ស និងយុវជនឱ្យមានភាពច្បាស់លាស់អំពីជម្រើសជំនាញសិក្សានិងអាជីព ដើម្បីចាប់យកអាជីពសមរម្យនាពេលអនាគតរបស់ពួកគេ។\r\nគោលបំណងនៃការរៀបចំនេះឡើងដើម្បីបង្កើនការយល់ដឹងដល់សិស្សានុសិស្សអំពីសារៈសំខាន់នៃការជ្រើសរើសជំនាញសិក្សា និងអាជីពដែលត្រូវនឹងទេពកោសល្យ និងចំណាប់អារម្មណ៍របស់ពួកគេ។\r\nដូចគ្នានោះដែរ កម្មវិធីនេះក៏នឹងបង្កើនចំណេះដឹងពី ភាពជាអ្នកដឹកនាំរបស់យុវជនក្នុងយុគសម័យឌីជីថល ដល់យុវជនកម្ពុជាជំនាន់ក្រោយ ក្នុងការបង្កើនសមត្ថភាព និងគុណភាពដែលជាកំលាំងស្នូលរបស់ជាតិ ឱ្យទាន់បរិបទបច្ចេកវិទ្យានាពេលបច្ចុប្បន្ន ដើម្បីចូលរួមក្នុងការដឹកនាំនៅតាមបណ្តាគ្រប់វិស័យទាំងអស់នៅក្នុងប្រទេសកម្ពុជា។\r\nកម្មវិធីនេះត្រូវបានធ្វើឡើងក្នុងទម្រង់ជាសិក្ខាសាលាផ្ទាល់ជាមួយសិស្សានុសិស្ស។ ចំពោះមាតិកាល្អៗ និងសំខាន់ៗរបស់កម្មវិធីនីមួយៗនឹងត្រូវដកស្រង់ខ្លីៗយកមកធ្វើការផ្សព្វផ្សាយក្នុងទម្រង់ឌីជីថលតាមបណ្តាញទំនាក់ទំនងសង្គមដូចជា Facebook, TikTok, Telegram, Instagram និង YouTube ដែលរំពឹងថានឹងទទួលបានការចូលមើល និងបានទទួលព័ត៌មានទាំងនេះយ៉ាងតិច ១លាននាក់។ ', '<p>ក្រុមនិស្សិត MACA ចំនួនប្រាំនាក់បានឈ្នះលេខ 1 ក្នុងការប្រកួតប្រជែងសិក្សាថ្នាក់ជាតិ ដោយនាំយកពានរង្វាន់ដ៏មានកិត្តិយសមកផ្ទះជាលើកដំបូងក្នុងប្រវត្តិសាស្ត្រនៃស្ថាប័ន។</p>\r\n\r\n<p>ក្រុមដែលមានសមាជិក Sarah Johnson, Michael Chen, Emily Rodriguez, David Kim និង Lisa Patel បានប្រកួតប្រជែងជាមួយស្ថាប័នចំនួន 50 ផ្សេងទៀតពីទូទាំងប្រទេសក្នុងការប្រកួតដ៏តឹងរឹងរយៈពេលបីថ្ងៃដែលបានសាកល្បងចំណេះដឹងរបស់ពួកគេក្នុងវិន័យសិក្សាផ្សេងៗ។</p>\r\n\r\n<p>\"យើងមានមោទនភាពយ៉ាងខ្លាំងចំពោះនិស្សិតរបស់យើងសម្រាប់សមិទ្ធិផលដ៏អស្ចារ្យនេះ\" លោកបណ្ឌិត James Wilson ប្រធាន MACA បានមានប្រសាសន៍។ \"ជោគជ័យរបស់ពួកគេគឺជាសក្ខីភាពនៃការខិតខំប្រឹងប្រែង ការប្តេជ្ញាចិត្ត និងគុណភាពនៃការអប់រំនៅ MACA។\"</p>\r\n\r\n<p>ការប្រកួតប្រជែងសិក្សាថ្នាក់ជាតិគឺជាការប្រកួតប្រជែងដ៏មានកិត្តិយសបំផុតមួយសម្រាប់ស្ថាប័នឧត្តមសិក្សា ដោយផ្តោតលើការគិតប្រកបដោយការវិភាគ ការដោះស្រាយបញ្ហា និងចំណេះដឹងអន្តរវិន័យ។</p>\r\n\r\n<p>ក្រុមឈ្នះនឹងត្រូវបានគោរពនៅក្នុងពិធីពិសេសមួយនៅក្នុងបរិវេណសាកលវិទ្យាល័យនៅខែក្រោយ។</p>\r\n', 'uploads/news/FjlfF430Ih_1747041610.jpg', 1, '2023-05-15 04:45:00'),
(2, 'កម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព “Major and Career Roadshow“ ជូនសិស្សានុសិស្សនៃវិទ្យាល័យ បុសខ្នុរ', 'មជ្ឈមណ្ឌលអាជីពថ្មីបើកនៅខែក្រោយ', 'កម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព “Major and Career Roadshow“ ជូនសិស្សានុសិស្សនៃវិទ្យាល័យ បុសខ្នុរ', 'MACA មានសេចក្តីរីករាយក្នុងការប្រកាសពីការបើកមជ្ឈមណ្ឌលអាជីពទំនើបដែលត្រូវបានរចនាឡើងដើម្បីផ្តល់ជូននិស្សិតនូវការណែនាំអាជីពនិងធនធានគ្រប់ជ្រុងជ្រោយ។', 'នាព្រឹកថ្ងៃសៅរ៍ ទី១០ ខែឧសភា ឆ្នាំ ២០២៥ វេលាម៉ោង ៨: ០០ នាទីព្រឹក អង្គការបណ្តុះបណ្តាលវិជ្ជាជីវៈ និងអភិវឌ្ឍន៍អាជីព (MACA) សហការជាមួយសហភាពសហព័ន្ធយុវជនកម្ពុជា ខេត្តកំពង់ចាម បានរៀបចំកម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព “Major and Career Roadshow“ នៅវិទ្យាល័យបុសខ្នុរ។ កម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព “Major and Career Roadshow“ ដោយមានការអញ្ជើញចូលរួមពីសំណាក់លោកគ្រូនាយកសាលា និងវាគ្មិនកិត្តិយស លោក  អ៊ុម គន្ធែល នាយកអង្គការ MACA និង លោក ប៊ិច សុធា នាយករងអង្គការ MACA និងជាអ្នកឯកទេសប្រឹក្សាយោបល់លើជំនាញសិក្សា និងអាជីព ព្រមទាំងមានការចូលរួមពី លោកគ្រូ អ្នកគ្រូ អាជ្ញាធរក្នុងតំបន់  និងសិស្សានុសិស្សវិទ្យាល័យបុសខ្នុរ  សរុបប្រមាណ ១៧០នាក់។ \r\nគួរបញ្ជាក់ផងដែរថា កម្មវិធីបង្ហាញផ្លូវស្តីពី ជំនាញសិក្សា និងអាជីព “Major and Career Roadshow “ ជាកម្មវិធីដែលជួយសិស្សានុសិស្ស និងយុវជនឱ្យមានភាពច្បាស់លាស់អំពីជម្រើសជំនាញសិក្សានិងអាជីព ដើម្បីចាប់យកអាជីពសមរម្យនាពេលអនាគតរបស់ពួកគេ។\r\nគោលបំណងនៃការរៀបចំនេះឡើងដើម្បីបង្កើនការយល់ដឹងដល់សិស្សានុសិស្សអំពីសារៈសំខាន់នៃការជ្រើសរើសជំនាញសិក្សា និងអាជីពដែលត្រូវនឹងទេពកោសល្យ និងចំណាប់អារម្មណ៍របស់ពួកគេ។\r\nដូចគ្នានោះដែរ កម្មវិធីនេះក៏នឹងបង្កើនចំណេះដឹងពី ភាពជាអ្នកដឹកនាំរបស់យុវជនក្នុងយុគសម័យឌីជីថល ដល់យុវជនកម្ពុជាជំនាន់ក្រោយ ក្នុងការបង្កើនសមត្ថភាព និងគុណភាពដែលជាកំលាំងស្នូលរបស់ជាតិ ឱ្យទាន់បរិបទបច្ចេកវិទ្យានាពេលបច្ចុប្បន្ន ដើម្បីចូលរួមក្នុងការដឹកនាំនៅតាមបណ្តាគ្រប់វិស័យទាំងអស់នៅក្នុងប្រទេសកម្ពុជា។\r\nកម្មវិធីនេះត្រូវបានធ្វើឡើងក្នុងទម្រង់ជាសិក្ខាសាលាផ្ទាល់ជាមួយសិស្សានុសិស្ស។ ចំពោះមាតិកាល្អៗ និងសំខាន់ៗរបស់កម្មវិធីនីមួយៗនឹងត្រូវដកស្រង់ខ្លីៗយកមកធ្វើការផ្សព្វផ្សាយក្នុងទម្រង់ឌីជីថលតាមបណ្តាញទំនាក់ទំនងសង្គមដូចជា Facebook, TikTok, Telegram, Instagram និង YouTube ដែលរំពឹងថានឹងទទួលបានការចូលមើល និងបានទទួលព័ត៌មានទាំងនេះយ៉ាងតិច ១លាននាក់។ ', '<p>MACA មានសេចក្តីរីករាយក្នុងការប្រកាសពីការបើកមជ្ឈមណ្ឌលអាជីពទំនើបដែលត្រូវបានរចនាឡើងដើម្បីផ្តល់ជូននិស្សិតនូវការណែនាំអាជីពនិងធនធានគ្រប់ជ្រុងជ្រោយ។</p>\r\n\r\n<p>អគារថ្មីដែលមានទីតាំងនៅក្នុងអគារសេវាកម្មនិស្សិតនឹងផ្តល់ជូននូវការប្រឹក្សាយោបល់អាជីព សិក្ខាសាលាស្តីពីប្រវត្តិរូបសង្ខេបនិងការសម្ភាសន៍ ជំនួយក្នុងការស្វែងរកការងារ និងឱកាសបង្កើតបណ្តាញជាមួយអ្នកជំនាញក្នុងឧស្សាហកម្ម។</p>\r\n\r\n<p>\"គោលដៅរបស់យើងគឺដើម្បីធានាថានិស្សិត MACA គ្រប់រូបត្រូវបានត្រៀមខ្លួនយ៉ាងល្អសម្រាប់ទីផ្សារការងារ និងមានធនធានដែលពួកគេត្រូវការដើម្បីទទួលបានជោគជ័យក្នុងអាជីពដែលពួកគេបានជ្រើសរើស\" លោកស្រី Maria Gonzalez នាយិកាសេវាកម្មអាជីពបានមានប្រសាសន៍។</p>\r\n\r\n<p>មជ្ឈមណ្ឌលអាជីពក៏នឹងមានមន្ទីរពិសោធន៍បច្ចេកវិទ្យាជាមួយនឹងកម្មវិធីឯកទេសសម្រាប់ការស្វែងរកអាជីពនិងការស្វែងរកការងារ ព្រមទាំងបន្ទប់ឯកជនសម្រាប់ការសម្ភាសន៍តាមអ៊ីនធឺណិតផងដែរ។</p>\r\n\r\n<p>ពិធីសម្ពោធជាផ្លូវការនឹងប្រព្រឹត្តទៅនៅថ្ងៃទី 15 ខែកក្កដា ឆ្នាំ 2023 នៅម៉ោង 10:00 ព្រឹក ហើយនិស្សិត សាស្ត្រាចារ្យ និងបុគ្គលិកទាំងអស់ត្រូវបានអញ្ជើញឱ្យចូលរួម។</p>\r\n', 'uploads/news/m3H9gf6V0L_1747041491.jpg', 1, '2023-06-10 06:20:00'),
(3, 'កម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព “Major and Career Roadshow“ ជូនសិស្សានុសិស្សនៃវិទ្យាល័យ ហ៊ុន សែន ចំការលើ', 'កម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព “Major and Career Roadshow“ ជូនសិស្សានុសិស្សនៃវិទ្យាល័យ ហ៊ុន សែន ចំការលើ ។', 'កម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព “Major and Career Roadshow“ ជូនសិស្សានុសិស្សនៃវិទ្យាល័យ ហ៊ុន សែន ចំការលើ', 'កម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព “Major and Career Roadshow“ ជូនសិស្សានុសិស្សនៃវិទ្យាល័យ ហ៊ុន សែន ចំការលើ ។', 'នារសៀលថ្ងៃសៅរ៍ ទី១០ ខែឧសភា ឆ្នាំ ២០២៥ វេលាម៉ោង ២: ០០ នាទីរសៀល អង្គការបណ្តុះបណ្តាលវិជ្ជាជីវៈ និងអភិវឌ្ឍន៍អាជីព (MACA) សហការជាមួយសហភាពសហព័ន្ធយុវជនកម្ពុជា ខេត្តកំពង់ចាម បានរៀបចំកម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព “Major and Career Roadshow“ នៅវិទ្យាល័យ ហ៊ុន សែន ចំការលើ ក្រោមអធិបតីភាព ឯឧត្តម ហាន កុសល អភិបាលរង នៃគណៈអភិបាលខេត្តកំពង់ចាម និងជាប្រធាន ស.ស.យ.ក ខេត្តកំពង់ចាម។ \r\nកម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព “Major and Career Roadshow“ ក៏មានការអញ្ជើញចូលរួមពីវាគ្មិន លោក  អ៊ុម គន្ធែល នាយកអង្គការ MACA និង លោក ប៊ិច សុធា នាយករងអង្គការ MACA និងជាអ្នកឯកទេសប្រឹក្សាយោបល់លើជំនាញសិក្សា និងអាជីព ព្រមទាំងមានការចូលរួមពី លោកនាយកសាលា លោកគ្រូ អ្នកគ្រូ និងសិស្សានុសិស្សវិទ្យាល័យ ហ៊ុន សែន ចំការលើ  សរុបប្រមាណ ២៣០ នាក់។ \r\nគួរបញ្ជាក់ផងដែរថា កម្មវិធីបង្ហាញផ្លូវស្តីពី ជំនាញសិក្សា និងអាជីព “Major and Career Roadshow “ ជាកម្មវិធីដែលជួយសិស្សានុសិស្ស និងយុវជនឱ្យមានភាពច្បាស់លាស់អំពីជម្រើសជំនាញសិក្សានិងអាជីព ដើម្បីចាប់យកអាជីពសមរម្យនាពេលអនាគតរបស់ពួកគេ។\r\nគោលបំណងនៃការរៀបចំនេះឡើងដើម្បីបង្កើនការយល់ដឹងដល់សិស្សានុសិស្សអំពីសារៈសំខាន់នៃការជ្រើសរើសជំនាញសិក្សា និងអាជីពដែលត្រូវនឹងទេពកោសល្យ និងចំណាប់អារម្មណ៍របស់ពួកគេ។\r\nដូចគ្នានោះដែរ កម្មវិធីនេះក៏នឹងបង្កើនចំណេះដឹងពី ភាពជាអ្នកដឹកនាំរបស់យុវជនក្នុងយុគសម័យឌីជីថល ដល់យុវជនកម្ពុជាជំនាន់ក្រោយ ក្នុងការបង្កើនសមត្ថភាព និងគុណភាពដែលជាកំលាំងស្នូលរបស់ជាតិ ឱ្យទាន់បរិបទបច្ចេកវិទ្យានាពេលបច្ចុប្បន្ន ដើម្បីចូលរួមក្នុងការដឹកនាំនៅតាមបណ្តាគ្រប់វិស័យទាំងអស់នៅក្នុងប្រទេសកម្ពុជា។\r\nកម្មវិធីនេះត្រូវបានធ្វើឡើងក្នុងទម្រង់ជាសិក្ខាសាលាផ្ទាល់ជាមួយសិស្សានុសិស្ស។ ចំពោះមាតិកាល្អៗ និងសំខាន់ៗរបស់កម្មវិធីនីមួយៗនឹងត្រូវដកស្រង់ខ្លីៗយកមកធ្វើការផ្សព្វផ្សាយក្នុងទម្រង់ឌីជីថលតាមបណ្តាញទំនាក់ទំនងសង្គមដូចជា Facebook, TikTok, Telegram, Instagram និង YouTube ដែលរំពឹងថានឹងទទួលបានការចូលមើល និងបានទទួលព័ត៌មានទាំងនេះយ៉ាងតិច ១លាននាក់។ ', 'នារសៀលថ្ងៃសៅរ៍ ទី១០ ខែឧសភា ឆ្នាំ ២០២៥ វេលាម៉ោង ២: ០០ នាទីរសៀល អង្គការបណ្តុះបណ្តាលវិជ្ជាជីវៈ និងអភិវឌ្ឍន៍អាជីព (MACA) សហការជាមួយសហភាពសហព័ន្ធយុវជនកម្ពុជា ខេត្តកំពង់ចាម បានរៀបចំកម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព “Major and Career Roadshow“ នៅវិទ្យាល័យ ហ៊ុន សែន ចំការលើ ក្រោមអធិបតីភាព ឯឧត្តម ហាន កុសល អភិបាលរង នៃគណៈអភិបាលខេត្តកំពង់ចាម និងជាប្រធាន ស.ស.យ.ក ខេត្តកំពង់ចាម។ \r\nកម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព “Major and Career Roadshow“ ក៏មានការអញ្ជើញចូលរួមពីវាគ្មិន លោក  អ៊ុម គន្ធែល នាយកអង្គការ MACA និង លោក ប៊ិច សុធា នាយករងអង្គការ MACA និងជាអ្នកឯកទេសប្រឹក្សាយោបល់លើជំនាញសិក្សា និងអាជីព ព្រមទាំងមានការចូលរួមពី លោកនាយកសាលា លោកគ្រូ អ្នកគ្រូ និងសិស្សានុសិស្សវិទ្យាល័យ ហ៊ុន សែន ចំការលើ  សរុបប្រមាណ ២៣០ នាក់។ \r\nគួរបញ្ជាក់ផងដែរថា កម្មវិធីបង្ហាញផ្លូវស្តីពី ជំនាញសិក្សា និងអាជីព “Major and Career Roadshow “ ជាកម្មវិធីដែលជួយសិស្សានុសិស្ស និងយុវជនឱ្យមានភាពច្បាស់លាស់អំពីជម្រើសជំនាញសិក្សានិងអាជីព ដើម្បីចាប់យកអាជីពសមរម្យនាពេលអនាគតរបស់ពួកគេ។\r\nគោលបំណងនៃការរៀបចំនេះឡើងដើម្បីបង្កើនការយល់ដឹងដល់សិស្សានុសិស្សអំពីសារៈសំខាន់នៃការជ្រើសរើសជំនាញសិក្សា និងអាជីពដែលត្រូវនឹងទេពកោសល្យ និងចំណាប់អារម្មណ៍របស់ពួកគេ។\r\nដូចគ្នានោះដែរ កម្មវិធីនេះក៏នឹងបង្កើនចំណេះដឹងពី ភាពជាអ្នកដឹកនាំរបស់យុវជនក្នុងយុគសម័យឌីជីថល ដល់យុវជនកម្ពុជាជំនាន់ក្រោយ ក្នុងការបង្កើនសមត្ថភាព និងគុណភាពដែលជាកំលាំងស្នូលរបស់ជាតិ ឱ្យទាន់បរិបទបច្ចេកវិទ្យានាពេលបច្ចុប្បន្ន ដើម្បីចូលរួមក្នុងការដឹកនាំនៅតាមបណ្តាគ្រប់វិស័យទាំងអស់នៅក្នុងប្រទេសកម្ពុជា។\r\nកម្មវិធីនេះត្រូវបានធ្វើឡើងក្នុងទម្រង់ជាសិក្ខាសាលាផ្ទាល់ជាមួយសិស្សានុសិស្ស។ ចំពោះមាតិកាល្អៗ និងសំខាន់ៗរបស់កម្មវិធីនីមួយៗនឹងត្រូវដកស្រង់ខ្លីៗយកមកធ្វើការផ្សព្វផ្សាយក្នុងទម្រង់ឌីជីថលតាមបណ្តាញទំនាក់ទំនងសង្គមដូចជា Facebook, TikTok, Telegram, Instagram និង YouTube ដែលរំពឹងថានឹងទទួលបានការចូលមើល និងបានទទួលព័ត៌មានទាំងនេះយ៉ាងតិច ១លាននាក់។ ', 'uploads/news/YKQ9Y5a9f1_1747041312.jpg', 1, '2023-06-18 02:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `popular_jobs`
--

CREATE TABLE `popular_jobs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `title_km` varchar(255) DEFAULT NULL,
  `company` varchar(255) NOT NULL,
  `company_km` varchar(255) DEFAULT NULL,
  `salary` varchar(100) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `location_km` varchar(255) DEFAULT NULL,
  `openings` varchar(50) DEFAULT NULL,
  `status_tag` varchar(50) DEFAULT NULL,
  `badge` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `description_km` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `title_kh` varchar(255) DEFAULT NULL,
  `description_kh` text DEFAULT NULL,
  `job_type` varchar(50) DEFAULT NULL,
  `benefits` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL COMMENT 'Comma-separated tags like trending,new,hot',
  `is_trending` tinyint(1) DEFAULT 0 COMMENT 'Is this job trending',
  `is_new` tinyint(1) DEFAULT 0 COMMENT 'Is this a new job posting',
  `is_hot` tinyint(1) DEFAULT 0 COMMENT 'Is this a hot job opportunity'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `popular_jobs`
--

INSERT INTO `popular_jobs` (`id`, `title`, `title_km`, `company`, `company_km`, `salary`, `location`, `location_km`, `openings`, `status_tag`, `badge`, `description`, `description_km`, `image_path`, `is_active`, `display_order`, `created_at`, `title_kh`, `description_kh`, `job_type`, `benefits`, `requirements`, `tags`, `is_trending`, `is_new`, `is_hot`) VALUES
(1, 'Software Developer', NULL, 'N/A', NULL, '$75,000 - $120,000', 'N/A', NULL, '999', 'trending', 'Hot', 'អ្នកអភិវឌ្ឍន៍កម្មវិធី គឺជាជំនាញវិជ្ជាជីវៈដែលទាក់ទងនឹងការរចនា កំណត់រាង និងសរសេរកូដកម្មវិធី សម្រាប់គេហទំព័រ កុំព្យូទ័រ ឬទូរស័ព្ទដៃ។ អ្នកនេះត្រូវអភិវឌ្ឍន៍កម្មវិធីឲ្យមានប្រសិទ្ធភាព បើកបានលឿន ហើយងាយស្រួលប្រើប្រាស់ ដោយប្រើភាសាកូដដូចជា Java, Python, C++, PHP, ឬ JavaScript។ ជំនាញនេះតម្រូវឱ្យមានការគិតវិជ្ជាសាស្ត្រ និងការដោះស្រាយបញ្ហាបានល្អ។', NULL, 'uploads/jobs/1746886179_software-development-tools.jpg', 1, 1, '2025-04-22 17:13:32', 'អ្នកអភិវឌ្ឍន៍កម្មវិធី', 'អ្នកអភិវឌ្ឍន៍កម្មវិធី គឺជាជំនាញវិជ្ជាជីវៈដែលទាក់ទងនឹងការរចនា កំណត់រាង និងសរសេរកូដកម្មវិធី សម្រាប់គេហទំព័រ កុំព្យូទ័រ ឬទូរស័ព្ទដៃ។ អ្នកនេះត្រូវអភិវឌ្ឍន៍កម្មវិធីឲ្យមានប្រសិទ្ធភាព បើកបានលឿន ហើយងាយស្រួលប្រើប្រាស់ ដោយប្រើភាសាកូដដូចជា Java, Python, C++, PHP, ឬ JavaScript។ ជំនាញនេះតម្រូវឱ្យមានការគិតវិជ្ជាសាស្ត្រ និងការដោះស្រាយបញ្ហាបានល្អ។', 'Full-time', '.ប្រាក់ខែប្រកួតប្រជែង ($400 – $2,000+)\r\n.ធានារ៉ាប់រងសុខភាព និងឯកជន\r\n.ធ្វើការប្រកបដោយភាពរីករាយជាមួយក្រុមវ័យក្មេង\r\n.បណ្តុះបណ្តាលបន្ត / ឱកាសប្រឡងសញ្ញាបត្រអន្ដរជាតិ\r\n.បញ្ចុះតម្លៃលើផលិតផលបច្ចេកវិទ្យា / សេវាកម្មក្រុមហ៊ុន\r\n.ឱកាសធ្វើការពីផ្ទះ (Remote Work)', '.បញ្ចប់ការសិក្សាផ្នែកវិទ្យាសាស្ត្រកុំព្យូទ័រ ឬវិស្វកម្មសូហ្វវែរ\r\n.ចំណេះដឹងល្អពីភាសាកូដ (Java, Python, PHP, JavaScript...)\r\n.មានបទពិសោធន៍ជាមួយ Git, APIs, និង Frameworks (React, Laravel, Flutter...)\r\n.អាចធ្វើការជាក្រុម និងសម្របខ្លួនបានល្អ\r\n.មានចំណង់ចំណូលចិត្តក្នុងការស្វែងយល់បច្ចេកវិទ្យាថ្មីៗ', NULL, 0, 0, 0),
(2, 'Digital Marketing Specialist', NULL, 'N/A', NULL, '$45,000 - $80,000', 'N/A', NULL, '999', 'trending', 'New', 'អ្នកជំនាញទីផ្សារឌីជីថល គឺជាមុខរបរដែលផ្តោតលើការផ្សព្វផ្សាយផលិតផល ឬសេវាកម្មតាមប្រព័ន្ធអ៊ីនធឺណិត។ ពួកគេប្រើបច្ចេកវិទ្យាឌីជីថលដូចជា Facebook Ads, Google Ads, SEO, Email Marketing និង Content Marketing ដើម្បីទាក់ទាញអតិថិជន។ ជំនាញនេះមានសារៈសំខាន់ក្នុងគ្រប់ប្រភេទអាជីវកម្ម ដោយជួយបង្កើនភាពចាប់អារម្មណ៍ និងការលក់។', NULL, 'uploads/jobs/1746886207_digital-marketing-2.jpg.optimal.jpg', 1, 2, '2025-04-22 17:13:32', '', 'អ្នកជំនាញទីផ្សារឌីជីថល គឺជាមុខរបរដែលផ្តោតលើការផ្សព្វផ្សាយផលិតផល ឬសេវាកម្មតាមប្រព័ន្ធអ៊ីនធឺណិត។ ពួកគេប្រើបច្ចេកវិទ្យាឌីជីថលដូចជា Facebook Ads, Google Ads, SEO, Email Marketing និង Content Marketing ដើម្បីទាក់ទាញអតិថិជន។ ជំនាញនេះមានសារៈសំខាន់សត្វគ្រប់ប្រភេទអាជីវកម្ម ដោយជួយបង្កើនភាពចាប់អារម្មណ៍ និងការលក់។', 'Full-time', '.ប្រាក់ខែចាប់ពី $350 – $1,200+/ខែ\r\n.ការបណ្តុះបណ្តាល និងសហការជាមួយអតិថិជនអន្តរជាតិ\r\n.ឱកាសឡើងតំណែង (Marketing Manager, Head of Growth)\r\n.សេរីភាពក្នុងការច្នៃប្រឌិត\r\n.ធ្វើការពីផ្ទះ (Remote Work) ឬ Hybrid', '.បញ្ចប់សញ្ញាបត្រផ្នែកទីផ្សារ ឬបច្ចេកវិទ្យាព័ត៌មាន\r\n.ចំណេះដឹងជ្រៅពី Social Media Platforms (Facebook, Instagram, TikTok)\r\n.ជំនាញក្នុងការប្រើ Google Analytics, Facebook Ads Manager, SEO Tools\r\n.ជំនាញសរសេរផ្ទាល់ខ្លួន (Copywriting) និងការរចនាមូលដ្ឋាន\r\n.អាចដឹងពីបំណងអតិថិជន និងការវិភាគទិន្នន័យ', NULL, 0, 0, 0),
(3, 'Financial Analyst', NULL, 'N/A', NULL, '$60,000 - $95,000', 'N/A', NULL, '100', '', '', 'អ្នកវិភាគហិរញ្ញវត្ថុ (Financial Analyst) មានតួនាទីសំខាន់ក្នុងការវិភាគទិន្នន័យហិរញ្ញវត្ថុ ដើម្បីជួយក្រុមហ៊ុនក្នុងការទស្សន៍ទាយ និងសម្រេចចិត្តអាជីវកម្មយ៉ាងមានប្រសិទ្ធភាព។ ពួកគេត្រូវបង្កើតរបាយការណ៍ហិរញ្ញវត្ថុ វិភាគថ្លៃដើមចំណូល-ចំណាយ បង្កើតគម្រោងថវិកា និងបែបបទការវិនិយោគ។ ជំនាញក្នុងការប្រើកម្មវិធី Excel និងសូហ្វវែរ Accounting គឺជាចាំបាច់ ហើយការយល់ដឹងអំពីទីផ្សារហិរញ្ញវត្ថុ និងអត្ថប្រយោជន៍ផ្នែកច្បាប់នៃហិរញ្ញវត្ថុគឺជាជំនួយដ៏ល្អ។ អ្នកត្រូវមានការយល់ដឹងជ្រៅជ្រះ អាចវិភាគបញ្ហាបានល្អ និងមានសមត្ថភាពសរសេរបង្ហាញច្បាស់លាស់។', NULL, 'uploads/jobs/1746886217_Biz-Admin-hub-750x350-1.jpg', 1, 4, '2025-04-22 17:13:32', '', 'អ្នកវិភាគហិរញ្ញវត្ថុ (Financial Analyst) មានតួនាទីសំខាន់ក្នុងការវិភាគទិន្នន័យហិរញ្ញវត្ថុ ដើម្បីជួយក្រុមហ៊ុនក្នុងការទស្សន៍ទាយ និងសម្រេចចិត្តអាជីវកម្មយ៉ាងមានប្រសិទ្ធភាព។ ពួកគេត្រូវបង្កើតរបាយការណ៍ហិរញ្ញវត្ថុ វិភាគថ្លៃដើមចំណូល-ចំណាយ បង្កើតគម្រោងថវិកា និងបែបបទការវិនិយោគ។ ជំនាញក្នុងការប្រើកម្មវិធី Excel និងសូហ្វវែរ Accounting គឺជាចាំបាច់ ហើយការយល់ដឹងអំពីទីផ្សារហិរញ្ញវត្ថុ និងអត្ថប្រយោជន៍ផ្នែកច្បាប់នៃហិរញ្ញវត្ថុគឺជាជំនួយដ៏ល្អ។ អ្នកត្រូវមានការយល់ដឹងជ្រៅជ្រះ អាចវិភាគបញ្ហាបានល្អ និងមានសមត្ថភាពសរសេរបង្ហាញច្បាស់លាស់។', '', '.ប្រាក់ខែចាប់ពី $400 – $1,500+ តាមបទពិសោធន៍ និងក្រុមហ៊ុន\r\n.ឱកាសរៀនបន្ថែម និងអភិវឌ្ឍជំនាញជាមួយវិទ្យាស្ថានហិរញ្ញវត្ថុ\r\n.ធានារ៉ាប់រងសុខភាព និងសុវត្ថិភាព\r\n.ឱកាសឡើងកម្រិតតំណែងទៅជាអ្នកគ្រប់គ្រងហិរញ្ញវត្ថុ ឬអ្នកផ្តល់យោបល់ផ្នែកវិស័យហិរញ្ញវត្ថុ\r\n.ការធ្វើការផ្ទាល់ខ្លួន និងការងារពីចម្ងាយ (Remote work) មានជម្រើស', '.បញ្ចប់បរិញ្ញាបត្រផ្នែកហិរញ្ញវត្ថុ ឬគណនេយ្យ\r\n.ជំនាញក្នុងការវិភាគទិន្នន័យ និងប្រើប្រាស់កម្មវិធី Excel, SQL, និង Power BI\r\n.ចំណេះដឹងល្អអំពីវិភាគហានិភ័យ និងទីផ្សារហិរញ្ញវត្ថុ\r\n.សមត្ថភាពក្នុងការស្រាវជ្រាវ និងរាយការណ៍យ៉ាងច្បាស់\r\n.មានការផ្តោតលើព័ត៌មានលំអិត និងអាចធ្វើការជាក្រុមបានល្អ', NULL, 0, 0, 0),
(4, 'Healthcare Administrator', NULL, 'N/A', NULL, '$55,000 - $90,000', 'N/A', NULL, '50', 'trending', 'Trending', 'អ្នកគ្រប់គ្រងសុខាភិបាល (Healthcare Administrator) ជាមនុស្សសំខាន់ក្នុងការដឹកនាំ និងគ្រប់គ្រងប្រព័ន្ធរដ្ឋបាលរបស់មន្ទីរពេទ្យ គ្លីនិក ឬអង្គភាពសុខាភិបាលផ្សេងៗ។ ពួកគេទទួលខុសត្រូវក្នុងការរៀបចំផែនការងារ គ្រប់គ្រងបុគ្គលិក គ្រប់គ្រងថវិកា និងធានាថាសេវាកម្មសុខាភិបាលដំណើរការបានយ៉ាងមានប្រសិទ្ធភាព។ ជំនាញក្នុងការដឹកនាំ ការសម្របសម្រួល និងការទំនាក់ទំនងគឺសំខាន់ណាស់។ ពួកគេក៏ត្រូវយល់ដឹងអំពីច្បាប់ និងបទប្បញ្ញត្តិពាក់ព័ន្ធនឹងវិស័យសុខាភិបាលផងដែរ ដើម្បីធានាថាអង្គភាពអនុវត្តតាមបទប្បញ្ញត្តិនានា។', NULL, 'uploads/jobs/1746886233_15-interesting-health-science-facts-shutterstock_1922200124-1.jpg', 1, 4, '2025-04-22 17:13:32', '', 'អ្នកគ្រប់គ្រងសុខាភិបាល (Healthcare Administrator) ជាមនុស្សសំខាន់ក្នុងការដឹកនាំ និងគ្រប់គ្រងប្រព័ន្ធរដ្ឋបាលរបស់មន្ទីរពេទ្យ គ្លីនិក ឬអង្គភាពសុខាភិបាលផ្សេងៗ។ ពួកគេទទួលខុសត្រូវក្នុងការរៀបចំផែនការងារ គ្រប់គ្រងបុគ្គលិក គ្រប់គ្រងថវិកា និងធានាថាសេវាកម្មសុខាភិបាលដំណើរការបានយ៉ាងមានប្រសិទ្ធភាព។ ជំនាញក្នុងការដឹកនាំ ការសម្របសម្រួល និងការទំនាក់ទំនងគឺសំខាន់ណាស់។ ពួកគេក៏ត្រូវយល់ដឹងអំពីច្បាប់ និងបទប្បញ្ញត្តិពាក់ព័ន្ធនឹងវិស័យសុខាភិបាលផងដែរ ដើម្បីធានាថាអង្គភាពអនុវត្តតាមបទប្បញ្ញត្តិនានា។', 'Full-time', '.ប្រាក់ខែចាប់ពី $400 – $1,200+ ដោយផ្អែកលើបទពិសោធន៍\r\n.ឱកាសរៀនបន្ថែម និងបណ្តុះបណ្តាលផ្នែកសុខាភិបាល\r\n.អត្ថប្រយោជន៍សុខភាព និងការធានារ៉ាប់រង\r\n.ឱកាសលើកតំណែងក្នុងវិស័យសុខាភិបាល\r\n.បរិយាកាសការងារមានសុវត្ថិភាព និងស្រាលខ្លួន', '.បញ្ចប់បរិញ្ញាបត្រផ្នែកគ្រប់គ្រងសុខាភិបាល ឬវិស័យដែលពាក់ព័ន្ធ\r\n.ជំនាញក្នុងការគ្រប់គ្រងធនធាន និងបញ្ជាទិញ\r\n.ចំណេះដឹងអំពីច្បាប់ និងគោលការណ៍សុខាភិបាល\r\n.មានជំនាញក្នុងការគ្រប់គ្រងពេលវេលា និងការទំនាក់ទំនង', NULL, 0, 0, 0),
(6, 'Graphic Designer', NULL, 'N/A', NULL, NULL, 'N/A', NULL, '999', 'hot', NULL, 'Graphic Designer មានភារកិច្ចបង្កើត និងរចនាផ្នែកវិស្វកម្មក្រាហ្វិក សម្រាប់ពាណិជ្ជកម្ម និងផ្សព្វផ្សាយ។ ពួកគេបង្កើតផ្ទាំងផ្សព្វផ្សាយ ប័ណ្ណផ្សព្វផ្សាយ, ផ្ទាំងផ្សាយពាណិជ្ជកម្ម និងរូបភាពសម្រាប់បណ្តាញសង្គម និងគេហទំព័រ ដើម្បីទាក់ទាញអតិថិជន និងជួយបង្កើនការលក់។', NULL, 'uploads/careers/7FUVZAx3md_1748329128.png', 1, 2, '2025-05-27 06:58:49', '', 'Graphic Designer មានភារកិច្ចបង្កើត និងរចនាផ្នែកវិស្វកម្មក្រាហ្វិក សម្រាប់ពាណិជ្ជកម្ម និងផ្សព្វផ្សាយ។ ពួកគេបង្កើតផ្ទាំងផ្សព្វផ្សាយ ប័ណ្ណផ្សព្វផ្សាយ, ផ្ទាំងផ្សាយពាណិជ្ជកម្ម និងរូបភាពសម្រាប់បណ្តាញសង្គម និងគេហទំព័រ ដើម្បីទាក់ទាញអតិថិជន និងជួយបង្កើនការលក់។', 'Full-time', '.ប្រាក់ខែចាប់ពី $300 – $1,000+ តាមបទពិសោធន៍\r\n.ឱកាសបណ្តុះបណ្តាល និងអភិវឌ្ឍជំនាញ\r\n.ធ្វើការប្រកបដោយភាពច្នៃប្រឌិត និងសេរីភាព\r\n.ឱកាសធ្វើការពីផ្ទះ (Remote Work) និងមានប្រភេទការងារ Freelance\r\n.ការចូលរួមក្នុងគម្រោងធំៗ និងប្រកួតប្រជែងជាមួយក្រុមរចនាអន្តរជាតិ', '.បញ្ចប់បរិញ្ញាបត្រផ្នែករចនា ឬវិស្វកម្មក្រាហ្វិក\r\n.ជំនាញប្រើប្រាស់កម្មវិធី Adobe Creative Suite (Photoshop, Illustrator, InDesign)\r\n.មានចំណេះដឹងល្អអំពីក្បួនរចនា និងលំនាំ\r\n.មានភាពច្នៃប្រឌិត និងអាចធ្វើការជាក្រុមបានល្អ\r\n.សមត្ថភាពគ្រប់គ្រងពេលវេលា និងការដោះស្រាយបញ្ហា', NULL, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `popular_majors`
--

CREATE TABLE `popular_majors` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `title_km` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `description_km` text DEFAULT NULL,
  `students` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT 'primary',
  `image_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `title_kh` varchar(255) DEFAULT NULL,
  `description_kh` text DEFAULT NULL,
  `avg_salary` varchar(100) DEFAULT NULL,
  `duration` varchar(100) DEFAULT NULL,
  `about_major` text DEFAULT NULL,
  `career_opportunities` text DEFAULT NULL,
  `skills_gained` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `popular_majors`
--

INSERT INTO `popular_majors` (`id`, `title`, `title_km`, `description`, `description_km`, `students`, `color`, `image_path`, `is_active`, `display_order`, `created_at`, `title_kh`, `description_kh`, `avg_salary`, `duration`, `about_major`, `career_opportunities`, `skills_gained`) VALUES
(1, 'Computer Science', NULL, 'វិស័យនេះផ្តោតលើការសិក្សាអំពីកម្មវិធីកុំព្យូទ័រ ភាសាកម្មវិធី សុវត្ថិភាពប្រព័ន្ធ និងបច្ចេកវិទ្យាឌីជីថល។ វាជាជម្រើសល្អសម្រាប់អ្នកចង់ក្លាយជា Software Developer, Web Developer, AI Specialist ឬ IT Support។ ការចូលរួមសិក្សាវិស័យនេះនឹងបើកផ្លូវឱ្យអ្នកធ្វើការជាមួយក្រុមហ៊ុនបច្ចេកវិទ្យា ឬបង្កើតសូហ្វវែរផ្ទាល់ខ្លួន។', NULL, '1,245', 'primary', 'uploads/majors/1747030115_software-engineer-job-skills-1.jpg', 1, 1, '2025-04-22 17:13:32', '', 'វិស័យនេះផ្តោតលើការសិក្សាអំពីកម្មវិធីកុំព្យូទ័រ ភាសាកម្មវិធី សុវត្ថិភាពប្រព័ន្ធ និងបច្ចេកវិទ្យាឌីជីថល។ វាជាជម្រើសល្អសម្រាប់អ្នកចង់ក្លាយជា Software Developer, Web Developer, AI Specialist ឬ IT Support។ ការចូលរួមសិក្សាវិស័យនេះនឹងបើកផ្លូវឱ្យអ្នកធ្វើការជាមួយក្រុមហ៊ុនបច្ចេកវិទ្យា ឬបង្កើតសូហ្វវែរផ្ទាល់ខ្លួន។', '$1,000 – $2,000/month', '4 years (Bachelor\'s Degree)', 'វិស័យនេះផ្តោតលើការសិក្សាអំពីកម្មវិធីកុំព្យូទ័រ ភាសាកម្មវិធី សុវត្ថិភាពប្រព័ន្ធ និងបច្ចេកវិទ្យាឌីជីថល។ វាជាជម្រើសល្អសម្រាប់អ្នកចង់ក្លាយជា Software Developer, Web Developer, AI Specialist ឬ IT Support។ ការចូលរួមសិក្សាវិស័យនេះនឹងបើកផ្លូវឱ្យអ្នកធ្វើការជាមួយក្រុមហ៊ុនបច្ចេកវិទ្យា ឬបង្កើតសូហ្វវែរផ្ទាល់ខ្លួន។', '.Software Developer / Engineer\r\n.Web Developer\r\n.Mobile App Developer\r\n.Database Administrator\r\n.Network/System Administrator\r\n.AI/ML Engineer\r\n.IT Support Specialist\r\n.Cybersecurity Analyst\r\n.Game Developer\r\n.Data Analyst or Data Scientist', 'Programming languages, Problem solving and algorithmic thinking, Database management, Software engineering and web development, System architecture and networking, Cybersecurity basics, Teamwork and communication skills'),
(2, 'Business Administration', NULL, 'ការគ្រប់គ្រងអាជីវកម្ម ជាជំនាញមួយដែលផ្តោតលើការរបបគ្រប់គ្រង ការធ្វើសេដ្ឋកិច្ច និងការគ្រប់គ្រងសកម្មភាពអាជីវកម្មនៅក្នុងស្ថាប័ន ឬក៏ក្រុមហ៊ុន។ វាសិក្សាពីការបង្កើតផែនការ អភិវឌ្ឍអាជីវកម្ម ការធ្វើទីផ្សារ ហិរញ្ញវត្ថុ និងការគ្រប់គ្រងធនធានមនុស្ស។ ជំនាញនេះមានសារៈសំខាន់សម្រាប់អ្នកចង់បង្កើតអាជីវកម្មផ្ទាល់ខ្លួន ឬធ្វើការជាមនុស្សគ្រប់គ្រងនៅក្រុមហ៊ុន។', NULL, '980', 'success', 'uploads/majors/1747030171_Biz-Admin-hub-750x350-1.jpg', 1, 2, '2025-04-22 17:13:32', '', 'ការគ្រប់គ្រងអាជីវកម្ម ជាជំនាញមួយដែលផ្តោតលើការរបបគ្រប់គ្រង ការធ្វើសេដ្ឋកិច្ច និងការគ្រប់គ្រងសកម្មភាពអាជីវកម្មនៅក្នុងស្ថាប័ន ឬក៏ក្រុមហ៊ុន។ វាសិក្សាពីការបង្កើតផែនការ អភិវឌ្ឍអាជីវកម្ម ការធ្វើទីផ្សារ ហិរញ្ញវត្ថុ និងការគ្រប់គ្រងធនធានមនុស្ស។ ជំនាញនេះមានសារៈសំខាន់សម្រាប់អ្នកចង់បង្កើតអាជីវកម្មផ្ទាល់ខ្លួន ឬធ្វើការជាមនុស្សគ្រប់គ្រងនៅក្រុមហ៊ុន។', '$800 – $1,500/month', '4 years (Bachelor\'s Degree)', 'ការគ្រប់គ្រងអាជីវកម្ម ជាជំនាញមួយដែលផ្តោតលើការរបបគ្រប់គ្រង ការធ្វើសេដ្ឋកិច្ច និងការគ្រប់គ្រងសកម្មភាពអាជីវកម្មនៅក្នុងស្ថាប័ន ឬក៏ក្រុមហ៊ុន។ វាសិក្សាពីការបង្កើតផែនការ អភិវឌ្ឍអាជីវកម្ម ការធ្វើទីផ្សារ ហិរញ្ញវត្ថុ និងការគ្រប់គ្រងធនធានមនុស្ស។ ជំនាញនេះមានសារៈសំខាន់សម្រាប់អ្នកចង់បង្កើតអាជីវកម្មផ្ទាល់ខ្លួន ឬធ្វើការជាមនុស្សគ្រប់គ្រងនៅក្រុមហ៊ុន។', '.Business Manager\r\n.Marketing Executive\r\n.Sales Manager\r\n.Human Resource Officer\r\n.Financial Analyst\r\n.Project Coordinator\r\n.Entrepreneur (Start your own business)\r\n.Operations Manager\r\n.Administrative Officer\r\n.Customer Relations Manager', 'Leadership and communication,\r\n\r\nStrategic planning and decision-making,\r\n\r\nFinancial management and budgeting,\r\n\r\nMarketing and branding,\r\n\r\nHuman resource management,\r\n\r\nBusiness ethics and law,\r\n\r\nAnalytical and problem-solving skills'),
(3, 'Engineering', NULL, 'វិស័យនេះគ្របដណ្តប់ដល់ការរចនា និងសាងសង់របស់បច្ចេកវិទ្យា និងសំណង់។ វាភ្ជាប់ទៅនឹងជំនាញដូចជា Civil Engineering, Electrical Engineering និងMechanical Engineering។ វាជាជម្រើសល្អសម្រាប់អ្នកចង់ចូលរួមក្នុងការអភិវឌ្ឍប្រទេសតាមរយៈសំណង់បេតុង ឧស្សាហកម្ម និងបច្ចេកវិទ្យា។', NULL, '875', 'info', 'uploads/majors/1747030195_civil-engineering.jpg', 1, 3, '2025-04-22 17:13:32', '', 'វិស័យនេះគ្របដណ្តប់ដល់ការរចនា និងសាងសង់របស់បច្ចេកវិទ្យា និងសំណង់។ វាភ្ជាប់ទៅនឹងជំនាញដូចជា Civil Engineering, Electrical Engineering និងMechanical Engineering។ វាជាជម្រើសល្អសម្រាប់អ្នកចង់ចូលរួមក្នុងការអភិវឌ្ឍប្រទេសតាមរយៈសំណង់បេតុង ឧស្សាហកម្ម និងបច្ចេកវិទ្យា។', '$1,200 – $2,000/month', '4 years (Bachelor\'s Degree)', 'វិស័យនេះគ្របដណ្តប់ដល់ការរចនា និងសាងសង់របស់បច្ចេកវិទ្យា និងសំណង់។ វាភ្ជាប់ទៅនឹងជំនាញដូចជា Civil Engineering, Electrical Engineering និងMechanical Engineering។ វាជាជម្រើសល្អសម្រាប់អ្នកចង់ចូលរួមក្នុងការអភិវឌ្ឍប្រទេសតាមរយៈសំណង់បេតុង ឧស្សាហកម្ម និងបច្ចេកវិទ្យា។', '.Civil Engineer (roads, bridges, buildings)\r\n.Electrical Engineer (power systems, wiring, renewable energy).\r\n.Mechanical Engineer (machines, tools, vehicles)\r\n.Computer/Software Engineer (embedded systems, robotics)\r\n.Project Manager\r\n.Construction Supervisor\r\n.Environmental Engineer\r\n.Design Engineer (CAD/CAM)\r\n.Quality Control Specialist\r\n.Research and Development (R&D) Engineer', 'Technical drawing and design,\r\n\r\nMathematical modeling and physics,\r\n\r\nUse of engineering software (e.g., AutoCAD, SolidWorks, MATLAB),\r\n\r\nProject management and teamwork,\r\n\r\nProblem-solving and innovation,\r\n\r\nStructural analysis and material science,\r\n\r\nSafety and quality assurance'),
(4, 'Healthcare Sciences', NULL, 'វិស័យនេះផ្តោតលើការថែទាំសុខភាពមនុស្ស តាមរយៈជំនាញដូចជា Medical Laboratory, Nursing, Public Health និងPharmacy។ វាជាផ្នែកសំខាន់ក្នុងការរស់នៅ និងបង្កើតសង្គមមានសុខភាពល្អ។ អ្នកអាចធ្វើការនៅមន្ទីរពេទ្យ ឬអង្គការមិនរកប្រាក់ចំណេញដែលផ្តោតលើសុខភាពសាធារណៈ។', NULL, '760', 'danger', 'uploads/majors/1747030221_15-interesting-health-science-facts-shutterstock_1922200124-1.jpg', 1, 4, '2025-04-22 17:13:32', '', 'វិស័យនេះផ្តោតលើការថែទាំសុខភាពមនុស្ស តាមរយៈជំនាញដូចជា Medical Laboratory, Nursing, Public Health និងPharmacy។ វាជាផ្នែកសំខាន់ក្នុងការរស់នៅ និងបង្កើតសង្គមមានសុខភាពល្អ។ អ្នកអាចធ្វើការនៅមន្ទីរពេទ្យ ឬអង្គការមិនរកប្រាក់ចំណេញដែលផ្តោតលើសុខភាពសាធារណៈ។', '$800 – $2,000/month', '6 years (Bachelor\'s Degree)', 'This major provides students with a comprehensive education in Healthcare Sciences. Students will develop critical thinking, problem-solving, and communication skills while gaining specialized knowledge in their field of study.', 'Nurse / Midwife\r\nMedical Laboratory Technician\r\nPublic Health Officer\r\nHealthcare Assistant\r\nHealth Educator\r\nEmergency Medical Technician (EMT)\r\nHospital Administrator\r\nClinical Research Assistant\r\nNutritionist (with further study)\r\nCommunity Health Worker', 'Knowledge of human biology and health systems,\r\n\r\nMedical terminology and clinical procedures,\r\n\r\nLaboratory testing and diagnostic skills,\r\n\r\nPatient care and ethics,\r\n\r\nHealth communication and public health education,\r\n\r\nEmergency response and first aid,\r\n\r\nData collection and health research');

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `title_km` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `description_km` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roadshow`
--

CREATE TABLE `roadshow` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roadshow`
--

INSERT INTO `roadshow` (`id`, `title`, `description`, `location`, `event_date`, `is_active`, `created_at`, `image_path`, `video_url`) VALUES
(2, 'សិស្សឈ្មោះ លី ពេជ្រមុនីរត្ន ​មានក្តីស្រមៃចង់ចាប់យកជំនាញសិក្សា \" ទំនាក់ទំនងអន្តរជាតិ (IR) \"', 'សិស្សឈ្មោះ លី ពេជ្រមុនីរត្ន ​ រៀននៅថ្នាក់ទី១០ ក ជាសិស្សនៃវិទ្យាល័យព្រែកដំបូក មានក្តីស្រមៃចង់ចាប់យកជំនាញសិក្សា \" ទំនាក់ទំនងអន្តរជាតិ (IR) \" ក្នុងកម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព \" Major and Career Roadshow \"', 'N/A', '2025-05-09', 1, '2025-05-13 13:04:08', '', 'https://youtu.be/tQGjkXOfErY?si=hE_XQLATxJ6jBQds'),
(3, 'ចំណាប់អារម្មណ៍របស់សិស្សឈ្មោះ ចាប ស្រីម៉ី ក្នុងកម្មវិធីបង្ហាញផ្លូវស្តីពី ជំនាញសិក្សា និងអាជីព!', 'ចំណាប់អារម្មណ៍របស់សិស្សឈ្មោះ ចាប ស្រីម៉ី រៀនថ្នាក់ទី១១ A  នៃវិទ្យាល័យសម្តេចអគ្គមហាសេនាបតីតេជោ ហ៊ុន សែន បាធាយ។\r\n\r\nដែលបានចូលរួមក្នុងកម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព \"Major and Career Roadshow\" នៅវិទ្យាល័យសម្តេចអគ្គមហាសេនាបតីតេជោ ហ៊ុន សែន បាធាយ នៃស្រុកបាធាយ ខេត្តកំពង់ចាម ដែលប្រព្រឹត្តិទៅកាលពីថ្ងៃសៅរ៍ ទី៣ ខែឧសភា ឆ្នាំ ២០២៥។', 'N/A', '2025-05-08', 1, '2025-05-13 13:05:44', '', 'https://youtu.be/QSdarGWghB8?si=a-x0rKP1Nf6bft3R'),
(4, 'សិស្សឈ្មោះ ខេង សុដាលីន រៀនថ្នាក់ទី១០ ខ មានក្តីស្រមៃចង់ក្លាយជា \" Fashion Design \" !', 'សិស្សឈ្មោះ ខេង សុដាលីន រៀនថ្នាក់ទី១០ ខ មានក្តីស្រមៃចង់ក្លាយជា \" Fashion Design \" ជាសិស្សនៃវិទ្យាល័យព្រែកដំបូក ក្នុងកម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព \" Major and Career Roadshow \" នៅស្រុកស្រីសន្ធរ ខេត្តកំពង់ចាម ដែលប្រព្រឹត្តិទៅកាលពីថ្ងៃទី២៦ ខែមេសា ឆ្នាំ ២០២៥។', 'ស្រុកស្រីសន្ធរ ខេត្តកំពង់ចាម', '2025-05-07', 1, '2025-05-13 13:06:45', '', 'https://youtu.be/SXIZCxXFMRw?si=RVuGaLBx3l-Wu96Y'),
(5, 'សិស្សឈ្មោះ កូ អេងតុង រៀនថ្នាក់ទី១០ ក មានក្តីស្រមៃចង់ក្លាយជា \" អ្នក Designer \"', 'សិស្សឈ្មោះ កូ អេងតុង រៀនថ្នាក់ទី១០ ក មានក្តីស្រមៃចង់ក្លាយជា \" អ្នក Designer \" ជាសិស្សនៃវិទ្យាល័យព្រែកដំបូក ក្នុងកម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព \" Major and Career Roadshow \" នៅស្រុកស្រីសន្ធរ ខេត្តកំពង់ចាម ដែលប្រព្រឹត្តិទៅកាលពីថ្ងៃទី២៦ ខែមេសា ឆ្នាំ ២០២៥។', 'ស្រុកស្រីសន្ធរ ខេត្តកំពង់ចាម', '2025-05-06', 1, '2025-05-13 13:08:48', '', 'https://youtu.be/e3_ZzoTT75U?si=BJR5NRx0msTrQ4AA');

-- --------------------------------------------------------

--
-- Table structure for table `roadshows`
--

CREATE TABLE `roadshows` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `title_km` varchar(255) NOT NULL,
  `summary` text NOT NULL,
  `summary_km` text NOT NULL,
  `content` text NOT NULL,
  `content_km` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `location_km` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roadshows`
--

INSERT INTO `roadshows` (`id`, `title`, `title_km`, `summary`, `summary_km`, `content`, `content_km`, `location`, `location_km`, `event_date`, `video_url`, `image_path`, `is_active`, `created_at`) VALUES
(1, 'cx', 'saf', 'd', 'asf', 'dzvd', 'fsa', 'd', 'asf', '2025-05-23', 'https://web.facebook.com/share/v/1ASf6FeTuS/', NULL, 1, '2025-05-01 23:45:32'),
(2, 'សិស្សឈ្មោះ លី ពេជ្រមុនីរត្ន ​មានក្តីស្រមៃចង់ចាប់យកជំនាញសិក្សា &quot; ទំនាក់ទំនងអន្តរជាតិ (IR) &quot;', 'សិស្សឈ្មោះ លី ពេជ្រមុនីរត្ន ​មានក្តីស្រមៃចង់ចាប់យកជំនាញសិក្សា &quot; ទំនាក់ទំនងអន្តរជាតិ (IR) &quot;', 'សិស្សឈ្មោះ លី ពេជ្រមុនីរត្ន ​ រៀននៅថ្នាក់ទី១០ ក ជាសិស្សនៃវិទ្យាល័យព្រែកដំបូក មានក្តីស្រមៃចង់ចាប់យកជំនាញសិក្សា &quot; ទំនាក់ទំនងអន្តរជាតិ (IR) &quot; ក្នុងកម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព &quot; Major and Career Roadshow &quot;', 'សិស្សឈ្មោះ លី ពេជ្រមុនីរត្ន ​ រៀននៅថ្នាក់ទី១០ ក ជាសិស្សនៃវិទ្យាល័យព្រែកដំបូក មានក្តីស្រមៃចង់ចាប់យកជំនាញសិក្សា &quot; ទំនាក់ទំនងអន្តរជាតិ (IR) &quot; ក្នុងកម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព &quot; Major and Career Roadshow &quot;', 'សិស្សឈ្មោះ លី ពេជ្រមុនីរត្ន ​ រៀននៅថ្នាក់ទី១០ ក ជាសិស្សនៃវិទ្យាល័យព្រែកដំបូក មានក្តីស្រមៃចង់ចាប់យកជំនាញសិក្សា \" ទំនាក់ទំនងអន្តរជាតិ (IR) \" ក្នុងកម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព \" Major and Career Roadshow \"', 'សិស្សឈ្មោះ លី ពេជ្រមុនីរត្ន ​ រៀននៅថ្នាក់ទី១០ ក ជាសិស្សនៃវិទ្យាល័យព្រែកដំបូក មានក្តីស្រមៃចង់ចាប់យកជំនាញសិក្សា \" ទំនាក់ទំនងអន្តរជាតិ (IR) \" ក្នុងកម្មវិធីបង្ហាញផ្លូវស្តីពីជំនាញសិក្សា និងអាជីព \" Major and Career Roadshow \"', 'N/A', 'N/A', '2025-05-09', 'https://youtu.be/tQGjkXOfErY?si=SUF0Zz8iGvRh6StQ', NULL, 1, '2025-05-13 09:23:13');

-- --------------------------------------------------------

--
-- Table structure for table `roadshow_videos`
--

CREATE TABLE `roadshow_videos` (
  `id` int(11) NOT NULL,
  `roadshow_id` int(11) NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `video_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_value_km` text DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_value_km`, `updated_at`, `created_at`) VALUES
(1, 'hero_image_1', 'uploads/slideshow/GjwFbzf1Vn_1749604358.jpg', NULL, '2025-06-11 01:12:38', '2025-05-05 14:48:07'),
(2, 'hero_image_2', 'uploads/slideshow/njGtkxJzNd_1746897064.jpg', NULL, '2025-05-10 17:11:04', '2025-05-05 14:48:07'),
(3, 'hero_image_3', 'uploads/slideshow/jDhpOdSfvA_1746896748.jpg', NULL, '2025-05-10 17:05:48', '2025-05-05 14:48:07'),
(4, 'hero_image_4', 'uploads/slideshow/M3KFYXkveI_1746896814.jpg', NULL, '2025-05-10 17:06:55', '2025-05-05 14:48:07'),
(5, 'hero_image_5', 'uploads/slideshow/AjS7gTTN2J_1746896889.jpg', NULL, '2025-05-10 17:08:09', '2025-05-05 14:48:07'),
(6, 'about_banner', 'uploads/banners/wrP897sAM5_1749452480.jpg', NULL, '2025-06-09 07:01:20', '2025-05-05 14:48:07'),
(7, 'contact_address', 'Wealth Mainson Building Floor 6, Room 30, Sk.Chroychangva Kh.Chroy Changva', NULL, '2025-05-04 10:39:24', '2025-05-05 14:48:07'),
(8, 'contact_phone', '070 887 332', NULL, '2025-05-04 10:39:24', '2025-05-05 14:48:07'),
(9, 'contact_email', 'info@mymaca.asia', NULL, '2025-05-04 10:39:24', '2025-05-05 14:48:07'),
(10, 'contact_hours', 'Monday-Friday: 8am-5pm', NULL, '2025-05-04 10:39:24', '2025-05-05 14:48:07'),
(11, 'social_facebook', 'https://web.facebook.com/mymaca.asia', NULL, '2025-05-04 10:45:00', '2025-05-05 14:48:07'),
(12, 'social_twitter', '', NULL, '2025-05-04 10:45:00', '2025-05-05 14:48:07'),
(13, 'social_instagram', '', NULL, '2025-05-04 10:45:00', '2025-05-05 14:48:07'),
(14, 'social_youtube', '', NULL, '2025-05-04 10:45:00', '2025-05-05 14:48:07'),
(15, 'social_linkedin', '', NULL, '2025-05-04 10:45:00', '2025-05-05 14:48:07'),
(16, 'hero_text_1', 'df', NULL, '2025-05-05 15:54:16', '2025-05-05 15:26:54'),
(17, 'hero_button_text_1', 'dsf', NULL, '2025-05-05 15:54:16', '2025-05-05 15:26:54'),
(18, 'hero_button_link_1', 'sdf', NULL, '2025-05-05 15:54:16', '2025-05-05 15:26:54'),
(19, 'hero_text_2', 'df', NULL, '2025-05-05 15:54:31', '2025-05-05 15:54:31'),
(20, 'hero_button_text_2', 'sdf', NULL, '2025-05-05 15:54:31', '2025-05-05 15:54:31'),
(21, 'hero_button_link_2', '#sd', NULL, '2025-05-05 15:54:31', '2025-05-05 15:54:31'),
(22, 'slideshow_speed', '4', NULL, '2025-05-07 09:06:47', '2025-05-07 09:05:37'),
(23, 'slideshow_effect', 'fade', NULL, '2025-05-07 09:06:30', '2025-05-07 09:05:37');

-- --------------------------------------------------------

--
-- Table structure for table `slider_images`
--

CREATE TABLE `slider_images` (
  `id` int(11) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `title_kh` varchar(255) NOT NULL,
  `description_en` text DEFAULT NULL,
  `description_kh` text DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `button_text_en` varchar(100) DEFAULT NULL,
  `button_text_kh` varchar(100) DEFAULT NULL,
  `button_url` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `slider_images`
--

INSERT INTO `slider_images` (`id`, `title_en`, `title_kh`, `description_en`, `description_kh`, `image_url`, `button_text_en`, `button_text_kh`, `button_url`, `display_order`, `active`, `created_at`) VALUES
(1, 'Welcome to MACA Education', 'សូមស្វាគមន៍មកកាន់ MACA Education', 'Empowering students with knowledge and skills for a better future', 'ផ្តល់អំណាចដល់សិស្សជាមួយនឹងចំណេះដឹងនិងជំនាញសម្រាប់អនាគតដ៏ល្អប្រសើរ', 'assets/images/slider1.jpg', 'Learn More', 'ស្វែងយល់បន្ថែម', 'index.php?page=about', 1, 1, '2025-05-04 11:32:15'),
(2, 'Discover Your Career Path', 'ស្វែងរកផ្លូវអាជីពរបស់អ្នក', 'Explore various career options and find the right path for your future', 'ស្វែងរកជម្រើសអាជីពផ្សេងៗ និងស្វែងរកផ្លូវត្រឹមត្រូវសម្រាប់អនាគតរបស់អ្នក', 'assets/images/slider2.jpg', 'Explore Careers', 'ស្វែងរកអាជីព', 'index.php?page=explore/career-paths', 2, 1, '2025-05-04 11:32:15'),
(3, 'Quality Education for All', 'ការអប់រំដែលមានគុណភាពសម្រាប់ទាំងអស់គ្នា', 'Access quality education resources and guidance from anywhere', 'ចូលប្រើធនធានអប់រំដែលមានគុណភាព និងការណែនាំពីគ្រប់ទីកន្លែង', 'assets/images/slider3.jpg', 'Our Programs', 'កម្មវិធីរបស់យើង', 'index.php?page=program/online-learning', 3, 1, '2025-05-04 11:32:15');

-- --------------------------------------------------------

--
-- Table structure for table `slides`
--

CREATE TABLE `slides` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `title_km` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `description_km` text DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `talkshow`
--

CREATE TABLE `talkshow` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `talkshows`
--

CREATE TABLE `talkshows` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `title_km` varchar(255) NOT NULL,
  `summary` text NOT NULL,
  `summary_km` text NOT NULL,
  `content` text NOT NULL,
  `content_km` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `location_km` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talkshows`
--

INSERT INTO `talkshows` (`id`, `title`, `title_km`, `summary`, `summary_km`, `content`, `content_km`, `location`, `location_km`, `event_date`, `video_url`, `image_path`, `is_active`, `created_at`) VALUES
(7, 'អាជីពជា &quot; វិស្វកម្មសំណង់ស៊ីវិល &quot;', 'អាជីពជា &quot; វិស្វកម្មសំណង់ស៊ីវិល &quot;', 'កម្មវិធី Major &amp; Career Talk សប្តាហ៍ទី៧ មានប្រធានបទ «អាជីពជា វិស្វកម្មសំណង់ស៊ីវិល» ដឹកនាំដោយវាគ្មិនកិត្តិយស លោកស្រី ហ៊ុន បូនីតា ដែលជាប្រធានអ៊ិនជីនៀរនៃក្រុមហ៊ុន BVS Engineering Co., Ltd និងជាអតីតនិស្សិតនៃ NTTI។\r\n\r\nកម្មវិធីនេះជាឱកាសសម្រាប់សិស្ស-និស្សិត និងយុវជនដែលមានចំណាប់អារម្មណ៍ចង់សិក្សា និងធ្វើការជាវិស្វករសំណង់ស៊ីវិល ដើម្បីស្វែងយល់ពីអាជីពនេះច្បាស់លាស់។\r\n\r\nកម្មវិធី Major &amp; Career Talk ត្រូវបានបង្កើតដោយអង្គការ MACA ដើម្បីជួយណែនាំអោយយុវជនជ្រើសរើសជំនាញសិក្សា និងអាជីពត្រឹមត្រូវ។ វាផ្សាយរៀងរាល់ថ្ងៃសុក្រ ម៉ោង ៨ យប់ តាម Facebook Page: Major &amp; Career Talk និង YouTube Channel: MACA។', 'កម្មវិធី Major &amp; Career Talk សប្តាហ៍ទី៧ មានប្រធានបទ «អាជីពជា វិស្វកម្មសំណង់ស៊ីវិល» ដឹកនាំដោយវាគ្មិនកិត្តិយស លោកស្រី ហ៊ុន បូនីតា ដែលជាប្រធានអ៊ិនជីនៀរនៃក្រុមហ៊ុន BVS Engineering Co., Ltd និងជាអតីតនិស្សិតនៃ NTTI។\r\n\r\nកម្មវិធីនេះជាឱកាសសម្រាប់សិស្ស-និស្សិត និងយុវជនដែលមានចំណាប់អារម្មណ៍ចង់សិក្សា និងធ្វើការជាវិស្វករសំណង់ស៊ីវិល ដើម្បីស្វែងយល់ពីអាជីពនេះច្បាស់លាស់។\r\n\r\nកម្មវិធី Major &amp; Career Talk ត្រូវបានបង្កើតដោយអង្គការ MACA ដើម្បីជួយណែនាំអោយយុវជនជ្រើសរើសជំនាញសិក្សា និងអាជីពត្រឹមត្រូវ។ វាផ្សាយរៀងរាល់ថ្ងៃសុក្រ ម៉ោង ៨ យប់ តាម Facebook Page: Major &amp; Career Talk និង YouTube Channel: MACA។', 'អាជីពជា \" វិស្វកម្មសំណង់ស៊ីវិល \"\r\n\r\nកិច្ចពិភាក្សាសប្តាហ៍ទី៧ ក្រោមប្រធានបទ៖ \" អាជីពជា វិស្វកម្មសំណង់ស៊ីវិល \" វាគ្មិនកិត្តិយស លោកស្រី ហ៊ុន បូនីតា លោកស្រីជា Cheif Engineer របស់ក្រុមហ៊ុន BVS Engineering Co.ltd និងជាអតីតនិស្សិតនៃវិទ្យាស្ថានជាតិបណ្តុះបណ្តាលបច្ចេកទេស (NTTI)  ក្នុងកម្មវិធី Major & Career Talk។\r\n\r\nនេះជាឱកាសសម្រាប់ប្អូនៗសិស្សានុសិស្ស - និស្សិត និងសាធារណៈជនដែលមានក្តីស្រម៉ៃចង់ចាប់យកអាជីពជា វិស្វកម្មសំណង់ស៊ីវិល កុំភ្លេចតាមដានទស្សនាកិច្ចពិភាក្សា និងការចែករំលែកលើអាជីពមួយនេះទាំងអស់គ្នា។\r\n\r\nគួរបញ្ជាក់ផងដែរកម្មវិធី Major & Career Talk ត្រូវបានបង្កើតឡើងដោយអង្គការ MACA ក្នុងគោលបំណងដើម្បីជជែកគ្នាអំពីការជ្រើសរើសជំនាញសិក្សា និងអាជីពឱ្យបានត្រឹមត្រូវ នឹងការស្វែងយល់ឱ្យកាន់ច្បាស់អំពីជំនាញសិក្សា និងអាជីពនីមួយៗទៅកាន់សិស្ស-និស្សិត សាធារណៈជន  ជាពិសេសនោះគឺ យុវជនកម្ពុជាទាំងមូល ដែលមិនទាន់មានភាពច្បាស់លាស់ក្នុងការជ្រើសរើសជំនាញសិក្សា និងអាជីពដែលខ្លួនស្រលាញ់នាពេលអនាគត ដែលផ្សាយជូនទស្សនារៀងរាល់ថ្ងៃសុក្រ វេលាម៉ោង ៨ :​ ០០ នាទីយប់តទៅ នៅលើបណ្តាញសង្គម  Facebook Page : Major & Career Talk និង Youtube Channel : MACA។', 'អាជីពជា \" វិស្វកម្មសំណង់ស៊ីវិល \"\r\n\r\nកិច្ចពិភាក្សាសប្តាហ៍ទី៧ ក្រោមប្រធានបទ៖ \" អាជីពជា វិស្វកម្មសំណង់ស៊ីវិល \" វាគ្មិនកិត្តិយស លោកស្រី ហ៊ុន បូនីតា លោកស្រីជា Cheif Engineer របស់ក្រុមហ៊ុន BVS Engineering Co.ltd និងជាអតីតនិស្សិតនៃវិទ្យាស្ថានជាតិបណ្តុះបណ្តាលបច្ចេកទេស (NTTI)  ក្នុងកម្មវិធី Major & Career Talk។\r\n\r\nនេះជាឱកាសសម្រាប់ប្អូនៗសិស្សានុសិស្ស - និស្សិត និងសាធារណៈជនដែលមានក្តីស្រម៉ៃចង់ចាប់យកអាជីពជា វិស្វកម្មសំណង់ស៊ីវិល កុំភ្លេចតាមដានទស្សនាកិច្ចពិភាក្សា និងការចែករំលែកលើអាជីពមួយនេះទាំងអស់គ្នា។\r\n\r\nគួរបញ្ជាក់ផងដែរកម្មវិធី Major & Career Talk ត្រូវបានបង្កើតឡើងដោយអង្គការ MACA ក្នុងគោលបំណងដើម្បីជជែកគ្នាអំពីការជ្រើសរើសជំនាញសិក្សា និងអាជីពឱ្យបានត្រឹមត្រូវ នឹងការស្វែងយល់ឱ្យកាន់ច្បាស់អំពីជំនាញសិក្សា និងអាជីពនីមួយៗទៅកាន់សិស្ស-និស្សិត សាធារណៈជន  ជាពិសេសនោះគឺ យុវជនកម្ពុជាទាំងមូល ដែលមិនទាន់មានភាពច្បាស់លាស់ក្នុងការជ្រើសរើសជំនាញសិក្សា និងអាជីពដែលខ្លួនស្រលាញ់នាពេលអនាគត ដែលផ្សាយជូនទស្សនារៀងរាល់ថ្ងៃសុក្រ វេលាម៉ោង ៨ :​ ០០ នាទីយប់តទៅ នៅលើបណ្តាញសង្គម  Facebook Page : Major & Career Talk និង Youtube Channel : MACA។', 'Wealth Mainson Building Floor 6, Room 30, Sk.Chroychangva Kh.Chroy Changva', 'Wealth Mainson Building Floor 6, Room 30, Sk.Chroychangva Kh.Chroy Changva', '2025-05-09', 'https://youtu.be/A79UERz0Eqc?si=1sgXOyumi_7dW7uq', NULL, 1, '2025-05-13 07:39:34'),
(8, 'ជំនាញសិក្សា អក្សរសាស្ត្រខ្មែរ', 'ជំនាញសិក្សា អក្សរសាស្ត្រខ្មែរ', 'កិច្ចពិភាក្សាក្នុងសប្តាហ៍ទី៦ ក្រោមប្រធានបទ៖ &quot; ជំនាញសិក្សា អក្សរសាស្ត្រខ្មែរ &quot; វាគ្មិនកិត្តិយស កញ្ញា ធឿន ស៊ាងហួរ ជាអតីតនិស្សិត នៃនៅវិទ្យាស្ថានខេមរាសាស្ត្រ  ក្នុងកម្មវិធី Major &amp; Career Talk ។\r\n\r\nនេះជាឱកាសសម្រាប់ប្អូនៗសិស្សានុសិស្ស - និស្សិត និងសាធារណៈជនដែលមានក្តីស្រម៉ៃចង់រៀនជំនាញអក្សរសាស្រ្តខ្មែរ កុំភ្លេចតាមដានទស្សនាកិច្ចពិភាក្សា និងការចែករំលែកលើអាជីពមួយនេះទាំងអស់គ្នា។\r\n\r\nគួរបញ្ជាក់ផងដែរកម្មវិធី Major &amp; Career Talk ត្រូវបានបង្កើតឡើងដោយអង្គការ MACA ក្នុងគោលបំណងដើម្បីជជែកគ្នាអំពីការជ្រើសរើសជំនាញសិក្សា និងអាជីពឱ្យបានត្រឹមត្រូវ នឹងការស្វែងយល់ឱ្យកាន់ច្បាស់អំពីជំនាញសិក្សា និងអាជីពនីមួយៗទៅកាន់សិស្ស-និស្សិត សាធារណៈជន  ជាពិសេសនោះគឺ យុវជនកម្ពុជាទាំងមូល ដែលមិនទាន់មានភាពច្បាស់លាស់ក្នុងការជ្រើសរើសជំនាញសិក្សា និងអាជីពដែលខ្លួនស្រលាញ់នាពេលអនាគត ចាក់ផ្សាយជូនទស្សនារៀងរាល់ថ្ងៃសុក្រ វេលាម៉ោង៨:០០ នាទីយប់នៅលើបណ្តាញសង្គម  Facebook Page : Major &amp; Career Talk និង Youtube Channel : MACA។', 'កិច្ចពិភាក្សាក្នុងសប្តាហ៍ទី៦ ក្រោមប្រធានបទ៖ &quot; ជំនាញសិក្សា អក្សរសាស្ត្រខ្មែរ &quot; វាគ្មិនកិត្តិយស កញ្ញា ធឿន ស៊ាងហួរ ជាអតីតនិស្សិត នៃនៅវិទ្យាស្ថានខេមរាសាស្ត្រ  ក្នុងកម្មវិធី Major &amp; Career Talk ។\r\n\r\nនេះជាឱកាសសម្រាប់ប្អូនៗសិស្សានុសិស្ស - និស្សិត និងសាធារណៈជនដែលមានក្តីស្រម៉ៃចង់រៀនជំនាញអក្សរសាស្រ្តខ្មែរ កុំភ្លេចតាមដានទស្សនាកិច្ចពិភាក្សា និងការចែករំលែកលើអាជីពមួយនេះទាំងអស់គ្នា។\r\n\r\nគួរបញ្ជាក់ផងដែរកម្មវិធី Major &amp; Career Talk ត្រូវបានបង្កើតឡើងដោយអង្គការ MACA ក្នុងគោលបំណងដើម្បីជជែកគ្នាអំពីការជ្រើសរើសជំនាញសិក្សា និងអាជីពឱ្យបានត្រឹមត្រូវ នឹងការស្វែងយល់ឱ្យកាន់ច្បាស់អំពីជំនាញសិក្សា និងអាជីពនីមួយៗទៅកាន់សិស្ស-និស្សិត សាធារណៈជន  ជាពិសេសនោះគឺ យុវជនកម្ពុជាទាំងមូល ដែលមិនទាន់មានភាពច្បាស់លាស់ក្នុងការជ្រើសរើសជំនាញសិក្សា និងអាជីពដែលខ្លួនស្រលាញ់នាពេលអនាគត ចាក់ផ្សាយជូនទស្សនារៀងរាល់ថ្ងៃសុក្រ វេលាម៉ោង៨:០០ នាទីយប់នៅលើបណ្តាញសង្គម  Facebook Page : Major &amp; Career Talk និង Youtube Channel : MACA។', 'កិច្ចពិភាក្សាក្នុងសប្តាហ៍ទី៦ ក្រោមប្រធានបទ៖ \" ជំនាញសិក្សា អក្សរសាស្ត្រខ្មែរ \" វាគ្មិនកិត្តិយស កញ្ញា ធឿន ស៊ាងហួរ ជាអតីតនិស្សិត នៃនៅវិទ្យាស្ថានខេមរាសាស្ត្រ  ក្នុងកម្មវិធី Major & Career Talk ។\r\n\r\nនេះជាឱកាសសម្រាប់ប្អូនៗសិស្សានុសិស្ស - និស្សិត និងសាធារណៈជនដែលមានក្តីស្រម៉ៃចង់រៀនជំនាញអក្សរសាស្រ្តខ្មែរ កុំភ្លេចតាមដានទស្សនាកិច្ចពិភាក្សា និងការចែករំលែកលើអាជីពមួយនេះទាំងអស់គ្នា។\r\n\r\nគួរបញ្ជាក់ផងដែរកម្មវិធី Major & Career Talk ត្រូវបានបង្កើតឡើងដោយអង្គការ MACA ក្នុងគោលបំណងដើម្បីជជែកគ្នាអំពីការជ្រើសរើសជំនាញសិក្សា និងអាជីពឱ្យបានត្រឹមត្រូវ នឹងការស្វែងយល់ឱ្យកាន់ច្បាស់អំពីជំនាញសិក្សា និងអាជីពនីមួយៗទៅកាន់សិស្ស-និស្សិត សាធារណៈជន  ជាពិសេសនោះគឺ យុវជនកម្ពុជាទាំងមូល ដែលមិនទាន់មានភាពច្បាស់លាស់ក្នុងការជ្រើសរើសជំនាញសិក្សា និងអាជីពដែលខ្លួនស្រលាញ់នាពេលអនាគត ចាក់ផ្សាយជូនទស្សនារៀងរាល់ថ្ងៃសុក្រ វេលាម៉ោង៨:០០ នាទីយប់នៅលើបណ្តាញសង្គម  Facebook Page : Major & Career Talk និង Youtube Channel : MACA។ ', 'កិច្ចពិភាក្សាក្នុងសប្តាហ៍ទី៦ ក្រោមប្រធានបទ៖ \" ជំនាញសិក្សា អក្សរសាស្ត្រខ្មែរ \" វាគ្មិនកិត្តិយស កញ្ញា ធឿន ស៊ាងហួរ ជាអតីតនិស្សិត នៃនៅវិទ្យាស្ថានខេមរាសាស្ត្រ  ក្នុងកម្មវិធី Major & Career Talk ។\r\n\r\nនេះជាឱកាសសម្រាប់ប្អូនៗសិស្សានុសិស្ស - និស្សិត និងសាធារណៈជនដែលមានក្តីស្រម៉ៃចង់រៀនជំនាញអក្សរសាស្រ្តខ្មែរ កុំភ្លេចតាមដានទស្សនាកិច្ចពិភាក្សា និងការចែករំលែកលើអាជីពមួយនេះទាំងអស់គ្នា។\r\n\r\nគួរបញ្ជាក់ផងដែរកម្មវិធី Major & Career Talk ត្រូវបានបង្កើតឡើងដោយអង្គការ MACA ក្នុងគោលបំណងដើម្បីជជែកគ្នាអំពីការជ្រើសរើសជំនាញសិក្សា និងអាជីពឱ្យបានត្រឹមត្រូវ នឹងការស្វែងយល់ឱ្យកាន់ច្បាស់អំពីជំនាញសិក្សា និងអាជីពនីមួយៗទៅកាន់សិស្ស-និស្សិត សាធារណៈជន  ជាពិសេសនោះគឺ យុវជនកម្ពុជាទាំងមូល ដែលមិនទាន់មានភាពច្បាស់លាស់ក្នុងការជ្រើសរើសជំនាញសិក្សា និងអាជីពដែលខ្លួនស្រលាញ់នាពេលអនាគត ចាក់ផ្សាយជូនទស្សនារៀងរាល់ថ្ងៃសុក្រ វេលាម៉ោង៨:០០ នាទីយប់នៅលើបណ្តាញសង្គម  Facebook Page : Major & Career Talk និង Youtube Channel : MACA។ ', 'Wealth Mainson Building Floor 6, Room 30, Sk.Chroychangva Kh.Chroy Changva', 'Wealth Mainson Building Floor 6, Room 30, Sk.Chroychangva Kh.Chroy Changva', '2025-05-03', 'https://youtu.be/N5WwFceVtFU?si=BxCSubPwhu9eBSKN', NULL, 1, '2025-05-13 08:25:29'),
(9, 'អាជីពជា AI Engineer វាគ្មិនកិត្តិយស លោក វ៉ែន វណ្ណនុត ក្នុងកម្មវិធី Major &amp; Career Talk', 'អាជីពជា AI Engineer វាគ្មិនកិត្តិយស លោក វ៉ែន វណ្ណនុត ក្នុងកម្មវិធី Major &amp; Career Talk', 'កិច្ចពិភាក្សាក្នុងសប្តាហ៍ទី៥ ក្រោមប្រធានបទ៖ &quot; អាជីពជា AI Engineer &quot; វាគ្មិនកិត្តិយស លោក វ៉ែន វណ្ណនុត អ្នក ជាជំនាញ AI Engineer ក្នុងកម្មវិធី Major &amp; Career Talk ផ្សាយជូនទស្សនារៀងរាល់ថ្ងៃសុក្រ វេលាម៉ោង ៨ :​ ០០ នាទីយប់តទៅ! \r\n\r\nនេះជាឱកាសសម្រាប់ប្អូនៗសិស្សានុសិស្ស - និស្សិត និងសាធារណៈជនដែលមានក្តីស្រម៉ៃចង់ក្លាយជាអ្នកអាជីពជា AI Engineer កុំភ្លេចតាមដានទស្សនាកិច្ចពិភាក្សា និងការចែករំលែកលើអាជីពមួយនេះទាំងអស់គ្នា។\r\n\r\nគួរបញ្ជាក់ផងដែរកម្មវិធី Major &amp; Career Talk ត្រូវបានបង្កើតឡើងដោយអង្គការ MACA ក្នុងគោលបំណងដើម្បីជជែកគ្នាអំពីការជ្រើសរើសជំនាញសិក្សា និងអាជីពឱ្យបានត្រឹមត្រូវ នឹងការស្វែងយល់ឱ្យកាន់ច្បាស់អំពីជំនាញសិក្សា និងអាជីពនីមួយៗទៅកាន់សិស្ស-និស្សិត សាធារណៈជន  ជាពិសេសនោះគឺ យុវជនកម្ពុជាទាំងមូល ដែលមិនទាន់មានភាពច្បាស់លាស់ក្នុងការជ្រើសរើសជំនាញសិក្សា និងអាជីពដែលខ្លួនស្រលាញ់នាពេលអនាគត។', 'កិច្ចពិភាក្សាក្នុងសប្តាហ៍ទី៥ ក្រោមប្រធានបទ៖ &quot; អាជីពជា AI Engineer &quot; វាគ្មិនកិត្តិយស លោក វ៉ែន វណ្ណនុត អ្នក ជាជំនាញ AI Engineer ក្នុងកម្មវិធី Major &amp; Career Talk ផ្សាយជូនទស្សនារៀងរាល់ថ្ងៃសុក្រ វេលាម៉ោង ៨ :​ ០០ នាទីយប់តទៅ! \r\n\r\nនេះជាឱកាសសម្រាប់ប្អូនៗសិស្សានុសិស្ស - និស្សិត និងសាធារណៈជនដែលមានក្តីស្រម៉ៃចង់ក្លាយជាអ្នកអាជីពជា AI Engineer កុំភ្លេចតាមដានទស្សនាកិច្ចពិភាក្សា និងការចែករំលែកលើអាជីពមួយនេះទាំងអស់គ្នា។\r\n\r\nគួរបញ្ជាក់ផងដែរកម្មវិធី Major &amp; Career Talk ត្រូវបានបង្កើតឡើងដោយអង្គការ MACA ក្នុងគោលបំណងដើម្បីជជែកគ្នាអំពីការជ្រើសរើសជំនាញសិក្សា និងអាជីពឱ្យបានត្រឹមត្រូវ នឹងការស្វែងយល់ឱ្យកាន់ច្បាស់អំពីជំនាញសិក្សា និងអាជីពនីមួយៗទៅកាន់សិស្ស-និស្សិត សាធារណៈជន  ជាពិសេសនោះគឺ យុវជនកម្ពុជាទាំងមូល ដែលមិនទាន់មានភាពច្បាស់លាស់ក្នុងការជ្រើសរើសជំនាញសិក្សា និងអាជីពដែលខ្លួនស្រលាញ់នាពេលអនាគត។', 'កិច្ចពិភាក្សាក្នុងសប្តាហ៍ទី៥ ក្រោមប្រធានបទ៖ \" អាជីពជា AI Engineer \" វាគ្មិនកិត្តិយស លោក វ៉ែន វណ្ណនុត អ្នក ជាជំនាញ AI Engineer ក្នុងកម្មវិធី Major & Career Talk ផ្សាយជូនទស្សនារៀងរាល់ថ្ងៃសុក្រ វេលាម៉ោង ៨ :​ ០០ នាទីយប់តទៅ! \r\n\r\nនេះជាឱកាសសម្រាប់ប្អូនៗសិស្សានុសិស្ស - និស្សិត និងសាធារណៈជនដែលមានក្តីស្រម៉ៃចង់ក្លាយជាអ្នកអាជីពជា AI Engineer កុំភ្លេចតាមដានទស្សនាកិច្ចពិភាក្សា និងការចែករំលែកលើអាជីពមួយនេះទាំងអស់គ្នា។\r\n\r\nគួរបញ្ជាក់ផងដែរកម្មវិធី Major & Career Talk ត្រូវបានបង្កើតឡើងដោយអង្គការ MACA ក្នុងគោលបំណងដើម្បីជជែកគ្នាអំពីការជ្រើសរើសជំនាញសិក្សា និងអាជីពឱ្យបានត្រឹមត្រូវ នឹងការស្វែងយល់ឱ្យកាន់ច្បាស់អំពីជំនាញសិក្សា និងអាជីពនីមួយៗទៅកាន់សិស្ស-និស្សិត សាធារណៈជន  ជាពិសេសនោះគឺ យុវជនកម្ពុជាទាំងមូល ដែលមិនទាន់មានភាពច្បាស់លាស់ក្នុងការជ្រើសរើសជំនាញសិក្សា និងអាជីពដែលខ្លួនស្រលាញ់នាពេលអនាគត។', 'កិច្ចពិភាក្សាក្នុងសប្តាហ៍ទី៥ ក្រោមប្រធានបទ៖ \" អាជីពជា AI Engineer \" វាគ្មិនកិត្តិយស លោក វ៉ែន វណ្ណនុត អ្នក ជាជំនាញ AI Engineer ក្នុងកម្មវិធី Major & Career Talk ផ្សាយជូនទស្សនារៀងរាល់ថ្ងៃសុក្រ វេលាម៉ោង ៨ :​ ០០ នាទីយប់តទៅ! \r\n\r\nនេះជាឱកាសសម្រាប់ប្អូនៗសិស្សានុសិស្ស - និស្សិត និងសាធារណៈជនដែលមានក្តីស្រម៉ៃចង់ក្លាយជាអ្នកអាជីពជា AI Engineer កុំភ្លេចតាមដានទស្សនាកិច្ចពិភាក្សា និងការចែករំលែកលើអាជីពមួយនេះទាំងអស់គ្នា។\r\n\r\nគួរបញ្ជាក់ផងដែរកម្មវិធី Major & Career Talk ត្រូវបានបង្កើតឡើងដោយអង្គការ MACA ក្នុងគោលបំណងដើម្បីជជែកគ្នាអំពីការជ្រើសរើសជំនាញសិក្សា និងអាជីពឱ្យបានត្រឹមត្រូវ នឹងការស្វែងយល់ឱ្យកាន់ច្បាស់អំពីជំនាញសិក្សា និងអាជីពនីមួយៗទៅកាន់សិស្ស-និស្សិត សាធារណៈជន  ជាពិសេសនោះគឺ យុវជនកម្ពុជាទាំងមូល ដែលមិនទាន់មានភាពច្បាស់លាស់ក្នុងការជ្រើសរើសជំនាញសិក្សា និងអាជីពដែលខ្លួនស្រលាញ់នាពេលអនាគត។', 'Wealth Mainson Building Floor 6, Room 30, Sk.Chroychangva Kh.Chroy Changva', 'Wealth Mainson Building Floor 6, Room 30, Sk.Chroychangva Kh.Chroy Changva', '2025-04-25', 'https://youtu.be/GYm9T0Xq3F0?si=Sg63_lF_zYP14ZUS', NULL, 1, '2025-05-13 09:15:17'),
(10, 'អាជីពជា Back-End Developer វាគ្មិនកិត្តិយស លោក ចេន សុផល ក្នុងកម្មវិធី Major &amp; Career Talk។', 'អាជីពជា Back-End Developer វាគ្មិនកិត្តិយស លោក ចេន សុផល ក្នុងកម្មវិធី Major &amp; Career Talk។', 'កិច្ចពិភាក្សាក្នុងសប្តាហ៍ទី៤ ក្រោមប្រធានបទ៖ &quot; អាជីពជា Back-End Developer &quot; វាគ្មិនកិត្តិយស លោក ចេន សុផល អ្នកជំនាញ Back-End Developer   ក្នុងកម្មវិធី Major &amp; Career Talk ផ្សាយជូនទស្សនារៀងរាល់ថ្ងៃសុក្រ វេលាម៉ោង ៨ :​ ០០ នាទីយប់តទៅ! \r\n\r\nនេះជាឪកាសល្អសម្រាប់ប្អូនៗសិស្សានុសិស្ស និស្សិត និងសាធារណ:ជនដែលមានក្តីស្រម៉ៃចង់ក្លាយជា Back-End Developer  ទស្សនាកិច្ចពិភាក្សា និងការចែករំលែកលើអាជីពមួយនេះទាំងអស់គ្នា !\r\n\r\nគួរបញ្ជាក់ផងដែរកម្មវិធី Major &amp; Career Talk ត្រូវបានបង្កើតឡើងដោយអង្គការ MACA ក្នុងគោលបំណងដើម្បីជជែកគ្នាអំពីការជ្រើសរើសជំនាញសិក្សា និងអាជីពឱ្យបានត្រឹមត្រូវ នឹងការស្វែងយល់ឱ្យកាន់ច្បាស់អំពីជំនាញសិក្សា និងអាជីពនីមួយៗទៅកាន់សិស្ស-និស្សិត សាធារណៈជន  ជាពិសេសនោះគឺ យុវជនកម្ពុជាទាំងមូល ដែលមិនទាន់មានភាពច្បាស់លាស់ក្នុងការជ្រើសរើសជំនាញសិក្សា និងអាជីពដែលខ្លួនស្រលាញ់នាពេលអនាគត ដែលចាក់ផ្សាយរៀងរាល់ថ្ងៃសុក្រ ម៉ោង៨:០០ នាទីយប់!', 'កិច្ចពិភាក្សាក្នុងសប្តាហ៍ទី៤ ក្រោមប្រធានបទ៖ &quot; អាជីពជា Back-End Developer &quot; វាគ្មិនកិត្តិយស លោក ចេន សុផល អ្នកជំនាញ Back-End Developer   ក្នុងកម្មវិធី Major &amp; Career Talk ផ្សាយជូនទស្សនារៀងរាល់ថ្ងៃសុក្រ វេលាម៉ោង ៨ :​ ០០ នាទីយប់តទៅ! \r\n\r\nនេះជាឪកាសល្អសម្រាប់ប្អូនៗសិស្សានុសិស្ស និស្សិត និងសាធារណ:ជនដែលមានក្តីស្រម៉ៃចង់ក្លាយជា Back-End Developer  ទស្សនាកិច្ចពិភាក្សា និងការចែករំលែកលើអាជីពមួយនេះទាំងអស់គ្នា !\r\n\r\nគួរបញ្ជាក់ផងដែរកម្មវិធី Major &amp; Career Talk ត្រូវបានបង្កើតឡើងដោយអង្គការ MACA ក្នុងគោលបំណងដើម្បីជជែកគ្នាអំពីការជ្រើសរើសជំនាញសិក្សា និងអាជីពឱ្យបានត្រឹមត្រូវ នឹងការស្វែងយល់ឱ្យកាន់ច្បាស់អំពីជំនាញសិក្សា និងអាជីពនីមួយៗទៅកាន់សិស្ស-និស្សិត សាធារណៈជន  ជាពិសេសនោះគឺ យុវជនកម្ពុជាទាំងមូល ដែលមិនទាន់មានភាពច្បាស់លាស់ក្នុងការជ្រើសរើសជំនាញសិក្សា និងអាជីពដែលខ្លួនស្រលាញ់នាពេលអនាគត ដែលចាក់ផ្សាយរៀងរាល់ថ្ងៃសុក្រ ម៉ោង៨:០០ នាទីយប់!', 'កិច្ចពិភាក្សាក្នុងសប្តាហ៍ទី៤ ក្រោមប្រធានបទ៖ \" អាជីពជា Back-End Developer \" វាគ្មិនកិត្តិយស លោក ចេន សុផល អ្នកជំនាញ Back-End Developer   ក្នុងកម្មវិធី Major & Career Talk ផ្សាយជូនទស្សនារៀងរាល់ថ្ងៃសុក្រ វេលាម៉ោង ៨ :​ ០០ នាទីយប់តទៅ! \r\n\r\nនេះជាឪកាសល្អសម្រាប់ប្អូនៗសិស្សានុសិស្ស និស្សិត និងសាធារណ:ជនដែលមានក្តីស្រម៉ៃចង់ក្លាយជា Back-End Developer  ទស្សនាកិច្ចពិភាក្សា និងការចែករំលែកលើអាជីពមួយនេះទាំងអស់គ្នា !\r\n\r\nគួរបញ្ជាក់ផងដែរកម្មវិធី Major & Career Talk ត្រូវបានបង្កើតឡើងដោយអង្គការ MACA ក្នុងគោលបំណងដើម្បីជជែកគ្នាអំពីការជ្រើសរើសជំនាញសិក្សា និងអាជីពឱ្យបានត្រឹមត្រូវ នឹងការស្វែងយល់ឱ្យកាន់ច្បាស់អំពីជំនាញសិក្សា និងអាជីពនីមួយៗទៅកាន់សិស្ស-និស្សិត សាធារណៈជន  ជាពិសេសនោះគឺ យុវជនកម្ពុជាទាំងមូល ដែលមិនទាន់មានភាពច្បាស់លាស់ក្នុងការជ្រើសរើសជំនាញសិក្សា និងអាជីពដែលខ្លួនស្រលាញ់នាពេលអនាគត ដែលចាក់ផ្សាយរៀងរាល់ថ្ងៃសុក្រ ម៉ោង៨:០០ នាទីយប់! \r\n', 'កិច្ចពិភាក្សាក្នុងសប្តាហ៍ទី៤ ក្រោមប្រធានបទ៖ \" អាជីពជា Back-End Developer \" វាគ្មិនកិត្តិយស លោក ចេន សុផល អ្នកជំនាញ Back-End Developer   ក្នុងកម្មវិធី Major & Career Talk ផ្សាយជូនទស្សនារៀងរាល់ថ្ងៃសុក្រ វេលាម៉ោង ៨ :​ ០០ នាទីយប់តទៅ! \r\n\r\nនេះជាឪកាសល្អសម្រាប់ប្អូនៗសិស្សានុសិស្ស និស្សិត និងសាធារណ:ជនដែលមានក្តីស្រម៉ៃចង់ក្លាយជា Back-End Developer  ទស្សនាកិច្ចពិភាក្សា និងការចែករំលែកលើអាជីពមួយនេះទាំងអស់គ្នា !\r\n\r\nគួរបញ្ជាក់ផងដែរកម្មវិធី Major & Career Talk ត្រូវបានបង្កើតឡើងដោយអង្គការ MACA ក្នុងគោលបំណងដើម្បីជជែកគ្នាអំពីការជ្រើសរើសជំនាញសិក្សា និងអាជីពឱ្យបានត្រឹមត្រូវ នឹងការស្វែងយល់ឱ្យកាន់ច្បាស់អំពីជំនាញសិក្សា និងអាជីពនីមួយៗទៅកាន់សិស្ស-និស្សិត សាធារណៈជន  ជាពិសេសនោះគឺ យុវជនកម្ពុជាទាំងមូល ដែលមិនទាន់មានភាពច្បាស់លាស់ក្នុងការជ្រើសរើសជំនាញសិក្សា និងអាជីពដែលខ្លួនស្រលាញ់នាពេលអនាគត ដែលចាក់ផ្សាយរៀងរាល់ថ្ងៃសុក្រ ម៉ោង៨:០០ នាទីយប់! \r\n', 'Wealth Mainson Building Floor 6, Room 30, Sk.Chroychangva Kh.Chroy Changva', 'Wealth Mainson Building Floor 6, Room 30, Sk.Chroychangva Kh.Chroy Changva', '2025-04-21', 'https://youtu.be/1xRxjBAKoio?si=OifK-6SMs3sqS0CJ', NULL, 1, '2025-05-13 09:16:37'),
(11, 'កិច្ចពិភាក្សាក្នុងសប្តាហ៍ទី៣ ក្រោមប្រធានបទ៖ &quot; អាជីពជាគ្រូបង្រៀន &quot; វាគ្មិនកិត្តិយស កញ្ញា ជា សុខនីត។', 'កិច្ចពិភាក្សាក្នុងសប្តាហ៍ទី៣ ក្រោមប្រធានបទ៖ &quot; អាជីពជាគ្រូបង្រៀន &quot; វាគ្មិនកិត្តិយស កញ្ញា ជា សុខនីត។', 'កិច្ចពិភាក្សាក្នុងសប្តាហ៍ទី៣ ក្រោមប្រធានបទ៖ &quot; អាជីពជាគ្រូបង្រៀន &quot; វាគ្មិនកិត្តិយស កញ្ញា ជា សុខនីត អតីតគ្រូបង្រៀនបឋមសិក្សា បច្ចុប្បន្នជា គរុនិស្សិតកម្រិតបរិញ្ញាត្រ + ២ ឯកទេសអក្សសាស្រ្តខ្មែរ ក្នុងកម្មវិធី Major &amp; Career Talk ផ្សាយជូនទស្សនារៀងរាល់ថ្ងៃសុក្រ វេលាម៉ោង ៨ :​ ០០ នាទីយប់តទៅ! \r\n\r\nនេះជាឱកាសសម្រាប់ប្អូនៗសិស្សានុសិស្ស - និស្សិត និងសាធារណៈជនដែលមានក្តីស្រម៉ៃចង់ក្លាយជា គ្រូបង្រៀន កុំភ្លេចតាមដានកិច្ចពិភាក្សា និងការចែករំលែកលើអាជីពមួយនេះទាំងអស់គ្នា !\r\n\r\nគួរបញ្ជាក់ផងដែរកម្មវិធី Major &amp; Career Talk ត្រូវបានបង្កើតឡើងដោយអង្គការ MACA ក្នុងគោលបំណងដើម្បីជជែកគ្នាអំពីការជ្រើសរើសជំនាញសិក្សា និងអាជីពឱ្យបានត្រឹមត្រូវ នឹងការស្វែងយល់ឱ្យកាន់ច្បាស់អំពីជំនាញសិក្សា និងអាជីពនីមួយៗទៅកាន់សិស្ស-និស្សិត សាធារណៈជន  ជាពិសេសនោះគឺ យុវជនកម្ពុជាទាំងមូល ដែលមិនទាន់មានភាពច្បាស់លាស់ក្នុងការជ្រើសរើសជំនាញសិក្សា និងអាជីពដែលខ្លួនស្រលាញ់នាពេលអនាគត។', 'កិច្ចពិភាក្សាក្នុងសប្តាហ៍ទី៣ ក្រោមប្រធានបទ៖ &quot; អាជីពជាគ្រូបង្រៀន &quot; វាគ្មិនកិត្តិយស កញ្ញា ជា សុខនីត អតីតគ្រូបង្រៀនបឋមសិក្សា បច្ចុប្បន្នជា គរុនិស្សិតកម្រិតបរិញ្ញាត្រ + ២ ឯកទេសអក្សសាស្រ្តខ្មែរ ក្នុងកម្មវិធី Major &amp; Career Talk ផ្សាយជូនទស្សនារៀងរាល់ថ្ងៃសុក្រ វេលាម៉ោង ៨ :​ ០០ នាទីយប់តទៅ! \r\n\r\nនេះជាឱកាសសម្រាប់ប្អូនៗសិស្សានុសិស្ស - និស្សិត និងសាធារណៈជនដែលមានក្តីស្រម៉ៃចង់ក្លាយជា គ្រូបង្រៀន កុំភ្លេចតាមដានកិច្ចពិភាក្សា និងការចែករំលែកលើអាជីពមួយនេះទាំងអស់គ្នា !\r\n\r\nគួរបញ្ជាក់ផងដែរកម្មវិធី Major &amp; Career Talk ត្រូវបានបង្កើតឡើងដោយអង្គការ MACA ក្នុងគោលបំណងដើម្បីជជែកគ្នាអំពីការជ្រើសរើសជំនាញសិក្សា និងអាជីពឱ្យបានត្រឹមត្រូវ នឹងការស្វែងយល់ឱ្យកាន់ច្បាស់អំពីជំនាញសិក្សា និងអាជីពនីមួយៗទៅកាន់សិស្ស-និស្សិត សាធារណៈជន  ជាពិសេសនោះគឺ យុវជនកម្ពុជាទាំងមូល ដែលមិនទាន់មានភាពច្បាស់លាស់ក្នុងការជ្រើសរើសជំនាញសិក្សា និងអាជីពដែលខ្លួនស្រលាញ់នាពេលអនាគត។', 'កិច្ចពិភាក្សាក្នុងសប្តាហ៍ទី៣ ក្រោមប្រធានបទ៖ \" អាជីពជាគ្រូបង្រៀន \" វាគ្មិនកិត្តិយស កញ្ញា ជា សុខនីត អតីតគ្រូបង្រៀនបឋមសិក្សា បច្ចុប្បន្នជា គរុនិស្សិតកម្រិតបរិញ្ញាត្រ + ២ ឯកទេសអក្សសាស្រ្តខ្មែរ ក្នុងកម្មវិធី Major & Career Talk ផ្សាយជូនទស្សនារៀងរាល់ថ្ងៃសុក្រ វេលាម៉ោង ៨ :​ ០០ នាទីយប់តទៅ! \r\n\r\nនេះជាឱកាសសម្រាប់ប្អូនៗសិស្សានុសិស្ស - និស្សិត និងសាធារណៈជនដែលមានក្តីស្រម៉ៃចង់ក្លាយជា គ្រូបង្រៀន កុំភ្លេចតាមដានកិច្ចពិភាក្សា និងការចែករំលែកលើអាជីពមួយនេះទាំងអស់គ្នា !\r\n\r\nគួរបញ្ជាក់ផងដែរកម្មវិធី Major & Career Talk ត្រូវបានបង្កើតឡើងដោយអង្គការ MACA ក្នុងគោលបំណងដើម្បីជជែកគ្នាអំពីការជ្រើសរើសជំនាញសិក្សា និងអាជីពឱ្យបានត្រឹមត្រូវ នឹងការស្វែងយល់ឱ្យកាន់ច្បាស់អំពីជំនាញសិក្សា និងអាជីពនីមួយៗទៅកាន់សិស្ស-និស្សិត សាធារណៈជន  ជាពិសេសនោះគឺ យុវជនកម្ពុជាទាំងមូល ដែលមិនទាន់មានភាពច្បាស់លាស់ក្នុងការជ្រើសរើសជំនាញសិក្សា និងអាជីពដែលខ្លួនស្រលាញ់នាពេលអនាគត។', 'កិច្ចពិភាក្សាក្នុងសប្តាហ៍ទី៣ ក្រោមប្រធានបទ៖ \" អាជីពជាគ្រូបង្រៀន \" វាគ្មិនកិត្តិយស កញ្ញា ជា សុខនីត អតីតគ្រូបង្រៀនបឋមសិក្សា បច្ចុប្បន្នជា គរុនិស្សិតកម្រិតបរិញ្ញាត្រ + ២ ឯកទេសអក្សសាស្រ្តខ្មែរ ក្នុងកម្មវិធី Major & Career Talk ផ្សាយជូនទស្សនារៀងរាល់ថ្ងៃសុក្រ វេលាម៉ោង ៨ :​ ០០ នាទីយប់តទៅ! \r\n\r\nនេះជាឱកាសសម្រាប់ប្អូនៗសិស្សានុសិស្ស - និស្សិត និងសាធារណៈជនដែលមានក្តីស្រម៉ៃចង់ក្លាយជា គ្រូបង្រៀន កុំភ្លេចតាមដានកិច្ចពិភាក្សា និងការចែករំលែកលើអាជីពមួយនេះទាំងអស់គ្នា !\r\n\r\nគួរបញ្ជាក់ផងដែរកម្មវិធី Major & Career Talk ត្រូវបានបង្កើតឡើងដោយអង្គការ MACA ក្នុងគោលបំណងដើម្បីជជែកគ្នាអំពីការជ្រើសរើសជំនាញសិក្សា និងអាជីពឱ្យបានត្រឹមត្រូវ នឹងការស្វែងយល់ឱ្យកាន់ច្បាស់អំពីជំនាញសិក្សា និងអាជីពនីមួយៗទៅកាន់សិស្ស-និស្សិត សាធារណៈជន  ជាពិសេសនោះគឺ យុវជនកម្ពុជាទាំងមូល ដែលមិនទាន់មានភាពច្បាស់លាស់ក្នុងការជ្រើសរើសជំនាញសិក្សា និងអាជីពដែលខ្លួនស្រលាញ់នាពេលអនាគត។', 'Wealth Mainson Building Floor 6, Room 30, Sk.Chroychangva Kh.Chroy Changva', 'Wealth Mainson Building Floor 6, Room 30, Sk.Chroychangva Kh.Chroy Changva', '2025-04-11', 'https://youtu.be/PlBJsK7Gs1E?si=1uHGooW42jgfAaI9', NULL, 1, '2025-05-13 09:18:36'),
(12, 'កិច្ចពិភាក្សាក្នុងសប្តាហ៍ទី២ ក្រោមបទ៖ &quot; អាជីពជាទណ្ឌបណ្ឌិត &quot; វាគ្មិនកិត្តិយស ទណ្ឌបណ្ឌិត ប៊ិច សុធា។', 'កិច្ចពិភាក្សាក្នុងសប្តាហ៍ទី២ ក្រោមបទ៖ &quot; អាជីពជាទណ្ឌបណ្ឌិត &quot; វាគ្មិនកិត្តិយស ទណ្ឌបណ្ឌិត ប៊ិច សុធា។', 'កិច្ចពិភាក្សាក្នុងសប្តាហ៍ទី២ ក្រោមបទ៖ &quot; អាជីពជាទណ្ឌបណ្ឌិត (ពេទ្យធ្មេញ)  &quot; វាគ្មិនកិត្តិយស ទណ្ឌបណ្ឌិត ប៊ិច សុធា ក្នុងកម្មវិធី Major &amp; Career Talk។\r\n\r\nផ្សាយជូនទស្សនារៀងរាល់ថ្ងៃសុក្រ វេលាម៉ោង ៨ :​ ០០ នាទីយប់តទៅ! \r\n\r\nនេះជាឪកាសល្អសម្រាប់ប្អូនៗសិស្សានុសិស្ស និស្សិត និងសាធារណ:ជនដែលមានក្តីស្រម៉ៃចង់ក្លាយជា ទណ្ឌបណ្ឌិត (ពេទ្យធ្មេញ)  ទស្សនាកិច្ចពិភាក្សា និងការចែករំលែកលើអាជីពមួយនេះទាំងអស់គ្នា !\r\n\r\nគួរបញ្ជាក់ផងដែរកម្មវិធី Major &amp; Career Talk ត្រូវបានបង្កើតឡើងដោយអង្គការ MACA ក្នុងគោលបំណងដើម្បីជជែកគ្នាអំពីការជ្រើសរើសជំនាញសិក្សា និងអាជីពឱ្យបានត្រឹមត្រូវ នឹងការស្វែងយល់ឱ្យកាន់ច្បាស់អំពីជំនាញសិក្សា និងអាជីពនីមួយៗទៅកាន់សិស្ស-និស្សិត សាធារណៈជន  ជាពិសេសនោះគឺ យុវជនកម្ពុជាទាំងមូល ដែលមិនទាន់មានភាពច្បាស់លាស់ក្នុងការជ្រើសរើសជំនាញសិក្សា និងអាជីពដែលខ្លួនស្រលាញ់នាពេលអនាគត ដែលចាក់ផ្សាយរៀងរាល់ថ្ងៃសុក្រ ម៉ោង៨:០០ នាទីយប់!', 'កិច្ចពិភាក្សាក្នុងសប្តាហ៍ទី២ ក្រោមបទ៖ &quot; អាជីពជាទណ្ឌបណ្ឌិត (ពេទ្យធ្មេញ)  &quot; វាគ្មិនកិត្តិយស ទណ្ឌបណ្ឌិត ប៊ិច សុធា ក្នុងកម្មវិធី Major &amp; Career Talk។\r\n\r\nផ្សាយជូនទស្សនារៀងរាល់ថ្ងៃសុក្រ វេលាម៉ោង ៨ :​ ០០ នាទីយប់តទៅ! \r\n\r\nនេះជាឪកាសល្អសម្រាប់ប្អូនៗសិស្សានុសិស្ស និស្សិត និងសាធារណ:ជនដែលមានក្តីស្រម៉ៃចង់ក្លាយជា ទណ្ឌបណ្ឌិត (ពេទ្យធ្មេញ)  ទស្សនាកិច្ចពិភាក្សា និងការចែករំលែកលើអាជីពមួយនេះទាំងអស់គ្នា !\r\n\r\nគួរបញ្ជាក់ផងដែរកម្មវិធី Major &amp; Career Talk ត្រូវបានបង្កើតឡើងដោយអង្គការ MACA ក្នុងគោលបំណងដើម្បីជជែកគ្នាអំពីការជ្រើសរើសជំនាញសិក្សា និងអាជីពឱ្យបានត្រឹមត្រូវ នឹងការស្វែងយល់ឱ្យកាន់ច្បាស់អំពីជំនាញសិក្សា និងអាជីពនីមួយៗទៅកាន់សិស្ស-និស្សិត សាធារណៈជន  ជាពិសេសនោះគឺ យុវជនកម្ពុជាទាំងមូល ដែលមិនទាន់មានភាពច្បាស់លាស់ក្នុងការជ្រើសរើសជំនាញសិក្សា និងអាជីពដែលខ្លួនស្រលាញ់នាពេលអនាគត ដែលចាក់ផ្សាយរៀងរាល់ថ្ងៃសុក្រ ម៉ោង៨:០០ នាទីយប់!', 'កិច្ចពិភាក្សាក្នុងសប្តាហ៍ទី២ ក្រោមបទ៖ \" អាជីពជាទណ្ឌបណ្ឌិត (ពេទ្យធ្មេញ)  \" វាគ្មិនកិត្តិយស ទណ្ឌបណ្ឌិត ប៊ិច សុធា ក្នុងកម្មវិធី Major & Career Talk។\r\n\r\nផ្សាយជូនទស្សនារៀងរាល់ថ្ងៃសុក្រ វេលាម៉ោង ៨ :​ ០០ នាទីយប់តទៅ! \r\n\r\nនេះជាឪកាសល្អសម្រាប់ប្អូនៗសិស្សានុសិស្ស និស្សិត និងសាធារណ:ជនដែលមានក្តីស្រម៉ៃចង់ក្លាយជា ទណ្ឌបណ្ឌិត (ពេទ្យធ្មេញ)  ទស្សនាកិច្ចពិភាក្សា និងការចែករំលែកលើអាជីពមួយនេះទាំងអស់គ្នា !\r\n\r\nគួរបញ្ជាក់ផងដែរកម្មវិធី Major & Career Talk ត្រូវបានបង្កើតឡើងដោយអង្គការ MACA ក្នុងគោលបំណងដើម្បីជជែកគ្នាអំពីការជ្រើសរើសជំនាញសិក្សា និងអាជីពឱ្យបានត្រឹមត្រូវ នឹងការស្វែងយល់ឱ្យកាន់ច្បាស់អំពីជំនាញសិក្សា និងអាជីពនីមួយៗទៅកាន់សិស្ស-និស្សិត សាធារណៈជន  ជាពិសេសនោះគឺ យុវជនកម្ពុជាទាំងមូល ដែលមិនទាន់មានភាពច្បាស់លាស់ក្នុងការជ្រើសរើសជំនាញសិក្សា និងអាជីពដែលខ្លួនស្រលាញ់នាពេលអនាគត ដែលចាក់ផ្សាយរៀងរាល់ថ្ងៃសុក្រ ម៉ោង៨:០០ នាទីយប់! ', 'កិច្ចពិភាក្សាក្នុងសប្តាហ៍ទី២ ក្រោមបទ៖ \" អាជីពជាទណ្ឌបណ្ឌិត (ពេទ្យធ្មេញ)  \" វាគ្មិនកិត្តិយស ទណ្ឌបណ្ឌិត ប៊ិច សុធា ក្នុងកម្មវិធី Major & Career Talk។\r\n\r\nផ្សាយជូនទស្សនារៀងរាល់ថ្ងៃសុក្រ វេលាម៉ោង ៨ :​ ០០ នាទីយប់តទៅ! \r\n\r\nនេះជាឪកាសល្អសម្រាប់ប្អូនៗសិស្សានុសិស្ស និស្សិត និងសាធារណ:ជនដែលមានក្តីស្រម៉ៃចង់ក្លាយជា ទណ្ឌបណ្ឌិត (ពេទ្យធ្មេញ)  ទស្សនាកិច្ចពិភាក្សា និងការចែករំលែកលើអាជីពមួយនេះទាំងអស់គ្នា !\r\n\r\nគួរបញ្ជាក់ផងដែរកម្មវិធី Major & Career Talk ត្រូវបានបង្កើតឡើងដោយអង្គការ MACA ក្នុងគោលបំណងដើម្បីជជែកគ្នាអំពីការជ្រើសរើសជំនាញសិក្សា និងអាជីពឱ្យបានត្រឹមត្រូវ នឹងការស្វែងយល់ឱ្យកាន់ច្បាស់អំពីជំនាញសិក្សា និងអាជីពនីមួយៗទៅកាន់សិស្ស-និស្សិត សាធារណៈជន  ជាពិសេសនោះគឺ យុវជនកម្ពុជាទាំងមូល ដែលមិនទាន់មានភាពច្បាស់លាស់ក្នុងការជ្រើសរើសជំនាញសិក្សា និងអាជីពដែលខ្លួនស្រលាញ់នាពេលអនាគត ដែលចាក់ផ្សាយរៀងរាល់ថ្ងៃសុក្រ ម៉ោង៨:០០ នាទីយប់! ', 'Wealth Mainson Building Floor 6, Room 30, Sk.Chroychangva Kh.Chroy Changva', 'Wealth Mainson Building Floor 6, Room 30, Sk.Chroychangva Kh.Chroy Changva', '2025-05-04', 'https://youtu.be/uq5MxFL8E-M?si=FUYi448M9JyiBZCh', NULL, 1, '2025-05-13 09:20:14');

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `name_km` varchar(100) DEFAULT NULL,
  `position` varchar(100) NOT NULL,
  `position_km` varchar(100) DEFAULT NULL,
  `bio` text NOT NULL,
  `bio_km` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`id`, `name`, `name_km`, `position`, `position_km`, `bio`, `bio_km`, `image_path`, `is_active`, `display_order`, `created_at`) VALUES
(2, 'Molyka Brasoeur', 'ប្រសើរ​​ ម៉ូលីកា', 'Board Director Master of Education', 'អគ្គនាយកក្រុមប្រឹក្សាភិបាលអនុបណ្ឌិតផ្នែកអប់រំ', '​​​​​​', '​', 'uploads/team/1752051728_489670142_3444957108969267_6685229239977796827_n.jpg', 1, 2, '2025-04-22 08:21:19'),
(3, 'Brasoeur Reasey', 'ប្រសើរ​​ រាសី', 'Board Member Master of International Economic &amp; Business', 'សមាជិកក្រុមប្រឹក្សាភិបាលអនុបណ្ឌិតផ្នែកសេដ្ឋកិច្ច និងអាជីវកម្មអន្តរជាតិ', '', '​​', 'uploads/team/1747680358_1739509669.jpg', 1, 3, '2025-04-22 08:21:19'),
(4, 'Sum Chan Dara', 'ស៊ុម​ ច័ន្ទតារា', 'Board Member Master of Public Administration', 'សមាជិកក្រុមប្រឹក្សាភិបាលអនុបណ្ឌិតផ្នែករដ្ឋបាលសាធារណៈ', '', '', 'uploads/team/1752053491_dara.jpg', 1, 4, '2025-04-22 08:21:19'),
(7, 'Dr.Sothea Bich', 'ប៊ិច ​សុធា', 'Deputy Director Master of Career Counseling', 'អនុនាយកអនុបណ្ឌិតផ្នែកជំនួយផ្នែកអាជីព', '', '', 'uploads/team/1752055094_sothea.jpg', 1, 5, '2025-06-09 06:51:58'),
(8, 'Ms.Dalis Theng', 'ថេង ដាលីស', 'Board Member of Global Innovation Management', 'សមាជិកក្រុមប្រឹក្សាភិបាលផ្នែកការគ្រប់គ្រងនវានុវត្តន៍សកល', '', '', 'uploads/team/1749452010_Screenshot 2025-06-09 134835.png', 1, 6, '2025-06-09 06:53:30'),
(9, 'Mr.Senghor Lay', 'ឡាយ​ សេង​ហ៊', 'Board  Member Master of Education', 'សមាជិកក្រុមប្រឹក្សាភិបាលអនុបណ្ឌិតផ្នែកអប់រំ', '', '', 'uploads/team/1752051395_senghor.jpg', 1, 7, '2025-06-09 06:54:39'),
(10, 'Mr. Rothana Oum', 'អ៊ុំ រតនា', 'Admin and Finance Master of Logistics', 'រដ្ឋបាល និងហិរញ្ញវត្ថុ អនុបណ្ឌិតផ្នែក​ Logistics', '', '', 'uploads/team/1752051296_rothana.jpg', 1, 8, '2025-06-09 06:56:23'),
(11, 'Oum Kunthel', 'អ៊ុម​​​ គន្ធែល', 'Master of Business', 'អនុបណ្ឌិតផ្នែកអាជីវកម្ម', '​​​', '​​​', 'uploads/team/1752053169_37769190_2316912741668580_6109929623846387712_n.jpg', 1, 1, '2025-07-09 09:26:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` varchar(20) DEFAULT 'admin',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `is_active`, `created_at`) VALUES
(8, 'Maca@Admin', '$2y$10$NvkQiOAIPt3sDSyF2kUKL.Hj4FeQVUdeoBUBJnCBUzvIzox1lBYya', 'admin@maca.edu', 'admin', 1, '2025-05-13 01:47:32'),
(9, 'Panha682', '$2y$10$rAcoSyKzscbuCXJhjbcia.c/lnvRMvzjt0H0imxt78G7qZwZ6ZjG6', 'sothpanha682@gmail.com', 'admin', 1, '2025-06-02 06:34:45');

-- --------------------------------------------------------

--
-- Table structure for table `user_activity_log`
--

CREATE TABLE `user_activity_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `activity_type` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `career_counselling_forms`
--
ALTER TABLE `career_counselling_forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `career_paths`
--
ALTER TABLE `career_paths`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `career_steps`
--
ALTER TABLE `career_steps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `career_path_id` (`career_path_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `intern_news`
--
ALTER TABLE `intern_news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_job_id` (`job_id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_application_date` (`application_date`),
  ADD KEY `idx_full_name` (`full_name`);

--
-- Indexes for table `job_postings`
--
ALTER TABLE `job_postings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `popular_jobs`
--
ALTER TABLE `popular_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `popular_majors`
--
ALTER TABLE `popular_majors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roadshow`
--
ALTER TABLE `roadshow`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roadshows`
--
ALTER TABLE `roadshows`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roadshow_videos`
--
ALTER TABLE `roadshow_videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roadshow_id` (`roadshow_id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `slider_images`
--
ALTER TABLE `slider_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slides`
--
ALTER TABLE `slides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `talkshow`
--
ALTER TABLE `talkshow`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `talkshows`
--
ALTER TABLE `talkshows`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_activity_log`
--
ALTER TABLE `user_activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `career_counselling_forms`
--
ALTER TABLE `career_counselling_forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `career_paths`
--
ALTER TABLE `career_paths`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `career_steps`
--
ALTER TABLE `career_steps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `intern_news`
--
ALTER TABLE `intern_news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `job_applications`
--
ALTER TABLE `job_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `job_postings`
--
ALTER TABLE `job_postings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `popular_jobs`
--
ALTER TABLE `popular_jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `popular_majors`
--
ALTER TABLE `popular_majors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roadshow`
--
ALTER TABLE `roadshow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `roadshows`
--
ALTER TABLE `roadshows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roadshow_videos`
--
ALTER TABLE `roadshow_videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `slider_images`
--
ALTER TABLE `slider_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `slides`
--
ALTER TABLE `slides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `talkshow`
--
ALTER TABLE `talkshow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `talkshows`
--
ALTER TABLE `talkshows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_activity_log`
--
ALTER TABLE `user_activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `career_steps`
--
ALTER TABLE `career_steps`
  ADD CONSTRAINT `career_steps_ibfk_1` FOREIGN KEY (`career_path_id`) REFERENCES `career_paths` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `roadshow_videos`
--
ALTER TABLE `roadshow_videos`
  ADD CONSTRAINT `roadshow_videos_ibfk_1` FOREIGN KEY (`roadshow_id`) REFERENCES `roadshow` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_activity_log`
--
ALTER TABLE `user_activity_log`
  ADD CONSTRAINT `user_activity_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
