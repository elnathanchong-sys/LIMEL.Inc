<?php
include '../base.php';

$productID = trim($_GET['id'] ?? '');

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

if (isset($_SESSION['cart'][$productID])) {
    $_SESSION['cart'][$productID]++;
} else {
    $_SESSION['cart'][$productID] = 1;
}

temp('success', 'Item added to cart.');

$referer = $_SERVER['HTTP_REFERER'] ?? '../page/keyboards.php';
redirect($referer);