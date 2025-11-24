<?php
$pageTitle = 'Dashboard';
require_once 'includes/auth-check.php';
require_once 'includes/header.php';

// Get statistics
$stats = [
    'total_members' => $db->fetch("SELECT COUNT(*) as count FROM members")['count'],
    'pending_members' => $db->fetch("SELECT COUNT(*) as count FROM members WHERE status = 'pending'")['count'],
    'approved_members' => $db->fetch("SELECT COUNT(*) as count FROM members WHERE status = 'approved'")['count'],
    'total_revenue' => $db->fetch("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE payment_status = 'success'")['total']
];

// Recent members
$recentMembers = $db->fetchAll("
    SELECT m.*, d.designation_name, l.level_name 
    FROM members m
    JOIN designations d ON m.designation_id = d.id
    JOIN levels l ON m.level_id = l.id
    ORDER BY m.created_at DESC
    LIMIT 10
");
?>

<div class="page-header">
    <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0"><?php echo number_format($stats['total_members']); ?></h3>
                    <p class="text-muted mb-0">Total Members</p>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0"><?php echo number_format($stats['pending_members']); ?></h3>
                    <p class="text-muted mb-0">Pending Approval</p>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0"><?php echo number_format($stats['approved_members']); ?></h3>
                    <p class="text-muted mb-0">Approved Members</p>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">₹<?php echo number_format($stats['total_revenue'], 2); ?></h3>
                    <p class="text-muted mb-0">Total Revenue</p>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b, #38f9d7);">
                    <i class="fas fa-rupee-sign"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Members Table -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-users me-2"></i> Recent Member Registrations</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Member ID</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Level</th>
                        <th>Mobile</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentMembers)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">No members found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentMembers as $member): ?>
                            <tr>
                                <td><strong><?php echo $member['member_id']; ?></strong></td>
                                <td><?php echo $member['full_name']; ?></td>
                                <td><?php echo $member['designation_name']; ?></td>
                                <td><?php echo $member['level_name']; ?></td>
                                <td><?php echo $member['mobile']; ?></td>
                                <td><?php echo getStatusBadge($member['status']); ?></td>
                                <td><?php echo formatDate($member['created_at']); ?></td>
                                <td>
                                    <a href="members/view.php?id=<?php echo $member['id']; ?>" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
