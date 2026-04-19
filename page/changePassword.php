<?php
include '../base.php';

auth();

// ----------------------------------------------------------------------------

if (is_post()) {

    $current = req('current_password');
    $new     = req('new_password');
    $confirm = req('confirm_password');

    // CURRENT PASSWORD
    if ($current == '') {
        $_err['current_password'] = 'Required';
    }

    // NEW PASSWORD
    if ($new == '') {
        $_err['new_password'] = 'Required';
    }
    else if (strlen($new) < 5 || strlen($new) > 100) {
        $_err['new_password'] = 'Between 5 - 100 characters';
    }

    // CONFIRM PASSWORD
    if ($confirm == '') {
        $_err['confirm_password'] = 'Required';
    }
    else if ($confirm != $new) {
        $_err['confirm_password'] = 'Not matched';
    }

    // CHECK CURRENT PASSWORD (DB)
    if (!$_err) {

        $stm = $_db->prepare('SELECT password FROM users WHERE userID = ?');
        $stm->execute([$_user->userID]);
        $dbPass = $stm->fetchColumn();

        if ($dbPass != sha1($current)) {
            $_err['current_password'] = 'Incorrect password';
        }
    }

    // UPDATE PASSWORD
    if (!$_err) {

        $stm = $_db->prepare('
            UPDATE users
            SET password = ?
            WHERE userID = ?
        ');

        $stm->execute([
            sha1($new),
            $_user->userID
        ]);

        temp('info', 'Password updated successfully');
        redirect('/page/myaccount.php');
    }
}
?>

<?php $_title = 'Change Password'; include '../header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/app.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="tab icon" href="/images/Logo.png">
    <script src="/Administration/product-listing/app.js"></script>
</head>
   
    <div class="passwordUpdate-page">

        <form method="post" class="passwordUpdate-card">

            <h1 class="passwordUpdate-title">Change Password</h1>

            <!-- CURRENT PASSWORD -->
            <div class="passwordUpdate-group">
                <label class="passwordUpdate-label">Current Password</label>
                <?= html_password('current_password') ?>
                <?= err('current_password') ?>
            </div>

            <!-- NEW PASSWORD -->
            <div class="passwordUpdate-group">
                <label class="passwordUpdate-label">New Password</label>
                <?= html_password('new_password') ?>
                <?= err('new_password') ?>
            </div>

            <!-- CONFIRM PASSWORD -->
            <div class="passwordUpdate-group">
                <label class="passwordUpdate-label">Confirm Password</label>
                <?= html_password('confirm_password') ?>
                <?= err('confirm_password') ?>
            </div>

            <!-- BUTTONS -->
            <section class="passwordUpdate-actions">
                <button class="passwordUpdate-btn primary">Update</button>
                <button type="reset" class="passwordUpdate-btn secondary">Reset</button>
            </section>

        </form>

    </div>  


<?php include '../footer.php'; ?>
