<?php
$pageTitle = 'Add Member Manually';
require_once '../includes/auth-check.php';
require_once '../includes/header.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $full_name = clean($_POST['full_name'] ?? '');
    $gender = clean($_POST['gender'] ?? '');
    $mobile = clean($_POST['mobile'] ?? '');
    $email = clean($_POST['email'] ?? '');
    $designation_id = clean($_POST['designation_id'] ?? '');
    $level_id = clean($_POST['level_id'] ?? '');
    $state_id = clean($_POST['state_id'] ?? '');
    
    // Basic validation
    if (empty($full_name) || empty($mobile) || empty($designation_id) || empty($level_id)) {
        $errors[] = 'Please fill all required fields';
    }
    
    if (empty($errors)) {
        // Generate Member ID
        require_once '../../core/MemberIDGenerator.php';
        $idGenerator = new MemberIDGenerator($db);
        $member_id = $idGenerator->generate($level_id, $state_id, null);
        
        try {
            $memberId = $db->insert('members', [
                'member_id' => $member_id,
                'full_name' => $full_name,
                'gender' => $gender,
                'mobile' => $mobile,
                'email' => $email,
                'designation_id' => $designation_id,
                'level_id' => $level_id,
                'state_id' => $state_id ?: null,
                'status' => 'approved', // Auto-approve admin-added members
                'payment_status' => 'completed',
                'membership_start_date' => date('Y-m-d'),
                'membership_expiry_date' => date('Y-m-d', strtotime('+1 year')),
                'approved_by' => $_SESSION['admin_id'],
                'approved_at' => date('Y-m-d H:i:s'),
                'father_husband_name' => 'Not Provided',
                'date_of_birth' => '2000-01-01',
                'govt_id_type' => 'Aadhar',
                'govt_id_number' => 'XXXXXXXXXXXX',
                'address_line1' => 'Not Provided',
                'pincode' => '000000',
                'payment_amount' => 0,
                'validity_years' => 1
            ]);
            
            $_SESSION['success'] = "Member added successfully! Member ID: {$member_id}";
            redirect(SITE_URL . 'admin/members/view.php?id=' . $memberId);
            
        } catch (Exception $e) {
            $errors[] = 'Error: ' . $e->getMessage();
        }
    }
}

// Get dropdowns
$levels = $db->fetchAll("SELECT * FROM levels WHERE is_active = 1 ORDER BY priority");
$states = $db->fetchAll("SELECT * FROM states WHERE is_active = 1 ORDER BY state_name");
$designations = $db->fetchAll("SELECT * FROM designations WHERE is_active = 1 ORDER BY priority");
?>

<div class="page-header">
    <h1><i class="fas fa-user-plus me-2"></i>Add Member Manually</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="index.php">Members</a></li>
            <li class="breadcrumb-item active">Add</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Basic Information</h5>
            </div>
            
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" class="form-control" name="full_name" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Gender *</label>
                            <select class="form-select" name="gender" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mobile *</label>
                            <input type="tel" class="form-control" name="mobile" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Level *</label>
                            <select class="form-select" name="level_id" required>
                                <option value="">Select</option>
                                <?php foreach ($levels as $level): ?>
                                    <option value="<?php echo $level['id']; ?>">
                                        <?php echo $level['level_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Designation *</label>
                            <select class="form-select" name="designation_id" required>
                                <option value="">Select</option>
                                <?php foreach ($designations as $designation): ?>
                                    <option value="<?php echo $designation['id']; ?>">
                                        <?php echo $designation['designation_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">State</label>
                            <select class="form-select" name="state_id">
                                <option value="">Select</option>
                                <?php foreach ($states as $state): ?>
                                    <option value="<?php echo $state['id']; ?>">
                                        <?php echo $state['state_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> Member ID will be auto-generated. Status will be set to "Approved" automatically.
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Add Member
                        </button>
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Information
                </h5>
            </div>
            <div class="card-body">
                <p class="small mb-2"><strong>Quick Add Feature:</strong></p>
                <ul class="small">
                    <li>Only basic details required</li>
                    <li>Member ID auto-generated</li>
                    <li>Status: Auto-approved</li>
                    <li>Validity: 1 year from today</li>
                    <li>Payment: Marked as completed</li>
                </ul>
                
                <hr>
                
                <p class="small text-muted mb-0">
                    For complete member registration with documents, use the public registration form.
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
