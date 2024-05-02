<?php
include('connection.php');

//Featured
$stmt = $conn->prepare("SELECT * FROM products WHERE product_category='bag' LIMIT 4");
$stmt->execute();
$shoes = $stmt->get_result();
