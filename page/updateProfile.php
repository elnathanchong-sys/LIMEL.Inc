<?php
include '../base.php';

auth();



// Load user
if (is_get()) {

    $stm = $_db->prepare('SELECT * FROM users WHERE userID = ?');
    $stm->execute([$_user->userID]);
    $u = $stm->fetch();

    if (!$u) {
        redirect('/');
    }

    extract((array)$u);
    $profilepic = $u->profilepic;
}


if (is_post()) {

    $email = req('email');
    $username  = req('username');
    $profilepic = $_SESSION['profilepic'];
    $f = get_file('profilepic');

    // EMAIL
    if ($email == '') {
        $_err['email'] = 'Required';
    }
    else if (strlen($email) > 100) {
        $_err['email'] = 'Maximum 100 characters';
    }
    else if (!is_email($email)) {
        $_err['email'] = 'Invalid email';
    }
    else {
        $stm = $_db->prepare('
            SELECT COUNT(*) FROM users
            WHERE email = ? AND userID != ?
        ');
        $stm->execute([$email, $_user->userID]);

        if ($stm->fetchColumn() > 0) {
            $_err['email'] = 'Duplicated';
        }
    }

    // USERNAME
    if ($username == '') {
        $_err['username'] = 'Required';
    }
    else if (strlen($username) > 100) {
        $_err['username'] = 'Maximum 100 characters';
    }

    // profilepic (optional)
    if ($f) {
        if (!str_starts_with($f->type, 'image/')) {
            $_err['profilepic'] = 'Must be image';
        }
        else if ($f->size > 40 * 1024 * 1024) {
            $_err['profilepic'] = 'Maximum 40MB';
        }
    }

    function save_profilepic($file, $dir)
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $ext = pathinfo($file->name, PATHINFO_EXTENSION);
        $filename = uniqid('profile_', true) . ($ext ? ".{$ext}" : '');
        $destination = rtrim($dir, '/\\') . '/' . $filename;

        move_uploaded_file($file->tmp_name, $destination);

        return $filename;
    }
    // UPDATE
    if (!$_err) {

        if ($f) {
            if ($profilepic && file_exists("../images/new-image-save/$profilepic")) {
                unlink("../images/new-image-save/$profilepic");
            }

            $profilepic = save_profilepic($f, '../images/new-image-save');
        }

        $stm = $_db->prepare('
            UPDATE users
            SET email = ?, username = ?, profilepic = ?
            WHERE userID = ?
        ');

        $stm->execute([$email, $username, $profilepic, $_user->userID]);

        $_user->email = $email;
        $_user->username = $username;
        $_user->profilepic = $profilepic;

        temp('info', 'Profile updated');
        redirect('/page/myaccount.php');
    }
}
?>

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


    <?php $_title = 'Profile Update'; include '../header.php'; ?>
    <div class="profileUpdate-page">

        <form method="post" enctype="multipart/form-data" class="profileUpdate-card">

            <h1 class="profileUpdate-title">Update Profile</h1>

            <!-- profilepic -->
            

            <div class="profileUpdate-photoBox">

                <label class="profileUpdate-upload" id="uploadArea">

                    <?php
                    $img = (!empty($u->profilepic) && file_exists("../images/new-image-save/" . $u->profilepic))
                        ? $u->profilepic
                        : 'default.png';
                    ?>

                    <img 
                        src="/images/new-image-save/<?= htmlspecialchars($img) ?>"
                        class="profileUpdate-avatar"
                        id="previewImg"
                    >

                    <input type="file" name="profilepic" id="fileInput" accept="image/*" hidden>

                    <div class="profileUpdate-uploadText">
                        Drag & Drop or Click to change photo
                    </div>

                </label>

                <?= err('profilepic') ?>

            </div>


            <div class="profileUpdate-group">

                <label class="profileUpdate-label">Email</label>
                <?= html_text('email', 'maxlength="100"') ?>
                <?= err('email') ?>

            </div>

            <div class="profileUpdate-group">

                <label class="profileUpdate-label">Username</label>
                <?= html_text('username', 'maxlength="100"') ?>
                <?= err('username') ?>

            </div>

            <!-- BUTTONS -->
            <section class="profileUpdate-actions">
                <button class="profileUpdate-btn primary">Save</button>
                <button type="reset" class="profileUpdate-btn secondary">Reset</button>
            </section>

            <div class="profileUpdate-link">
                <a href="changePassword.php">Change password?</a>
            </div>
        </form>

    </div>
<?php include '../footer.php'; ?>

