<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="stylesheet" href="app.css">
    <style>
        .account-hero {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px 0;
            border-bottom: 1px solid #eee;
            margin-bottom: 30px;
        }
        .account-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #667eea;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
        }
        .account-name {
            font-size: 22px;
            font-weight: bold;
        }
        .account-email {
            color: #888;
            font-size: 14px;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 20px;
        }
        .dashboard-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 25px;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s;
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }
        .dashboard-card:hover {
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(102,126,234,0.2);
            transform: translateY(-2px);
        }
        .card-icon {
            font-size: 28px;
        }
        .card-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }
        .card-desc {
            font-size: 13px;
            color: #888;
        }
        .logout-btn {
            margin-top: 30px;
            display: inline-block;
        }
    </style>
</head>
<body class="account-management-system">

    <div class="user-status-bar">
        Logged in as: <span><?= htmlspecialchars($_SESSION['username']) ?></span>
    </div>

    <div class="container">
        <div class="header">
            <h1>My Account</h1>
        </div>

        <div class="card">

            <!-- User Info -->
            <div class="account-hero">
                <div class="account-avatar">
                    <?= strtoupper(substr($_SESSION['username'], 0, 2)) ?>
                </div>
                <div>
                    <div class="account-name">
                        <?= htmlspecialchars($_SESSION['username']) ?>
                    </div>
                    <div class="account-email">Member</div>
                </div>
            </div>

            <!-- Dashboard Cards -->
            <div class="dashboard-grid">

                <a href="account.php" class="dashboard-card">
                    <div class="card-icon">👤</div>
                    <div>
                        <div class="card-title">My Profile</div>
                        <div class="card-desc">Edit name, email, phone and profile photo</div>
                    </div>
                </a>

                <a href="orderhistory.php" class="dashboard-card">
                    <div class="card-icon">📦</div>
                    <div>
                        <div class="card-title">My Orders</div>
                        <div class="card-desc">View your order history and details</div>
                    </div>
                </a>

            </div>

            <!-- Logout -->
            <div class="logout-btn">
                <a href="logout.php" class="btn btn-secondary">Logout</a>
            </div>

        </div>
    </div>

</body>
</html>