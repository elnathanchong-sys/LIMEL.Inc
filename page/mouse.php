<?php
include '../base.php';
$stm = $_db->prepare("SELECT * FROM products WHERE categoryID = ?");
$stm->execute(['M001']);
$products = $stm->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIMEL</title>
    <link rel="stylesheet" href="/app.css">
    <link rel="tab icon" href="/images/Logo.png">
</head>

<body>

    <?php include '../header.php'; ?>

    <main>
        <div class="mouse-grid">

            <?php foreach ($products as $p): ?>
                <div class="mouse-card">
                    <div class="mouse-image-container">
                        <a href="../product-page/product.php?productID=<?= htmlspecialchars($p->productID) ?>">
                            <img src="/images/new-image-save/<?= htmlspecialchars($p->imageFile) ?>" alt="<?= htmlspecialchars($p->productName) ?>">
                        </a>
                    </div>
                    <div class="card-line">______________________________________</div>
                    <div class="mouse-title"><?= htmlspecialchars($p->productName) ?></div>
                    <div class="mouse-price">MYR <?= number_format($p->productPrice, 2) ?></div>
                </div>
            <?php endforeach; ?>
        </div>

    </main>
</body>
    <?php include '../footer.php'; ?>


    