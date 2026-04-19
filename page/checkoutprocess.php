<?php
include '../base.php';
auth();

if (!is_post()) {
    redirect('checkout.php');
}


$userID = $_SESSION['users']->userID ?? null;

if (!$userID) {
    temp('error', 'You must be logged in to place an order.');
    redirect('/security/login.php');
}

$fullName = post('fullName');
$phone    = post('phone');
$address1 = post('address1');
$city     = post('city');
$postcode = post('postcode');


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


$lastOrder = $_db->query("SELECT orderID FROM orders ORDER BY purchasedAt DESC LIMIT 1")->fetch();
if ($lastOrder) {

    $lastNum = (int) preg_replace('/[^0-9]/', '', $lastOrder->orderID);
    $nextNum = $lastNum + 1;
} else {
    $nextNum = 1;
}
$orderID = 'ORD' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);

$shipping = 15.00;
$subtotal = 0;
foreach ($products as $p) {
    $qty = $_SESSION['cart'][$p->productID];
    $subtotal += $p->productPrice * $qty;
}
$totalAmount = $subtotal + $shipping;

try {
    $stmt = $_db->prepare("
        INSERT INTO orders (orderID, userID, totalAmount, orderStatus, shippingAddress, phoneNumber, purchasedAt)
        VALUES (?, ?, ?, 'Processing', ?, ?, NOW())
    ");
    $stmt->execute([
        $orderID,
        $userID,
        $totalAmount,
        $shippingAddress,
        $phone
    ]);

    $itemStmt = $_db->prepare("
        INSERT INTO order_items (orderID, productID, quantity, priceAtPurchase)
        VALUES (?, ?, ?, ?)
    ");
    foreach ($products as $p) {
        $qty = $_SESSION['cart'][$p->productID];
        $itemStmt->execute([
            $orderID,
            $p->productID,
            $qty,
            $p->productPrice
        ]);
    }

    $_SESSION['cart'] = [];

    $_SESSION['last_order_id'] = $orderID;

    temp('success', 'Order placed successfully! Your order is now being processed.');
    redirect('receipt.php');

} catch (PDOException $e) {
    temp('error', 'Failed to place order: ' . $e->getMessage());
    redirect('checkout.php');
}
