<?php
include('../server/connection.php');

// Lấy giá trị order_id và page_no từ URL
$order_id = $_GET['order_id'];
$page_no = $_GET['page_no'];

// Cập nhật cột "confirm" trong bảng order thành "confirmed"
$sql = "UPDATE orders SET `confirm` = 'confirmed' WHERE `order_id` = $order_id";
if ($conn->query($sql) === TRUE) {
    // Chuyển hướng về trang hiện tại (index.php?page_no=$page_no)
    header("Location: index.php?page_no=$page_no");
    exit();
} else {
    echo "Lỗi khi cập nhật: " . $conn->error;
}

$conn->close();
