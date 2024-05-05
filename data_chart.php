<?php
include('server/connection.php');

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn dữ liệu
// $sql = "SELECT YEAR(order_date) AS year, MONTH(order_date) AS month, SUM(order_cost) AS total_sales
//         FROM orders
//         GROUP BY YEAR(order_date), MONTH(order_date)
//         ORDER BY year, month";
$sql = "SELECT YEAR(order_date) AS year, MONTH(order_date) AS month, SUM(order_cost) AS total_sales
FROM orders
WHERE cancel_order = 'do not cancel'
GROUP BY YEAR(order_date), MONTH(order_date)
ORDER BY year, month;
";
$result = $conn->query($sql);

// Tạo đối tượng data
$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $year = $row["year"];
        $month = $row["month"] - 1; // Tháng bắt đầu từ 0
        $sales = $row["total_sales"];

        if (!isset($data[$year])) {
            $data[$year] = array_fill(0, 12, 0); // Khởi tạo mảng với 12 phần tử bằng 0
        }

        $data[$year][$month] = $sales;
    }
}

// Đóng kết nối
$conn->close();

// Trả về dữ liệu dưới dạng JSON và thoát khỏi tệp
header('Content-Type: application/json');
echo json_encode($data);
exit;
