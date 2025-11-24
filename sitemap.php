<?php
require_once 'config/config.php';
require_once 'includes/seo.php';

header('Content-Type: application/xml; charset=utf-8');

echo SEO::generateSitemap($db);
?>
