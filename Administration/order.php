<?php
$basePath = '../base.php'; 

if (file_exists($basePath)) {
    require_once $basePath;
} else {
    die("<div style='color: white; background: red; padding: 20px;'>
            CRITICAL ERROR: Could not find base.php at: $basePath. 
            Please check your file folders.
         </div>");
}

// ── Search: filter orders by Order ID or customer name ──
$search = trim($_GET['search'] ?? '');
$statusFilter = trim($_GET['status'] ?? '');

try {
    if (!isset($_db)) {
        throw new Exception("Database connection variable \$_db not found. Check base.php.");
    }

    // Build query with optional search and status filter
    $conditions = [];
    $params     = [];

    if (!empty($search)) {
        $conditions[] = "(o.orderID LIKE ? OR u.username LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    if (!empty($statusFilter)) {
        $conditions[] = "o.orderStatus = ?";
        $params[] = $statusFilter;
    }

    $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

    $sql = "SELECT o.*, u.username 
            FROM orders o 
            JOIN users u ON o.userID = u.userID
            $whereClause
            ORDER BY o.purchasedAt DESC";
            
    $stmt = $_db->prepare($sql);
    $stmt->execute($params);
    $orders = $stmt->fetchAll(PDO::FETCH_OBJ);

} catch (Exception $e) {
    echo "<div style='color: white; background: red; padding: 10px;'>Error: " . $e->getMessage() . "</div>";
    $orders = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management | LIMEL Inc.</title>
    <link rel="tab icon" href="/images/Logo.png">
    <link rel="stylesheet" href="/app.css">
</head>
<body class="admin-body">
    <div class="inventory-layout">
        <?php include 'sidebar.php'; ?>

        <main class="inventory-container">
            <div class="inventory-header">
                <div class="inventory-title">
                    <h1 class="title" style="font-size: 24px; text-align: left; margin: 0;">Orders</h1>
                    <p class="subtitle">Manage Customer Orders and Status</p>
                </div>
            </div>
            
            <!-- ── Search + filter form (submits with GET so URL stays shareable) ── -->
            <form method="GET">
            <section class="filter-section">
                <div class="inventory-search-bar">
                    <input type="text"
                           name="search"
                           placeholder="Search by Order ID or Customer name..."
                           value="<?= htmlspecialchars($search) ?>">
                </div>
                <select class="filter-select" name="status" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="Processing"  <?= $statusFilter === 'Processing'  ? 'selected' : '' ?>>Processing</option>
                    <option value="Shipped"     <?= $statusFilter === 'Shipped'     ? 'selected' : '' ?>>Shipped</option>
                    <option value="Delivered"   <?= $statusFilter === 'Delivered'   ? 'selected' : '' ?>>Delivered</option>
                    <option value="Cancelled"   <?= $statusFilter === 'Cancelled'   ? 'selected' : '' ?>>Cancelled</option>
                </select>
                <button type="submit" class="filter-button">Search</button>
                <?php if (!empty($search) || !empty($statusFilter)): ?>
                    <a href="order.php" class="filter-button" style="text-decoration:none; background:transparent; color:#aaa; border:1px solid #444;">✕ Clear</a>
                <?php endif; ?>
            </section>
            </form>

            <?php if (!empty($search) || !empty($statusFilter)): ?>
            <p style="color:#888; font-size:13px; margin: 8px 0 14px;">
                Showing <?= count($orders) ?> result(s)
                <?= !empty($search) ? 'for "' . htmlspecialchars($search) . '"' : '' ?>
                <?= !empty($statusFilter) ? '(Status: ' . htmlspecialchars($statusFilter) . ')' : '' ?>
            </p>
            <?php endif; ?>

            <div class="inventory-table-container">
                <table class="product-details-table">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Invoice ID</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr>
                                <td colspan="7" style="text-align:center; padding: 40px; color: #666;">
                                    <?php if (!empty($search) || !empty($statusFilter)): ?>
                                        No orders found matching your search.
                                    <?php else: ?>
                                        No orders found in the database.
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $sl = 1; foreach ($orders as $order): ?>
                            <tr>
                                <td class="text-dim"><?= $sl++ ?></td>
                                <td><strong>#<?= htmlspecialchars($order->orderID) ?></strong></td>
                                <td><?= date('d M Y', strtotime($order->purchasedAt)) ?></td>
                                <td><?= htmlspecialchars($order->username) ?></td>
                                <td>RM<?= number_format($order->totalAmount, 2) ?></td>
                                <td>
                                    <span class="status-badge badge-<?= strtolower($order->orderStatus) ?>">
                                        <?= htmlspecialchars($order->orderStatus) ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="action-btn" title="View Details"
                                        onclick="window.location.href='../page/receipt.php?id=<?= urlencode($order->orderID) ?>'">👁</button>
                                    <!-- Status update dropdown -->
                                    <form method="POST" action="order_update_status.php" style="display:inline;">
                                        <input type="hidden" name="orderID" value="<?= htmlspecialchars($order->orderID) ?>">
                                        <select name="orderStatus" onchange="this.form.submit()" style="background:#1a1a1a; color:#ccc; border:1px solid #333; border-radius:5px; padding:3px 6px; font-size:12px;">
                                            <option value="Processing" <?= $order->orderStatus === 'Processing' ? 'selected' : '' ?>>Processing</option>
                                            <option value="Shipped"    <?= $order->orderStatus === 'Shipped'    ? 'selected' : '' ?>>Shipped</option>
                                            <option value="Delivered"  <?= $order->orderStatus === 'Delivered'  ? 'selected' : '' ?>>Delivered</option>
                                            <option value="Cancelled"  <?= $order->orderStatus === 'Cancelled'  ? 'selected' : '' ?>>Cancelled</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
