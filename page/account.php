<?php
require '../base.php';
auth();

$user_id = $_user->id;
$success = false;
$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    
    if (empty($name)) { $errors[] = 'Name is required'; }
    if (empty($email) || !is_email($email)) { $errors[] = 'Valid email is required'; }
    if (empty($phone) || !preg_match('/^[0-9]+$/', $phone)) { $errors[] = 'Phone must contain numbers only'; }
    
    if (empty($errors)) {
        $sql = "SELECT COUNT(*) as cnt FROM user WHERE email = ? AND id != ?";
        $result = query($sql, [$email, $user_id]);
        if ($result[0]['cnt'] > 0) { $errors[] = 'Email already in use'; }
    }
    
    $photo = $_user->photo;
    if (isset($_FILES['photo']) && $_FILES['photo']['size'] > 0) {
        $file = $_FILES['photo'];
        $allowed = array('image/jpeg', 'image/png', 'image/gif', 'image/webp');
        if (!in_array($file['type'], $allowed)) {
            $errors[] = 'Invalid image type';
        } else {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newFilename = 'profile_' . $user_id . '_' . time() . '.' . $ext;
            if (move_uploaded_file($file['tmp_name'], '../uploads/' . $newFilename)) {
                if ($_user->photo != 'default.png' && file_exists('../uploads/' . $_user->photo)) {
                    unlink('../uploads/' . $_user->photo);
                }
                $photo = $newFilename;
            }
        }
    }
    
    if (empty($errors)) {
        $sql = "UPDATE user SET name = ?, email = ?, phone = ?, photo = ? WHERE id = ?";
        query($sql, [$name, $email, $phone, $photo, $user_id]);
        $_user->name = $name;
        $_user->email = $email;
        $_user->phone = $phone;
        $_user->photo = $photo;
        $success = true;
    }
}

$current_name = $_user->name;
$current_email = $_user->email;
$current_phone = $_user->phone ?? '';
$current_photo = $_user->photo ?? 'default.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIMEL - Account</title>
    <link rel="stylesheet" href="/app.css">
</head>
<body>

<header class="account-header">
    <div class="logo">LIMEL</div>
    <div class="account-user-info">
        <span><?= htmlspecialchars($current_name) ?></span>
        <img src="../uploads/<?= $current_photo ?>" onerror="this.src='../images/default-profile.png'">
    </div>
</header>

<main>
<section class="account-page">
    <div class="account-container">
        <form method="post" enctype="multipart/form-data" action="account.php">

            <div class="profile-picture">
                <img src="../uploads/<?= $current_photo ?>" 
                     onerror="this.src='../images/default-profile.png'" 
                     id="profilePreview">
                <input type="file" name="photo" id="photoInput" accept="image/*">
                <p>Change Profile Picture</p>
            </div>

            <div class="account-details">
                <div class="detail">
                    <label for="username" class="label">Name:</label>
                    <input type="text" name="name" id="username" class="value" value="<?= htmlspecialchars($current_name) ?>" required>
                </div>

                <div class="detail">
                    <label for="email" class="label">Email:</label>
                    <input type="email" name="email" id="email" class="value" value="<?= htmlspecialchars($current_email) ?>" required>
                </div>

                <div class="detail">
                    <label for="phone" class="label">Phone:</label>
                    <input type="tel" name="phone" id="phone" class="value" pattern="[0-9]+" value="<?= htmlspecialchars($current_phone) ?>" required>
                </div>

                <div class="detail">
                    <label class="label">Security:</label>
                    <a href="password.php" class="forgot-link">Change Password</a>
                </div>

                <div class="button-wrapper">
                    <button type="submit" class="customize-button">Save Changes</button>
                    <button type="reset" class="customize-button reset-btn">Clear Data</button>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="error-messages">
                        <?php foreach ($errors as $error): ?>
                            <p class="error-msg"><?= $error ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <p id="success-msg" class="show">Profile updated successfully!</p>
                <?php endif; ?>
            </div> 
        </form>
    </div> 
</section>
</main>

<?php include '../footer.php'; ?>

</body>
</html>