<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $img = $conn->query("SELECT image FROM gallery WHERE id = $id")->fetch_assoc();
    if ($img) deleteImage(GALLERY_UPLOAD_PATH, $img['image']);
    $conn->query("DELETE FROM gallery WHERE id = $id");
    setFlash('success', 'Image deleted.');
    header("Location: " . SITE_URL . "/admin/gallery_manage.php");
    exit();
}

if (isset($_POST['add_gallery'])) {
    $title = sanitize($_POST['title']);
    $desc = sanitize($_POST['description'] ?? '');
    if (isset($_FILES['gallery_image']) && $_FILES['gallery_image']['error'] === UPLOAD_ERR_OK) {
        $result = uploadImage('gallery_image', GALLERY_UPLOAD_PATH);
        if ($result['success']) {
            $stmt = $conn->prepare("INSERT INTO gallery (title, description, image) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $title, $desc, $result['filename']);
            $stmt->execute();
            $stmt->close();
            setFlash('success', 'Image uploaded!');
        } else {
            setFlash('error', $result['message']);
        }
    } else {
        setFlash('error', 'Please select an image.');
    }
    header("Location: " . SITE_URL . "/admin/gallery_manage.php");
    exit();
}

$gallery = $conn->query("SELECT * FROM gallery ORDER BY created_at DESC");
$adminName = $_SESSION['admin_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery | <?= SITE_NAME ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin.css">
    <style>
        .gal-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 15px; }
        .gal-item { position: relative; border-radius: 12px; overflow: hidden; height: 200px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .gal-item img { width: 100%; height: 100%; object-fit: cover; }
        .gal-item .ov { position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.8)); padding: 15px 10px 10px; display: flex; justify-content: space-between; align-items: flex-end; }
        .gal-item .ov span { color: #fff; font-size: 0.8rem; }
        .img-upload-area { border: 3px dashed #C9B88A; border-radius: 12px; padding: 25px; text-align: center; cursor: pointer; background: #FDF8ED; }
        .img-upload-area:hover { border-color: #8B6F47; }
        .img-upload-area i { font-size: 2rem; color: #8B6F47; }
        .img-preview { max-width: 250px; max-height: 150px; border-radius: 8px; margin-top: 10px; display: none; }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <div class="sidebar-header"><a href="<?= SITE_URL ?>" class="nav-logo"><i class="fas fa-mug-hot"></i><span>Bean & Brew</span></a></div>
        <nav class="sidebar-nav">
            <a href="<?= SITE_URL ?>/admin/index.php" class="sidebar-nav-item"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
            <a href="<?= SITE_URL ?>/admin/orders.php" class="sidebar-nav-item"><i class="fas fa-shopping-cart"></i><span>Orders</span></a>
            <a href="<?= SITE_URL ?>/admin/menu_manage.php" class="sidebar-nav-item"><i class="fas fa-utensils"></i><span>Menu Items</span></a>
            <a href="<?= SITE_URL ?>/admin/gallery_manage.php" class="sidebar-nav-item active"><i class="fas fa-images"></i><span>Gallery</span></a>
            <a href="<?= SITE_URL ?>/admin/contacts.php" class="sidebar-nav-item"><i class="fas fa-envelope"></i><span>Messages</span></a>
            <a href="<?= SITE_URL ?>/" class="sidebar-nav-item" target="_blank"><i class="fas fa-globe"></i><span>View Site</span></a>
            <a href="<?= SITE_URL ?>/admin/logout.php" class="sidebar-nav-item"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
        </nav>
    </aside>
    <main class="admin-main">
        <div class="admin-topbar"><h2><i class="fas fa-images"></i> Gallery Management</h2></div>
        <div class="admin-content">
            <?= getFlash() ?>
            <div class="admin-form" style="margin-bottom:30px;">
                <h3><i class="fas fa-upload"></i> Add Image</h3>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group"><label>Title *</label><input type="text" name="title" class="form-control" placeholder="e.g., Our Interior" required></div>
                        <div class="form-group"><label>Description</label><input type="text" name="description" class="form-control" placeholder="Short description"></div>
                    </div>
                    <div class="form-group">
                        <label>Image *</label>
                        <div class="img-upload-area" onclick="document.getElementById('gImg').click();">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Click to select image</p>
                            <img id="gPrev" class="img-preview">
                        </div>
                        <input type="file" id="gImg" name="gallery_image" accept="image/*" style="display:none;" onchange="var p=document.getElementById('gPrev');if(this.files[0]){var r=new FileReader();r.onload=function(e){p.src=e.target.result;p.style.display='block';};r.readAsDataURL(this.files[0]);}">
                    </div>
                    <button type="submit" name="add_gallery" class="btn btn-dark"><i class="fas fa-plus"></i> Upload</button>
                </form>
            </div>
            <h3 style="margin-bottom:20px;"><i class="fas fa-images"></i> All Images</h3>
            <div class="gal-grid">
                <?php while ($img = $gallery->fetch_assoc()): ?>
                <div class="gal-item">
                    <img src="<?= getImageUrl($img['image'], 'gallery') ?>" alt="<?= $img['title'] ?>">
                    <div class="ov">
                        <span><?= $img['title'] ?></span>
                        <a href="?delete=<?= $img['id'] ?>" class="action-btn action-btn-delete" style="padding:4px 8px; font-size:0.75rem;" onclick="return confirm('Delete?');"><i class="fas fa-trash"></i></a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </main>
</div>
</body>
</html>