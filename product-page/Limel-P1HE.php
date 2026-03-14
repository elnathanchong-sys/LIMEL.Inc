<?php
    $keyboardName = "Limel P1HE Magnetic Keyboard";
    $price = "899.00";
    $stockStatus = "true";
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
        <div class="Limel-P1HE-White">    
            <div class="product-images mid">
                <input type="radio" name="p" id="p1" checked>
                <input type="radio" name="p" id="p2">
                <input type="radio" name="p" id="p3">
                <input type="radio" name="p" id="p4">
                <input type="radio" name="p" id="p5">
                <div class="product-image f1">
                    <img src="\images\keyboard-pic\Limel-P1HE White\Limel-P1-HE-01.png" alt="Keyboard" style="width: 100%; max-width: 500px;">
                </div>
                <div class="product-image">
                    <img src="\images\keyboard-pic\Limel-P1HE White\Limel-P1-HE-02.png" alt="Keyboard" style="width: 100%; max-width: 500px;">
                </div>
                <div class="product-image">
                    <img src="\images\keyboard-pic\Limel-P1HE White\Limel-P1-HE-03.png" alt="Keyboard" style="width: 100%; max-width: 500px;">
                </div>
                <div class="product-image">
                    <img src="\images\keyboard-pic\Limel-P1HE White\Limel-P1-HE-04.png" alt="Keyboard" style="width: 100%; max-width: 500px;">
                </div>
                <div class="product-image">
                    <img src="\images\keyboard-pic\Limel-P1HE White\Limel-P1-HE-05.png" alt="Keyboard" style="width: 100%; max-width: 500px;">
                </div>            
            </div> 


            <div class="product-navigation">
                <label for="p1" class="dot"></label>
                <label for="p2" class="dot"></label>
                <label for="p3" class="dot"></label>
                <label for="p4" class="dot"></label>
                <label for="p5" class="dot"></label>
            </div>
<!--            <div class="product-info">
                <h1><?php echo $keyboardName; ?></h1>
                <p>Price: RM<?php echo $price; ?></p>

            <hr>

            <p>Theme: White</p>
-->         </div>
        </div>
    </main>

    <?php include '../footer.php'; ?>
</body>