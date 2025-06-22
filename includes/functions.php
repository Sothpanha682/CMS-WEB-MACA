<?php
// Prevent direct access to this file
if (!defined('INCLUDED')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

/**
 * Check if user is logged in
 * 
 * @return bool True if user is logged in, false otherwise
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Check if user is an admin
 * 
 * @return bool True if user is an admin, false otherwise
 */
function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}

/**
 * Get text based on selected language
 * 
 * @param string $en English text
 * @param string $kh Khmer text
 * @return string Text in the selected language
 */
function getLangText($en, $kh) {
    $lang = $_SESSION['lang'] ?? 'en';
    if ($lang == 'kh') {
        return '<span class="khmer-text">' . $kh . '</span>';
    }
    return $en;
}

/**
 * Get current language
 * 
 * @return string Current language code ('en' or 'kh')
 */
function getCurrentLanguage() {
    return $_SESSION['lang'] ?? 'en';
}

/**
 * Format date for display
 * 
 * @param string $date Date string
 * @param string $format Format string
 * @return string Formatted date
 */
function formatDate($date, $format = 'M d, Y') {
    if (empty($date)) return '';
    return date($format, strtotime($date));
}

/**
 * Truncate text to a specific length
 * 
 * @param string $text Text to truncate
 * @param int $length Maximum length
 * @param string $append Text to append if truncated
 * @return string Truncated text
 */
function truncateText($text, $length = 100, $append = '...') {
    $text = strip_tags($text);
    if (strlen($text) > $length) {
        $text = substr($text, 0, $length);
        $text = substr($text, 0, strrpos($text, ' '));
        $text .= $append;
    }
    return $text;
}

/**
 * Generate a random string
 * 
 * @param int $length Length of the string
 * @return string Random string
 */
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * Sanitize input data
 * 
 * @param string $data Data to sanitize
 * @return string Sanitized data
 */
function sanitize($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    if ($conn) {
        $data = mysqli_real_escape_string($conn, $data);
    }
    return $data;
}

/**
 * Alias for sanitize() to maintain backward compatibility
 */
function sanitizeInput($data) {
    return sanitize($data);
}

/**
 * Upload a file
 * 
 * @param array $file File data from $_FILES
 * @param string $destination Destination directory
 * @param array $allowedTypes Allowed file types
 * @param int $maxSize Maximum file size in bytes
 * @return array Result with status and message
 */
function uploadFile($file, $destination = 'uploads/', $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'], $maxSize = 5242880) {
    // Debug information
    error_log("Upload function called for file: " . print_r($file, true));
    error_log("Destination: " . $destination);
    
    // Check if file was uploaded
    if (!isset($file) || $file['error'] != 0) {
        $errorMessages = [
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk',
            8 => 'A PHP extension stopped the file upload'
        ];
        
        $errorMessage = isset($errorMessages[$file['error']]) 
            ? $errorMessages[$file['error']] 
            : 'Unknown upload error';
            
        error_log("Upload error: " . $errorMessage);
        return ['status' => false, 'message' => 'Error uploading file: ' . $errorMessage];
    }
    
    // Check file size
    if ($file['size'] > $maxSize) {
        error_log("File too large: " . $file['size'] . " bytes (max: " . $maxSize . " bytes)");
        return ['status' => false, 'message' => 'File is too large. Maximum size is ' . ($maxSize / 1048576) . 'MB.'];
    }
    
    // Check file type
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($fileExtension, $allowedTypes)) {
        error_log("Invalid file type: " . $fileExtension);
        return ['status' => false, 'message' => 'Invalid file type. Allowed types: ' . implode(', ', $allowedTypes)];
    }
    
    // Create destination directory if it doesn't exist
    if (!file_exists($destination)) {
        error_log("Creating directory: " . $destination);
        if (!mkdir($destination, 0755, true)) {
            error_log("Failed to create directory: " . $destination);
            return ['status' => false, 'message' => 'Failed to create upload directory.'];
        }
    }
    
    // Check if directory is writable
    if (!is_writable($destination)) {
        error_log("Directory not writable: " . $destination);
        return ['status' => false, 'message' => 'Upload directory is not writable.'];
    }
    
    // Generate unique filename
    $newFilename = generateRandomString() . '_' . time() . '.' . $fileExtension;
    $targetPath = $destination . $newFilename;
    
    error_log("Attempting to move uploaded file to: " . $targetPath);
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        error_log("File uploaded successfully to: " . $targetPath);
        return ['status' => true, 'message' => 'File uploaded successfully.', 'path' => $targetPath];
    } else {
        error_log("Failed to move uploaded file from " . $file['tmp_name'] . " to " . $targetPath);
        return ['status' => false, 'message' => 'Failed to move uploaded file.'];
    }
}

/**
 * Get site settings
 * 
 * @param string $settingName Setting name
 * @param mixed $default Default value if setting not found
 * @return mixed Setting value or default
 */
function getSiteSetting($settingName, $default = '') {
    global $conn;
    if (!$conn) {
        return $default;
    }
    
    $query = "SELECT value FROM site_settings WHERE setting_name = '$settingName'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['value'];
    }
    
    return $default;
}

/**
 * Update site setting
 * 
 * @param string $settingName Setting name
 * @param mixed $value Setting value
 * @return bool True if successful, false otherwise
 */
function updateSiteSetting($settingName, $value) {
    global $conn;
    if (!$conn) {
        return false;
    }
    
    $settingName = sanitize($settingName);
    $value = sanitize($value);
    
    // Check if setting exists
    $query = "SELECT id FROM site_settings WHERE setting_name = '$settingName'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        // Update existing setting
        $query = "UPDATE site_settings SET value = '$value' WHERE setting_name = '$settingName'";
    } else {
        // Insert new setting
        $query = "INSERT INTO site_settings (setting_name, value) VALUES ('$settingName', '$value')";
    }
    
    return mysqli_query($conn, $query);
}

/**
 * Get all announcements
 * 
 * @param PDO $pdo Database connection
 * @param int $limit Number of announcements to get
 * @param int $offset Offset for pagination
 * @return array Announcements
 */
function getAnnouncements($pdo, $limit = 10, $offset = 0) {
    if (!$pdo) {
        return [];
    }
    
    $lang = $_SESSION['lang'] ?? 'en';
    try {
        // Updated to explicitly sort by created_at in descending order (newest first)
        $stmt = $pdo->prepare("SELECT * FROM announcements ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching announcements: " . $e->getMessage());
        return [];
    }
}

/**
 * Get announcement by ID
 *
 * @param PDO $pdo Database connection
 * @param int $id Announcement ID
 * @return array|null Announcement data or null if not found
 */
function getAnnouncementById($pdo, $id) {
    if (!$pdo) {
        return null;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM announcements WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $announcement = $stmt->fetch(PDO::FETCH_ASSOC);
        return $announcement ?: null;
    } catch (PDOException $e) {
        error_log("Error fetching announcement by ID: " . $e->getMessage());
        return null;
    }
}

/**
 * Get all news articles
 * 
 * @param PDO $pdo Database connection
 * @param int $limit Number of news articles to get
 * @param int $offset Offset for pagination
 * @return array News articles
 */
function getNews($pdo, $limit = 10, $offset = 0) {
    if (!$pdo) {
        return [];
    }
    
    $lang = $_SESSION['lang'] ?? 'en';
    try {
        // Updated to explicitly sort by created_at in descending order (newest first)
        $stmt = $pdo->prepare("SELECT * FROM news ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching news: " . $e->getMessage());
        return [];
    }
}

/**
 * Get news article by ID
 *
 * @param PDO $pdo Database connection
 * @param int $id News article ID
 * @return array|null News article data or null if not found
 */
function getNewsById($pdo, $id) {
    if (!$pdo) {
        return null;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM news WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $news = $stmt->fetch(PDO::FETCH_ASSOC);
        return $news ?: null;
    } catch (PDOException $e) {
        error_log("Error fetching news by ID: " . $e->getMessage());
        return null;
    }
}

/**
 * Get all team members
 * 
 * @param PDO $pdo Database connection
 * @param bool $active_only Get only active team members
 * @return array Team members
 */
function getTeamMembers($pdo, $active_only = true) {
    if (!$pdo) {
        return [];
    }
    
    try {
        $sql = "SELECT * FROM team_members";
        if ($active_only) {
            $sql .= " WHERE is_active = 1";
        }
        $sql .= " ORDER BY display_order ASC, name ASC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching team members: " . $e->getMessage());
        return [];
    }
}

/**
 * Get all popular jobs
 * 
 * @param int $limit Number of jobs to get
 * @return array Popular jobs
 */
function getPopularJobs($limit = 6) {
    global $conn;
    if (!$conn) {
        return [];
    }
    
    $lang = $_SESSION['lang'] ?? 'en';
    $query = "SELECT * FROM popular_jobs WHERE lang = '$lang' ORDER BY id DESC LIMIT $limit";
    $result = mysqli_query($conn, $query);
    
    $jobs = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $jobs[] = $row;
        }
    }
    
    return $jobs;
}

/**
 * Get all popular majors
 * 
 * @param int $limit Number of majors to get
 * @return array Popular majors
 */
function getPopularMajors($limit = 6) {
    global $conn;
    if (!$conn) {
        return [];
    }
    
    $lang = $_SESSION['lang'] ?? 'en';
    $query = "SELECT * FROM popular_majors WHERE lang = '$lang' ORDER BY id DESC LIMIT $limit";
    $result = mysqli_query($conn, $query);
    
    $majors = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $majors[] = $row;
        }
    }
    
    return $majors;
}

/**
 * Get all career paths
 * 
 * @param int $limit Number of career paths to get
 * @return array Career paths
 */
function getCareerPaths($limit = 6) {
    global $conn;
    if (!$conn) {
        return [];
    }
    
    $lang = $_SESSION['lang'] ?? 'en';
    $query = "SELECT * FROM career_paths WHERE lang = '$lang' ORDER BY id DESC LIMIT $limit";
    $result = mysqli_query($conn, $query);
    
    $careers = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $careers[] = $row;
        }
    }
    
    return $careers;
}

/**
 * Get a specific popular job by ID
 * 
 * @param int $id Job ID
 * @return array|null Job data or null if not found
 */
function getPopularJobById($id) {
    global $conn;
    if (!$conn) {
        return null;
    }
    
    $id = (int)$id;
    $lang = $_SESSION['lang'] ?? 'en';
    $query = "SELECT * FROM popular_jobs WHERE id = $id AND lang = '$lang'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    
    return null;
}

/**
 * Get a specific popular major by ID
 * 
 * @param int $id Major ID
 * @return array|null Major data or null if not found
 */
function getPopularMajorById($id) {
    global $conn;
    if (!$conn) {
        return null;
    }
    
    $id = (int)$id;
    $lang = $_SESSION['lang'] ?? 'en';
    $query = "SELECT * FROM popular_majors WHERE id = $id AND lang = '$lang'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    
    return null;
}

/**
 * Get a specific career path by ID
 * 
 * @param int $id Career path ID
 * @return array|null Career path data or null if not found
 */
function getCareerPathById($id) {
    global $conn;
    if (!$conn) {
        return null;
    }
    
    $id = (int)$id;
    $lang = $_SESSION['lang'] ?? 'en';
    $query = "SELECT * FROM career_paths WHERE id = $id AND lang = '$lang'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    
    return null;
}

/**
 * Get contact messages
 * 
 * @param int $limit Number of messages to get
 * @param int $offset Offset for pagination
 * @param bool $unread_only Get only unread messages
 * @return array Contact messages
 */
function getContactMessages($limit = 10, $offset = 0, $unread_only = false) {
    global $pdo;
    if (!$pdo) {
        return [];
    }
    
    try {
        $sql = "SELECT * FROM contact_messages";
        if ($unread_only) {
            $sql .= " WHERE is_read = 0";
        }
        $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        // Log error or handle it appropriately
        return [];
    }
}

/**
 * Mark a contact message as read
 * 
 * @param int $id Message ID
 * @return bool True if successful, false otherwise
 */
function markMessageAsRead($id) {
    global $pdo;
    if (!$pdo) {
        return false;
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    } catch(PDOException $e) {
        // Log error or handle it appropriately
        return false;
    }
}

/**
 * Count unread contact messages
 * 
 * @return int Number of unread messages
 */
function countUnreadMessages() {
    global $pdo;
    if (!$pdo) {
        return 0;
    }
    
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE is_read = 0");
        return $stmt->fetchColumn();
    } catch(PDOException $e) {
        // Log error or handle it appropriately
        return 0;
    }
}

/**
 * Debug function to log variables to a file
 * 
 * @param mixed $data Data to log
 * @param string $label Optional label for the data
 * @return void
 */
function debugLog($data, $label = '') {
    $logFile = 'debug.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp]";
    
    if ($label) {
        $logMessage .= " [$label]";
    }
    
    if (is_array($data) || is_object($data)) {
        $logMessage .= " " . print_r($data, true);
    } else {
        $logMessage .= " $data";
    }
    
    file_put_contents($logFile, $logMessage . PHP_EOL, FILE_APPEND);
}

/**
 * Get all roadshows
 * 
 * @param PDO $pdo Database connection
 * @param int $limit Number of roadshows to get
 * @param int $offset Offset for pagination
 * @param bool $active_only Get only active roadshows
 * @return array Roadshows
 */
function getRoadshows($pdo, $limit = 10, $offset = 0, $active_only = true) {
    if (!$pdo) {
        return [];
    }
    
    try {
        $sql = "SELECT * FROM roadshow";
        if ($active_only) {
            $sql .= " WHERE is_active = 1";
        }
        // Updated to explicitly sort by created_at in descending order (newest first)
        $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching roadshows: " . $e->getMessage());
        return [];
    }
}

/**
 * Get roadshow by ID
 *
 * @param PDO $pdo Database connection
 * @param int $id Roadshow ID
 * @return array|null Roadshow data or null if not found
 */
function getRoadshowById($pdo, $id) {
    if (!$pdo) {
        return null;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM roadshow WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $roadshow = $stmt->fetch(PDO::FETCH_ASSOC);
        return $roadshow ?: null;
    } catch (PDOException $e) {
        error_log("Error fetching roadshow by ID: " . $e->getMessage());
        return null;
    }
}

/**
 * Get all talkshows
 * 
 * @param PDO $pdo Database connection
 * @param int $limit Number of talkshows to get
 * @param int $offset Offset for pagination
 * @param bool $active_only Get only active talkshows
 * @return array Talkshows
 */
function getTalkshows($pdo, $limit = 10, $offset = 0, $active_only = true) {
    if (!$pdo) {
        return [];
    }
    
    try {
        $sql = "SELECT * FROM talkshows";
        if ($active_only) {
            $sql .= " WHERE is_active = 1";
        }
        // Sort by created_at in descending order (newest first)
        $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching talkshows: " . $e->getMessage());
        return [];
    }
}

/**
 * Get talkshow by ID
 *
 * @param PDO $pdo Database connection
 * @param int $id Talkshow ID
 * @return array|null Talkshow data or null if not found
 */
function getTalkshowById($pdo, $id) {
    if (!$pdo) {
        return null;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM talkshows WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $talkshow = $stmt->fetch(PDO::FETCH_ASSOC);
        return $talkshow ?: null;
    } catch (PDOException $e) {
        error_log("Error fetching talkshow by ID: " . $e->getMessage());
        return null;
    }
}

/**
 * Get news articles sorted by event date
 * 
 * @param PDO $pdo Database connection
 * @param int $limit Number of news articles to get
 * @param int $offset Offset for pagination
 * @return array News articles
 */
function getNewsByEventDate($pdo, $limit = 10, $offset = 0) {
    if (!$pdo) {
        return [];
    }
    
    try {
        // Order by event_date (nulls last), then by created_at
        $stmt = $pdo->prepare("SELECT * FROM news WHERE is_active = 1 ORDER BY 
            CASE WHEN event_date IS NULL THEN 1 ELSE 0 END, 
            event_date DESC, 
            created_at DESC 
            LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching news by event date: " . $e->getMessage());
        return [];
    }
}

/**
 * Get upcoming events from news table
 * 
 * @param PDO $pdo Database connection
 * @param int $limit Number of events to get
 * @return array Upcoming events
 */
function getUpcomingEvents($pdo, $limit = 5) {
    if (!$pdo) {
        return [];
    }
    
    $today = date('Y-m-d');
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM news 
            WHERE event_date IS NOT NULL 
            AND event_date >= :today 
            AND is_active = 1 
            ORDER BY event_date ASC 
            LIMIT :limit");
        $stmt->bindParam(':today', $today);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching upcoming events: " . $e->getMessage());
        return [];
    }
}

/**
 * Get announcements sorted by event date
 * 
 * @param PDO $pdo Database connection
 * @param int $limit Number of announcements to get
 * @param int $offset Offset for pagination
 * @return array Announcements
 */
function getAnnouncementsByEventDate($pdo, $limit = 10, $offset = 0) {
    if (!$pdo) {
        return [];
    }
    
    try {
        // Order by event_date (nulls last), then by created_at
        $stmt = $pdo->prepare("SELECT * FROM announcements WHERE is_active = 1 ORDER BY 
            CASE WHEN event_date IS NULL THEN 1 ELSE 0 END, 
            event_date DESC, 
            created_at DESC 
            LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching announcements by event date: " . $e->getMessage());
        return [];
    }
}

/**
 * Get upcoming events from announcements table
 * 
 * @param PDO $pdo Database connection
 * @param int $limit Number of events to get
 * @return array Upcoming events
 */
function getUpcomingAnnouncementEvents($pdo, $limit = 5) {
    if (!$pdo) {
        return [];
    }
    
    $today = date('Y-m-d');
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM announcements 
            WHERE event_date IS NOT NULL 
            AND event_date >= :today 
            AND is_active = 1 
            ORDER BY event_date ASC 
            LIMIT :limit");
        $stmt->bindParam(':today', $today);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching upcoming announcement events: " . $e->getMessage());
        return [];
    }
}

/**
 * Get roadshows sorted by event date
 * 
 * @param PDO $pdo Database connection
 * @param int $limit Number of roadshows to get
 * @param int $offset Offset for pagination
 * @return array Roadshows
 */
function getRoadshowsByEventDate($pdo, $limit = 10, $offset = 0) {
    if (!$pdo) {
        return [];
    }
    
    try {
        // Order by event_date (nulls last), then by created_at
        $stmt = $pdo->prepare("SELECT * FROM roadshow WHERE is_active = 1 ORDER BY 
            CASE WHEN event_date IS NULL THEN 1 ELSE 0 END, 
            event_date DESC, 
            created_at DESC 
            LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching roadshows by event date: " . $e->getMessage());
        return [];
    }
}

/**
 * Get talkshows sorted by event date
 * 
 * @param PDO $pdo Database connection
 * @param int $limit Number of talkshows to get
 * @param int $offset Offset for pagination
 * @return array Talkshows
 */
function getTalkshowsByEventDate($pdo, $limit = 10, $offset = 0) {
    if (!$pdo) {
        return [];
    }
    
    try {
        // Order by event_date (nulls last), then by created_at
        $stmt = $pdo->prepare("SELECT * FROM talkshows WHERE is_active = 1 ORDER BY 
            CASE WHEN event_date IS NULL THEN 1 ELSE 0 END, 
            event_date DESC, 
            created_at DESC 
            LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching talkshows by event date: " . $e->getMessage());
        return [];
    }
}

/**
 * Generate video embed code for YouTube or other video URLs.
 *
 * @param string $videoUrl The URL of the video.
 * @return string The HTML embed code.
 */
function getVideoEmbedCode($videoUrl) {
    // Check if it's a YouTube URL
    if (strpos($videoUrl, 'youtube.com/watch?v=') !== false || strpos($videoUrl, 'youtu.be/') !== false) {
        $videoId = '';
        if (strpos($videoUrl, 'youtube.com/watch?v=') !== false) {
            parse_str(parse_url($videoUrl, PHP_URL_QUERY), $params);
            $videoId = $params['v'] ?? '';
        } elseif (strpos($videoUrl, 'youtu.be/') !== false) {
            $videoId = basename(parse_url($videoUrl, PHP_URL_PATH));
        }

        if ($videoId) {
            return '<iframe src="https://www.youtube.com/embed/' . htmlspecialchars($videoId) . '" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen></iframe>';
        }
    }
    
    // Fallback for other video URLs (e.g., direct video files)
    // You might want to add more specific handling for Vimeo, etc.
    return '<video controls class="w-100 h-100"><source src="' . htmlspecialchars($videoUrl) . '" type="video/mp4">Your browser does not support the video tag.</video>';
}
