<?php
include '../base.php';

auth('Member');

// ----------------------------------------------------------------------------

if (is_post()) {
    $current = req('current');
    $password = req('password');
    $confirm = req('confirm');

    // Get current user password from DB
    $stm = $_db->prepare('SELECT password FROM users WHERE userID = ?');
    $stm->execute([$user->userID]);
    $u = $stm->fetch();

    // Validate: current password
    if (!$current) {
        $_err['current'] = 'Required';
    }
    else if (!password_verify($current, $u->password)) {
        $_err['current'] = 'Incorrect password';
    }

    // Validate: new password
    if (!$password) {
        $_err['password'] = 'Required';
    }
    else if (strlen($password) < 6) {
        $_err['password'] = 'Minimum 6 characters';
    }

    // Validate: confirm
    if ($confirm !== $password) {
        $_err['confirm'] = 'Passwords do not match';
    }

    // ----------------------------------------------------------------------------
    if (!$_err) {

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stm = $_db->prepare('
            UPDATE users
            SET password = ?
            WHERE userID = ?
        ');
        $stm->execute([$hashed, $user->userID]);

        temp('info', 'Password updated successfully');
        redirect('/page/myaccount.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Change Password</title>
<link rel="stylesheet" href="/app.css">
</head>

<?php include '../header.php'; ?>

<body>

<div class="account-wrapper">
    <form method="post">
        <div class="account-container">

            <div class="account-header">
                <h1>Change Password</h1>
            </div>

            <div class="account-form-grid">

                <div class="account-form-group">
                    <label>Current Password</label>
                    <?= html_password('current', 'placeholder="Enter current password"') ?>
                    <div class="field-error"><?= err('current') ?></div>
                </div>

                <div class="account-form-group">
                    <label>New Password</label>
                    <?= html_password('password', 'placeholder="New password"') ?>
                    <div class="field-error"><?= err('password') ?></div>
                </div>

                <div class="account-form-group">
                    <label>Confirm New Password</label>
                    <?= html_password('confirm', 'placeholder="Confirm new password"') ?>
                    <div class="field-error"><?= err('confirm') ?></div>
                </div>

            </div>

            <div class="account-button-group">
                <button type="submit" class="account-button-create">Update Password</button>
                <button type="reset" class="account-button-clear">Clear</button>
            </div>

        </div>
    </form>
</div>

</body>
</html>