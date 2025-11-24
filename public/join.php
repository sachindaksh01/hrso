<?php
$pageTitle = 'Join Membership';
require_once '../config/config.php';
require_once '../includes/header.php';

$success = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $full_name = clean($_POST['full_name'] ?? '');
    $gender = clean($_POST['gender'] ?? '');
    $father_husband_name = clean($_POST['father_husband_name'] ?? '');
    $relation_type = clean($_POST['relation_type'] ?? '');
    $dob = clean($_POST['dob'] ?? '');
    $mobile = clean($_POST['mobile'] ?? '');
    $email = clean($_POST['email'] ?? '');
    $govt_id_type = clean($_POST['govt_id_type'] ?? '');
    $govt_id_number = clean($_POST['govt_id_number'] ?? '');
    $designation_id = clean($_POST['designation_id'] ?? '');
    $level_id = clean($_POST['level_id'] ?? '');
    $state_id = clean($_POST['state_id'] ?? '');
    $district_id = clean($_POST['district_id'] ?? '');
    $city = clean($_POST['city'] ?? '');
    $address_line1 = clean($_POST['address_line1'] ?? '');
    $pincode = clean($_POST['pincode'] ?? '');
    $validity_years = clean($_POST['validity_years'] ?? 1);
    
    // Validation
    $validator = new Validator();
    $validator->required('full_name', $full_name)
              ->required('gender', $gender)
              ->required('father_husband_name', $father_husband_name)
              ->required('dob', $dob)
              ->required('mobile', $mobile)
              ->mobile('mobile', $mobile)
              ->email('email', $email)
              ->required('govt_id_type', $govt_id_type)
              ->required('govt_id_number', $govt_id_number)
              ->required('designation_id', $designation_id)
              ->required('level_id', $level_id)
              ->required('address_line1', $address_line1)
              ->required('pincode', $pincode);
    
    if ($validator->isValid()) {
        // Get membership plan amount
        $plan = $db->fetch(
            "SELECT * FROM membership_plans 
             WHERE level_id = :level AND designation_id = :designation AND is_active = 1",
            ['level' => $level_id, 'designation' => $designation_id]
        );
        
        if (!$plan) {
            $errors[] = 'No membership plan found for selected level and designation.';
        } else {
            // File uploads
            $photoUpload = uploadFile($_FILES['photo'], MEMBER_PHOTO_PATH, unserialize(ALLOWED_IMAGE_TYPES), MAX_PHOTO_SIZE);
            $signatureUpload = uploadFile($_FILES['signature'], MEMBER_SIGNATURE_PATH, unserialize(ALLOWED_IMAGE_TYPES), MAX_SIGNATURE_SIZE);
            $idProofUpload = uploadFile($_FILES['id_proof'], MEMBER_ID_PROOF_PATH, unserialize(ALLOWED_DOCUMENT_TYPES), MAX_ID_PROOF_SIZE);
            
            if (!$photoUpload['success']) {
                $errors[] = 'Photo: ' . $photoUpload['error'];
            }
            if (!$signatureUpload['success']) {
                $errors[] = 'Signature: ' . $signatureUpload['error'];
            }
            if (!$idProofUpload['success']) {
                $errors[] = 'ID Proof: ' . $idProofUpload['error'];
            }
            
            if (empty($errors)) {
                // Generate Member ID
                $idGenerator = new MemberIDGenerator($db);
                $member_id = $idGenerator->generate($level_id, $state_id, $district_id);
                
                // Insert member
                try {
                    $memberId = $db->insert('members', [
                        'member_id' => $member_id,
                        'full_name' => $full_name,
                        'gender' => $gender,
                        'father_husband_name' => $father_husband_name,
                        'relation_type' => $relation_type,
                        'date_of_birth' => $dob,
                        'mobile' => $mobile,
                        'email' => $email,
                        'govt_id_type' => $govt_id_type,
                        'govt_id_number' => $govt_id_number,
                        'designation_id' => $designation_id,
                        'level_id' => $level_id,
                        'state_id' => $state_id ?: null,
                        'district_id' => $district_id ?: null,
                        'city' => $city,
                        'address_line1' => $address_line1,
                        'pincode' => $pincode,
                        'photo_path' => str_replace(BASE_PATH, '', $photoUpload['path']),
                        'signature_path' => str_replace(BASE_PATH, '', $signatureUpload['path']),
                        'id_proof_path' => str_replace(BASE_PATH, '', $idProofUpload['path']),
                        'membership_plan_id' => $plan['id'],
                        'validity_years' => $validity_years,
                        'payment_amount' => $plan['donation_amount'] * $validity_years,
                        'status' => 'pending',
                        'payment_status' => 'pending'
                    ]);
                    
                    // Redirect to payment page
                    redirect(SITE_URL . 'public/payment-success.php?member_id=' . $memberId . '&amount=' . ($plan['donation_amount'] * $validity_years));
                    
                } catch (Exception $e) {
                    $errors[] = 'Error: ' . $e->getMessage();
                }
            }
        }
    } else {
        $errors = $validator->getErrors();
    }
}

// Get form options
$levels = $db->fetchAll("SELECT * FROM levels WHERE is_active = 1 ORDER BY priority");
$states = $db->fetchAll("SELECT * FROM states WHERE is_active = 1 ORDER BY state_name");
$designations = $db->fetchAll("SELECT * FROM designations WHERE is_active = 1 ORDER BY priority");
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-white text-center py-4">
                    <h2 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>
                        Join Membership
                    </h2>
                    <p class="text-muted mb-0">Fill the form below to become a member</p>
                </div>
                
                <div class="card-body p-4">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</h6>
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" enctype="multipart/form-data">
                        <!-- Personal Information -->
                        <h5 class="border-bottom pb-2 mb-4">
                            <i class="fas fa-user me-2"></i>Personal Information
                        </h5>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Full Name *</label>
                                <input type="text" class="form-control" name="full_name" required
                                       value="<?php echo htmlspecialchars($full_name ?? ''); ?>">
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">Gender *</label>
                                <select class="form-select" name="gender" required>
                                    <option value="">Select</option>
                                    <option value="Male" <?php echo ($gender ?? '') == 'Male' ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo ($gender ?? '') == 'Female' ? 'selected' : ''; ?>>Female</option>
                                    <option value="Other" <?php echo ($gender ?? '') == 'Other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">Date of Birth *</label>
                                <input type="date" class="form-control" name="dob" required
                                       value="<?php echo htmlspecialchars($dob ?? ''); ?>">
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Relation Type *</label>
                                <select class="form-select" name="relation_type" required>
                                    <option value="Father">S/o (Son of)</option>
                                    <option value="Husband">W/o (Wife of)</option>
                                    <option value="C/o">C/o (Care of)</option>
                                </select>
                            </div>
                            
                            <div class="col-md-8">
                                <label class="form-label">Father/Husband Name *</label>
                                <input type="text" class="form-control" name="father_husband_name" required
                                       value="<?php echo htmlspecialchars($father_husband_name ?? ''); ?>">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Mobile Number *</label>
                                <input type="tel" class="form-control" name="mobile" 
                                       placeholder="10-digit mobile" required
                                       value="<?php echo htmlspecialchars($mobile ?? ''); ?>">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email"
                                       value="<?php echo htmlspecialchars($email ?? ''); ?>">
                            </div>
                        </div>
                        
                        <!-- Government ID -->
                        <h5 class="border-bottom pb-2 mb-4">
                            <i class="fas fa-id-card me-2"></i>Government ID Proof
                        </h5>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">ID Type *</label>
                                <select class="form-select" name="govt_id_type" required>
                                    <option value="">Select</option>
                                    <option value="Aadhar">Aadhar Card</option>
                                    <option value="PAN">PAN Card</option>
                                    <option value="Driving License">Driving License</option>
                                    <option value="Passport">Passport</option>
                                    <option value="Voter ID">Voter ID</option>
                                </select>
                            </div>
                            
                            <div class="col-md-8">
                                <label class="form-label">ID Number *</label>
                                <input type="text" class="form-control" name="govt_id_number" required
                                       value="<?php echo htmlspecialchars($govt_id_number ?? ''); ?>">
                            </div>
                        </div>
                        
                        <!-- Membership Details -->
                        <h5 class="border-bottom pb-2 mb-4">
                            <i class="fas fa-briefcase me-2"></i>Membership Details
                        </h5>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Level *</label>
                                <select class="form-select" name="level_id" required>
                                    <option value="">Select Level</option>
                                    <?php foreach ($levels as $level): ?>
                                        <option value="<?php echo $level['id']; ?>">
                                            <?php echo $level['level_name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Designation *</label>
                                <select class="form-select" name="designation_id" required>
                                    <option value="">Select Designation</option>
                                    <?php foreach ($designations as $designation): ?>
                                        <option value="<?php echo $designation['id']; ?>">
                                            <?php echo $designation['designation_name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Validity Years *</label>
                                <select class="form-select" name="validity_years">
                                    <option value="1">1 Year</option>
                                    <option value="2">2 Years</option>
                                    <option value="3">3 Years</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Address -->
                        <h5 class="border-bottom pb-2 mb-4">
                            <i class="fas fa-map-marker-alt me-2"></i>Address Details
                        </h5>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">State</label>
                                <select class="form-select" name="state_id">
                                    <option value="">Select State</option>
                                    <?php foreach ($states as $state): ?>
                                        <option value="<?php echo $state['id']; ?>">
                                            <?php echo $state['state_name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">City</label>
                                <input type="text" class="form-control" name="city"
                                       value="<?php echo htmlspecialchars($city ?? ''); ?>">
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Pincode *</label>
                                <input type="text" class="form-control" name="pincode" required
                                       placeholder="6-digit pincode"
                                       value="<?php echo htmlspecialchars($pincode ?? ''); ?>">
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Address *</label>
                                <textarea class="form-control" name="address_line1" rows="2" required><?php echo htmlspecialchars($address_line1 ?? ''); ?></textarea>
                            </div>
                        </div>
                        
                        <!-- File Uploads -->
                        <h5 class="border-bottom pb-2 mb-4">
                            <i class="fas fa-upload me-2"></i>Upload Documents
                        </h5>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Photo * (Max 2MB)</label>
                                <input type="file" class="form-control" name="photo" 
                                       accept="image/jpeg,image/jpg,image/png" required>
                                <small class="text-muted">JPG, PNG format</small>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Signature * (Max 1MB)</label>
                                <input type="file" class="form-control" name="signature" 
                                       accept="image/jpeg,image/jpg,image/png" required>
                                <small class="text-muted">JPG, PNG format</small>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">ID Proof * (Max 5MB)</label>
                                <input type="file" class="form-control" name="id_proof" 
                                       accept="image/*,application/pdf" required>
                                <small class="text-muted">JPG, PNG, PDF format</small>
                            </div>
                        </div>
                        
                        <!-- Terms -->
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a>
                            </label>
                        </div>
                        
                        <!-- Submit -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-check-circle me-2"></i>Submit & Proceed to Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
