<?php
$pageTitle = 'Home';
require_once '../config/config.php';
require_once '../includes/header.php';

// Get some stats for homepage
$totalMembers = $db->fetch("SELECT COUNT(*) as count FROM members WHERE status = 'approved'")['count'] ?? 0;
$totalStates = $db->fetch("SELECT COUNT(DISTINCT state_id) as count FROM members WHERE status = 'approved'")['count'] ?? 0;
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0" style="position: relative; z-index: 1;">
                <h1 class="display-4">Welcome to <?php echo SITE_NAME; ?></h1>
                <p class="lead"><?php echo SITE_TAGLINE; ?></p>
                <p class="mb-4">
                    Join thousands of members across India working together for 
                    social welfare, community development, and positive change.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="join.php" class="btn btn-light btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Become a Member
                    </a>
                    <a href="members.php" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-search me-2"></i>Verify Member
                    </a>
                </div>
                
                <!-- Stats -->
                <div class="row mt-5">
                    <div class="col-6">
                        <h3 class="mb-0"><?php echo number_format($totalMembers); ?>+</h3>
                        <p class="mb-0 opacity-75">Active Members</p>
                    </div>
                    <div class="col-6">
                        <h3 class="mb-0"><?php echo $totalStates; ?>+</h3>
                        <p class="mb-0 opacity-75">States Covered</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6" style="position: relative; z-index: 1;">
                <img src="https://via.placeholder.com/600x500/667eea/ffffff?text=Community+Together" 
                     alt="Community" class="img-fluid rounded shadow-lg">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section bg-light">
    <div class="container">
        <div class="section-title">
            <h2>Why Join Us?</h2>
            <p>Discover the benefits of being part of our community</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h4>Community Network</h4>
                    <p class="text-muted">
                        Connect with thousands of like-minded individuals across India 
                        working towards social welfare.
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <h4>Official Recognition</h4>
                    <p class="text-muted">
                        Get official membership ID card and certificate recognized 
                        across all states.
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h4>Growth Opportunities</h4>
                    <p class="text-muted">
                        Leadership positions, skill development programs, and 
                        networking events.
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>Secure & Verified</h4>
                    <p class="text-muted">
                        All members are verified through government ID and secure 
                        database system.
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4>Multi-Level Organization</h4>
                    <p class="text-muted">
                        Structured organization from National to Panchayat level 
                        for effective governance.
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h4>Social Impact</h4>
                    <p class="text-muted">
                        Be part of initiatives making real difference in education, 
                        health, and community development.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Preview Section -->
<section class="section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <img src="https://via.placeholder.com/600x400/764ba2/ffffff?text=About+Us" 
                     alt="About Us" class="img-fluid rounded shadow">
            </div>
            <div class="col-lg-6">
                <h2 class="mb-4">About <?php echo SITE_NAME; ?></h2>
                <p class="lead text-muted">
                    We are a nationwide organization dedicated to social welfare, 
                    community development, and empowering individuals across India.
                </p>
                <p class="text-muted">
                    Our mission is to create a strong network of socially responsible 
                    citizens who work together for the betterment of society. Through 
                    various programs and initiatives, we focus on education, healthcare, 
                    skill development, and community support.
                </p>
                <ul class="list-unstyled mt-4">
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Registered Non-Profit Organization
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Pan-India Presence
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Transparent Operations
                    </li>
                </ul>
                <a href="about.php" class="btn btn-primary mt-3">
                    Learn More <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
    <div class="container text-center text-white">
        <h2 class="mb-4">Ready to Make a Difference?</h2>
        <p class="lead mb-4">
            Join our community today and be part of something bigger
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="join.php" class="btn btn-light btn-lg">
                <i class="fas fa-user-plus me-2"></i>Join Membership
            </a>
            <a href="donate.php" class="btn btn-outline-light btn-lg">
                <i class="fas fa-hand-holding-heart me-2"></i>Support Us
            </a>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>
