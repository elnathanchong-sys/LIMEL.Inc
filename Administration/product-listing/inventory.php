<?php
include '../../base.php';

$arr = $_db->query('SELECT * FROM products')->fetchAll();


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
                <button type="button" class="button-insert" data-get="insert.php">
                    Add New Product <span>+</span>
                </button>
            </div>

            <section class="filter-section">
                <div class="inventory-search-bar">
                    <i class="isearch"></i>
                    <input type="text" placeholder="Search products by name, ID">
                </div>
                <select class="filter-select"><option>All Categories</option></select>
                <button class="filter-button">Keyboard</button>
            </section>

            <div class="inventory-table-container">
                <table class="product-details-table">
                    <thead>
                        <tr>
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
                    <?php foreach ($arr as $p): ?>
                    <tr>
                        
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
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>