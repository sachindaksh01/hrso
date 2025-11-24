<?php
$pageTitle = 'Manage Designations';
require_once '../includes/auth-check.php';
require_once '../includes/header.php';

// $auth->requirePermission('manage_designations');

// Get all designations
$designations = $db->fetchAll("SELECT * FROM designations ORDER BY priority ASC");
?>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-briefcase me-2"></i>Manage Designations</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Designations</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="add.php" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Designation
            </a>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>
            All Designations (<?php echo count($designations); ?>)
        </h5>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="designationsTable">
                <thead class="table-light">
                    <tr>
                        <th width="8%">Priority</th>
                        <th width="30%">Designation Name</th>
                        <th width="15%">Code</th>
                        <th width="15%">Unique Per Area</th>
                        <th width="12%">Status</th>
                        <th width="20%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($designations)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                No designations found
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($designations as $designation): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-info"><?php echo $designation['priority']; ?></span>
                                </td>
                                <td>
                                    <strong><?php echo $designation['designation_name']; ?></strong>
                                </td>
                                <td>
                                    odede><?php echo $designation['designation_code']; ?></code>
                                </td>
                                <td>
                                    <?php if ($designation['is_unique_per_area']): ?>
                                        <span class="badge bg-warning">
                                            <i class="fas fa-lock me-1"></i>Unique
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-unlock me-1"></i>Multiple
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($designation['is_active']): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="edit.php?id=<?php echo $designation['id']; ?>" 
                                           class="btn btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="toggle-status.php?id=<?php echo $designation['id']; ?>" 
                                           class="btn btn-<?php echo $designation['is_active'] ? 'warning' : 'success'; ?>" 
                                           title="<?php echo $designation['is_active'] ? 'Deactivate' : 'Activate'; ?>"
                                           onclick="return confirm('Toggle status?')">
                                            <i class="fas fa-<?php echo $designation['is_active'] ? 'eye-slash' : 'eye'; ?>"></i>
                                        </a>
                                        <a href="delete.php?id=<?php echo $designation['id']; ?>" 
                                           class="btn btn-danger" title="Delete"
                                           onclick="return confirm('Are you sure? This will affect existing members!')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card shadow-sm mt-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-info-circle me-2"></i>
            About Designations
        </h5>
    </div>
    <div class="card-body">
        <ul class="mb-0">
            <li><strong>Priority:</strong> Lower number = Higher importance (e.g., President = 1)</li>
            <li><strong>Unique Per Area:</strong> Only one person can hold this designation in an area (e.g., President)</li>
            <li><strong>Code:</strong> Used for internal identification and reporting</li>
            <li><strong>Status:</strong> Only active designations appear in registration forms</li>
        </ul>
    </div>
</div>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#designationsTable').DataTable({
        order: [[0, 'asc']],
        pageLength: 25
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>
