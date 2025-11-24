<?php
$pageTitle = 'Renew Membership';
require_once '../includes/auth-check.php';
require_once '../includes/header.php';

$id = clean($_GET['id'] ?? 0);

$member = $db->fetch("SELECT * FROM members WHERE id = :id", ['id' => $id]);

if (!$member) {
    $_SESSION['error'] = 'Member not found';
    redirect(SITE_URL . 'admin/members/all.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $years = clean($_POST['years'] ?? 1);
    
    // Calculate new expiry date
    $currentExpiry = $member['membership_expiry_date'];
    $newExpiry = date('Y-m-d', strtotime($currentExpiry . " +{$years} year"));
    
    try {
        $db->update('members', [
            'membership_expiry_date' => $newExpiry,
            'status' => 'approved',
            'updated_at' => date('Y-m-d H:i:s')
        ], "id = {$id}");
        
        $_SESSION['success'] = "Membership renewed successfully! New expiry: {$newExpiry}";
        redirect(SITE_URL . 'admin/members/view.php?id=' . $id);
        
    } catch (Exception $e) {
        $_SESSION['error'] = 'Error: ' . $e->getMessage();
    }
}
?>

<div class="page-header">
    <h1><i class="fas fa-sync-alt me-2"></i>Renew Membership</h1>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Member Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>Name:</strong></td>
                        <td><?php echo $member['full_name']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Member ID:</strong></td>
                        <td><?php echo $member['member_id']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Current Expiry:</strong></td>
                        <td>
                            <?php echo formatDate($member['membership_expiry_date']); ?>
                            <?php if (strtotime($member['membership_expiry_date']) < time()): ?>
                                <span class="badge bg-danger ms-2">Expired</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
                
                <hr>
                
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Renewal Period</label>
                        <select class="form-select" name="years" required>
                            <option value="1">1 Year</option>
                            <option value="2">2 Years</option>
                            <option value="3">3 Years</option>
                            <option value="5">5 Years</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-sync-alt me-2"></i>Renew Membership
                    </button>
                    <a href="all.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
