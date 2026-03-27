<?php
//keyboards.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="app.css">
</head>

<body>
    <?php include '../header.php'; ?>
    
    <main class="cart-container">
        
        <section class="empty-cart-section">
            <h1 class="cart-title">Your Cart</h1>
            <p class="empty-text">It's empty right here...</p>
            <p class="empty-subtext">Anything you like so far?</p>
        </section>

        <section class="recommendation-section">
            <h2 class="recommendation-title">You Might Like..</h2>
            
            <div class="keyboard-grid">
                
                <div class="keyboard-card">
                    <div class="keyboard-image-container">
                        <img src="/images/keyboard-pic/Limel K60HE v2 (Top View).png">
                    </div>
                    <div class="keyboard-brand">Limel</div>
                    <div class="keyboard-title">Limel K60 HE v2</div>
                    <div class="keyboard-price">MYR 690.00</div>
                </div>

                <div class="keyboard-card">
                    <div class="mouse-image-container">
                        <img src="/images/mouse pic/Limel M Hyperlight.png">
                    </div>
                    <div class="keyboard-brand">Limel</div>
                    <div class="keyboard-title">Limel M Hyperlight</div>
                    <div class="keyboard-price">MYR 499.99</div>
                </div>

                <div class="keyboard-card">
                    <div class="keyboard-image-container">
                        <img src="/images/keyboard-pic/Limel K80HE (Top View).png">
                    </div>
                    <div class="keyboard-brand">Limel</div>
                    <div class="keyboard-title">Limel K80HE</div>
                    <div class="keyboard-price">MYR 729.00</div>
                </div>

            </div>
        </section>

    </main>
        
    <?php include '../footer.php'; ?>
</body>

<?php include 'header.php'; ?>

<?php include '../footer.php'; ?>