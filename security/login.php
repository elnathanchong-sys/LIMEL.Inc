<?php
require_once '../base.php';
$error = temp('error');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In — Shop</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../app.css">
</head>
<body class="login-page">
  <div class="card">
    <div class="logo">Shop<span>.</span></div>
    <p class="subtitle">Sign in to your account</p>

    <?php if ($error): ?>
      <div class="error-box"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="loginprocess.php" novalidate>
      
      <input type="hidden" name="csrf_token" value="<?= generate_csrf() ?>">

      <div class="field">
        <label for="email">Email address</label>
        <input type="email" id="email" name="email" required
               placeholder="you@example.com"
               autocomplete="email">
      </div>

      <div class="field">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required
               placeholder="••••••••"
               autocomplete="current-password">
      </div>

      <div class="forgot-link">
        <a href="forgotpassword.php">Forgot your password?</a>
      </div>

      <button type="submit" class="btn">Sign In</button>
    </form>

    <div class="divider">or</div>

    <p class="register-link">
      New customer? <a href="../account.php">Create an account</a>
    </p>
  </div>
</body>
</html>