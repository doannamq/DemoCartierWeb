<?php

// include("../server/connection.php");

// if (isset($_POST['create_delivery'])) {
//     // Nhận dữ liệu từ form
//     $sender_name = $_POST['sender_name'];
//     $sender_address = $_POST['sender_address'];
//     $sender_ward = $_POST['sender_ward'];
//     $sender_district = $_POST['sender_district'];
//     $sender_province = $_POST['sender_province'];
//     $recipient_name = $_POST['recipient_name'];
//     $recipient_phone = $_POST['recipient_phone'];
//     $recipient_address = $_POST['recipient_address'];
//     $recipient_ward = $_POST['recipient_ward'];
//     $recipient_district = $_POST['recipient_district'];
//     $recipient_province = $_POST['recipient_province'];
//     $order_id = $_POST['order_id'];
//     $order_weight = $_POST['order_weight'];
//     $order_length = $_POST['order_length'];
//     $order_width = $_POST['order_width'];
//     $order_height = $_POST['order_height'];
//     $delivery_service = $_POST['delivery_service'];
//     $note = $_POST['note'];
//     $order_price = $_POST['order_price'];
//     $pick_shift = $_POST['pick_shift'];
//     $product_id = $_POST['product_id'];
//     $product_name = $_POST['product_name'];
//     $product_quantity = $_POST['product_quantity'];
//     $product_category = $_POST['product_category'];

//     // Chuẩn bị và thực thi câu lệnh SQL
//     $sql = "INSERT INTO delivery_order (sender_name, sender_address, sender_ward, sender_district, sender_province, recipient_name, recipient_phone, recipient_address, recipient_ward, recipient_district, recipient_province, order_id, order_weight, order_length, order_width, order_height, delivery_service, note, order_price, pick_shift, product_id, product_name, product_quantity, product_category)
// VALUES ('$sender_name', '$sender_address', '$sender_ward', '$sender_district', '$sender_province', '$recipient_name', '$recipient_phone', '$recipient_address', '$recipient_ward', '$recipient_district', '$recipient_province', '$order_id', '$order_weight', '$order_length', '$order_width', '$order_height', '$delivery_service', '$note', '$order_price', '$pick_shift', '$product_id', '$product_name', '$product_quantity', '$product_category')";

//     if ($conn->query($sql) === TRUE) {
//         echo "Đơn hàng đã được tạo thành công";
//     } else {
//         echo "Lỗi: " . $sql . "<br>" . $conn->error;
//     }
// }

// $conn->close();

//////////////////////////////////////////////////////////////////////////////////////////
// include("../server/connection.php");

// if (isset($_POST['create_delivery'])) {
//     // Nhận dữ liệu từ form
//     $sender_name = "Catier Shop";
//     $sender_address = "255 Cầu Giấy";
//     $sender_ward = "Dịch Vọng";
//     $sender_district = "Cầu Giấy";
//     $sender_province = "Hà Nội";
//     $recipient_name = $_POST['recipient_name'];
//     $recipient_phone = $_POST['recipient_phone'];
//     $recipient_address = $_POST['recipient_address'];
//     $recipient_ward = $_POST['recipient_ward'];
//     $recipient_district = $_POST['recipient_district'];
//     $recipient_province = $_POST['recipient_province'];
//     $order_id = $_POST['order_id'];
//     $order_weight = intval($_POST['order_weight']);
//     $order_length = intval($_POST['order_length']);
//     $order_width = intval($_POST['order_width']);
//     $order_height = intval($_POST['order_height']);
//     $note = $_POST['note'];
//     $order_price = intval($_POST['order_price']);
//     $pick_shift = intval($_POST['pick_shift']);
//     $product_names = $_POST['product_names'];

//     $items = array();
//     $product_names_array = explode("\n", $product_names); // Tách chuỗi thành mảng dựa trên ký tự '\n'

//     foreach ($product_names_array as $name) {
//         $name = trim($name, '- '); // Loại bỏ ký tự '-' khỏi tên sản phẩm
//         if (!empty($name)) { // Kiểm tra nếu tên sản phẩm không trống
//             $item_data = array(
//                 "name" => $name,
//                 "code" => "1",
//                 "quantity" => 1,
//                 "price" => 5,
//                 "length" => 5,
//                 "width" => 5,
//                 "weight" => 5,
//                 "height" => 5,
//                 "category" => array("level1" => "")
//             );
//             $items[] = $item_data;
//         }
//     }

//     // Tạo mảng dữ liệu để gửi đến API
//     $data = array(
//         "payment_type_id" => 2,
//         "note" => $note,
//         "required_note" => "KHONGCHOXEMHANG",
//         "return_phone" => "0865860262",
//         "return_address" => $sender_address,
//         "return_district_name" => $sender_district,
//         "return_ward_name" => $sender_ward,
//         "client_order_code" => $order_id,
//         "from_name" => $sender_name,
//         "from_phone" => "0865860262",
//         "from_address" => $sender_address,
//         "from_ward_name" => $sender_ward,
//         "from_district_name" => $sender_district,
//         "from_province_name" => $sender_province,
//         "to_name" => $recipient_name,
//         "to_phone" => $recipient_phone,
//         "to_address" => $recipient_address,
//         "to_ward_name" => $recipient_ward,
//         "to_district_name" => $recipient_district,
//         "to_province_name" => $recipient_province,
//         "cod_amount" => $order_price,
//         "weight" => $order_weight,
//         "length" => $order_length,
//         "width" => $order_width,
//         "height" => $order_height,
//         "pick_shift" => [$pick_shift],
//         "items" => $items // Truyền mảng $items vào đây
//     );

//     $json_data = json_encode($data);

//     // Gửi dữ liệu đến API của Giao Hàng Nhanh
//     $url = "https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/create";
//     $headers = array(
//         "Content-Type: application/json",
//         "Token: af98b191-ffaf-11ee-b1d4-92b443b7a897",
//         "ShopId: 191981",
//     );

//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $url);
//     curl_setopt($ch, CURLOPT_POST, true);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
//     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//     $response = curl_exec($ch);
//     curl_close($ch);

//     // Xử lý kết quả trả về từ API
//     if ($response) {
//         $result = json_decode($response, true);
//         if ($result['code'] == 200) {
//             echo "Đơn hàng đã được tạo thành công";
//             // Lưu thông tin đơn hàng vào cơ sở dữ liệu nếu cần
//         } else {
//             echo "Lỗi: " . $result['code'] . " - " . $result['message'];
//         }
//     } else {
//         echo "Lỗi khi gửi dữ liệu đến API";
//     }
// }
/////////////////////////////////////////////////////////////////////////////////
include("../server/connection.php");

if (isset($_POST['create_delivery'])) {

    // Nhận dữ liệu từ form
    $sender_name = $_POST['sender_name'];
    $sender_address = $_POST['sender_address'];
    $sender_ward = $_POST['sender_ward'];
    $sender_district = $_POST['sender_district'];
    $sender_province = $_POST['sender_province'];
    $recipient_name = $_POST['recipient_name'];
    $recipient_phone = $_POST['recipient_phone'];
    $recipient_address = $_POST['recipient_address'];
    $recipient_ward = $_POST['recipient_ward'];
    $recipient_district = $_POST['recipient_district'];
    $recipient_province = $_POST['recipient_province'];
    $order_id = $_POST['order_id'];
    $order_weight = intval($_POST['order_weight']);
    $order_length = intval($_POST['order_length']);
    $order_width = intval($_POST['order_width']);
    $order_height = intval($_POST['order_height']);
    $note = $_POST['note'];
    $order_price = intval($_POST['order_cost']);
    // $pick_shift = intval($_POST['pick_shift']);
    // $product_names = $_POST['product_names'];
    // $product_quantity = intval($_POST['product_quantity']);
    $product_names = $_POST['product_names'];
    $product_quantity = array_map('intval', $_POST['product_quantity']);
    $note = $_POST['note'];

    //echo $recipient_ward, $recipient_district, $recipient_province;

    $items = array();
    for ($i = 0; $i < count($product_names); $i++) {
        $items[] = array(
            "name" => $product_names[$i],
            "quantity" => $product_quantity[$i],
            "category" => array(
                "level1" => "Trang sức"
            )
        );
    }

    $data = array(
        "payment_type_id" => 2,
        "note" => $note,
        "required_note" => "KHONGCHOXEMHANG",
        "return_phone" => "0865860262",
        "return_address" => $sender_address,
        "return_district_id" => null,
        "return_ward_code" => "",
        "client_order_code" => "",
        "from_name" => $sender_name,
        "from_phone" => "0865860262",
        "from_address" => $sender_address,
        "from_ward_name" => $sender_ward,
        "from_district_name" => $sender_district,
        "from_province_name" => $sender_province,
        "to_name" => $recipient_name,
        "to_phone" => $recipient_phone,
        "to_address" => $recipient_address,
        // "to_ward_name" => "Phường Cổ Nhuế 1",
        // "to_district_name" => "Quận Bắc Từ Liêm",
        // "to_province_name" => "Hà Nội",
        "to_ward_name" => $recipient_ward,
        "to_district_name" => $recipient_district,
        "to_province_name" => $recipient_province,
        "cod_amount" => $order_price * 25345,
        "content" => "Khong",
        "weight" => $order_weight,
        "length" => $order_length,
        "width" => $order_width,
        "height" => $order_height,
        "cod_failed_amount" => 2000,
        "pick_station_id" => 1444,
        "deliver_station_id" => null,
        "insurance_value" => $order_price * 25345,
        "service_id" => 0,
        "service_type_id" => 2,
        "coupon" => null,
        "pickup_time" => 1692840132,
        "pick_shift" => array(2),
        "items" => $items
    );

    $json_data = json_encode($data);

    // Gửi dữ liệu đến API của Giao Hàng Nhanh
    $url = "https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/create";
    $headers = array(
        "Content-Type: application/json",
        "Token: af98b191-ffaf-11ee-b1d4-92b443b7a897",
        "ShopId: 191981",
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response) {
        $result = json_decode($response, true);
        if ($result['code'] == 200) {
            $order_code = $result['data']['order_code']; // Lấy mã đơn hàng từ phản hồi

            // echo "Đơn hàng đã được tạo thành công với mã đơn: " . $order_code;

            // Cập nhật trạng thái đơn hàng trong bảng orders
            $update_status_sql = "UPDATE orders SET delivery_status = 'awaiting pickup', order_code = '$order_code' WHERE order_id = '$order_id'";
            if ($conn->query($update_status_sql) === TRUE) {
                header('location:index.php');
            } else {
                echo "Lỗi: " . $conn->error;
            }
        } else {
            echo "Lỗi: " . $result['code'] . " - " . $result['message'];
        }
    } else {
        echo "Lỗi khi gửi dữ liệu đến API";
    }
}
