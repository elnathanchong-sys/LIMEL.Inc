<?php
include '../base.php';

$orderID = $_GET['id'] ?? $_SESSION['last_order_id'] ?? null;
if (!$orderID) {
    redirect('myorders.php');
}
$pageTitle = 'Receipt - ' . htmlspecialchars($orderID);
include '../header.php';

// Fetch the main order info
$stmt = $_db->prepare("SELECT * FROM orders WHERE orderID = ?");
$stmt->execute([$orderID]);
$order = $stmt->fetch();

if (!$order) {
    redirect('myorders.php');
}

// Fetch all items in this order (joined with products to get name and image)
$itemStmt = $_db->prepare("
    SELECT oi.*, p.productName, p.imageFile
    FROM order_items oi
    JOIN products p ON oi.productID = p.productID
    WHERE oi.orderID = ?
");
$itemStmt->execute([$orderID]);
$orderItems = $itemStmt->fetchAll();
?>

<link rel="stylesheet" href="/app.css">

<main class="receipt-container">
    <section id="printableReceipt" class="receipt-card">
        
        <header class="receipt-header">
            <h1>LIMEL.Inc</h1>
            <p class="receipt-subtitle">Official E-Receipt</p>
            <div class="receipt-meta">
                <span class="meta-id">Order ID: <?= htmlspecialchars($order->orderID) ?></span>
                <span class="meta-date">Date: <?= date('d M Y, h:i A', strtotime($order->purchasedAt)) ?></span>
            </div>
        </header>

        <article class="receipt-address">
            <h3>Shipping Details</h3>
            <address>
                <?= htmlspecialchars($order->shippingAddress) ?><br>
                Contact: <?= htmlspecialchars($order->phoneNumber) ?>
            </address>
        </article>

        <!-- All items in this order -->
        <table class="receipt-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderItems as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item->productName) ?></td>
                    <td><?= $item->quantity ?></td>
                    <td>RM <?= number_format($item->priceAtPurchase, 2) ?></td>
                    <td>RM <?= number_format($item->priceAtPurchase * $item->quantity, 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Shipping</th>
                    <td>RM <?= number_format($order->totalAmount - array_sum(array_map(fn($i) => $i->priceAtPurchase * $i->quantity, $orderItems)), 2) ?></td>
                </tr>
                <tr>
                    <th colspan="3">Total Paid</th>
                    <td>RM <?= number_format($order->totalAmount, 2) ?></td>
                </tr>
            </tfoot>
        </table>

        <footer class="receipt-footer">
            <p>Thank you for choosing LIMEL.Inc!</p>
            <small>This is a computer-generated receipt. Order Status: <strong><?= htmlspecialchars($order->orderStatus) ?></strong></small>
        </footer>
        
    </section>

    <nav class="receipt-actions">
        <button onclick="window.print()" class="btn-print">Print / Save as PDF</button>
        <a href="myorders.php" class="btn-secondary">View All Orders</a>
        <a href="../index.php" class="btn-secondary">Continue Shopping</a>
    </nav>
</main>

<!-- Hide nav buttons when printing so only the receipt prints -->
<style>
@media print {
    .receipt-actions, header, footer, nav { display: none !important; }
    body { background: white !important; }
    .receipt-card { box-shadow: none !important; border: none !important; }
}
</style>

<?php include '../footer.php'; ?>
