<?php

use function PHPSTORM_META\type;

include('../server/connection.php');

if (isset($_GET['order_id']) && $_GET['user_id']) {
    $order_id = $_GET['order_id'];
    // Query để lấy thông tin đơn hàng dựa trên order_id
    $stmt1 = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
    $stmt1->bind_param("i", $order_id);
    $stmt1->execute();
    $result = $stmt1->get_result();
    $order = $result->fetch_assoc();

    $order_cost_value = '';
    if ($order['order_status'] === 'paid') {
        $order_cost_value = 0;
    } elseif ($order['order_status'] === 'not paid') {
        $order_cost_value = $order['order_cost'];
    }

    $user_id = $_GET['user_id'];
    // Query để lấy thông tin đơn hàng dựa trên user_id
    $stmt2 = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt2->bind_param("i", $user_id);
    $stmt2->execute();
    $result = $stmt2->get_result();
    $user = $result->fetch_assoc();

    // Lấy các giá trị từ bảng orders để điền vào các ô input
    $recipient_name = $user['user_name'];
    $recipient_phone = $order['user_phone'];
    $recipient_address = $order['user_address'];
    $recipient_ward = $order['user_ward'];
    $recipient_district = $order['user_district'];
    $recipient_province = $order['user_city'];

    // Query để lấy tất cả product_id và product_name cùng thuộc một order_id trong bảng order_items
    $stmt3 = $conn->prepare("SELECT product_id, product_name, product_quantity FROM order_items WHERE order_id = ?");
    $stmt3->bind_param("i", $order_id);
    $stmt3->execute();
    $result = $stmt3->get_result();
    $order_items = $result->fetch_all(MYSQLI_ASSOC);

    //Query để trả về tổng số lượng sản phẩm trong đơn hàng
    $stmt4 = $conn->prepare("SELECT SUM(product_quantity) AS total_quantity FROM order_items WHERE order_id = ?");
    $stmt4->bind_param("i", $order_id);
    $stmt4->execute();
    $result = $stmt4->get_result();
    $total_quantity = $result->fetch_assoc()['total_quantity'];
}
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Tables</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Custom styles for this template -->
    <link href="style.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .nguoi-gui,
        .nguoi-nhan,
        .thong-tin-don-hang {
            display: flex;
            justify-content: space-between;
            margin-bottom: 50px;
        }

        input[type='text'] {
            width: 450px;
            outline: 0.5px solid rgb(0, 70, 127);
            border-radius: 5px;
            border-width: 1px;
            padding: 3px 8px;
            font-size: 18px;
            font-weight: 600;
        }

        .info-gui,
        .address-gui,
        .address-nhan,
        .info-don-hang,
        .kich-co-don-hang {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 200px;
        }

        h4 {
            color: coral;
            font-weight: bold;
        }

        h6 {
            color: rgb(0, 70, 127)
        }

        p {
            color: coral
        }

        form {
            background-color: white;
        }

        .submit-container {
            text-align: center;
            margin-top: 20px;
        }

        .submit-container input[type="submit"] {
            width: 200px;
            padding: 10px;
            background-color: #4e73df;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Cartier Admin</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="chart.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span>Đơn hàng</span></a>
            </li>
            <hr class="sidebar-divider">

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link" href="products.php">
                    <i class="fa-solid fa-box"></i>
                    <span>Sản phẩm</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <li class="nav-item">
                <a class="nav-link" href="account.php">
                    <i class="fa-solid fa-user"></i>
                    <span>Tài khoản</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">


            <li class="nav-item">
                <a class="nav-link" href="add_new_product.php">
                    <i class="fa-solid fa-square-plus"></i>
                    <span>Thêm sản phẩm mới</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <li class="nav-item">
                <a class="nav-link" href="ware_house.php">
                    <i class="fa-solid fa-warehouse"></i>
                    <span>Kho hàng</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <li class="nav-item">
                <a class="nav-link" href="statistical.php">
                    <i class="fa-solid fa-square-poll-vertical"></i>
                    <span>Thống kê</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>

                    <!-- Topbar Search -->
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">

                            <div class="input-group-append">

                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">

                                        <div class="input-group-append">

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
                        <li>
                            <a class="nav-link px-3" href="logout.php?logout=1">Log out</a>
                        </li>
                    </ul>

                </nav>
                <!-- End of Topbar -->
                <form method="POST" action="create_delivery.php">
                    <!-- Begin Page Content -->
                    <div class="container">

                        <!--Create Order -->
                        <!--Người gửi hàng-->
                        <div class="nhan-gui">
                            <h4>Bên gửi</h4>
                            <div class="nguoi-gui">
                                <div class="info-gui">
                                    <h6>Tên cửa hàng</h6>
                                    <input type="text" name="sender_name" placeholder="Người gửi" value="Cartier Shop" />
                                    <h6>SDT</h6>
                                    <input type="text" name="sender_phone" placeholder="SDT" value="0865860262" />
                                    <h6 style="margin-top: 20px">Địa chỉ hoàn trả</h6>
                                    <p>0865860262</p>
                                    <p>255 Cầu Giấy, Phường Dịch Vọng, Quận Cầu Giấy, Hà Nội</p>
                                </div>
                                <div class="address-gui">
                                    <h6>Địa chỉ cửa hàng</h6>
                                    <input type="text" name="sender_address" placeholder="Địa chỉ" value="255 Cầu Giấy, Phường Dịch Vọng, Quận Cầu Giấy, Hà Nội, Vietnam" />
                                    <input type="text" name="sender_ward" placeholder="Phường/Xã" value="Phường Dịch Vọng" />
                                    <input type="text" name="sender_district" placeholder="Quận/Huyện" value="Quận Cầu Giấy" />
                                    <input type="text" name="sender_province" placeholder="Tỉnh/Thành phố" value="Hà Nội" />
                                </div>
                            </div>
                            <hr style="width:100%">
                            <!--Người nhận hàng-->
                            <h4>Bên nhận</h4>
                            <div class="nguoi-nhan">
                                <div class="info-nhan">
                                    <h6>Số điện thoại</h6>
                                    <input type="text" name="recipient_phone" placeholder="Sdt" value="<?php echo isset($recipient_phone) ? '0' . $recipient_phone : ''; ?>" />
                                    <h6>Họ tên</h6>
                                    <input type="text" name="recipient_name" placeholder="Người nhận" value="<?php echo isset($recipient_name) ? $recipient_name : ''; ?>" />
                                </div>
                                <div class="address-nhan">
                                    <h6>Địa chỉ</h6>
                                    <input type="text" name="recipient_address" placeholder="Địa chỉ" value="<?php echo isset($recipient_address) ? $recipient_address : ''; ?>" />
                                    <input type="text" name="recipient_ward" placeholder="Phường/Xã" value="<?php echo isset($recipient_ward) ? $recipient_ward : ''; ?>" />
                                    <input type="text" name="recipient_district" placeholder="Quận/Huyện" value="<?php echo isset($recipient_district) ? $recipient_district : ''; ?>" />
                                    <input type="text" name="recipient_province" placeholder="Tỉnh/Thành phố" value="<?php echo isset($recipient_province) ? $recipient_province : ''; ?>" />
                                </div>
                            </div>
                            <hr style="width:100%">
                        </div>
                        <h4>Thông tin đơn hàng</h4>
                        <div class="don-hang">
                            <!--Đơn hàng-->
                            <div class="thong-tin-don-hang">
                                <div class="info-don-hang">
                                    <h6>Mã đơn hàng:</h6>
                                    <input type="text" name="order_id" placeholder="Mã đơn hàng" value="<?php echo isset($order_id) ? $order_id : ''; ?>" />
                                    <h6>Thành tiền:</h6>
                                    <input type="text" name="order_cost" placeholder="Thành tiền" value="<?php echo $order_cost_value; ?>" />
                                    <h6>Dịch vụ vận chuyển:</h6>
                                    <input type="text" name="delivery_service" placeholder="Dịch vụ vận chuyển" value="Chuyển phát thương mại điện tử" />
                                    <h6>Ghi chú: </h6>
                                    <input type="text" name="note" placeholder="Ghi chú đơn hàng" />
                                </div>
                                <div class="kich-co-don-hang">
                                    <h6>Khối lượng:</h6>
                                    <input type="text" name="order_weight" placeholder="Tối đa: 30000 gram" autocomplete="off" required />
                                    <h6>Chiều dài:</h6>
                                    <input type="text" name="order_length" placeholder="Tối đa: 150 cm" />
                                    <h6>Chiều rộng:</h6>
                                    <input type="text" name="order_width" placeholder="Tối đa: 150 cm" />
                                    <h6>Chiều cao:</h6>
                                    <input type="text" name="order_height" placeholder="Tối đa: 150 cm" />
                                </div>
                            </div>
                            <hr style="width:100%">
                            <div class="chi-tiet-don-hang">
                                <h4>Chi tiết đơn hàng</h4>
                                <div style="display:flex; justify-content: space-between; width: 500px">
                                    <h6>Tên sản phẩm</h6>
                                    <h6>SL</h6>
                                </div>
                                <?php if (isset($order_items)) { ?>
                                    <?php foreach ($order_items as $item) { ?>
                                        <div style="display: flex; margin-bottom: 10px">
                                            <input type="text" name="product_names[]" value="<?php echo $item['product_name'] ?>" class="product-name" />
                                            <input style="width:50px; text-align:right" type="text" name="product_quantity[]" value="<?php echo $item['product_quantity'] ?>" class="product-quantity" />
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                            <!--End Create Order -->
                        </div>
                    </div>
                    <div class="submit-container">
                        <input type="submit" value="Tạo đơn vận chuyển" name="create_delivery">
                    </div>
                </form>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
</body>

</html>