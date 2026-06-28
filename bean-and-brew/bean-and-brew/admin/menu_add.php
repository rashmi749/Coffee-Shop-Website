<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

if (isset($_POST['add_item'])) {
    $name = sanitize($_POST['name']);
    $category = sanitize($_POST['category']);
    $description = sanitize($_POST['description']);
    $price = (float)$_POST['price'];
    $imageAlt = sanitize($_POST['image_alt'] ?? '');
    $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
    $isAvailable = isset($_POST['is_available']) ? 1 : 0;

    $imageName = 'default.jpg';

    // Upload file
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $result = uploadImage('product_image', MENU_UPLOAD_PATH);
        if ($result['success']) $imageName = $result['filename'];
    }

    // Or external URL
    if ($imageName === 'default.jpg' && !empty($_POST['external_image_url'])) {
        $url = trim($_POST['external_image_url']);
        if (filter_var($url, FILTER_VALIDATE_URL)) $imageName = $url;
    }

    $stmt = $conn->prepare("INSERT INTO menu_items (name, category, description, price, image, image_alt, is_featured, is_available) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdssii", $name, $category, $description, $price, $imageName, $imageAlt, $isFeatured, $isAvailable);

    if ($stmt->execute()) {
        setFlash('success', "✅ '$name' added successfully!");
        header("Location: " . SITE_URL . "/admin/menu_manage.php");
        exit();
    } else {
        setFlash('error', 'Failed to add item.');
    }
    $stmt->close();
}

$adminName = $_SESSION['admin_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Menu Item | <?= SITE_NAME ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin.css">
    <style>
        .img-upload-area { border: 3px dashed #C9B88A; border-radius: 12px; padding: 30px; text-align: center; cursor: pointer; transition: 0.3s; background: #FDF8ED; }
        .img-upload-area:hover { border-color: #8B6F47; }
        .img-upload-area i { font-size: 3rem; color: #8B6F47; margin-bottom: 10px; }
        .img-upload-area p { color: #8B7D6B; font-size: 0.9rem; }
        .img-preview { max-width: 300px; max-height: 200px; border-radius: 8px; margin-top: 15px; display: none; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <div class="sidebar-header"><a href="<?= SITE_URL ?>" class="nav-logo"><i class="fas fa-mug-hot"></i><span>Bean & Brew</span></a></div>
        <nav class="sidebar-nav">
            <a href="<?= SITE_URL ?>/admin/index.php" class="sidebar-nav-item"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
            <a href="<?= SITE_URL ?>/admin/orders.php" class="sidebar-nav-item"><i class="fas fa-shopping-cart"></i><span>Orders</span></a>
            <a href="<?= SITE_URL ?>/admin/menu_manage.php" class="sidebar-nav-item active"><i class="fas fa-utensils"></i><span>Menu Items</span></a>
            <a href="<?= SITE_URL ?>/admin/gallery_manage.php" class="sidebar-nav-item"><i class="fas fa-images"></i><span>Gallery</span></a>
            <a href="<?= SITE_URL ?>/admin/contacts.php" class="sidebar-nav-item"><i class="fas fa-envelope"></i><span>Messages</span></a>
            <a href="<?= SITE_URL ?>/" class="sidebar-nav-item" target="_blank"><i class="fas fa-globe"></i><span>View Site</span></a>
            <a href="<?= SITE_URL ?>/admin/logout.php" class="sidebar-nav-item"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
        </nav>
    </aside>

    <main class="admin-main">
        <div class="admin-topbar">
            <h2><i class="fas fa-plus-circle"></i> Add Menu Item</h2>
            <a href="menu_manage.php" class="btn btn-sm btn-dark"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
        <div class="admin-content">
            <?= getFlash() ?>
            <div class="admin-form" style="max-width:800px;">
                <h3><i class="fas fa-utensils"></i> Product Details</h3>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label><i class="fas fa-image"></i> Product Image</label>
                        <div class="img-upload-area" onclick="document.getElementById('pImg').click();">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Click to upload image (JPG, PNG, WebP — Max 5MB)</p>
                            <img id="imgPrev" class="img-preview" alt="Preview">
                        </div>
                        <input type="file" id="pImg" name="product_image" accept="image/*" style="display:none;" onchange="var p=document.getElementById('imgPrev');if(this.files[0]){var r=new FileReader();r.onload=function(e){p.src=e.target.result;p.style.display='block';};r.readAsDataURL(this.files[0]);}">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-link"></i> Or External Image URL</label>
                        <input type="url" name="external_image_url" class="form-control" placeholder="https://example.com/image.jpg">
                    </div>
                    <hr style="border-color:#F5EFE0; margin:25px 0;">
                    <div class="form-row">
                        <div class="form-group"><label>Item Name *</label><input type="text" name="name" class="form-control" placeholder="e.g., Caramel Latte" required></div>
                        <div class="form-group"><label>Category *</label><select name="category" class="form-control" required><option value="">Select</option><option value="coffee">☕ Coffee</option><option value="tea">🍵 Tea</option><option value="cakes">🎂 Cakes</option><option value="snacks">🍪 Snacks</option></select></div>
                    </div>
                    <div class="form-group"><label>Description *</label><textarea name="description" class="form-control" rows="3" placeholder="Item description..." required></textarea></div>
                    <div class="form-row">
                        <div class="form-group"><label>Price (LKR) *</label><input type="number" name="price" class="form-control" step="0.01" min="0" placeholder="650.00" required></div>
                        <div class="form-group"><label>Image Alt Text</label><input type="text" name="image_alt" class="form-control" placeholder="SEO alt text"></div>
                    </div>
                    <div style="display:flex; gap:20px; margin:20px 0; flex-wrap:wrap;">
                        <label style="display:flex; align-items:center; gap:10px; padding:12px 20px; background:#FDF8ED; border-radius:8px; cursor:pointer;"><input type="checkbox" name="is_available" checked style="width:20px; height:20px; accent-color:#8B6F47;"> Available</label>
                        <label style="display:flex; align-items:center; gap:10px; padding:12px 20px; background:#FDF8ED; border-radius:8px; cursor:pointer;"><input type="checkbox" name="is_featured" style="width:20px; height:20px; accent-color:#D4A843;"> Featured</label>
                    </div>
                    <button type="submit" name="add_item" class="btn btn-dark btn-lg" style="width:100%;"><i class="fas fa-save"></i> Save Item</button>
                </form>
            </div>
        </div>
    </main>
</div>
</body>
</html>