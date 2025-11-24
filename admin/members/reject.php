<?php
require_once '../includes/auth-check.php';

$id = clean($_GET['id'] ?? 0);

if ($id) {
    try {
        $member = $db->fetch("SELECT * FROM members WHERE id = :id", ['id' => $id]);
        
        if ($member && $member['status'] === 'pending') {
            // Update status to rejected
            $db->update('members', [
                'status' => 'rejected',
                'rejection_reason' => 'Rejected by admin',
                'approved_by' => $_SESSION['admin_id'],
                'approved_at' => date('Y-m-d H:i:s')
            ], "id = {$id}");
            
            // Log audit
            $db->insert('audit_logs', [
                'admin_id' => $_SESSION['admin_id'],
                'table_name' => 'members',
                'record_id' => $id,
                'action_type' => 'reject',
                'new_data' => json_encode(['status' => 'rejected']),
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT']
            ]);
            
            $_SESSION['success'] = 'Member rejected.';
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'Error: ' . $e->getMessage();
    }
}

redirect(SITE_URL . 'admin/members/pending.php');
?>
