<?php
$pageTitle = 'Expired Members';
require_once '../includes/auth-check.php';
require_once '../includes/header.php';

// Get expired or expiring soon members
$sql = "SELECT m.*, 
        d.designation_name, 
        l.level_name,
        DATEDIFF(m.membership_expiry_date, CURDATE()) as days_left
        FROM members m
        JOIN designations d ON m.designation_id = d.id
        JOIN levels l ON m.level_id = l.id
        WHERE m.status IN ('approved', 'expired')
        AND m.membership_expiry_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
        ORDER BY m.membership_expiry_date ASC";

$members = $db->fetchAll($sql);
?>

<div class="page-header">
    <h1><i class="fas fa-exclamation-triangle me-2"></i>Expired & Expiring Members</h1>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Members Expiring in Next 30 Days</h5>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Member ID</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Expiry Date</th>
                        <th>Days Left</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($members as $member): ?>
                        <tr class="<?php echo $member['days_left'] < 0 ? 'table-danger' : ($member['days_left'] < 7 ? 'table-warning' : ''); ?>">
                            <td><strong><?php echo $member['member_id']; ?></strong></td>
                            <td><?php echo $member['full_name']; ?></td>
                            <td><?php echo $member['mobile']; ?></td>
                            <td><?php echo formatDate($member['membership_expiry_date']); ?></td>
                            <td>
                                <?php if ($member['days_left'] < 0): ?>
                                    <span class="badge bg-danger">Expired <?php echo abs($member['days_left']); ?> days ago</span>
                                <?php else: ?>
                                    <span class="badge bg-warning"><?php echo $member['days_left']; ?> days left</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo getStatusBadge($member['status']); ?></td>
                            <td>
                                <a href="view.php?id=<?php echo $member['id']; ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
