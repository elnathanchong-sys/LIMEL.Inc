<?php
include '../base.php';

// Redirect to login if not logged in
if (!isset($_SESSION['users']) || $_SESSION['users']->role !== 'Member') {
    redirect('/security/login.php');
}

$users  = $_SESSION['users'];
$userID = $users->userID;

// Basic search — filter by order ID or status
$search = trim($_GET['search'] ?? '');

// Fetch this user's orders from the database
// Also fetch all items for each order using a subquery
if (!empty($search)) {
    $stmt = $_db->prepare("
        SELECT o.*
        FROM orders o
        WHERE o.userID = ?
          AND (o.orderID LIKE ? OR o.orderStatus LIKE ?)
        ORDER BY o.purchasedAt DESC
    ");
    $searchTerm = "%$search%";
    $stmt->execute([$userID, $searchTerm, $searchTerm]);
} else {
    $stmt = $_db->prepare("
        SELECT * FROM orders
        WHERE userID = ?
        ORDER BY purchasedAt DESC
    ");
    $stmt->execute([$userID]);
}

$orders = $stmt->fetchAll();

// For each order, fetch its items so we can display product names
$orderItems = [];
foreach ($orders as $ord) {
    $iStmt = $_db->prepare("
        SELECT oi.*, p.productName, p.imageFile
        FROM order_items oi
        JOIN products p ON oi.productID = p.productID
        WHERE oi.orderID = ?
    ");
    $iStmt->execute([$ord->orderID]);
    $orderItems[$ord->orderID] = $iStmt->fetchAll();
}

$pageTitle = 'My Orders';
include '../header.php';
?>

<style>
/* Consistent Background */
body { 
    background-color: black; 
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
}

.order-container {
    max-width: 900px;
    margin: 40px auto;
    padding: 0 20px;
}

/* Search bar */
.order-search-form {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}
.order-search-form input {
    flex: 1;
    padding: 10px 16px;
    border-radius: 8px;
    border: 1px solid #333;
    background: #1a1a1a;
    color: #f2f2f2;
    font-size: 14px;
}
.order-search-form button {
    padding: 10px 20px;
    background: #2a2a2a;
    color: #e0e0e0;
    border: 1px solid #444;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
}
.order-search-form button:hover { background: #333; }
.order-search-clear {
    padding: 10px 14px;
    background: transparent;
    color: #888;
    border: 1px solid #333;
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px;
    text-decoration: none;
    display: flex;
    align-items: center;
}
.order-search-clear:hover { color: #ccc; }

/* White Container style */
.order-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #eee;
}

.order-item {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px 0;
    border-bottom: 1px solid #eee;
}
.order-item:last-child { border-bottom: none; }

.order-item img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    background: #f9f9f9;
}

.order-info { flex: 1; }
.order-name { font-weight: bold; font-size: 17px; color: #2d3436; }
.order-meta { font-size: 14px; color: #636e72; margin-top: 4px; }
.order-date { font-size: 13px; color: #b2bec3; margin-top: 4px; }
.order-id-label { font-size: 12px; color: #999; margin-top: 2px; }

.order-status {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
}

/* Status Colors */
.status-processing { background: #fff3cd; color: #856404; }
.status-shipped    { background: #cce5ff; color: #004085; }
.status-delivered  { background: #d4edda; color: #155724; }
.status-cancelled  { background: #f8d7da; color: #721c24; }

.back-link {
    display: inline-block;
    margin-bottom: 20px;
    color: #667eea;
    text-decoration: none;
    font-weight: bold;
}

/* View receipt link */
.receipt-link {
    display: inline-block;
    margin-top: 6px;
    font-size: 12px;
    color: #667eea;
    text-decoration: none;
}
.receipt-link:hover { text-decoration: underline; }

.no-orders-msg {
    text-align: center;
    color: #888;
    padding: 40px 20px;
    font-size: 15px;
}

/* Order group header (shows per-order info) */
.order-group {
    margin-bottom: 24px;
    border: 1px solid #eee;
    border-radius: 12px;
    overflow: hidden;
}
.order-group-header {
    background: #f8f8f8;
    padding: 12px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    border-bottom: 1px solid #eee;
}
.order-group-id { font-weight: 700; color: #333; font-size: 14px; }
.order-group-date { color: #999; font-size: 13px; }
.order-group-total { font-weight: 600; color: #2d3436; font-size: 14px; }
.order-group-body { padding: 0 20px; background: #fff; }
</style>

<main class="order-container">
    <a href="myaccount.php" class="back-link">← Back to Dashboard</a>
    
    <h1 style="color: #ffffff; margin-bottom: 20px;">My Orders</h1>

    <!-- Search bar -->
    <form method="GET" class="order-search-form">
        <input type="text"
               name="search"
               placeholder="Search by Order ID or status..."
               value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
        <?php if (!empty($search)): ?>
            <a href="myorders.php" class="order-search-clear">✕ Clear</a>
        <?php endif; ?>
    </form>

    <?php if (empty($orders)): ?>
        <div class="order-card">
            <div class="no-orders-msg">
                <?php if (!empty($search)): ?>
                    No orders found for "<?= htmlspecialchars($search) ?>".
                    <br><a href="myorders.php" style="color:#667eea;">Show all orders</a>
                <?php else: ?>
                    You haven't placed any orders yet.<br>
                    <a href="../page/keyboards.php" style="color:#667eea;">Start shopping →</a>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>

        <?php foreach ($orders as $ord): ?>
        <?php $items = $orderItems[$ord->orderID] ?? []; ?>
        <div class="order-group">

            <!-- Order header: shows ID, date, total, status -->
            <div class="order-group-header">
                <div>
                    <div class="order-group-id">Order #<?= htmlspecialchars($ord->orderID) ?></div>
                    <div class="order-group-date"><?= date('d M Y, h:i A', strtotime($ord->purchasedAt)) ?></div>
                </div>
                <div style="display:flex; align-items:center; gap:12px;">
                    <div class="order-group-total">RM <?= number_format($ord->totalAmount, 2) ?></div>
                    <span class="order-status status-<?= strtolower($ord->orderStatus) ?>">
                        <?= htmlspecialchars($ord->orderStatus) ?>
                    </span>
                    <a href="receipt.php?id=<?= urlencode($ord->orderID) ?>" class="receipt-link">🧾 View Receipt</a>
                </div>
            </div>

            <!-- Each product in this order -->
            <div class="order-group-body">
                <?php foreach ($items as $item): ?>
                <div class="order-item">
                    <img src="/images/<?= htmlspecialchars($item->imageFile) ?>"
                         alt="<?= htmlspecialchars($item->productName) ?>">
                    <div class="order-info">
                        <div class="order-name"><?= htmlspecialchars($item->productName) ?></div>
                        <div class="order-meta">
                            Qty: <?= $item->quantity ?> &nbsp;|&nbsp;
                            <strong>RM <?= number_format($item->priceAtPurchase * $item->quantity, 2) ?></strong>
                        </div>
                        <div class="order-date">
                            Unit price: RM <?= number_format($item->priceAtPurchase, 2) ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        </div>
        <?php endforeach; ?>

    <?php endif; ?>

</main>

<?php include '../footer.php'; ?>
