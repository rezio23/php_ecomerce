<?php
require __DIR__ . '/data.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/server/connection.php';
require __DIR__ . '/server/orders.php';

// If cart empty and no success, go to cart
$cartItems = getCartItemsDB($con);
$subtotal  = getCartSubtotalDB($con);
$total     = $subtotal;

$errors  = [];
$success = false;
$orderId = null;

// Pre-fill from session if logged in
$prefill = [
    'name'    => '',
    'email'   => '',
    'phone'   => '',
    'city'    => '',
    'address' => '',
];

if (isLoggedIn()) {
    require __DIR__ . '/server/auth.php';
    $user = getCurrentUser($con);
    $prefill['name']  = $user['user_name'] ?? '';
    $prefill['email'] = $user['user_email'] ?? '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $phone   = trim($_POST['phone']   ?? '');
    $city    = trim($_POST['city']    ?? '');
    $address = trim($_POST['address'] ?? '');

    $prefill = compact('name', 'email', 'phone', 'city', 'address');

    if ($name    === '') $errors[] = 'Name is required.';
    if ($email   === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
    if ($phone   === '') $errors[] = 'Phone is required.';
    if ($city    === '') $errors[] = 'City is required.';
    if ($address === '') $errors[] = 'Address is required.';

    if (empty($cartItems)) {
        $errors[] = 'Your cart is empty.';
    }

    if (empty($errors)) {
        $userId  = isLoggedIn() ? (int) $_SESSION['user_id'] : 0;
        $orderId = createOrder($con, $cartItems, $total, compact('phone', 'city', 'address'), $userId);

        if ($orderId) {
            clearCart();
            $success = true;
            // Reload cart items (now empty)
            $cartItems = [];
            $subtotal  = 0;
            $total     = 0;
        } else {
            $errors[] = 'Failed to place order. Please try again.';
        }
    }
}

if (!$success && empty($cartItems) && !isset($_POST['checkout'])) {
    header('Location: cart.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | <?= htmlspecialchars($site['brand']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php require __DIR__ . '/includes/header.php'; ?>

    <main>
        <section class="checkout-section section-space">
            <div class="container">

                <?php if ($success): ?>
                    <!-- Success Screen -->
                    <div class="checkout-success">
                        <div class="success-icon"><i class="fa-solid fa-circle-check"></i></div>
                        <h2>Order Placed!</h2>
                        <p>Thank you for your order. Your order <strong>#<?= (int) $orderId ?></strong> has been received and is being processed.</p>
                        <?php if (isLoggedIn()): ?>
                            <a href="account.php?section=orders" class="primary-btn">View My Orders</a>
                        <?php else: ?>
                            <a href="index.php" class="primary-btn">Continue Shopping</a>
                        <?php endif; ?>
                    </div>

                <?php else: ?>
                    <div class="checkout-layout">

                        <!-- Form -->
                        <div class="checkout-form-col">
                            <h2 class="checkout-title">Shipping Details</h2>

                            <?php if (!empty($errors)): ?>
                                <div class="notice error">
                                    <?php foreach ($errors as $e): ?>
                                        <p><?= htmlspecialchars($e) ?></p>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <form id="checkout-form" action="checkout.php" method="post">
                                <div class="checkout-row">
                                    <div class="checkout-small-element">
                                        <label for="checkout-name">Full Name</label>
                                        <input type="text" class="field-control" id="checkout-name" name="name"
                                               placeholder="Your name"
                                               value="<?= htmlspecialchars($prefill['name']) ?>" required>
                                    </div>
                                    <div class="checkout-small-element">
                                        <label for="checkout-email">Email</label>
                                        <input type="email" class="field-control" id="checkout-email" name="email"
                                               placeholder="you@example.com"
                                               value="<?= htmlspecialchars($prefill['email']) ?>" required>
                                    </div>
                                </div>

                                <div class="checkout-row">
                                    <div class="checkout-small-element">
                                        <label for="checkout-phone">Phone</label>
                                        <input type="tel" class="field-control" id="checkout-phone" name="phone"
                                               placeholder="+855 12 345 678"
                                               value="<?= htmlspecialchars($prefill['phone']) ?>" required>
                                    </div>
                                    <div class="checkout-small-element">
                                        <label for="checkout-city">City</label>
                                        <input type="text" class="field-control" id="checkout-city" name="city"
                                               placeholder="Phnom Penh"
                                               value="<?= htmlspecialchars($prefill['city']) ?>" required>
                                    </div>
                                </div>

                                <div class="form-group checkout-large-element">
                                    <label for="checkout-address">Address</label>
                                    <input type="text" class="field-control" id="checkout-address" name="address"
                                           placeholder="Street address"
                                           value="<?= htmlspecialchars($prefill['address']) ?>" required>
                                </div>

                                <div class="checkout-btn-container">
                                    <button type="submit" name="checkout" class="primary-btn checkout-btn">
                                        <i class="fa-solid fa-lock"></i> Place Order
                                    </button>
                                </div>

                                <?php if (!isLoggedIn()): ?>
                                    <p class="text-center" style="margin-top:12px;">
                                        <a href="login.php?redirect=checkout.php" class="auth-link">Have an account? Sign in to save your details</a>
                                    </p>
                                <?php endif; ?>
                            </form>
                        </div>

                        <!-- Order Summary -->
                        <div class="checkout-summary-col">
                            <h3 class="checkout-summary-title">Order Summary</h3>
                            <div class="checkout-summary-items">
                                <?php foreach ($cartItems as $item): ?>
                                    <div class="checkout-summary-item">
                                        <img src="<?= htmlspecialchars($item['product']['image']) ?>"
                                             alt="<?= htmlspecialchars($item['product']['name']) ?>">
                                        <div class="checkout-item-info">
                                            <p class="checkout-item-name"><?= htmlspecialchars($item['product']['name']) ?></p>
                                            <p class="checkout-item-qty">Qty: <?= (int) $item['quantity'] ?></p>
                                        </div>
                                        <span class="checkout-item-price"><?= money($item['subtotal']) ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="checkout-summary-totals">
                                <div class="summary-row">
                                    <span>Subtotal</span>
                                    <span><?= money($subtotal) ?></span>
                                </div>
                                <div class="summary-row">
                                    <span>Shipping</span>
                                    <span class="text-green">Free</span>
                                </div>
                                <div class="summary-row summary-total">
                                    <span>Total</span>
                                    <span><?= money($total) ?></span>
                                </div>
                            </div>
                        </div>

                    </div><!-- /.checkout-layout -->
                <?php endif; ?>

            </div>
        </section>
    </main>

    <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
