
<?php
session_start();

$user = $_SESSION['user'] ?? null;  // 👈 grab the user object

$pageTitle = 'My Account';
include '../header.php'; 
?>

<main class="container">
    <div class="account-header">
        <h1 style="color: white;">Welcome Back, <?= htmlspecialchars($user->name ?? 'Guest') ?>!</h1>
    </div>

    <div class="account-card">
        <div class="account-hero">
            <div class="account-avatar">
                <span style="color:white;"><?= $user ? strtoupper(substr($user->name, 0, 2)) : 'GU' ?></span>
            </div>
            <div>
                <span style="color: white;"><?= htmlspecialchars($user->name ?? 'Guest') ?></span>
                <span style="color: white;"><?= htmlspecialchars($user->email ?? 'Not logged in') ?></span>
            </div>
        </div>

        <div class="dashboard-grid">
            <a href="account.php" class="dashboard-card">
                <div class="card-icon">👤</div>
                <div>
                    <div class="card-title">My Profile</div>
                    <div class="card-desc">Edit your personal details and contact info</div>
                </div>
            </a>

            <a href="myorders.php" class="dashboard-card">
                <div class="card-icon">📦</div>
                <div>
                    <div class="card-title">My Orders</div>
                    <div class="card-desc">Check status and history of your purchases</div>
                </div>
            </a>
        </div>

        <div class="logout-section">
            <p style="color: #ffffff; margin-bottom: 15px;">Finished for today?</p>
            <a href="../security/logout.php" class="btn-logout">Logout from Account</a>
        </div>
    </div>


</main>

<?php include '../footer.php'; ?>