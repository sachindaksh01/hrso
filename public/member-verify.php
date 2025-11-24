<?php
$pageTitle = 'Verify Member';
require_once '../config/config.php';
require_once '../includes/header.php';

// Get member ID from URL
$memberId = clean($_GET['id'] ?? '');

$member = null;
$error = '';

if ($memberId) {
    // Fetch member details
    $sql = "SELECT m.*, 
            d.designation_name, 
            l.level_name, 
            s.state_name,
            dist.district_name
            FROM members m
            JOIN designations d ON m.designation_id = d.id
            JOIN levels l ON m.level_id = l.id
            LEFT JOIN states s ON m.state_id = s.id
            LEFT JOIN districts dist ON m.district_id = dist.id
            WHERE m.member_id = :id AND m.status = 'approved'";
    
    $member = $db->fetch($sql, ['id' => $memberId]);
    
    if (!$member) {
        $error = 'Member not found or not approved yet.';
    }
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-id-card me-2"></i>
                        Member Verification
                    </h3>
                </div>
                
                <div class="card-body p-4">
                    <?php if (!$memberId): ?>
                        <!-- Search Form -->
                        <div class="text-center mb-4">
                            <p class="text-muted">Enter Member ID or Mobile Number to verify</p>
                        </div>
                        
                        <form method="GET" action="">
                            <div class="input-group input-group-lg mb-3">
                                <input type="text" class="form-control" name="id" 
                                       placeholder="Enter Member ID (e.g., HRSO-NAT-IND-2025-0001)" 
                                       required>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>Verify
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-4">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Only approved members can be verified
                            </small>
                        </div>
                        
                    <?php elseif ($error): ?>
                        <!-- Error Message -->
                        <div class="alert alert-danger text-center">
                            <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                            <h5><?php echo $error; ?></h5>
                            <p class="mb-0">Please check the Member ID and try again</p>
                            <a href="member-verify.php" class="btn btn-primary mt-3">
                                <i class="fas fa-redo me-2"></i>Try Again
                            </a>
                        </div>
                        
                    <?php else: ?>
                        <!-- Member Details -->
                        <div class="alert alert-success text-center mb-4">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <h5 class="mb-0">✓ Verified Member</h5>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 text-center mb-4">
                                <?php if ($member['photo_path'] && file_exists(BASE_PATH . $member['photo_path'])): ?>
                                    <img src="<?php echo UPLOAD_URL . $member['photo_path']; ?>" 
                                         alt="Photo" class="img-fluid rounded shadow" 
                                         style="max-width: 200px;">
                                <?php else: ?>
                                    <div class="rounded shadow mx-auto d-flex align-items-center justify-content-center" 
                                         style="width:200px; height:200px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color:white; font-size:64px; font-weight:bold;">
                                        <?php echo strtoupper(substr($member['full_name'], 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-8">
                                <h4 class="mb-3"><?php echo $member['full_name']; ?></h4>
                                
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="40%" class="text-muted"><strong>Member ID:</strong></td>
                                        <td><span class="badge bg-primary fs-6"><?php echo $member['member_id']; ?></span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><strong>Designation:</strong></td>
                                        <td><?php echo $member['designation_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><strong>Level:</strong></td>
                                        <td><?php echo $member['level_name']; ?></td>
                                    </tr>
                                    <?php if ($member['state_name']): ?>
                                    <tr>
                                        <td class="text-muted"><strong>State:</strong></td>
                                        <td><?php echo $member['state_name']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($member['district_name']): ?>
                                    <tr>
                                        <td class="text-muted"><strong>District:</strong></td>
                                        <td><?php echo $member['district_name']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td class="text-muted"><strong>Mobile:</strong></td>
                                        <td><?php echo $member['mobile']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><strong>Member Since:</strong></td>
                                        <td><?php echo formatDate($member['membership_start_date'] ?? $member['created_at']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><strong>Valid Until:</strong></td>
                                        <td>
                                            <?php if ($member['membership_expiry_date']): ?>
                                                <?php echo formatDate($member['membership_expiry_date']); ?>
                                                <?php if (strtotime($member['membership_expiry_date']) > time()): ?>
                                                    <span class="badge bg-success ms-2">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger ms-2">Expired</span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                N/A
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4 pt-4 border-top">
                            <a href="member-verify.php" class="btn btn-outline-primary">
                                <i class="fas fa-search me-2"></i>Verify Another Member
                            </a>
                            <a href="members.php" class="btn btn-outline-secondary ms-2">
                                <i class="fas fa-list me-2"></i>View All Members
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
