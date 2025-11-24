<?php
$pageTitle = 'Contact Us';
require_once '../config/config.php';
require_once '../includes/header.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean($_POST['name'] ?? '');
    $email = clean($_POST['email'] ?? '');
    $phone = clean($_POST['phone'] ?? '');
    $subject = clean($_POST['subject'] ?? '');
    $message = clean($_POST['message'] ?? '');
    
    // Validation
    $validator = new Validator();
    $validator->required('name', $name)
              ->required('email', $email)
              ->email('email', $email)
              ->required('phone', $phone)
              ->mobile('phone', $phone)
              ->required('subject', $subject)
              ->required('message', $message);
    
    if ($validator->isValid()) {
        // Here you can save to database or send email
        // For now, just show success message
        $success = 'Thank you for contacting us! We will get back to you soon.';
        
        // Clear form
        $name = $email = $phone = $subject = $message = '';
    } else {
        $error = $validator->getFirstError();
    }
}
?>

<!-- Page Header -->
<div class="hero-section" style="padding: 60px 0;">
    <div class="container text-center">
        <h1 class="display-4">Contact Us</h1>
        <p class="lead">Get in touch with us</p>
    </div>
</div>

<!-- Contact Content -->
<section class="section">
    <div class="container">
        <div class="row">
            <!-- Contact Info -->
            <div class="col-lg-4 mb-4">
                <div class="feature-card h-100">
                    <h4 class="mb-4">Get In Touch</h4>
                    
                    <div class="mb-4">
                        <div class="d-flex align-items-start">
                            <div class="feature-icon me-3" style="width: 50px; height: 50px; font-size: 20px;">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <h6>Email</h6>
                                <p class="text-muted mb-0"><?php echo SITE_EMAIL; ?></p>
                                <p class="text-muted mb-0"><?php echo SUPPORT_EMAIL; ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex align-items-start">
                            <div class="feature-icon me-3" style="width: 50px; height: 50px; font-size: 20px;">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <h6>Phone</h6>
                                <p class="text-muted mb-0"><?php echo CONTACT_PHONE; ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex align-items-start">
                            <div class="feature-icon me-3" style="width: 50px; height: 50px; font-size: 20px;">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <h6>Address</h6>
                                <p class="text-muted mb-0">
                                    New Delhi, India
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h6 class="mb-3">Follow Us</h6>
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="fab fa-facebook"></i>
                            </a>
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="fab fa-linkedin"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="feature-card">
                    <h4 class="mb-4">Send Us a Message</h4>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name *</label>
                                <input type="text" class="form-control" name="name" 
                                       value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control" name="email" 
                                       value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control" name="phone" 
                                       value="<?php echo htmlspecialchars($phone ?? ''); ?>" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Subject *</label>
                                <input type="text" class="form-control" name="subject" 
                                       value="<?php echo htmlspecialchars($subject ?? ''); ?>" required>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Message *</label>
                                <textarea class="form-control" name="message" rows="5" required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                            </div>
                            
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>
