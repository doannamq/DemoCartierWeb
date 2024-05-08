<?php

session_start();

include('../server/connection.php');

$stmt1 = $conn->prepare("SELECT COUNT(*) AS total_orders FROM orders WHERE order_date >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK);");
$stmt1->execute();
$new_orders = $stmt1->get_result();
$row1 = $new_orders->fetch_assoc();
$total_orders_week = $row1['total_orders'];

$stmt2 = $conn->prepare("SELECT COUNT(*) AS user FROM users");
$stmt2->execute();
$users = $stmt2->get_result();
$row2 = $users->fetch_assoc();
$total_users = $row2['user'];

$stmt3 = $conn->prepare("SELECT COUNT(*) AS visitors FROM unique_visitors");
$stmt3->execute();
$visitors = $stmt3->get_result();
$row3 = $visitors->fetch_assoc();
$total_visitors = $row3['visitors'];

$stmt4 = $conn->prepare("SELECT COUNT(*) AS cancel_orders FROM orders WHERE cancel_order = 'canceled'");
$stmt4->execute();
$cancel_orders = $stmt4->get_result();
$row4 = $cancel_orders->fetch_assoc();
$total_cancel_orders = $row4['cancel_orders'];

$stmt_total_orders = $conn->prepare("SELECT COUNT(*) AS total_orders FROM orders");
$stmt_total_orders->execute();
$total_orders_result = $stmt_total_orders->get_result();
$row5 = $total_orders_result->fetch_assoc();
$total_orders = $row5['total_orders'];

if ($total_orders > 0) {
    $cancel_percentage = ($total_cancel_orders / $total_orders) * 100;
    $cancel_percentage = round($cancel_percentage, 2);
} else {
    $cancel_percentage = 0;
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

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
        integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Custom styles for this template -->
    <link href="style.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <title>Doanh số bán hàng theo tháng và năm</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <script>
    var data;
    var chart;
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '../data_chart.php', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            data = JSON.parse(xhr.responseText);
            createChart();
        }
    };
    xhr.send();

    var monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    function createChart() {
        var years = Object.keys(data);
        var datasets = years.map(function(year) {
            return {
                label: 'Năm ' + year,
                data: data[year],
                borderColor: getRandomColor(),
                backgroundColor: getRandomColor(0.2),
                fill: false
            };
        });

        var ctx = document.getElementById('salesChart').getContext('2d');
        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthNames,
                datasets: datasets
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value, index, values) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    function getRandomColor(alpha = 1) {
        var r = Math.floor(Math.random() * 256);
        var g = Math.floor(Math.random() * 256);
        var b = Math.floor(Math.random() * 256);
        // var r = 67;
        // var g = 166;
        // var b = 246;
        return 'rgba(' + r + ',' + g + ',' + b + ',' + alpha + ')';
    }
    </script>
</head>

<!-- <body>
    <canvas id="salesChart"></canvas>
</body> -->

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
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>

                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">

                            <div class="input-group-append">

                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
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
                    <form action="products.php" method="POST">
                        <h1 class="h3 mb-2 text-gray-800">Dashboard</h1>
                    </form>
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Doanh số bán hàng theo tháng</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="salesChart"></canvas>
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Số lượng bán ra của loại sản phẩm</h6>
                            </div>
                            <div class="card-body donut-chart-container">
                                <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                            </div>
                            <div style="display: flex; justify-content:space-between; margin-top: 20px">
                                <div class="new-orders">
                                    <div class="new-orders-title">
                                        <i class="fa-solid fa-bag-shopping"></i>
                                        <p>Số lượng đơn hàng mới</p>
                                    </div>
                                    <h2><?php echo $total_orders_week; ?></h2>
                                </div>
                                <div class="bounce-rate">
                                    <div class="bounce-rate-title">
                                        <i class="fa-solid fa-chart-simple"></i>
                                        <p>Tỷ lệ hủy đơn</p>
                                    </div>
                                    <h2><?php echo $cancel_percentage . "%"; ?></h2>
                                    <div class="more-info"><a href="bounce_rate.php">Xem thêm<i
                                                class="fa-solid fa-circle-arrow-right"></i></a></div>
                                </div>
                            </div>
                            <div style="display: flex; justify-content:space-between; margin-top: 20px">
                                <div class="user-registrations">
                                    <div class="user-registrations-title">
                                        <i class="fa-solid fa-user-plus"></i>
                                        <p>Số lượng người đăng ký</p>
                                    </div>
                                    <h2><?php echo $total_users; ?></h2>
                                </div>
                                <div class="unique-visitors">
                                    <div class="unique-visitors-title">
                                        <i class="fa-solid fa-user-check"></i>
                                        <p>Khách ghé thăm</p>
                                    </div>
                                    <h2><?php echo $total_visitors ?></h2>
                                </div>
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
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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
    window.onload = function() {
        // Gửi yêu cầu AJAX để lấy dữ liệu donut chart
        var xhrDonut = new XMLHttpRequest();
        xhrDonut.open('GET', '../data_donut_chart.php', true);
        xhrDonut.onreadystatechange = function() {
            if (xhrDonut.readyState === 4 && xhrDonut.status === 200) {
                // Xử lý dữ liệu trả về từ data_donut_chart.php
                var dataDonut = JSON.parse(xhrDonut.responseText);

                // Tạo biểu đồ Doughnut Chart
                var chartDonut = new CanvasJS.Chart("chartContainer", {
                    animationEnabled: true,
                    title: {
                        text: "Danh mục sản phẩm"
                    },
                    data: [{
                        type: "doughnut",
                        startAngle: 60,
                        indexLabelFontSize: 17,
                        indexLabel: "{label} - #percent%",
                        toolTipContent: "<b>{label}:</b> {y} (#percent%)",
                        dataPoints: dataDonut
                    }]
                });
                chartDonut.render();
            }
        };
        xhrDonut.send();
    }
    </script>
</body>


</html>