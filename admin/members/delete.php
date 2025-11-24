<?php
require_once '../includes/auth-check.php';

$id = clean($_GET['id'] ?? 0);

if ($id) {
    try {
        // Get member details first
        $member = $db->fetch("SELECT * FROM members WHERE id = :id", ['id' => $id]);
        
        if ($member) {
            // Delete associated files
            if ($member['photo_path'] && file_exists(BASE_PATH . $member['photo_path'])) {
                @unlink(BASE_PATH . $member['photo_path']);
            }
            if ($member['signature_path'] && file_exists(BASE_PATH . $member['signature_path'])) {
                @unlink(BASE_PATH . $member['signature_path']);
            }
            if ($member['id_proof_path'] && file_exists(BASE_PATH . $member['id_proof_path'])) {
                @unlink(BASE_PATH . $member['id_proof_path']);
            }
            
            // Delete from database
            $db->delete('members', 'id = :id', ['id' => $id]);
            
            $_SESSION['success'] = 'Member deleted successfully!';
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'Error: ' . $e->getMessage();
    }
}

redirect(SITE_URL . 'admin/members/all.php');
?>
