<?php
// Database connection
$con = mysqli_connect("localhost", "phpuser", "phppass123", "php_project", 3306)
    or die("Couldn't connect to database");

$con->set_charset("utf8mb4");
