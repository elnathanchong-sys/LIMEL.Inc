<?php
include '../base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    $username = req('username');
    $email  = req('email');
    $password  = req('password');
    $confirm = req('confirm');
    $f = $_FILES['profilepic'] ?? null;

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
    if (!$f || $f['error'] !== UPLOAD_ERR_OK) {
        $_err['profilepic'] = 'Required';
    }
    else {
        $check = getimagesize($f['tmp_name']);

        if ($check === false) {
            $_err['profilepic'] = 'Must be image';
        }
        else if ($f['size'] > 40 * 1024 * 1024) {
            $_err['profilepic'] = 'Maximum 40MB';
        }
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
            $_err['profilepic'] = $e->getMessage();
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
        redirect('/index.php');
    }
}
?>

<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Account</title>
<link rel="stylesheet" href="/app.css">
</head>
<?php include '../header.php'; ?>
    <body>


        <div class="account-wrapper">

            <form method="post" enctype="multipart/form-data">
                <div class="account-container">

                    <div class="account-header">
                        <h1>Create Account</h1>
                        
                    </div>

                    <div class="account-profile-section">
                    <div class="profile-picture-container">
                        <img id="imagePreview" style="display: none;">
                        <svg id="imgPlaceholder" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>

                    <div class="account-upload-area" id="uploadArea">
                        Drag & Drop image<br><span style="font-size: 12px; color: #777;">or click to browse</span>
                        <input type="file" id="fileInput" name="profilepic" accept="image/*" hidden>
                    </div>
                    <div class="account-upload-error"><?= err('profilepic') ?></div>
                </div>

                <div class="account-form-grid">
                    <div class="account-form-group">
                        <label>Username</label>
                        <?= html_text('username', 'placeholder="e.g. LimelFan99"') ?>
                        <div class="field-error"><?= err('username') ?></div>
                    </div>

                    <div class="account-form-group">
                        <label>Email</label>
                        <?= html_text('email', 'placeholder="your@email.com"') ?>
                        <div class="field-error"><?= err('email') ?></div>
                    </div>

                    <div class="account-form-group">
                        <label>Password</label>
                        <?= html_password('password', 'placeholder="••••••••"') ?>
                        <div class="field-error"><?= err('password') ?></div>
                    </div>

                    <div class="account-form-group">
                        <label>Confirm Password</label>
                        <?= html_password('confirm', 'placeholder="••••••••"') ?>
                        <div class="field-error"><?= err('confirm') ?></div>
                    </div>
                </div>

                <div class="account-button-group">
                    <button type="submit" class="account-button-create">Create Account</button>
                    <button type="reset" class="account-button-clear">Clear</button>
                </div>
                
                <div class="account-login-link">
                    Already have an account? <a href="/security/login.php">Log in here</a>
                </div>
                
            </form>

        </div>

        <script>
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('fileInput');
        const preview = document.getElementById('imagePreview');
        const placeholder = document.getElementById('imgPlaceholder');

        uploadArea.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', e => {
            handleFile(e.target.files[0]);
        });

        uploadArea.addEventListener('dragover', e => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', e => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');

            fileInput.files = e.dataTransfer.files;
            handleFile(e.dataTransfer.files[0]);
        });

        function handleFile(file) {
            if (!file || !file.type.startsWith('image/')) return;

            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
        
        document.querySelector('.account-button-clear').addEventListener('click', () => {
            preview.src = '';
            preview.style.display = 'none';
            placeholder.style.display = 'block';
            uploadArea.classList.remove('dragover');
        });
    </script>

    
</body>



</html>