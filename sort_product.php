<?php
// Kết nối CSDL
include('server/connection.php');

// Kiểm tra nếu tồn tại yêu cầu AJAX và phương thức POST được sử dụng
if (isset($_POST['order']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy thứ tự sắp xếp từ yêu cầu POST
    $order = $_POST['order'];

    // Xây dựng truy vấn SQL dựa trên thứ tự sắp xếp được chọn
    $sql = "SELECT * FROM products ORDER BY product_price";

    if ($order == 'low_to_high') {
        $sql .= " ASC";
    } elseif ($order == 'high_to_low') {
        $sql .= " DESC";
    }

    // Thực hiện truy vấn
    $result = $conn->query($sql);

    // Lấy số trang hiện tại (nếu có)
    $page_no = isset($_GET['page_no']) ? $_GET['page_no'] : 1;

    // Số sản phẩm trên mỗi trang
    $total_records_per_page = 8;

    // Tính offset
    $offset = ($page_no - 1) * $total_records_per_page;

    // Truy vấn sản phẩm cho trang hiện tại
    $sql .= " LIMIT $offset, $total_records_per_page";
    $result = $conn->query($sql);

    // Kiểm tra xem có sản phẩm nào được trả về không
    if ($result->num_rows > 0) {
        // Hiển thị sản phẩm đã được sắp xếp
        echo '<div class="row">';
        $count = 0;
        while ($row = $result->fetch_assoc()) {
            echo '<div class="product text-center col-lg-3 col-md-3 col-sm-6 pb-4">';
            echo '<img class="img-fluid mb-3" src="assets/imgs/' . $row['product_image'] . '" />';
            echo '<div class="star">';
            echo '<i class="fa fa-star"></i>';
            echo '<i class="fa fa-star"></i>';
            echo '<i class="fa fa-star"></i>';
            echo '<i class="fa fa-star"></i>';
            echo '<i class="fa fa-star"></i>';
            echo '</div>';
            echo '<h5 class="p-name">' . $row['product_name'] . '</h5>';
            echo '<h4 class="p-price">$ ' . number_format($row['product_price'], 2, '.', ',') . '</h4>';
            echo '<a class="btn buy-btn" href="single_product.php?product_id=' . $row['product_id'] . '">Mua ngay</a>';
            echo '</div>';
            $count++;
            // Đóng hàng sau mỗi 4 sản phẩm
            if ($count % 4 == 0) {
                echo '</div><div class="row">';
            }
        }
        echo '</div>'; // Đóng hàng cuối cùng nếu số lượng sản phẩm không chia hết cho 4
    } else {
        echo '<p>Không tìm thấy sản phẩm.</p>';
    }
} else {
    // Nếu không có yêu cầu POST hoặc không tồn tại yêu cầu AJAX, chuyển hướng hoặc thực hiện các xử lý khác tùy thuộc vào yêu cầu của bạn.
}