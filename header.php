<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'LIMEL' ?></title>
    <link rel="stylesheet" href="/app.css">
</head>
<body> <a id="top"></a>

    <header>
      <nav>
        <div class="left-icon">
          <a href="/index.php"><img src="/images/logo.png" width="100" alt="Limel Logo"></a>
        </div>

        <div class="navigation-bar">
          <a href="/page/keyboards.php" class="no-underlined">Keyboard</a>
          <a href="/page/mouse.php" class="no-underlined">Mouse</a>
          <a href="/page/contactus.php" class="no-underlined">Contact Us</a>
          <a href="/page/learnmore.php" class="no-underlined">Learn More</a>
          <a href="/page/myaccount.php" class="no-underlined">Testing</a>
        </div>
      </nav>

     <div class="right-icon">
        <div class="hpsearch-wrapper">
            <a href="#" id="hpsearchIconBtn"><img src="/images/icon/search-icon.png" width="20" alt="Search"></a>
            
            <div class="hpsearch-dropdown" id="hpsearchDropdown">
                <form action="/page/search.php" method="GET">
                    <input type="text" name="q" id="hpsearchInput" placeholder="Search products...">
                </form>
            </div>
        </div>
        
        <a href="#" id="userMenuBtn"><img src="/images/icon/user.png" width="20" alt="User"></a>
    </div>
</header>

<script>
    document.getElementById('hpsearchIconBtn').addEventListener('click', function(e) {
        e.preventDefault(); 
        const dropdown = document.getElementById('hpsearchDropdown');
        const input = document.getElementById('hpsearchInput');

        dropdown.classList.toggle('active');
        
        if (dropdown.classList.contains('active')) {
            input.focus();
        }
    });

    document.addEventListener('click', function(e) {
        const wrapper = document.querySelector('.hpsearch-wrapper');
        const dropdown = document.getElementById('hpsearchDropdown');
        
        if (!wrapper.contains(e.target) && dropdown.classList.contains('active')) {
            dropdown.classList.remove('active');
        }
    });
</script>