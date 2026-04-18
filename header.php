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
        <div class="hpsearch-container">
            <form action="/page/search.php" method="GET" id="searchForm">
                <input type="text" name="q" id="hpsearchInput" placeholder="Search products..." class="hpsearch-input">
                <a href="#" id="hpsearchIconBtn"><img src="/images/icon/search-icon.png" width="20" alt="Search"></a>
            </form>
        </div>
        
        <a href="#" id="userMenuBtn"><img src="/images/icon/user.png" width="20" alt="User"></a>
    </div>
    </header>

    <script>
        document.getElementById('hpsearchIconBtn').addEventListener('click', function(e) {
            const container = document.querySelector('.hpsearch-container');
            const input = document.getElementById('hpsearchInput');

          
            if (!container.classList.contains('active')) {
                e.preventDefault(); 
                container.classList.add('active');
                input.focus();
            } else {
             
                if (input.value.trim() === '') {
                    e.preventDefault();
                    container.classList.remove('active');
                }
          
            }
        });
    </script>