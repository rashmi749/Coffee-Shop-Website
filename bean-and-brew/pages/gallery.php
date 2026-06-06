<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="menu-hero">
    <h1 class="menu-hero-title" data-aos="fade-down">Our <span>Gallery</span></h1>
</section>

<section class="section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-subtitle">Visual Journey</span>
            <h2 class="section-title">Inside Bean & Brew</h2>
            <p class="section-desc">Take a peek at our cozy space and delicious creations</p>
        </div>
        
        <div class="gallery-grid">
            <div class="gallery-item" data-aos="fade-up">
                <img src="https://images.unsplash.com/photo-1559925393-8be0ec4767c8?w=600&q=80" alt="Cafe Interior">
                <div class="gallery-item-overlay">
                    <div class="gallery-item-info">
                        <h4>Main Dining Area</h4>
                        <p>Cozy & Warm Ambiance</p>
                    </div>
                </div>
            </div>
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="50">
                <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=600&q=80" alt="Coffee Art">
                <div class="gallery-item-overlay">
                    <div class="gallery-item-info">
                        <h4>Latte Art</h4>
                        <p>Crafted by Our Baristas</p>
                    </div>
                </div>
            </div>
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="100">
                <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=600&q=80" alt="Coffee Cup">
                <div class="gallery-item-overlay">
                    <div class="gallery-item-info">
                        <h4>Morning Brew</h4>
                        <p>Start Your Day Right</p>
                    </div>
                </div>
            </div>
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="150">
                <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?w=600&q=80" alt="Pastries">
                <div class="gallery-item-overlay">
                    <div class="gallery-item-info">
                        <h4>Fresh Pastries</h4>
                        <p>Baked Daily</p>
                    </div>
                </div>
            </div>
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="200">
                <img src="https://images.unsplash.com/photo-1442512595331-e89e73853f31?w=600&q=80" alt="Coffee Beans">
                <div class="gallery-item-overlay">
                    <div class="gallery-item-info">
                        <h4>Premium Beans</h4>
                        <p>Sourced from Highland</p>
                    </div>
                </div>
            </div>
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="250">
                <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=600&q=80" alt="Cafe Exterior">
                <div class="gallery-item-overlay">
                    <div class="gallery-item-info">
                        <h4>Our Storefront</h4>
                        <p>Galle Road, Colombo</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>