<?php
$pageTitle = 'Edit Designation';
require_once '../includes/auth-check.php';
require_once '../includes/header.php';

$auth->requirePermission('manage_designations');

$id = clean($_GET['id'] ?? 0);

// Get designation details
$designation = $db->fetch("SELECT * FROM designations WHERE id = :id", ['id' => $id]);

if (!$designation) {
    $_SESSION['error'] = 'Designation not found';
    redirect(SITE_URL . 'admin/designations/index.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $designation_name = clean($_POST['designation_name'] ?? '');
    $designation_code = strtoupper(clean($_POST['designation_code'] ?? ''));
    $priority = clean($_POST['priority'] ?? '');
    $is_unique_per_area = isset($_POST['is_unique_per_area']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Validation
    if (empty($designation_name)) {
        $errors[] = 'Designation name is required';
    }
    
    if (empty($designation_code)) {
        $errors[] = 'Designation code is required';
    } elseif (!preg_match('/^[A-Z]{2,10}$/', $designation_code)) {
        $errors[] = 'Code must be 2-10 uppercase letters only';
    }
    
    if (empty($priority) || !is_numeric($priority)) {
        $errors[] = 'Priority must be a number';
    }
    
    // Check if code exists (excluding current record)
    if (empty($errors)) {
        $exists = $db->fetch(
            "SELECT id FROM designations WHERE designation_code = :code AND id != :id",
            ['code' => $designation_code, 'id' => $id]
        );
        
        if ($exists) {
            $errors[] = 'This designation code already exists';
        }
    }
    
    // Update if no errors
    if (empty($errors)) {
        try {
            $db->update('designations', [
                'designation_name' => $designation_name,
                'designation_code' => $designation_code,
                'priority' => $priority,
                'is_unique_per_area' => $is_unique_per_area,
                'is_active' => $is_active
            ], "id = {$id}");
            
            $_SESSION['success'] = 'Designation updated successfully!';
            redirect(SITE_URL . 'admin/designations/index.php');
            
        } catch (Exception $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }
} else {
    // Pre-fill form with existing data
    $designation_name = $designation['designation_name'];
    $designation_code = $designation['designation_code'];
    $priority = $designation['priority'];
    $is_unique_per_area = $designation['is_unique_per_area'];
    $is_active = $designation['is_active'];
}
?>

<div class="page-header">
    <h1><i class="fas fa-edit me-2"></i>Edit Designation</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="index.php">Designations</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Edit Designation Information</h5>
            </div>
            
            <div class="card-body">
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
                
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Designation Name *</label>
                            <input type="text" class="form-control" name="designation_name" 
                                   value="<?php echo htmlspecialchars($designation_name); ?>" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Code *</label>
                            <input type="text" class="form-control" name="designation_code" 
                                   value="<?php echo htmlspecialchars($designation_code); ?>" 
                                   maxlength="10" style="text-transform: uppercase;" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Priority *</label>
                        <input type="number" class="form-control" name="priority" 
                               value="<?php echo htmlspecialchars($priority); ?>" 
                               min="1" max="100" required>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_unique_per_area" 
                                   id="isUnique" value="1" <?php echo $is_unique_per_area ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="isUnique">
                                <strong>Unique Per Area</strong>
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" 
                                   id="isActive" value="1" <?php echo $is_active ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="isActive">
                                <strong>Active</strong>
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Designation
                        </button>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>Usage Stats
                </h5>
            </div>
            <div class="card-body">
                <?php
                $memberCount = $db->fetch(
                    "SELECT COUNT(*) as count FROM members WHERE designation_id = :id",
                    ['id' => $id]
                )['count'];
                ?>
                
                <p class="mb-2">
                    <strong>Members with this designation:</strong>
                </p>
                <h3 class="text-primary"><?php echo number_format($memberCount); ?></h3>
                
                <?php if ($memberCount > 0): ?>
                    <div class="alert alert-warning mt-3 small">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Deleting this designation will affect <?php echo $memberCount; ?> member(s).
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
