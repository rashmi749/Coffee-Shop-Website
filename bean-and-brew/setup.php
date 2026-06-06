<?php
// ============================================
// SETUP SCRIPT - RUN THIS FIRST
// URL: http://localhost/bean-and-brew/setup.php
// ============================================

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>⏳ Setting up Bean & Brew database...</h2>";

$conn = new mysqli('localhost', 'root', '', '');
if ($conn->connect_error) {
    die("<p style='color:red;'>❌ MySQL connection failed: " . $conn->connect_error . "</p>");
}

// Create database
$conn->query("CREATE DATABASE IF NOT EXISTS bean_and_brew");
$conn->select_db("bean_and_brew");
$conn->set_charset("utf8mb4");

echo "<p>✅ Database created</p>";

// Users table
$conn->query("DROP TABLE IF EXISTS users");
$conn->query("CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Insert admin - password: admin123
$hash = password_hash('admin123', PASSWORD_DEFAULT);
$conn->query("INSERT INTO users (username, password, full_name, email) 
              VALUES ('admin', '$hash', 'Admin User', 'admin@beanandbrew.lk')");

echo "<p>✅ Admin user created (admin / admin123)</p>";

// Menu items table
$conn->query("DROP TABLE IF EXISTS menu_items");
$conn->query("CREATE TABLE menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category ENUM('coffee','tea','cakes','snacks') NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(500) DEFAULT 'default.jpg',
    image_alt VARCHAR(200) DEFAULT '',
    is_featured TINYINT(1) DEFAULT 0,
    is_available TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");

// Sample items
$items = [
    "('Espresso','coffee','Rich bold single shot espresso',450.00,'default.jpg','',0,1)",
    "('Cappuccino','coffee','Classic Italian coffee with steamed milk foam',550.00,'default.jpg','',1,1)",
    "('Caramel Latte','coffee','Smooth espresso with caramel and creamy steamed milk',650.00,'default.jpg','',1,1)",
    "('Iced Americano','coffee','Chilled espresso with cold water over ice',500.00,'default.jpg','',0,1)",
    "('Mocha','coffee','Espresso with chocolate and steamed milk',700.00,'default.jpg','',0,1)",
    "('Flat White','coffee','Velvety espresso with microfoam milk',600.00,'default.jpg','',0,1)",
    "('English Breakfast Tea','tea','Classic black tea blend from Ceylon highlands',350.00,'default.jpg','',0,1)",
    "('Green Tea','tea','Fresh and light Japanese green tea',400.00,'default.jpg','',0,1)",
    "('Chai Latte','tea','Spiced Indian chai with frothy milk',500.00,'default.jpg','',1,1)",
    "('Earl Grey','tea','Bergamot scented premium black tea',400.00,'default.jpg','',0,1)",
    "('Tiramisu Cake','cakes','Italian coffee-flavored layered cake',850.00,'default.jpg','',1,1)",
    "('Cheesecake','cakes','Creamy New York style cheesecake',750.00,'default.jpg','',0,1)",
    "('Chocolate Brownie','cakes','Rich dark chocolate brownie',600.00,'default.jpg','',0,1)",
    "('Carrot Cake','cakes','Moist carrot cake with cream cheese frosting',700.00,'default.jpg','',0,1)",
    "('Chicken Wrap','snacks','Grilled chicken with fresh veggies in tortilla',750.00,'default.jpg','',0,1)",
    "('Croissant','snacks','Buttery French croissant',450.00,'default.jpg','',1,1)",
    "('Muffin','snacks','Freshly baked blueberry muffin',350.00,'default.jpg','',0,1)",
    "('Avocado Toast','snacks','Sourdough toast with smashed avocado',650.00,'default.jpg','',0,1)"
];

$conn->query("INSERT INTO menu_items (name,category,description,price,image,image_alt,is_featured,is_available) VALUES " . implode(",", $items));
echo "<p>✅ Menu items added (" . count($items) . " items)</p>";

// Orders table
$conn->query("DROP TABLE IF EXISTS orders");
$conn->query("CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_address TEXT NOT NULL,
    items TEXT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50) DEFAULT 'cod',
    payment_status ENUM('pending','paid','failed') DEFAULT 'pending',
    order_status ENUM('pending','confirmed','preparing','delivered','cancelled') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");
echo "<p>✅ Orders table created</p>";

// Contacts table
$conn->query("DROP TABLE IF EXISTS contacts");
$conn->query("CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
echo "<p>✅ Contacts table created</p>";

// Gallery table
$conn->query("DROP TABLE IF EXISTS gallery");
$conn->query("CREATE TABLE gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    image VARCHAR(500) NOT NULL,
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
echo "<p>✅ Gallery table created</p>";

// Create upload folders
$dirs = [
    __DIR__ . '/uploads',
    __DIR__ . '/uploads/menu',
    __DIR__ . '/uploads/gallery'
];
foreach ($dirs as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
        echo "<p>✅ Folder created: " . basename($dir) . "</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Done!</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:Arial,sans-serif;background:#1a0e05;color:#E4D6A9;display:flex;align-items:center;justify-content:center;min-height:100vh;padding:20px}
        .card{background:#2d1f12;padding:40px;border-radius:20px;max-width:500px;width:100%;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,0.5)}
        h1{font-size:2rem;margin-bottom:20px}
        .box{background:rgba(228,214,169,0.1);padding:20px;border-radius:10px;text-align:left;margin:20px 0}
        .box p{margin:8px 0;font-size:0.95rem}
        .box strong{color:#D4A843}
        .btn{display:inline-block;padding:14px 30px;background:#E4D6A9;color:#1a0e05;border-radius:30px;text-decoration:none;font-weight:700;margin:8px;transition:0.3s}
        .btn:hover{transform:translateY(-3px);box-shadow:0 8px 20px rgba(228,214,169,0.3)}
        .btn.dark{background:transparent;color:#E4D6A9;border:2px solid #E4D6A9}
        .warn{margin-top:20px;padding:12px;background:rgba(255,0,0,0.15);border:1px solid rgba(255,0,0,0.3);border-radius:8px;font-size:0.82rem;color:#ff6b6b}
    </style>
</head>
<body>
    <div class="card">
        <h1>☕ Setup Complete!</h1>
        <p style="color:#4CAF50;font-size:1.1rem;margin-bottom:20px">✅ Everything is ready!</p>
        
        <div class="box">
            <p><strong>Admin URL:</strong></p>
            <p>http://localhost/bean-and-brew/admin/login.php</p>
            <br>
            <p><strong>Username:</strong> admin</p>
            <p><strong>Password:</strong> admin123</p>
        </div>
        
        <a href="admin/login.php" class="btn">🔑 Admin Login</a>
        <a href="./" class="btn dark">🌐 View Website</a>
        
        <div class="warn">
            ⚠️ මේ setup.php file එක delete කරන්න setup අවසන් උනාට පස්සේ!
        </div>
    </div>
</body>
</html>