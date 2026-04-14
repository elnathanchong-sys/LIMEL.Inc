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
    <a href="#" id="userMenuBtn"><img src="/images/icon/user.png" width="20" alt="User"></a>
    <a href="/page/cart.php"><img src="/images/icon/shopping-cart.png" width="20" alt="Cart"></a>
  </div>
</header>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="user-sidebar" id="userSidebar">
    <div class="sidebar-top">
        <h3>Menu</h3>
        <button id="closeSidebarBtn" class="close-sidebar-btn">&times;</button>
    </div>
    <div class="sidebar-menu-links">
        <a href="/index.php">Home</a>
        <a href="/page/account.php">Account</a>
        <a href="/security/login.php">Login</a>
        <a href="/security/logout.php">Logout</a>
    </div>
</div>

<script>
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userSidebar = document.getElementById('userSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const closeSidebarBtn = document.getElementById('closeSidebarBtn');

    userMenuBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        userSidebar.classList.toggle('active');
        sidebarOverlay.classList.toggle('active');
    });

    closeSidebarBtn.addEventListener('click', function() {
        userSidebar.classList.remove('active');
        sidebarOverlay.classList.remove('active');
    });

    sidebarOverlay.addEventListener('click', function() {
        userSidebar.classList.remove('active');
        sidebarOverlay.classList.remove('active');
    });
</script>
