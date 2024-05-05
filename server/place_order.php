<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require('../phpmailer/src/Exception.php');
require('../phpmailer/src/PHPMailer.php');
require('../phpmailer/src/SMTP.php');

session_start();

include('connection.php');

//if user is not logged in
if (!isset($_SESSION['logged_in'])) {
    header('location: ../checkout.php?message=Đăng nhập/Đăng ký để đặt hàng');
    exit;

    //if user is logged in
} else {

    if (isset($_POST['place_order'])) {

        //1.get user info and store it in database
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $city = $_POST['city'];
        $district = $_POST['district'];
        $ward = $_POST['ward'];
        $address = $_POST['address'];
        $order_cost = $_SESSION['total'];
        $order_status = "not paid";
        $user_id = $_SESSION['user_id'];
        $order_date = date('Y-m-d H:i:s');

        $stmt = $conn->prepare("INSERT INTO orders (order_cost, order_status, user_id, user_phone, user_city, user_district, user_ward, user_address, order_date )
                            VALUES(?,?,?,?,?,?,?,?,?); ");

        $stmt->bind_param('isiisssss', $order_cost, $order_status, $user_id, $phone, $city, $district, $ward, $address, $order_date);

        $stmt_status = $stmt->execute();

        if (!$stmt_status) {
            header('location: index.php');
            exit;
        }

        //2.issue new order and store order info in database
        $order_id = $stmt->insert_id;

        //3.get product from cart(from session)
        $_SESSION['cart'];
        // Khởi tạo mảng ordered_quantities
        $ordered_quantities = array();
        foreach ($_SESSION['cart'] as $key => $value) {
            $product = $_SESSION['cart'][$key];
            $product_id = $product['product_id'];
            $product_name = $product['product_name'];
            $product_image = $product['product_image'];
            $product_price = $product['product_price'];
            $product_quantity = $product['product_quantity'];

            //4. store each single item in order_items database
            $stmt1 = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_image, product_price, product_quantity, user_id, order_date)
                      VALUES (?,?,?,?,?,?,?,?)");

            $stmt1->bind_param('iissiiis', $order_id, $product_id, $product_name, $product_image, $product_price, $product_quantity, $user_id, $order_date);

            $stmt1->execute();

            // Lưu trữ số lượng đã đặt hàng cho mỗi sản phẩm vào mảng
            if (array_key_exists($product_id, $ordered_quantities)) {
                $ordered_quantities[$product_id] += $product_quantity;
            } else {
                $ordered_quantities[$product_id] = $product_quantity;
            }
        }

        //update product_quantity trong products
        foreach ($ordered_quantities as $product_id => $ordered_quantity) {
            // Lấy số lượng hiện tại của sản phẩm trong products
            $stmt3 = $conn->prepare("SELECT product_quantity FROM products WHERE product_id = ?");
            $stmt3->bind_param("s", $product_id);
            $stmt3->execute();
            $result3 = $stmt3->get_result();
            $row3 = $result3->fetch_assoc();
            $current_quantity = $row3["product_quantity"];

            // Cập nhật số lượng mới trong products
            $new_quantity = $current_quantity - $ordered_quantity;
            $stmt4 = $conn->prepare("UPDATE products SET product_quantity = ? WHERE product_id = ?");
            $stmt4->bind_param("is", $new_quantity, $product_id);
            $stmt4->execute();
        }


        //5. remove everything from cart --> delay until payment is done
        unset($_SESSION['cart']);

        $_SESSION['order_id'] = $order_id;

        //Send email thank you for shopping
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'doannamq@gmail.com';
        $mail->Password = 'criwujlzsqpvfxmg';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('doannamq@gmail.com');

        $mail->addAddress($email);

        $mail->isHTML(true);

        $mail->Subject = $_POST['subject'];
        $mail->Body = $_POST['message'];

        $mail->send();

        //6. inform user whether everything is fine or there is a problem 
        header('location: ../payment.php?order_status=Đặt hàng thành công');
    }
}
