<?php
session_start();
include('../server/connection.php');

if (!isset($_SESSION['admin_logged_in'])) {
    header('location: login.php');
    exit;
}

if (isset($_GET['product_id']) && isset($_GET['product_quantity'])) {
    $product_id = $_GET['product_id'];
    $quantity = $_GET['product_quantity'];

    // Lấy số lượng hiện tại của sản phẩm
    $stmt = $conn->prepare("SELECT product_quantity FROM products WHERE product_id = ?");
    $stmt->bind_param("s", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $current_quantity = $row["product_quantity"];

    // Cập nhật số lượng mới
    $new_quantity = $current_quantity + $quantity;
    $stmt = $conn->prepare("UPDATE products SET product_quantity = ? WHERE product_id = ?");
    $stmt->bind_param("is", $new_quantity, $product_id);
    $stmt->execute();
}

// Redirect về trang ware_house.php sau khi cập nhật
header("Location: ware_house.php");
exit();
