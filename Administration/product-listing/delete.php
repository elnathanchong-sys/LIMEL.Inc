<?php
include '../../base.php';

auth('Admin');

// ----------------------------------------------------------------------------

if (is_post()) {
    $productID = req('productID');

    if (!$productID) {
        temp('error', 'Invalid product ID');
        redirect('inventory.php');
    }

    // Delete photo
    $stm = $_db->prepare('SELECT imageFile FROM products WHERE productID = ?');
    $stm->execute([$productID]);
    $photo = $stm->fetchColumn();
    
    if ($photo) {
        $filePath = __DIR__ . '/../../images/new-image-save/' . $photo;

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    $stm = $_db->prepare('DELETE FROM products WHERE productID = ?');
    $stm->execute([$productID]);
    
    temp('info', 'Record deleted');
}

redirect('inventory.php');

?>
// ----------------------------------------------------------------------------
