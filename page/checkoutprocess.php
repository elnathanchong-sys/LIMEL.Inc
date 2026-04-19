<?php
include '../base.php';
auth();

if (!is_post()) {
    redirect('checkout.php');
}

// 1. Get the logged-in user's ID from session
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

// 2. Validate Shipping Info
if (!$fullName || !$phone || !$address1 || !$city || !$postcode) {
    temp('error', 'Please fill in all required shipping details.');
    redirect('checkout.php');
}

if (empty($_SESSION['cart'])) {
    temp('error', 'Your cart is empty.');
    redirect('cart.php');
}

// 3. Fetch products from DB so we have accurate prices
$ids = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $_db->prepare("SELECT * FROM products WHERE productID IN ($placeholders)");
$stmt->execute($ids);
$products = $stmt->fetchAll();

// 4. Build full shipping address string
$shippingAddress = $address1 . ', ' . $city . ', ' . $postcode . ', Malaysia';

// 5. Generate a unique Order ID (e.g. ORD001, ORD002...)
$lastOrder = $_db->query("SELECT orderID FROM orders ORDER BY purchasedAt DESC LIMIT 1")->fetch();
if ($lastOrder) {
    // Extract the number from the last order ID and add 1
    $lastNum = (int) preg_replace('/[^0-9]/', '', $lastOrder->orderID);
    $nextNum = $lastNum + 1;
} else {
    $nextNum = 1;
}
$orderID = 'ORD' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);

// 6. Calculate totals
$shipping = 15.00;
$subtotal = 0;
foreach ($products as $p) {
    $qty = $_SESSION['cart'][$p->productID];
    $subtotal += $p->productPrice * $qty;
}
$totalAmount = $subtotal + $shipping;

try {
    // 7. Insert the main order row (one row per checkout, not per product)
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

    // 8. Insert each product as a separate row in order_items
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

    // 9. Clear the session cart (fixed syntax — was: $_SESSION['cart' =] = [])
    $_SESSION['cart'] = [];

    // 10. Save last order ID so receipt.php can show it
    $_SESSION['last_order_id'] = $orderID;

    temp('success', 'Order placed successfully! Your order is now being processed.');
    redirect('receipt.php');

} catch (PDOException $e) {
    temp('error', 'Failed to place order: ' . $e->getMessage());
    redirect('checkout.php');
}
