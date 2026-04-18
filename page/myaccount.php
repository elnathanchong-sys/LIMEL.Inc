<?php
session_start();
include '../base.php';
$users = $_SESSION['users'] ?? null; 
$message = "";
$messageClass= "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $current_pass = $_POST['current_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    if ($new_pass !== $confirm_pass) {
        $message = "New passwords do not match!";
        $messageClass = "error";
    } else {
        $stmt = $_db->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$users->id]);
        $db_user = $stmt->fetch();

        if ($db_user && password_verify($current_pass, $db_user['password'])) {
            $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
            $update = $_db->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update->execute([$new_hash, $users->id]);
            
            $message = "Password updated successfully!";
            $messageClass = "success";
        } else {
            $message = "Current password is incorrect.";
            $messageClass = "error";
        }
    }
}

$pageTitle = 'My Account';
include '../header.php'; 
?>

<link rel="stylesheet" href="/app.css">

<main class="container">
    <div class="account-header">
        <h1 style="color: white;">Welcome back!</h1>
    </div>

    <div class="account-card">
        <div class="account-hero">
            <div> 
                <span style="color: white;"><?= htmlspecialchars($users->name ?? 'Guest') ?></span>
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
            <a href="security_update.php" class="dashboard-card">
        <div class="card-icon">🔒</div>
        <div>
            <div class="card-title">Password & Security</div>
            <div class="card-desc">Update your password and secure your account</div>
        </div>
            </a>
            </div>
        </div>

        <div class="logout-section">
            <p style="color: #ffffff; margin-bottom: 15px;">Finished for today?</p>
            <a href="../security/logout.php" class="btn-logout">Logout from Account</a>
        </div>
    </div>


</main>

<?php include '../footer.php'; ?>