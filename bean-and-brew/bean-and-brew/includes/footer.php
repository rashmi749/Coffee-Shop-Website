<?php
// ============================================
// Bean & Brew Cafe - Footer Template
// ============================================
?>

<!-- ========== FOOTER ========== -->
<footer class="footer">
    <div class="footer-wave">
        <svg viewBox="0 0 1440 120" preserveAspectRatio="none">
            <path d="M0,64L48,69.3C96,75,192,85,288,80C384,75,480,53,576,48C672,43,768,53,864,64C960,75,1056,85,1152,80C1248,75,1344,53,1392,42.7L1440,32V120H0Z" fill="#1a0e05"></path>
        </svg>
    </div>
    
    <div class="footer-content">
        <div class="container">
            <div class="footer-grid">
                <!-- About Column -->
                <div class="footer-col" data-aos="fade-up">
                    <div class="footer-logo">
                        <i class="fas fa-mug-hot"></i>
                        <h3>Bean & Brew</h3>
                    </div>
                    <p>Sri Lanka's premium coffee destination. Crafting perfect cups since 2020. Every sip tells a story.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="footer-col" data-aos="fade-up" data-aos-delay="100">
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="<?= SITE_URL ?>">Home</a></li>
                        <li><a href="<?= SITE_URL ?>/pages/menu.php">Our Menu</a></li>
                        <li><a href="<?= SITE_URL ?>/pages/about.php">About Us</a></li>
                        <li><a href="<?= SITE_URL ?>/pages/gallery.php">Gallery</a></li>
                        <li><a href="<?= SITE_URL ?>/pages/contact.php">Contact</a></li>
                    </ul>
                </div>
                
                <!-- Opening Hours -->
                <div class="footer-col" data-aos="fade-up" data-aos-delay="200">
                    <h4>Opening Hours</h4>
                    <ul class="footer-hours">
                        <li><span>Monday - Friday</span><span>7:00 AM - 10:00 PM</span></li>
                        <li><span>Saturday</span><span>8:00 AM - 11:00 PM</span></li>
                        <li><span>Sunday</span><span>8:00 AM - 9:00 PM</span></li>
                        <li><span>Public Holidays</span><span>9:00 AM - 8:00 PM</span></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div class="footer-col" data-aos="fade-up" data-aos-delay="300">
                    <h4>Contact Us</h4>
                    <ul class="footer-contact">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>42 Galle Road, Colombo 03, Sri Lanka</span>
                        </li>
                        <li>
                            <i class="fas fa-phone-alt"></i>
                            <span>+94 11 234 5678</span>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span>info@beanandbrew.lk</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="footer-bottom">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= SITE_NAME ?>. All rights reserved. Made with <i class="fas fa-heart"></i> in Sri Lanka.</p>
        </div>
    </div>
</footer>

<!-- ========== BACK TO TOP ========== -->
<a href="#" class="back-to-top" id="backToTop">
    <i class="fas fa-arrow-up"></i>
</a>

<!-- AOS JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- Main JS -->
<script src="<?= SITE_URL ?>/assets/js/script.js"></script>

</body>
</html>