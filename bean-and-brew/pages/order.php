<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

// Fetch all available menu items
$menuResult = $conn->query("SELECT * FROM menu_items WHERE is_available = 1 ORDER BY category, name");
?>

<section class="menu-hero">
    <h1 class="menu-hero-title" data-aos="fade-down">Place Your <span>Order</span></h1>
</section>

<section class="section">
    <div class="container">
        <?= getFlash() ?>
        
        <div class="order-section">
            <!-- Order Items Selection -->
            <div>
                <div class="section-header" style="text-align: left; margin-bottom: 30px;">
                    <span class="section-subtitle">Step 1</span>
                    <h2 class="section-title">Select Your Items</h2>
                </div>
                
                <form action="<?= SITE_URL ?>/order_process.php" method="POST" id="orderForm">
                    <div class="order-items-list">
                        <?php while ($item = $menuResult->fetch_assoc()): ?>
                        <div class="order-item-select">
                            <input type="checkbox" name="item[]" value="<?= $item['id'] ?>" 
                                   class="order-item-checkbox" 
                                   data-name="<?= $item['name'] ?>"
                                   data-price="<?= $item['price'] ?>">
                            
                            <div class="order-item-info">
                                <h4><?= $item['name'] ?></h4>
                                <p><?= ucfirst($item['category']) ?> — <?= substr($item['description'], 0, 60) ?>...</p>
                            </div>
                            
                            <span class="order-item-price item-price" data-price="<?= $item['price'] ?>">
                                <?= formatPrice($item['price']) ?>
                            </span>
                            
                            <div class="order-item-qty">
                                <button type="button" class="qty-btn" data-action="decrease" data-target="qty_<?= $item['id'] ?>">−</button>
                                <input type="number" name="qty[<?= $item['id'] ?>]" id="qty_<?= $item['id'] ?>" 
                                       value="1" min="1" max="20" class="item-qty-input" style="width:40px; text-align:center; border:1px solid #ddd; border-radius:5px; padding:5px;">
                                <button type="button" class="qty-btn" data-action="increase" data-target="qty_<?= $item['id'] ?>">+</button>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    
                    <!-- Customer Details -->
                    <div style="margin-top: 40px;">
                        <div class="section-header" style="text-align: left; margin-bottom: 30px;">
                            <span class="section-subtitle">Step 2</span>
                            <h2 class="section-title">Your Details</h2>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="cust_name">Full Name *</label>
                                <input type="text" id="cust_name" name="customer_name" class="form-control" 
                                       placeholder="Enter your full name" required>
                            </div>
                            <div class="form-group">
                                <label for="cust_email">Email *</label>
                                <input type="email" id="cust_email" name="customer_email" class="form-control" 
                                       placeholder="your@email.com" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="cust_phone">Phone *</label>
                                <input type="tel" id="cust_phone" name="customer_phone" class="form-control" 
                                       placeholder="+94 XX XXX XXXX" required>
                            </div>
                            <div class="form-group">
                                <label for="cust_address">Delivery Address *</label>
                                <input type="text" id="cust_address" name="customer_address" class="form-control" 
                                       placeholder="Full delivery address" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cust_notes">Special Instructions</label>
                            <textarea id="cust_notes" name="notes" class="form-control" 
                                      placeholder="Any special requests or delivery notes..."></textarea>
                        </div>
                    </div>
            </div>
            
            <!-- Order Summary -->
            <div>
                <div class="order-summary">
                    <h3><i class="fas fa-receipt"></i> Order Summary</h3>
                    
                    <div class="summary-line">
                        <span>Subtotal</span>
                        <span id="orderSubtotal">Rs. 0.00</span>
                    </div>
                    <div class="summary-line">
                        <span>Delivery Fee</span>
                        <span id="orderDelivery">Rs. 0.00</span>
                    </div>
                    <div class="summary-total">
                        <span>Total</span>
                        <span id="orderTotal">Rs. 0.00</span>
                    </div>
                    
                    <!-- Payment Methods -->
                    <div class="payment-methods">
                        <label>Payment Method *</label>
                        <div class="payment-options">
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="payhere" checked>
                                <span>💳 PayHere (Card / Bank)</span>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="cod">
                                <span>💵 Cash on Delivery</span>
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-top: 20px;">
                        <i class="fas fa-check-circle"></i> Place Order
                    </button>
                    
                    <p style="text-align:center; margin-top:15px; font-size:0.8rem; color:var(--text-light);">
                        <i class="fas fa-lock"></i> Secured by PayHere Payment Gateway
                    </p>
                </div>
            </div>
            </form>
        </div>
    </div>
</section>

<!-- PayHere Payment Script -->
<script src="https://www.payhere.lk/lib/payhere.js"></script>
<script>
// PayHere Integration
var payHereForm = document.getElementById('orderForm');
payHereForm.addEventListener('submit', function(e) {
    var paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    
    if (paymentMethod === 'payhere') {
        e.preventDefault();
        
        // Check if items are selected
        var checked = document.querySelectorAll('.order-item-checkbox:checked');
        if (checked.length === 0) {
            alert('Please select at least one item');
            return;
        }
        
        // Get total
        var total = document.getElementById('orderTotal').textContent;
        var amount = parseFloat(total.replace('Rs. ', ''));
        
        // Submit form via AJAX to get order ID, then initiate payment
        var formData = new FormData(payHereForm);
        
        fetch('<?= SITE_URL ?>/payment_process.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Initialize PayHere payment
                var payment = {
                    "sandbox": true,
                    "merchant_id": "<?= PAYHERE_MERCHANT_ID ?>",
                    "return_url": "<?= PAYHERE_RETURN_URL ?>&order_id=" + data.order_id,
                    "cancel_url": "<?= PAYHERE_CANCEL_URL ?>&order_id=" + data.order_id,
                    "notify_url": "<?= PAYHERE_NOTIFY_URL ?>",
                    "order_id": data.order_id,
                    "items": "Bean & Brew Order #" + data.order_id,
                    "amount": amount.toFixed(2),
                    "currency": "LKR",
                    "first_name": document.getElementById('cust_name').value.split(' ')[0],
                    "last_name": document.getElementById('cust_name').value.split(' ').slice(1).join(' '),
                    "email": document.getElementById('cust_email').value,
                    "phone": document.getElementById('cust_phone').value,
                    "address": document.getElementById('cust_address').value,
                    "city": "Colombo",
                    "country": "Sri Lanka"
                };
                
                payhere.onCompleted = function(orderId) {
                    alert('Payment completed! Order ID: ' + orderId);
                    window.location.href = '<?= SITE_URL ?>/payment_callback.php?status=success&order_id=' + orderId;
                };
                
                payhere.onDismissed = function() {
                    alert('Payment dismissed');
                };
                
                payhere.onError = function(error) {
                    alert('Payment error: ' + error);
                };
                
                payhere.startPayment(payment);
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
    // For COD, let the form submit normally
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>