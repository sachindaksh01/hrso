<?php
$pageTitle = 'Renewal Members';
require_once '../includes/auth-check.php';
require_once '../includes/header.php';

// Get members expiring in next 30 days
$sql = "SELECT m.*, 
        d.designation_name, 
        l.level_name,
        s.state_name,
        DATEDIFF(m.membership_expiry_date, CURDATE()) as days_left
        FROM members m
        JOIN designations d ON m.designation_id = d.id
        JOIN levels l ON m.level_id = l.id
        LEFT JOIN states s ON m.state_id = s.id
        WHERE m.status = 'approved'
        AND DATEDIFF(m.membership_expiry_date, CURDATE()) BETWEEN 0 AND 30
        ORDER BY m.membership_expiry_date ASC";

$members = $db->fetchAll($sql);
?>

<div class="page-header">
    <h1><i class="fas fa-sync-alt me-2"></i>Renewal Members</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Renewal Members</li>
        </ol>
    </nav>
</div>

<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Showing members expiring in next 30 days</strong> - Total: <?php echo count($members); ?> members
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Members Due for Renewal</h5>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Member ID</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Expiry Date</th>
                        <th>Days Left</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($members)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="fas fa-check-circle fa-3x mb-3 d-block text-success"></i>
                                No members due for renewal
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($members as $index => $member): ?>
                            <tr class="<?php echo $member['days_left'] <= 7 ? 'table-warning' : ''; ?>">
                                <td><?php echo $index + 1; ?></td>
                                <td><strong><?php echo $member['member_id']; ?></strong></td>
                                <td><?php echo $member['full_name']; ?></td>
                                <td><?php echo $member['mobile']; ?></td>
                                <td><?php echo formatDate($member['membership_expiry_date']); ?></td>
                                <td>
                                    <span class="badge <?php echo $member['days_left'] <= 7 ? 'bg-danger' : 'bg-warning'; ?>">
                                        <?php echo $member['days_left']; ?> days left
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="view.php?id=<?php echo $member['id']; ?>" 
                                           class="btn btn-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="renew.php?id=<?php echo $member['id']; ?>" 
                                           class="btn btn-success" title="Renew Now">
                                            <i class="fas fa-sync-alt"></i> Renew
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

<?php require_once '../includes/footer.php'; ?>
