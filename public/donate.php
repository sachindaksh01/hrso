<?php
$pageTitle = 'Donate';
require_once '../config/config.php';
require_once '../includes/header.php';
?>

<!-- Page Header -->
<div class="hero-section" style="padding: 60px 0;">
    <div class="container text-center">
        <h1 class="display-4">Support Our Cause</h1>
        <p class="lead">Your contribution makes a difference</p>
    </div>
</div>

<!-- Donate Content -->
<section class="section">
    <div class="container">
        <div class="row">
            <!-- Donation Form -->
            <div class="col-lg-7 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">
                            <i class="fas fa-hand-holding-heart me-2"></i>
                            Make a Donation
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <form id="donationForm">
                            <div class="mb-4">
                                <label class="form-label">Select Amount</label>
                                <div class="row g-2">
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-primary w-100 amount-btn" data-amount="500">₹500</button>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-primary w-100 amount-btn" data-amount="1000">₹1000</button>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-primary w-100 amount-btn" data-amount="2000">₹2000</button>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-primary w-100 amount-btn" data-amount="5000">₹5000</button>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-primary w-100 amount-btn" data-amount="10000">₹10000</button>
                                    </div>
                                    <div class="col-4">
                                        <input type="number" class="form-control" id="customAmount" placeholder="Custom">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="donorName" required>
                            </div>
                            
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="donorEmail" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Mobile *</label>
                                    <input type="tel" class="form-control" id="donorMobile" required>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Purpose (Optional)</label>
                                <select class="form-select" id="purpose">
                                    <option value="">Select Purpose</option>
                                    <option value="General">General Donation</option>
                                    <option value="Education">Education Support</option>
                                    <option value="Healthcare">Healthcare Initiative</option>
                                    <option value="Infrastructure">Infrastructure Development</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            
                            <button type="button" id="donateBtn" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-heart me-2"></i>Donate Now
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Bank Transfer Details -->
                <div class="card shadow mt-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-university me-2"></i>
                            Bank Transfer Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">You can also donate via direct bank transfer</p>
                        <table class="table table-bordered">
                            <tr>
                                <td width="40%"><strong>Bank Name:</strong></td>
                                <td><?php echo BANK_NAME; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Account Name:</strong></td>
                                <td><?php echo BANK_ACCOUNT_NAME; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Account Number:</strong></td>
                                <td><strong><?php echo BANK_ACCOUNT_NUMBER; ?></strong></td>
                            </tr>
                            <tr>
                                <td><strong>IFSC Code:</strong></td>
                                <td><strong><?php echo BANK_IFSC; ?></strong></td>
                            </tr>
                        </table>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Please email the transaction receipt to <?php echo SITE_EMAIL; ?>
                        </small>
                    </div>
                </div>
            </div>
            
            <!-- Why Donate -->
            <div class="col-lg-5">
                <div class="feature-card sticky-top" style="top: 100px;">
                    <h4 class="mb-4">Why Your Donation Matters</h4>
                    
                    <div class="mb-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="feature-icon me-3" style="width: 50px; height: 50px; font-size: 20px;">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div>
                                <h6>Education Support</h6>
                                <p class="text-muted small mb-0">
                                    Help underprivileged children access quality education
                                </p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start mb-3">
                            <div class="feature-icon me-3" style="width: 50px; height: 50px; font-size: 20px;">
                                <i class="fas fa-heartbeat"></i>
                            </div>
                            <div>
                                <h6>Healthcare</h6>
                                <p class="text-muted small mb-0">
                                    Support health camps and medical assistance programs
                                </p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start mb-3">
                            <div class="feature-icon me-3" style="width: 50px; height: 50px; font-size: 20px;">
                                <i class="fas fa-home"></i>
                            </div>
                            <div>
                                <h6>Infrastructure</h6>
                                <p class="text-muted small mb-0">
                                    Build community centers and support facilities
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-shield-alt me-2"></i>
                        <strong>100% Secure</strong><br>
                        <small>All donations are processed through secure payment gateway</small>
                    </div>
                    
                    <div class="alert alert-success">
                        <i class="fas fa-receipt me-2"></i>
                        <strong>Tax Benefits</strong><br>
                        <small>Donations are eligible for tax deduction under 80G</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Razorpay Integration -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
// Amount selection
document.querySelectorAll('.amount-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('customAmount').value = this.dataset.amount;
    });
});

// Donate button
document.getElementById('donateBtn').addEventListener('click', function() {
    const amount = document.getElementById('customAmount').value;
    const name = document.getElementById('donorName').value;
    const email = document.getElementById('donorEmail').value;
    const mobile = document.getElementById('donorMobile').value;
    const purpose = document.getElementById('purpose').value;
    
    // Validation
    if (!amount || amount < 100) {
        alert('Please enter amount (minimum ₹100)');
        return;
    }
    
    if (!name || !email || !mobile) {
        alert('Please fill all required fields');
        return;
    }
    
    // For demo - show success message
    // In production, integrate actual Razorpay
    if (confirm(`Donate ₹${amount}?\n\nNote: Payment gateway integration pending.\nThis is a demo.`)) {
        alert('Thank you for your generous donation!\n\nIn production, Razorpay payment will be processed here.');
        
        // Reset form
        document.getElementById('donationForm').reset();
        document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active'));
    }
    
    /* 
    // ACTUAL RAZORPAY INTEGRATION (Uncomment after adding keys)
    var options = {
        "key": "<?php echo RAZORPAY_KEY_ID; ?>",
        "amount": amount * 100, // Amount in paise
        "currency": "INR",
        "name": "<?php echo SITE_NAME; ?>",
        "description": purpose || "Donation",
        "image": "<?php echo IMAGE_URL; ?>logo.png",
        "prefill": {
            "name": name,
            "email": email,
            "contact": mobile
        },
        "theme": {
            "color": "#667eea"
        },
        "handler": function (response) {
            // Success
            alert('Payment Successful!\nPayment ID: ' + response.razorpay_payment_id);
            
            // Send to server to save donation
            fetch('save-donation.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    payment_id: response.razorpay_payment_id,
                    amount: amount,
                    name: name,
                    email: email,
                    mobile: mobile,
                    purpose: purpose
                })
            }).then(() => {
                window.location.href = 'payment-success.php';
            });
        }
    };
    
    var rzp = new Razorpay(options);
    rzp.open();
    */
});
</script>

<style>
.amount-btn.active {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    border-color: var(--primary-color);
}
</style>

<?php require_once '../includes/footer.php'; ?>
