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

<style>

.account-wrapper {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #000;
    padding: 20px;
}

.account-container {
    max-width: 700px;
    background: #1e1e1e;
    padding: 40px;
    border-radius: 12px;
    border: 1px solid #333;
    color: #fff;
    box-sizing: border-box;
}


.account-header {
    text-align: center;
    margin-bottom: 25px;
}


.account-profile-section {
    text-align: center;
    margin-bottom: 25px;
}


.profile-picture-container {
    width: 110px;
    height: 110px;
    margin: 0 auto 15px;
    border-radius: 50%;
    background: #141414;
    border: 2px solid #555;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.profile-picture-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}


#imgPlaceholder {
    color: #aaa;
    font-weight: bold;
}


.account-upload-area {
    width: 220px;
    margin: 0 auto;
    padding: 15px;
    border: 2px dashed #555;
    border-radius: 10px;
    cursor: pointer;
    color: #aaa;
    text-align: center;
    transition: 0.2s;
}

.account-upload-area:hover {
    border-color: #764ba2;
    color: #fff;
}


.account-form-group {
    margin-bottom: 18px;
}

.account-form-group label {
    display: block;
    margin-bottom: 6px;
    font-size: 14px;
    color: #bbb;
}

.account-form-group input {
    width: 100%;
    padding: 12px;
    background: #292929;
    border: 2px solid #444;
    border-radius: 6px;
    color: #fff;
    box-sizing: border-box;
}


.account-button-group {
    display: flex;
    gap: 12px;
    margin-top: 20px;
}

.account-button-create {
    flex: 1;
    background: #764ba2;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.2s;
}

.account-button-create:hover {
    background: #5f3b86;
}

.account-button-clear {
    flex: 1;
    background: #444;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.2s;
}

.account-button-clear:hover {
    background: #555;
}

</style>

</head>

    <body>

        <div class="account-wrapper">

            <form method="post" enctype="multipart/form-data">
                <div class="account-container">

                    <div class="account-header">
                        <h1>Create Account</h1>
                    </div>

                    <div class="account-profile-section">

                        <div class="profile-picture-container">
                            <img id="imagePreview">
                            <span id="imgPlaceholder">USER</span>
                        </div>

                        <div class="account-upload-area" id="uploadArea">
                            Drag & Drop image<br>or click
                            <input type="file" id="fileInput" name="profilepic" accept="image/*" hidden>
                        </div>

                        <?= err('profilepic') ?>
                        

                    </div>

                        <div class="account-form-group">
                            <label>Username</label>
                            <?= html_text('username') ?>
                            <?= err('username') ?>
                        </div>

                        <div class="account-form-group">
                            <label>Email</label>
                            <?= html_text('email') ?>
                            <?= err('email') ?>
                        </div>

                        <div class="account-form-group">
                            <label>Password</label>
                            <?= html_password('password') ?>
                            <?= err('password') ?>
                        </div>

                        <div class="account-form-group">
                            <label>Confirm Password</label>
                            <?= html_password('confirm') ?>
                            <?= err('confirm') ?>
                        </div>

                        <div class="account-button-group">
                            <button type="submit" class="account-button-create">Create</button>
                            <button type="reset" class="account-button-clear">Clear</button>
                        </div>
                    </div>
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
        </script>

    </body>
</html>