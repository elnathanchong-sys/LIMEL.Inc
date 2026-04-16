<?php
session_start();

$host = 'localhost';
$db   = 'limel';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Connection failed.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin-login.php");
    exit();
}

$email = trim($_POST['adm_user_field'] ?? '');
$password = $_POST['adm_pass_field'] ?? '';

if (empty($email) || empty($password)) {
    header("Location: admin-login.php?error=invalid");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM staff WHERE staffEmail = ?");
$stmt->execute([$email]);
$staff = $stmt->fetch();

if ($staff && password_verify($password, $staff['staffPassword'])) {
    session_regenerate_id(true);
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_id'] = $staff['staffID'];
    $_SESSION['admin_name'] = $staff['staffName'];
    
    header("Location: admin.php");
    exit();
}

header("Location: admin-login.php?error=invalid");
exit();