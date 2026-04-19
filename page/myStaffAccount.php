<?php
include '../base.php';

// ── Only Admins can see this page ──
if (!isset($_SESSION['users']) || $_SESSION['users']->role !== 'Admin') {
    redirect('/security/login.php');
}

$users     = $_SESSION['users'];
$pageTitle = 'My Staff Account';

// Check if admin has a profile photo saved
$hasPhoto = !empty($users->profilepic)
    && file_exists(__DIR__ . '/../images/new-image-save/' . $users->profilepic);

// Get total number of orders (useful info for staff)
$totalOrders = $_db->query("SELECT COUNT(*) FROM orders")->fetchColumn();

// Get total number of products
$totalProducts = $_db->query("SELECT COUNT(*) FROM products")->fetchColumn();

// Get total number of members
$totalMembers = $_db->query("SELECT COUNT(*) FROM users WHERE role = 'Member'")->fetchColumn();

include '../header.php';
?>

<style>
/* ── Same style as myaccount.php ── */
.myacc-page {
    max-width: 860px;
    margin: 50px auto 80px;
    padding: 0 24px;
    font-family: 'Roboto', sans-serif;
}

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
.myacc-avatar-img {
    width: 90px; height: 90px;
    border-radius: 50%; object-fit: cover;
    border: 3px solid #333; display: block;
}
.myacc-avatar-initials {
    width: 90px; height: 90px; border-radius: 50%;
    background: linear-gradient(135deg, #1a3a4a, #2a5a6a);
    border: 3px solid #2a5a6a;
    color: #e0e0e0; font-size: 36px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Montserrat', sans-serif;
}
.myacc-hero-info { flex: 1; min-width: 0; }
.myacc-hero-name {
    color: #f2f2f2; font-size: 24px; font-weight: 700;
    font-family: 'Montserrat', sans-serif;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    margin-bottom: 5px;
}
.myacc-hero-email { color: #666; font-size: 13px; margin-bottom: 12px; }

/* Admin badge — blue/teal instead of grey */
.myacc-role-badge {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(42,90,106,0.2);
    border: 1px solid #2a5a6a;
    color: #4db8cc; font-size: 11px; font-weight: 600;
    text-transform: uppercase; letter-spacing: 0.5px;
    padding: 4px 10px; border-radius: 20px;
}
.myacc-hero-actions { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 18px; }
.myacc-action-primary {
    background: #2a2a2a; color: #e0e0e0;
    border: 1px solid #3a3a3a;
    padding: 9px 18px; border-radius: 8px;
    font-size: 13px; font-weight: 500;
    text-decoration: none; display: inline-flex; align-items: center; gap: 7px;
    transition: background 0.2s, border-color 0.2s;
}
.myacc-action-primary:hover { background: #333; border-color: #555; color: #fff; }

/* ── Stats row (Orders / Products / Members counts) ── */
.myacc-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-bottom: 28px;
}
.myacc-stat-card {
    background: #1a1a1a; border: 1px solid #2a2a2a;
    border-radius: 14px; padding: 20px 16px;
    text-align: center;
}
.myacc-stat-num  { color: #4db8cc; font-size: 28px; font-weight: 700; font-family: 'Montserrat', sans-serif; }
.myacc-stat-lbl  { color: #555; font-size: 12px; margin-top: 4px; }

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
    text-decoration: none; display: flex; align-items: center; gap: 18px;
    transition: border-color 0.2s, transform 0.2s, box-shadow 0.2s;
}
.myacc-card:hover { border-color: #444; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.4); }
.myacc-card-icon {
    width: 46px; height: 46px;
    background: #242424; border: 1px solid #2f2f2f;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; color: #4db8cc;
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
</style>

<main class="myacc-page">

    <!-- ── Profile Hero ── -->
    <div class="myacc-hero">

        <!-- Avatar: photo or initials -->
        <div class="myacc-avatar-wrap">
            <?php if ($hasPhoto): ?>
                <img src="/images/new-image-save/<?= htmlspecialchars($users->profilepic) ?>"
                     class="myacc-avatar-img" alt="Profile photo">
            <?php else: ?>
                <div class="myacc-avatar-initials">
                    <?= strtoupper(substr($users->username, 0, 1)) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Name, email, role, action buttons -->
        <div class="myacc-hero-info">
            <div class="myacc-hero-name"><?= htmlspecialchars($users->username) ?></div>
            <div class="myacc-hero-email"><?= htmlspecialchars($users->email) ?></div>

            <span class="myacc-role-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                Admin / Staff
            </span>

            <div class="myacc-hero-actions">
                <!-- Go to admin dashboard -->
                <a href="/Administration/dashboard.php" class="myacc-action-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                    </svg>
                    Dashboard
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

    <!-- ── Store stats (quick overview) ── -->
    <div class="myacc-stats">
        <div class="myacc-stat-card">
            <div class="myacc-stat-num"><?= $totalOrders ?></div>
            <div class="myacc-stat-lbl">Total Orders</div>
        </div>
        <div class="myacc-stat-card">
            <div class="myacc-stat-num"><?= $totalProducts ?></div>
            <div class="myacc-stat-lbl">Products</div>
        </div>
        <div class="myacc-stat-card">
            <div class="myacc-stat-num"><?= $totalMembers ?></div>
            <div class="myacc-stat-lbl">Members</div>
        </div>
    </div>

    <!-- ── Admin Quick Links ── -->
    <div class="myacc-grid">

        <a href="/Administration/dashboard.php" class="myacc-card">
            <div class="myacc-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                    <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                </svg>
            </div>
            <div>
                <div class="myacc-card-title">Dashboard</div>
                <div class="myacc-card-desc">View sales charts and store overview</div>
            </div>
        </a>

        <a href="/Administration/product-listing/inventory.php" class="myacc-card">
            <div class="myacc-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                </svg>
            </div>
            <div>
                <div class="myacc-card-title">Inventory</div>
                <div class="myacc-card-desc">Add, edit, or remove products</div>
            </div>
        </a>

        <a href="/Administration/order.php" class="myacc-card">
            <div class="myacc-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>
                </svg>
            </div>
            <div>
                <div class="myacc-card-title">Orders</div>
                <div class="myacc-card-desc">View and update customer orders</div>
            </div>
        </a>

        <a href="/Administration/member.php" class="myacc-card">
            <div class="myacc-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <div>
                <div class="myacc-card-title">Members</div>
                <div class="myacc-card-desc">View all registered members</div>
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
                <div class="myacc-card-desc">Update your admin password</div>
            </div>
        </a>

    </div>

    <!-- ── Logout section ── -->
    <div class="myacc-danger">
        <div class="myacc-danger-text">
            <strong>Sign out</strong>
            You will be logged out and returned to the homepage.
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
