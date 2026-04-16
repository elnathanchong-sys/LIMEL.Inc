<?php
require_once '../base.php';
$err = temp('error');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../app.css">
</head>
<body class="login-body">
<main class="login-main">
    <div class="login-container">
        <div class="login-header">
            <h1>Welcome Back</h1>
            <p>Enter your credentials to access your account</p>
        </div>

        <?php if ($err): ?>
            <div class="error-message show"><?= htmlspecialchars($err) ?></div>
        <?php endif; ?>

        <form method="POST" action="loginprocess.php">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="email@example.com">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Enter password">
            </div>
            <button type="submit" class="login-btn">Sign In</button>
        </form>

        <div class="signup-link">
            Don't have an account? <a href="register.php">Create one</a>
        </div>
    </div>
</main>
</body>
</html>