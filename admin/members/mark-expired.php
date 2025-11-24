<?php
require_once '../includes/auth-check.php';

$id = clean($_GET['id'] ?? 0);

if ($id) {
    try {
        $db->update('members', [
            'status' => 'expired'
        ], "id = {$id}");
        
        $_SESSION['success'] = 'Member marked as expired successfully!';
    } catch (Exception $e) {
        $_SESSION['error'] = 'Error: ' . $e->getMessage();
    }
}

redirect(SITE_URL . 'admin/members/all.php');
?>
