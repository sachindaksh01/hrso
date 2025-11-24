<?php
$pageTitle = 'All Members';
require_once '../includes/auth-check.php';
require_once '../includes/header.php';

// Get filters
$search = clean($_GET['search'] ?? '');
$level = clean($_GET['level'] ?? '');
$state = clean($_GET['state'] ?? '');
$designation = clean($_GET['designation'] ?? '');
$status = clean($_GET['status'] ?? '');

// Build query
$sql = "SELECT m.*, 
        d.designation_name, 
        l.level_name, 
        s.state_name,
        DATEDIFF(m.membership_expiry_date, CURDATE()) as days_left
        FROM members m
        JOIN designations d ON m.designation_id = d.id
        JOIN levels l ON m.level_id = l.id
        LEFT JOIN states s ON m.state_id = s.id
        WHERE 1=1";

$params = [];

if ($search) {
    $sql .= " AND (m.member_id LIKE :search OR m.full_name LIKE :search OR m.mobile LIKE :search)";
    $params['search'] = "%{$search}%";
}

if ($level) {
    $sql .= " AND m.level_id = :level";
    $params['level'] = $level;
}

if ($state) {
    $sql .= " AND m.state_id = :state";
    $params['state'] = $state;
}

if ($designation) {
    $sql .= " AND m.designation_id = :designation";
    $params['designation'] = $designation;
}

if ($status) {
    $sql .= " AND m.status = :status";
    $params['status'] = $status;
}

$sql .= " ORDER BY m.created_at DESC LIMIT 500";

$members = $db->fetchAll($sql, $params);

// Get filter options
$levels = $db->fetchAll("SELECT * FROM levels WHERE is_active = 1 ORDER BY priority");
$states = $db->fetchAll("SELECT * FROM states WHERE is_active = 1 ORDER BY state_name");
$designations = $db->fetchAll("SELECT * FROM designations WHERE is_active = 1 ORDER BY priority");
?>

<div class="page-header">
    <h1><i class="fas fa-users me-2"></i>All Members</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">All Members</li>
        </ol>
    </nav>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Advanced Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-filter me-2"></i>Search & Filter Members
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="search" 
                           placeholder="Search by Name, ID, Mobile..." 
                           value="<?php echo htmlspecialchars($search); ?>">
                </div>
                
                <div class="col-md-2">
                    <select class="form-select" name="level">
                        <option value="">All Levels</option>
                        <?php foreach ($levels as $lvl): ?>
                            <option value="<?php echo $lvl['id']; ?>" 
                                    <?php echo $level == $lvl['id'] ? 'selected' : ''; ?>>
                                <?php echo $lvl['level_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <select class="form-select" name="designation">
                        <option value="">All Designations</option>
                        <?php foreach ($designations as $des): ?>
                            <option value="<?php echo $des['id']; ?>" 
                                    <?php echo $designation == $des['id'] ? 'selected' : ''; ?>>
                                <?php echo $des['designation_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <select class="form-select" name="state">
                        <option value="">All States</option>
                        <?php foreach ($states as $st): ?>
                            <option value="<?php echo $st['id']; ?>" 
                                    <?php echo $state == $st['id'] ? 'selected' : ''; ?>>
                                <?php echo $st['state_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="pending" <?php echo $status == 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="approved" <?php echo $status == 'approved' ? 'selected' : ''; ?>>Approved</option>
                        <option value="rejected" <?php echo $status == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                        <option value="expired" <?php echo $status == 'expired' ? 'selected' : ''; ?>>Expired</option>
                    </select>
                </div>
                
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            
            <?php if ($search || $level || $state || $designation || $status): ?>
                <div class="mt-3">
                    <a href="all.php" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-redo me-1"></i>Reset Filters
                    </a>
                    <span class="text-muted ms-3">Found: <strong><?php echo count($members); ?></strong> members</span>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Members Table -->
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>Members List (<?php echo count($members); ?>)
        </h5>
        <div>
            <a href="export-excel.php?<?php echo http_build_query($_GET); ?>" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel me-1"></i>Export Excel
            </a>
            <a href="add.php" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i>Add New
            </a>
        </div>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="membersTable">
                <thead class="table-light">
                    <tr>
                        <th width="3%">#</th>
                        <th width="12%">Member ID</th>
                        <th width="15%">Name</th>
                        <th width="10%">Designation</th>
                        <th width="8%">Level</th>
                        <th width="10%">Mobile</th>
                        <th width="8%">Status</th>
                        <th width="10%">Expiry</th>
                        <th width="24%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($members)): ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                No members found
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($members as $index => $member): ?>
                            <tr class="<?php echo $member['days_left'] < 0 ? 'table-danger' : ($member['days_left'] < 7 && $member['status'] == 'approved' ? 'table-warning' : ''); ?>">
                                <td><?php echo $index + 1; ?></td>
                                <td>
                                    <strong><?php echo $member['member_id'] ?? 'Pending'; ?></strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if ($member['photo_path'] && file_exists(BASE_PATH . $member['photo_path'])): ?>
                                            <img src="<?php echo UPLOAD_URL . $member['photo_path']; ?>" 
                                                 class="rounded me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="rounded me-2 d-flex align-items-center justify-content-center" 
                                                 style="width:30px; height:30px; background: linear-gradient(135deg, #667eea, #764ba2); color:white; font-size:12px; font-weight:bold;">
                                                <?php echo strtoupper(substr($member['full_name'], 0, 1)); ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php echo $member['full_name']; ?>
                                    </div>
                                </td>
                                <td><?php echo $member['designation_name']; ?></td>
                                <td><span class="badge bg-info"><?php echo $member['level_name']; ?></span></td>
                                <td><?php echo $member['mobile']; ?></td>
                                <td><?php echo getStatusBadge($member['status']); ?></td>
                                <td>
                                    <?php if ($member['membership_expiry_date']): ?>
                                        <small><?php echo formatDate($member['membership_expiry_date'], 'd M Y'); ?></small>
                                        <?php if ($member['days_left'] < 0): ?>
                                            <br><span class="badge bg-danger">Expired</span>
                                        <?php elseif ($member['days_left'] <= 7): ?>
                                            <br><span class="badge bg-warning"><?php echo $member['days_left']; ?>d left</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <!-- View Profile -->
                                        <a href="view.php?id=<?php echo $member['id']; ?>" 
                                           class="btn btn-primary" title="View Profile">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <!-- Edit -->
                                        <a href="edit.php?id=<?php echo $member['id']; ?>" 
                                           class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <!-- Download ID Card -->
                                        <?php if ($member['status'] == 'approved'): ?>
                                            <a href="../documents/generate-id-card.php?id=<?php echo $member['id']; ?>" 
                                               class="btn btn-info" title="Download ID Card" target="_blank">
                                                <i class="fas fa-id-card"></i>
                                            </a>
                                            
                                            <!-- Download Oath -->
                                            <a href="../documents/generate-oath.php?id=<?php echo $member['id']; ?>" 
                                               class="btn btn-secondary" title="Download Oath Letter" target="_blank">
                                                <i class="fas fa-certificate"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <!-- Mark Expired -->
                                        <?php if ($member['status'] == 'approved' && $member['days_left'] >= 0): ?>
                                            <a href="mark-expired.php?id=<?php echo $member['id']; ?>" 
                                               class="btn btn-danger" title="Mark as Expired"
                                               onclick="return confirm('Mark this member as expired?')">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <!-- Renew -->
                                        <?php if ($member['status'] == 'approved' || $member['status'] == 'expired'): ?>
                                            <a href="renew.php?id=<?php echo $member['id']; ?>" 
                                               class="btn btn-success" title="Renew Membership">
                                                <i class="fas fa-sync-alt"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <!-- Delete -->
                                        <a href="delete.php?id=<?php echo $member['id']; ?>" 
                                           class="btn btn-dark" title="Delete"
                                           onclick="return confirm('Are you sure you want to delete this member?')">
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

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#membersTable').DataTable({
        order: [[0, 'asc']],
        pageLength: 50,
        paging: true,
        searching: false // Using custom search
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>
