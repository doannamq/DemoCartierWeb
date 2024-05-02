<?php
include('server/connection.php');

if (isset($_POST['category'])) {
    $category = $_POST['category'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_category = ?");
    $stmt->bind_param('s', $category);
    $stmt->execute();
    $products = $stmt->get_result();

    // Bắt đầu container cho hàng mới
    echo '<div class="row mx-auto container-fluid">';

    $count = 0; // Biến đếm số sản phẩm đã hiển thị trong hàng hiện tại
    // Hiển thị sản phẩm đã lọc
    while ($row = $products->fetch_assoc()) {
        // Hiển thị sản phẩm
        echo '<div class="product text-center col-lg-3 col-md-4 col-sm-12 pb-4">';
        echo '<img class="img-fluid mb-3" src="assets/imgs/' . $row['product_image'] . '" />';
        echo '<div class="star">';
        // Hiển thị sao
        echo '<i class="fa fa-star"></i>';
        echo '<i class="fa fa-star"></i>';
        echo '<i class="fa fa-star"></i>';
        echo '<i class="fa fa-star"></i>';
        echo '<i class="fa fa-star"></i>';
        echo '</div>';
        // Hiển thị tên sản phẩm và giá
        echo '<h5 class="p-name">' . $row['product_name'] . '</h5>';
        echo '<h4 class="p-price">$ ' . $row['product_price'] . '</h4>';
        // Liên kết đến trang chi tiết sản phẩm
        echo '<a class="btn buy-btn" href="single_product.php?product_id=' . $row['product_id'] . '">Mua ngay</a>';
        echo '</div>';

        $count++; // Tăng biến đếm số sản phẩm đã hiển thị trong hàng hiện tại
        // Nếu đã hiển thị 4 sản phẩm, đóng container cho hàng hiện tại và bắt đầu hàng mới
        if ($count % 4 == 0) {
            echo '</div>'; // Kết thúc hàng
            echo '<div class="row mx-auto container-fluid">'; // Bắt đầu hàng mới
        }
    }

    // Kết thúc container cho hàng cuối cùng nếu cần
    if ($count % 4 != 0) {
        echo '</div>'; // Kết thúc hàng
    }
}
