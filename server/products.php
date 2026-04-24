<?php
// Helper: get a single product from DB by ID
// Returns array or null
function getProductFromDB(mysqli $con, int $id): ?array
{
    $stmt = $con->prepare("SELECT * FROM products WHERE product_id = ? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$row) return null;
    return $row;
}

// Helper: get all products (with optional category filter)
function getProductsFromDB(mysqli $con, string $category = ''): mysqli_result|false
{
    if ($category !== '' && $category !== 'all') {
        $stmt = $con->prepare("SELECT * FROM products WHERE product_category = ? ORDER BY product_id");
        $stmt->bind_param("s", $category);
    } else {
        $stmt = $con->prepare("SELECT * FROM products ORDER BY product_id");
    }
    $stmt->execute();
    return $stmt->get_result();
}

// Convert a DB row to the same shape used by static data / functions
function dbRowToProduct(array $row): array
{
    return [
        'id'                => (int) $row['product_id'],
        'name'              => $row['product_name'],
        'price'             => (float) $row['product_price'],
        'rating'            => 5,
        'category'          => $row['product_category'],
        'slug'              => strtolower(str_replace(' ', '-', $row['product_name'])),
        'short_description' => $row['product_description'],
        'description'       => $row['product_description'],
        'sizes'             => ['XS', 'S', 'M', 'L', 'XL'],
        'image'             => $row['product_image'],
        'gallery'           => array_filter([
            $row['product_image'],
            $row['product_image2'],
            $row['product_image3'],
            $row['product_image4'],
        ]),
        'tag'               => $row['product_special_offer'] > 0
                                 ? $row['product_special_offer'] . '% OFF'
                                 : '',
        'color'             => $row['product_color'],
        'special_offer'     => (int) $row['product_special_offer'],
    ];
}
