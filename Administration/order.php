<?php
session_start();

/** * 1. MOCK DATA 
 * This replaces the database query so you can see the UI immediately.
 * When you get DB access, you will replace this array with a real SQL fetch.
 */
$orders = [
    ['id' => '2346326', 'created_at' => '2024-02-11', 'username' => 'Jerome Howard', 'total_price' => 393.01, 'status' => 'delivered'],
    ['id' => '2346327', 'created_at' => '2024-02-12', 'username' => 'Esther Howard', 'total_price' => 120.50, 'status' => 'pending'],
    ['id' => '2346328', 'created_at' => '2024-02-13', 'username' => 'Jenny Wilson', 'total_price' => 550.00, 'status' => 'shipped'],
    ['id' => '2346329', 'created_at' => '2024-02-14', 'username' => 'Guy Hawkins', 'total_price' => 89.99, 'status' => 'delivered'],
    ['id' => '2346330', 'created_at' => '2024-02-15', 'username' => 'Robert Fox', 'total_price' => 210.00, 'status' => 'cancelled'],
];
?>
<?php
include '/Administration/sidebar.php'
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/app.css">
<style>
    :root {
        --bg-light: #f8f9fa;
        --white: #ffffff;
        --text-muted: #8898aa;
        --text-dark: #32325d;
        /* Status Colors */
        --success: #2dce89;
        --pending: #fb6340;
        --info: #11cdef;
        --danger: #f5365c;
    }

    .order-page-container {
        margin-left: 260px; 
        padding: 30px;
        background-color: #121212;
        min-height: 100vh;
        width: calc(100% - 260px);
        box-sizing: border-box;
    }

    .order-card {
        background: var(--white);
        border-radius: 0.5rem;
        box-shadow: 0 0 2rem 0 rgba(136, 152, 170, .15);
        border: 0;
    }

    .card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(0, 0, 0, .05);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header h3 {
        margin: 0;
        color: var(--text-dark);
        font-size: 1.2rem;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .order-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 0;
    }

    .order-table th {
        background-color: #333333;
        color: var(--text-muted);
        padding: 1rem 1.5rem;
        text-transform: uppercase;
        font-size: .65rem;
        letter-spacing: 1px;
        text-align: left;
        border-bottom: 1px solid #e9ecef;
    }

    .order-table td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #1c1c1c;
        font-size: .875rem;
        color: var(--text-dark);
    }

    /* Status Badges */
    .status-badge {
        padding: .25rem .5rem;
        border-radius: .375rem;
        font-weight: 600;
        font-size: .75rem;
        display: inline-block;
        text-align: center;
        min-width: 80px;
    }

    .badge-delivered { background-color: #e7f7ed; color: var(--success); }
    .badge-pending { background-color: #fff4e5; color: var(--pending); }
    .badge-shipped { background-color: #e8f4fd; color: var(--info); }
    .badge-cancelled { background-color: #fdf2f2; color: var(--danger); }

    .action-btn {
        background: none;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        font-size: 1.1rem;
        margin: 0 5px;
        transition: color 0.2s;
    }

    .action-btn:hover { color: #5e72e4; }
</style>
</head>
<body>
    <div class="inventory-layout">
        <?php include 'sidebar.php';?>

        <main class="admin-content">

        <div class="order-page-container">
            <div class="order-card">
                <div class="card-header">
                    <h3>Customer Order List</h3>
                    <button style="background: #5e72e4; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">
                        + Add New Order
                    </button>
                </div>
        
                <div class="table-responsive">
                    <table class="order-table">
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
                            <?php $sl = 1; foreach ($orders as $order): ?>
                            <tr>
                                <td><?= $sl++ ?></td>
                                <td><strong>#S-<?= $order['id'] ?></strong></td>
                                <td><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                                <td><?= htmlspecialchars($order['username']) ?></td>
                                <td>$<?= number_format($order['total_price'], 2) ?></td>
                                <td>
                                <span class="status-badge badge-<?= $order['status'] ?>">
                                    <?= ucfirst($order['status'] == 'delivered' ? 'Approved' : $order['status']) ?>
                                </span>
                                </td>
                                <td>
                                    <button class="action-btn" title="View Details">👁</button>
                                    <button class="action-btn" title="Edit Status">✏️</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
