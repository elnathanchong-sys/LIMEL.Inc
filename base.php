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

function auth(...$roles) {
    global $_user;
    if (!$_user || ($roles && !in_array($_user->role, $roles))) {
        redirect('/security/login.php');
    }
}

//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;

//require 'PHPMailer/src/Exception.php';
//require 'PHPMailer/src/PHPMailer.php';
//require 'PHPMailer/src/SMTP.php';

//function get_mail() {
   // $mail = new PHPMailer(true);
    //$mail->isSMTP();
    //$mail->Host       = 'stmp.gmail.com';
   // $mail->SMTPAuth   = true;
   // $mail->Username   = 'j3rome0202@gmail.com';
   // $mail->Password   = 'adbcefghijklmnop';
   // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
   // $mail->Port       = 587;
   // $mail->setFrom('j3rome0202@gmail.com', 'Limel System');
   // return $mail;
//}