<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIMEL</title>
    <link rel="stylesheet" href="/app.css">
    <link rel="tab icon" href="/images/Logo.png">
</head>
    
<body>

    <?php include '../header.php';?>

    <main>

        <div class="mouse-grid">

            <div class="mouse-card">
                <button class="wishlist-btn" onclick="toggleFavorite(event, this, 'Limel M Hyperlight')">🤍</button>
                <div class="mouse-image-container">
                    <img src="/images/mouse pic/Limel M Hyperlight.png">
                </div>
                <div class="card-line">_________________________________</div>
                <div class="mouse-title">Limel M Hyperlight</div>
                <div class="mouse-desc">Built with all-new nano coating.</div>
                <div class="mouse-price">MYR 499.99</div>
            </div>
       
            <div class="mouse-card">
                <button class="wishlist-btn" onclick="toggleFavorite(event, this, 'Limel M Hyperlight PrintStream')">🤍</button>
                <div class="mouse-image-container">
                    <img src="/images/mouse pic/Limel M Hyperlight PrintStream.png">
                </div>
                <div class="card-line">_________________________________</div>
                <div class="mouse-title">Limel M Hyperlight PrintStream</div>
                <div class="mouse-desc">Limited "PrintStream" edition Mice!</div>
                <div class="mouseprice">MYR 399.99</div>
            </div>

            <div class="mouse-card">
                <button class="wishlist-btn" onclick="toggleFavorite(event, this, 'Limel M OP1 8k')">🤍</button>
                <div class="mouse-image-container">
                    <img src="/images/mouse pic/Limel M OP1 8k.png">
                </div>
                <div class="card-line">_________________________________</div>
                <div class="mouse-title">Limel M OP1 8k</div>
                <div class="mouse-desc">1% Gaming Mouse</div>
                <div class="mouse-price">MYR 299.99</div>
            </div>

            <div class="mouse-card">
                <button class="wishlist-btn" onclick="toggleFavorite(event, this, 'Limel Glorious Model-D')">🤍</button>
                <div class="mouse-image-container">
                    <img src="/images/mouse pic/Limel-Glorious-Model-D.png">
                </div>
                <div class="card-line">_________________________________</div>
                <div class="mouse-title">Limel Glorious Model-D</div>
                <div class="mouse-desc">Elnathan's stolen mouse</div>
                <div class="mouse-price">MYR 909.99</div>
            </div>

            <div class="mouse-card">
                <button class="wishlist-btn" onclick="toggleFavorite(event, this, 'Limel Frozen Egypt LW')">🤍</button>
                <div class="mouse-image-container">
                    <img src="/images/mouse pic/Limel_Frozen_Egypt_LW(Top view).png">
                </div>
                <div class="card-line">_________________________________</div>
                <div class="mouse-title">Limel Frozen Egypt LW</div>
                <div class="mouse-desc">An old Pharaoh's gaming mouse(also stolen)</div>
                <div class="mouse-price">MYR 2.00</div>
            </div>

            <div class="mouse-card">
                <button class="wishlist-btn" onclick="toggleFavorite(event, this, 'Limel GlacierLW Translucent Purple')">🤍</button>
                <div class="mouse-image-container">
                    <img src="/images/mouse pic/Limel_GlacierLW_Translucent_Purple(Top view).png">
                </div>
                <div class="card-line">_________________________________</div>
                <div class="mouse-title">Limel GlacierLW Translucent Purple</div>
                <div class="mouse-desc">a very good purple mice.👍</div>
                <div class="mouse-price">MYR 9,999.99</div>
            </div>
	</div>

    <script>
function toggleFavorite(e, button, productID) {
    e.preventDefault();
    e.stopPropagation();

    const isActive = button.classList.toggle('active');
    button.textContent = isActive ? '❤️' : '🤍';
    console.log(isActive ? 'Saved ' : 'Removed ', productID);
}
</script>

    </main>
</body>
    <?php include '../footer.php'; ?>


    