<?php
// Get coats/category products from the database
// As shown in video 60: Get coats
// Selects products WHERE product_category = 'coats' LIMIT 4

include "connection.php";

$stmt = $con->prepare("SELECT * FROM products WHERE product_category = 'coats' LIMIT 4");
$stmt->execute();
$coats_products = $stmt->get_result();
