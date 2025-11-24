<?php
/**
 * Common Helper Functions
 */

// Sanitize input
function clean($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

// Flash message
function setFlash($type, $message) {
    $_SESSION['flash_type'] = $type; // success, error, warning, info
    $_SESSION['flash_message'] = $message;
}

function getFlash() {
    if (isset($_SESSION['flash_message'])) {
        $flash = [
            'type' => $_SESSION['flash_type'],
            'message' => $_SESSION['flash_message']
        ];
        unset($_SESSION['flash_type'], $_SESSION['flash_message']);
        return $flash;
    }
    return null;
}

// Redirect
function redirect($url) {
    header("Location: " . $url);
    exit;
}

// Format date
function formatDate($date, $format = 'd M Y') {
    return date($format, strtotime($date));
}

// Calculate age
function calculateAge($dob) {
    return date_diff(date_create($dob), date_create('now'))->y;
}

// Format currency
function formatCurrency($amount) {
    return '₹ ' . number_format($amount, 2);
}

// Time ago
function timeAgo($datetime) {
    $timestamp = strtotime($datetime);
    $difference = time() - $timestamp;
    $periods = ["second", "minute", "hour", "day", "week", "month", "year"];
    $lengths = [60, 60, 24, 7, 4.35, 12];
    
    for ($i = 0; $difference >= $lengths[$i] && $i < count($lengths) - 1; $i++) {
        $difference /= $lengths[$i];
    }
    
    $difference = round($difference);
    if ($difference != 1) {
        $periods[$i] .= "s";
    }
    
    return "$difference $periods[$i] ago";
}

// Generate random string
function generateRandomString($length = 10) {
    return bin2hex(random_bytes($length / 2));
}

// Check file type
function isValidFileType($file, $allowed_types) {
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    return in_array($ext, $allowed_types);
}

// Upload file
function uploadFile($file, $destination, $allowed_types, $max_size = MAX_FILE_SIZE) {
    // Check file uploaded
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'File upload error'];
    }
    
    // Check file size
    if ($file['size'] > $max_size) {
        return ['success' => false, 'error' => 'File too large (max ' . ($max_size / 1024 / 1024) . 'MB)'];
    }
    
    // Check file type
    if (!isValidFileType($file, $allowed_types)) {
        return ['success' => false, 'error' => 'Invalid file type. Allowed: ' . implode(', ', $allowed_types)];
    }
    
    // Generate unique filename
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = uniqid() . '_' . time() . '.' . $ext;
    $filepath = $destination . $filename;
    
    // Move file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => $filename, 'path' => $filepath];
    }
    
    return ['success' => false, 'error' => 'Failed to move uploaded file'];
}

// Get status badge HTML
function getStatusBadge($status) {
    $badges = [
        'pending' => '<span class="badge bg-warning">Pending</span>',
        'approved' => '<span class="badge bg-success">Approved</span>',
        'rejected' => '<span class="badge bg-danger">Rejected</span>',
        'expired' => '<span class="badge bg-secondary">Expired</span>',
        'completed' => '<span class="badge bg-success">Completed</span>',
        'failed' => '<span class="badge bg-danger">Failed</span>',
    ];
    
    return $badges[$status] ?? '<span class="badge bg-secondary">' . ucfirst($status) . '</span>';
}
?>
