<?php
$pageTitle = 'Add Admin User';
require_once '../includes/auth-check.php';
require_once '../includes/header.php';

$auth->requirePermission('manage_admins');

$success = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean($_POST['username'] ?? '');
    $email = clean($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $full_name = clean($_POST['full_name'] ?? '');
    $role_type = clean($_POST['role_type'] ?? '');
    
    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
        $errors[] = 'All fields are required';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters';
    } else {
        // Check if username exists
        $exists = $db->fetch("SELECT id FROM admin_users WHERE username = :username OR email = :email", 
            ['username' => $username, 'email' => $email]);
        
        if ($exists) {
            $errors[] = 'Username or email already exists';
        } else {
            // Insert new admin
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            
            $db->insert('admin_users', [
                'username' => $username,
                'email' => $email,
                'password_hash' => $passwordHash,
                'full_name' => $full_name,
                'role_type' => $role_type,
                'is_active' => 1
            ]);
            
            $success = 'Admin user created successfully!';
        }
    }
}
?>

<div class="page-header">
    <h1><i class="fas fa-user-plus me-2"></i>Add Admin User</h1>
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
                    <div class="mb-3">
                        <label class="form-label">Username *</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Full Name *</label>
                        <input type="text" class="form-control" name="full_name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password *</label>
                        <input type="password" class="form-control" name="password" 
                               minlength="8" required>
                        <small class="text-muted">Minimum 8 characters</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Role *</label>
                        <select class="form-select" name="role_type" required>
                            <option value="">Select Role</option>
                            <option value="super_admin">Super Admin</option>
                            <option value="national_admin">National Admin</option>
                            <option value="state_admin">State Admin</option>
                            <option value="district_admin">District Admin</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Create Admin User
                    </button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
