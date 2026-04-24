<?php
require __DIR__ . '/data.php';
require __DIR__ . '/includes/functions.php';

// DB setup
$use_db = false;
$con    = null;
if (file_exists(__DIR__ . '/server/connection.php')) {
    @include_once __DIR__ . '/server/connection.php';
    @include_once __DIR__ . '/server/products.php';
    if (isset($con) && $con) {
        $use_db = true;
    }
}

// Category filter
$activeCategory = isset($_GET['category']) ? strtolower(trim($_GET['category'])) : 'all';
$categories = ['all', 'shoes', 'coats', 'dresses', 'shirts', 'bags'];

// Add to cart from index
if (isset($_GET['add'])) {
    $addId = (int) $_GET['add'];

    $valid = false;
    if ($use_db) {
        $check = getProductFromDB($con, $addId);
        $valid = ($check !== null);
    } else {
        $valid = (getProductById($products, $addId) !== null);
    }

    if ($valid) {
        addToCart($addId, 1);
    }

    $back = 'index.php?added=1';
    if ($activeCategory !== 'all') $back .= '&category=' . urlencode($activeCategory);
    header('Location: ' . $back . '#products');
    exit;
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
            <div class="container notice success">Product added to cart. <a href="cart.php">View Cart</a></div>
        </div>
    <?php endif; ?>

    <main>
        <!-- Hero -->
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
                    <img src="<?= htmlspecialchars($hero['image']) ?>"
                         alt="<?= htmlspecialchars($hero['image_alt']) ?>"
                         class="hero-image">
                </div>
            </div>
        </section>

        <!-- Products Section -->
        <section class="products-section section-space" id="products">
            <div class="container">
                <div class="section-header left-align">
                    <span>Our Collection</span>
                    <h2>Shop Products</h2>
                </div>

                <!-- Category Filter Tabs -->
                <div class="category-tabs">
                    <?php foreach ($categories as $cat): ?>
                        <a href="index.php?category=<?= urlencode($cat) ?>#products"
                           class="category-tab <?= $activeCategory === $cat ? 'active' : '' ?>">
                            <?= ucfirst($cat) ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- Product Grid -->
                <div class="product-grid">
                    <?php if ($use_db): ?>
                        <?php
                        $result = getProductsFromDB($con, $activeCategory);
                        $count = 0;
                        while ($row = $result->fetch_assoc()):
                            $p = dbRowToProduct($row);
                            $count++;
                        ?>
                            <article class="product-card">
                                <a class="product-thumb-link" href="singleproduct.php?id=<?= (int)$p['id'] ?>">
                                    <div class="product-image-wrap">
                                        <img src="<?= htmlspecialchars($p['image']) ?>"
                                             alt="<?= htmlspecialchars($p['name']) ?>"
                                             class="product-image" loading="lazy">
                                    </div>
                                </a>
                                <div class="product-meta-top">
                                    <span class="product-category"><?= htmlspecialchars(ucfirst($p['category'])) ?></span>
                                    <?php if ($p['special_offer'] > 0): ?>
                                        <span class="product-tag"><?= (int)$p['special_offer'] ?>% OFF</span>
                                    <?php endif; ?>
                                </div>
                                <a class="product-title-link" href="singleproduct.php?id=<?= (int)$p['id'] ?>">
                                    <h3 class="product-name"><?= htmlspecialchars($p['name']) ?></h3>
                                </a>
                                <?= renderStars(5) ?>
                                <p class="product-price"><?= money((float)$p['price']) ?></p>
                                <div class="product-actions">
                                    <a class="details-btn" href="singleproduct.php?id=<?= (int)$p['id'] ?>">View Details</a>
                                    <a class="buy-btn" href="index.php?add=<?= (int)$p['id'] ?>&category=<?= urlencode($activeCategory) ?>#products">Add To Cart</a>
                                </div>
                            </article>
                        <?php endwhile; ?>

                        <?php if ($count === 0): ?>
                            <p class="no-products">No products found in this category.</p>
                        <?php endif; ?>

                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <?= renderProductCard($product) ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
