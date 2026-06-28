<?php
// ============================================
// Bean & Brew Cafe - Configuration
// ============================================

// Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bean_and_brew');

// Site
define('SITE_NAME', 'Bean & Brew Cafe');
define('SITE_URL', 'http://localhost/bean-and-brew');
define('SITE_EMAIL', 'info@beanandbrew.lk');
define('SITE_PHONE', '+94 11 234 5678');
define('SITE_ADDRESS', '42 Galle Road, Colombo 03, Sri Lanka');

// PayHere
define('PAYHERE_MERCHANT_ID', 'YOUR_MERCHANT_ID');
define('PAYHERE_MERCHANT_SECRET', 'YOUR_MERCHANT_SECRET');
define('PAYHERE_MODE', 'sandbox');
define('PAYHERE_RETURN_URL', SITE_URL . '/payment_callback.php?status=success');
define('PAYHERE_CANCEL_URL', SITE_URL . '/payment_callback.php?status=cancel');
define('PAYHERE_NOTIFY_URL', SITE_URL . '/payment_callback.php?status=ipn');

// Image Upload
define('UPLOAD_PATH', dirname(__DIR__) . '/uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');
define('MENU_UPLOAD_PATH', UPLOAD_PATH . 'menu/');
define('GALLERY_UPLOAD_PATH', UPLOAD_PATH . 'gallery/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024);
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// Create upload dirs
foreach (['uploads', 'uploads/menu', 'uploads/gallery'] as $dir) {
    $fullPath = dirname(__DIR__) . '/' . $dir;
    if (!file_exists($fullPath)) {
        mkdir($fullPath, 0755, true);
    }
}

// Database Connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// Session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>