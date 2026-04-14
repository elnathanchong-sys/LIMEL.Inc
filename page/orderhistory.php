<?php
require_once '../base.php';
$pageTitle = 'My Orders';
include '../header.php';

$userID = $_SESSION['userID'] ?? null;

$stmt = $_db->prepare("
    SELECT orders.*, products.productName, products.imageFile, products.productPrice
    FROM orders
    JOIN products ON orders.productID = products.productID
    WHERE orders.userID = ?
    ORDER BY orders.purchasedAt DESC
");
$stmt->execute([$userID]);
$orders = $stmt->fetchAll();
?>

<style>
    .order-item {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 20px 0;
        border-bottom: 1px solid #eee;
    }
    .order-item img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
    }
    .order-info { flex: 1; }
    .order-name { font-weight: bold; font-size: 16px; }
    .order-meta { font-size: 13px; color: #888; margin-top: 4px; }
    .order-date { font-size: 13px; color: #aaa; margin-top: 4px; }
    .order-status {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: bold;
    }
    .status-processing { background: #fff3cd; color: #856404; }
    .status-shipped    { background: #cce5ff; color: #004085; }
    .status-delivered  { background: #d4edda; color: #155724; }
    .status-cancelled  { background: #f8d7da; color: #721c24; }
</style>

<div class="container">
    <div class="header">
        <h1>My Orders</h1>
    </div>
    <div class="card">

        <?php if (empty($orders)): ?>
            <p style="color:#888; text-align:center; padding:40px 0;">
                You have no orders yet.
            </p>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
            <div class="order-item">
                <img src="<?= htmlspecialchars($order->imageFile) ?>" alt="product">
                <div class="order-info">
                    <div class="order-name"><?= encode($order->productName) ?></div>
                    <div class="order-meta">
                        Qty: <?= $order->quantity ?> &nbsp;|&nbsp;
                        RM <?= number_format($order->totalPrice, 2) ?>
                    </div>
                    <div class="order-date">
                        Ordered on: <?= date('d M Y', strtotime($order->purchasedAt)) ?>
                    </div>
                </div>
                <div class="order-status status-<?= strtolower($order->orderStatus) ?>">
                    <?= encode($order->orderStatus) ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</div>

<?php include '../footer.php'; ?>
