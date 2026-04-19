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
        
 <?php
        $sessionUser = $_SESSION['users'] ?? null;
        $hasPhoto = !empty($sessionUser->profilepic)
            && file_exists($_SERVER['DOCUMENT_ROOT'] . '/images/new-image-save/' . $sessionUser->profilepic);
 
        if ($sessionUser && $sessionUser->role === 'Member'): ?>
 
        <div class="hp-profile-wrapper" id="hpProfileWrapper">
            <button class="hp-profile-trigger" id="hpProfileTrigger" type="button" aria-expanded="false">
                <?php if ($hasPhoto): ?>
                    <img src="/images/new-image-save/<?= htmlspecialchars($sessionUser->profilepic) ?>"
                         class="hp-profile-avatar" alt="<?= htmlspecialchars($sessionUser->username) ?>">
                <?php else: ?>
                    <div class="hp-profile-initials">
                        <?= strtoupper(substr($sessionUser->username, 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <span class="hp-profile-trigger-name"><?= htmlspecialchars($sessionUser->username) ?></span>
                <svg class="hp-profile-caret" xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                     stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
 
            <div class="hp-profile-dropdown" id="hpProfileDropdown">
                <div class="hp-profile-info">
                    <?php if ($hasPhoto): ?>
                        <img src="/images/new-image-save/<?= htmlspecialchars($sessionUser->profilepic) ?>"
                             class="hp-profile-dropdown-avatar" alt="<?= htmlspecialchars($sessionUser->username) ?>">
                    <?php else: ?>
                        <div class="hp-profile-initials hp-profile-initials-lg">
                            <?= strtoupper(substr($sessionUser->username, 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    <div class="hp-profile-info-text">
                        <div class="hp-profile-name"><?= htmlspecialchars($sessionUser->username) ?></div>
                        <div class="hp-profile-email"><?= htmlspecialchars($sessionUser->email) ?></div>
                    </div>
                </div>
 
                <div class="hp-profile-divider"></div>
 
                <a href="/page/myaccount.php" class="hp-profile-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    My Account
                </a>
                <a href="/page/myorders.php" class="hp-profile-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    My Orders
                </a>
                <a href="/page/updateProfile.php" class="hp-profile-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Edit Profile
                </a>
 
                <div class="hp-profile-divider"></div>
 
                <a href="/security/logout.php" class="hp-profile-item hp-profile-logout">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Logout
                </a>
            </div>
        </div>
 
        <?php else: ?>
        <a href="/security/login.php"><img src="/images/icon/user.png" width="20" alt="User"></a>
        <?php endif; ?>
    </div>
</header>
 
<style>
.hp-profile-wrapper { position: relative; }
 
.hp-profile-trigger {
    background: none; border: none; cursor: pointer;
    padding: 4px 10px 4px 5px;
    display: flex; align-items: center; gap: 8px;
    border-radius: 20px;
    transition: background 0.2s;
}
.hp-profile-trigger:hover { background: rgba(255,255,255,0.07); }
 
.hp-profile-avatar {
    width: 32px; height: 32px; border-radius: 50%;
    object-fit: cover; border: 2px solid #555; flex-shrink: 0;
    transition: border-color 0.2s;
}
.hp-profile-trigger:hover .hp-profile-avatar,
.hp-profile-trigger:hover .hp-profile-initials { border-color: #999; }
 
.hp-profile-initials {
    width: 32px; height: 32px; border-radius: 50%;
    background: #333; border: 2px solid #555;
    color: #fff; font-size: 13px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Montserrat', sans-serif; flex-shrink: 0;
    transition: border-color 0.2s;
}
.hp-profile-initials-lg { width: 44px; height: 44px; font-size: 18px; }
 
.hp-profile-trigger-name {
    color: #f2f2f2; font-size: 13px;
    font-family: 'Montserrat', sans-serif; font-weight: 500;
    white-space: nowrap; max-width: 110px;
    overflow: hidden; text-overflow: ellipsis;
}
 
.hp-profile-caret {
    color: #888; flex-shrink: 0; transition: transform 0.2s;
}
.hp-profile-trigger[aria-expanded="true"] .hp-profile-caret { transform: rotate(180deg); }
 
.hp-profile-dropdown {
    position: absolute; top: calc(100% + 12px); right: 0;
    background: #1a1a1a; border: 1px solid #2f2f2f;
    border-radius: 12px; padding: 8px; min-width: 220px;
    box-shadow: 0 12px 40px rgba(0,0,0,0.75);
    opacity: 0; visibility: hidden; transform: translateY(-8px);
    transition: all 0.2s cubic-bezier(0.4,0,0.2,1); z-index: 1002;
}
.hp-profile-dropdown.active {
    opacity: 1; visibility: visible; transform: translateY(0);
}
 
.hp-profile-info { display: flex; align-items: center; gap: 10px; padding: 8px 8px 12px; }
.hp-profile-dropdown-avatar {
    width: 44px; height: 44px; border-radius: 50%;
    object-fit: cover; flex-shrink: 0; border: 2px solid #333;
}
.hp-profile-info-text { overflow: hidden; }
.hp-profile-name {
    color: #f2f2f2; font-size: 13px; font-weight: 600;
    font-family: 'Montserrat', sans-serif;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 145px;
}
.hp-profile-email {
    color: #666; font-size: 11px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 145px; margin-top: 2px;
}
.hp-profile-divider { height: 1px; background: #2a2a2a; margin: 4px 0; }
 
.hp-profile-item {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 10px; color: #bbb; text-decoration: none;
    font-size: 13px; border-radius: 7px;
    transition: background 0.15s, color 0.15s;
    font-family: 'Roboto', sans-serif; white-space: nowrap;
}
.hp-profile-item:hover { background: #252525; color: #fff; }
.hp-profile-logout { color: #ff6b6b; }
.hp-profile-logout:hover { background: rgba(255,107,107,0.08); color: #ff4444; }
</style>
 
<script>
    const searchIconBtn    = document.getElementById('hpsearchIconBtn');
    const dropdown         = document.getElementById('hpsearchDropdown');
    const input            = document.getElementById('hpsearchInput');
    const resultsContainer = document.getElementById('hpsearchResults');
 
    searchIconBtn.addEventListener('click', function(e) {
        e.preventDefault();
        dropdown.classList.toggle('active');
        if (dropdown.classList.contains('active')) input.focus();
    });
 
    input.addEventListener('input', function() {
        const query = this.value.trim();
        if (query.length > 0) {
            fetch(`/page/livesearch.php?q=${encodeURIComponent(query)}`)
                .then(r => r.json())
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
        const sw = document.querySelector('.hpsearch-wrapper');
        if (sw && !sw.contains(e.target) && dropdown.classList.contains('active')) {
            dropdown.classList.remove('active');
            resultsContainer.style.display = 'none';
        }
    });
 
    const profileTrigger  = document.getElementById('hpProfileTrigger');
    const profileDropdown = document.getElementById('hpProfileDropdown');
    if (profileTrigger && profileDropdown) {
        profileTrigger.addEventListener('click', function(e) {
            e.stopPropagation();
            const open = profileDropdown.classList.toggle('active');
            profileTrigger.setAttribute('aria-expanded', String(open));
        });
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('hpProfileWrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                profileDropdown.classList.remove('active');
                profileTrigger.setAttribute('aria-expanded', 'false');
            }
        });
    }
</script>