<?php
require_once '../includes/auth-check.php';

$auth->requirePermission('manage_designations');

$id = clean($_GET['id'] ?? 0);

if ($id) {
    // Check if any members have this designation
    $memberCount = $db->fetch(
        "SELECT COUNT(*) as count FROM members WHERE designation_id = :id",
        ['id' => $id]
    )['count'];
    
    if ($memberCount > 0) {
        $_SESSION['error'] = "Cannot delete! {$memberCount} member(s) have this designation.";
    } else {
        try {
            $db->delete('designations', 'id = :id', ['id' => $id]);
            $_SESSION['success'] = 'Designation deleted successfully!';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error deleting designation: ' . $e->getMessage();
        }
    }
}

redirect(SITE_URL . 'admin/designations/index.php');
?>
