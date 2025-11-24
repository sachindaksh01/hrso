<?php
/**
 * Database Configuration with Auto-Setup
 * Your Database Credentials
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'hrso_membership');
define('DB_USER', 'root');      // Your username
define('DB_PASS', 'Sachin123@');        // Your password
define('DB_CHARSET', 'utf8mb4');

// Auto-create database and tables (Set to false after first setup)
define('AUTO_CREATE_DATABASE', true);
define('AUTO_CREATE_TABLES', true);

// PDO Options
define('PDO_OPTIONS', serialize([
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
]));

/**
 * SECURITY NOTES:
 * 
 * 1. After first successful run, set:
 *    - AUTO_CREATE_DATABASE = false
 *    - AUTO_CREATE_TABLES = false
 * 
 * 2. NEVER commit this file to GitHub/public repo
 *    - Add to .gitignore: config/database.php
 * 
 * 3. For production server:
 *    - Use different stronger password
 *    - Enable SSL connection if available
 * 
 * 4. File permissions (Linux/Production):
 *    - chmod 600 config/database.php (read/write owner only)
 */
?>
