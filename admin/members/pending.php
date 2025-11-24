<?php
$pageTitle = 'Pending Members';
require_once '../includes/auth-check.php';
require_once '../includes/header.php';

// Get pending members
$sql = "SELECT m.*, 
        d.designation_name, 
        l.level_name, 
        s.state_name 
        FROM members m
        JOIN designations d ON m.designation_id = d.id
        JOIN levels l ON m.level_id = l.id
        LEFT JOIN states s ON m.state_id = s.id
        WHERE m.status = 'pending'
        ORDER BY m.created_at DESC";

$pendingMembers = $db->fetchAll($sql);
?>

<div class="page-header">
    <h1><i class="fas fa-clock me-2"></i>Pending Approvals</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Pending Members</li>
        </ol>
    </nav>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>
            Pending Applications (<?php echo count($pendingMembers); ?>)
        </h5>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="pendingTable">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th width="15%">Name</th>
                        <th width="15%">Designation</th>
                        <th width="10%">Level</th>
                        <th width="15%">Mobile</th>
                        <th width="10%">Amount</th>
                        <th width="12%">Applied On</th>
                        <th width="18%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pendingMembers)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                No pending applications
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pendingMembers as $index => $member): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td>
                                    <strong><?php echo $member['full_name']; ?></strong>
                                    <br>
                                    <small class="text-muted"><?php echo $member['member_id'] ?? 'ID Pending'; ?></small>
                                </td>
                                <td><?php echo $member['designation_name']; ?></td>
                                <td>
                                    <span class="badge bg-info"><?php echo $member['level_name']; ?></span>
                                </td>
                                <td><?php echo $member['mobile']; ?></td>
                                <td>
                                    <strong>₹<?php echo number_format($member['payment_amount'], 2); ?></strong>
                                </td>
                                <td>
                                    <small><?php echo formatDate($member['created_at'], 'd M Y, h:i A'); ?></small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="view.php?id=<?php echo $member['id']; ?>" 
                                           class="btn btn-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="approve.php?id=<?php echo $member['id']; ?>" 
                                           class="btn btn-success" title="Approve"
                                           onclick="return confirm('Approve this member?')">
                                            <i class="fas fa-check"></i>
                                        </a>
                                        <a href="reject.php?id=<?php echo $member['id']; ?>" 
                                           class="btn btn-danger" title="Reject"
                                           onclick="return confirm('Reject this application?')">
                                            <i class="fas fa-times"></i>
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

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#pendingTable').DataTable({
        pageLength: 25,
        order: [[6, 'desc']]
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>
