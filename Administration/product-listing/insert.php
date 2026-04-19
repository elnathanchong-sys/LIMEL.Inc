<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../base.php';

auth('Admin');

// ----------------------------------------------------------------------------

if (is_post()) {
    $productID    = req('productID');
    $productName  = req('productName');
    $productTheme = req('productTheme');
    $productLayers = req('productLayers');
    $productKeycaps = req('productKeycaps');
    $productSwitches = req('productSwitches');
    $productDesc = req('productDesc');
    $categoryID = req('categoryID');
    $productPrice = req('productPrice');
    $stockAmount = req('stockAmount');  
    $f = $_FILES['imageFile'] ?? null;

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

    //Validate: CategoryID
    if ($categoryID == '') {
        $_err['categoryID'] = 'Required';
    }

    if (!preg_match('/^[KM]\d{3}$/', $categoryID)) {
        $_err['categoryID'] = 'Format must be K001 or M001';
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

    // Validate: photo (file)
    if (!$f || $f['error'] !== UPLOAD_ERR_OK) {
        $_err['imageFile'] = 'Required';
    }
    else if (!str_starts_with($f['type'], 'image/')) {
        $_err['imageFile'] = 'Must be image';
    }
    else if ($f['size'] > 40 * 1024 * 1024) { 
        $_err['imageFile'] = 'Image too large (max 40MB)';
    }

    //databse that executes to save the data
    if (!$_err) {

        // Ensure folder exists
        $folder = __DIR__ . '/../../images/new-image-save';

        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        try {
            $imageFile = upload_image($f, $folder);
        } catch (Exception $e) {
            $_err['imageFile'] = $e->getMessage();
        }

        if ($_err) {
            temp('error', 'Image upload failed');
            redirect(); 
        }

        // Insert into DB
        $stm = $_db->prepare("
            INSERT INTO products
            (productID, productName, productTheme, productLayers,
            productKeycaps, productSwitches, productDesc, categoryID,
            productPrice, stockAmount, imageFile)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stm->execute([
            $productID,
            $productName,
            $productTheme,
            $productLayers,
            $productKeycaps,
            $productSwitches,
            $productDesc,
            $categoryID,
            $productPrice,
            $stockAmount,
            $imageFile
        ]);

        temp('info', 'Record inserted successfully');
        redirect('inventory.php');
    }
}
   

// ----------------------------Stop if errors exist--------------------------------------

include '../sidebar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/app.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="./app.js"></script>
</head>

<body class="admin-body">
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

                        <label for="categoryID">Product Category</label><br>
                        <?= html_text('categoryID') ?>
                        <?= err('categoryID') ?><br><br>

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
                            <?= html_file('imageFile', 'image/*', 'hidden') ?>
                            <div class="insert-upload-box">
                                <img src ="/images/icon/insert-new-image-icon.png">
                            </div>
                        </label>
                        <?= err('imageFile') ?><br>
                    
                    <div class="insert-buttons-section">
                        <section>
                            <button class="insert-buttons-reset" type="reset">Reset</button>
                            <button class="insert-buttons" type="submit">Submit</button>
                        </section>
                    </div>
                </div>

            </form>
        </div>

        <div class="insert-back-button">
            <p>
                <button class="back-button" data-get="inventory.php">Back</button>
            </p>
        </div>
    </div>
</body>