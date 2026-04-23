<?php
// Database connection
// As shown in video 54: Database connection
// Uses mysqli_connect(host, username, password, database, port)
// Username: root, Password: (empty), Database: php_project, Port: 3308

$con = mysqli_connect("localhost", "root", "", "php_project", 3308) or die("Couldn't connect to database");
