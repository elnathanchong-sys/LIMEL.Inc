<?php
include '../base.php';
auth();

if (!is_post()) {
    redirect('checkout.php');
}

$userID   = $_user->id;
$fullName = post('fullName');
$phone    = post('phone');
$address1 = post('address1');
$address2 = post('address2');
$city     = post('city');
$postcode = post('postcode');

// Validation
if (!$fullName || !$phone || !$address1 || !$city || !$postcode) {
    temp('error', 'Please fill in all required shipping details.');
    redirect('checkout.php');
}

// Fetch cart items
$stmt = $_db->prepare("
    SELECT cart.*, products.productPrice 
    FROM cart 
    JOIN products ON cart.productID = products.productID 
    WHERE cart.userID = ?
");
$stmt->execute([$userID]);
$cartItems = $stmt->fetchAll();

if (empty($cartItems)) {
    temp('error', 'Your cart is empty.');
    redirect('cart.php');
}

// Build full address
$shippingAddress = $address1;
if ($address2) $shippingAddress .= ', ' . $address2;
$shippingAddress .= ', ' . $city . ', ' . $postcode . ', Malaysia';

// Generate next orderID e.g. ORD005
$lastOrder = $_db->query("SELECT orderID FROM orders ORDER BY orderID DESC LIMIT 1")->fetch();
if ($lastOrder) {
    $lastNum = (int) substr($lastOrder->orderID, 3);
    $nextNum = $lastNum + 1;
} else {
    $nextNum = 1;
}

$shipping = 15.00;

try {
    foreach ($cartItems as $item) {
        $orderID    = 'ORD' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
        $itemTotal  = ($item->price * $item->cartQuantity) + $shipping;
        $shipping   = 0; // only charge shipping once

        $stmt = $_db->prepare("
            INSERT INTO orders (orderID, userID, productID, orderHistoryQuantity, totalPrice, orderStatus, shippingAddress, phoneNumber, purchasedAt)
            VALUES (?, ?, ?, ?, ?, 'Processing', ?, ?, NOW())
        ");
        $stmt->execute([
            $orderID,
            $userID,
            $item->productID,
            $item->cartQuantity,
            $itemTotal,
            $shippingAddress,
            $phone
        ]);

        $nextNum++;
    }

    // Clear cart
    $stmt = $_db->prepare("DELETE FROM cart WHERE userID = ?");
    $stmt->execute([$userID]);

    temp('success', 'Order placed successfully! Your order is now being processed.');
    redirect('myorders.php');

} catch (PDOException $e) {
    temp('error', 'Failed to place order: ' . $e->getMessage());
    redirect('checkout.php');
}