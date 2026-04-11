<body>
    <a id="top"></a>
</body>

<header>
  <nav>
    <div class="left-icon">
      <a href="/index.php"><img src="/images/logo.png" width="100"></a>
    </div>
    
    <div class="navigation-bar">
      <a href="/page/keyboards.php" class="no-underlined">Keyboard</a>
      <a href="/page/mouse.php" class="no-underlined">Mouse</a>
      <a href="/page/contactus.php" class="no-underlined">Contact Us</a>
      <a href="/page/learnmore.php" class="no-underlined">Learn More</a>
    </div>
  </nav>

  <div class="right-icon">
    <a href="/page/account.php"><img src="/images/icon/user.png" width="20"></a>
    <a href="/page/cart.php"><img src="/images/icon/shopping-cart.png" width="20"></a>
  </div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.body.classList.add('loaded');
    });

    document.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');

     
            if (!href || href.startsWith('#') || href.startsWith('http')) return;

            e.preventDefault();
            document.body.classList.remove('loaded');

            setTimeout(() => {
                window.location.href = href;
            }, 200); 
        });
    });
</script>

</header>

