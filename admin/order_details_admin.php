<?php
include('../server/connection.php')
?>

<?php
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    $stmt1 = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
    $stmt1->bind_param('i', $order_id);
    $stmt1->execute();
    $orders = $stmt1->get_result();
}

$stmt3 = $conn->prepare("SELECT product_id, product_name, product_quantity, product_price FROM order_items WHERE order_id = ?");
$stmt3->bind_param("i", $order_id);
$stmt3->execute();
$result = $stmt3->get_result();
$order_items = $result->fetch_all(MYSQLI_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>EShop Admin 2</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-refe4rer" />

    <!-- Custom styles for this template -->
    <link href="style.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/style.css" />

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <style>
        form {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 20% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
            height: 200px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        p {
            color: black;
            font-size: 22px;
            text-align: center;
        }

        .button {
            display: flex;
            flex-direction: row;
            justify-content: space-around;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        #confirmDeleteBtn,
        #cancelDeleteBtn {
            padding: 15px 50px;
            border-radius: 5px;
            border: 1px solid white;
            font-size: 19px;
            font-weight: bold;
            color: white
        }

        #confirmDeleteBtn {
            background-color: rgb(105, 219, 103);
        }

        #confirmDeleteBtn:hover {
            background-color: rgb(119, 229, 117);
        }

        #cancelDeleteBtn {
            background-color: rgb(229, 90, 84);
        }

        #cancelDeleteBtn:hover {
            background-color: rgb(240, 105, 100);
        }

        #deleteSuccessMessage {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        #deleteSuccessMessage .modal-content {
            background-color: #fefefe;
            margin: 20% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
        }

        #deleteSuccessMessage p {
            color: black;
            font-size: 22px;
            text-align: center;
        }

        #deleteSuccessMessage .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        #deleteSuccessMessage .close:hover,
        #deleteSuccessMessage .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        form {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .btn-outline-success a {
            text-decoration: none;
        }

        .btn-primary a {
            color: white;
            text-decoration: none;
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
                <form method="POST" action="statistical.php">
                    <a class="nav-link" href="statistical.php">
                        <i class="fa-solid fa-square-poll-vertical"></i>
                        <span>Thống kê</span></a>
                </form>
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
                            <a class="nav-link px-3" href="logout.php?logout=1">Đăng xuất</a>
                        </li>
                    </ul>

                </nav>

                <div style="display: flex; justify-content: space-around">
                    <?php foreach ($orders as $order) { ?>
                        <div style="width:400px; display: flex; flex-direction: column; justify-content: space-between">
                            <div class="database-column">
                                <h4>Mã đơn hàng:</h4>
                                <?php echo $order['order_id'] ?>
                            </div>
                            <div class="database-column">
                                <h4>Giá đơn hàng:</h4>
                                <?php echo $order['order_cost'] ?>
                            </div>
                            <div class="database-column">
                                <h4>Thanh toán:</h4>
                                <?php echo $order['order_status'] ?>
                            </div>
                            <div class="database-column">
                                <h4>Ngày đặt hàng:</h4>
                                <?php echo $order['order_date'] ?>
                            </div>
                        </div>
                        <div style="width:400px; display: flex; flex-direction: column; justify-content: space-between">
                            <div class="database-column">
                                <h4>SDT khách hàng:</h4>
                                <?php echo "0" . $order['user_phone'] ?>
                            </div>
                            <div class="database-column">
                                <h4>Địa chỉ khách hàng:</h4>
                                <?php echo $order['user_address'] ?>
                            </div>
                            <div class="database-column">
                                <h4>Phường/Xã:</h4>
                                <?php echo $order['user_ward'] ?>
                            </div>
                            <div class="database-column">
                                <h4>Quận/Huyện:</h4>
                                <?php echo $order['user_district'] ?>
                            </div>
                            <div class="database-column">
                                <h4>Tỉnh/Thành Phố:</h4>
                                <?php echo $order['user_city'] ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div style="width: 77%; height:1px; background-color: black; margin:20px 170px">
                </div>
                <div>
                    <h4 style="margin-left:170px; color: black">Chi tiết đơn hàng</h4>
                    <table style="margin-left:170px; width:800px">
                        <thead>
                            <tr>
                                <th>Tên Sản Phẩm</th>
                                <th>Giá sản phẩm</th>
                                <th>Số Lượng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($order_items)) { ?>
                                <?php foreach ($order_items as $item) { ?>
                                    <tr>
                                        <td><?php echo $item['product_name'] ?></td>
                                        <td><?php echo $item['product_price'] ?></td>
                                        <td><?php echo $item['product_quantity'] ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="button-admin">
                    <td><a class="btn btn-danger" href="javascript:void(0);" onclick="confirmDelete(<?php echo $order['order_id']; ?>)">Xóa</a>
                    </td>
                </div>
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
    <!-- Modal Popup -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close" id="dong">&times;</span>
            <p>Bạn có muốn xóa đơn hàng này không?</p>
            <div class="button">
                <button id="confirmDeleteBtn">Xóa</button>
                <button id="cancelDeleteBtn">Hủy</button>
            </div>
        </div>
    </div>
    <script>
        // JavaScript để điều khiển modal popup
        var modal = document.getElementById('deleteModal');
        var confirmBtn = document.getElementById("confirmDeleteBtn");
        var cancelBtn = document.getElementById("cancelDeleteBtn");

        // Mở modal khi nhấn nút Xóa
        function confirmDelete(orderId) {
            modal.style.display = "block";

            // Xác nhận xóa
            confirmBtn.onclick = function() {
                // Thay đổi nội dung của modal thành "Xóa thành công"
                var modalContent = document.querySelector("#deleteModal .modal-content");
                modalContent.innerHTML = '<span class="close" id="dong">&times;</span><p>Xóa thành công</p>';
                // Tự đóng modal sau 2 giây
                setTimeout(function() {
                    modal.style.display = "none";
                    window.location.href = 'delete_order.php?order_id=' + orderId;
                }, 1000);
            }

            // Hủy xóa
            cancelBtn.onclick = function() {
                modal.style.display = "none";
            }
        }

        // Đóng modal khi nhấn vào nút đóng
        var closeBtn = document.getElementById("dong");
        closeBtn.onclick = function() {
            modal.style.display = "none";
        }

        // Đóng modal khi nhấn ra ngoài modal
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>

</html>