<?php
include('connection.php');

//Featured
$stmt = $conn->prepare("SELECT * FROM products WHERE product_special_offer = 0 LIMIT 4");
$stmt->execute();
$featured_products = $stmt->get_result();

/*
//Clothes
$clothes = $conn->prepare("SELECT * FROM clothes LIMIT 4");
$clothes->execute();
$clothes_products = $clothes->get_result();

//Watches
$watches = $conn->prepare("SELECT * FROM watches LIMIT 4");
$watches->execute();
$watches_products = $watches->get_result();

//Sport Shoes
$sport_shoes = $conn->prepare("SELECT * FROM sportshoes LIMIT 4");
$sport_shoes->execute();
$sport_shoes_products = $sport_shoes->get_result();
*/