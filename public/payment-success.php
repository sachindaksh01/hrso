<?php
$pageTitle = 'Payment Successful';
require_once '../config/config.php';
require_once '../includes/header.php';

$member_id = $_GET['member_id'] ?? null;
$amount = $_GET['amount'] ?? 0;
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow text-center">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-5x" style="color: #28a745;"></i>
                    </div>
                    
                    <h2 class="mb-3">Application Submitted!</h2>
                    
                    <p class="lead text-muted">
                        Your membership application has been submitted successfully.
                    </p>
                    
                    <?php if ($member_id): ?>
                        <div class="alert alert-info">
                            <strong>Application ID:</strong> #<?php echo $member_id; ?><br>
                            <strong>Amount:</strong> ₹<?php echo number_format($amount, 2); ?>
                        </div>
                    <?php endif; ?>
                    
                    <p class="text-muted">
                        Your application is pending admin approval. You will receive your 
                        Member ID and certificate via email once approved.
                    </p>
                    
                    <div class="mt-4">
                        <a href="<?php echo SITE_URL; ?>public/" class="btn btn-primary me-2">
                            <i class="fas fa-home me-2"></i>Go to Homepage
                        </a>
                        <a href="<?php echo SITE_URL; ?>public/contact.php" class="btn btn-outline-secondary">
                            <i class="fas fa-envelope me-2"></i>Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
