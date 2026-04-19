<?php
include '../base.php';

$orderID = $_GET['id'] ?? $_SESSION['last_order_id'] ?? null;
if (!$orderID) {
    redirect('myorders.php');
}
$pageTitle = 'Receipt - ' . htmlspecialchars($orderID);
include '../header.php';

$stmt = $_db->prepare("
    SELECT o.*, p.productName 
    FROM orders o 
    JOIN products p ON o.productID = p.productID 
    WHERE o.orderID = ?
");
$stmt->execute([$orderID]);
$order = $stmt->fetch();

if (!$order) {
    redirect('myorders.php');
}
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

        <table class="receipt-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= htmlspecialchars($order->productName) ?></td>
                    <td><?= $order->orderHistoryQuantity ?></td>
                    <td>RM <?= number_format($order->totalPrice, 2) ?></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">Total Paid</th>
                    <td>RM <?= number_format($order->totalPrice, 2) ?></td>
                </tr>
            </tfoot>
        </table>

        <footer class="receipt-footer">
            <p>Thank you for choosing LIMEL.Inc!</p>
            <small>This is a computer-generated receipt.</small>
        </footer>
        
    </section>

    <nav class="receipt-actions">
        <button onclick="window.print()" class="btn-print">Print / Save as PDF</button>
        <a href="myorders.php" class="btn-secondary">View All Orders</a>
        <a href="../index.php" class="btn-secondary">Continue Shopping</a>
    </nav>
</main>

<?php include '../footer.php'; ?>