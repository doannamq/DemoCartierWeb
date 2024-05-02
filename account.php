<?php

session_start();
include('server/connection.php');

if (!isset($_SESSION['logged_in'])) {
    header('location: login.php');
    exit;
}

if (isset($_GET['logout'])) {
    if (isset($_SESSION['logged_in'])) {
        unset($_SESSION['logged_in']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_email']);
        header('location: login.php');
        exit;
    }
}

if (isset($_POST['change_password'])) {

    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $user_email = $_SESSION['user_email'];

    //if password dont match
    if ($password !== $confirmPassword) {
        header('location: account.php?error=password dont match');

        //if password is less than 6 characters
    } else if (strlen($password) < 6) {
        header('location: account.php?error=password must be at least 6 characters');

        //no errors
    } else {
        $stmt = $conn->prepare("UPDATE users SET user_password = ? WHERE user_email = ?");
        $stmt->bind_param('ss', md5($password), $user_email);

        if ($stmt->execute()) {
            header('location: account.php?message=password has been updated successfully');
        } else {
            header('location: account.php?error=coud not update password');
        }
    }
}

//get orders 
if (isset($_SESSION['logged_in'])) {

    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ");

    $stmt->bind_param('i', $user_id);

    $stmt->execute();

    $orders = $stmt->get_result();
}

?>



<?php include('layouts/header.php'); ?>

<!--Account-->
<section class="my-5 py-5">
    <div class="row container mx-auto">

        <?php if (isset($_GET['payment_message'])) { ?>
            <p class="mt-5 text-center" style="color: #3FD168;"><?php echo $_GET['payment_message']; ?></p>
        <?php } ?>

        <div class="text-center mt-3 pt-5 col-lg-5 col-md-12 col-sm-12">
            <p class="text-center" style="color: #3FD168"><?php if (isset($_GET['register_success'])) {
                                                                echo $_GET['register_success'];
                                                            } ?>
            </p>
            <p class="text-center" style="color: #3FD168"><?php if (isset($_GET['login_success'])) {
                                                                echo $_GET['login_success'];
                                                            } ?>
            </p>
            <h3 class="font-weight-bold">Thông tin tài khoản</h3>
            <hr class="mx-auto">
            <div class="account-info">
                <p>Tên: <span><?php if (isset($_SESSION['user_name'])) {
                                    echo $_SESSION['user_name'];
                                } ?></span></p>
                <p>Email: <span><?php if (isset($_SESSION['user_email'])) {
                                    echo $_SESSION['user_email'];
                                } ?></span>
                </p>
                <p><a href="#orders" id="orders-btn">Đơn đặt hàng</a></p>
                <p><a href="account.php?logout=1" id="logout-btn">Đăng xuất</a></p>
            </div>
        </div>

        <div class="col-lg-6 col-md-12 col-sm-12">
            <form id="account-form" method="POST" action="account.php">
                <p class="text-center" style="color: red"><?php if (isset($_GET['error'])) {
                                                                echo $_GET['error'];
                                                            } ?>
                </p>
                <p class="text-center" style="color: #3FD168">
                    <?php if (isset($_GET['message'])) {
                        echo $_GET['message'];
                    } ?></p>
                <h3>Đổi mật khẩu</h3>
                <hr class="mx-auto">
                <div class="form-group">
                    <label>Mật khẩu</label>
                    <input type="password" class="form-control" id="account-password" name="password" placeholder="Mật khẩu" required />
                </div>
                <div class="form-group">
                    <label>Xác nhận mật khẩu</label>
                    <input type="password" class="form-control" id="account-password-confirm" name="confirmPassword" placeholder="Xác nhận mật khẩu" required />
                </div>
                <div class="form-group">
                    <input type="submit" value="Đổi mật khẩu" name="change_password" class="btn" id="change-pass-btn" />
                </div>
            </form>
        </div>
    </div>
</section>


<!--Orders-->
<section id="orders" class="orders container my-5 py-5">
    <div class="container mt-2">
        <h2 class="font-weight-bold text-center">Đơn đặt hàng</h2>
        <hr class="mx-auto">
    </div>
    <?php
    if ($orders->num_rows == 0) {
        echo "<p class='text-center'>Bạn chưa có đơn hàng nào</p>";
        echo "<div class='text-center'>";
        echo "<button class='btn btn-success'><a href='shop.php' style='color: white; text-decoration: none; padding: 50px; font-weight: bold'>Mua ngay</a></button>";
        echo "</div>";
    } else {
    ?>
        <table>
            <tr>
                <th>Mã đơn hàng</th>
                <th>Giá hóa đơn</th>
                <th>Thanh toán</th>
                <th>Vận chuyển</th>
                <th>Ngày đặt hàng</th>
                <th>Chi tiết đơn hàng</th>
            </tr>

            <?php while ($row = $orders->fetch_assoc()) { ?>

                <tr>
                    <td>
                        <div class="product-info">
                            <!-- <img src="assets/imgs/featured1.png" /> -->
                            <div>
                                <p class="mt-3"><?php echo $row['order_id']; ?></p>
                            </div>
                        </div>
                    </td>

                    <td>
                        <span><?php echo number_format($row['order_cost'], 2, '.', ',') ?></span>
                    </td>

                    <?php if ($row['order_status'] == 'paid') { ?>
                        <td>
                            <span>Đã thanh toán</span>
                        </td>
                    <?php } else { ?>
                        <td>
                            <span>COD</span>
                        </td>
                    <?php } ?>

                    <!-- <td>
                        <span><?php echo $row['delivery_status'] ?></span>
                    </td> -->
                    <?php if ($row['delivery_status'] == 'awaiting pickup') { ?>
                        <td>
                            <span>Chờ lấy hàng</span>
                        </td>
                    <?php } else { ?>
                        <td></td>
                    <?php } ?>

                    <td>
                        <span><?php echo $row['order_date'] ?></span>
                    </td>

                    <td>
                        <form method="POST" action="order_details.php">
                            <input type="hidden" value="<?php echo $row['order_status']; ?>" name="order_status" />
                            <input type="hidden" value="<?php echo $row['order_id']; ?>" name="order_id" />
                            <input class="btn order-details-btn" name="order_details_btn" type="submit" value="Chi tiết" />
                        </form>
                    </td>
                </tr>

            <?php } ?>

        </table>
    <?php } ?>
</section>

<?php include('layouts/footer.php'); ?>