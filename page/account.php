<?php
include '../base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    $username = req('username');
    $email  = req('email');
    $password  = req('password');
    $confirm = req('confirm');
    $f = get_file('profilepic');

    // Validate: username
    if (!$username) {
        $_err['username'] = 'Required';
    }
    else if (strlen($username) > 100) {
        $_err['username'] = 'Maximum 100 characters';
    }
    
    // Validate: email
    if (!$email) {
        $_err['email'] = 'Required';
    }
    else if (strlen($email) > 100) {
        $_err['email'] = 'Maximum 100 characters';
    }
    else if (!is_email($email)) {
        $_err['email'] = 'Invalid email';
    }
    else if (!is_unique($email, 'users', 'email')) {
        $_err['email'] = 'Duplicated';
    }

    // Validate: password
    if (!$password) {
        $_err['password'] = 'Required';
    }
    else if (strlen($password) < 5 || strlen($password) > 100) {
        $_err['password'] = 'Between 5-100 characters';
    }

    // Validate: confirm
    if (!$confirm) {
        $_err['confirm'] = 'Required';
    }
    else if (strlen($confirm) < 5 || strlen($confirm) > 100) {
        $_err['confirm'] = 'Between 5-100 characters';
    }
    else if ($confirm != $password) {
        $_err['confirm'] = 'Not matched';
    }

    // Validate: photo (file)
    if (!$f) {
        $_err['profilepic'] = 'Required';
    }
    else if (!str_starts_with($f->type, 'image/')) {
        $_err['profilepic'] = 'Must be image';
    }
    else if ($f->size > 1 * 1024 * 1024) {
        $_err['profilepic'] = 'Maximum 1MB';
    }

    // DB operation
    if (!$_err) {

        // Ensure folder exists
        $folder = __DIR__ . '/../images/new-image-save';

        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        try {
            $profilepic = upload_image($f, $folder);
        } catch (Exception $e) {
            $_err['pprofilepic'] = $e->getMessage();
        }

        if ($_err) {
            temp('error', 'Image upload failed');
            redirect(); 
        }

        // (2) Insert user (member)
        $stm = $_db->prepare('
            INSERT INTO users (username, email, password, profilepic, role)
            VALUES (?, ?, SHA1(?), ?, "Member")
        ');
        $stm->execute([$username, $email, $password, $profilepic]);

        temp('info', 'Record inserted');
        redirect('/login.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <link rel="stylesheet" href="/app.css">
</head>
<body class="account-management-system">
    <?php include '../header.php'; ?>

    <button class="logout-btn" id="logoutBtn">← Back to Login</button>

    <div class="user-status-bar">
        Logged in as: <span id="navUsername">Guest</span>
    </div>
    
    <form method="post" enctype="multipart/form-data">
        <div class="container">
            <div class="header">
                <h1>Account Settings</h1>
            </div>

            <div class="card">
                <div class="success-message" id="successMessage">✓ Profile updated successfully</div>
                <div class="error-message" id="errorMessage"></div>

                <div class="profile-section">
                    <div class="upload-area">
                        <div class="profile-picture-container" id="profilePictureContainer">
                            <img id="imagePreview" src="" alt="Profile" style="display: none;">
                            <span id="imgPlacehovlder">USER</span>
                        </div>
                        <div class="account-upload"><label for="photo"></label>
                            <label class="insert-upload">
                                <?= html_file('profilepic', 'image/*', 'hidden') ?>
                                <div class="insert-upload-box">
                                    <img src ="/images/icon/account-drag-drop.png">
                                </div>
                            </label>
                            <?= err('profilepic') ?><br>
                        </div>

                    </div>

                    <div class="form-section">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <?= html_text('username', 'maxlength="100"') ?>
                            <?= err('username') ?>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <?= html_text('email', 'maxlength="100"') ?>
                            <?= err('email') ?>
                        </div>

                        <div class="form-group">
                            <label for="phone">Password</label>
                            <?= html_password('password', 'maxlength="100"') ?>
                            <?= err('password') ?>
                        </div>
                        </div> </div> 
                        <div class="form-group">
                            <label for="confirm">Confirm Password</label>
                            <?= html_password('confirm', 'maxlength="100"') ?>
                            <?= err('confirm') ?>
                        </div>

                    <div class="button-group">
                        <button class="btn btn-primary" id="saveBtn">Save Changes</button>
                        <button class="btn btn-secondary" id="clearBtn">Clear All</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>
<!--
    <script>
        document.addEventListener('DOMContentLoaded', () => {
    const fileInput = document.getElementById('fileInput');
    const imagePreview = document.getElementById('imagePreview');
    const imgPlaceholder = document.getElementById('imgPlaceholder');
    const saveBtn = document.getElementById('saveBtn');
    const clearBtn = document.getElementById('clearBtn');
    const logoutBtn = document.getElementById('logoutBtn');
    const navUsername = document.getElementById('navUsername');
    const successMsg = document.getElementById('successMessage');
    const errorMsg = document.getElementById('errorMessage');

    logoutBtn.addEventListener('click', () => {
        window.location.href = 'login.php';
    });


    const handleFileUpload = (file) => {
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
                imgPlaceholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    };

    dragDropArea.addEventListener('click', () => {
        fileInput.click();
    });

    fileInput.addEventListener('change', (e) => {
        handleFileUpload(e.target.files[0]);
    });


    saveBtn.addEventListener('click', () => {
        const userValue = document.getElementById('username').value;
        const emailValue = document.getElementById('email').value;
        const phoneValue = phoneInput.value;

        if (userValue && emailValue && phoneValue) {
            navUsername.textContent = userValue;
            errorMsg.classList.remove('show');
            successMsg.classList.add('show');
            setTimeout(() => successMsg.classList.remove('show'), 3000);
        } else {
            errorMsg.textContent = "Please fill in all fields.";
            errorMsg.classList.add('show');
        }
    });

    clearBtn.addEventListener('click', () => {
        document.getElementById('username').value = '';
        document.getElementById('email').value = '';
        imagePreview.src = '';
        imagePreview.style.display = 'none';
        imgPlaceholder.style.display = 'block';
        fileInput.value = '';
        successMsg.classList.remove('show');
        errorMsg.classList.remove('show');
    });
});
    </script>
    <?php include '../footer.php'; ?>
</body>
</html>