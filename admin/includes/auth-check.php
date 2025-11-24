<?php
/**
 * Authentication Check
 * Include this file at the top of every admin page
 */

if (!defined('BASE_PATH')) {
    require_once dirname(__DIR__, 2) . '/config/config.php';
}

// Check if user is logged in
$auth->requireLogin();

// Get current admin details
$currentAdmin = $auth->getCurrentAdmin();
?>
