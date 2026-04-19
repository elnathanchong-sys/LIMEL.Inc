<?php
require_once '../base.php';
$pageTitle = 'Checkout';
include '../header.php';

$err = temp('error');
$suc = temp('success');

// Fetch cart items
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

// Fixed shipping cost and total — defined here so they work throughout the page
$shipping = 15.00;
$total    = $subtotal + $shipping;
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
                        <input type="text" name="fullName" id="fullName" placeholder="Your full name" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" name="phone" placeholder="e.g. 0123456789" required pattern="[0-9]+" oninput="this.value=this.value.replace(/\D/g,'')">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address1" id="address1" placeholder="Street address" required oninput="updateMap()">
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="city" id="city" placeholder="City" required oninput="updateMap()">
                        </div>
                        <div class="form-group">
                            <label>Postcode</label>
                            <input type="text" name="postcode" id="postcode" placeholder="e.g. 88000" required oninput="updateMap()">
                        </div>
                    </div>

                    <!-- Google Maps preview: shows the address location as user types -->
                    <div style="margin-top:14px;">
                        <label style="font-size:13px; color:#888; margin-bottom:6px; display:block;">📍 Location Preview</label>
                        <iframe
                            id="mapFrame"
                            width="100%"
                            height="200"
                            style="border:0; border-radius:10px;"
                            loading="lazy"
                            allowfullscreen
                            referrerpolicy="no-referrer-when-downgrade"
                            src="https://maps.google.com/maps?q=Malaysia&output=embed">
                        </iframe>
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
                            Total amount: RM <?= number_format($total, 2) ?> (includes RM <?= number_format($shipping, 2) ?> shipping)
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
// ── Save Shipping feedback ──
function saveShipping() {
    const fields = ['fullName', 'phone', 'address1', 'city', 'postcode'];
    const allFilled = fields.every(id => {
        const el = document.querySelector(`[name="${id}"]`);
        return el && el.value.trim() !== '';
    });

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

// ── Google Maps preview — updates when user types address fields ──
let mapTimer = null;
function updateMap() {
    clearTimeout(mapTimer);
    // Wait 800ms after user stops typing before updating map
    mapTimer = setTimeout(() => {
        const address = document.getElementById('address1').value.trim();
        const city    = document.getElementById('city').value.trim();
        const postcode= document.getElementById('postcode').value.trim();

        if (city || address) {
            const query = encodeURIComponent([address, city, postcode, 'Malaysia'].filter(Boolean).join(', '));
            document.getElementById('mapFrame').src =
                'https://maps.google.com/maps?q=' + query + '&output=embed';
        }
    }, 800);
}
</script>

<?php include '../footer.php'; ?>
