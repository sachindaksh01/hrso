<?php
$pageTitle = 'Members Directory';
require_once '../config/config.php';
require_once '../includes/header.php';

// Get filter parameters
$search = clean($_GET['search'] ?? '');
$level = clean($_GET['level'] ?? '');
$state = clean($_GET['state'] ?? '');
$designation = clean($_GET['designation'] ?? '');
$status = 'approved'; // Only show approved members publicly

// Build query
$sql = "SELECT m.*, 
        d.designation_name, 
        l.level_name, 
        s.state_name 
        FROM members m
        JOIN designations d ON m.designation_id = d.id
        JOIN levels l ON m.level_id = l.id
        LEFT JOIN states s ON m.state_id = s.id
        WHERE m.status = :status";

$params = ['status' => $status];

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

$sql .= " ORDER BY m.created_at DESC LIMIT 50";

// Execute query
$members = $db->fetchAll($sql, $params);

// Get filter options
$levels = $db->fetchAll("SELECT * FROM levels WHERE is_active = 1 ORDER BY priority");
$states = $db->fetchAll("SELECT * FROM states WHERE is_active = 1 ORDER BY state_name");
$designations = $db->fetchAll("SELECT * FROM designations WHERE is_active = 1 ORDER BY priority");
?>

<div class="container my-5">
    <div class="page-header text-center mb-5">
        <h1><i class="fas fa-users me-2"></i>Members Directory</h1>
        <p class="lead text-muted">Search and verify our registered members</p>
    </div>
    
    <!-- Search & Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="search" 
                               placeholder="Search by Name, ID, Mobile..." 
                               value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    
                    <div class="col-md-3">
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
                    
                    <div class="col-md-3">
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
                    
                    <div class="col-md-3">
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
                    
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-2"></i>Search
                        </button>
                        <a href="members.php" class="btn btn-outline-secondary">
                            <i class="fas fa-redo me-2"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Results -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>
                Search Results (<?php echo count($members); ?> found)
            </h5>
        </div>
        <div class="card-body p-0">
            <?php if (empty($members)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No members found matching your criteria</p>
                </div>
            <?php else: ?>
                <div class="row g-0">
                    <?php foreach ($members as $member): ?>
                        <div class="col-md-6 col-lg-4 p-3 border-bottom border-end">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <?php if ($member['photo_path'] && file_exists(BASE_PATH . $member['photo_path'])): ?>
                                        <img src="<?php echo UPLOAD_URL . $member['photo_path']; ?>" 
                                             alt="Photo" class="rounded" style="width:80px; height:80px; object-fit:cover;">
                                    <?php else: ?>
                                        <div class="rounded d-flex align-items-center justify-content-center" 
                                             style="width:80px; height:80px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color:white; font-size:32px; font-weight:bold;">
                                            <?php echo strtoupper(substr($member['full_name'], 0, 1)); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1"><?php echo $member['full_name']; ?></h6>
                                    <p class="mb-1 small text-muted">
                                        <strong>ID:</strong> <?php echo $member['member_id']; ?>
                                    </p>
                                    <p class="mb-1 small">
                                        <span class="badge bg-primary"><?php echo $member['designation_name']; ?></span>
                                        <span class="badge bg-secondary"><?php echo $member['level_name']; ?></span>
                                    </p>
                                    <?php if ($member['state_name']): ?>
                                        <p class="mb-2 small text-muted">
                                            <i class="fas fa-map-marker-alt me-1"></i><?php echo $member['state_name']; ?>
                                        </p>
                                    <?php endif; ?>
                                    <a href="member-verify.php?id=<?php echo $member['member_id']; ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
