<?php
session_start();
 
$host = 'localhost';
$db   = 'limel';
$user = 'root';
$pass = '';
 
try {
    $_db = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
 
$_user = $_SESSION['user'] ?? null;
 
function is_post() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}
 
function post($key) {
    return trim($_POST[$key] ?? '');
}
 
function redirect($url) {
    header("Location: $url");
    exit();
}
 
function temp($key, $value = null) {
    if ($value !== null) {
        $_SESSION["temp_$key"] = $value;
    } else {
        $value = $_SESSION["temp_$key"] ?? '';
        unset($_SESSION["temp_$key"]);
        return $value;
    }
}
 
function login($u, $url = 'index.php') {
    $_SESSION['user'] = $u;
    redirect($url);
}
 
function logout($url = 'index.php') {
    unset($_SESSION['user']);
    redirect($url);
}
 
function auth(...$roles) {
    global $_user;
    if ($_user) {
        if (!$roles || in_array($_user->role, $roles)) {
            return;
        }
    }
    redirect('/security/login.php');
}
 
function is_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}
 
function generate_csrf(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
 
function verify_csrf(?string $token): bool {
    return isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], (string) $token);
}
 