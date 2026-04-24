<?php
$cartCount = getCartCount();
$_isLoggedIn = isLoggedIn();
$_userName = $_isLoggedIn ? htmlspecialchars($_SESSION['user_name'] ?? 'Account') : '';
?>
<header class="site-header" id="home">
    <div class="container navbar">
        <a href="index.php#home" class="brand"><?= htmlspecialchars($site['brand']) ?></a>

        <nav class="nav-links">
            <?php foreach ($navLinks as $link): ?>
                <a href="<?= htmlspecialchars($link['href']) ?>"><?= htmlspecialchars($link['label']) ?></a>
            <?php endforeach; ?>
        </nav>

        <div class="nav-icons">
            <a href="index.php#products" aria-label="Shop"><i class="fa-solid fa-magnifying-glass"></i></a>

            <?php if ($_isLoggedIn): ?>
                <a href="account.php" aria-label="My Account" class="nav-user-link">
                    <i class="fa-regular fa-user"></i>
                    <span class="nav-username"><?= $_userName ?></span>
                </a>
            <?php else: ?>
                <a href="login.php" aria-label="Login"><i class="fa-regular fa-user"></i></a>
            <?php endif; ?>

            <a href="cart.php" aria-label="Shopping bag" class="cart-link">
                <i class="fa-solid fa-bag-shopping"></i>
                <?php if ($cartCount > 0): ?>
                    <span class="cart-count"><?= $cartCount ?></span>
                <?php endif; ?>
            </a>
        </div>
    </div>
</header>
