<?php
include '../base.php';

$productID = trim($_GET['id'] ?? '');
// Accept optional quantity param (default 1)
$qty = max(1, (int)($_GET['qty'] ?? 1));

if (empty($productID)) {
    redirect('../page/cart.php');
}

$stmt = $_db->prepare("SELECT * FROM products WHERE productID = ? LIMIT 1");
$stmt->execute([$productID]);
$product = $stmt->fetch();

if (!$product) {
    temp('error', 'Product not found.');
    redirect('../product-page/Limel-K60-HE-v2.php');
}

if ($product->stockAmount <= 0) {
    temp('error', 'This product is out of stock.');
    redirect('../product-page/Limel-K60-HE-v2.php');
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add the chosen quantity (instead of always adding 1)
if (isset($_SESSION['cart'][$productID])) {
    $_SESSION['cart'][$productID] += $qty;
} else {
    $_SESSION['cart'][$productID] = $qty;
}

// Make sure we don't go over stock
if ($_SESSION['cart'][$productID] > $product->stockAmount) {
    $_SESSION['cart'][$productID] = $product->stockAmount;
}

// Redirect back to the product page with ?added=1 so the page can show the toast
$referer = $_SERVER['HTTP_REFERER'] ?? '../page/keyboards.php';
// Append ?added=1 to show success toast
if (strpos($referer, '?') !== false) {
    redirect($referer . '&added=1');
} else {
    redirect($referer . '?added=1');
}
