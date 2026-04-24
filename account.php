<?php
require __DIR__ . '/data.php';
require __DIR__ . '/includes/functions.php';
requireLogin();

require __DIR__ . '/server/connection.php';
require __DIR__ . '/server/auth.php';
require __DIR__ . '/server/orders.php';

$currentUser = getCurrentUser($con);
$section = $_GET['section'] ?? 'info'; // info | orders | password

$successMsg = '';
$errorMsg   = '';

// Handle change-password POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current = $_POST['current_password'] ?? '';
    $newPass = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $result  = authChangePassword($con, (int) $_SESSION['user_id'], $current, $newPass, $confirm);
    if ($result['success']) {
        $successMsg = 'Password changed successfully.';
        $section = 'password';
    } else {
        $errorMsg = $result['error'];
        $section  = 'password';
    }
}

$orders = ($section === 'orders') ? getUserOrders($con, (int) $_SESSION['user_id']) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account | <?= htmlspecialchars($site['brand']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php require __DIR__ . '/includes/header.php'; ?>

    <main>
        <section class="account-section section-space">
            <div class="container">
                <div class="account-layout">

                    <!-- Sidebar -->
                    <aside class="account-sidebar">
                        <div class="account-avatar">
                            <div class="avatar-circle">
                                <?= strtoupper(substr($currentUser['user_name'] ?? 'U', 0, 1)) ?>
                            </div>
                            <p class="avatar-name"><?= htmlspecialchars($currentUser['user_name'] ?? '') ?></p>
                            <p class="avatar-email"><?= htmlspecialchars($currentUser['user_email'] ?? '') ?></p>
                        </div>
                        <nav class="account-nav">
                            <a href="account.php?section=info" class="account-nav-item <?= $section === 'info' ? 'active' : '' ?>">
                                <i class="fa-regular fa-user"></i> Account Info
                            </a>
                            <a href="account.php?section=orders" class="account-nav-item <?= $section === 'orders' ? 'active' : '' ?>">
                                <i class="fa-solid fa-box"></i> My Orders
                            </a>
                            <a href="account.php?section=password" class="account-nav-item <?= $section === 'password' ? 'active' : '' ?>">
                                <i class="fa-solid fa-lock"></i> Change Password
                            </a>
                            <a href="logout.php" class="account-nav-item account-nav-logout">
                                <i class="fa-solid fa-right-from-bracket"></i> Logout
                            </a>
                        </nav>
                    </aside>

                    <!-- Main Content -->
                    <div class="account-content">

                        <?php if ($successMsg): ?>
                            <div class="notice success"><?= htmlspecialchars($successMsg) ?></div>
                        <?php endif; ?>
                        <?php if ($errorMsg): ?>
                            <div class="notice error"><?= htmlspecialchars($errorMsg) ?></div>
                        <?php endif; ?>

                        <!-- Account Info -->
                        <?php if ($section === 'info'): ?>
                            <div class="account-panel">
                                <h2 class="account-panel-title">Account Information</h2>
                                <div class="account-info-grid">
                                    <div class="account-info-item">
                                        <span class="info-label">Full Name</span>
                                        <span class="info-value"><?= htmlspecialchars($currentUser['user_name'] ?? '') ?></span>
                                    </div>
                                    <div class="account-info-item">
                                        <span class="info-label">Email Address</span>
                                        <span class="info-value"><?= htmlspecialchars($currentUser['user_email'] ?? '') ?></span>
                                    </div>
                                    <div class="account-info-item">
                                        <span class="info-label">Member Since</span>
                                        <span class="info-value">2026</span>
                                    </div>
                                </div>
                                <div class="account-info-actions">
                                    <a href="account.php?section=orders" class="secondary-btn">View My Orders</a>
                                    <a href="index.php#products" class="primary-btn">Continue Shopping</a>
                                </div>
                            </div>

                        <!-- Orders -->
                        <?php elseif ($section === 'orders'): ?>
                            <div class="account-panel">
                                <h2 class="account-panel-title">My Orders</h2>

                                <?php if (empty($orders)): ?>
                                    <div class="empty-state">
                                        <i class="fa-solid fa-box-open"></i>
                                        <p>No orders yet.</p>
                                        <a href="index.php#products" class="primary-btn">Start Shopping</a>
                                    </div>
                                <?php else: ?>
                                    <div class="orders-table-wrap">
                                        <table class="orders-table">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Order #</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($orders as $order): ?>
                                                <tr>
                                                    <td>
                                                        <div class="order-product-cell">
                                                            <img src="<?= htmlspecialchars($order['product_image']) ?>"
                                                                 alt="<?= htmlspecialchars($order['product_name']) ?>">
                                                            <span><?= htmlspecialchars($order['product_name']) ?></span>
                                                        </div>
                                                    </td>
                                                    <td>#<?= (int) $order['order_id'] ?></td>
                                                    <td><?= statusBadge($order['order_status']) ?></td>
                                                    <td><?= date('M j, Y', strtotime($order['order_date'])) ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>

                        <!-- Change Password -->
                        <?php elseif ($section === 'password'): ?>
                            <div class="account-panel">
                                <h2 class="account-panel-title">Change Password</h2>
                                <form action="account.php?section=password" method="post" class="password-form">
                                    <div class="form-group">
                                        <label for="current_password">Current Password</label>
                                        <input type="password" class="field-control" id="current_password"
                                               name="current_password" placeholder="Current password" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="new_password">New Password</label>
                                        <input type="password" class="field-control" id="new_password"
                                               name="new_password" placeholder="Min. 6 characters" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="confirm_password">Confirm New Password</label>
                                        <input type="password" class="field-control" id="confirm_password"
                                               name="confirm_password" placeholder="Repeat new password" required>
                                    </div>
                                    <button type="submit" name="change_password" class="primary-btn">Update Password</button>
                                </form>
                            </div>
                        <?php endif; ?>

                    </div><!-- /.account-content -->
                </div><!-- /.account-layout -->
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
