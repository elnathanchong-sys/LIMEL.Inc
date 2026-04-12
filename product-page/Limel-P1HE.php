<?php
    require '../base.php';

    $stm = $_db->prepare("SELECT * FROM products");
    $stm->execute([]);
    $products= $stm->fetchAll();
    $p = $products[1];

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
                        <h1><?php echo $p->productName; ?></h1>
                        <p>Price: RM<?php echo $p->productPrice; ?></p>

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
                        <p>Theme: <?php echo $p->productTheme; ?></p>
                        <p>The Fully Assembled version includes:</p>
                        <ul>
                            <li><?php echo $p->productLayers; ?></li>
                            <li><?php echo $p->productKeycaps; ?></li>
                            <li><?php echo $p->productSwitches; ?></li>
                        </ul>
                        <p> </p>
                        <p><?php echo $p->productDesc; ?></p>
                        <p style="color:red;">Please drop your emailon "Notify Me When Available" if the product model is out of stock.</p>
                </div>
            </div>
            
        </div>
    </main>

    <?php include '../footer.php'; ?>
</body>