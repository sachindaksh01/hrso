    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-hands-helping me-2"></i><?php echo SITE_NAME; ?></h5>
                    <p class="text-white-50">
                        Empowering communities across India through social welfare, 
                        education, and development initiatives.
                    </p>
                    <div class="social-links mt-3">
                        <a href="#" class="me-3"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#"><i class="fab fa-linkedin fa-lg"></i></a>
                    </div>
                </div>
                
                <div class="col-md-2 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>public/about.php">About Us</a></li>
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>public/aims.php">Our Aims</a></li>
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>public/members.php">Members</a></li>
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>public/gallery.php">Gallery</a></li>
                    </ul>
                </div>
                
                <div class="col-md-2 mb-4">
                    <h5>Get Involved</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>public/join.php">Join Membership</a></li>
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>public/donate.php">Donate</a></li>
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>public/contact.php">Contact Us</a></li>
                    </ul>
                </div>
                
                <div class="col-md-4 mb-4">
                    <h5>Contact Info</h5>
                    <ul class="list-unstyled text-white-50">
                        <li class="mb-2">
                            <i class="fas fa-envelope me-2"></i>
                            <?php echo SITE_EMAIL; ?>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-phone me-2"></i>
                            <?php echo CONTACT_PHONE; ?>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            New Delhi, India
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom text-center text-white-50">
                <p class="mb-0">
                    © <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All Rights Reserved. 
                    | <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a>
                </p>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (optional) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?php echo ASSETS_URL; ?>js/main.js"></script>
</body>
</html>
