<?php
require __DIR__ . '/data.php';
require __DIR__ . '/includes/functions.php';

$productId = isset($_GET['id']) ? (int) $_GET['id'] : 1;
$product   = null;
$use_db    = false;

// Try DB first
if (file_exists(__DIR__ . '/server/connection.php')) {
    @include_once __DIR__ . '/server/connection.php';
    @include_once __DIR__ . '/server/products.php';
    if (isset($con) && $con) {
        $row = getProductFromDB($con, $productId);
        if ($row) {
            $product = dbRowToProduct($row);
            $use_db  = true;
        }
    }
}

// Fallback to static data
if (!$product) {
    $product = getProductById($products, $productId);
}

if ($product === null) {
    http_response_code(404);
}

// Handle add-to-cart POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $postedId = (int) ($_POST['product_id'] ?? 0);
    $quantity  = max(1, (int) ($_POST['quantity'] ?? 1));

    // Verify product exists (DB or static)
    $valid = false;
    if ($use_db && isset($con)) {
        $check = getProductFromDB($con, $postedId);
        $valid = ($check !== null);
    } else {
        $valid = (getProductById($products, $postedId) !== null);
    }

    if ($valid) {
        addToCart($postedId, $quantity);
        header('Location: singleproduct.php?id=' . $postedId . '&added=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $product ? htmlspecialchars($product['name']) : 'Product not found' ?> | <?= htmlspecialchars($site['brand']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php require __DIR__ . '/includes/header.php'; ?>

    <main class="single-page section-space">
        <div class="container">
            <?php if (!$product): ?>
                <div class="notice error">Product not found. <a href="index.php">Return to home page</a>.</div>
            <?php else: ?>

                <?php if (isset($_GET['added'])): ?>
                    <div class="notice success single-notice">Product added to cart successfully.</div>
                <?php endif; ?>

                <div class="breadcrumb">
                    <a href="index.php">Home</a>
                    <span>/</span>
                    <a href="index.php?category=<?= urlencode($product['category']) ?>"><?= htmlspecialchars(ucfirst($product['category'])) ?></a>
                    <span>/</span>
                    <span><?= htmlspecialchars($product['name']) ?></span>
                </div>

                <section class="single-product-layout">
                    <div class="single-gallery">
                        <div class="single-main-image-wrap">
                            <img id="mainProductImage"
                                 src="<?= htmlspecialchars($product['gallery'][0] ?? $product['image']) ?>"
                                 alt="<?= htmlspecialchars($product['name']) ?>"
                                 class="single-main-image">
                        </div>

                        <div class="single-thumbs">
                            <?php foreach ($product['gallery'] as $index => $image): ?>
                                <button type="button"
                                        class="thumb-btn<?= $index === 0 ? ' active' : '' ?>"
                                        data-image="<?= htmlspecialchars($image) ?>"
                                        aria-label="Show image <?= $index + 1 ?>">
                                    <img src="<?= htmlspecialchars($image) ?>"
                                         alt="<?= htmlspecialchars($product['name']) ?> thumbnail <?= $index + 1 ?>">
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="single-info">
                        <p class="single-category"><?= htmlspecialchars(ucfirst($product['category'])) ?></p>
                        <h1><?= htmlspecialchars($product['name']) ?></h1>

                        <div class="single-rating-row">
                            <?= renderStars((int) $product['rating']) ?>
                            <span class="rating-text">(<?= (int) $product['rating'] ?>.0 rating)</span>
                        </div>

                        <div class="single-price-row">
                            <p class="single-price"><?= money((float) $product['price']) ?></p>
                            <?php if (!empty($product['special_offer'])): ?>
                                <span class="product-tag"><?= (int) $product['special_offer'] ?>% OFF</span>
                            <?php endif; ?>
                        </div>

                        <p class="single-desc"><?= htmlspecialchars($product['short_description']) ?></p>

                        <form action="singleproduct.php?id=<?= (int) $product['id'] ?>" method="post" class="product-form">
                            <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">

                            <div class="form-row">
                                <div class="form-col">
                                    <label for="size">Size</label>
                                    <select name="size" id="size" class="field-control">
                                        <?php foreach ($product['sizes'] as $size): ?>
                                            <option value="<?= htmlspecialchars($size) ?>"><?= htmlspecialchars($size) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-col">
                                    <label for="quantity">Quantity</label>
                                    <input type="number" min="1" max="10" value="1" name="quantity" id="quantity" class="field-control">
                                </div>
                            </div>

                            <button type="submit" name="add_to_cart" class="primary-btn full-btn add-to-cart-btn">
                                <i class="fa-solid fa-bag-shopping"></i> Add To Cart
                            </button>
                        </form>

                        <div class="product-details-box">
                            <h3>Product Details</h3>
                            <p><?= htmlspecialchars($product['description']) ?></p>
                        </div>

                        <a href="index.php#products" class="back-link">
                            <i class="fa-solid fa-arrow-left"></i> Back to products
                        </a>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </main>

    <?php require __DIR__ . '/includes/footer.php'; ?>

    <script>
        const mainImage = document.getElementById('mainProductImage');
        const thumbButtons = document.querySelectorAll('.thumb-btn');
        thumbButtons.forEach((button) => {
            button.addEventListener('click', () => {
                if (!mainImage) return;
                mainImage.src = button.dataset.image;
                thumbButtons.forEach((item) => item.classList.remove('active'));
                button.classList.add('active');
            });
        });
    </script>
</body>
</html>
