<?php
require_once '../base.php';

if (!is_post()) {
    redirect('login.php');
}

if (!verify_csrf(post('csrf_token'))) {
    temp('error', 'Invalid request. Please try again.');
    redirect('login.php');
}

$ip        = $_SERVER['REMOTE_ADDR'];
$limit_key = 'login_attempts_' . md5($ip);

if (!isset($_SESSION[$limit_key])) {
    $_SESSION[$limit_key] = ['count' => 0, 'first_attempt' => time()];
}

$attempts = &$_SESSION[$limit_key];

if (time() - $attempts['first_attempt'] > 600) {
    $attempts = ['count' => 0, 'first_attempt' => time()];
}

if ($attempts['count'] >= 5) {
    $wait = ceil((600 - (time() - $attempts['first_attempt'])) / 60);
    temp('error', "Too many failed attempts. Please wait {$wait} minute(s).");
    redirect('login.php');
}

$email    = post('email');
$password = post('password');

if (empty($email) || empty($password)) {
    temp('error', 'Please fill in all fields.');
    redirect('login.php');
}

if (!is_email($email)) {
    temp('error', 'Invalid email address.');
    redirect('login.php');
}

$stmt = $_db->prepare("SELECT * FROM user WHERE email = ? AND password = SHA1(?) LIMIT 1");
$stmt->execute([$email, $password]);
$u = $stmt->fetch();

if ($u) {
    unset($_SESSION[$limit_key]);
    session_regenerate_id(true);
    login($u, $u->role === 'Admin' ? '../Administration/dashboard.php' : '../page/account.php');
} else {
    $attempts['count']++;
    temp('error', 'Invalid email or password.');
    redirect('login.php');
}