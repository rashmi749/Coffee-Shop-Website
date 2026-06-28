<?php
// ============================================
// Bean & Brew Cafe - Menu Page
// ============================================
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Menu Hero -->
<section class="menu-hero">
    <h1 class="menu-hero-title" data-aos="fade-down">Our <span>Menu</span></h1>
</section>

<!-- Menu Section -->
<section class="section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-subtitle">Explore Our Flavors</span>
            <h2 class="section-title">Crafted With Love</h2>
            <p class="section-desc">Every item is prepared with the finest ingredients and attention to detail</p>
        </div>
        
        <!-- Filter Buttons -->
        <div class="menu-filter" data-aos="fade-up">
            <button class="menu-filter-btn active" data-category="all">
                <i class="fas fa-th"></i> All
            </button>
            <button class="menu-filter-btn" data-category="coffee">
                <i class="fas fa-mug-hot"></i> Coffee
            </button>
            <button class="menu-filter-btn" data-category="tea">
                <i class="fas fa-leaf"></i> Tea
            </button>
            <button class="menu-filter-btn" data-category="cakes">
                <i class="fas fa-birthday-cake"></i> Cakes
            </button>
            <button class="menu-filter-btn" data-category="snacks">
                <i class="fas fa-cookie-bite"></i> Snacks
            </button>
        </div>
        
        <!-- Menu Items Grid -->
        <div class="menu-grid">
            <?php
            $menuItems = $conn->query("SELECT * FROM menu_items WHERE is_available = 1 ORDER BY category, name");
            $imgIndex = 0;
            $unsplashImages = [
                'coffee' => [
                    'https://images.unsplash.com/photo-1510707577719-ae7c14805e3a?w=600&q=80',
                    'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=600&q=80',
                    'https://images.unsplash.com/photo-1534778101976-62847782c213?w=600&q=80',
                    'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=600&q=80',
                    'https://images.unsplash.com/photo-1514432324607-a09d9b4aefda?w=600&q=80',
                    'https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?w=600&q=80',
                ],
                'tea' => [
                    'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=600&q=80',
                    'https://images.unsplash.com/photo-1558618666-fcd25c85f82e?w=600&q=80',
                    'https://images.unsplash.com/photo-1597318181409-cf64d0b5d8a2?w=600&q=80',
                    'https://images.unsplash.com/photo-1571934811356-5cc061b6221f?w=600&q=80',
                ],
                'cakes' => [
                    'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?w=600&q=80',
                    'https://images.unsplash.com/photo-1567171466295-4afa63d45416?w=600&q=80',
                    'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=600&q=80',
                    'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&q=80',
                ],
                'snacks' => [
                    'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=600&q=80',
                    'https://images.unsplash.com/photo-1555507036-ab1f4038024a?w=600&q=80',
                    'https://images.unsplash.com/photo-1607958996333-41aef7caefaa?w=600&q=80',
                    'https://images.unsplash.com/photo-1541519227354-08fa5d50c44d?w=600&q=80',
                ],
            ];
            $catCounters = ['coffee' => 0, 'tea' => 0, 'cakes' => 0, 'snacks' => 0];
            
            while ($item = $menuItems->fetch_assoc()):
                $cat = $item['category'];
                $idx = $catCounters[$cat] % count($unsplashImages[$cat]);
                $imgUrl = $unsplashImages[$cat][$idx];
                $catCounters[$cat]++;
            ?>
            <div class="menu-item" data-category="<?= $cat ?>" data-aos="fade-up">
                <div class="menu-item-img">
                    <img src="<?= $imgUrl ?>" alt="<?= $item['name'] ?>">
                    <div class="menu-item-overlay">
                        <i class="fas fa-plus"></i>
                    </div>
                </div>
                <div class="menu-item-body">
                    <span class="menu-item-category"><?= ucfirst($cat) ?></span>
                    <h3 class="menu-item-name"><?= $item['name'] ?></h3>
                    <p class="menu-item-desc"><?= $item['description'] ?></p>
                    <div class="menu-item-bottom">
                        <span class="menu-item-price"><?= formatPrice($item['price']) ?></span>
                        <a href="<?= SITE_URL ?>/pages/order.php" class="btn btn-sm btn-primary">
                            <i class="fas fa-cart-plus"></i> Order
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>