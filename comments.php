<?php
include('server/connection.php');
session_start();

if (isset($_POST['comment'])) {
    $user_name = $_POST['user_name'];
    $user_id = $_POST['user_id'];
    $product_name = $_POST['product_name'];
    $product_id = $_POST['product_id'];
    $comment_content = $_POST['comment_content'];
    $create_at = date('Y-m-d H:i:s');

    if (!isset($_SESSION['logged_in'])) {
        header('location: single_product.php?message=Đăng nhập để bình luận&product_id=' . $product_id);
        exit;
    } else {

        $stmt = $conn->prepare("INSERT INTO comments (user_name, user_id, product_name, product_id, comment_content, create_at) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $user_name, $user_id, $product_name, $product_id, $comment_content, $create_at);

        if ($stmt->execute()) {
            $redirect_url = "single_product.php?product_id=" . $product_id;
            header("Location: " . $redirect_url);
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
