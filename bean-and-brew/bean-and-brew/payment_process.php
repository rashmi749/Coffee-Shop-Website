<?php
// ============================================
// Bean & Brew Cafe - PayHere Payment Process
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
    $paymentMethod = sanitize($_POST['payment_method'] ?? 'payhere');
    $notes = sanitize($_POST['notes'] ?? '');
    
    // Collect items
    $itemIds = $_POST['item'] ?? [];
    $quantities = $_POST['qty'] ?? [];
    
    if (empty($itemIds)) {
        echo json_encode(['status' => 'error', 'message' => 'Please select at least one item.']);
        exit();
    }
    
    // Calculate total
    $totalAmount = 0;
    $itemsDescription = [];
    
    foreach ($itemIds as $itemId) {
        $itemId = (int)$itemId;
        $stmt = $conn->prepare("SELECT * FROM menu_items WHERE id = ?");
        $stmt->bind_param("i", $itemId);
        $stmt->execute();
        $item = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        if ($item) {
            $qty = isset($quantities[$itemId]) ? (int)$quantities[$itemId] : 1;
            $qty = max(1, min(20, $qty));
            $totalAmount += $item['price'] * $qty;
            $itemsDescription[] = $item['name'] . ' x' . $qty;
        }
    }
    
    // Add delivery fee
    $totalAmount += 350;
    $itemsString = implode(', ', $itemsDescription);
    
    // Insert order first
    $stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_email, customer_phone, customer_address, items, total_amount, payment_method, payment_status, order_status, notes) VALUES (?, ?, ?, ?, ?, ?, 'payhere', 'pending', 'pending', ?)");
    $stmt->bind_param("sssssdss", $customerName, $customerEmail, $customerPhone, $customerAddress, $itemsString, $totalAmount, $notes);
    
    if ($stmt->execute()) {
        $orderId = $stmt->insert_id;
        $stmt->close();
        
        echo json_encode([
            'status' => 'success',
            'order_id' => $orderId,
            'total' => $totalAmount
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create order.']);
    }
    
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>