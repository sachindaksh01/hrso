<?php
/**
 * HRSO Membership System
 * Application Entry Point
 */

// Define base path
define('BASE_PATH', __DIR__ . '/');

// Load configuration
require_once BASE_PATH . 'config/config.php';

// Redirect to public homepage
header('Location: ' . SITE_URL . 'public/index.php');
exit;
?>
