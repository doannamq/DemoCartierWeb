<?php
include('server/connection.php');

$order_id = $_GET['order_id'];

$sql = "UPDATE orders SET `cancel_order` = 'do not cancel' WHERE `order_id` = $order_id";
if ($conn->query($sql) === TRUE) {
    header("Location: account.php?cacel_status=Đặt lại đơn hàng thành công");
    exit();
} else {
    echo "Lỗi khi cập nhật: " . $conn->error;
}

$conn->close();
