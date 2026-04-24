<?php
// Insert a new order + order_items into DB
// Returns order_id on success, false on failure
function createOrder(mysqli $con, array $cartItems, float $total, array $form, int $userId): int|false
{
    $phone   = preg_replace('/\D/', '', $form['phone']);
    $phone   = (int) substr($phone, 0, 10); // fits INT(11)
    $city    = $form['city'];
    $address = $form['address'];

    $stmt = $con->prepare(
        "INSERT INTO orders (order_cost, order_status, user_id, user_phone, user_city, user_address)
         VALUES (?, 'on_hold', ?, ?, ?, ?)"
    );
    $stmt->bind_param("diiss", $total, $userId, $phone, $city, $address);
    $stmt->execute();
    $orderId = $con->insert_id;
    $stmt->close();

    if (!$orderId) return false;

    foreach ($cartItems as $item) {
        $productId   = (int) $item['product']['id'];
        $productName = $item['product']['name'];
        $productImg  = $item['product']['image'];

        $stmt2 = $con->prepare(
            "INSERT INTO order_items (order_id, product_id, product_name, product_image, user_id)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt2->bind_param("iissi", $orderId, $productId, $productName, $productImg, $userId);
        $stmt2->execute();
        $stmt2->close();
    }

    return $orderId;
}

// Get orders for a user (with items)
function getUserOrders(mysqli $con, int $userId): array
{
    $stmt = $con->prepare(
        "SELECT oi.item_id, oi.product_name, oi.product_image,
                o.order_id, o.order_status, o.order_cost, o.order_date
         FROM order_items oi
         JOIN orders o ON oi.order_id = o.order_id
         WHERE oi.user_id = ?
         ORDER BY o.order_date DESC"
    );
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    $stmt->close();
    return $rows;
}
