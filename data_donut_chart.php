<?php
// Kết nối với cơ sở dữ liệu
include('server/connection.php');

// Truy vấn dữ liệu từ bảng order_items và products
$query = "SELECT p.product_category, SUM(oi.product_quantity) AS total_quantity
          FROM order_items oi
          JOIN products p ON oi.product_id = p.product_id
          GROUP BY p.product_category";

$result = $conn->query($query);

// Tạo mảng dữ liệu để trả về
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = array(
        'label' => $row['product_category'],
        'y' => intval($row['total_quantity'])
    );
}

// Trả về dữ liệu dưới dạng JSON
echo json_encode($data);

// Đóng kết nối
$conn->close();
