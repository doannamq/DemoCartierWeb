<?php
// if (isset($_GET['limit']) && isset($_GET['start'])) {
//     $limit = $_GET['limit'];
//     $start = $_GET['start'];

//     include_once './server/connection.php';

//     // $sql = "select product.*, brand.name as brand_name
//     //             from product, brand
//     //             where product.brand = brand.id
//     //             limit " . $start . ", " . $limit . "";

//     $sql = "select products.product_id, products.product_image, products.product_name, products.product_price from products  limit " . $start . ", " . $limit . "";

//     $result = $conn->query($sql);

//     if ($result->num_rows > 0) {
//         $out = array();
//         $out['status'] = 1;
//         while ($row = $result->fetch_array()) {
//             $out['data'][] = array(
//                 // 'id' => $row['id'],
//                 // 'name' => $row['name'],
//                 // 'brand' => $row['brand_name'],
//                 // 'cpu' => $row['cpu'],
//                 // 'ram' => $row['ram'],
//                 // 'hard_drive' => $row['hard_drive'],
//                 // 'graphics' => $row['graphics'],
//                 'product_id' => $row['product_id'],
//                 'product_image' => $row['product_image'],
//                 'product_name' => $row['product_name'],
//                 'product_price' => $row['product_price'],
//             );
//         }
//     } else {
//         $out['status'] = 0;
//     }
//     echo json_encode($out);
//}
// Kết nối cơ sở dữ liệu
include_once './server/connection.php';

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lấy giá trị limit và start từ request
$limit = $_GET['limit'];
$start = $_GET['start'];

// Truy vấn database để lấy sản phẩm
$sql = "SELECT * FROM products LIMIT $start, $limit";
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $response = array(
        'status' => 1,
        'data' => $data
    );
} else {
    $response = array(
        'status' => 0,
        'message' => 'No data found'
    );
}

// Trả về dữ liệu JSON
echo json_encode($response);

$conn->close();
