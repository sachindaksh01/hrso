<?php
$pageTitle = 'Change Password';
require_once '../includes/auth-check.php';
require_once '../includes/header.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Get current admin password
    $admin = $db->fetch("SELECT password_hash FROM admin_users WHERE id = :id", 
        ['id' => $_SESSION['admin_id']]);
    
    // Verify current password
    if (!password_verify($currentPassword, $admin['password_hash'])) {
        $error = 'Current password is incorrect';
    } elseif ($newPassword !== $confirmPassword) {
        $error = 'New passwords do not match';
    } elseif (strlen($newPassword) < 8) {
        $error = 'Password must be at least 8 characters';
    } else {
        // Update password
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $db->update('admin_users', 
            ['password_hash' => $newHash], 
            "id = {$_SESSION['admin_id']}");
        
        $success = 'Password changed successfully!';
    }
}
?>

<div class="page-header">
    <h1><i class="fas fa-key me-2"></i>Change Password</h1>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" name="new_password" 
                               minlength="8" required>
                        <small class="text-muted">Minimum 8 characters</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" name="confirm_password" 
                               minlength="8" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Change Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
