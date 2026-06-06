<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$item = $conn->query("SELECT * FROM menu_items WHERE id = $id")->fetch_assoc();

if (!$item) {
    setFlash('error', 'Item not found.');
    header("Location: " . SITE_URL . "/admin/menu_manage.php");
    exit();
}

if (isset($_POST['update_item'])) {
    $name = sanitize($_POST['name']);
    $category = sanitize($_POST['category']);
    $description = sanitize($_POST['description']);
    $price = (float)$_POST['price'];
    $imageAlt = sanitize($_POST['image_alt'] ?? '');
    $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
    $isAvailable = isset($_POST['is_available']) ? 1 : 0;

    $newImage = $item['image'];

    // New upload?
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $result = uploadImage('product_image', MENU_UPLOAD_PATH, $item['image']);
        if ($result['success']) $newImage = $result['filename'];
    }

    // External URL?
    if (!empty($_POST['external_image_url'])) {
        $url = trim($_POST['external_image_url']);
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            if ($newImage !== $url) {
                deleteImage(MENU_UPLOAD_PATH, $item['image']);
                $newImage = $url;
            }
        }
    }

    $stmt = $conn->prepare("UPDATE menu_items SET name=?, category=?, description=?, price=?, image=?, image_alt=?, is_featured=?, is_available=? WHERE id=?");
    $stmt->bind_param("sssdssiii", $name, $category, $description, $price, $newImage, $imageAlt, $isFeatured, $isAvailable, $id);

    if ($stmt->execute()) {
        setFlash('success', "✅ '$name' updated!");
        header("Location: " . SITE_URL . "/admin/menu_manage.php");
        exit();
    } else {
        setFlash('error', 'Update failed.');
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
    <title>Edit: <?= $item['name'] ?> | <?= SITE_NAME ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin.css">
    <style>
        .img-upload-area { border: 3px dashed #C9B88A; border-radius: 12px; padding: 25px; text-align: center; cursor: pointer; background: #FDF8ED; transition: 0.3s; }
        .img-upload-area:hover { border-color: #8B6F47; }
        .img-upload-area i { font-size: 2rem; color: #8B6F47; }
        .img-preview { max-width: 300px; max-height: 200px; border-radius: 8px; margin-top: 10px; display: none; }
        .current-img-box { display: flex; align-items: center; gap: 20px; padding: 20px; background: #FDF8ED; border-radius: 12px; margin-bottom: 20px; }
        .current-img-box img { width: 150px; height: 120px; object-fit: cover; border-radius: 8px; }
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
            <h2><i class="fas fa-edit"></i> Edit: <?= $item['name'] ?></h2>
            <a href="menu_manage.php" class="btn btn-sm btn-dark"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
        <div class="admin-content">
            <div class="admin-form" style="max-width:800px;">
                <h3><i class="fas fa-utensils"></i> Update Product</h3>
                <form method="POST" enctype="multipart/form-data">
                    
                    <!-- Current Image -->
                    <div class="current-img-box">
                        <img src="<?= getImageUrl($item['image'], 'menu') ?>" alt="<?= $item['name'] ?>" onerror="this.src='<?= getDefaultCategoryImage($item['category']) ?>'">
                        <div>
                            <strong>Current Image</strong>
                            <p style="color:#8B7D6B; font-size:0.8rem;">
                                <?= $item['image'] === 'default.jpg' ? 'No image uploaded' : $item['image'] ?>
                            </p>
                        </div>
                    </div>

                    <!-- Replace Image -->
                    <div class="form-group">
                        <label><i class="fas fa-camera"></i> Replace Image (leave empty to keep current)</label>
                        <div class="img-upload-area" onclick="document.getElementById('pImg').click();">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Click to upload new image</p>
                            <img id="imgPrev" class="img-preview">
                        </div>
                        <input type="file" id="pImg" name="product_image" accept="image/*" style="display:none;" onchange="var p=document.getElementById('imgPrev');if(this.files[0]){var r=new FileReader();r.onload=function(e){p.src=e.target.result;p.style.display='block';};r.readAsDataURL(this.files[0]);}">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-link"></i> Or Enter Image URL</label>
                        <input type="url" name="external_image_url" class="form-control" value="<?= (strpos($item['image'], 'http') === 0) ? $item['image'] : '' ?>" placeholder="https://example.com/image.jpg">
                    </div>
                    <hr style="border-color:#F5EFE0; margin:25px 0;">
                    
                    <div class="form-row">
                        <div class="form-group"><label>Item Name *</label><input type="text" name="name" class="form-control" value="<?= $item['name'] ?>" required></div>
                        <div class="form-group"><label>Category *</label><select name="category" class="form-control" required>
                            <option value="coffee" <?= $item['category']==='coffee'?'selected':'' ?>>☕ Coffee</option>
                            <option value="tea" <?= $item['category']==='tea'?'selected':'' ?>>🍵 Tea</option>
                            <option value="cakes" <?= $item['category']==='cakes'?'selected':'' ?>>🎂 Cakes</option>
                            <option value="snacks" <?= $item['category']==='snacks'?'selected':'' ?>>🍪 Snacks</option>
                        </select></div>
                    </div>
                    <div class="form-group"><label>Description *</label><textarea name="description" class="form-control" rows="3" required><?= $item['description'] ?></textarea></div>
                    <div class="form-row">
                        <div class="form-group"><label>Price (LKR) *</label><input type="number" name="price" class="form-control" step="0.01" min="0" value="<?= $item['price'] ?>" required></div>
                        <div class="form-group"><label>Image Alt Text</label><input type="text" name="image_alt" class="form-control" value="<?= $item['image_alt'] ?>"></div>
                    </div>
                    <div style="display:flex; gap:20px; margin:20px 0; flex-wrap:wrap;">
                        <label style="display:flex; align-items:center; gap:10px; padding:12px 20px; background:#FDF8ED; border-radius:8px; cursor:pointer;"><input type="checkbox" name="is_available" <?= $item['is_available']?'checked':'' ?> style="width:20px; height:20px; accent-color:#8B6F47;"> Available</label>
                        <label style="display:flex; align-items:center; gap:10px; padding:12px 20px; background:#FDF8ED; border-radius:8px; cursor:pointer;"><input type="checkbox" name="is_featured" <?= $item['is_featured']?'checked':'' ?> style="width:20px; height:20px; accent-color:#D4A843;"> Featured</label>
                    </div>
                    <button type="submit" name="update_item" class="btn btn-dark btn-lg" style="width:100%;"><i class="fas fa-save"></i> Update Item</button>
                </form>
            </div>
        </div>
    </main>
</div>
</body>
</html>