<?php
require_once '../base.php';
$pageTitle = 'Checkout';
include '../header.php';

$err = temp('error');
$suc = temp('success');

// Fetch cart items
// Replace your entire try/catch cart fetch with this:
$cartItems = [];
$subtotal = 0;

if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $_db->prepare("SELECT * FROM products WHERE productID IN ($placeholders)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();

    foreach ($products as $p) {
        $qty = $_SESSION['cart'][$p->productID];
        $cartItems[] = (object)[
            'productID'    => $p->productID,
            'productName'  => $p->productName,
            'productPrice' => $p->productPrice,
            'imageFile'    => $p->imageFile,
            'cartQuantity' => $qty,
        ];
        $subtotal += $p->productPrice * $qty;
    }
}
?>

<div class="checkout-wrapper">
    <h1>Checkout</h1>

    <?php if ($err): ?>
        <div class="alert alert-error"><?= htmlspecialchars($err) ?></div>
    <?php endif; ?>
    <?php if ($suc): ?>
        <div class="alert alert-success"><?= htmlspecialchars($suc) ?></div>
    <?php endif; ?>

    <form method="POST" action="checkoutprocess.php" id="checkoutForm">
        <div class="checkout-grid">

            <!-- LEFT: SHIPPING + PAYMENT -->
            <div class="left-col">
                <div class="section-card">
                    <h2>Shipping Details</h2>
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="fullName" placeholder="Your full name" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" name="phone" placeholder="e.g. 0123456789" required pattern="[0-9]+" oninput="this.value=this.value.replace(/\D/g,'')">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address1" placeholder="Street address" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="city" placeholder="City" required>
                        </div>
                        <div class="form-group">
                            <label>Postcode</label>
                            <input type="text" name="postcode" placeholder="e.g. 88000" required>
                        </div>
                    </div>
                    <button type="button" id="saveShippingBtn" onclick="saveShipping()" class="save-shipping-btn">
                        Save Shipping Details
                    </button>
                </div>

                <div class="section-card">
                    <h2>Payment — Touch 'n Go eWallet</h2>
                    <div class="tng-qr-wrapper">
                        <div class="tng-qr-box">
                            <img src="/images/icon/TnG.png" alt="Touch 'n Go eWallet QR Code" style="width:200px; height:auto; border-radius:8px;">
                        </div>
                        <div class="tng-amount-badge">RM <?= number_format($total, 2) ?></div>
                        <div class="tng-instructions">
                            <strong>Scan QR with your TnG eWallet app to pay</strong><br>
                            Total amount shown above
                        </div>
                        <div class="tng-steps">
                            <div class="tng-step"><span class="step-num">1</span>Open TnG App</div>
                            <div class="tng-step"><span class="step-num">2</span>Scan QR</div>
                            <div class="tng-step"><span class="step-num">3</span>Confirm Pay</div>
                        </div>
                    </div>
                    <input type="hidden" name="paymentMethod" value="tng_qr">
                </div>
            </div>

            <!-- RIGHT: ORDER SUMMARY -->
            <div class="right-col">
                <div class="section-card" style="box-sizing:border-box;">
                    <h2>Order Summary</h2>

                    <?php if (empty($cartItems)): ?>
                        <p style="color:#555; text-align:center; padding: 20px 0;">Your cart is empty.</p>
                    <?php else: ?>
                        <?php foreach ($cartItems as $item): ?>
                        <div class="order-item">
                            <img src="/images/<?= htmlspecialchars($item->imageFile) ?>" alt="<?= htmlspecialchars($item->productName) ?>">
                            <div class="order-item-info">
                                <div class="order-item-name"><?= htmlspecialchars($item->productName) ?></div>
                                <div class="order-item-qty">Qty: <?= $item->cartQuantity ?></div>
                            </div>
                            <div class="order-item-price">RM <?= number_format($item->productPrice * $item->cartQuantity, 2) ?></div>
                        </div>
                        <?php endforeach; ?>

                        <div class="summary-row"><span>Subtotal</span><span>RM <?= number_format($subtotal, 2) ?></span></div>
                        <div class="summary-row"><span>Shipping</span><span>RM <?= number_format($shipping, 2) ?></span></div>
                        <div class="summary-row total"><span>Total</span><span>RM <?= number_format($total, 2) ?></span></div>
                    <?php endif; ?>
                </div>

                <!-- Place Order Button -->

<button type="submit" form="checkoutForm" class="place-order-btn" style="margin-top: 20px;">
    Place Order
</button>
            </div>

        </div>
    </form>
</div>

<script>
function saveShipping() {
    const fields = ['fullName', 'phone', 'address1', 'city', 'postcode'];
    const allFilled = fields.every(id => document.querySelector(`[name="${id}"]`).value.trim() !== '');

    const btn = document.getElementById('saveShippingBtn');
    if (!allFilled) {
        btn.textContent = '⚠ Please fill in all shipping fields';
        btn.style.borderColor = '#c0392b';
        btn.style.color = '#e74c3c';
        setTimeout(() => {
            btn.textContent = 'Save Shipping Details';
            btn.style.borderColor = '#333';
            btn.style.color = '#aaa';
        }, 2500);
        return;
    }
    btn.classList.add('saved');
    btn.textContent = '✓ Saved';
}
</script>

<?php include '../footer.php'; ?>

// checkout