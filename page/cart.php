<?php
include '../base.php';

$action    = trim($_GET['action'] ?? '');
$productID = trim($_GET['id'] ?? '');

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($action && $productID) {
    if ($action === 'increase') {
        $stmt = $_db->prepare("SELECT stockAmount FROM products WHERE productID = ? LIMIT 1");
        $stmt->execute([$productID]);
        $product = $stmt->fetch();

        if ($product && $_SESSION['cart'][$productID] < $product->stockAmount) {
            $_SESSION['cart'][$productID]++;
        } else {
            temp('error', 'No more stock available.');
        }

    } elseif ($action === 'decrease') {
        if (isset($_SESSION['cart'][$productID])) {
            $_SESSION['cart'][$productID]--;
            if ($_SESSION['cart'][$productID] <= 0) {
                unset($_SESSION['cart'][$productID]);
            }
        }

    } elseif ($action === 'remove') {
        unset($_SESSION['cart'][$productID]);
        temp('success', 'Item removed.');
    }

    redirect('../page/cart.php');
}

$pageTitle = 'My Cart';
include '../header.php';

$items   = [];
$total   = 0;

if (!empty($_SESSION['cart'])) {
    $ids         = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $_db->prepare("SELECT * FROM products WHERE productID IN ($placeholders)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();

    foreach ($products as $p) {
        $qty     = $_SESSION['cart'][$p->productID];
        $subtotal = $p->productPrice * $qty;
        $total   += $subtotal;
        $items[] = (object)[
            'productID'   => $p->productID,
            'productName' => $p->productName,
            'productPrice'=> $p->productPrice,
            'imageFile'   => $p->imageFile,
            'stockAmount' => $p->stockAmount,
            'quantity'    => $qty,
            'subtotal'    => $subtotal,
        ];
    }
}

$success = temp('success');
$error   = temp('error');
?>

<link rel="stylesheet" href="/app.css">

<div class="cart-page">
<main class="container">
    <h1 class="cart-title">My Cart</h1>

    <?php if ($success): ?>
        <div class="success-box"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="error-box"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (empty($items)): ?>
        <div class="cart-empty">
            <p>Your cart is empty.</p>
            <a href="../page/keyboards.php" class="btn-checkout">Shop Now</a>
        </div>
    <?php else: ?>
        <div class="cart-list">
            <?php foreach ($items as $item): ?>
                <div class="cart-item">
                    <div class="cart-item-info">
                        <div class="cart-item-name"><?= htmlspecialchars($item->productName) ?></div>
                        <div class="cart-item-price">RM <?= number_format($item->productPrice, 2) ?></div>
                    </div>
                    <div class="cart-item-controls">
                        <a href="cart.php?id=<?= $item->productID ?>&action=decrease">−</a>
                        <span><?= $item->quantity ?></span>
                        <a href="cart.php?id=<?= $item->productID ?>&action=increase">+</a>
                    </div>
                    <div class="cart-item-subtotal">
                        RM <?= number_format($item->subtotal, 2) ?>
                    </div>
                    <a href="cart.php?id=<?= $item->productID ?>&action=remove" class="cart-remove">✕</a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="cart-summary">
            <div class="cart-total">Total: <strong>RM <?= number_format($total, 2) ?></strong></div>
            <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</main>
</div>

<?php include '../footer.php'; ?>