<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'LIMEL' ?></title>
    <link rel="stylesheet" href="/app.css">
</head>
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
      <a href="/page/myaccount.php" class="no-underlined">Testing</a>
    </div>
  </nav>

 <div class="right-icon">
  <a href="#" id="userMenuBtn"><img src="/images/icon/user.png" width="20" alt="User"></a>
</div>

</header>