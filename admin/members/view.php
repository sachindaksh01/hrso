<?php
$pageTitle = 'View Member';
require_once '../includes/auth-check.php';
require_once '../includes/header.php';

$id = clean($_GET['id'] ?? 0);

// Get member details
$sql = "SELECT m.*, 
        d.designation_name, 
        l.level_name, 
        s.state_name,
        dist.district_name,
        mp.donation_amount,
        approved_admin.full_name as approved_by_name
        FROM members m
        JOIN designations d ON m.designation_id = d.id
        JOIN levels l ON m.level_id = l.id
        LEFT JOIN states s ON m.state_id = s.id
        LEFT JOIN districts dist ON m.district_id = dist.id
        LEFT JOIN membership_plans mp ON m.membership_plan_id = mp.id
        LEFT JOIN admin_users approved_admin ON m.approved_by = approved_admin.id
        WHERE m.id = :id";

$member = $db->fetch($sql, ['id' => $id]);

if (!$member) {
    redirect(SITE_URL . 'admin/members/pending.php');
}
?>

<div class="page-header">
    <h1><i class="fas fa-user me-2"></i>Member Details</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="pending.php">Members</a></li>
            <li class="breadcrumb-item active">View</li>
        </ol>
    </nav>
</div>

<div class="row">
    <!-- Member Info -->
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Personal Information</h5>
                <div>
                    <?php echo getStatusBadge($member['status']); ?>
                    <?php if ($member['status'] === 'pending'): ?>
                        <a href="approve.php?id=<?php echo $member['id']; ?>" 
                           class="btn btn-success btn-sm ms-2"
                           onclick="return confirm('Approve this member?')">
                            <i class="fas fa-check me-1"></i>Approve
                        </a>
                        <a href="reject.php?id=<?php echo $member['id']; ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Reject this member?')">
                            <i class="fas fa-times me-1"></i>Reject
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Full Name:</strong>
                        <p><?php echo $member['full_name']; ?></p>
                    </div>
                    <div class="col-md-3">
                        <strong>Gender:</strong>
                        <p><?php echo $member['gender']; ?></p>
                    </div>
                    <div class="col-md-3">
                        <strong>Date of Birth:</strong>
                        <p><?php echo formatDate($member['date_of_birth']); ?></p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong><?php echo $member['relation_type']; ?> Name:</strong>
                        <p><?php echo $member['father_husband_name']; ?></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Mobile:</strong>
                        <p><?php echo $member['mobile']; ?></p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Email:</strong>
                        <p><?php echo $member['email'] ?: 'Not provided'; ?></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Member ID:</strong>
                        <p><span class="badge bg-primary fs-6"><?php echo $member['member_id'] ?? 'Pending'; ?></span></p>
                    </div>
                </div>
                
                <hr>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Govt ID Type:</strong>
                        <p><?php echo $member['govt_id_type']; ?></p>
                    </div>
                    <div class="col-md-8">
                        <strong>Govt ID Number:</strong>
                        <p><?php echo $member['govt_id_number']; ?></p>
                    </div>
                </div>
                
                <hr>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Designation:</strong>
                        <p><?php echo $member['designation_name']; ?></p>
                    </div>
                    <div class="col-md-4">
                        <strong>Level:</strong>
                        <p><?php echo $member['level_name']; ?></p>
                    </div>
                    <div class="col-md-4">
                        <strong>State:</strong>
                        <p><?php echo $member['state_name'] ?? 'N/A'; ?></p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-12">
                        <strong>Address:</strong>
                        <p>
                            <?php echo $member['address_line1']; ?>
                            <?php if ($member['city']): ?>, <?php echo $member['city']; ?><?php endif; ?>
                            <?php if ($member['state_name']): ?>, <?php echo $member['state_name']; ?><?php endif; ?>
                            - <?php echo $member['pincode']; ?>
                        </p>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-4">
                        <strong>Membership Amount:</strong>
                        <p class="h5 text-success">₹<?php echo number_format($member['payment_amount'], 2); ?></p>
                    </div>
                    <div class="col-md-4">
                        <strong>Validity:</strong>
                        <p><?php echo $member['validity_years']; ?> Year(s)</p>
                    </div>
                    <div class="col-md-4">
                        <strong>Payment Status:</strong>
                        <p><?php echo getStatusBadge($member['payment_status']); ?></p>
                    </div>
                </div>
                
                <?php if ($member['approved_by_name']): ?>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Approved By:</strong>
                            <p><?php echo $member['approved_by_name']; ?></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Approved On:</strong>
                            <p><?php echo formatDate($member['approved_at'], 'd M Y, h:i A'); ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Documents -->
    <div class="col-lg-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Photo</h5>
            </div>
            <div class="card-body text-center">
                <?php if ($member['photo_path'] && file_exists(BASE_PATH . $member['photo_path'])): ?>
                    <img src="<?php echo UPLOAD_URL . $member['photo_path']; ?>" 
                         alt="Photo" class="img-fluid rounded shadow" style="max-width: 250px;">
                <?php else: ?>
                    <div class="text-muted">No photo uploaded</div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Signature</h5>
            </div>
            <div class="card-body text-center">
                <?php if ($member['signature_path'] && file_exists(BASE_PATH . $member['signature_path'])): ?>
                    <img src="<?php echo UPLOAD_URL . $member['signature_path']; ?>" 
                         alt="Signature" class="img-fluid rounded shadow" style="max-width: 200px;">
                <?php else: ?>
                    <div class="text-muted">No signature uploaded</div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">ID Proof</h5>
            </div>
            <div class="card-body text-center">
                <?php if ($member['id_proof_path'] && file_exists(BASE_PATH . $member['id_proof_path'])): ?>
                    <a href="<?php echo UPLOAD_URL . $member['id_proof_path']; ?>" 
                       target="_blank" class="btn btn-primary">
                        <i class="fas fa-file-download me-2"></i>View ID Proof
                    </a>
                <?php else: ?>
                    <div class="text-muted">No ID proof uploaded</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

<?php if ($member['status'] === 'approved'): ?>
    <div class="mt-4">
        <h6>Generate Documents:</h6>
        <div class="btn-group">
            <a href="../documents/generate-id-card.php?id=<?php echo $member['id']; ?>" 
               class="btn btn-primary" target="_blank">
                <i class="fas fa-id-card me-2"></i>Download ID Card
            </a>
            <a href="../documents/generate-oath.php?id=<?php echo $member['id']; ?>" 
               class="btn btn-success" target="_blank">
                <i class="fas fa-certificate me-2"></i>Download Oath Letter
            </a>
        </div>
    </div>
<?php endif; ?>

