<?php
session_start();
/*
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../security/adminlogin.php");
    exit();
}
*/
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
    <link rel="stylesheet" href="/app.css">
</head>
<body class="member-body">
    <?php include 'sidebar.php';?>

    <div class="member-container">
        <div class="member-header">
            <h1>Member Management</h1>
            <p class="member-subtitle">Staff View: Browsing all registered users</p>
        </div>

        <div class="member-card">
            <form method="GET" class="member-toolbar">
                <input type="text" name="search" class="member-search-input" placeholder="Search by name, email, or ID..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="member-search-btn">Search</button>
            </form>

            <table class="member-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Registered</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($members) > 0): ?>
                        <?php foreach ($members as $m): ?>
                            <tr>
                                <td>
                                    <div class="u-profile-cell">
                                        <div class="u-avatar">
                                            <?= strtoupper(substr($m['username'], 0, 1)) ?>
                                        </div>
                                        <div class="u-info-wrapper">
                                            <span class="u-username"><?= htmlspecialchars($m['username']) ?></span>
                                            <span class="u-userid">#<?= htmlspecialchars($m['userID']) ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="mailto:<?= htmlspecialchars($m['email']) ?>" class="u-email-link">
                                        <?= htmlspecialchars($m['email']) ?>
                                    </a>
                                </td>
                                <td class="u-date-text">
                                    <?= date('M j, Y', strtotime($m['acountCreatedAt'])) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="member-no-data">
                                No members found matching your search.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>