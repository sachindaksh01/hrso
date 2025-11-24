<?php
/**
 * Application Constants
 * Define any custom constants here
 */

// ====================================
// DESIGNATION TYPES
// ====================================
define('DESIGNATION_PRESIDENT', 1);
define('DESIGNATION_VICE_PRESIDENT', 2);
define('DESIGNATION_GENERAL_SECRETARY', 3);
define('DESIGNATION_SECRETARY', 4);
define('DESIGNATION_TREASURER', 6);
define('DESIGNATION_MEMBER', 15);

// ====================================
// LEVEL TYPES
// ====================================
define('LEVEL_NATIONAL', 1);
define('LEVEL_STATE', 2);
define('LEVEL_DISTRICT', 3);
define('LEVEL_DIVISION', 4);
define('LEVEL_CITY', 5);

// ====================================
// ADMIN ROLES
// ====================================
define('ROLE_SUPER_ADMIN', 'super_admin');
define('ROLE_NATIONAL_ADMIN', 'national_admin');
define('ROLE_STATE_ADMIN', 'state_admin');
define('ROLE_DISTRICT_ADMIN', 'district_admin');

// ====================================
// FILE UPLOAD DIRECTORIES
// ====================================
define('UPLOAD_DIR_PHOTOS', 'uploads/members/photos/');
define('UPLOAD_DIR_SIGNATURES', 'uploads/members/signatures/');
define('UPLOAD_DIR_ID_PROOFS', 'uploads/members/id_proofs/');
define('UPLOAD_DIR_GALLERY', 'uploads/gallery/');

// ====================================
// VALIDATION PATTERNS (Regex)
// ====================================
define('REGEX_MOBILE', '/^[6-9]\d{9}$/');
define('REGEX_EMAIL', '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/');
define('REGEX_AADHAR', '/^\d{12}$/');
define('REGEX_PAN', '/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/');
define('REGEX_PINCODE', '/^\d{6}$/');

// ====================================
// NOTIFICATION MESSAGES
// ====================================
define('MSG_SUCCESS', 'Operation completed successfully!');
define('MSG_ERROR', 'An error occurred. Please try again.');
define('MSG_INVALID_INPUT', 'Please provide valid input.');
define('MSG_LOGIN_SUCCESS', 'Login successful! Welcome back.');
define('MSG_LOGIN_FAILED', 'Invalid username or password.');
define('MSG_LOGOUT_SUCCESS', 'You have been logged out successfully.');
define('MSG_ACCESS_DENIED', 'Access denied. You do not have permission.');

// ====================================
// INDIAN STATES (for quick reference)
// ====================================
define('INDIAN_STATES', serialize([
    'AP' => 'Andhra Pradesh',
    'AR' => 'Arunachal Pradesh',
    'AS' => 'Assam',
    'BR' => 'Bihar',
    'CG' => 'Chhattisgarh',
    'GA' => 'Goa',
    'GJ' => 'Gujarat',
    'HR' => 'Haryana',
    'HP' => 'Himachal Pradesh',
    'JH' => 'Jharkhand',
    'KA' => 'Karnataka',
    'KL' => 'Kerala',
    'MP' => 'Madhya Pradesh',
    'MH' => 'Maharashtra',
    'MN' => 'Manipur',
    'ML' => 'Meghalaya',
    'MZ' => 'Mizoram',
    'NL' => 'Nagaland',
    'OD' => 'Odisha',
    'PB' => 'Punjab',
    'RJ' => 'Rajasthan',
    'SK' => 'Sikkim',
    'TN' => 'Tamil Nadu',
    'TS' => 'Telangana',
    'TR' => 'Tripura',
    'UP' => 'Uttar Pradesh',
    'UK' => 'Uttarakhand',
    'WB' => 'West Bengal',
    'DL' => 'Delhi',
    'JK' => 'Jammu and Kashmir',
    'LA' => 'Ladakh'
]));

// ====================================
// META INFORMATION
// ====================================
define('APP_VERSION', '1.0.0');
define('APP_AUTHOR', 'HRSO Development Team');
define('APP_YEAR', date('Y'));

?>
