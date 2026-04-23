<?php
// Database connection
// As shown in video 54: Database connection
// Uses mysqli_connect(host, username, password, database)
// Username: root, Password: (empty), Database: php_project

$con = mysqli_connect("localhost", "root", "", "php_project") or die("Couldn't connect to database");
