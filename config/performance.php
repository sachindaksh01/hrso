<?php
/**
 * Performance Optimization Configuration
 */

// Enable OPcache (add to php.ini)
/*
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
*/

// Output Buffering
if (!ob_get_level()) {
    ob_start('ob_gzhandler'); // Enable GZIP compression
}

// Set cache headers for static files
if (preg_match('/\.(jpg|jpeg|png|gif|css|js|ico|svg|woff|woff2|ttf)$/i', $_SERVER['REQUEST_URI'])) {
    header('Cache-Control: public, max-age=31536000'); // 1 year
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
}

// Preconnect to external resources
function addPreconnect() {
    echo '<link rel="preconnect" href="https://cdn.jsdelivr.net">';
    echo '<link rel="preconnect" href="https://cdnjs.cloudflare.com">';
    echo '<link rel="dns-prefetch" href="https://fonts.googleapis.com">';
}

// Lazy loading for images
function lazyLoadImage($src, $alt = '', $class = '') {
    return '<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" 
            data-src="' . htmlspecialchars($src) . '" 
            alt="' . htmlspecialchars($alt) . '" 
            class="lazy ' . htmlspecialchars($class) . '">';
}

// Minify HTML output
function minifyHTML($buffer) {
    // Remove comments
    $buffer = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $buffer);
    
    // Remove whitespace
    $buffer = preg_replace('/\s+/', ' ', $buffer);
    
    return $buffer;
}

// Enable at the end of script
// ob_start('minifyHTML');
?>
