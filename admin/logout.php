<?php
require_once '../config/config.php';

// Logout user
$auth->logout();

// Redirect to login page
redirect(SITE_URL . 'admin/login.php');
?>
