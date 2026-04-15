<?php
$error = '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$members = [
    [
        'userID' => 'MEM-001',
        'username' => 'Alex Rivera',
        'email' => 'arivera@example.com',
        'accountCreatedAt' => '2026-01-15 09:30:00'
    ],
    [
        'userID' => 'MEM-002',
        'username' => 'Sarah Chen',
        'email' => 'schen@design.io',
        'accountCreatedAt' => '2026-02-10 14:45:00'
    ],
    [
        'userID' => 'MEM-003',
        'username' => 'Marcus Thorne',
        'email' => 'mthorne@tech.net',
        'accountCreatedAt' => '2026-03-05 11:20:00'
    ],
    [
        'userID' => 'MEM-004',
        'username' => 'Elena Vance',
        'email' => 'evance@limel.com',
        'accountCreatedAt' => '2026-04-12 16:00:00'
    ]
];

function toggleOrder($currentSort, $currentOrder, $col) {
    return ($currentSort === $col && $currentOrder === 'ASC') ? 'DESC' : 'ASC';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIMEL | Member Directory</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="admin-body">
    <?php include 'sidebar.php';?>

    <div class="container">
        <div class="header">
            <h1>Member Management</h1>
            <p class="subtitle">Staff View: Browsing all registered users</p>
        </div>

        <div class="card">
            <form method="GET">
                <div class="toolbar">
                    <input type="text" name="search" class="search-input" placeholder="Search users..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="search-btn">Search</button>
                </div>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Registered</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($members as $m): ?>
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="avatar"><?= strtoupper(substr($m['username'], 0, 1)) ?></div>
                                    <div class="user-info">
                                        <div class="username"><?= htmlspecialchars($m['username']) ?></div>
                                        <div class="userid">#<?= htmlspecialchars($m['userID']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="email-link"><?= htmlspecialchars($m['email']) ?></td>
                            <td class="date-text"><?= date('M j, Y', strtotime($m['accountCreatedAt'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>