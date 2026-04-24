<?php
require __DIR__ . '/data.php';
require __DIR__ . '/includes/functions.php';

if (isset($_GET['add'])) {
    $productId = (int) $_GET['add'];
    $product = getProductById($products, $productId);

    if ($product !== null) {
        addToCart($productId, 1);
    }

    header('Location: index.php?added=1#products');
    exit;
}

// Try to load featured products from the database (video 59: Get products)
// Falls back to static data if DB is not available
$use_db = false;
$db_featured_products = null;
$db_coats_products = null;

if (file_exists(__DIR__ . '/server/connection.php')) {
    @include_once __DIR__ . '/server/getFeaturedProducts.php';
    @include_once __DIR__ . '/server/get_quotes.php';
    if (isset($featured_products) && $featured_products !== false) {
        $use_db = true;
        $db_featured_products = $featured_products;
        $db_coats_products = $coats_products ?? null;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($site['brand']) ?> | PHP E-Commerce</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php require __DIR__ . '/includes/header.php'; ?>

    <?php if (isset($_GET['added'])): ?>
        <div class="notice-wrap">
            <div class="container notice success">Product added to cart successfully.</div>
        </div>
    <?php endif; ?>

    <main>
        <section class="hero section-space">
            <div class="container hero-grid">
                <div class="hero-content">
                    <span class="eyebrow"><?= htmlspecialchars($hero['eyebrow']) ?></span>
                    <h1><?= htmlspecialchars($hero['title']) ?></h1>
                    <p><?= htmlspecialchars($hero['subtitle']) ?></p>
                    <a href="<?= htmlspecialchars($hero['button_link']) ?>" class="primary-btn">
                        <?= htmlspecialchars($hero['button_text']) ?>
                    </a>
                </div>

                <div class="hero-image-wrap">
                    <img src="<?= htmlspecialchars($hero['image']) ?>" alt="<?= htmlspecialchars($hero['image_alt']) ?>" class="hero-image">
                </div>
            </div>
        </section>

        <!-- Featured Products Section -->
        <!-- Video 59: Get products from DB using while($row = $result->fetch_assoc()) loop -->
        <section class="products-section section-space" id="products">
            <div class="container">
                <div class="section-header left-align">
                    <span>Our Products</span>
                    <h2>Featured Products</h2>
                    <p>
                        <?php if ($use_db): ?>
                            Products loaded from the database (php_project).
                        <?php else: ?>
                            Click any product to open the single product page. Connect to a MySQL database to load products dynamically.
                        <?php endif; ?>
                    </p>
                </div>

                <div class="product-grid">
                    <?php if ($use_db && $db_featured_products): ?>
                        <?php while ($row = $db_featured_products->fetch_assoc()): ?>
                            <article class="product-card">
                                <div class="product-image-wrap">
                                    <img src="<?= htmlspecialchars($row['product_image']) ?>"
                                         alt="<?= htmlspecialchars($row['product_name']) ?>"
                                         class="product-image">
                                </div>
                                <div class="product-meta-top">
                                    <span class="product-category"><?= htmlspecialchars($row['product_category']) ?></span>
                                    <?php if (!empty($row['product_special_offer'])): ?>
                                        <span class="product-tag"><?= (int)$row['product_special_offer'] ?>% OFF</span>
                                    <?php endif; ?>
                                </div>
                                <a class="product-title-link" href="singleproduct.php?id=<?= (int)$row['product_id'] ?>">
                                    <h3 class="product-name"><?= htmlspecialchars($row['product_name']) ?></h3>
                                </a>
                                <p class="product-price">$<?= number_format((float)$row['product_price'], 0) ?></p>
                                <div class="product-actions">
                                    <a class="details-btn" href="singleproduct.php?id=<?= (int)$row['product_id'] ?>">View Details</a>
                                    <a class="buy-btn" href="index.php?add=<?= (int)$row['product_id'] ?>#products">Add To Cart</a>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <?= renderProductCard($product) ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Coats / Category Section -->
        <!-- Video 60: Get coats from DB WHERE product_category = 'coats' -->
        <?php if ($use_db && $db_coats_products): ?>
        <section class="products-section section-space" id="coats">
            <div class="container">
                <div class="section-header left-align">
                    <span>Collection</span>
                    <h2>Coats</h2>
                    <p>Coats loaded from the database where product_category = 'coats'.</p>
                </div>

                <div class="product-grid">
                    <?php while ($row = $db_coats_products->fetch_assoc()): ?>
                        <article class="product-card">
                            <div class="product-image-wrap">
                                <img src="<?= htmlspecialchars($row['product_image']) ?>"
                                     alt="<?= htmlspecialchars($row['product_name']) ?>"
                                     class="product-image">
                            </div>
                            <div class="product-meta-top">
                                <span class="product-category"><?= htmlspecialchars($row['product_category']) ?></span>
                                <?php if (!empty($row['product_special_offer'])): ?>
                                    <span class="product-tag"><?= (int)$row['product_special_offer'] ?>% OFF</span>
                                <?php endif; ?>
                            </div>
                            <a class="product-title-link" href="singleproduct.php?id=<?= (int)$row['product_id'] ?>">
                                <h3 class="product-name"><?= htmlspecialchars($row['product_name']) ?></h3>
                            </a>
                            <p class="product-price">$<?= number_format((float)$row['product_price'], 0) ?></p>
                            <div class="product-actions">
                                <a class="details-btn" href="singleproduct.php?id=<?= (int)$row['product_id'] ?>">View Details</a>
                                <a class="buy-btn" href="index.php?add=<?= (int)$row['product_id'] ?>#coats">Add To Cart</a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>
    </main>

    <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
