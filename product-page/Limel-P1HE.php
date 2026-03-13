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
            <div class="product-images">
                <img src="/images/keyboard-pic/Limel-P1HE White.png" alt="Keyboard" style="width: 100%; max-width: 500px;">
            </div>
            
            <div class="product-info">
                <h1><?php echo $keyboardName; ?></h1>
                <p>Price: RM<?php echo $price; ?></p>

            <hr>

            <p>Theme: White</p>
            </div>
        </div>
    </main>

    <?php include '../footer.php'; ?>
</body>