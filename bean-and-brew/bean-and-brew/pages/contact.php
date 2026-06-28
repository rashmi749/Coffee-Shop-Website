<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="menu-hero">
    <h1 class="menu-hero-title" data-aos="fade-down">Contact <span>Us</span></h1>
</section>

<section class="section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-subtitle">Get In Touch</span>
            <h2 class="section-title">We'd Love To Hear From You</h2>
        </div>
        
        <div class="contact-section">
            <!-- Contact Info -->
            <div data-aos="fade-right">
                <h3 style="font-family: var(--font-heading); color: var(--secondary); margin-bottom: 25px;">
                    Contact Information
                </h3>
                
                <div class="contact-info-list">
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-info-text">
                            <h4>Visit Us</h4>
                            <p>42 Galle Road, Colombo 03,<br>Sri Lanka</p>
                        </div>
                    </div>
                    
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-info-text">
                            <h4>Call Us</h4>
                            <a href="tel:+94112345678">+94 11 234 5678</a><br>
                            <a href="tel:+94771234567">+94 77 123 4567</a>
                        </div>
                    </div>
                    
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-info-text">
                            <h4>Email Us</h4>
                            <a href="mailto:info@beanandbrew.lk">info@beanandbrew.lk</a><br>
                            <a href="mailto:orders@beanandbrew.lk">orders@beanandbrew.lk</a>
                        </div>
                    </div>
                    
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-info-text">
                            <h4>Opening Hours</h4>
                            <p>Mon - Fri: 7:00 AM - 10:00 PM<br>
                               Sat - Sun: 8:00 AM - 11:00 PM</p>
                        </div>
                    </div>
                </div>
                
                <!-- Google Map -->
                <div class="map-container">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63371.81615857231!2d79.8211856!3d6.9218386!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae253d10f7a7003%3A0x320b2e4d32d3838d!2sColombo%2C%20Sri%20Lanka!5e0!3m2!1sen!2s!4v1700000000000!5m2!1sen!2s" 
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="contact-form" data-aos="fade-left">
                <h3 style="font-family: var(--font-heading); color: var(--secondary); margin-bottom: 5px;">
                    Send Us a Message
                </h3>
                <p style="color: var(--text-light); margin-bottom: 25px; font-size: 0.9rem;">
                    Fill out the form and we'll get back to you within 24 hours.
                </p>
                
                <?= getFlash() ?>
                
                <form action="<?= SITE_URL ?>/contact_process.php" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" class="form-control" 
                                   placeholder="Your name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" class="form-control" 
                                   placeholder="your@email.com" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="tel" id="phone" name="phone" class="form-control" 
                                   placeholder="+94 XX XXX XXXX">
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject *</label>
                            <input type="text" id="subject" name="subject" class="form-control" 
                                   placeholder="How can we help?" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" class="form-control" 
                                  placeholder="Write your message here..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-dark btn-lg" style="width: 100%;">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>