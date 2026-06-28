<?php
// ============================================
// Bean & Brew - Admin Dashboard (STANDALONE)
// ============================================
session_start();

// Check login
if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Database
$conn = new mysqli('localhost', 'root', '', 'bean_and_brew');
if ($conn->connect_error) die("DB Error");
$conn->set_charset("utf8mb4");

$adminName = $_SESSION['admin_name'] ?? 'Admin';

// Stats
$totalOrders = $conn->query("SELECT COUNT(*) as c FROM orders")->fetch_assoc()['c'];
$pendingOrders = $conn->query("SELECT COUNT(*) as c FROM orders WHERE order_status='pending'")->fetch_assoc()['c'];
$totalMenu = $conn->query("SELECT COUNT(*) as c FROM menu_items")->fetch_assoc()['c'];
$unreadMsg = $conn->query("SELECT COUNT(*) as c FROM contacts WHERE is_read=0")->fetch_assoc()['c'];
$revenue = $conn->query("SELECT COALESCE(SUM(total_amount),0) as t FROM orders WHERE payment_status='paid'")->fetch_assoc()['t'];
$recentOrders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5");

// Flash
$flash = '';
if (isset($_SESSION['flash'])) {
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    $cls = $f['type'] === 'success' ? 'background:#d4edda;color:#155724;border:1px solid #c3e6cb;' : 'background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;';
    $flash = "<div style='padding:12px 16px;border-radius:8px;margin-bottom:20px;font-size:0.9rem;{$cls}'>{$f['message']}</div>";
}

function fmtPrice($p) { return 'Rs. ' . number_format((float)$p, 2); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Bean & Brew Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Poppins',sans-serif;background:#FDF8ED;color:#1a0e05;display:flex;min-height:100vh}
        
        /* Sidebar */
        .sidebar{width:260px;background:#1a0e05;color:#E4D6A9;position:fixed;top:0;left:0;height:100vh;overflow-y:auto;z-index:100}
        .sidebar-header{padding:25px 20px;border-bottom:1px solid rgba(228,214,169,0.1);text-align:center}
        .sidebar-header a{display:flex;align-items:center;justify-content:center;gap:10px;text-decoration:none;color:#E4D6A9;font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:700}
        .sidebar-header i{color:#D4A843;font-size:1.5rem}
        .sidebar-nav{padding:15px 0}
        .sidebar-nav a{display:flex;align-items:center;gap:12px;padding:14px 25px;color:rgba(228,214,169,0.8);text-decoration:none;font-size:0.92rem;transition:0.3s;border-left:3px solid transparent}
        .sidebar-nav a:hover,.sidebar-nav a.active{background:rgba(228,214,169,0.08);color:#D4A843;border-left-color:#D4A843}
        .sidebar-nav a i{width:20px;text-align:center}
        .sidebar-nav .badge{margin-left:auto;background:#8B6F47;color:#fff;padding:2px 8px;border-radius:20px;font-size:0.72rem}
        
        /* Main */
        .main{margin-left:260px;flex:1}
        .topbar{background:#fff;padding:18px 30px;display:flex;align-items:center;justify-content:space-between;box-shadow:0 2px 10px rgba(0,0,0,0.05);position:sticky;top:0;z-index:50}
        .topbar h2{font-family:'Playfair Display',serif;font-size:1.4rem}
        .topbar-info{display:flex;align-items:center;gap:15px;color:#8B7D6B;font-size:0.88rem}
        .content{padding:30px}
        
        /* Dashboard Cards */
        .dash-cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;margin-bottom:30px}
        .dash-card{background:#fff;border-radius:12px;padding:25px;display:flex;align-items:center;gap:18px;box-shadow:0 2px 10px rgba(0,0,0,0.05);transition:0.3s}
        .dash-card:hover{transform:translateY(-3px);box-shadow:0 4px 20px rgba(0,0,0,0.1)}
        .dash-icon{width:55px;height:55px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem}
        .dash-icon.c1{background:rgba(228,214,169,0.2);color:#D4A843}
        .dash-icon.c2{background:rgba(139,111,71,0.15);color:#8B6F47}
        .dash-icon.c3{background:rgba(212,168,67,0.15);color:#8B6F47}
        .dash-icon.c4{background:rgba(26,14,5,0.08);color:#1a0e05}
        .dash-info h3{font-size:1.7rem;font-family:'Playfair Display',serif}
        .dash-info p{color:#8B7D6B;font-size:0.85rem}
        
        /* Table */
        .table-box{background:#fff;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.05);overflow:hidden}
        .table-header{padding:18px 25px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #F5EFE0}
        .table-header h3{font-family:'Playfair Display',serif;font-size:1.1rem}
        table{width:100%;border-collapse:collapse}
        th{background:#F5EFE0;padding:12px 18px;text-align:left;font-size:0.82rem;font-weight:600;color:#5C4A32;text-transform:uppercase}
        td{padding:12px 18px;border-bottom:1px solid #F5EFE0;font-size:0.88rem}
        tr:hover{background:rgba(228,214,169,0.05)}
        
        /* Badges */
        .badge-status{display:inline-block;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600}
        .s-pending{background:#fff3cd;color:#856404}
        .s-confirmed{background:#cce5ff;color:#004085}
        .s-preparing{background:#d4edda;color:#155724}
        .s-delivered{background:#d1ecf1;color:#0c5460}
        .s-cancelled{background:#f8d7da;color:#721c24}
        .s-paid{background:#d4edda;color:#155724}
        .s-failed{background:#f8d7da;color:#721c24}
        
        /* Buttons */
        .btn{display:inline-flex;align-items:center;gap:6px;padding:8px 18px;border-radius:8px;text-decoration:none;font-size:0.82rem;font-weight:600;transition:0.3s;border:none;cursor:pointer;font-family:'Poppins',sans-serif}
        .btn-dark{background:#1a0e05;color:#E4D6A9}
        .btn-dark:hover{background:#2d1f12;transform:translateY(-2px)}
        
        @media(max-width:768px){
            .sidebar{width:60px}
            .sidebar-header a span,.sidebar-nav a span,.sidebar-nav .badge{display:none}
            .sidebar-nav a{justify-content:center;padding:14px}
            .main{margin-left:60px}
            .content{padding:15px}
            .topbar{padding:12px 15px}
        }
    </style>
</head>
<body>
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="../"><i class="fas fa-mug-hot"></i><span>Bean & Brew</span></a>
        </div>
        <nav class="sidebar-nav">
            <a href="index.php" class="active"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
            <a href="orders.php"><i class="fas fa-shopping-cart"></i><span>Orders</span><?php if($pendingOrders>0):?><span class="badge"><?=$pendingOrders?></span><?php endif;?></a>
            <a href="menu_manage.php"><i class="fas fa-utensils"></i><span>Menu Items</span></a>
            <a href="gallery_manage.php"><i class="fas fa-images"></i><span>Gallery</span></a>
            <a href="contacts.php"><i class="fas fa-envelope"></i><span>Messages</span><?php if($unreadMsg>0):?><span class="badge"><?=$unreadMsg?></span><?php endif;?></a>
            <a href="../" target="_blank"><i class="fas fa-globe"></i><span>View Site</span></a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
        </nav>
    </aside>

    <!-- MAIN -->
    <main class="main">
        <div class="topbar">
            <h2><i class="fas fa-tachometer-alt"></i> Dashboard</h2>
            <div class="topbar-info">
                <span><i class="fas fa-user-circle"></i> <?=$adminName?></span>
                <span>|</span>
                <span><?=date('d M Y')?></span>
                <a href="logout.php" style="color:#c62828;text-decoration:none;font-weight:600;margin-left:10px;">
                    <i class="fas fa-power-off"></i> Logout
                </a>
            </div>
        </div>

        <div class="content">
            <?=$flash?>

            <!-- Stats -->
            <div class="dash-cards">
                <div class="dash-card">
                    <div class="dash-icon c1"><i class="fas fa-shopping-cart"></i></div>
                    <div class="dash-info"><h3><?=$totalOrders?></h3><p>Total Orders</p></div>
                </div>
                <div class="dash-card">
                    <div class="dash-icon c2"><i class="fas fa-clock"></i></div>
                    <div class="dash-info"><h3><?=$pendingOrders?></h3><p>Pending</p></div>
                </div>
                <div class="dash-card">
                    <div class="dash-icon c3"><i class="fas fa-utensils"></i></div>
                    <div class="dash-info"><h3><?=$totalMenu?></h3><p>Menu Items</p></div>
                </div>
                <div class="dash-card">
                    <div class="dash-icon c4"><i class="fas fa-coins"></i></div>
                    <div class="dash-info"><h3><?=fmtPrice($revenue)?></h3><p>Revenue</p></div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="table-box">
                <div class="table-header">
                    <h3><i class="fas fa-clock"></i> Recent Orders</h3>
                    <a href="orders.php" class="btn btn-dark">View All</a>
                </div>
                <table>
                    <thead>
                        <tr><th>#</th><th>Customer</th><th>Items</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        <?php if($recentOrders->num_rows > 0): ?>
                            <?php while($o = $recentOrders->fetch_assoc()): ?>
                            <tr>
                                <td><strong>#<?=$o['id']?></strong></td>
                                <td><?=$o['customer_name']?></td>
                                <td><small><?=substr($o['items'],0,30)?>...</small></td>
                                <td><strong><?=fmtPrice($o['total_amount'])?></strong></td>
                                <td><span class="badge-status s-<?=$o['payment_status']?>"><?=ucfirst($o['payment_status'])?></span></td>
                                <td><span class="badge-status s-<?=$o['order_status']?>"><?=ucfirst($o['order_status'])?></span></td>
                                <td><small><?=date('d M, H:i', strtotime($o['created_at']))?></small></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="7" style="text-align:center;padding:40px;color:#8B7D6B;">
                                <i class="fas fa-inbox" style="font-size:2rem;display:block;margin-bottom:10px;"></i>No orders yet
                            </td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
<?php $conn->close(); ?>