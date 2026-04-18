<?php
require_once '../base.php';

if (is_post()) {
    $email    = post('email');
    $password = post('password');

    $stmt = $_db->prepare("SELECT *, 'Member' as assigned_role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $u = $stmt->fetch();

    if (!$u) {
        $stmt = $_db->prepare("SELECT *, 'Admin' as assigned_role FROM staff WHERE staffEmail = ?");
        $stmt->execute([$email]);
        $u = $stmt->fetch();

        if ($u) {
            $u->password = $u->staffPassword;
            $u->username = $u->staffName;
        }
    }

    if ($u && password_verify($password, $u->password)) {
        session_regenerate_id(true);
        $u->role = $u->assigned_role;
        $_SESSION['user'] = $u;

        if ($u->role === 'Admin') {
            redirect('../../Administration/dashboard.php');
        } else {
            redirect('../page/account.php');
        }
    } else {
        temp('error', 'Invalid email or password');
        redirect('login.php');
    }
}