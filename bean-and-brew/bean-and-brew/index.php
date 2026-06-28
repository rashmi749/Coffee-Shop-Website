<?php
// ============================================
// Bean & Brew Cafe - Home Page
// ============================================
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';
?>

<!-- ========== HERO SECTION ========== -->
<section class="hero">
    <div class="hero-slider">
        <div class="hero-slide active">
            <img src="https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=1920&q=80" alt="Coffee beans">
        </div>
        <div class="hero-slide">
            <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1920&q=80" alt="Coffee cup">
        </div>
        <div class="hero-slide">
            <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=1920&q=80" alt="Latte art">
        </div>
    </div>
    <div class="hero-overlay"></div>
    
    <div class="hero-content">
        <div class="hero-badge">
            <i class="fas fa-leaf"></i> 100% Premium Arabica Beans
        </div>
        <h1 class="hero-title">
            Crafting The <span>Perfect</span><br>Cup Since 2020
        </h1>
        <p class="hero-desc">
            Welcome to Bean & Brew Cafe — where every cup tells a story. 
            Experience Sri Lanka's finest coffee, handcrafted with love.
        </p>
        <div class="hero-buttons">
            <a href="<?= SITE_URL ?>/pages/order.php" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-bag"></i> Order Now
            </a>
            <a href="<?= SITE_URL ?>/pages/menu.php" class="btn btn-secondary btn-lg">
                <i class="fas fa-book-open"></i> View Menu
            </a>
        </div>
        
        <div class="hero-stats">
            <div class="hero-stat">
                <h3><span class="counter" data-target="5000">0</span>+</h3>
                <p>Cups Served Monthly</p>
            </div>
            <div class="hero-stat">
                <h3><span class="counter" data-target="15">0</span>+</h3>
                <p>Coffee Varieties</p>
            </div>
            <div class="hero-stat">
                <h3><span class="counter" data-target="4">0</span>.8 ★</h3>
                <p>Customer Rating</p>
            </div>
        </div>
    </div>
    
    <a href="#featured" class="hero-scroll">
        <span>Scroll Down</span>
        <i class="fas fa-chevron-down"></i>
    </a>
</section>

<!-- ========== FEATURED PRODUCTS ========== -->
<section class="featured-section section" id="featured">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-subtitle">Our Specialties</span>
            <h2 class="section-title">Featured Products</h2>
            <p class="section-desc">Hand-picked favorites that keep our customers coming back for more</p>
        </div>
        
        <div class="featured-grid">
            <?php
            $featured = $conn->query("SELECT * FROM menu_items WHERE is_available = 1 ORDER BY RAND() LIMIT 4");
            while ($item = $featured->fetch_assoc()):
            ?>
            <div class="featured-card" data-aos="fade-up" data-aos-delay="<?= $featured->current_row * 100 ?>">
                <div class="featured-card-img">
                    <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=600&q=80" 
                         alt="<?= $item['name'] ?>">
                    <span class="featured-card-badge"><?= ucfirst($item['category']) ?></span>
                </div>
                <div class="featured-card-body">
                    <p class="featured-card-category"><?= ucfirst($item['category']) ?></p>
                    <h3 class="featured-card-title"><?= $item['name'] ?></h3>
                    <p class="featured-card-desc"><?= substr($item['description'], 0, 80) ?>...</p>
                    <div class="featured-card-footer">
                        <span class="featured-card-price"><?= formatPrice($item['price']) ?></span>
                        <a href="<?= SITE_URL ?>/pages/order.php" class="featured-card-btn">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        
        <div style="text-align: center; margin-top: 50px;" data-aos="fade-up">
            <a href="<?= SITE_URL ?>/pages/menu.php" class="btn btn-dark btn-lg">
                <i class="fas fa-book-open"></i> View Full Menu
            </a>
        </div>
    </div>
</section>

<!-- ========== ABOUT PREVIEW ========== -->
<section class="about-preview section">
    <div class="container">
        <div class="about-preview-grid">
            <div class="about-preview-img" data-aos="fade-right">
                <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=800&q=80" 
                     alt="Bean & Brew Cafe Interior">
                <div class="about-experience-badge">
                    <h3>5+</h3>
                    <p>Years</p>
                </div>
            </div>
            
            <div class="about-preview-content" data-aos="fade-left">
                <span class="section-subtitle">About Us</span>
                <h2 class="section-title">A Story Brewed With Passion</h2>
                <p class="about-text">
                    Nestled in the heart of Colombo, Bean & Brew Cafe was born from a simple dream — 
                    to create a space where great coffee meets warm hospitality. Our journey began in 2020, 
                    and today we're proud to be one of Sri Lanka's beloved coffee destinations.
                </p>
                <div class="about-features">
                    <div class="about-feature">
                        <i class="fas fa-coffee"></i>
                        <span>Premium Arabica Beans</span>
                    </div>
                    <div class="about-feature">
                        <i class="fas fa-leaf"></i>
                        <span>Fresh & Organic</span>
                    </div>
                    <div class="about-feature">
                        <i class="fas fa-heart"></i>
                        <span>Made With Love</span>
                    </div>
                    <div class="about-feature">
                        <i class="fas fa-truck"></i>
                        <span>Island-wide Delivery</span>
                    </div>
                </div>
                <a href="<?= SITE_URL ?>/pages/about.php" class="btn btn-dark">
                    Learn More <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ========== PARALLAX CTA ========== -->
<section class="parallax-section" style="background-image: url('https://images.unsplash.com/photo-1442512595331-e89e73853f31?w=1920&q=80');">
    <div class="parallax-overlay"></div>
    <div class="parallax-content" data-aos="zoom-in">
        <h2>Your Perfect Cup Awaits</h2>
        <p>Order online and get it delivered to your doorstep anywhere in Sri Lanka</p>
        <a href="<?= SITE_URL ?>/pages/order.php" class="btn btn-primary btn-lg">
            <i class="fas fa-shopping-bag"></i> Start Your Order
        </a>
    </div>
</section>

<!-- ========== TESTIMONIALS ========== -->
<section class="testimonials-section section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-subtitle" style="color: var(--gold);">Testimonials</span>
            <h2 class="section-title">What Our Customers Say</h2>
            <p class="section-desc">Don't just take our word for it — hear from our happy customers</p>
        </div>
        
        <div class="testimonials-slider">
            <div class="testimonial-card" data-aos="fade-up" data-aos-delay="0">
                <div class="testimonial-stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="testimonial-text">
                    "The best coffee I've had in Colombo! The cappuccino is absolutely divine, 
                    and the atmosphere is so relaxing. My go-to place for work meetings."
                </p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">NK</div>
                    <div class="testimonial-info">
                        <h4>Nadeesha Kumari</h4>
                        <p>Regular Customer</p>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card" data-aos="fade-up" data-aos-delay="100">
                <div class="testimonial-stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="testimonial-text">
                    "Bean & Brew has the freshest cakes and the most aromatic coffee. 
                    I love their tiramisu! The online ordering makes it so convenient."
                </p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">AS</div>
                    <div class="testimonial-info">
                        <h4>Arun Silva</h4>
                        <p>Food Blogger</p>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card" data-aos="fade-up" data-aos-delay="200">
                <div class="testimonial-stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <p class="testimonial-text">
                    "A perfect blend of great coffee, amazing pastries, and wonderful ambiance. 
                    The staff is incredibly friendly. Highly recommend the caramel latte!"
                </p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">PR</div>
                    <div class="testimonial-info">
                        <h4>Pradeep Rajapaksa</h4>
                        <p>Coffee Enthusiast</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>