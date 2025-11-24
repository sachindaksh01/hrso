<?php
$pageTitle = 'Gallery';
require_once '../config/config.php';
require_once '../includes/header.php';
?>

<!-- Page Header -->
<div class="hero-section" style="padding: 60px 0;">
    <div class="container text-center">
        <h1 class="display-4">Photo Gallery</h1>
        <p class="lead">Our events and activities</p>
    </div>
</div>

<!-- Gallery Content -->
<section class="section">
    <div class="container">
        <div class="row g-4">
            <!-- Sample Gallery Items -->
            <?php for ($i = 1; $i <= 12; $i++): ?>
                <div class="col-md-4 col-lg-3">
                    <div class="card border-0 shadow-sm h-100">
                        <img src="https://via.placeholder.com/400x300/<?php echo sprintf('%06X', mt_rand(0, 0xFFFFFF)); ?>/ffffff?text=Event+<?php echo $i; ?>" 
                             class="card-img-top" alt="Event <?php echo $i; ?>">
                        <div class="card-body">
                            <h6 class="card-title">Event Title <?php echo $i; ?></h6>
                            <p class="card-text small text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                <?php echo date('d M Y', strtotime("-{$i} days")); ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>
