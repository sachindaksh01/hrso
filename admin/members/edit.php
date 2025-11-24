<?php
$pageTitle = 'Edit Member';
require_once '../includes/auth-check.php';
require_once '../includes/header.php';

$id = clean($_GET['id'] ?? 0);

// Get member
$member = $db->fetch("SELECT * FROM members WHERE id = :id", ['id' => $id]);

if (!$member) {
    $_SESSION['error'] = 'Member not found';
    redirect(SITE_URL . 'admin/members/index.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = clean($_POST['full_name'] ?? '');
    $mobile = clean($_POST['mobile'] ?? '');
    $email = clean($_POST['email'] ?? '');
    $designation_id = clean($_POST['designation_id'] ?? '');
    $level_id = clean($_POST['level_id'] ?? '');
    $state_id = clean($_POST['state_id'] ?? '');
    
    if (empty($errors)) {
        try {
            $db->update('members', [
                'full_name' => $full_name,
                'mobile' => $mobile,
                'email' => $email,
                'designation_id' => $designation_id,
                'level_id' => $level_id,
                'state_id' => $state_id ?: null
            ], "id = {$id}");
            
            $_SESSION['success'] = 'Member updated successfully!';
            redirect(SITE_URL . 'admin/members/view.php?id=' . $id);
            
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
    <h1><i class="fas fa-edit me-2"></i>Edit Member</h1>
</div>

<div class="card shadow-sm">
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
        
        <form method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name *</label>
                    <input type="text" class="form-control" name="full_name" 
                           value="<?php echo htmlspecialchars($member['full_name']); ?>" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Mobile *</label>
                    <input type="tel" class="form-control" name="mobile" 
                           value="<?php echo htmlspecialchars($member['mobile']); ?>" required>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Level *</label>
                    <select class="form-select" name="level_id" required>
                        <?php foreach ($levels as $level): ?>
                            <option value="<?php echo $level['id']; ?>" 
                                    <?php echo $member['level_id'] == $level['id'] ? 'selected' : ''; ?>>
                                <?php echo $level['level_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label">Designation *</label>
                    <select class="form-select" name="designation_id" required>
                        <?php foreach ($designations as $designation): ?>
                            <option value="<?php echo $designation['id']; ?>"
                                    <?php echo $member['designation_id'] == $designation['id'] ? 'selected' : ''; ?>>
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
                            <option value="<?php echo $state['id']; ?>"
                                    <?php echo $member['state_id'] == $state['id'] ? 'selected' : ''; ?>>
                                <?php echo $state['state_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" 
                       value="<?php echo htmlspecialchars($member['email']); ?>">
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Update Member
            </button>
            <a href="view.php?id=<?php echo $id; ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
