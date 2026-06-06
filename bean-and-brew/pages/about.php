<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Page Hero -->
<section class="menu-hero">
    <h1 class="menu-hero-title" data-aos="fade-down">About <span>Us</span></h1>
</section>

<!-- Our Story -->
<section class="section">
    <div class="container">
        <div class="about-story">
            <div class="about-preview-img" data-aos="fade-right">
                <img src="https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?w=800&q=80" 
                     alt="Our Story">
            </div>
            <div class="about-story-content" data-aos="fade-left">
                <span class="section-subtitle">Our Story</span>
                <h2 class="section-title">From Bean to Cup, With Love</h2>
                <p class="about-text">
                    Bean & Brew Cafe started as a small dream in 2020, right in the heart of Colombo. 
                    Our founders — a group of coffee enthusiasts — wanted to create a place where 
                    every sip transports you to coffee paradise.
                </p>
                <p class="about-text">
                    We source our beans directly from the highlands of Sri Lanka and around the world, 
                    ensuring every cup is fresh, aromatic, and full of flavor. Our baristas are trained 
                    in the art of coffee making, and our kitchen serves only the freshest pastries 
                    baked daily.
                </p>
                <p class="about-text">
                    Today, Bean & Brew is more than just a cafe — it's a community. We believe in 
                    sustainability, fair trade, and making a positive impact on every life we touch.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="section" style="background: var(--bg-section);">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-subtitle">Meet The Team</span>
            <h2 class="section-title">The People Behind The Brew</h2>
        </div>
        
        <div class="team-grid">
            <div class="team-card" data-aos="fade-up" data-aos-delay="0">
                <div class="team-card-img">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&q=80" alt="Kasun">
                    <div class="team-card-social">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="team-card-body">
                    <h3 class="team-card-name">Kasun Perera</h3>
                    <p class="team-card-role">Founder & Head Barista</p>
                </div>
            </div>
            
            <div class="team-card" data-aos="fade-up" data-aos-delay="100">
                <div class="team-card-img">
                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400&q=80" alt="Amara">
                    <div class="team-card-social">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="team-card-body">
                    <h3 class="team-card-name">Amara Fernando</h3>
                    <p class="team-card-role">Head Chef</p>
                </div>
            </div>
            
            <div class="team-card" data-aos="fade-up" data-aos-delay="200">
                <div class="team-card-img">
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&q=80" alt="Dinesh">
                    <div class="team-card-social">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="team-card-body">
                    <h3 class="team-card-name">Dinesh Raj</h3>
                    <p class="team-card-role">Operations Manager</p>
                </div>
            </div>
            
            <div class="team-card" data-aos="fade-up" data-aos-delay="300">
                <div class="team-card-img">
                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=400&q=80" alt="Thilini">
                    <div class="team-card-social">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="team-card-body">
                    <h3 class="team-card-name">Thilini Jayawardena</h3>
                    <p class="team-card-role">Marketing Director</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-subtitle">Purpose</span>
            <h2 class="section-title">Mission & Vision</h2>
        </div>
        
        <div class="mv-grid">
            <div class="mv-card" data-aos="fade-up" data-aos-delay="0">
                <div class="mv-card-icon">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3>Our Mission</h3>
                <p>To craft exceptional coffee experiences that bring people together, 
                support sustainable farming, and create a warm community space in the heart of Sri Lanka.</p>
            </div>
            
            <div class="mv-card" data-aos="fade-up" data-aos-delay="100">
                <div class="mv-card-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <h3>Our Vision</h3>
                <p>To become Sri Lanka's most loved coffee brand, known for quality, 
                innovation, and our commitment to making every moment special.</p>
            </div>
            
            <div class="mv-card" data-aos="fade-up" data-aos-delay="200">
                <div class="mv-card-icon">
                    <i class="fas fa-gem"></i>
                </div>
                <h3>Our Values</h3>
                <p>Quality above all, sustainability in practice, community at heart, 
                and innovation in every cup we serve.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>