<?php
require_once '../base.php';
$pageTitle = 'Checkout';
include '../header.php';

$err = temp('error');
$suc = temp('success');
?>

<style>
    body { background-color: #0a0a0a; }

    .checkout-wrapper {
        max-width: 600px;
        margin: 50px auto;
        padding: 0 20px;
    }

    .checkout-wrapper h1 {
        color: white;
        grid-column: 1 / -1;
        font-size: 28px;
        margin-bottom: 10px;
    }

    .section-card {
        background: #1a1a1a;
        border: 1px solid #2a2a2a;
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 24px;
    }

    .section-card h2 {
        color: white;
        font-size: 16px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid #2a2a2a;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        color: #aaa;
        font-size: 13px;
        margin-bottom: 7px;
        font-weight: 500;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        background: #111;
        border: 1px solid #333;
        border-radius: 8px;
        color: white;
        padding: 12px 14px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s;
        box-sizing: border-box;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: #667eea;
    }

    .form-group select option {
        background: #1a1a1a;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    /* Order Summary */
    .summary-card {
        background: #1a1a1a;
        border: 1px solid #2a2a2a;
        border-radius: 16px;
        padding: 28px;
        position: sticky;
        top: 20px;
        height: fit-content;
    }

    .summary-card h2 {
        color: white;
        font-size: 16px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid #2a2a2a;
    }

    .summary-product {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 0;
        border-bottom: 1px solid #222;
    }

    .summary-product img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        background: #222;
    }

    .summary-product-info { flex: 1; }
    .summary-product-name { color: white; font-size: 14px; font-weight: 600; }
    .summary-product-sub { color: #888; font-size: 12px; margin-top: 3px; }

    .summary-totals { margin-top: 20px; }
    .summary-row {
        display: flex;
        justify-content: space-between;
        color: #aaa;
        font-size: 14px;
        margin-bottom: 10px;
    }
    .summary-row.total {
        color: white;
        font-size: 18px;
        font-weight: 700;
        border-top: 1px solid #333;
        padding-top: 14px;
        margin-top: 6px;
    }

    /* TnG QR */
    .tng-qr-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 16px;
        padding: 10px 0;
    }

    .tng-logo-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .tng-logo-circle {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: linear-gradient(135deg, #00bcd4, #0288d1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .tng-brand {
        color: white;
        font-size: 18px;
        font-weight: 700;
    }

    .tng-brand span {
        color: #00bcd4;
    }

    .tng-qr-box {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 0 30px rgba(0,188,212,0.25);
    }

    .tng-amount-badge {
        background: #00bcd4;
        color: white;
        font-size: 15px;
        font-weight: 700;
        padding: 8px 20px;
        border-radius: 20px;
        letter-spacing: 0.5px;
    }

    .tng-instructions {
        color: #888;
        font-size: 13px;
        text-align: center;
        line-height: 1.7;
    }

    .tng-instructions strong {
        color: #ccc;
    }

    .tng-steps {
        display: flex;
        gap: 10px;
        margin-top: 6px;
    }

    .tng-step {
        background: #111;
        border: 1px solid #2a2a2a;
        border-radius: 10px;
        padding: 10px 12px;
        text-align: center;
        font-size: 12px;
        color: #aaa;
        flex: 1;
    }

    .tng-step .step-num {
        display: block;
        width: 22px;
        height: 22px;
        background: #00bcd4;
        color: white;
        border-radius: 50%;
        font-weight: 700;
        font-size: 12px;
        line-height: 22px;
        margin: 0 auto 6px;
    }

    .save-shipping-btn {
        width: 100%;
        background: #1a1a1a;
        color: #aaa;
        border: 1px solid #333;
        border-radius: 10px;
        padding: 13px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 4px;
        transition: all 0.3s;
    }
    .save-shipping-btn:hover { border-color: #667eea; color: white; }
    .save-shipping-btn.saved { background: #1a2d1a; border-color: #27ae60; color: #2ecc71; }

    .place-order-btn {
        width: 100%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 16px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        margin-top: 20px;
        transition: opacity 0.2s;
    }
    .place-order-btn:hover { opacity: 0.9; }

    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        grid-column: 1 / -1;
    }
    .alert-error { background: #2d1a1a; border: 1px solid #c0392b; color: #e74c3c; }
    .alert-success { background: #1a2d1a; border: 1px solid #27ae60; color: #2ecc71; }

    #cardFields { display: block; }

    @media (max-width: 768px) {
        .checkout-wrapper { grid-template-columns: 1fr; }
        .summary-card { position: static; }
    }
</style>

<div class="checkout-wrapper">
    <h1>Checkout</h1>

    <?php if ($err): ?>
        <div class="alert alert-error"><?= htmlspecialchars($err) ?></div>
    <?php endif; ?>
    <?php if ($suc): ?>
        <div class="alert alert-success"><?= htmlspecialchars($suc) ?></div>
    <?php endif; ?>

    <form method="POST" action="checkoutprocess.php" id="checkoutForm">
        <div>

            <!-- SHIPPING -->
            <div class="section-card">
                <h2>📦 Shipping Details</h2>
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

            <!-- PAYMENT -->
            <div class="section-card">
                <h2>📱 Payment — Touch 'n Go eWallet</h2>

                <div class="tng-qr-wrapper">
                    <div class="tng-qr-box">
                        <img src="/images/icon/TnG code.png" alt="Touch 'n Go eWallet QR Code" style="width:220px; height:auto; border-radius:8px;">
                    </div>

                    <div class="tng-amount-badge" id="tngAmount">RM 15.00</div>

                    <div class="tng-instructions">
                        <strong>Scan QR with your TnG eWallet app to pay</strong><br>
                        Amount updates automatically when you select a product
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

        <!-- ORDER SUMMARY SIDEBAR -->
        <div>
            <div class="summary-card">
                <h2>Order Summary</h2>

                <div id="summaryProduct" style="display:none;" class="summary-product">
                    <div class="summary-product-info">
                        <div class="summary-product-name" id="summaryName">—</div>
                        <div class="summary-product-sub" id="summaryQty">Qty: 1</div>
                    </div>
                    <div style="color:white; font-weight:700;" id="summaryItemTotal">RM 0.00</div>
                </div>

                <div id="summaryEmpty" style="color:#555; font-size:14px; padding: 20px 0; text-align:center;">
                    No product selected yet
                </div>

                <div class="summary-totals">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span id="subtotal">RM 0.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>RM 15.00</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span id="grandTotal">RM 15.00</span>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

<script>
const SHIPPING = 15;

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