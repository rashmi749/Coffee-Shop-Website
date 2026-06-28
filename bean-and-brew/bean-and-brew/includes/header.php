<?php
// ============================================
// Bean & Brew Cafe - Header Template
// ============================================
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
if (isset($_GET['page'])) {
    $currentPage = $_GET['page'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Bean & Brew Cafe - Premium coffee experience in Colombo, Sri Lanka. Freshly brewed coffee, delicious cakes, and more.">
    <meta name="keywords" content="coffee, cafe, Colombo, Sri Lanka, espresso, cappuccino, bakery">
    <title><?= SITE_NAME ?> | Premium Coffee Experience</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
</head>
<body>

<!-- ========== NAVBAR ========== -->
<nav class="navbar" id="navbar">
    <div class="nav-container">
        <a href="<?= SITE_URL ?>" class="nav-logo">
            <i class="fas fa-mug-hot"></i>
            <span>Bean & Brew</span>
        </a>
        
        <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
            <span></span>
            <span></span>
            <span></span>
        </button>
        
        <ul class="nav-menu" id="navMenu">
            <li><a href="<?= SITE_URL ?>" class="nav-link <?= ($currentPage === 'home' || $currentPage === 'index') ? 'active' : '' ?>">Home</a></li>
            <li><a href="<?= SITE_URL ?>/pages/menu.php" class="nav-link <?= ($currentPage === 'menu') ? 'active' : '' ?>">Menu</a></li>
            <li><a href="<?= SITE_URL ?>/pages/about.php" class="nav-link <?= ($currentPage === 'about') ? 'active' : '' ?>">About</a></li>
            <li><a href="<?= SITE_URL ?>/pages/gallery.php" class="nav-link <?= ($currentPage === 'gallery') ? 'active' : '' ?>">Gallery</a></li>
            <li><a href="<?= SITE_URL ?>/pages/contact.php" class="nav-link <?= ($currentPage === 'contact') ? 'active' : '' ?>">Contact</a></li>
            <li><a href="<?= SITE_URL ?>/pages/order.php" class="nav-link nav-order-btn <?= ($currentPage === 'order') ? 'active' : '' ?>">Order Now</a></li>
        </ul>

        <!-- Cart + Auth Buttons -->
        <div class="nav-auth">
            <a href="<?= SITE_URL ?>/pages/order.php" class="nav-cart-icon" title="Order Now">
                <i class="fas fa-shopping-cart"></i>
            </a>

            <?php if (isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id'])): ?>
                <a href="<?= SITE_URL ?>/admin/index.php" class="nav-btn-login">Dashboard</a>
                <a href="<?= SITE_URL ?>/admin/logout.php" class="nav-btn-outline">Logout</a>
            <?php else: ?>
                <a href="<?= SITE_URL ?>/admin/login.php" class="nav-btn-login">Login</a>
                <a href="<?= SITE_URL ?>/admin/login.php" class="nav-btn-register">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- ========== PRELOADER ========== -->
<div class="preloader" id="preloader">
    <div class="loader">
        <i class="fas fa-mug-hot"></i>
        <p>Brewing...</p>
    </div>
</div>
