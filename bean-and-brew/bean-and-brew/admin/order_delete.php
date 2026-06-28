<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $conn->query("DELETE FROM orders WHERE id = $id");
    setFlash('success', "Order #$id has been deleted.");
}
header("Location: " . SITE_URL . "/admin/orders.php");
exit();
?>