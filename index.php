<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIMEL</title>
    <link rel="stylesheet" href="/app.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="tab icon" href="/images/Logo.png">
    <script src="./app.js"></script>
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
      <img src="images/keyboard-pic/Jerri_80HE.png" alt="Keyboard 1">
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
    <a href="page/myorders.php" class="order-button">Order Now</a>
</div>
</section>

<script>
    
</script>


<section>
    <div class="keyboard-header">
        <h2>Keyboards</h2>
        <a href="page/keyboards.php">View More</a>
    </div>


    
    <div class="keyboard-grid">

        <div class="keyboard-card">
            <div class="keyboard-image-container">
                <a href="/product-page/Limel-K60-HE-v2.php"><img src="/images/keyboard-pic/Limel K60HE v2 (Top View).png"></a>
            </div>
            <div class="card-line">______________________________________</div>
            <div class="keyboard-title">Limel K60 HE v2</div>
            <div class="keyboard-desc">60% Gaming Keyboard</div>
            <div class="keyboard-price">MYR 690.00</div>
        </div>
       
        <div class="keyboard-card">
            <div class="keyboard-image-container">
              <a href="/product-page/Limel-M1-V5-TMR.php"><img src="images/keyboard-pic/Limel-M1-V5-TMR/M1-V5-TMR-Magnetic-Switch-Keyboard.png"></a>
            </div>
            <div class="card-line">______________________________________</div>
            <div class="keyboard-title">Limel M1 V5 TMR</div>
            <div class="keyboard-desc">75% Gaming Keyboard</div>
            <div class="keyboard-price">MYR 969.00</div>
        </div>

        <div class="keyboard-card">
            <div class="keyboard-image-container">
                <a href="/product-page/Limel-P1HE.php"><img src="/images/keyboard-pic/Limel-P1HE White/Limel-P1HE White.png"></a>
            </div>
            <div class="card-line">______________________________________</div>
            <div class="keyboard-title">Limel P1 HE</div>
            <div class="keyboard-desc">75% Gaming Keyboard</div>
            <div class="keyboard-price">MYR 869.00</div>
        </div>
    </div>


</section>


<section>
<!--Start of Mouse-->
    
<div class="mouse-header">
        <h2>Mouse</h2>
        <a href="page/mouse.php" class="mouse-view-more">View More</a>
    </div>

    <div class="mouse-grid">
    <div class="mouse-card">
        <div class="mouse-image-container">
            <a href="/product-page/LMHL.php"><img src="images/mouse pic/Limel M Hyperlight.png"></a>
        </div>
        <div class="card-line">______________________________________</div>
        <div class="mouse-title">Limel M Hyperlight</div>
        <div class="mouse-desc">100% real mouse and not scam.</div>
        <div class="mouse-price">MYR 499.99</div>
    </div>
   
    <div class="mouse-card">
        <div class="mouse-image-container">
            <a href="/product-page/LMHP.php"><img src="images/mouse pic/Limel M Hyperlight PrintStream.png"></a> <!-- ← added </a> -->
        </div>
        <div class="card-line">______________________________________</div>
        <div class="mouse-title">Limel M Hyperlight PrintStream</div>
        <div class="mouse-desc">100% not scam and a functional mouse</div>
        <div class="mouse-price">MYR 399.99</div>
    </div>

    <div class="mouse-card">
        <div class="mouse-image-container">
            <a href="/product-page/LMOP1.php"><img src="images/mouse pic/Limel M OP1 8k.png"></a> <!-- ← added </a> -->
        </div>
        <div class="card-line">______________________________________</div>
        <div class="mouse-title">Limel M OP1 8k</div>
        <div class="mouse-desc">1% Gaming Mouse</div>
        <div class="mouse-price">MYR 299.99</div>
    </div>	
</div>
<!--End of Mouse-->

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
            <h3>We're just simply better</h3>
        </div>
    </div>
</section>


</body>
    <?php include './footer.php'; ?>

    <script src="/path/to/app.js"></script>
