<?php
// Get featured products from the database
// As shown in video 59: Get products
// Selects all products with a LIMIT of 4

include "connection.php";

$stmt = $con->prepare("SELECT * FROM products LIMIT 4");
$stmt->execute();
$featured_products = $stmt->get_result();
