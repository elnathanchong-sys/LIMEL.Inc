<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIMEL</title>
    <link rel="stylesheet" href="/app.css">
</head>
    
<body>

    <?php include './header.php'; ?>
    
    <h1 class="title">﹥*:ꔫ:*+ﾟ Homepage +*:ꔫ:*﹤</h1>
<section id="hero" class="hero">
    <div class="hero-content">
        <h1>The ENR Edition</h1>
        <p> by Enrique </p>
        <p>Precision meets aesthetic.</p>

        <button class="cta-button">Order now</button>

       
</div>
</section>
<div class="button-wrapper">
    <a href="pages/aboutus.php" class="about-button">About Us</a>
</div>
    <section class="gallery">
        <div class="card"><img src="images/keyboard pic/Limel K80HE Jerri Edition.png" alt="Keyboard"width="10px"></div>
        <div class="card"><img src="images/keyboard pic/Limel K80HE Jerri Edition (Top View).png" alt="Keyboard"width="10px"></div>
</section>    

<script>
    const hero = document.getElementById('hero');
    const images = [
        'url("images/keyboard pic/Limel K80HE Jerri Edition.png")',
        'url("images/keyboard pic/Limel K80HE Jerri Edition (Top View).png")'
    ];

    let currentIndex = 0;
    setInterval(() => {
        currentIndex = (currentIndex + 1) % images.length;
        hero.style.backgroundImage = images [currentIndex];
}, 4000);
</script>
<!--
<section id="Product">
    <div class="Product-header">
        <h2>Jerome's 18+ Keyboards (..◜ᴗ◝..) </h2>
        <a href="keyboards.php">View More</a>
        </div>

        <div class="keyboards-display">
    <div class="keyboard-grid">
        <dive class="mouse0card">
        <img src="images/keyboard pic/Limel K60HE + (Top View).png" alt="Limel K60HE + (Top View)" width="150px">
        <h3>Limel K60HE + (Top View)</h3>
        <p> MYR 2,500.00</p>
        <a href=""a>
</div>
    

    <div class="Product-header">
        <h2>Mouses ≽^•⩊•^≼</h2>
        <a href="mouse.php">View More</a>
    </div>

    <div class="mouses-display">
        <div class="mouse-grid">
            <div class="mouse-card">
                    <img src="images/mouse pic/Limel M Hyperlight PrintStream.png" alt="Limel M Hyperlight PrintStream" width="150px">
                    <h3>Limel M Hyperlight PrintStream</h3>
                    <p>MYR 3,000.00</p>
                    <a href=""a>               
        </div>
        <div class="mouse-card">
                <img src="images/mouse pic/Limel M Hyperlight.png" alt="Limel M Hyperlight.png" width="150px">
                <h3>Limel M Hyperlight</h3>
                <p>MYR 12,000.00</p>
                <a href=""a>
        </div>
        <div class="mouse-card">
                <img src="images/mouse pic/Limel M OP1 8k.png" alt="Limel M OP1 8k" width="150px">
                <h3>Limel M OP1 8k</h3>
                <p>MYR 1,000,000.00</p>
                <a href=""a>
        </div> 
-->    
</body>

    <?php include './footer.php'; ?>

