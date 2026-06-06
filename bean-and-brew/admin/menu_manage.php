<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $img = $conn->query("SELECT image FROM menu_items WHERE id = $id")->fetch_assoc();
    if ($img) deleteImage(MENU_UPLOAD_PATH, $img['image']);
    $conn->query("DELETE FROM menu_items WHERE id = $id");
    setFlash('success', "Item deleted.");
    header("Location: " . SITE_URL . "/admin/menu_manage.php");
    exit();
}

// Handle toggle availability
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $conn->query("UPDATE menu_items SET is_available = NOT is_available WHERE id = $id");
    setFlash('success', "Availability updated.");
    header("Location: " . SITE_URL . "/admin/menu_manage.php");
    exit();
}

// Handle toggle featured
if (isset($_GET['featured'])) {
    $id = (int)$_GET['featured'];
    $conn->query("UPDATE menu_items SET is_featured = NOT is_featured WHERE id = $id");
    setFlash('success', "Featured status updated.");
    header("Location: " . SITE_URL . "/admin/menu_manage.php");
    exit();
}

// Filter
$filterCat = $_GET['cat'] ?? 'all';
$where = "";
if ($filterCat !== 'all' && in_array($filterCat, ['coffee', 'tea', 'cakes', 'snacks'])) {
    $where = "WHERE category = '" . $conn->real_escape_string($filterCat) . "'";
}

$menuItems = $conn->query("SELECT * FROM menu_items $where ORDER BY category ASC, name ASC");
$totalItems = $menuItems->num_rows;

// Count per category
$counts = [];
foreach (['coffee', 'tea', 'cakes', 'snacks'] as $cat) {
    $r = $conn->query("SELECT COUNT(*) as c FROM menu_items WHERE category='$cat'");
    $counts[$cat] = $r->fetch_assoc()['c'];
}
$totalAll = $conn->query("SELECT COUNT(*) as c FROM menu_items")->fetch_assoc()['c'];
$adminName = $_SESSION['admin_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu | <?= SITE_NAME ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin.css">
    <style>
        .menu-grid-admin { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        .menu-card-admin { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(26,14,5,0.08); transition: 0.3s; }
        .menu-card-admin:hover { transform: translateY(-3px); box-shadow: 0 4px 20px rgba(26,14,5,0.15); }
        .menu-card-admin-img { position: relative; height: 180px; overflow: hidden; }
        .menu-card-admin-img img { width: 100%; height: 100%; object-fit: cover; }
        .menu-card-admin-img .badge-featured { position: absolute; top: 10px; left: 10px; background: #D4A843; color: #1a0e05; padding: 3px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 700; }
        .menu-card-admin-img .badge-unavailable { position: absolute; top: 10px; right: 10px; background: #dc3545; color: #fff; padding: 3px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 700; }
        .menu-card-admin-body { padding: 18px; }
        .menu-card-admin-body h4 { font-family: 'Playfair Display', serif; color: #1a0e05; margin-bottom: 5px; }
        .menu-card-admin-body .cat-tag { display: inline-block; padding: 2px 10px; background: #F5EFE0; color: #8B6F47; border-radius: 20px; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; margin-bottom: 8px; }
        .menu-card-admin-body p { color: #8B7D6B; font-size: 0.82rem; margin-bottom: 12px; }
        .menu-card-admin-body .price { font-family: 'Playfair Display', serif; font-size: 1.2rem; color: #8B6F47; font-weight: 700; margin-bottom: 12px; }
        .menu-card-admin-actions { display: flex; gap: 6px; flex-wrap: wrap; }
        .filter-bar { display: flex; gap: 8px; margin-bottom: 25px; flex-wrap: wrap; }
        .filter-btn-admin { padding: 8px 20px; border: 2px solid #C9B88A; background: transparent; color: #5C4A32; border-radius: 30px; font-family: 'Poppins', sans-serif; font-size: 0.82rem; font-weight: 600; cursor: pointer; transition: 0.3s; text-decoration: none; }
        .filter-btn-admin:hover, .filter-btn-admin.active { background: linear-gradient(135deg, #E4D6A9 0%, #C9B88A 100%); color: #1a0e05; border-color: #E4D6A9; }
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
            <h2><i class="fas fa-utensils"></i> Menu Items (<?= $totalItems ?>)</h2>
            <a href="<?= SITE_URL ?>/admin/menu_add.php" class="btn btn-sm btn-dark"><i class="fas fa-plus"></i> Add New</a>
        </div>

        <div class="admin-content">
            <?= getFlash() ?>
            
            <div class="filter-bar">
                <a href="?cat=all" class="filter-btn-admin <?= $filterCat === 'all' ? 'active' : '' ?>">All (<?= $totalAll ?>)</a>
                <a href="?cat=coffee" class="filter-btn-admin <?= $filterCat === 'coffee' ? 'active' : '' ?>">☕ Coffee (<?= $counts['coffee'] ?>)</a>
                <a href="?cat=tea" class="filter-btn-admin <?= $filterCat === 'tea' ? 'active' : '' ?>">🍵 Tea (<?= $counts['tea'] ?>)</a>
                <a href="?cat=cakes" class="filter-btn-admin <?= $filterCat === 'cakes' ? 'active' : '' ?>">🎂 Cakes (<?= $counts['cakes'] ?>)</a>
                <a href="?cat=snacks" class="filter-btn-admin <?= $filterCat === 'snacks' ? 'active' : '' ?>">🍪 Snacks (<?= $counts['snacks'] ?>)</a>
            </div>

            <div class="menu-grid-admin">
                <?php while ($item = $menuItems->fetch_assoc()): ?>
                <div class="menu-card-admin">
                    <div class="menu-card-admin-img">
                        <img src="<?= getImageUrl($item['image'], 'menu') ?>" alt="<?= $item['name'] ?>" onerror="this.src='<?= getDefaultCategoryImage($item['category']) ?>'">
                        <?php if ($item['is_featured']): ?><span class="badge-featured"><i class="fas fa-star"></i> Featured</span><?php endif; ?>
                        <?php if (!$item['is_available']): ?><span class="badge-unavailable"><i class="fas fa-eye-slash"></i> Hidden</span><?php endif; ?>
                    </div>
                    <div class="menu-card-admin-body">
                        <span class="cat-tag"><?= ucfirst($item['category']) ?></span>
                        <h4><?= $item['name'] ?></h4>
                        <p><?= substr($item['description'], 0, 70) ?>...</p>
                        <div class="price"><?= formatPrice($item['price']) ?></div>
                        <div class="menu-card-admin-actions">
                            <a href="menu_edit.php?id=<?= $item['id'] ?>" class="action-btn action-btn-edit"><i class="fas fa-edit"></i> Edit</a>
                            <a href="?toggle=<?= $item['id'] ?>" class="action-btn action-btn-view" title="<?= $item['is_available'] ? 'Hide' : 'Show' ?>">
                                <i class="fas fa-<?= $item['is_available'] ? 'eye-slash' : 'eye' ?>"></i>
                            </a>
                            <a href="?featured=<?= $item['id'] ?>" class="action-btn action-btn-edit" title="Feature"><i class="fas fa-star"></i></a>
                            <a href="?delete=<?= $item['id'] ?>" class="action-btn action-btn-delete" onclick="return confirm('Delete <?= $item['name'] ?>?');"><i class="fas fa-trash"></i></a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </main>
</div>
</body>
</html>