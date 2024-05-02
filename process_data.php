<?php
if (isset($_POST["query"])) {
    $connect = new PDO("mysql:host=localhost;dbname=php_project", "root", "");

    $data = array();

    // Sử dụng truy vấn tham số để tránh SQL Injection
    $condition = '%' . $_POST["query"] . '%';

    $query = "SELECT product_name FROM products WHERE product_name LIKE :condition OR product_category LIKE :condition ORDER BY product_id DESC LIMIT 10";


    $statement = $connect->prepare($query);
    $statement->bindParam(':condition', $condition, PDO::PARAM_STR);
    $statement->execute();

    $replace_string = '<b>' . $_POST["query"] . '</b>';

    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        // Thay thế từ khóa tìm kiếm trong tên sản phẩm để làm nổi bật
        $data[] = array(
            'product_name' => str_ireplace($_POST["query"], $replace_string, $row["product_name"])
        );
    }

    echo json_encode($data);
}
