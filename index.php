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
    
    <h1 class="title"></h1>
<section id="hero" class="hero">
    <div class="hero-slides">
       <img src="images/keyboard-pic/Limel-K80HE-Jerri-Edition.png" class="slide active" alt= "Keyboard 1">
       <div class="caption">
        <h1>The ENR Edition"</h1>
        <p>By Enrique</p>
        <p>Precision meets aesthetic.</p>
    </div>  
</div>
<div class="slide">
    <img src="images/keyboard-pic/Limel-K75HE-Nathan-Edition.png" class="slide" alt= "Keyboard 2">
    <div class="caption">
         <h1>The NTH Edition</h1>
        <p>By Nathan</p>
        <p>Performance meets style.</p>
      </div>
    </div>
  </div>
    <button class="prev">&#10094;</button>
    <button class="next">&#10095;</button>
    <button class="aboutus" onclick="location.href='aboutus.html'">About Us</button>
     <button class="aboutus" onclick="location.href='.html'">Order Now</button>
</section>

<script>
let slides = document.querySelectorAll('.slide');
let current =0;

document.querySelector('.next').addEventListener('click', () => {
  slides[current].classList.remove('active');
  current = (current + 1) % slides.length;
  slides[current].classList.add('active');
});

document.querySelector('.prev').addEventListener('click', () => {
  slides[current].classList.remove('active');
  current = (current - 1 + slides.length) % slides.length;
  slides[current].classList.add('active');
});
</script>
    


<div class="button-wrapper">
    <a href="page/aboutus.php" class="about-button">About Us</a>
</div>

<section>
    
    <div class="Product-header">
        <h2>Keyboards (..◜ᴗ◝..) </h2>
        <a href="keyboards.php">View More</a>
    </div>

    
    <div class="keyboard-grid">

            <div class="keyboard-card">
            <div class="keyboard-image-container">
                <img src="/images/keyboard-pic/Limel K60HE v2 (Top View).png">
            </div>
            <div class="keyboard-brand">Limel</div>
            <div class="keyboard-title">Limel K60 HE v2</div>
            <div class="keyboard-desc">60% Gaming Keyboard</div>
            <div class="keyboard-price">MYR 690.00</div>
        </div>
       
        <div class="keyboard-card">
            <div class="keyboard-image-container">
                <img src="/images/keyboard-pic/Limel K80HE Jerri Edition (Top View).png">
            </div>
            <div class="keyboard-brand">Limel</div>
            <div class="keyboard-title">Limel K80HE Jerri Edition</div>
            <div class="keyboard-desc">80% Gaming Keyboard</div>
            <div class="keyboard-price">MYR 879.00</div>
        </div>

        <div class="keyboard-card">
            <div class="keyboard-image-container">
                <img src="/images/keyboard-pic/Limel K60HE + (Top View).png">
            </div>
            <div class="keyboard-brand">Limel</div>
            <div class="keyboard-title">Limel K60HE+</div>
            <div class="keyboard-desc">80% Gaming Keyboard</div>
            <div class="keyboard-price">MYR 459.00</div>
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
        content[currentIndex].classList.remove('active');
        currentIndex = (currentIndex + direction + slides.length) % slides.length; //ensures picture loops back to the first if reached the last one
        slides[currentIndex].classList.add('active');
        contents[currentIndex].classList.add('active');
    }

</script>   



</body>

    <?php include './footer.php'; ?>

    <script src="/path/to/app.js"></script>
