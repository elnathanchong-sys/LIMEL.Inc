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
    die("Database connection failed.");
}

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin.php");
    exit();
}

$error = '';
if (isset($_GET['error'])) {
    $error = match($_GET['error']) {
        'invalid'  => 'Invalid email or password.',
        'nonadmin' => 'Access denied.',
        default    => 'An error occurred.',
    };
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="/app.css">
</head>
<body class="adm-ln-body">

<main class="adm-ln-main">
    <div class="adm-ln-container">
        <div class="adm-ln-badge">
            <div class="adm-ln-badge-inner">🛡️</div>
        </div>

        <div class="adm-ln-header">
            <h1>Admin Access</h1>
            <p>Authorised personnel only</p>
        </div>

        <div class="adm-ln-ribbon">
            ⚠️ RESTRICTED AREA — Unauthorised access is prohibited
        </div>

        <?php if ($error): ?>
            <div class="adm-ln-error">✗ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="admin-login-process.php">
            <div class="adm-ln-group adm-ln-field-user">
                <label for="adm_user_field">Staff Email</label>
                <input type="email" id="adm_user_field" name="adm_user_field" class="adm-ln-input" placeholder="name@limel.com" required>
            </div>

            <div class="adm-ln-group adm-ln-field-pass">
                <label for="adm_pass_field">Password</label>
                <input type="password" id="adm_pass_field" name="adm_pass_field" class="adm-ln-input" placeholder="Enter password" required>
            </div>

            <button type="submit" class="adm-ln-btn">Enter Dashboard</button>
        </form>

        <div class="adm-ln-back">
            <a href="/">← Back to main site</a>
        </div>
    </div>
</main>

</body>
</html>