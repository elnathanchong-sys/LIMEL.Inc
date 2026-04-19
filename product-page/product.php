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

<main>
        <div class="keyboard-product-page">    
            <div class="product-container">    
                <div class="product-images">
                    <img src ="/images/new-image-save/<?= htmlspecialchars($product->imageFile) ?>" alt="<?= htmlspecialchars($product->productName) ?>">
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
                            <button type="button" id="buy-now" onclick="addToCart()">Add to cart</button>
                        </div>
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
