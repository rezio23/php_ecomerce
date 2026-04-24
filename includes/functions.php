<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// =============================================
// AUTH HELPERS
// =============================================

function isLoggedIn(): bool
{
    return !empty($_SESSION['user_id']);
}

function getCurrentUser(mysqli $con): ?array
{
    if (!isLoggedIn()) return null;
    $id = (int) $_SESSION['user_id'];
    $stmt = $con->prepare("SELECT user_id, user_name, user_email FROM users WHERE user_id = ? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $row ?: null;
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

// =============================================
// FORMATTING HELPERS
// =============================================

function money(float $amount): string
{
    return '$' . number_format($amount, 2);
}

function renderStars(int $rating): string
{
    $html = '<div class="stars">';
    for ($i = 1; $i <= 5; $i++) {
        $class = $i <= $rating ? 'fa-solid fa-star active' : 'fa-regular fa-star';
        $html .= '<i class="' . $class . '"></i>';
    }
    $html .= '</div>';
    return $html;
}

// =============================================
// CART HELPERS (SESSION-BASED)
// =============================================

function getCartCount(): int
{
    return array_sum($_SESSION['cart'] ?? []);
}

function getProductById(array $products, int $id): ?array
{
    foreach ($products as $product) {
        if ((int) $product['id'] === $id) {
            return $product;
        }
    }
    return null;
}

function addToCart(int $productId, int $quantity = 1): void
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $quantity = max(1, $quantity);
    $_SESSION['cart'][$productId] = ($_SESSION['cart'][$productId] ?? 0) + $quantity;
}

function updateCartItem(int $productId, int $quantity): void
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if ($quantity <= 0) {
        unset($_SESSION['cart'][$productId]);
        return;
    }
    $_SESSION['cart'][$productId] = $quantity;
}

function removeFromCart(int $productId): void
{
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

function clearCart(): void
{
    $_SESSION['cart'] = [];
}

// =============================================
// CART WITH DB PRODUCTS
// =============================================

// Uses DB connection to load real product data for cart items
function getCartItemsDB(mysqli $con): array
{
    $cart = $_SESSION['cart'] ?? [];
    if (empty($cart)) return [];

    $ids = array_map('intval', array_keys($cart));
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));

    $stmt = $con->prepare("SELECT * FROM products WHERE product_id IN ($placeholders)");
    $stmt->bind_param($types, ...$ids);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    while ($row = $result->fetch_assoc()) {
        $productId = (int) $row['product_id'];
        $qty = max(1, (int) ($cart[$productId] ?? 1));
        $price = (float) $row['product_price'];
        $items[] = [
            'product' => [
                'id'    => $productId,
                'name'  => $row['product_name'],
                'price' => $price,
                'image' => $row['product_image'],
                'category' => $row['product_category'],
            ],
            'quantity' => $qty,
            'subtotal' => $price * $qty,
        ];
    }
    $stmt->close();
    return $items;
}

function getCartSubtotalDB(mysqli $con): float
{
    $subtotal = 0.0;
    foreach (getCartItemsDB($con) as $item) {
        $subtotal += $item['subtotal'];
    }
    return $subtotal;
}

// =============================================
// STATIC FALLBACK CART (when no DB)
// =============================================

function getCartItems(array $products): array
{
    $items = [];
    $cart = $_SESSION['cart'] ?? [];
    foreach ($cart as $productId => $quantity) {
        $product = getProductById($products, (int) $productId);
        if ($product === null) continue;
        $qty = max(1, (int) $quantity);
        $subtotal = (float) $product['price'] * $qty;
        $items[] = ['product' => $product, 'quantity' => $qty, 'subtotal' => $subtotal];
    }
    return $items;
}

function getCartSubtotal(array $products): float
{
    $subtotal = 0.0;
    foreach (getCartItems($products) as $item) {
        $subtotal += $item['subtotal'];
    }
    return $subtotal;
}

// =============================================
// PRODUCT CARD RENDERER
// =============================================

function renderProductCard(array $product, string $backRef = 'index'): string
{
    $id       = (int) $product['id'];
    $name     = htmlspecialchars($product['name']);
    $image    = htmlspecialchars($product['image']);
    $category = htmlspecialchars($product['category']);
    $price    = money((float) $product['price']);
    $tag      = htmlspecialchars($product['tag'] ?? '');
    $rating   = (int) ($product['rating'] ?? 5);
    $link     = 'singleproduct.php?id=' . $id;
    $offer    = (int) ($product['special_offer'] ?? 0);

    $tagHtml = $tag !== ''
        ? '<span class="product-tag">' . $tag . '</span>'
        : '';

    return '
        <article class="product-card">
            <a class="product-thumb-link" href="' . $link . '">
                <div class="product-image-wrap">
                    <img src="' . $image . '" alt="' . $name . '" class="product-image" loading="lazy">
                </div>
            </a>
            <div class="product-meta-top">
                <span class="product-category">' . $category . '</span>
                ' . $tagHtml . '
            </div>
            <a class="product-title-link" href="' . $link . '">
                <h3 class="product-name">' . $name . '</h3>
            </a>
            ' . renderStars($rating) . '
            <p class="product-price">' . $price . '</p>
            <div class="product-actions">
                <a class="details-btn" href="' . $link . '">View Details</a>
                <a class="buy-btn" href="index.php?add=' . $id . '">Add To Cart</a>
            </div>
        </article>
    ';
}

// =============================================
// STATUS BADGE
// =============================================

function statusBadge(string $status): string
{
    $map = [
        'delivered' => 'badge-green',
        'shipped'   => 'badge-blue',
        'on_hold'   => 'badge-yellow',
        'cancelled' => 'badge-red',
    ];
    $class = $map[$status] ?? 'badge-yellow';
    $label = ucwords(str_replace('_', ' ', $status));
    return '<span class="status-badge ' . $class . '">' . htmlspecialchars($label) . '</span>';
}
