<?php
require __DIR__ . '/data.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/server/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_item'])) {
        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantity  = (int) ($_POST['quantity'] ?? 1);
        updateCartItem($productId, $quantity);
        header('Location: cart.php?updated=1');
        exit;
    }

    if (isset($_POST['checkout'])) {
        header('Location: checkout.php');
        exit;
    }
}

if (isset($_GET['remove'])) {
    removeFromCart((int) $_GET['remove']);
    header('Location: cart.php?removed=1');
    exit;
}

$cartItems = getCartItemsDB($con);
$subtotal  = getCartSubtotalDB($con);
$total     = $subtotal;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart | <?= htmlspecialchars($site['brand']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php require __DIR__ . '/includes/header.php'; ?>

    <main class="cart-page section-space">
        <div class="container">

            <?php if (isset($_GET['updated'])): ?>
                <div class="notice success single-notice">Cart updated.</div>
            <?php endif; ?>
            <?php if (isset($_GET['removed'])): ?>
                <div class="notice success single-notice">Item removed from cart.</div>
            <?php endif; ?>

            <section class="cart-section">
                <?php if (empty($cartItems)): ?>
                    <div class="empty-cart-box">
                        <i class="fa-solid fa-bag-shopping empty-cart-icon"></i>
                        <h1>Your Cart is Empty</h1>
                        <p>Looks like you haven't added anything yet.</p>
                        <a href="index.php#products" class="primary-btn">Continue Shopping</a>
                    </div>
                <?php else: ?>
                    <div class="cart-grid">
                        <div class="cart-table-wrap">
                            <h2 class="cart-title">Shopping Cart <span class="cart-count-label">(<?= array_sum($_SESSION['cart'] ?? []) ?> items)</span></h2>
                            <table class="cart-table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cartItems as $item): ?>
                                        <?php $product = $item['product']; ?>
                                        <tr>
                                            <td>
                                                <div class="cart-product-cell">
                                                    <a href="singleproduct.php?id=<?= (int)$product['id'] ?>">
                                                        <img src="<?= htmlspecialchars($product['image']) ?>"
                                                             alt="<?= htmlspecialchars($product['name']) ?>">
                                                    </a>
                                                    <div class="cart-product-info">
                                                        <a href="singleproduct.php?id=<?= (int)$product['id'] ?>" class="cart-product-name">
                                                            <?= htmlspecialchars($product['name']) ?>
                                                        </a>
                                                        <p class="cart-product-price"><?= money((float)$product['price']) ?> each</p>
                                                        <a href="cart.php?remove=<?= (int)$product['id'] ?>" class="remove-link">
                                                            <i class="fa-solid fa-trash"></i> Remove
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <form action="cart.php" method="post" class="quantity-form">
                                                    <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                                                    <div class="qty-stepper">
                                                        <input type="number" min="1" max="10"
                                                               name="quantity"
                                                               value="<?= (int)$item['quantity'] ?>"
                                                               class="cart-qty-input">
                                                        <button type="submit" name="update_item" class="qty-update-btn">Update</button>
                                                    </div>
                                                </form>
                                            </td>
                                            <td class="cart-subtotal-cell"><?= money((float)$item['subtotal']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                            <div class="cart-continue">
                                <a href="index.php#products" class="back-link">
                                    <i class="fa-solid fa-arrow-left"></i> Continue Shopping
                                </a>
                            </div>
                        </div>

                        <!-- Cart Summary -->
                        <div class="cart-summary">
                            <h3 class="cart-summary-title">Order Summary</h3>
                            <div class="cart-summary-row">
                                <span>Subtotal</span>
                                <span><?= money($subtotal) ?></span>
                            </div>
                            <div class="cart-summary-row">
                                <span>Shipping</span>
                                <span class="text-green">Free</span>
                            </div>
                            <div class="cart-summary-row cart-summary-total">
                                <span>Total</span>
                                <span><?= money($total) ?></span>
                            </div>

                            <form action="cart.php" method="post">
                                <button type="submit" name="checkout" class="primary-btn checkout-btn full-btn">
                                    Proceed to Checkout
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </main>

    <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
