<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../security/login.php");
    exit();
}

$pageTitle = 'My Account';
include '../header.php'; 
?>


<main class="container">
    <div class="account-header">
        <h1>Welcome Back, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
    </div>

    <div class="account-card">
        <div class="account-hero">
            <div class="account-avatar">
                <?= strtoupper(substr($_SESSION['username'], 0, 2)) ?>
            </div>
            <div>
                <div class="account-name"><?= htmlspecialchars($_SESSION['username']) ?></div>
                <div class="account-email">Verified Member</div>
            </div>
        </div>

        <div class="dashboard-grid">
            <a href="profile-edit.php" class="dashboard-card">
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

            <a href="cart.php" class="dashboard-card">
                <div class="card-icon">❤️</div>
                <div>
                    <div class="card-title">Favourited Item</div>
                    <div class="card-desc">My Favourites</div>
                </div>
            </a>
        </div>

        <div class="logout-section">
            <p style="color: #888; margin-bottom: 15px;">Finished for today?</p>
            <a href="../security/logout.php" class="btn-logout">Logout from Account</a>
        </div>
        &nbsp;
        &nbsp;
        &nbsp;
        &nbsp;
        &nbsp;
        
    </div>
</main>

<?php include '../footer.php'; ?>