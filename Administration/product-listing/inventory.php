<?php
include '../../base.php';

auth('Admin');

// ── Search: filter products by name or ID ──
$search = trim($_GET['search'] ?? '');

if (!empty($search)) {
    $stmt = $_db->prepare("SELECT * FROM products WHERE productName LIKE ? OR productID LIKE ?");
    $searchTerm = "%$search%";
    $stmt->execute([$searchTerm, $searchTerm]);
    $arr = $stmt->fetchAll();
} else {
    $arr = $_db->query('SELECT * FROM products')->fetchAll();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/app.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="tab icon" href="/images/Logo.png">
    <script src="./app.js"></script>
</head>
<body>
    <div class="inventory-layout">   
        <?php include '../sidebar.php'; ?>
    

        <main class="inventory-container">
            <div class="inventory-header">
                <div class="inventory-title">
                    <h1 class="title">Inventory</h1>
                    <p class="subtitle">Manage Products</p>
                </div>
                <div style="display:flex; gap:10px; align-items:center;">
                    <!-- Batch Delete button — shows only when rows are selected -->
                    <button type="button"
                            class="button-batch-delete"
                            id="batchDeleteBtn"
                            onclick="batchDelete()">
                        🗑 Delete Selected (<span id="selectedCount">0</span>)
                    </button>

                    <!-- Add New Product button -->
                    <button type="button" class="button-insert" data-get="insert.php">
                        Add New Product <span>+</span>
                    </button>
                </div>
            </div>

            <section class="filter-section">
                <form method="GET" style="display:flex; gap:10px; flex:1;">
                    <div class="inventory-search-bar" style="flex:1;">
                        <i class="isearch"></i>
                        <input type="text"
                               name="search"
                               id="searchInput"
                               placeholder="Search products by name, ID"
                               value="<?= htmlspecialchars($search) ?>">
                    </div>
                <button type="submit" class="filter-button">Search</button>
                    <?php if (!empty($search)): ?>
                        <a href="inventory.php" class="filter-button" style="text-decoration:none; background:transparent; color:#aaa; border:1px solid #444;">✕ Clear</a>
                    <?php endif; ?>
                </form>

            </section>

            <?php if (!empty($search)): ?>
            <p style="color:#888; font-size:13px; margin: 8px 0 14px;">
                Showing <?= count($arr) ?> result(s) for "<?= htmlspecialchars($search) ?>"
            </p>
            <?php endif; ?>

            <div class="inventory-table-container">
                <!-- Batch delete form — wraps the whole table -->
                <form id="batchDeleteForm" method="POST" action="delete_batch.php">
                <table class="product-details-table">
                    <thead>
                        <tr>
                            <!-- Select All checkbox -->
                            <th><input type="checkbox" id="selectAll" class="row-checkbox" title="Select all" onchange="toggleSelectAll(this)"></th>
                            <th>Product ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Category ID</th>
                            <th>Theme</th>
                            <th>Layers</th>
                            <th>Keycaps</th>
                            <th>Switches</th>
                            <th>Description</th>
                            <th>Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($arr)): ?>
                        <tr>
                            <td colspan="12" style="text-align:center; padding:40px; color:#666;">
                                No products found<?= !empty($search) ? ' matching "' . htmlspecialchars($search) . '"' : '' ?>.
                            </td>
                        </tr>
                    <?php else: ?>
                    <?php foreach ($arr as $p): ?>
                    <tr>
                        <!-- Per-row checkbox for batch delete -->
                        <td>
                            <input type="checkbox"
                                   name="productIDs[]"
                                   value="<?= htmlspecialchars($p->productID) ?>"
                                   class="row-checkbox batch-checkbox"
                                   onchange="updateBatchCount()">
                        </td>
                        
                        <td class="text-dim"><?= $p->productID ?></td>
                        
                        <td>
                            <span class="name"><?= $p->productName ?></span>
                        </td>
                        
                        <td>RM<?= $p->productPrice ?></td>
                        
                        <td>
                            <span class="stockAmount <?= ($p->stockAmount ?? 0) <= 5 ? 'low' : '' ?>">
                                <?= $p->stockAmount ?? '0' ?>
                            </span>
                        </td>
                        
                        <td><?= $p->categoryID ?? '-' ?></td> <!-- ?? '-' means if null then show '-' -->
                        
                        <td><?= $p->productTheme ?? '-' ?></td>
                        
                        <td><?= $p->productLayers ?? '-' ?></td>
                        
                        <td><?= $p->productKeycaps ?? '-' ?></td>
                        
                        <td><?= $p->productSwitches ?? '-' ?></td>
                        
                        <td><?= $p->productDesc ?? '-' ?></td>

                        <td>
                            <div class="update-delete-button-insert-page">
                                <button type="button"
                                    class="update-button-insert"
                                    data-get="update.php?productID=<?= $p->productID ?>">
                                    Update
                                </button>
                            
                                <button class="insert-button-delete"
                                    data-post="delete.php"
                                    data-id="<?= $p->productID ?>">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach ?>
                    <?php endif; ?>
                    </tbody>
                </table>
                </form>
            </div>
        </main>
    </div>

<script>
// ── Select All checkbox ──
function toggleSelectAll(masterCheckbox) {
    const checkboxes = document.querySelectorAll('.batch-checkbox');
    checkboxes.forEach(cb => cb.checked = masterCheckbox.checked);
    updateBatchCount();
}

// ── Update the batch delete button count and visibility ──
function updateBatchCount() {
    const checked = document.querySelectorAll('.batch-checkbox:checked');
    const btn = document.getElementById('batchDeleteBtn');
    const countSpan = document.getElementById('selectedCount');
    
    countSpan.textContent = checked.length;

    if (checked.length > 0) {
        btn.classList.add('visible');
    } else {
        btn.classList.remove('visible');
    }
}

// ── Batch delete: confirm and submit the form ──
function batchDelete() {
    const checked = document.querySelectorAll('.batch-checkbox:checked');
    if (checked.length === 0) return;

    const ok = confirm('Delete ' + checked.length + ' selected product(s)? This cannot be undone.');
    if (!ok) return;

    document.getElementById('batchDeleteForm').submit();
}
</script>

</body>
