<?php
$pageTitle = 'Renew Membership';
require_once '../config/config.php';
require_once '../includes/header.php';

if (!ALLOW_SELF_RENEWAL) {
    die('Self-renewal is currently disabled. Please contact admin.');
}

$member_id = clean($_GET['id'] ?? '');
$step = $_GET['step'] ?? 'verify';

$member = null;
$error = '';

// Step 1: Verify Member
if ($step === 'verify' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = clean($_POST['member_id']);
    $mobile = clean($_POST['mobile']);
    
    $member = $db->fetch(
        "SELECT m.*, d.designation_name, l.level_name, mp.donation_amount
         FROM members m
         JOIN designations d ON m.designation_id = d.id
         JOIN levels l ON m.level_id = l.id
         JOIN membership_plans mp ON m.membership_plan_id = mp.id
         WHERE m.member_id = :id AND m.mobile = :mobile AND m.status = 'approved'",
        ['id' => $member_id, 'mobile' => $mobile]
    );
    
    if ($member) {
        $step = 'confirm';
    } else {
        $error = 'Invalid Member ID or Mobile Number';
    }
} elseif ($member_id) {
    $member = $db->fetch(
        "SELECT m.*, d.designation_name, mp.donation_amount
         FROM members m
         JOIN designations d ON m.designation_id = d.id
         JOIN membership_plans mp ON m.membership_plan_id = mp.id
         WHERE m.member_id = :id",
        ['id' => $member_id]
    );
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-sync-alt me-2"></i>
                        Renew Membership
                    </h3>
                </div>
                
                <div class="card-body p-4">
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($step === 'verify'): ?>
                        <!-- Verification Form -->
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">Member ID *</label>
                                <input type="text" class="form-control" name="member_id" 
                                       placeholder="e.g., HRSO-NAT-IND-2025-0001" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Registered Mobile *</label>
                                <input type="tel" class="form-control" name="mobile" 
                                       placeholder="10-digit mobile" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-search me-2"></i>Verify & Continue
                            </button>
                        </form>
                        
                    <?php elseif ($step === 'confirm' && $member): ?>
                        <!-- Confirmation -->
                        <div class="text-center mb-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h5>Member Verified!</h5>
                        </div>
                        
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
                                <td><?php echo formatDate($member['membership_expiry_date']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Renewal Amount:</strong></td>
                                <td class="h5 text-success">₹<?php echo number_format($member['donation_amount'], 2); ?></td>
                            </tr>
                        </table>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Your membership will be extended for 1 year from current expiry date.
                        </div>
                        
                        <form method="POST" action="process-renewal.php">
                            <input type="hidden" name="member_id" value="<?php echo $member['id']; ?>">
                            <button type="submit" class="btn btn-success btn-lg w-100">
                                <i class="fas fa-credit-card me-2"></i>Proceed to Payment
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
