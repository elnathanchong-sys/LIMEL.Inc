<?php
require_once '../base.php';

if (is_post()) {
    $email = post('email');
    $password = post('password');

    $stmt = $_db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user->password)) {
        $_SESSION['userID'] = $user->userID;
        $_SESSION['username'] = $user->username;
        redirect('/page/account.php');
    } else {
        temp('error', 'Invalid email or password');
        redirect('/page/login.php');
    }
}