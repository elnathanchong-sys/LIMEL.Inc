<?php
include '../base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    $email    = req('email');
    $password = req('password');

    // Validate: email
    if ($email == '') {
        $_err['email'] = 'Required';
    }
    else if (!is_email($email)) {
        $_err['email'] = 'Invalid email';
    }

    // Validate: password
    if ($password == '') {
        $_err['password'] = 'Required';
    }

    // Login user
    if (!$_err) {
        // TODO
        $stm = $_db->prepare('
            SELECT * FROM users
            WHERE email = ? AND password = SHA1(?)
        ');
        $stm->execute([$email, $password]);
        $u = $stm->fetch();

        if ($u) {
            temp('info', 'Login successfully');
            login($u);
        }
        else {
            $_err['password'] = 'Not matched';
        }
    }
}

// ----------------------------------------------------------------------------
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | LIMEL</title>
    <link rel="stylesheet" href="/app.css?v=<?= time() ?>">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="./app.js"></script>
</head>

<body class="auth-layout">

    <?php include '../header.php'; ?>

    <div class="account-wrapper">
        <form method="post" class="account-form-wrapper" style="max-width: 450px;">
            <div class="account-container" style="max-width: 450px; width: 100%; padding: 40px;">

                <div class="account-header">
                    <h1>Welcome Back</h1>
                    <p>Log in to your LIMEL account.</p>
                </div>

                <?php if (isset($_err['password']) && $_err['password'] == 'Not matched'): ?>
                    <div class="field-error" style="text-align: center; margin-bottom: 20px;">
                        <span class="err" style="font-size: 14px;">Invalid email or password.</span>
                    </div>
                <?php endif; ?>

                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div class="account-form-group">
                        <label for="email">Email</label>
                        <?= html_text('email', 'placeholder="your@email.com" maxlength="100"') ?>
                        <div class="field-error"><?= err('email') ?></div>
                    </div>

                    <div class="account-form-group">
                        <label for="password">Password</label>
                        <?= html_password('password', 'placeholder="••••••••" maxlength="100"') ?>
                        <div class="field-error"><?= err('password') ?></div>
                    </div>
                </div>

                <div class="account-button-group">
                    <button type="submit" class="account-button-create">Login</button>
                    <button type="reset" class="account-button-clear">Reset</button>
                </div>
                
                <div class="account-login-link">
                    No account? <a href="/page/account.php">Create account here</a>
                </div>
                
            </div>
        </form>
    </div>



</body>
</html>