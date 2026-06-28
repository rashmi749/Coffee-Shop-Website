<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

if (isset($_GET['read'])) {
    $id = (int)$_GET['read'];
    $conn->query("UPDATE contacts SET is_read = 1 WHERE id = $id");
    header("Location: " . SITE_URL . "/admin/contacts.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM contacts WHERE id = $id");
    setFlash('success', 'Message deleted.');
    header("Location: " . SITE_URL . "/admin/contacts.php");
    exit();
}

$contacts = $conn->query("SELECT * FROM contacts ORDER BY created_at DESC");
$adminName = $_SESSION['admin_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages | <?= SITE_NAME ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin.css">
</head>
<body>
<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <div class="sidebar-header"><a href="<?= SITE_URL ?>" class="nav-logo"><i class="fas fa-mug-hot"></i><span>Bean & Brew</span></a></div>
        <nav class="sidebar-nav">
            <a href="<?= SITE_URL ?>/admin/index.php" class="sidebar-nav-item"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
            <a href="<?= SITE_URL ?>/admin/orders.php" class="sidebar-nav-item"><i class="fas fa-shopping-cart"></i><span>Orders</span></a>
            <a href="<?= SITE_URL ?>/admin/menu_manage.php" class="sidebar-nav-item"><i class="fas fa-utensils"></i><span>Menu Items</span></a>
            <a href="<?= SITE_URL ?>/admin/gallery_manage.php" class="sidebar-nav-item"><i class="fas fa-images"></i><span>Gallery</span></a>
            <a href="<?= SITE_URL ?>/admin/contacts.php" class="sidebar-nav-item active"><i class="fas fa-envelope"></i><span>Messages</span></a>
            <a href="<?= SITE_URL ?>/" class="sidebar-nav-item" target="_blank"><i class="fas fa-globe"></i><span>View Site</span></a>
            <a href="<?= SITE_URL ?>/admin/logout.php" class="sidebar-nav-item"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
        </nav>
    </aside>
    <main class="admin-main">
        <div class="admin-topbar"><h2><i class="fas fa-envelope"></i> Messages</h2></div>
        <div class="admin-content">
            <?= getFlash() ?>
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead><tr><th></th><th>Name</th><th>Email</th><th>Phone</th><th>Subject</th><th>Message</th><th>Date</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php if ($contacts->num_rows > 0): ?>
                            <?php while ($c = $contacts->fetch_assoc()): ?>
                            <tr style="<?= !$c['is_read'] ? 'background:rgba(228,214,169,0.1); font-weight:500;' : '' ?>">
                                <td><?= !$c['is_read'] ? '<i class="fas fa-circle" style="color:#D4A843; font-size:0.6rem;"></i>' : '' ?></td>
                                <td><strong><?= $c['name'] ?></strong></td>
                                <td><a href="mailto:<?= $c['email'] ?>"><?= $c['email'] ?></a></td>
                                <td><?= $c['phone'] ?: '-' ?></td>
                                <td><?= $c['subject'] ?></td>
                                <td><small><?= substr($c['message'], 0, 50) ?>...</small></td>
                                <td><small><?= date('d M, H:i', strtotime($c['created_at'])) ?></small></td>
                                <td>
                                    <div class="action-btns">
                                        <?php if (!$c['is_read']): ?>
                                            <a href="?read=<?= $c['id'] ?>" class="action-btn action-btn-view"><i class="fas fa-check"></i></a>
                                        <?php endif; ?>
                                        <a href="?delete=<?= $c['id'] ?>" class="action-btn action-btn-delete" onclick="return confirm('Delete?');"><i class="fas fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="8" style="text-align:center; padding:40px; color:#8B7D6B;"><i class="fas fa-inbox" style="font-size:2rem; display:block; margin-bottom:10px;"></i>No messages yet</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
</body>
</html>