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
                        <div class="quantity-button">
                            <button type="button" id=plus>+</button>
                            <input type="text" id="quantity" value="1">
                            <button type="button" id=minus>-</button>
                        </div>

                        <div class="buy-now">
                            <button type="button" id=buy-now>Add to cart</button>
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
                        <p style="color:red;">Please drop your email on "Notify Me When Available" if the product model is out of stock.</p>
                </div>
            </div>
            
        </div>
    </main>

    <?php include '../footer.php'; ?>
</body>

