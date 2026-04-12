<?php

// REGISTERING a new user or RESETTING password
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// User LOGS IN to verify their password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

?>