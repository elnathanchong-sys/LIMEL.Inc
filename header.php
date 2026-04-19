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
          <a href="/page/myaccount.php" class="no-underlined">Account</a>
          <a href="/page/storelocation.php" class="no-underlined">Location</a>
        </div>
      </nav>

     <div class="right-icon">
        <div class="hpsearch-wrapper">
            <a href="#" id="hpsearchIconBtn"><img src="/images/icon/search icon.png" width="20" alt="Search"></a>
            
            <div class="hpsearch-dropdown" id="hpsearchDropdown">
                <form action="/page/search.php" method="GET">
                    <input type="text" name="q" id="hpsearchInput" placeholder="Search products..." autocomplete="off">
                </form>
                
                <div id="hpsearchResults" class="hpsearch-results-list"></div>
            </div>
        </div>

        <a href="/page/cart.php">
            <img src="/images/icon/shopping-cart.png" width="20" alt="Cart">
        </a>
        
        <a href="/security/login.php"><img src="/images/icon/user.png" width="20" alt="User"></a>
    </div>
</header>

<script>
    const searchIconBtn = document.getElementById('hpsearchIconBtn');
    const dropdown = document.getElementById('hpsearchDropdown');
    const input = document.getElementById('hpsearchInput');
    const resultsContainer = document.getElementById('hpsearchResults');

    searchIconBtn.addEventListener('click', function(e) {
        e.preventDefault(); 
        dropdown.classList.toggle('active');
        if (dropdown.classList.contains('active')) {
            input.focus();
        }
    });

    input.addEventListener('input', function() {
        const query = this.value.trim();

        if (query.length > 0) {
            fetch(`/page/livesearch.php?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    resultsContainer.innerHTML = ''; 
                    
                    if (data.length > 0) {
                        data.forEach(product => {
                            const link = document.createElement('a');
                            link.href = `/page/productdetail.php?id=${product.productID}`;
                            link.className = 'hpsearch-result-item';
                            link.textContent = product.productName;
                            resultsContainer.appendChild(link);
                        });
                        resultsContainer.style.display = 'block';
                    } else {
                        resultsContainer.innerHTML = '<div class="hpno-results">No products found</div>';
                        resultsContainer.style.display = 'block';
                    }
                });
        } else {
            resultsContainer.style.display = 'none';
            resultsContainer.innerHTML = '';
        }
    });

    document.addEventListener('click', function(e) {
        const wrapper = document.querySelector('.hpsearch-wrapper');
        if (!wrapper.contains(e.target) && dropdown.classList.contains('active')) {
            dropdown.classList.remove('active');
            resultsContainer.style.display = 'none';
        }
    });
</script>