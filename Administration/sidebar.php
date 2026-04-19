<?php
$_sidebarUser = $_SESSION['users'] ?? null;

// Profile picture path check
$_sidebarHasPhoto = !empty($_sidebarUser->profilepic)
    && file_exists($_SERVER['DOCUMENT_ROOT'] . '/images/new-image-save/' . $_sidebarUser->profilepic);
?>

<aside class="admin-sidebar">
    <div class="sidebar-logo">
        <a href="/index.php"><img src="/images/logo.png" width="150"></a>
    </div>
    
    <div class="sidebar-menu">
        <a href="/Administration/dashboard.php" class="sidebar-link">Dashboard</a>
        <a href="/Administration/product-listing/inventory.php" class="sidebar-link">Inventory</a>
        <a href="/Administration/member.php" class="sidebar-link">Member</a>
        <a href="/Administration/order.php" class="sidebar-link">Order</a>
        <a href="/page/staffAccount.php" class="sidebar-link">Staff Account</a>
    </div>

    <div class="sidebar-bottom">

        <!-- Staff profile card -->
        <?php if ($_sidebarUser): ?>
        <div class="sb-profile-card">

            <!-- Avatar -->
            <div class="sb-avatar-wrap">
                <?php if ($_sidebarHasPhoto): ?>
                    <img
                        src="/images/new-image-save/<?= htmlspecialchars($_sidebarUser->profilepic) ?>"
                        class="sb-avatar-img"
                        alt="<?= htmlspecialchars($_sidebarUser->username) ?>"
                    >
                <?php else: ?>
                    <div class="sb-avatar-initials">
                        <?= strtoupper(substr($_sidebarUser->username, 0, 1)) ?>
                    </div>
                <?php endif; ?>

                <!-- Online dot -->
                <span class="sb-online-dot"></span>
            </div>

            <!-- Name + role -->
            <div class="sb-profile-text">
                <div class="sb-profile-name"><?= htmlspecialchars($_sidebarUser->username) ?></div>
                <div class="sb-profile-role"><?= htmlspecialchars($_sidebarUser->role) ?></div>
            </div>
        </div>

        <div class="sb-profile-divider"></div>
        <?php endif; ?>

        <a href="/security/logout.php" class="logout-bttn">Logout</a>

    </div>
</aside>

<style>
/* ── Sidebar Profile Card ─────────────────────────── */
.sb-profile-card {
    display: flex;
    align-items: center;
    gap: 11px;
}

.sb-avatar-wrap {
    position: relative;
    flex-shrink: 0;
}

.sb-avatar-img {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #3a3a3a;
    display: block;
}

.sb-avatar-initials {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2a2a2a, #3d3d3d);
    border: 2px solid #3a3a3a;
    color: #e0e0e0;
    font-size: 17px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Montserrat', sans-serif;
}

.sb-online-dot {
    position: absolute;
    bottom: 1px;
    right: 1px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #4caf50;
    border: 2px solid #1a1a1a;
}

.sb-profile-text {
    overflow: hidden;
}

.sb-profile-name {
    color: #f0f0f0;
    font-size: 13px;
    font-weight: 600;
    font-family: 'Montserrat', sans-serif;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 170px;
}

.sb-profile-role {
    color: #777;
    font-size: 11px;
    margin-top: 2px;
    font-family: 'Roboto', sans-serif;
}

.sb-profile-divider {
    height: 1px;
    background: #2f2f2f;
    margin: 4px 0;
}
</style>