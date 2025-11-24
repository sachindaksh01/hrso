<?php
$pageTitle = 'Approved Members';
require_once '../includes/auth-check.php';
require_once '../includes/header.php';

// Get approved members
$sql = "SELECT m.*, 
        d.designation_name, 
        l.level_name, 
        s.state_name 
        FROM members m
        JOIN designations d ON m.designation_id = d.id
        JOIN levels l ON m.level_id = l.id
        LEFT JOIN states s ON m.state_id = s.id
        WHERE m.status = 'approved'
        ORDER BY m.approved_at DESC
        LIMIT 100";

$members = $db->fetchAll($sql);
?>

<div class="page-header">
    <h1><i class="fas fa-check-circle me-2"></i>Approved Members</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Approved Members</li>
        </ol>
    </nav>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>
            Approved Members (<?php echo count($members); ?>)
        </h5>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="approvedTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Member ID</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Mobile</th>
                        <th>Expiry Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($members as $index => $member): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><strong><?php echo $member['member_id']; ?></strong></td>
                            <td><?php echo $member['full_name']; ?></td>
                            <td><?php echo $member['designation_name']; ?></td>
                            <td><?php echo $member['mobile']; ?></td>
                            <td>
                                <?php 
                                $expiry = strtotime($member['membership_expiry_date']);
                                $isExpired = $expiry < time();
                                ?>
                                <span class="badge <?php echo $isExpired ? 'bg-danger' : 'bg-success'; ?>">
                                    <?php echo formatDate($member['membership_expiry_date']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="view.php?id=<?php echo $member['id']; ?>" 
                                   class="btn btn-sm btn-primary">
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

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#approvedTable').DataTable({
        pageLength: 25,
        order: [[1, 'desc']]
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>
