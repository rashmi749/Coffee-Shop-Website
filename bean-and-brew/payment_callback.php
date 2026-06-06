<?php
// ============================================
// Bean & Brew Cafe - PayHere Payment Callback
// ============================================
require_once 'includes/config.php';
require_once 'includes/functions.php';

$status = $_GET['status'] ?? '';
$orderId = $_GET['order_id'] ?? '';

if ($status === 'success' && $orderId) {
    // Update order payment status
    $orderId = (int)$orderId;
    $stmt = $conn->prepare("UPDATE orders SET payment_status = 'paid', order_status = 'confirmed' WHERE id = ?");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $stmt->close();
    
    setFlash('success', "Payment successful! Your order #$orderId has been confirmed. We'll prepare it right away! ☕");
} elseif ($status === 'cancel') {
    setFlash('error', 'Payment was cancelled. You can try again or choose Cash on Delivery.');
} else {
    setFlash('error', 'Payment status unknown. Please contact us for assistance.');
}

redirect(SITE_URL . '/pages/order.php');
?>