<?php
include('connection.php');

//Featured
$stmt = $conn->prepare("SELECT * FROM products WHERE product_category='ring' AND product_special_offer = 0 LIMIT 4");
$stmt->execute();
$coats = $stmt->get_result();
