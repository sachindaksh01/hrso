<?php
require_once '../includes/auth-check.php';

$auth->requirePermission('manage_designations');

$id = clean($_GET['id'] ?? 0);

if ($id) {
    try {
        // Get current status
        $current = $db->fetch("SELECT is_active FROM designations WHERE id = :id", ['id' => $id]);
        
        if ($current) {
            // Toggle status
            $newStatus = $current['is_active'] ? 0 : 1;
            $db->update('designations', ['is_active' => $newStatus], "id = {$id}");
            
            $_SESSION['success'] = 'Status updated successfully!';
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'Error: ' . $e->getMessage();
    }
}

redirect(SITE_URL . 'admin/designations/index.php');
?>
