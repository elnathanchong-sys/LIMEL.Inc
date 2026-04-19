<?php
include '../base.php';

// ── Only Members can see this page ──
if (!isset($_SESSION['users']) || $_SESSION['users']->role !== 'Member') {
    redirect('/security/login.php');
}

$users     = $_SESSION['users'];
$pageTitle = 'My Account';

// Check if user has a profile photo saved
$hasPhoto = !empty($users->profilepic)
    && file_exists(__DIR__ . '/../images/new-image-save/' . $users->profilepic);

// Get this user's wishlist items (joined with product info)
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
/* ── Page wrapper ── */
.myacc-page {
    max-width: 860px;
    margin: 50px auto 80px;
    padding: 0 24px;
    font-family: 'Roboto', sans-serif;
}

/* ── Profile Hero card at the top ── */
.myacc-hero {
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    border-radius: 16px;
    padding: 36px 32px;
    display: flex;
    align-items: center;
    gap: 28px;
    margin-bottom: 28px;
}

.myacc-avatar-wrap { position: relative; flex-shrink: 0; }

/* Profile photo (circle) */
.myacc-avatar-img {
    width: 90px; height: 90px;
    border-radius: 50%; object-fit: cover;
    border: 3px solid #333;
    display: block;
}

/* Initials circle shown when no photo */
.myacc-avatar-initials {
    width: 90px; height: 90px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2d2d2d, #444);
    border: 3px solid #333;
    color: #e0e0e0;
    font-size: 36px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Montserrat', sans-serif;
}

/* Small edit pencil button on the avatar */
.myacc-edit-btn {
    position: absolute; bottom: 2px; right: 2px;
    width: 26px; height: 26px;
    background: #333; border: 2px solid #1a1a1a;
    border-radius: 50%; display: flex;
    align-items: center; justify-content: center;
    text-decoration: none; transition: background 0.2s;
}
.myacc-edit-btn:hover { background: #555; }

.myacc-hero-info { flex: 1; min-width: 0; }

.myacc-hero-name {
    color: #f2f2f2; font-size: 24px; font-weight: 700;
    font-family: 'Montserrat', sans-serif;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    margin-bottom: 5px;
}
.myacc-hero-email { color: #666; font-size: 13px; margin-bottom: 12px; }

/* Role badge (e.g. "Member") */
.myacc-role-badge {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(255,255,255,0.05);
    border: 1px solid #2f2f2f;
    color: #999; font-size: 11px; font-weight: 600;
    text-transform: uppercase; letter-spacing: 0.5px;
    padding: 4px 10px; border-radius: 20px;
}

.myacc-hero-actions { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 18px; }

/* Small action buttons */
.myacc-action-primary {
    background: #2a2a2a; color: #e0e0e0;
    border: 1px solid #3a3a3a;
    padding: 9px 18px; border-radius: 8px;
    font-size: 13px; font-weight: 500;
    text-decoration: none; display: inline-flex;
    align-items: center; gap: 7px;
    transition: background 0.2s, border-color 0.2s;
}
.myacc-action-primary:hover { background: #333; border-color: #555; color: #fff; }

/* ── Quick links grid ── */
.myacc-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 16px;
    margin-bottom: 28px;
}
.myacc-card {
    background: #1a1a1a; border: 1px solid #2a2a2a;
    border-radius: 14px; padding: 24px;
    text-decoration: none;
    display: flex; align-items: center; gap: 18px;
    transition: border-color 0.2s, transform 0.2s, box-shadow 0.2s;
}
.myacc-card:hover { border-color: #444; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.4); }
.myacc-card-icon {
    width: 46px; height: 46px;
    background: #242424; border: 1px solid #2f2f2f;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; color: #aaa;
}
.myacc-card-title { color: #e0e0e0; font-size: 15px; font-weight: 600; font-family: 'Montserrat', sans-serif; margin-bottom: 3px; }
.myacc-card-desc  { color: #555; font-size: 12px; line-height: 1.4; }

/* ── Logout section ── */
.myacc-danger {
    background: #1a1a1a; border: 1px solid #2a2a2a;
    border-radius: 14px; padding: 24px 28px;
    display: flex; align-items: center;
    justify-content: space-between; gap: 20px; flex-wrap: wrap;
}
.myacc-danger-text        { color: #555; font-size: 13px; }
.myacc-danger-text strong { color: #888; display: block; margin-bottom: 3px; font-size: 14px; }
.myacc-logout-btn {
    background: transparent; border: 1px solid #3a2a2a;
    color: #cc5555; padding: 9px 20px;
    border-radius: 8px; font-size: 13px; font-weight: 500;
    text-decoration: none; display: inline-flex; align-items: center; gap: 7px;
    transition: background 0.2s, border-color 0.2s; white-space: nowrap;
}
.myacc-logout-btn:hover { background: rgba(200,60,60,0.1); border-color: #cc5555; color: #ff6b6b; }

/* ── Wishlist section ── */
.myacc-section {
    background: #1a1a1a; border: 1px solid #2a2a2a;
    border-radius: 14px; padding: 24px 28px; margin-bottom: 28px;
}
.myacc-section-title {
    color: #f2f2f2; font-size: 17px; font-weight: 600;
    font-family: 'Montserrat', sans-serif; margin-bottom: 18px;
    display: flex; align-items: center; gap: 9px;
}
.wishlist-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 14px;
}
.wishlist-item {
    background: #242424; border: 1px solid #2f2f2f; border-radius: 10px; padding: 12px;
    text-decoration: none; display: flex; flex-direction: column; align-items: center; gap: 8px;
    transition: border-color 0.2s, transform 0.2s;
}
.wishlist-item:hover  { border-color: #555; transform: translateY(-2px); }
.wishlist-item img    { width: 100%; height: 100px; object-fit: cover; border-radius: 7px; }
.wishlist-item-name   { color: #e0e0e0; font-size: 12px; font-weight: 500; text-align: center; line-height: 1.3; }
.wishlist-item-price  { color: #888; font-size: 12px; }
.wishlist-empty       { color: #555; font-size: 13px; text-align: center; padding: 20px 0; }
</style>

<main class="myacc-page">

    <!-- ── Profile Hero ── -->
    <div class="myacc-hero">

        <!-- Avatar: photo or initials circle -->
        <div class="myacc-avatar-wrap">
            <?php if ($hasPhoto): ?>
                <img src="/images/new-image-save/<?= htmlspecialchars($users->profilepic) ?>"
                     class="myacc-avatar-img" alt="Profile photo">
            <?php else: ?>
                <div class="myacc-avatar-initials">
                    <?= strtoupper(substr($users->username, 0, 1)) ?>
                </div>
            <?php endif; ?>

            <!-- Pencil button to edit profile -->
            <a href="updateProfile.php" class="myacc-edit-btn" title="Edit profile">
                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24"
                     fill="none" stroke="#ccc" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
            </a>
        </div>

        <!-- Name, email, role badge, and buttons -->
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

    <!-- ── Quick Links (3 cards) ── -->
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

    <!-- ── Wishlist ── -->
    <div class="myacc-section">
        <div class="myacc-section-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                 fill="#e74c3c" stroke="#e74c3c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
            My Wishlist
            <span style="color:#555; font-size:13px; font-weight:400;">(<?= count($wishlistItems) ?> items)</span>
        </div>

        <?php if (empty($wishlistItems)): ?>
            <div class="wishlist-empty">
                You haven't wishlisted anything yet.<br>
                <a href="../page/keyboards.php" style="color:#667eea;">Browse products →</a>
            </div>
        <?php else: ?>
            <div class="wishlist-grid">
                <?php foreach ($wishlistItems as $wItem): ?>
                <a href="/product-page/product.php?productID=<?= urlencode($wItem->productID) ?>"
                   class="wishlist-item">
                    <img src="/images/<?= htmlspecialchars($wItem->imageFile) ?>"
                         alt="<?= htmlspecialchars($wItem->productName) ?>">
                    <div class="wishlist-item-name"><?= htmlspecialchars($wItem->productName) ?></div>
                    <div class="wishlist-item-price">RM <?= number_format($wItem->productPrice, 2) ?></div>
                </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- ── Logout section ── -->
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
