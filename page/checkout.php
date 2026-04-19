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
                        <input type="text" name="address1" id="address1" placeholder="Street address" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="city" id="city" placeholder="City" required>
                        </div>
                        <div class="form-group">
                            <label>Postcode</label>
                            <input type="text" name="postcode" id="postcode" placeholder="e.g. 88000" required>
                        </div>
                    </div>

                    <!-- Interactive Map: click to drop a pin and auto-fill address -->
                    <div style="margin-top:14px;">
                        <label style="font-size:13px; color:#888; margin-bottom:6px; display:block;">
                            📍 Pin Your Location <span style="color:#555; font-size:11px;">(click on the map to auto-fill your address)</span>
                        </label>
                        <div id="checkoutMap" style="width:100%; height:220px; border-radius:10px; border:1px solid #333; z-index:1;"></div>
                    </div>

                    <!-- Load Leaflet.js (free map, no API key needed) -->
                    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
                    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

                    <script>
                    // ── Leaflet map setup ──
                    // Default center: Malaysia
                    const map = L.map('checkoutMap').setView([4.2105, 108.9758], 6);

                    // Use OpenStreetMap tiles (free, no API key)
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(map);

                    let marker = null;

                    // When user clicks on the map, drop a pin and reverse-geocode
                    map.on('click', function(e) {
                        const lat = e.latlng.lat;
                        const lng = e.latlng.lng;

                        // Move or place the pin
                        if (marker) {
                            marker.setLatLng([lat, lng]);
                        } else {
                            marker = L.marker([lat, lng]).addTo(map);
                        }

                        // Reverse geocode using Nominatim (free, no API key)
                        fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
                            .then(r => r.json())
                            .then(data => {
                                const addr = data.address || {};

                                // Fill in the address fields from the geocode result
                                const streetParts = [addr.house_number, addr.road].filter(Boolean).join(' ');
                                document.querySelector('[name="address1"]').value = streetParts || (addr.suburb || '');
                                document.querySelector('[name="city"]').value     = addr.city || addr.town || addr.county || '';
                                document.querySelector('[name="postcode"]').value = addr.postcode || '';

                                // Show a popup on the pin
                                marker.bindPopup(data.display_name).openPopup();
                            })
                            .catch(() => {
                                marker.bindPopup('Location pinned!').openPopup();
                            });
                    });

                    // Fix map rendering inside flex layout (sometimes tiles don't load until resize)
                    setTimeout(() => map.invalidateSize(), 300);
                    </script>

                    <button type="button" id="saveShippingBtn" onclick="saveShipping()" class="save-shipping-btn">
                        Save Shipping Details
                    </button>
                </div>

                <div class="section-card">
                    <h2>Payment — Bank Transfer</h2>
                    <p style="color:#666; font-size:13px; margin-bottom:16px;">
                        Transfer the exact amount to one of the bank accounts below, then fill in your reference number.
                    </p>

                    <!-- Fake bank details for demo -->
                    <div style="display:flex; flex-direction:column; gap:12px; margin-bottom:18px;">

                        <!-- Maybank -->
                        <div style="background:#242424; border:1px solid #2f2f2f; border-radius:10px; padding:14px 16px;">
                            <div style="color:#f2c14e; font-weight:600; font-size:13px; margin-bottom:6px;">🏦 Maybank</div>
                            <div style="color:#bbb; font-size:13px;">Account Name: <strong style="color:#e0e0e0;">LIMEL INC SDN BHD</strong></div>
                            <div style="color:#bbb; font-size:13px;">Account No: <strong style="color:#e0e0e0;">5642 8801 2234</strong></div>
                        </div>

                        <!-- CIMB -->
                        <div style="background:#242424; border:1px solid #2f2f2f; border-radius:10px; padding:14px 16px;">
                            <div style="color:#e07b54; font-weight:600; font-size:13px; margin-bottom:6px;">🏦 CIMB Bank</div>
                            <div style="color:#bbb; font-size:13px;">Account Name: <strong style="color:#e0e0e0;">LIMEL INC SDN BHD</strong></div>
                            <div style="color:#bbb; font-size:13px;">Account No: <strong style="color:#e0e0e0;">7012 0045 6789</strong></div>
                        </div>

                    </div>

                    <!-- Amount reminder -->
                    <div style="background:#1f2a1f; border:1px solid #2a3f2a; border-radius:8px; padding:12px 14px; margin-bottom:16px;">
                        <div style="color:#6fcf97; font-size:13px; font-weight:600;">
                            ✅ Transfer Amount: RM <?= number_format($total, 2) ?>
                        </div>
                        <div style="color:#555; font-size:11px; margin-top:3px;">Includes RM <?= number_format($shipping, 2) ?> shipping</div>
                    </div>

                    <!-- Reference number input -->
                    <div class="form-group">
                        <label style="font-size:13px; color:#888;">Your Transfer Reference / Receipt Number</label>
                        <input type="text" name="bankRef" placeholder="e.g. TXN20260419001" style="margin-top:6px;">
                    </div>
                    <input type="hidden" name="paymentMethodBank" value="bank_transfer">
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

// updateMap() is no longer used — the Leaflet map above handles pinning
</script>

<?php include '../footer.php'; ?>
