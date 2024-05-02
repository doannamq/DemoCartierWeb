<?php
include('connection.php');

//Featured
$stmt = $conn->prepare("SELECT * FROM products WHERE product_special_offer > 0 LIMIT 4");
$stmt->execute();
$product_special_offer = $stmt->get_result();
