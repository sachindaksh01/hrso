<?php
require_once '../includes/auth-check.php';

$id = clean($_GET['id'] ?? 0);

if ($id) {
    try {
        // Get member details
        $member = $db->fetch("SELECT * FROM members WHERE id = :id", ['id' => $id]);
        
        if ($member && $member['status'] === 'pending') {
            $db->beginTransaction();
            
            // Update member status
            $today = date('Y-m-d');
            $expiryDate = date('Y-m-d', strtotime("+{$member['validity_years']} year"));
            
            $db->update('members', [
                'status' => 'approved',
                'payment_status' => 'completed',
                'membership_start_date' => $today,
                'membership_expiry_date' => $expiryDate,
                'approved_by' => $_SESSION['admin_id'],
                'approved_at' => date('Y-m-d H:i:s')
            ], "id = {$id}");
            
            // Log audit
            $db->insert('audit_logs', [
                'admin_id' => $_SESSION['admin_id'],
                'table_name' => 'members',
                'record_id' => $id,
                'action_type' => 'approve',
                'new_data' => json_encode(['status' => 'approved']),
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT']
            ]);
            
            $db->commit();
            
            $_SESSION['success'] = 'Member approved successfully!';
        }
    } catch (Exception $e) {
        $db->rollback();
        $_SESSION['error'] = 'Error: ' . $e->getMessage();
    }
}

redirect(SITE_URL . 'admin/members/pending.php');
?>
