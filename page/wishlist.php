<?php
require '../base.php';

if (!isset($_SESSION['userID'])) {
    redirect('../security/login.php');
}

$stm = $_db->prepare("
    SELECT p.productID, p.productName, p.productPrice, p.imageFile, w.favouritedAt
    FROM wishlist w
    JOIN products p ON w.productID = p.productID
    WHERE w.userID = ?
");
$stm->execute([$_SESSION['userID']]);
$items = $stm->fetchAll();
?>

<?php include '../header.php'; ?>

<main class="container">
    <h1>My Favourites</h1>

    <?php if (empty($items)): ?>
        <p>You haven't favourited anything yet.</p>
    <?php else: ?>
        <div class="keyboard-grid">
            <?php foreach ($items as $item): ?>
                <div class="keyboard-card">
                    <button class="wishlist-btn active" 
                        onclick="toggleFavorite(event, this, '<?= $item->productID ?>')">❤️</button>
                    <div class="keyboard-image-container">
                        <img src="/images/keyboard-pic/<?= $item->imageFile ?>">
                    </div>
                    <div class="keyboard-title"><?= encode($item->productName) ?></div>
                    <div class="keyboard-price">MYR <?= number_format($item->productPrice, 2) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php include '../footer.php'; ?>