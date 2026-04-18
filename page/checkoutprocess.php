<?php
include '../base.php';
auth();

if (!is_post()) {
    redirect('checkout.php');
}

// 1. Get the logged-in user's ID safely
$userID   = $_users->userID ?? null; 

if (!$userID) {
    temp('error', 'You must be logged in to place an order.');
    redirect('/security/login.php');
}

$fullName = post('fullName');
$phone    = post('phone');
$address1 = post('address1');
$city     = post('city');
$postcode = post('postcode');

// 2. Validate Shipping Info
if (!$fullName || !$phone || !$address1 || !$city || !$postcode) {
    temp('error', 'Please fill in all required shipping details.');
    redirect('checkout.php');
}

if (empty($_SESSION['cart'])) {
    temp('error', 'Your cart is empty.');
    redirect('cart.php');
}

$ids = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $_db->prepare("SELECT * FROM products WHERE productID IN ($placeholders)");
$stmt->execute($ids);
$products = $stmt->fetchAll();

$shippingAddress = $address1 . ', ' . $city . ', ' . $postcode . ', Malaysia';

$lastOrder = $_db->query("SELECT orderID FROM orders ORDER BY CAST(SUBSTRING(orderID, 4) AS UNSIGNED) DESC LIMIT 1")->fetch();
if ($lastOrder) {
    $lastNum = (int) substr($lastOrder->orderID, 3);
    $nextNum = $lastNum + 1;
} else {
    $nextNum = 1;
}

$shipping = 15.00;

try {
    foreach ($products as $p) {
        $qty = $_SESSION['cart'][$p->productID];
        $orderID    = 'ORD' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
        
        $itemTotal  = ($p->productPrice * $qty) + $shipping;
        $shipping   = 0; 

        $stmt = $_db->prepare("
            INSERT INTO orders (orderID, userID, productID, orderHistoryQuantity, totalPrice, orderStatus, shippingAddress, phoneNumber, purchasedAt)
            VALUES (?, ?, ?, ?, ?, 'Processing', ?, ?, NOW())
        ");
        
        $stmt->execute([
            $orderID,
            $userID,
            $p->productID,
            $qty,
            $itemTotal,
            $shippingAddress,
            $phone
        ]);

        $nextNum++;
    }

    unset($_SESSION['cart']);

    temp('success', 'Order placed successfully! Your order is now being processed.');
    redirect('myorders.php');

} catch (PDOException $e) {
    temp('error', 'Failed to place order: ' . $e->getMessage());
    redirect('checkout.php');
}