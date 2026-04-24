<?php
require __DIR__ . '/data.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/server/connection.php';
require __DIR__ . '/server/products.php';

// Add to cart
if (isset($_GET['add'])) {
    $addId = (int) $_GET['add'];
    $check = getProductFromDB($con, $addId);
    if ($check) addToCart($addId, 1);
    $qs = http_build_query(array_filter([
        'added'    => 1,
        'category' => $_GET['category'] ?? '',
        'sort'     => $_GET['sort'] ?? '',
        'q'        => $_GET['q'] ?? '',
    ]));
    header('Location: shop.php?' . $qs . '#results');
    exit;
}

// Filters
$activeCategory = isset($_GET['category']) ? strtolower(trim($_GET['category'])) : 'all';
$sort           = $_GET['sort'] ?? 'default';
$search         = trim($_GET['q'] ?? '');
$categories     = ['all', 'shoes', 'coats', 'dresses', 'shirts', 'bags'];
$sortOptions    = [
    'default'     => 'Featured',
    'price_asc'   => 'Price: Low to High',
    'price_desc'  => 'Price: High to Low',
    'name_asc'    => 'Name: A–Z',
];

// Build query
$where  = [];
$params = [];
$types  = '';

if ($activeCategory !== 'all' && $activeCategory !== '') {
    $where[]  = 'product_category = ?';
    $params[] = $activeCategory;
    $types   .= 's';
}

if ($search !== '') {
    $where[]  = '(product_name LIKE ? OR product_description LIKE ?)';
    $like     = '%' . $search . '%';
    $params[] = $like;
    $params[] = $like;
    $types   .= 'ss';
}

$orderBy = match($sort) {
    'price_asc'  => 'ORDER BY product_price ASC',
    'price_desc' => 'ORDER BY product_price DESC',
    'name_asc'   => 'ORDER BY product_name ASC',
    default      => 'ORDER BY product_id ASC',
};

$whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';
$sql = "SELECT * FROM products $whereClause $orderBy";

$stmt = $con->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$allProducts = [];
while ($row = $result->fetch_assoc()) {
    $allProducts[] = dbRowToProduct($row);
}
$stmt->close();

// Price range stats
$stmt2 = $con->prepare("SELECT MIN(product_price) as minp, MAX(product_price) as maxp FROM products");
$stmt2->execute();
$priceRange = $stmt2->get_result()->fetch_assoc();
$stmt2->close();

// Category counts
$stmt3 = $con->prepare("SELECT product_category, COUNT(*) as cnt FROM products GROUP BY product_category");
$stmt3->execute();
$catCounts = [];
$cr = $stmt3->get_result();
$totalCount = 0;
while ($row = $cr->fetch_assoc()) {
    $catCounts[$row['product_category']] = (int) $row['cnt'];
    $totalCount += (int) $row['cnt'];
}
$stmt3->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop | <?= htmlspecialchars($site['brand']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php require __DIR__ . '/includes/header.php'; ?>

    <!-- Page Hero -->
    <div class="page-hero">
        <div class="container">
            <div class="breadcrumb">
                <a href="index.php">Home</a><span>/</span><span>Shop</span>
            </div>
            <h1>Shop All Products</h1>
            <p><?= count($allProducts) ?> item<?= count($allProducts) !== 1 ? 's' : '' ?> available</p>
        </div>
    </div>

    <?php if (isset($_GET['added'])): ?>
        <div class="notice-wrap">
            <div class="container notice success">Product added to cart. <a href="cart.php">View Cart</a></div>
        </div>
    <?php endif; ?>

    <main class="shop-page section-space">
        <div class="container">
            <div class="shop-layout" id="results">

                <!-- Sidebar -->
                <aside class="shop-sidebar">

                    <!-- Search -->
                    <div class="sidebar-block">
                        <h4 class="sidebar-title">Search</h4>
                        <form action="shop.php" method="get" class="sidebar-search-form">
                            <?php if ($activeCategory !== 'all'): ?>
                                <input type="hidden" name="category" value="<?= htmlspecialchars($activeCategory) ?>">
                            <?php endif; ?>
                            <?php if ($sort !== 'default'): ?>
                                <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
                            <?php endif; ?>
                            <div class="sidebar-search-wrap">
                                <input type="text" name="q" class="field-control"
                                       placeholder="Search products…"
                                       value="<?= htmlspecialchars($search) ?>">
                                <button type="submit" class="sidebar-search-btn">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Categories -->
                    <div class="sidebar-block">
                        <h4 class="sidebar-title">Categories</h4>
                        <ul class="sidebar-cat-list">
                            <li>
                                <a href="shop.php?category=all<?= $sort !== 'default' ? '&sort=' . urlencode($sort) : '' ?><?= $search ? '&q=' . urlencode($search) : '' ?>"
                                   class="sidebar-cat-link <?= $activeCategory === 'all' ? 'active' : '' ?>">
                                    All Products
                                    <span class="cat-count"><?= $totalCount ?></span>
                                </a>
                            </li>
                            <?php foreach ($categories as $cat):
                                if ($cat === 'all') continue;
                                $cnt = $catCounts[$cat] ?? 0;
                            ?>
                            <li>
                                <a href="shop.php?category=<?= urlencode($cat) ?><?= $sort !== 'default' ? '&sort=' . urlencode($sort) : '' ?><?= $search ? '&q=' . urlencode($search) : '' ?>"
                                   class="sidebar-cat-link <?= $activeCategory === $cat ? 'active' : '' ?>">
                                    <?= ucfirst($cat) ?>
                                    <span class="cat-count"><?= $cnt ?></span>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Price Range Info -->
                    <div class="sidebar-block">
                        <h4 class="sidebar-title">Price Range</h4>
                        <p class="sidebar-price-range">
                            <?= money((float)$priceRange['minp']) ?> — <?= money((float)$priceRange['maxp']) ?>
                        </p>
                    </div>

                    <!-- Clear filters -->
                    <?php if ($activeCategory !== 'all' || $search !== '' || $sort !== 'default'): ?>
                    <a href="shop.php" class="clear-filters-btn">
                        <i class="fa-solid fa-xmark"></i> Clear All Filters
                    </a>
                    <?php endif; ?>
                </aside>

                <!-- Main -->
                <div class="shop-main">

                    <!-- Toolbar -->
                    <div class="shop-toolbar">
                        <p class="shop-results-count">
                            <?php if ($search !== ''): ?>
                                <strong><?= count($allProducts) ?></strong> result<?= count($allProducts) !== 1 ? 's' : '' ?> for "<em><?= htmlspecialchars($search) ?></em>"
                            <?php else: ?>
                                Showing <strong><?= count($allProducts) ?></strong> product<?= count($allProducts) !== 1 ? 's' : '' ?>
                                <?= $activeCategory !== 'all' ? 'in <strong>' . ucfirst($activeCategory) . '</strong>' : '' ?>
                            <?php endif; ?>
                        </p>

                        <form action="shop.php" method="get" class="sort-form" id="sortForm">
                            <?php if ($activeCategory !== 'all'): ?>
                                <input type="hidden" name="category" value="<?= htmlspecialchars($activeCategory) ?>">
                            <?php endif; ?>
                            <?php if ($search !== ''): ?>
                                <input type="hidden" name="q" value="<?= htmlspecialchars($search) ?>">
                            <?php endif; ?>
                            <label for="sort-select" class="sort-label">Sort by:</label>
                            <select name="sort" id="sort-select" class="field-control sort-select" onchange="this.form.submit()">
                                <?php foreach ($sortOptions as $val => $label): ?>
                                    <option value="<?= $val ?>" <?= $sort === $val ? 'selected' : '' ?>><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>

                    <!-- Grid -->
                    <?php if (empty($allProducts)): ?>
                        <div class="shop-empty">
                            <i class="fa-solid fa-box-open"></i>
                            <h3>No products found</h3>
                            <p>Try a different search or category.</p>
                            <a href="shop.php" class="primary-btn">Clear Filters</a>
                        </div>
                    <?php else: ?>
                        <div class="product-grid shop-grid">
                            <?php foreach ($allProducts as $p): ?>
                                <article class="product-card">
                                    <a class="product-thumb-link" href="singleproduct.php?id=<?= (int)$p['id'] ?>">
                                        <div class="product-image-wrap">
                                            <img src="<?= htmlspecialchars($p['image']) ?>"
                                                 alt="<?= htmlspecialchars($p['name']) ?>"
                                                 class="product-image" loading="lazy">
                                            <?php if ($p['special_offer'] > 0): ?>
                                                <div class="product-badge"><?= (int)$p['special_offer'] ?>% OFF</div>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                    <div class="product-meta-top">
                                        <span class="product-category"><?= htmlspecialchars(ucfirst($p['category'])) ?></span>
                                    </div>
                                    <a class="product-title-link" href="singleproduct.php?id=<?= (int)$p['id'] ?>">
                                        <h3 class="product-name"><?= htmlspecialchars($p['name']) ?></h3>
                                    </a>
                                    <?= renderStars(5) ?>
                                    <p class="product-price"><?= money((float)$p['price']) ?></p>
                                    <div class="product-actions">
                                        <a class="details-btn" href="singleproduct.php?id=<?= (int)$p['id'] ?>">View Details</a>
                                        <a class="buy-btn" href="shop.php?add=<?= (int)$p['id'] ?>&category=<?= urlencode($activeCategory) ?>&sort=<?= urlencode($sort) ?><?= $search ? '&q=' . urlencode($search) : '' ?>">Add To Cart</a>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </main>

    <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
