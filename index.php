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
    <section class="carousel-section">
<div class="slidershow">
  <input type="radio" name="r" id="r1" checked>
  <input type="radio" name="r" id="r2">

  <div class="slides">
    <div class="slide s1">
      <img src="images/keyboard-pic/Limel-K80HE-Jerri-Edition.png" alt="Keyboard 1">
      <div class="text-overlay">The ENR Edition</div>
    </div>
    <div class="slide">
      <img src="images/keyboard-pic/Limel-K75HE-Nathan-Edition.png" alt="Keyboard 2">
      <div class="text-overlay">The SLOTH Edition</div>
    </div>
  </div>

  <div class="navigation">
    <label for="r1" class="bar"></label>
    <label for="r2" class="bar"></label>
  </div>
</div>
<div class="button-wrapper">
    <a href="page/order.php" class="order-button">Order Now</a>
</div>
</section>

<section>
    
    <div class="Product-header">
        <h2>Keyboards</h2>
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
<section>
    <div class="mouse-header">
        <h2>Mouse</h2>
    </div>

    <div class="mouse-view-more">
        <mouse-li><span href="mouse.php">view more</span></mouse-li>
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
                        <div class="mouse-price">MYR 399.99</div>
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
</section>
<section class="info-switches-head-class">
    <div class="info-switches-head">
        <h2>GAMING</h2>
        <p>Hall Effect Magnetic Switches</p>
    </div>

    <div class="glass-container">
        <div class="glass-card">
            <div class="icon"><img src="\images\icon\magnetic-switches-icon.png" width="100px"></div> <h3>MAGNETIC SWITCHES</h3>
            <p>Ultra-accurate typing experiences adjustable in 0.01mm. Applying for the electromagnetic principle, operated by Hall effect sensing.</p>
        </div>

        <div class="glass-card">
            <div class="icon"><img src="images/icon/snap keys icon.png"width="100px"></div> <h3>SNAP KEYS</h3>
            <p>Limel's implementation of SOCD. With Snap Keys, the last direction input takes priority, allowing for more precise movement in FPS games.</p>
        </div>

        <div class="glass-card">
            <div class="icon"><img src="\images\icon\rapid trigger icon.png"width="100px"></div> <h3>RAPID TRIGGER</h3>
            <p>Instead of relying on a fixed actuation like conventional mechanical keyboards, it immediately activates and deactivates the key with minimal travel. </p>
        </div>
    </div>
</section>

<section>
    <div class="why-choose-limel-container">
        <div class="why-choose-limel">
            <h1>Why Choose Limel</h1>
        </div>
        <div class="just-better">
            <h3>We're just simple better</h3>
        </div>
    </div>
</section>



</body>
    <?php include './footer.php'; ?>

    <script src="/path/to/app.js"></script>
