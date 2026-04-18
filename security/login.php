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


include '../header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/app.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="./app.js"></script>
</head>

<body>
    
    <div class = "login-page">
        <div class="login-title">
            <h1>Login</h1><br><br>
        </div>
        <form method="post" class="login-form">
            <label for="email">Email</label>
            <?= html_text('email', 'maxlength="100"') ?>
            <?= err('email') ?>

            <label for="password">Password</label>
            <?= html_password('password', 'maxlength="100"') ?>
            <?= err('password') ?>

            
            <section>
                <button class="login-button">Login</button>
                <button class="login-button-create" type="reset">Reset</button>
            </section>
        </form>
        <div class="login-create-account">
            <a href="/page/account.php" class="no-underlined">No account? Create account</a>
        </div>
    </div>
</body>

<?php
include '../footer.php';