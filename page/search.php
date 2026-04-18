<?php
require_once '../base.php';

$search_query = $_GET['q'] ?? '';

if (!empty($search_query)) {
    $stmt = $_db->prepare("SELECT * FROM products WHERE productName LIKE ?");
    $stmt->execute(["%$search_query%"]);
    $results = $stmt->fetchAll();
} else {
    $results = [];
}

include '../header.php'; 
?>

<main style="min-height: 70vh; padding: 50px 20px; background-color: #000; color: #fff;">
    <div style="max-width: 1200px; margin: 0 auto;">
        
        <h1 style="font-family: 'Montserrat', sans-serif; margin-bottom: 40px; border-bottom: 1px solid #333; padding-bottom: 20px;">
            Search Results for: <span style="color: #667eea;">"<?= htmlspecialchars($search_query) ?>"</span>
        </h1>

        <?php if (empty($results)): ?>
            <div style="text-align: center; padding: 100px 0;">
                <h3 style="color: #888; font-family: 'Montserrat', sans-serif;">No products found matching your search.</h3>
                <p style="color: #555;">Try checking your spelling or using more general keywords.</p>
                <br>
                <a href="/index.php" style="color: #667eea; text-decoration: none; font-weight: bold;">&larr; Back to Home</a>
            </div>

        <?php else: ?>
            <div class="keyboard-grid">
                <?php foreach ($results as $product): ?>
                    
                    <div class="keyboard-card">
                        <div class="keyboard-image-container">
                            <img src="/images/<?= $product->imageFile ?? 'placeholder.png' ?>" alt="<?= $product->productName ?>">
                        </div>
                        
                        <div class="keyboard-brand"><?= $product->productTheme ?> Edition</div>
                        <h3 class="keyboard-title"><?= $product->productName ?></h3>
                        
                        <p class="keyboard-desc"><?= substr(strip_tags($product->productDesc), 0, 60) ?>...</p>
                        
                        <div class="keyboard-price">RM <?= number_format($product->productPrice, 2) ?></div>
                        
                        <div style="margin-top: 15px; text-align: center;">
                            <a href="/page/product-page.php?id=<?= $product->productID ?>" style="color: #fff; text-decoration: underline; font-size: 14px;">View Details</a>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php 

include '../footer.php'; 
?>