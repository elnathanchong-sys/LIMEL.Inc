<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/app.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="./app.js"></script>
</head>

<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
include './base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    $productID    = req('productID');
    $productName  = req('productName');
    $productTheme = req('productTheme');
    $productLayers = req('productLayers');
    $productKeycaps = req('productKeycaps');
    $productSwitches = req('productSwitches');
    $productDesc = req('productDesc');
    $productPrice = req('productPrice');
    $stockAmount = req('stockAmount');  
    $categoryID = req('categoryID');

    // Validate: id
    if ($productID == '') {
        $_err['productID'] = 'Required';
    }

    else if (!is_unique($productID, 'products', 'productID')) {
        $_err['productID'] = 'Duplicated';
    }

    // Validate: name
    if ($productName == '') {
        $_err['productName'] = 'Required';
    }
    else if (strlen($productName) > 255) {
        $_err['productName'] = 'Maximum 255 characters';
    }

    // Validate: Theme
    if ($productTheme == '') {
        $_err['productTheme'] = 'Required';
    }

    else if (strlen($productTheme) > 20) {
        $_err['productTheme'] = 'Maximum 20 characters';
    }

    // Validate: Layers
    if ($productLayers == '') {
        $_err['productLayers'] = 'Required';
    }

    else if (strlen($productLayers) > 200) {
        $_err['productLayers'] = 'Maximum 200 characters';
    }

    // Validate: Keycaps
    if ($productKeycaps == '') {
        $_err['productKeycaps'] = 'Required';
    }

    else if (strlen($productKeycaps) > 200) {
        $_err['productKeycaps'] = 'Maximum 200 characters';
    }

    // Validate: Switches
    if ($productSwitches == '') {
        $_err['productSwitches'] = 'Required';
    }

    else if (strlen($productSwitches) > 200) {
        $_err['productSwitches'] = 'Maximum 200 characters';
    }   

    // Validate: Description
    if ($productDesc == '') {
        $_err['productDesc'] = 'Required';
    }

    // Validate: price
    if ($productPrice == '') {
        $_err['productPrice'] = 'Required';
    }
    else if (!is_money($productPrice)) {
        $_err['productPrice'] = 'Must be money';
    }
    else if ($productPrice < 0.01 || $productPrice > 9999.99) {
        $_err['productPrice'] = 'Must between 0.01 - 9999.99';
    }

    // Validate: stock amount
    if ($stockAmount == '') {
        $_err['stockAmount'] = 'Required';
    }
    else if (!is_numeric($stockAmount) || $stockAmount < 0) {
        $_err['stockAmount'] = 'Must be non-negative number';
    }

    // Validate: category
    if ($categoryID == '') {
        $_err['categoryID'] = 'Required';
    }
    else if (!is_exists($categoryID, 'category', 'categoryID')) {
        $_err['categoryID'] = 'Invalid Category';
    }

    // Validate: photo (file)
//    if (!$f) {
//       $_err['photo'] = 'Required';
//    }
//    else if (!str_starts_with($f->type, 'image/')) {
//        $_err['photo'] = 'Must be image';
//    }
//    else if ($f->size > 1 * 1024 * 1024) {
//        $_err['photo'] = 'Maximum 1MB';
//    }
//
//    // DB operation
//    if (!$_err) {
//        // Save photo
//        $photo = save_photo($f, '../photos');
//
//        $stm = $_db->prepare('
//            INSERT INTO product (id, name, price, photo)
//            VALUES (?, ?, ?, ?)
//        ');
//        $stm->execute([$id, $name, $price, $photo]);
//
//        temp('info', 'Record inserted');
//        redirect('index.php');
//    }

    $f = get_file('photo');

        // Validate: photo (file)
        if ($f == null) {
            $_err['photo'] = 'Required';
        }
        else if (!str_starts_with($f->type, 'image/')) { // TODO
            $_err['photo'] = 'Must be image';
        }
        else if ($f->size > (1024*1024)) { // TODO
            $_err['photo'] = 'Maximum 1MB';
        }

        if (!$_err) {
            // TODO
            move_uploaded_file($f->tmp_name, 'uploads/' . $f->name);
            temp('info', 'Photo uploaded');
            redirect();
        }
    }
    
    if (!$_err) {

        $stm = $_db->prepare("
            INSERT INTO products
            (productID, productName, productTheme, productLayers,
            productKeycaps, productSwitches, productDesc,
            productPrice, stockAmount, categoryID)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stm->execute([
            $productID,
            $productName,
            $productTheme,
            $productLayers,
            $productKeycaps,
            $productSwitches,
            $productDesc,
            $productPrice,
            $stockAmount,
            $categoryID
        ]);

        redirect('inventory.php');
    }

    //upload photo
   

// ----------------------------Stop if errors exist--------------------------------------



include '../sidebar.php';
?>
<body>
    <div class="insert-container">
        <div class="insert-header">
            <div class="insert-title">
                <h1 class="title">Add new Products</h1>
            </div>
        </div>
        
        <div class="insert-body">
            <form method="post" class="insert-form" enctype="multipart/form-data">
                
                <div class="insert-top-section">

                    <div class="insert-table-left">
                        <label for="productID">ProductID</label><br>
                        <?= html_text('productID', 'maxlength="20" data-upper') ?>
                        <?= err('productID') ?><br><br>

                        <label for="productName">Product Name</label><br>
                        <?= html_text('productName', 'maxlength="255"') ?>
                        <?= err('productName') ?><br><br>

                        <label for="productTheme">Product Theme</label><br>
                        <?= html_text('productTheme', 'maxlength="20"') ?>
                        <?= err('productTheme') ?><br><br>

                        <label for="productLayers">Product Layers</label><br>
                        <?= html_text('productLayers', 'maxlength="200"') ?>
                        <?= err('productLayers') ?><br><br>

                        <label for="productKeycaps">Product Keycaps</label><br>
                        <?= html_text('productKeycaps', 'maxlength="200"') ?>
                        <?= err('productKeycaps') ?><br><br>
                    </div>

                    <div class="insert-table-right">
                        <label for="productSwitches">Product Switches</label><br>
                        <?= html_text('productSwitches', 'maxlength="200"') ?>
                        <?= err('productSwitches') ?><br><br>

                        <label for="productDesc">Product Description</label><br>
                        <?= html_text('productDesc') ?>
                        <?= err('productDesc') ?><br><br>

                        <label for="productPrice">Price</label><br>
                        <?= html_number('productPrice', 0.01, 9999.99, 0.01) ?>
                        <?= err('productPrice') ?><br><br>

                        <label for="stockAmount">Stock Amount</label><br>
                        <?= html_number('stockAmount', 0, null, 1) ?><br><br>
                        
                        
                    </div>

                </div>

                <div class="insert-table-bottom">
                    <label for="photo"></label>
                        <label class="insert-upload">
                            <?= html_file('photo', 'image/*', 'hidden') ?>
                            <div class="insert-upload-box">
                                <br>Drag & Drop Image Here<br>
                                <span>or click to upload</span><br><br>
                            </div>
                        </label>
                        <?= err('photo') ?><br>
                    
                    <div class="insert-buttons-section">
                        <section>
                            <button class="insert-buttons-reset" type="reset">Reset</button>
                            <button class="insert-buttons" type="submit">Submit</button>
                        </section>
                    </div>
                </div>

            </form>
        </div>

        <p>
            <button data-get="inventory.php">Back</button>
        </p>
    </div>
</body>