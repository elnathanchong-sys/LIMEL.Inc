=<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin-login.php");
    exit();
}

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

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if (!empty($search)) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username LIKE ? OR email LIKE ? OR userID LIKE ? ORDER BY acountCreatedAt DESC");
    $searchTerm = "%$search%";
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
} else {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY acountCreatedAt DESC");
}

$members = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIMEL | Member Directory</title>
    <link rel="tab icon" href="/images/Logo.png">
    <link rel="stylesheet" href="/app.css>
</head>
<body class="admin-body">
    <?php include 'sidebar.php';?>

    <div class="adm-ln-main">
        <div class="adm-ln-container" style="max-width: 900px;">
            <div class="adm-ln-header">
                <h1>Member Management</h1>
                <p>Staff View: Browsing all registered users</p>
            </div>

            <form method="GET" style="margin-bottom: 25px;">
                <div style="display: flex; gap: 10px;">
                    <input type="text" name="search" class="adm-ln-input" placeholder="Search by name, email, or ID..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="adm-ln-btn" style="width: auto; padding: 0 25px;">Search</button>
                </div>
            </form>

            <table style="width: 100%; border-collapse: collapse; color: white;">
                <thead>
                    <tr style="border-bottom: 2px solid #2a2a2a; text-align: left;">
                        <th style="padding: 12px; color: #667eea;">User</th>
                        <th style="padding: 12px; color: #a78bfa;">Email</th>
                        <th style="padding: 12px; color: #a0a0a0;">Registered</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($members) > 0): ?>
                        <?php foreach ($members as $m): ?>
                            <tr style="border-bottom: 1px solid #2a2a2a;">
                                <td style="padding: 12px;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div class="adm-ln-badge-inner" style="width: 35px; height: 35px; font-size: 14px; margin: 0;">
                                            <?= strtoupper(substr($m['username'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div style="font-weight: 600;"><?= htmlspecialchars($m['username']) ?></div>
                                            <div style="font-size: 11px; color: #a0a0a0;">#<?= htmlspecialchars($m['userID']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 12px; color: #667eea; font-size: 14px;">
                                    <?= htmlspecialchars($m['email']) ?>
                                </td>
                                <td style="padding: 12px; color: #a0a0a0; font-size: 13px;">
                                    <?= date('M j, Y', strtotime($m['acountCreatedAt'])) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="padding: 40px; text-align: center; color: #a0a0a0;">No members found matching your search.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>