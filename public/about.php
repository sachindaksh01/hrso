<?php
$pageTitle = 'About Us';
require_once '../config/config.php';
require_once '../includes/header.php';
?>

<!-- Page Header -->
<div class="hero-section" style="padding: 60px 0;">
    <div class="container text-center">
        <h1 class="display-4">About Us</h1>
        <p class="lead">Learn more about our mission, vision, and values</p>
    </div>
</div>

<!-- About Content -->
<section class="section">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="mb-4">Who We Are</h2>
                <p class="lead text-muted">
                    <?php echo SITE_NAME; ?> is a nationwide social welfare organization 
                    dedicated to empowering communities and creating positive change across India.
                </p>
                <p>
                    Established with the vision of bringing together socially responsible 
                    citizens, we work towards the holistic development of society through 
                    various initiatives in education, healthcare, skill development, and 
                    community support.
                </p>
                <p>
                    Our organization operates with complete transparency and accountability, 
                    ensuring that every effort contributes meaningfully to the betterment 
                    of society.
                </p>
            </div>
            <div class="col-lg-6">
                <img src="https://via.placeholder.com/600x400/667eea/ffffff?text=Our+Team" 
                     alt="About Us" class="img-fluid rounded shadow">
            </div>
        </div>
        
        <!-- Mission & Vision -->
        <div class="row g-4 my-5">
            <div class="col-md-6">
                <div class="feature-card h-100">
                    <div class="feature-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3>Our Mission</h3>
                    <p class="text-muted">
                        To create a strong network of socially responsible individuals 
                        who work collaboratively for community development, education, 
                        and social welfare across India.
                    </p>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="feature-card h-100">
                    <div class="feature-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3>Our Vision</h3>
                    <p class="text-muted">
                        To become India's most trusted and impactful social organization, 
                        empowering millions to contribute towards building a better, 
                        more equitable society.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Core Values -->
        <div class="section-title mt-5">
            <h2>Our Core Values</h2>
            <p>The principles that guide our work</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-3">
                <div class="text-center p-4">
                    <div class="feature-icon mx-auto">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h5 class="mt-3">Integrity</h5>
                    <p class="text-muted small">Transparency and honesty in all our operations</p>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="text-center p-4">
                    <div class="feature-icon mx-auto">
                        <i class="fas fa-users"></i>
                    </div>
                    <h5 class="mt-3">Inclusivity</h5>
                    <p class="text-muted small">Welcoming all, regardless of background</p>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="text-center p-4">
                    <div class="feature-icon mx-auto">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h5 class="mt-3">Innovation</h5>
                    <p class="text-muted small">Creative solutions to social challenges</p>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="text-center p-4">
                    <div class="feature-icon mx-auto">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h5 class="mt-3">Compassion</h5>
                    <p class="text-muted small">Empathy and care in everything we do</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics -->
<section class="section bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <h2 class="display-4" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    <?php echo number_format($db->fetch("SELECT COUNT(*) as count FROM members WHERE status = 'approved'")['count']); ?>+
                </h2>
                <p class="text-muted">Active Members</p>
            </div>
            
            <div class="col-md-3 mb-4">
                <h2 class="display-4" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    <?php echo $db->fetch("SELECT COUNT(DISTINCT state_id) as count FROM members WHERE status = 'approved'")['count']; ?>+
                </h2>
                <p class="text-muted">States Covered</p>
            </div>
            
            <div class="col-md-3 mb-4">
                <h2 class="display-4" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    <?php echo $db->fetch("SELECT COUNT(DISTINCT district_id) as count FROM members WHERE status = 'approved'")['count']; ?>+
                </h2>
                <p class="text-muted">Districts Reached</p>
            </div>
            
            <div class="col-md-3 mb-4">
                <h2 class="display-4" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    100+
                </h2>
                <p class="text-muted">Initiatives Completed</p>
            </div>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>
