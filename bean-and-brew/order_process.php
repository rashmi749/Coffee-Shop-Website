<?php
// ============================================
// Bean & Brew Cafe - Order Processing
// ============================================
require_once 'includes/config.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Collect form data
    $customerName = sanitize($_POST['customer_name'] ?? '');
    $customerEmail = sanitize($_POST['customer_email'] ?? '');
    $customerPhone = sanitize($_POST['customer_phone'] ?? '');
    $customerAddress = sanitize($_POST['customer_address'] ?? '');
    $paymentMethod = sanitize($_POST['payment_method'] ?? 'cod');
    $notes = sanitize($_POST['notes'] ?? '');
    
    // Collect items
    $itemIds = $_POST['item'] ?? [];
    $quantities = $_POST['qty'] ?? [];
    
    if (empty($itemIds)) {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            echo json_encode(['status' => 'error', 'message' => 'Please select at least one item.']);
        } else {
            setFlash('error', 'Please select at least one item.');
            redirect(SITE_URL . '/pages/order.php');
        }
        exit();
    }
    
    if (empty($customerName) || empty($customerEmail) || empty($customerPhone) || empty($customerAddress)) {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
        } else {
            setFlash('error', 'Please fill in all required fields.');
            redirect(SITE_URL . '/pages/order.php');
        }
        exit();
    }
    
    // Calculate total and build items string
    $totalAmount = 0;
    $itemsDescription = [];
    
    foreach ($itemIds as $itemId) {
        $itemId = (int)$itemId;
        $item = getMenuItem($itemId);
        if ($item) {
            $qty = isset($quantities[$itemId]) ? (int)$quantities[$itemId] : 1;
            $qty = max(1, min(20, $qty));
            $totalAmount += $item['price'] * $qty;
            $itemsDescription[] = $item['name'] . ' x' . $qty;
        }
    }
    
    // Add delivery fee
    $deliveryFee = 350;
    $totalAmount += $deliveryFee;
    
    $itemsString = implode(', ', $itemsDescription);
    
    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_email, customer_phone, customer_address, items, total_amount, payment_method, payment_status, order_status, notes) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', 'pending', ?)");
    $paymentStatus = ($paymentMethod === 'payhere') ? 'pending' : 'pending';
    $stmt->bind_param("sssssdss", $customerName, $customerEmail, $customerPhone, $customerAddress, $itemsString, $totalAmount, $paymentMethod, $notes);
    
    if ($stmt->execute()) {
        $orderId = $stmt->insert_id;
        
        // If AJAX request (for PayHere)
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            echo json_encode([
                'status' => 'success',
                'order_id' => $orderId,
                'total' => $totalAmount
            ]);
        } else {
            if ($paymentMethod === 'payhere') {
                // Redirect to payment page
                setFlash('success', 'Order #' . $orderId . ' created! Proceeding to payment...');
            } else {
                setFlash('success', 'Order #' . $orderId . ' placed successfully! We will contact you shortly.');
            }
            redirect(SITE_URL . '/pages/order.php');
        }
    } else {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            echo json_encode(['status' => 'error', 'message' => 'Failed to place order. Please try again.']);
        } else {
            setFlash('error', 'Failed to place order. Please try again.');
            redirect(SITE_URL . '/pages/order.php');
        }
    }
    
    $stmt->close();
} else {
    redirect(SITE_URL . '/pages/order.php');
}
?>