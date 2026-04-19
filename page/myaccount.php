<?php
include '../base.php';

if (!isset($_SESSION['users']) || $_SESSION['users']->role !== 'Member') {
    redirect('/security/login.php');
}

$users     = $_SESSION['users'];
$pageTitle = 'My Account';

$hasPhoto = !empty($users->profilepic)
    && file_exists(__DIR__ . '/../images/new-image-save/' . $users->profilepic);

$wStmt = $_db->prepare("
    SELECT w.addedAt, p.*
    FROM wishlist w
    JOIN products p ON w.productID = p.productID
    WHERE w.userID = ?
    ORDER BY w.addedAt DESC
");
$wStmt->execute([$users->userID]);
$wishlistItems = $wStmt->fetchAll();

include '../header.php';
?>

<style>

</style>

<main class="myacc-page">


    <div class="myacc-hero">

        <div class="myacc-avatar-wrap">
            <?php if ($hasPhoto): ?>
                <img src="/images/new-image-save/<?= htmlspecialchars($users->profilepic) ?>"
                     class="myacc-avatar-img" alt="Profile photo">
            <?php else: ?>
                <div class="myacc-avatar-initials">
                    <?= strtoupper(substr($users->username, 0, 1)) ?>
                </div>
            <?php endif; ?>


            <a href="updateProfile.php" class="myacc-edit-btn" title="Edit profile">
                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24"
                     fill="none" stroke="#ccc" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
            </a>
        </div>


        <div class="myacc-hero-info">
            <div class="myacc-hero-name"><?= htmlspecialchars($users->username) ?></div>
            <div class="myacc-hero-email"><?= htmlspecialchars($users->email) ?></div>

            <span class="myacc-role-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
                <?= htmlspecialchars($users->role) ?>
            </span>

            <div class="myacc-hero-actions">
                <a href="updateProfile.php" class="myacc-action-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Edit Profile
                </a>
                <a href="changePassword.php" class="myacc-action-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    Change Password
                </a>
            </div>
        </div>
    </div>


    <div class="myacc-grid">
        <a href="updateProfile.php" class="myacc-card">
            <div class="myacc-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
            <div>
                <div class="myacc-card-title">My Profile</div>
                <div class="myacc-card-desc">Edit your personal details and photo</div>
            </div>
        </a>

        <a href="myorders.php" class="myacc-card">
            <div class="myacc-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>
                </svg>
            </div>
            <div>
                <div class="myacc-card-title">My Orders</div>
                <div class="myacc-card-desc">Check status and history of purchases</div>
            </div>
        </a>

        <a href="changePassword.php" class="myacc-card">
            <div class="myacc-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>
            <div>
                <div class="myacc-card-title">Change Password</div>
                <div class="myacc-card-desc">Update your account password</div>
            </div>
        </a>
    </div>


    <div class="myacc-danger">
        <div class="myacc-danger-text">
            <strong>Sign out</strong>
            You will be returned to the homepage after logging out.
        </div>
        <a href="../security/logout.php" class="myacc-logout-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16 17 21 12 16 7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            Logout
        </a>
    </div>

</main>

<?php include '../footer.php'; ?>
