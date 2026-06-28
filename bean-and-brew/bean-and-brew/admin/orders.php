<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

// Handle status update
if (isset($_POST['update_status'])) {
    $orderId = (int)$_POST['order_id'];
    $newStatus = sanitize($_POST['new_status']);
    $valid = ['pending', 'confirmed', 'preparing', 'delivered', 'cancelled'];
    if (in_array($newStatus, $valid)) {
        $stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
        $stmt->bind_param("si", $newStatus, $orderId);
        $stmt->execute();
        $stmt->close();
        setFlash('success', "Order #$orderId updated to '$newStatus'.");
    }
    header("Location: " . SITE_URL . "/admin/orders.php");
    exit();
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM orders WHERE id = $id");
    setFlash('success', "Order #$id deleted.");
    header("Location: " . SITE_URL . "/admin/orders.php");
    exit();
}

// Filter
$filter = $_GET['filter'] ?? 'all';
$where = "";
if ($filter !== 'all' && in_array($filter, ['pending', 'confirmed', 'preparing', 'delivered', 'cancelled'])) {
    $where = "WHERE order_status = '" . $conn->real_escape_string($filter) . "'";
}
$orders = $conn->query("SELECT * FROM orders $where ORDER BY created_at DESC");
$adminName = $_SESSION['admin_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders | <?= SITE_NAME ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin.css">
</head>
<body>
<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <a href="<?= SITE_URL ?>" class="nav-logo"><i class="fas fa-mug-hot"></i><span>Bean & Brew</span></a>
        </div>
        <nav class="sidebar-nav">
            <a href="<?= SITE_URL ?>/admin/index.php" class="sidebar-nav-item"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
            <a href="<?= SITE_URL ?>/admin/orders.php" class="sidebar-nav-item active"><i class="fas fa-shopping-cart"></i><span>Orders</span></a>
            <a href="<?= SITE_URL ?>/admin/menu_manage.php" class="sidebar-nav-item"><i class="fas fa-utensils"></i><span>Menu Items</span></a>
            <a href="<?= SITE_URL ?>/admin/gallery_manage.php" class="sidebar-nav-item"><i class="fas fa-images"></i><span>Gallery</span></a>
            <a href="<?= SITE_URL ?>/admin/contacts.php" class="sidebar-nav-item"><i class="fas fa-envelope"></i><span>Messages</span></a>
            <a href="<?= SITE_URL ?>/" class="sidebar-nav-item" target="_blank"><i class="fas fa-globe"></i><span>View Site</span></a>
            <a href="<?= SITE_URL ?>/admin/logout.php" class="sidebar-nav-item"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
        </nav>
    </aside>

    <main class="admin-main">
        <div class="admin-topbar">
            <h2><i class="fas fa-shopping-cart"></i> Orders</h2>
            <div class="admin-topbar-info">
                <a href="?filter=all" class="btn btn-sm <?= $filter === 'all' ? 'btn-dark' : '' ?>" style="<?= $filter !== 'all' ? 'background:var(--bg-cream);' : '' ?>">All</a>
                <a href="?filter=pending" class="btn btn-sm" style="background:<?= $filter === 'pending' ? '#ffc107' : '#fff3cd' ?>; color:#856404;">Pending</a>
                <a href="?filter=confirmed" class="btn btn-sm" style="background:<?= $filter === 'confirmed' ? '#0d6efd' : '#cce5ff' ?>; color:#fff;">Confirmed</a>
                <a href="?filter=delivered" class="btn btn-sm" style="background:<?= $filter === 'delivered' ? '#198754' : '#d4edda' ?>; color:#fff;">Delivered</a>
            </div>
        </div>

        <div class="admin-content">
            <?= getFlash() ?>
            
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($orders->num_rows > 0): ?>
                            <?php while ($order = $orders->fetch_assoc()): ?>
                            <tr>
                                <td><strong>#<?= $order['id'] ?></strong></td>
                                <td>
                                    <strong><?= $order['customer_name'] ?></strong><br>
                                    <small style="color:var(--text-light);"><?= $order['customer_email'] ?></small>
                                </td>
                                <td><?= $order['customer_phone'] ?></td>
                                <td><small><?= substr($order['items'], 0, 40) ?>...</small></td>
                                <td><strong><?= formatPrice($order['total_amount']) ?></strong></td>
                                <td><span class="status-badge status-<?= $order['payment_status'] ?>"><?= ucfirst($order['payment_status']) ?></span></td>
                                <td>
                                    <form method="POST" style="display:flex; gap:5px; align-items:center;">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                        <select name="new_status" class="form-control" style="padding:5px 8px; font-size:0.78rem; width:auto;">
                                            <option value="pending" <?= $order['order_status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="confirmed" <?= $order['order_status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                            <option value="preparing" <?= $order['order_status'] === 'preparing' ? 'selected' : '' ?>>Preparing</option>
                                            <option value="delivered" <?= $order['order_status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                            <option value="cancelled" <?= $order['order_status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                        </select>
                                        <button type="submit" name="update_status" class="action-btn action-btn-edit"><i class="fas fa-save"></i></button>
                                    </form>
                                </td>
                                <td><small><?= date('d M, H:i', strtotime($order['created_at'])) ?></small></td>
                                <td>
                                    <a href="?delete=<?= $order['id'] ?>" class="action-btn action-btn-delete" 
                                       onclick="return confirm('Delete order #<?= $order['id'] ?>?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" style="text-align:center; padding:40px; color:var(--text-light);">
                                    <i class="fas fa-inbox" style="font-size:2rem; display:block; margin-bottom:10px;"></i>
                                    No orders found
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
</body>
</html>