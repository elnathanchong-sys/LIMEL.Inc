<?php
require_once '../base.php';

$stmt = $_db->prepare("SELECT * FROM products WHERE productID = 'P1HE' LIMIT 1");
$stmt->execute();
$p = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIMEL - <?= htmlspecialchars($p->productName) ?></title>
    <link rel="stylesheet" href="/app.css">
</head>
<body>
    <?php include '../header.php'; ?>
    <main>
        <div class="keyboard-product-page">    
            <div class="product-container">    
                <div class="product-images">
                    <input type="radio" name="p" id="p1" checked>
                    <input type="radio" name="p" id="p2">
                    <input type="radio" name="p" id="p3">
                    <input type="radio" name="p" id="p4">
                    <input type="radio" name="p" id="p5">

                    <div class="slides">    
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
                        <h1><?= htmlspecialchars($p->productName) ?></h1>
                        <p>Price: RM<?= number_format($p->productPrice, 2) ?></p>
                        <hr>
                    </div>

                    <?php if (temp('success')): ?>
                        <div class="success-box"><?= htmlspecialchars(temp('success')) ?></div>
                    <?php endif; ?>
                    <?php if (temp('error')): ?>
                        <div class="error-box"><?= htmlspecialchars(temp('error')) ?></div>
                    <?php endif; ?>

                    <div class="product-select">
                        <div class="buy-now">
                            <a href="/page/addtocart.php?id=<?= $p->productID ?>">
                                <button type="button">Add to Cart</button>
                            </a>
                        </div>
                    </div>

                    <div class="product-details">
                        <br><p>Theme: <?= htmlspecialchars($p->productTheme) ?></p><br>
                        <p>The Fully Assembled version includes:</p>
                        <ul>
                            <li><?= htmlspecialchars($p->productLayers) ?></li>
                            <li><?= htmlspecialchars($p->productKeycaps) ?></li>
                            <li><?= htmlspecialchars($p->productSwitches) ?></li>
                        </ul><br>
                        <p><?= $p->productDesc ?></p><br>
                        <p style="color:red;">Please drop your email on "Notify Me When Available" if the product model is out of stock.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include '../footer.php'; ?>
</body>