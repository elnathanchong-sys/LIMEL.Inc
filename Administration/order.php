<?php
// Define the path to base.php. Adjust this path based on where the file actually is!
$basePath = './product-listing/base.php'; 

if (file_exists($basePath)) {
    require_once $basePath; // base.php handles session_start()
} else {
    die("<div style='color: white; background: red; padding: 20px;'>
            CRITICAL ERROR: Could not find base.php at: $basePath. 
            Please check your file folders.
         </div>");
}

try {
    if (!isset($_db)) {
        throw new Exception("Database connection variable \$_db not found. Check base.php.");
    }

    $sql = "SELECT o.*, u.username 
            FROM orders o 
            JOIN users u ON o.userID = u.userID 
            ORDER BY o.purchasedAt DESC";
            
    $orders = $_db->query($sql)->fetchAll(PDO::FETCH_OBJ);
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
    <title>Orders Management | ELLIM Inc.</title>
    <link rel="tab icon" href="/images/Logo.png">
    <link rel="stylesheet" href="/app.css">
</head>
<body class="admin-body">
    <div class="inventory-layout">
        <?php include 'sidebar.php'; ?>

        <main class="inventory-container">
            <div class="inventory-header">
                <div class="inventory-title">
                    <h1 class="title" style="font-size: 24px; text-align: left; margin: 0;">Order</h1>
                    <p class="subtitle">Manage Customer Orders and Status</p>
                </div>
                <button type="button" class="button-insert">
                    Add New Order <span>+</span>
                </button>
            </div>
            
            <section class="filter-section">
                <div class="inventory-search-bar">
                    <input type="text" placeholder="Search orders by Invoice ID, Customer name...">
                </div>
                <select class="filter-select">
                    <option value="">All Status</option>
                    <option value="Processing">Processing</option>
                    <option value="Shipped">Shipped</option>
                    <option value="Delivered">Delivered</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
                <button class="filter-button">Recent</button>
            </section>

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
                                    No orders found in the database.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $sl = 1; foreach ($orders as $order): ?>
                            <tr>
                                <td class="text-dim"><?= $sl++ ?></td>
                                <td><strong>#<?= $order->orderID ?></strong></td>
                                <td><?= date('d M Y', strtotime($order->purchasedAt)) ?></td>
                                <td><?= htmlspecialchars($order->username) ?></td>
                                <td>RM<?= number_format($order->totalPrice, 2) ?></td>
                                <td>
                                    <span class="status-badge badge-<?= strtolower($order->orderStatus) ?>">
                                        <?= $order->orderStatus ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="action-btn" title="View Details">👁</button>
                                    <button class="action-btn" title="Edit Order">✏️</button>
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