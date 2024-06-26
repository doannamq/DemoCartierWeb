<?php

include('server/connection.php');

session_start();
if (isset($_SESSION['user_name']) && isset($_SESSION['user_id'])) {
    $user_name = $_SESSION['user_name'];
    $user_id = $_SESSION['user_id'];
}

if (isset($_GET['product_id'])) {

    $product_id = $_GET["product_id"];

    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);

    $stmt->execute();

    $product = $stmt->get_result();

    //no product id was given
} else {

    header(('location: index.php'));
    exit;
}

$size_array = ['Select size', 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64];

//get all comments of product
if (isset($_GET['product_id'])) {

    $product_id = $_GET["product_id"];

    $stmt1 = $conn->prepare("SELECT * FROM comments WHERE product_id = ? ORDER BY comment_id DESC");

    $stmt1->bind_param("i", $product_id);

    $stmt1->execute();

    $comments = $stmt1->get_result();
}
?>

<?php include('layouts/header.php'); ?>

<!--Single product-->
<section class="container single-product my-5  pt-5">
    <div class="row mt-5">

        <?php while ($row = $product->fetch_assoc()) { ?>
        <div class="col-lg-5 col-md-6 col-sm-12">
            <img class="img-fluid w-100 pb-1" src="assets/imgs/<?php echo $row['product_image'] ?>" id="mainImg" />
            <div class="small-img-group">
                <div class="small-img-col">
                    <img src="assets/imgs/<?php echo $row['product_image'] ?>" width="100%" class="small-img" />
                </div>
                <div class="small-img-col">
                    <img src="assets/imgs/<?php echo $row['product_image2'] ?>" width="100%" class="small-img" />
                </div>
                <div class="small-img-col">
                    <img src="assets/imgs/<?php echo $row['product_image3'] ?>" width="100%" class="small-img" />
                </div>
                <div class="small-img-col">
                    <img src="assets/imgs/<?php echo $row['product_image4'] ?>" width="100%" class="small-img" />
                </div>
            </div>
        </div>


        <div class="col-lg-6 col-md-12 col-12">
            <h3 class="py-4"><?php echo $row['product_name'] ?></h3>
            <?php if ($row['product_special_offer'] > 0) { ?>
            <h4 class="p-price"><del>$ <?php echo number_format($row['product_price'], 2, '.', ','); ?></del></h4>
            <h2 class="p-price">$
                <?php echo number_format($row['product_price'] - $row['product_price'] * ($row['product_special_offer'] / 100), 2, '.', ','); ?>
            </h2>
            <?php } else { ?>
            <h2 class="p-price">$ <?php echo number_format($row['product_price'], 2, '.', ','); ?></h2>
            <?php } ?>

            <?php if ($row['product_category'] == 'ring') { ?>

            <p style="margin-top: 20px;">FIND YOUR SIZE</p>
            <select id="select-size" class="form-select" onchange="updateHiddenInput(this.value)">
                <?php for ($i = 0; $i < count($size_array); $i++) { ?>
                <option value="<?php echo $size_array[$i]; ?>"><?php echo $size_array[$i]; ?></option>
                <?php } ?>
            </select>
            <?php } ?>

            <form method="POST" action="cart.php" onsubmit="return validateQuantity();">
                <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>" />
                <input type="hidden" name="product_image" value="<?php echo $row['product_image'] ?>" />
                <input type="hidden" name="product_name" value="<?php echo $row['product_name'] ?>" />
                <input type="hidden" name="product_price"
                    value="<?php echo number_format($row['product_price'] - $row['product_price'] * ($row['product_special_offer'] / 100), 2, '.', ','); ?>" />
                <input type="number" name="product_quantity" value="1" id="product_quantity" />

                <?php if ($row['product_category'] == 'ring') { ?>
                <input type="hidden" name="product_size" id="hidden-size" value="52" />
                <?php } ?>
                <input type="hidden" name="product_category" value="<?php echo $row['product_category'] ?>" />

                <button class="buy-btn" id="buy-btn" type="submit" name="add_to_cart">Thêm vào giỏ</button>
            </form>

            <h4 class="mt-5 mb-5">Chi tiết sản phẩm</h4>
            <span><?php echo $row['product_description'] ?></span>
        </div>

        <div id="comment">
            <h3 class="my-4">Bình luận</h3>
            <form method="POST" action="comments.php">
                <p class="text-center" style="color: red;">
                    <?php if (isset($_GET['message'])) {
                            echo $_GET['message'];
                        } ?>
                    <?php if (isset($_GET['message'])) { ?>

                    <a href="login.php" class="btn btn_primary">Đăng nhập</a>

                    <?php } ?>
                </p>
                <input type="hidden" name="user_name" value="<?php echo $user_name ?>" />
                <input type="hidden" name="user_id" value="<?php echo $user_id ?>" />
                <input type="hidden" name="product_name" value="<?php echo $row['product_name'] ?>" />
                <input type="hidden" name="product_id" value="<?php echo $row['product_id'] ?>" />
                <div class="comment-container">
                    <textarea name="comment_content" placeholder="Thêm bình luận" required></textarea>
                    <input type="submit" name="comment" value="Bình luận" />
                </div>

                <?php while ($comment_row = $comments->fetch_assoc()) { ?>
                <div class="commented">
                    <div style="display: flex; align-items:center">
                        <h4 class="py-4"><?php echo $comment_row['user_name'] ?></h4>
                        <h5><i><?php echo $comment_row['create_at'] ?></i></h5>
                    </div>
                    <p><?php echo $comment_row['comment_content'] ?></p>
                </div>
                <?php } ?>

            </form>
        </div>

        <?php } ?>

    </div>
</section>


<!--Related Products-->
<section id="related-products" class="my-5 pb-5">
    <div class="container text-center mt-5 py-1">
        <h3>Sản phẩm liên quan</h3>
        <hr class="mx-auto">
    </div>
    <div class="row mx-auto container-fluid">
        <div class="product text-center col-lg-3 col-md-4 col-sm-12">
            <img class="img-fluid" src="assets/imgs/bag1.1.avif" />
            <div class="star">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
            </div>
            <h5 class="p-name">Túi xách</h5>
            <h4 class="p-price">$200</h4>
            <button class="buy-btn">Mua ngay</button>
        </div>
        <div class="product text-center col-lg-3 col-md-4 col-sm-12">
            <img class="img-fluid" src="assets/imgs/LOVE RING, 3 DIAMONDS1.jpg" />
            <div class="star">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
            </div>
            <h5 class="p-name">Nhẫn</h5>
            <h4 class="p-price">$100</h4>
            <button class="buy-btn">Mua ngay</button>
        </div>
        <div class="product text-center col-lg-3 col-md-4 col-sm-12">
            <img class="img-fluid" src="assets/imgs/perfume1.1.avif" />
            <div class="star">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
            </div>
            <h5 class="p-name">Nước hoa</h5>
            <h4 class="p-price">$50</h4>
            <button class="buy-btn">Mua ngay</button>
        </div>
        <div class="product text-center col-lg-3 col-md-4 col-sm-12">
            <img class="img-fluid" src="assets/imgs/SANTOS DE CARTIER WATCH1.jpg" />
            <div class="star">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
            </div>
            <h5 class="p-name">Đồng hồ</h5>
            <h4 class="p-price">$900</h4>
            <button class="buy-btn">Mua ngay</button>
        </div>
    </div>
</section>

<script>
var mainImg = document.getElementById('mainImg');
var smallImg = document.getElementsByClassName('small-img')

for (let i = 0; i < 4; i++) {
    smallImg[i].onclick = function() {
        mainImg.src = smallImg[i].src;
    }
}

function validateQuantity() {
    let quantity = document.getElementById("product_quantity").value;
    if (quantity < 1) {
        alert("Số lượng sản phẩm phải lớn hơn 0");
        window.location.reload();
        return false;
    }
    return true;
}

const selectSize = document.getElementById('select-size');
const buyBtn = document.getElementById('buy-btn');


buyBtn.addEventListener('mouseover', function() {
    if (selectSize.value === 'Select size') {
        buyBtn.textContent = 'Hãy chọn size';
        buyBtn.disabled = true;
    }
});

buyBtn.addEventListener('mouseout', function() {
    buyBtn.textContent = 'Thêm vào giỏ';
    buyBtn.disabled = false;
});

let selectedSize = document.getElementById('select-size').value;

function updateHiddenInput(value) {
    document.getElementById('hidden-size').value = value;
}
</script>

<?php include('layouts/footer.php'); ?>