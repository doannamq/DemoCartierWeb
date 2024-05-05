<?php
include('server/connection.php');
$new_key = uniqid() . '_' . uniqid() . '_' . uniqid() . '_' . gethostbyaddr($_SERVER['REMOTE_ADDR']);
$md_key = md5($new_key);
if (!isset($_COOKIE['vistor_id'])) {
    $check = $conn->prepare("SELECT * FROM unique_visitors WHERE `unique_id` = ?");
    $check->bind_param("s", $md_key);
    $check->execute();
    $check_result = $check->get_result();
    if ($check_result->num_rows > 0) {
    } else {
        $insert = $conn->prepare("INSERT INTO `unique_visitors`(`unique_id`) VALUES (?)");
        $insert->bind_param("s", $md_key);
        $insert->execute();
        setcookie("vistor_id", $md_key, time() + (86400 * 30), "/");
    }
}
?>

<?php include('layouts/header.php'); ?>

<!--Home-->
<section id="home">
    <div class="container">
        <video id="video" controls autoplay loop muted>
            <source src="assets\imgs\video.mp4" type="video/mp4">
        </video>
        <div class="trinity">
            <h3>LIÊN KẾT BỞI TRINITY</h3>
            <p>Sự đa dạng, tình yêu, tình bạn, Trinity tượng trưng cho điều này và nhiều hơn thế nữa.</p>
            <a href="shop.php">Khám phá</a>
        </div>
    </div>
</section>

<!--Featured-->
<section id="featured">
    <div class="container text-center mt-5 py-5">
        <h3>Sản phẩm nổi bật</h3>
        <hr class="mx-auto">
        <p>Tại đây bạn có thể tham khảo những sản phẩm nổi bật của chúng tôi</p>
    </div>
    <div class="row mx-auto container-fluid">

        <?php include('server/get_featured_products.php'); ?>


        <?php while ($row = $featured_products->fetch_assoc()) { ?>


            <div class="product text-center col-lg-3 col-md-4 col-sm-12">
                <img class="img-fluid mb-3" style="height:400px; width:400px" src="assets/imgs/<?php echo $row['product_image']; ?>" />
                <div class="star">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                </div>
                <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
                <!-- <h4 class="p-price">$ <?php echo number_format($row['product_price'], 2, '.', ','); ?></h4> -->
                <?php if ($row['product_special_offer'] > 0) { ?>
                    <h5 class="p-price"><del>$ <?php echo number_format($row['product_price'], 2, '.', ','); ?></del></h5>
                    <h4 class="p-price">$
                        <?php echo number_format($row['product_price'] - $row['product_price'] * ($row['product_special_offer'] / 100), 2, '.', ','); ?>
                    </h4>
                <?php } else { ?>
                    <h4 class="p-price">$ <?php echo number_format($row['product_price'], 2, '.', ','); ?></h4>
                <?php } ?>
                <a href="<?php echo "single_product.php?product_id=" . $row['product_id']; ?>">
                    <button class="buy-btn">Mua ngay</button>
                </a>
            </div>

        <?php } ?>
    </div>
</section>

<!--Sản phẩm giảm giá-->
<section id="watches" class="my-5 pb-5">
    <div class="container text-center">
        <h3>Sản phẩm giảm giá</h3>
        <hr class="mx-auto">
    </div>
    <div class="row mx-auto container-fluid">

        <?php include('server/get_special_offer.php'); ?>

        <?php while ($row = $product_special_offer->fetch_assoc()) { ?>

            <div class="product text-center col-lg-3 col-md-4 col-sm-12" style="position:relative">
                <div id="product-special-offer">
                    <h4><?php echo $row['product_special_offer'] ?> %</h4>
                </div>
                <img class="img-fluid mb-3" style="height:400px; width:400px" src="assets/imgs/<?php echo $row['product_image']; ?>" />
                <div class="star">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                </div>
                <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
                <!-- <h4 class="p-price">$ <?php echo number_format($row['product_price'], 2, '.', ','); ?></h4> -->
                <?php if ($row['product_special_offer'] > 0) { ?>
                    <h5 class="p-price"><del>$ <?php echo number_format($row['product_price'], 2, '.', ','); ?></del></h5>
                    <h4 class="p-price">$
                        <?php echo number_format($row['product_price'] - $row['product_price'] * ($row['product_special_offer'] / 100), 2, '.', ','); ?>
                    </h4>
                <?php } else { ?>
                    <h4 class="p-price">$ <?php echo number_format($row['product_price'], 2, '.', ','); ?></h4>
                <?php } ?>
                <a href="<?php echo "single_product.php?product_id=" . $row['product_id']; ?>">
                    <button class="buy-btn">Mua ngay</button>
                </a>
            </div>

        <?php } ?>

    </div>
</section>



<!--Banner-->
<section id="banner" class="my-5 py-5">
    <div class="container text-center mt-5 py-5">
        <h4>Kiểm tra ngay</h4>
        <h1>Bộ sưu tập trang sức <br>Giảm giá lên đến 15%</h1>
        <button class="text-uppercase">
            <a style="text-decoration: none; color: white" href="shop.php">Mua ngay</a>
        </button>
    </div>
</section>

<!--Ring-->
<section id="watches">
    <div class="container text-center mt-5 py-5">
        <h3>Nhẫn</h3>
        <hr class="mx-auto">
        <p>Tại đây bạn có thể xem nhẫn tuyệt vời</p>
    </div>
    <div class="row mx-auto container-fluid">

        <?php include('server/get_rings.php'); ?>

        <?php while ($row = $coats->fetch_assoc()) { ?>

            <div class="product text-center col-lg-3 col-md-4 col-sm-12">
                <img class="img-fluid mb-3" style="height:400px; width:400px" src="assets/imgs/<?php echo $row['product_image']; ?>" />
                <div class="star">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                </div>
                <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
                <!-- <h4 class="p-price">$ <?php echo number_format($row['product_price'], 2, '.', ','); ?></h4> -->
                <?php if ($row['product_special_offer'] > 0) { ?>
                    <h5 class="p-price"><del>$ <?php echo number_format($row['product_price'], 2, '.', ','); ?></del></h5>
                    <h4 class="p-price">$
                        <?php echo number_format($row['product_price'] - $row['product_price'] * ($row['product_special_offer'] / 100), 2, '.', ','); ?>
                    </h4>
                <?php } else { ?>
                    <h4 class="p-price">$ <?php echo number_format($row['product_price'], 2, '.', ','); ?></h4>
                <?php } ?>
                <a href="<?php echo "single_product.php?product_id=" . $row['product_id']; ?>">
                    <button class="buy-btn">Mua ngay</button>
                </a>
            </div>

        <?php } ?>

    </div>
</section>


<!--BestWatch-->
<section id="watches">
    <div class="container text-center">
        <h3>Đồng hồ tốt nhất</h3>
        <hr class="mx-auto">
        <p>Tại đây bạn có thể kiểm tra đồng hồ của chúng tôi</p>
    </div>
    <div class="row mx-auto container-fluid">

        <?php include('server/get_watches.php'); ?>

        <?php while ($row = $watches->fetch_assoc()) { ?>

            <div class="product text-center col-lg-3 col-md-4 col-sm-12">
                <img class="img-fluid mb-3" style="height:400px; width:400px" src="assets/imgs/<?php echo $row['product_image']; ?>" />
                <div class="star">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                </div>
                <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
                <!-- <h4 class="p-price">$ <?php echo number_format($row['product_price'], 2, '.', ','); ?></h4> -->
                <?php if ($row['product_special_offer'] > 0) { ?>
                    <h5 class="p-price"><del>$ <?php echo number_format($row['product_price'], 2, '.', ','); ?></del></h5>
                    <h4 class="p-price">$
                        <?php echo number_format($row['product_price'] - $row['product_price'] * ($row['product_special_offer'] / 100), 2, '.', ','); ?>
                    </h4>
                <?php } else { ?>
                    <h4 class="p-price">$ <?php echo number_format($row['product_price'], 2, '.', ','); ?></h4>
                <?php } ?>
                <a href="<?php echo "single_product.php?product_id=" . $row['product_id']; ?>">
                    <button class="buy-btn">Mua ngay</button>
                </a>
            </div>

        <?php } ?>

    </div>
</section>

<!--Bag-->
<section id="shoes" class="my-5 pb-5">
    <div class="container text-center">
        <h3>Túi xách</h3>
        <hr class="mx-auto">
        <p>Tại đây bạn có thể kiểm tra túi xách của chúng tôi</p>
    </div>
    <div class="row mx-auto container-fluid">

        <?php include('server/get_bag.php'); ?>

        <?php while ($row = $shoes->fetch_assoc()) { ?>

            <div class="product text-center col-lg-3 col-md-4 col-sm-12">
                <img class="img-fluid mb-3" style="height:400px; width:400px" src="assets/imgs/<?php echo $row['product_image']; ?>" />
                <div class="star">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                </div>
                <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
                <!-- <h4 class="p-price">$ <?php echo number_format($row['product_price'], 2, '.', ','); ?></h4> -->
                <?php if ($row['product_special_offer'] > 0) { ?>
                    <h5 class="p-price"><del>$ <?php echo number_format($row['product_price'], 2, '.', ','); ?></del></h5>
                    <h4 class="p-price">$
                        <?php echo number_format($row['product_price'] - $row['product_price'] * ($row['product_special_offer'] / 100), 2, '.', ','); ?>
                    </h4>
                <?php } else { ?>
                    <h4 class="p-price">$ <?php echo number_format($row['product_price'], 2, '.', ','); ?></h4>
                <?php } ?>
                <a href="<?php echo "single_product.php?product_id=" . $row['product_id']; ?>">
                    <button class="buy-btn">Mua ngay</button>
                </a>
            </div>

        <?php } ?>

    </div>
</section>

<!--Banner-->
<div style="display: flex; flex-direction: column; align-items: center; margin-bottom: 20px">
    <h3>PANTHÈRE C DE CARTIER</h3>
    <p>The Maison's emblematic animal leaves traces of her claws on the latest handbag designs.</p>
    <a href="shop.php" id="shop-now">Shop now</a>
    <div class="line"></div>
</div>
<section id="bracelet">
    <img src="assets/imgs/bracelet.jpg" />
</section>

<div class="cartier-universe">
    <h3>Enter the Cartier universe</h3>
    <p>Follow @Cartier to stay up to date with the latest news and collections.</p>
</div>

<div class="container1">
    <div class="slide">
        <div class="item" style="background-image: url(./assets/imgs/bst2.jpg)">
            <div class="content">
                <div class="name">DRIVE DE CARTIER</div>
                <div class="des">Trong năm 2016, Cartier, bậc thầy của các thiết kế vỏ đẹp mắt, giới thiệu một chiếc
                    đồng hồ thời trang mới dành cho nam giới; Cartier Drive. Được cung cấp bằng chất liệu vàng hoặc
                    thép, đây là dòng đồng hồ trở thành xu hướng ngay tức thời. Dòng đồng hồ thời trang nam tính Drive
                    de Cartier lấy cảm hứng từ thế giới của những chiếc xe thể thao đã được giới thiệu tại triển lãm
                    SIHH ở Geneva vào tháng 1 năm 2016.</div>
                <button>Xem thêm</button>
            </div>
        </div>

        <div class="item" style="background-image: url(./assets/imgs/bst5.jpg)">
            <div class="content">
                <div class="name">BALLON BLEU DE CARTIER</div>
                <div class="des">"Ballon Bleu" - quả bóng màu xanh - là một cái tên đặc biệt cho chiếc đồng hồ tinh tế
                    khi quan sát núm điều chỉnh cabochon sapphire màu xanh được đệm vào phía bên phải của đồng hồ. Hơn
                    nữa, điểm nhấn độc đáo này của chiếc đồng hồ tạo thêm một nét tinh tế cho bất kỳ cổ tay nào lựa chọn
                    Ballon Bleu.</div>
                <button>Xem thêm</button>
            </div>
        </div>

        <div class="item" style="background-image: url(./assets/imgs/bst3.jpg)">
            <div class="content">
                <div class="name">CARTIER PANTHÈRE</div>
                <div class="des">Cartier Panthère: Con báo là biểu tượng vượt thời gian. Nó là một sinh vật hoang dã và
                    sang trọng, hoàn toàn phù hợp cho hình ảnh đặc trưng của chiếc đồng hồ Panthère và các đồ trang sức
                    được tạo ra bởi thương hiệu Cartier. Không có gì đáng ngạc nhiên khi chiếc đồng hồ Panthère, đặc
                    biệt, vẫn có nhu cầu cao sau khi ngừng sản xuất. Hơn nữa, động vật hoang dã liên tục là nguồn cảm
                    hứng vô tận cho đồng hồ Cartier mới và tăng thêm sức hấp dẫn.</div>
                <button>Xem thêm</button>
            </div>
        </div>

        <div class="item" style="background-image: url(./assets/imgs/bst4.jpg)">
            <div class="content">
                <div class="name"> CARTIER TANK</div>
                <div class="des">Cartier Tank - một huyền thoại thực sự trong thế giới đồng hồ sang trọng. Với hình dạng
                    hình chữ nhật, chiếc đồng hồ cổ điển tìm cảm hứng ở dạng xe tăng quân sự - một yếu tố được thể hiện
                    rõ ràng trong tên của mô hình. </div>
                <button>Xem thêm</button>
            </div>
        </div>

        <div class="item" style="background-image: url(./assets/imgs/bst1.jpg)">
            <div class="content">
                <div class="name">CARTIER SANTOS</div>
                <div class="des">Đồng hồ Cartier Santos gây ấn tượng với một lịch sử đầy quyến rũ: Đó là chiếc đồng hồ
                    đeo tay đầu tiên được tạo ra cho nam giới cũng như chiếc đồng hồ dành cho phi công đầu tiên được sản
                    xuất bởi Cartier.</div>
                <button>Xem thêm</button>
            </div>
        </div>


    </div>

    <div class="button">
        <button class="prev"><i class="fa-solid fa-arrow-left"></i></button>
        <button class="next"><i class="fa-solid fa-arrow-right"></i></button>
    </div>
</div>

<div class="grid-module">
    <div class="grid">
        <div class="grid-module-item">
            <img src="assets/imgs/icon1.png" />
            <p>COMPLIMENTARY DELIVERY</p>
        </div>

        <div class="grid-module-item">
            <img src="assets/imgs/icon2.png" />
            <p>EASY RETURN OR EXCHANGE</p>
        </div>

        <div class="grid-module-item">
            <img src="assets/imgs/icon3.png" />
            <p>FREE GIFT WRAPPING</p>
        </div>
    </div>
</div>
<?php include('layouts/footer.php') ?>