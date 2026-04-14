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
}

// ----------------------------Stop if errors exist--------------------------------------


$_title = 'Insert new product';
include '../sidebar.php';
?>

<p>
    <button data-get="inventory.php">Back</button>
</p>

<form method="post" class="form" enctype="multipart/form-data" TODO>
    <label for="productID">ProductID</label>
    <?= html_text('productID', 'maxlength="20"  data-upper') ?>
    <?= err('productID') ?>

    <label for="productName">Product Name</label>
    <?= html_text('productName', 'maxlength="255"') ?>
    <?= err('productName') ?>

    <label for="productTheme">Product Theme</label>
    <?= html_text('productTheme', 'maxlength="20"') ?>
    <?= err('productTheme') ?>

    <label for="productLayers">Product Layers</label>
    <?= html_text('productLayers', 'maxlength="200"') ?>
    <?= err('productLayers') ?>

    <label for="productSwitches">Product Switches</label>
    <?= html_text('productSwitches', 'maxlength="200"') ?>
    <?= err('productSwitches') ?>

    <label for="productDesc">Product Description</label>
    <?= html_text('productDesc') ?>
    <?= err('productDesc') ?>

    <label for="productPrice">Price</label>
    <?= html_number('productPrice', 0.01, 9999.99, 0.01) ?>
    <?= err('productPrice') ?>

    <label for="stockAmount">Stock Amount</label>
    <?= html_number('stockAmount', 0, null, 1) ?>

    <label for="categoryID">Category</label>
    <select id="categoryID" name="categoryID">
        <option value="">Select Category</option>
        <?php
        $stm = $_db->query('SELECT categoryID, categoryName FROM category');
        while ($row = $stm->fetch()) {
            $selected = ($row['categoryID'] == ($categoryID ?? '-')) ? 'selected' : '';
            echo "<option value='{$row['categoryID']}' $selected>{$row['categoryName']}</option>";
        }
        ?>

    <!--<label for="photo">Photo</label>
    <label class="upload" tabindex="0">
        <?= html_file('photo', 'image/*', 'hidden') ?>
        <img src="/images/photo.jpg">
    </label>
    <?= err('photo') ?>
    -->

    <section>
        <button>Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>