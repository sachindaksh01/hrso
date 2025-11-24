<?php
/**
 * HRSO Membership System
 * Main Configuration File
 * 
 * This file contains all general settings for the application
 */

// Prevent direct access
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__) . '/');
}

// ====================================
// ENVIRONMENT SETTINGS
// ====================================
// Options: 'development' or 'production'
define('ENVIRONMENT', 'development');

// ====================================
// SITE SETTINGS
// ====================================
define('SITE_NAME', 'HRSO India');
define('SITE_TAGLINE', 'Empowering Communities Across India');
define('SITE_URL', 'http://localhost/hrso/');
define('SITE_EMAIL', 'info@hrso.org');
define('ADMIN_EMAIL', 'admin@hrso.org');
define('SUPPORT_EMAIL', 'support@hrso.org');
define('CONTACT_PHONE', '+91-1234567890');

// ====================================
// PATHS (Server side - file system)
// ====================================
define('UPLOAD_PATH', BASE_PATH . 'uploads/');
define('MEMBER_PHOTO_PATH', UPLOAD_PATH . 'members/photos/');
define('MEMBER_SIGNATURE_PATH', UPLOAD_PATH . 'members/signatures/');
define('MEMBER_ID_PROOF_PATH', UPLOAD_PATH . 'members/id_proofs/');
define('GALLERY_PATH', UPLOAD_PATH . 'gallery/');
define('DOCUMENT_PATH', UPLOAD_PATH . 'documents/');
define('ID_CARD_PATH', DOCUMENT_PATH . 'id_cards/');
define('OATH_LETTER_PATH', DOCUMENT_PATH . 'oath_letters/');
define('LOG_PATH', BASE_PATH . 'logs/');

// ====================================
// URLs (Client side - browser access)
// ====================================
define('ASSETS_URL', SITE_URL . 'assets/');
define('CSS_URL', ASSETS_URL . 'css/');
define('JS_URL', ASSETS_URL . 'js/');
define('IMAGE_URL', ASSETS_URL . 'images/');
define('UPLOAD_URL', SITE_URL . 'uploads/');

// ====================================
// FILE UPLOAD SETTINGS
// ====================================
// Maximum file sizes (in bytes)
define('MAX_PHOTO_SIZE', 2 * 1024 * 1024);        // 2MB
define('MAX_SIGNATURE_SIZE', 1 * 1024 * 1024);    // 1MB
define('MAX_ID_PROOF_SIZE', 5 * 1024 * 1024);     // 5MB
define('MAX_DOCUMENT_SIZE', 10 * 1024 * 1024);    // 10MB

// Allowed file types
define('ALLOWED_IMAGE_TYPES', serialize(['jpg', 'jpeg', 'png']));
define('ALLOWED_DOCUMENT_TYPES', serialize(['jpg', 'jpeg', 'png', 'pdf']));

// Image dimensions for member photo
define('MEMBER_PHOTO_WIDTH', 300);
define('MEMBER_PHOTO_HEIGHT', 400);

// ====================================
// PAGINATION SETTINGS
// ====================================
define('RECORDS_PER_PAGE', 25);
define('ADMIN_RECORDS_PER_PAGE', 50);
define('SEARCH_RESULTS_PER_PAGE', 20);

// ====================================
// MEMBERSHIP SETTINGS
// ====================================
define('MEMBER_ID_PREFIX', 'HRSO');
define('DEFAULT_MEMBERSHIP_VALIDITY', 1); // Years
define('MEMBERSHIP_EXPIRY_ALERT_DAYS', 30); // Alert 30 days before expiry
define('ALLOW_SELF_RENEWAL', true);

// ====================================
// PAYMENT SETTINGS
// ====================================
define('PAYMENT_GATEWAY', 'razorpay'); // Options: razorpay, ccavenue, paytm, manual
define('PAYMENT_CURRENCY', 'INR');
define('PAYMENT_TEST_MODE', true); // Set to false in production

// Razorpay (fill these from your Razorpay dashboard)
define('RAZORPAY_KEY_ID', ''); // Your Razorpay Key ID
define('RAZORPAY_SECRET', ''); // Your Razorpay Secret

// ====================================
// BANK DETAILS (For Manual Donations)
// ====================================
define('BANK_NAME', 'Punjab National Bank');
define('BANK_ACCOUNT_NAME', 'HRSO India');
define('BANK_ACCOUNT_NUMBER', '7983002100001067');
define('BANK_IFSC', 'PUNB0798300');
define('BANK_BRANCH', 'Main Branch');

// ====================================
// EMAIL SETTINGS (SMTP)
// ====================================
define('SMTP_ENABLED', false); // Set to true to enable email
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-app-password');
define('SMTP_ENCRYPTION', 'tls'); // tls or ssl
define('SMTP_FROM_NAME', SITE_NAME);
define('SMTP_FROM_EMAIL', SITE_EMAIL);

// ====================================
// SMS SETTINGS (Optional - for notifications)
// ====================================
define('SMS_ENABLED', false);
define('SMS_PROVIDER', 'msg91'); // msg91, twilio, etc.
define('SMS_API_KEY', '');
define('SMS_SENDER_ID', 'HRSOIN');

// ====================================
// SECURITY SETTINGS
// ====================================
// Session timeout (in seconds)
define('SESSION_TIMEOUT', 3600); // 1 hour = 3600 seconds

// Password requirements
define('MIN_PASSWORD_LENGTH', 8);
define('REQUIRE_PASSWORD_UPPERCASE', true);
define('REQUIRE_PASSWORD_LOWERCASE', true);
define('REQUIRE_PASSWORD_NUMBER', true);
define('REQUIRE_PASSWORD_SPECIAL', true);

// CAPTCHA settings
define('CAPTCHA_ENABLED', false); // Enable for production
define('RECAPTCHA_SITE_KEY', '');
define('RECAPTCHA_SECRET_KEY', '');

// Maximum login attempts before blocking
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_BLOCK_TIME', 900); // 15 minutes in seconds

// ====================================
// DATE & TIME SETTINGS
// ====================================
date_default_timezone_set('Asia/Kolkata');
define('DATE_FORMAT', 'd-m-Y');
define('TIME_FORMAT', 'h:i A');
define('DATETIME_FORMAT', 'd-m-Y h:i A');

// ====================================
// ERROR REPORTING
// ====================================
if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', LOG_PATH . 'error.log');
}

// ====================================
// SESSION CONFIGURATION
// ====================================
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS
ini_set('session.cookie_samesite', 'Lax');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ====================================
// AUTO-LOAD REQUIRED FILES
// ====================================
// Load database configuration
require_once __DIR__ . '/database.php';

// Load helper functions
require_once BASE_PATH . 'includes/functions.php';

// Load core classes
spl_autoload_register(function($class) {
    $coreFile = BASE_PATH . 'core/' . $class . '.php';
    $modelFile = BASE_PATH . 'models/' . $class . '.php';
    $controllerFile = BASE_PATH . 'controllers/' . $class . '.php';
    
    if (file_exists($coreFile)) {
        require_once $coreFile;
    } elseif (file_exists($modelFile)) {
        require_once $modelFile;
    } elseif (file_exists($controllerFile)) {
        require_once $controllerFile;
    }
});

// ====================================
// GLOBAL OBJECTS
// ====================================
// Initialize database connection
$db = new Database();

// Initialize authentication
$auth = new Auth($db);

// ====================================
// APPLICATION CONSTANTS
// ====================================
// Member status
define('STATUS_PENDING', 'pending');
define('STATUS_APPROVED', 'approved');
define('STATUS_REJECTED', 'rejected');
define('STATUS_EXPIRED', 'expired');

// Payment status
define('PAYMENT_PENDING', 'pending');
define('PAYMENT_COMPLETED', 'completed');
define('PAYMENT_FAILED', 'failed');

// Gender options
define('GENDER_MALE', 'Male');
define('GENDER_FEMALE', 'Female');
define('GENDER_OTHER', 'Other');

// Govt ID types
define('ID_AADHAR', 'Aadhar');
define('ID_PAN', 'PAN');
define('ID_DRIVING_LICENSE', 'Driving License');
define('ID_PASSPORT', 'Passport');
define('ID_VOTER_ID', 'Voter ID');

// ====================================
// DEBUG HELPER (Development only)
// ====================================
if (ENVIRONMENT === 'development') {
    function dd($var) {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
        die();
    }
    
    function pr($var) {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }
}


// Set security headers
Security::setSecurityHeaders();

// Regenerate session ID periodically (prevent session fixation)
if (!isset($_SESSION['last_regeneration'])) {
    $_SESSION['last_regeneration'] = time();
} elseif (time() - $_SESSION['last_regeneration'] > 1800) { // 30 minutes
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}



// ====================================
// END OF CONFIG
// ====================================
?>
