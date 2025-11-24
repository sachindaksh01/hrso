<?php
$pageTitle = 'Add Designation';
require_once '../includes/auth-check.php';
require_once '../includes/header.php';

$auth->requirePermission('manage_designations');

$errors = [];
$success = '';

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
    
    // Check if code already exists
    if (empty($errors)) {
        $exists = $db->fetch(
            "SELECT id FROM designations WHERE designation_code = :code",
            ['code' => $designation_code]
        );
        
        if ($exists) {
            $errors[] = 'This designation code already exists';
        }
    }
    
    // Insert if no errors
    if (empty($errors)) {
        try {
            $db->insert('designations', [
                'designation_name' => $designation_name,
                'designation_code' => $designation_code,
                'priority' => $priority,
                'is_unique_per_area' => $is_unique_per_area,
                'is_active' => $is_active
            ]);
            
            $_SESSION['success'] = 'Designation added successfully!';
            redirect(SITE_URL . 'admin/designations/index.php');
            
        } catch (Exception $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<div class="page-header">
    <h1><i class="fas fa-plus me-2"></i>Add New Designation</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="index.php">Designations</a></li>
            <li class="breadcrumb-item active">Add New</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Designation Information</h5>
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
                                   value="<?php echo htmlspecialchars($designation_name ?? ''); ?>" 
                                   placeholder="e.g., President" required>
                            <small class="text-muted">Full designation title</small>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Code *</label>
                            <input type="text" class="form-control" name="designation_code" 
                                   value="<?php echo htmlspecialchars($designation_code ?? ''); ?>" 
                                   placeholder="e.g., PRES" maxlength="10" 
                                   style="text-transform: uppercase;" required>
                            <small class="text-muted">2-10 letters, uppercase</small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Priority *</label>
                        <input type="number" class="form-control" name="priority" 
                               value="<?php echo htmlspecialchars($priority ?? ''); ?>" 
                               min="1" max="100" required>
                        <small class="text-muted">Lower number = Higher priority (1 is highest)</small>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_unique_per_area" 
                                   id="isUnique" value="1" 
                                   <?php echo ($is_unique_per_area ?? 0) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="isUnique">
                                <strong>Unique Per Area</strong>
                                <br>
                                <small class="text-muted">Only one person can hold this designation in each area (e.g., President)</small>
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" 
                                   id="isActive" value="1" checked>
                            <label class="form-check-label" for="isActive">
                                <strong>Active</strong>
                                <br>
                                <small class="text-muted">Active designations appear in registration forms</small>
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Designation
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
                    <i class="fas fa-info-circle me-2"></i>Guidelines
                </h5>
            </div>
            <div class="card-body">
                <h6>Common Designations:</h6>
                <ul class="small">
                    <li>President (Priority: 1, Unique)</li>
                    <li>Vice President (Priority: 2)</li>
                    <li>General Secretary (Priority: 3, Unique)</li>
                    <li>Secretary (Priority: 4)</li>
                    <li>Treasurer (Priority: 6, Unique)</li>
                    <li>Member (Priority: 15)</li>
                </ul>
                
                <hr>
                
                <h6>Code Format:</h6>
                <ul class="small mb-0">
                    <li>Use 2-10 uppercase letters</li>
                    <li>No spaces or special characters</li>
                    <li>Examples: PRES, VPRES, GSEC, TREAS</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
