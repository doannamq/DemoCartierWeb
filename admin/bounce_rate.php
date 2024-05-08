<?php
session_start();
?>

<?php
include('../server/connection.php')
?>

<?php
if (!isset($_SESSION['admin_logged_in'])) {
    header('location: login.php');
    exit;
}
?>

<?php
if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
    $page_no = $_GET['page_no'];
} else {
    $page_no = 1;
}

$stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM orders WHERE cancel_order = 'canceled'");
$stmt1->execute();
$stmt1->bind_result($total_records);
$stmt1->store_result();
$stmt1->fetch();

$total_records_per_page  = 10;

$offset = ($page_no - 1) * $total_records_per_page;

$previous_page = $page_no - 1;
$next_page = $page_no + 1;

$adjacent = "2";

$total_no_of_page = ceil($total_records / $total_records_per_page);

$stmt = $conn->prepare("SELECT o.*, u.user_name FROM orders o JOIN users u ON o.user_id = u.user_id WHERE o.cancel_order = 'canceled' ORDER BY o.order_id DESC LIMIT $offset, $total_records_per_page");
$stmt->execute();
$cancel_orders = $stmt->get_result();
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Custom styles for this template -->
    <link href="style.css" rel="stylesheet">

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
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
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
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <form action="index.php" method="POST">
                        <h1 class="h3 mb-2 text-gray-800">Dashboard</h1>
                    </form>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Đơn hàng đã hủy</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Mã đơn hàng</th>
                                            <th>Mã khách hàng</th>
                                            <th>Ngày đặt hàng</th>
                                            <th>SDT Khách hàng</th>
                                            <th>Tên khách hàng</th>
                                            <th>Địa chỉ Khách hàng</th>
                                            <th>Hủy</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php if ($cancel_orders->num_rows > 0) {
                                            foreach ($cancel_orders as $order) { ?>

                                                <tr>
                                                    <td><?php echo $order['order_id'] ?></td>
                                                    <td><?php echo $order['user_id'] ?></td>
                                                    <td><?php echo $order['order_date'] ?></td>
                                                    <td><?php echo '0' . $order['user_phone'] ?></td>
                                                    <td><?php echo $order['user_name'] ?></td>
                                                    <td><?php echo $order['user_address'] . ', ' . $order['user_ward'] . ', ' . $order['user_district'] . ', ' . $order['user_city'] ?>
                                                    </td>

                                                    <?php
                                                    if ($order['confirm'] == 'awaiting confirm' && $order['cancel_order'] != 'canceled') {
                                                    ?>
                                                        <td>
                                                            <a class="btn btn-info" href="confirm.php?order_id=<?php echo $order['order_id']; ?>&page_no=<?php echo $page_no; ?>">
                                                                Xác nhận</a>
                                                        </td>
                                                    <?php } elseif ($order['confirm'] == 'confirmed') { ?>
                                                        <td>
                                                            <button class="btn btn-success" disabled>Đã xác nhận</button>
                                                        </td>
                                                    <?php } else if ($order['cancel_order'] == "canceled") { ?>
                                                        <td>
                                                            <button class="btn btn-success" disabled>Đã hủy</button>
                                                        </td>
                                                    <?php  } ?>
                                                <?php } ?>
                                            <?php } ?>
                                    </tbody>
                                </table>

                                <nav aria-label="Page navigation example" style="display:<?php if (isset($_POST['search'])) {
                                                                                                echo "none";
                                                                                            } else {
                                                                                                echo "block";
                                                                                            } ?>">
                                    <ul class="pagination mt-5">

                                        <li class="page-item <?php if ($page_no <= 1) {
                                                                    echo 'disabled';
                                                                } ?>">
                                            <a class="page-link" href="<?php if ($page_no <= 1) {
                                                                            echo '#';
                                                                        } else {
                                                                            echo "?page_no=" . $previous_page;
                                                                        } ?>">Trước</a>
                                        </li>
                                        <li class="page-item"><a class="page-link" href="?page_no=1">1</a></li>
                                        <li class="page-item"><a class="page-link" href="?page_no=2">2</a></li>
                                        <?php if ($page_no >= 3) { ?>
                                            <li class="page-item"><a class="page-link" href="#">...</a></li>
                                            <li class="page-item"><a class="page-link" href="<?php echo "?page_no=" . $page_no; ?>"><?php echo $page_no; ?></a>
                                            </li>
                                        <?php } ?>

                                        <li class="page-item <?php if ($page_no >= $total_no_of_page) {
                                                                    echo 'disable';
                                                                } ?>">
                                            <a class="page-link" href="<?php if ($page_no >= $total_no_of_page) {
                                                                            echo '#';
                                                                        } else {
                                                                            echo "?page_no=" . ($page_no + 1);
                                                                        } ?>">Tiếp theo</a>
                                        </li>
                                    </ul>
                                </nav>

                            </div>
                        </div>
                    </div>

                </div>
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
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
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

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

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