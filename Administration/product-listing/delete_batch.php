<?php

include '../../base.php';

auth('Admin');

// Only handle POST requests
if (!is_post()) {
    redirect('inventory.php');
}

$productIDs = $_POST['productIDs'] ?? [];

if (empty($productIDs)) {
    temp('error', 'No products selected for deletion.');
    redirect('inventory.php');
}

$deleted = 0;
$errors  = 0;

foreach ($productIDs as $productID) {
    $productID = trim($productID);
    if (empty($productID)) continue;

    try {
        // Delete the product image file first
        $stm = $_db->prepare('SELECT imageFile FROM products WHERE productID = ?');
        $stm->execute([$productID]);
        $photo = $stm->fetchColumn();

        if ($photo) {
            $filePath = __DIR__ . '/../../images/new-image-save/' . $photo;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Delete the product from DB
        $stm = $_db->prepare('DELETE FROM products WHERE productID = ?');
        $stm->execute([$productID]);
        $deleted++;

    } catch (PDOException $e) {
        $errors++;
    }
}

if ($errors > 0) {
    temp('error', "Deleted $deleted product(s). $errors could not be deleted (may be linked to orders).");
} else {
    temp('info', "$deleted product(s) deleted successfully.");
}

redirect('inventory.php');
?>
