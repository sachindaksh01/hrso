<?php
$pageTitle = 'All Members';
require_once '../includes/auth-check.php';
require_once '../includes/header.php';

// Get filter
$status = clean($_GET['status'] ?? 'all');

// Build query
$sql = "SELECT m.*, 
        d.designation_name, 
        l.level_name, 
        s.state_name 
        FROM members m
        JOIN designations d ON m.designation_id = d.id
        JOIN levels l ON m.level_id = l.id
        LEFT JOIN states s ON m.state_id = s.id";

if ($status !== 'all') {
    $sql .= " WHERE m.status = :status";
}

$sql .= " ORDER BY m.created_at DESC LIMIT 100";

$members = $status !== 'all' 
    ? $db->fetchAll($sql, ['status' => $status])
    : $db->fetchAll($sql);

// Get counts for tabs
$counts = [
    'all' => $db->fetch("SELECT COUNT(*) as count FROM members")['count'],
    'pending' => $db->fetch("SELECT COUNT(*) as count FROM members WHERE status = 'pending'")['count'],
    'approved' => $db->fetch("SELECT COUNT(*) as count FROM members WHERE status = 'approved'")['count'],
    'rejected' => $db->fetch("SELECT COUNT(*) as count FROM members WHERE status = 'rejected'")['count'],
    'expired' => $db->fetch("SELECT COUNT(*) as count FROM members WHERE status = 'expired'")['count']
];
?>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-users me-2"></i>Member Management</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Members</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="add.php" class="btn btn-primary">
                <i class="fas fa-user-plus me-2"></i>Add Member Manually
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

<!-- Status Tabs -->
<ul class="nav nav-pills mb-4">
    <li class="nav-item">
        <a class="nav-link <?php echo $status === 'all' ? 'active' : ''; ?>" href="?status=all">
            All Members (<?php echo $counts['all']; ?>)
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo $status === 'pending' ? 'active' : ''; ?>" href="?status=pending">
            <i class="fas fa-clock me-1"></i>Pending (<?php echo $counts['pending']; ?>)
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo $status === 'approved' ? 'active' : ''; ?>" href="?status=approved">
            <i class="fas fa-check-circle me-1"></i>Approved (<?php echo $counts['approved']; ?>)
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo $status === 'rejected' ? 'active' : ''; ?>" href="?status=rejected">
            <i class="fas fa-times-circle me-1"></i>Rejected (<?php echo $counts['rejected']; ?>)
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo $status === 'expired' ? 'active' : ''; ?>" href="?status=expired">
            <i class="fas fa-exclamation-triangle me-1"></i>Expired (<?php echo $counts['expired']; ?>)
        </a>
    </li>
</ul>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="membersTable">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th width="15%">Member ID</th>
                        <th width="18%">Name</th>
                        <th width="12%">Designation</th>
                        <th width="10%">Level</th>
                        <th width="12%">Mobile</th>
                        <th width="10%">Status</th>
                        <th width="18%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($members)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                No members found
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($members as $index => $member): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td>
                                    <strong><?php echo $member['member_id'] ?? 'Pending'; ?></strong>
                                </td>
                                <td><?php echo $member['full_name']; ?></td>
                                <td><?php echo $member['designation_name']; ?></td>
                                <td>
                                    <span class="badge bg-info"><?php echo $member['level_name']; ?></span>
                                </td>
                                <td><?php echo $member['mobile']; ?></td>
                                <td><?php echo getStatusBadge($member['status']); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="view.php?id=<?php echo $member['id']; ?>" 
                                           class="btn btn-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="edit.php?id=<?php echo $member['id']; ?>" 
                                           class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($member['status'] === 'pending'): ?>
                                            <a href="approve.php?id=<?php echo $member['id']; ?>" 
                                               class="btn btn-success" title="Approve"
                                               onclick="return confirm('Approve this member?')">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        <?php endif; ?>
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
        pageLength: 25
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>
