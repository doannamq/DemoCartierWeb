<?php
session_start();
?>

<?php
include('../server/connection.php')
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

    <!-- Custom styles for this template -->
    <link href="style.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <style>
        #edit-product {
            display: flex;
            flex-direction: column;
        }

        h2 {
            color: black;
        }

        label {
            color: black;
        }

        input[type="text"] {
            width: 100%;
        }

        select {
            font-size: 18px;
            padding: 5px 0px;
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
                <div class="sidebar-brand-text mx-3">EShop Admin <sup>2</sup></div>
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
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Đơn hàng</span></a>
            </li>
            <hr class="sidebar-divider">

            <!-- <li class="nav-item">
                <a class="nav-link" href="add_new_delivery.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Tạo đơn hàng</span></a>
            </li> -->
            <!-- Divider -->
            <!-- <hr class="sidebar-divider"> -->

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link" href="products.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Sản phẩm</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <li class="nav-item">
                <a class="nav-link" href="account.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Tài khoản</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">


            <li class="nav-item">
                <a class="nav-link" href="add_new_product.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Thêm sản phẩm mới</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <li class="nav-item">
                <a class="nav-link" href="ware_house.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Kho hàng</span></a>
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

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Dashboard</h1>

                    <!-- Edit Product -->
                    <div id="edit-product" class="table-responsive">
                        <h2>Thêm sản phẩm</h2>
                        <div>
                            <form id="create-form" enctype="multipart/form-data" method="POST" action="create_product.php">
                                <p style="color: red;"><?php if (isset($_GET['error'])) {
                                                            echo $_GET['error'];
                                                        } ?> </p>
                                <div class="form-group mt-2">

                                    <input type="hidden" name="product_id" value="<?php echo $product['product_id'] ?>" />
                                    <label>Tên sản phẩm</label>
                                    <input type="text" class="form-control" id="product-name" name="title" placeholder="Tên sản phẩm" />
                                </div>
                                <div class="form-group mt-2">
                                    <label>Mô tả sản phẩm</label>
                                    <!-- <input type="text" class="form-control" id="producu-desc" name="description"
                                        placeholder="Mô tả sản phẩm" /> -->
                                    <textarea class="form-control" id="producu-desc" name="description" placeholder="Mô tả sản phẩm"></textarea>
                                </div>
                                <div class="form-group mt-2">
                                    <label>Giá</label>
                                    <input type="text" class="form-control" id="producu-price" name="price" placeholder="Giá" />
                                </div>
                                <div class="form-group mt-2">
                                    <label>Giảm giá</label>
                                    <input type="number" class="form-control" value="<?php echo $product['product_special_offer']; ?>" id="product-offer" name="offer" placeholder="Giảm giá %" />
                                </div>
                                <div class="form-group mt-2" style="display: flex; flex-direction:column">
                                    <label>Loại sản phẩm</label>
                                    <select class="form-select" required name="category">
                                        <option value="bracelet">Vòng tay</option>
                                        <option value="ring">Nhẫn</option>
                                        <option value="watches">Đồng hồ</option>
                                        <option value="necklace">Vòng cổ</option>
                                        <option value="bag">Túi xách</option>
                                        <option value="glasses">Kính mắt</option>
                                        <option value="perfume">Nước hoa</option>
                                    </select>
                                </div>
                                <div class="form-group mt-2">
                                    <label>Màu sắc</label>
                                    <input type="text" class="form-control" id="producu-color" name="color" placeholder="Màu sắc" required />
                                </div>
                                <div class="form-group mt-2">
                                    <label>Ảnh 1</label>
                                    <input type="file" class="form-control" id="image1" name="image1" placeholder="Ảnh 1" required />
                                </div>
                                <div class="form-group mt-2">
                                    <label>Ảnh 2</label>
                                    <input type="file" class="form-control" id="image2" name="image2" placeholder="Ảnh 2" />
                                </div>
                                <div class="form-group mt-2">
                                    <label>Ảnh 3</label>
                                    <input type="file" class="form-control" id="image3" name="image3" placeholder="Ảnh 3" />
                                </div>
                                <div class="form-group mt-2">
                                    <label>Ảnh 4</label>
                                    <input type="file" class="form-control" id="image4" name="image4" placeholder="Ảnh 4" />
                                </div>

                                <div class="form-group mt-3">
                                    <input type="submit" class="btn btn-primary" name="create_product" value="Thêm" />
                                </div>


                            </form>
                        </div>
                    </div>
                    <!--End Edit Product -->

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

</body>

</html>