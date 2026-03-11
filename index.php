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
    
    <h1 class="title">﹥﹤</h1>
<section id="hero" class="hero">
    <div class="hero-slides">
       <img src="images/keyboard pic/Limel K80HE Jerri Edition.png" class="slide active" alt= "Keyboard 1">
       <img src="images/keyboard pic/Limel K60HE v2.png" class="slide zoom-out" alt= "Keyboard 2">
    </div>

    <button class="arrow prev" onclick="changeSlide(-1)">&#10094;</button>
    <button class="arrow next" onclick="changeSlide(1)">&#10095;</button>
    <div class="hero-content">
        <h1>The ENR Edition</h1>
        <p>By Enrique</p>
        <p>Precision meets aesthetic.</p>
    </div>
        <h1>The Lim V2</h1>
        <p>By Lim</p>
        <p>Unmatched speed and responsiveness</p>
    </div>

        <button class="cta-button">Order now</button>
    </div>
</section>

<div class="button-wrapper">
    <a href="page/aboutus.php" class="about-button">About Us</a>
</div>

<section>
    
    <div class="Product-header">
        <h2>Keyboards (..◜ᴗ◝..) </h2>
        <a href="keyboards.php">View More</a>
        </div>

 <div class="keyboards-display">
    <div class="keyboard-grid">
        <div class="keyboard-image-container">
        <img src="images/keyboard pic/Limel K60HE + (Top View).png">
          </div>
          <div class ="keyboard-brand">Limel</div>
          <div class ="keyboard-title">Limel K69HE + (Top View)</div>
          <div class ="keyboard-price"> MYR 888.00</div>
</div>

<div class="keyboard-card">
    <div class="mouse-image-container">
    <img src="images/keyboard pic/Limel K60HE v2 (Top View).png">
        </div>
            <div class = "keyboard-brand">Limel</div>
            <div class = "keyboard-title">Limel K60HE v2 (Top View)</div>
            <div class = "keyboard-price"> MYR 999.99</div>
</div>



<div class="keyboard-card">
    <img src="images/keyboard pic/Limel K80HE.png" alt="Limel K80HE" width="150px">
    <h3> Limel K80HE </h3>
    <p> MYR 2,500.00 </p>

</div>
    
<div class="keyboard-card">
    <img src="images/keyboard pic/Limel K80HE Jerri Edition.png" alt="Limel K80HE Jerri Edition" width="150px">
    <h3> Limel K80HE Jerri Edition </h3>
    <p> MYR 3,000.00 </p>
</div> 
</div>
</div>

</section>

    <div class="Product-header">
        <h2>Mouses ≽^•⩊•^≼</h2>
        <a href="mouse.php">View More</a>
    </div>

     
<div class="mouse-grid">
    <div class="mouse-card">
        <div class="mouse-image-container">
            <img src="images/mouse pic/Limel M Hyperlight.png">
                </div>
                    <div class="mouse-brand">Limel</div>
                    <div class="mouse-title">Limel M Hyperlight</div>
                    <div class="mouse-price">MYR 499.99</div>
                </div>
       
    <div class="mouse-card">
        <div class="mouse-image-container">
            <img src="images/mouse pic/Limel M Hyperlight PrintStream.png">
                </div>
                    <div class="mouse-brand">Limel</div>
                    <div class="mouse-title">Limel M Hyperlight PrintStream</div>
                    <div class="mouseprice">MYR 399.99</div>
                </div>

    <div class="mouse-card">
        <div class="mouse-image-container">
            <img src="images/mouse pic/Limel M OP1 8k.png">
                </div>
                    <div class="mouse-brand">Limel</div>
                    <div class="mouse-title">Limel M OP1 8k</div>
                    <div class="mouse-price">MYR 299.99</div>
                </div>
	</div>

<script>
    let currentIndex =0;
    function changeSlide(direction){ //for the arrows
        const slides = document.querySelectorAll('.slide'); //looks for image with slide class
        slides[currentIndex].classList.remove('active');
        currentIndex = (currentIndex + direction + slides.length) % slides.length; //ensures picture loops back to the first if reached the last one
        slides[currentIndex].classList.add('active');
    }

</script>   



</body>

    <?php include './footer.php'; ?>

