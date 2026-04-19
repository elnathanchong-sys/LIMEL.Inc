<?php
include '../base.php';

$productID = $_GET['productID'] ?? null;

if (!$productID) {
    echo "No product selected";
    exit;
}

$stm = $_db->prepare("SELECT * FROM products WHERE productID = ?");
$stm->execute([$productID]);
$product = $stm->fetch();

if (!$product) {
    echo "Product not found";
    exit;
}

// Check if this product is in the logged-in user's wishlist
$isWishlisted = false;
$sessionUser = $_SESSION['users'] ?? null;
if ($sessionUser) {
    $wStmt = $_db->prepare("SELECT wishlistID FROM wishlist WHERE userID = ? AND productID = ?");
    $wStmt->execute([$sessionUser->userID, $productID]);
    $isWishlisted = (bool) $wStmt->fetch();
}

// Check if item was just added to cart (for success toast)
$justAdded = isset($_GET['added']) && $_GET['added'] == '1';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIMEL</title>
    <link rel="stylesheet" href="/app.css">
</head>
<body>
    <?php include '../header.php';?>

<!-- Toast notification for Add to Cart feedback -->
<?php if ($justAdded): ?>
<div id="cartToast" class="cart-toast cart-toast-show">
    ✅ Added to cart successfully!
</div>
<?php endif; ?>

<style>
/* ── Toast notification ── */
.cart-toast {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background: #1e7e34;
    color: #fff;
    padding: 14px 24px;
    border-radius: 10px;
    font-size: 15px;
    font-family: 'Roboto', sans-serif;
    box-shadow: 0 4px 20px rgba(0,0,0,0.4);
    z-index: 9999;
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.3s ease, transform 0.3s ease;
}
.cart-toast-show {
    opacity: 1;
    transform: translateY(0);
}

/* ── Wishlist heart button ── */
.wishlist-btn {
    background: none;
    border: 2px solid #555;
    border-radius: 8px;
    padding: 10px 18px;
    cursor: pointer;
    font-size: 20px;
    color: #888;
    transition: border-color 0.2s, color 0.2s, transform 0.15s;
    display: inline-flex;
    align-items: center;
    gap: 7px;
}
.wishlist-btn:hover { border-color: #e74c3c; color: #e74c3c; transform: scale(1.05); }
.wishlist-btn.wishlisted { border-color: #e74c3c; color: #e74c3c; }
.wishlist-btn.wishlisted .heart-icon { fill: #e74c3c; }
.wishlist-label { font-size: 13px; font-family: 'Roboto', sans-serif; }
</style>

<main>
        <div class="keyboard-product-page">    
            <div class="product-container">    
                <div class="product-images">
                    <input type="radio" name="p" id="p1" checked>
                    <input type="radio" name="p" id="p2">
                    <input type="radio" name="p" id="p3">
                    <input type="radio" name="p" id="p4">
                    <input type="radio" name="p" id="p5">

                    <div class="product-slides">    
                        <div class="product-image">
                            <img src="/images/keyboard-pic/Limel-P1HE White/Limel-P1-HE-01.png" alt="Keyboard">
                        </div>
                        <div class="product-image">
                            <img src="/images/keyboard-pic/Limel-P1HE White/Limel-P1-HE-02.png" alt="Keyboard">
                        </div>
                        <div class="product-image">
                            <img src="/images/keyboard-pic/Limel-P1HE White/Limel-P1-HE-03.png" alt="Keyboard">
                        </div>
                        <div class="product-image">
                            <img src="/images/keyboard-pic/Limel-P1HE White/Limel-P1-HE-04.png" alt="Keyboard">
                        </div>
                        <div class="product-image">
                            <img src="/images/keyboard-pic/Limel-P1HE White/Limel-P1-HE-05.png" alt="Keyboard">
                        </div>            
                    </div>
                    
                    <div class="product-navigation">
                        <label for="p1" class="dot"></label>
                        <label for="p2" class="dot"></label>
                        <label for="p3" class="dot"></label>
                        <label for="p4" class="dot"></label>
                        <label for="p5" class="dot"></label>
                    </div>
                </div>

                <div class="product-info">

                    <div class="product-header">
                        <h1><?= htmlspecialchars($product->productName) ?></h1>
                        <p>Price: RM<?= number_format($product->productPrice, 2) ?></p>

                        <hr>
                    </div>

                    <div class="product-select">
                        <!-- Quantity buttons: + increases, - decreases, min is 1 -->
                        <div class="quantity-button">
                            <button type="button" id="plus" onclick="changeQty(1)">+</button>
                            <input type="text" id="quantity" value="1" readonly>
                            <button type="button" id="minus" onclick="changeQty(-1)">-</button>
                        </div>

                        <div class="buy-now">
                            <!-- Add to Cart: goes to addtocart.php with productID and quantity -->
                            <button type="button" id="buy-now" onclick="addToCart()">Add to cart</button>
                        </div>

                        <!-- Wishlist heart button -->
                        <button type="button"
                            class="wishlist-btn <?= $isWishlisted ? 'wishlisted' : '' ?>"
                            id="wishlistBtn"
                            onclick="toggleWishlist()"
                            title="<?= $isWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' ?>">
                            <svg class="heart-icon" xmlns="http://www.w3.org/2000/svg"
                                 width="20" height="20" viewBox="0 0 24 24"
                                 fill="<?= $isWishlisted ? '#e74c3c' : 'none' ?>"
                                 stroke="#e74c3c" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                            <span class="wishlist-label" id="wishlistLabel">
                                <?= $isWishlisted ? 'Wishlisted' : 'Wishlist' ?>
                            </span>
                        </button>
                    </div>

                    <div class="product-details">
                        
                        <br><p>Theme: <?= htmlspecialchars($product->productTheme) ?></p><br>
                        
                        <p>The Fully Assembled version includes:</p>
                        <ul>
                            <li><?= htmlspecialchars($product->productLayers) ?></li>
                            <li><?= htmlspecialchars($product->productKeycaps) ?></li>
                            <li><?= htmlspecialchars($product->productSwitches) ?></li>
                        </ul><br>

                        <p>Description: <br><?= htmlspecialchars($product->productDesc) ?></p><br>
                        <p style="color:red;">Please drop your email on "Notify Me When Available" if the product model is out of stock.</p>
                </div>
            </div>
            
        </div>
    </main>

    <?php include '../footer.php'; ?>

<script>
    // ── Quantity controls ──
    function changeQty(delta) {
        const input = document.getElementById('quantity');
        let current = parseInt(input.value) || 1;
        current += delta;
        if (current < 1) current = 1;   // minimum is 1
        if (current > 99) current = 99; // reasonable max
        input.value = current;
    }

    // ── Add to Cart: redirect to addtocart.php with qty ──
    function addToCart() {
        const qty = document.getElementById('quantity').value;
        const productID = '<?= htmlspecialchars($productID) ?>';
        // Go to addtocart.php; it will add to session and redirect back here with ?added=1
        window.location.href = '/page/addtocart.php?id=' + productID + '&qty=' + qty;
    }

    // ── Wishlist toggle via AJAX ──
    function toggleWishlist() {
        const btn = document.getElementById('wishlistBtn');
        const label = document.getElementById('wishlistLabel');
        const heartIcon = btn.querySelector('.heart-icon');
        const productID = '<?= htmlspecialchars($productID) ?>';

        <?php if (!$sessionUser): ?>
        // Not logged in — redirect to login
        window.location.href = '/security/login.php';
        return;
        <?php endif; ?>

        // Send AJAX request to toggle wishlist
        fetch('/page/wishlist_toggle.php?productID=' + productID)
            .then(r => r.json())
            .then(data => {
                if (data.wishlisted) {
                    btn.classList.add('wishlisted');
                    heartIcon.setAttribute('fill', '#e74c3c');
                    label.textContent = 'Wishlisted';
                    btn.title = 'Remove from Wishlist';
                } else {
                    btn.classList.remove('wishlisted');
                    heartIcon.setAttribute('fill', 'none');
                    label.textContent = 'Wishlist';
                    btn.title = 'Add to Wishlist';
                }
            })
            .catch(() => {
                alert('Could not update wishlist. Please try again.');
            });
    }

    // ── Auto-hide the success toast after 3 seconds ──
    const toast = document.getElementById('cartToast');
    if (toast) {
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(20px)';
        }, 3000);
    }
</script>

</body>
</html>
