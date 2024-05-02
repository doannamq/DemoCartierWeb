<?php

include('server/connection.php');

if (isset($_GET['product_id'])) {

    $product_id = $_GET["product_id"];

    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);

    $stmt->execute();

    $product = $stmt->get_result();

    //no product id was given
} else {

    header(('location: index.php'));
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

            <form method="POST" action="cart.php" onsubmit="return validateQuantity();">
                <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>" />
                <input type="hidden" name="product_image" value="<?php echo $row['product_image'] ?>" />
                <input type="hidden" name="product_name" value="<?php echo $row['product_name'] ?>" />
                <input type="hidden" name="product_price"
                    value="<?php echo number_format($row['product_price'] - $row['product_price'] * ($row['product_special_offer'] / 100), 2, '.', ','); ?>" />
                <input type="number" name="product_quantity" value="1" id="product_quantity" />
                <button class="buy-btn" type="submit" name="add_to_cart">Thêm vào giỏ</button>
            </form>

            <h4 class="mt-5 mb-5">Chi tiết sản phẩm</h4>
            <span><?php echo $row['product_description'] ?></span>
        </div>

        <?php } ?>

    </div>
</section>

<!--Related Products-->
<section id="related-products" class="my-5 pb-5">
    <div class="container text-center mt-5 py-5">
        <h3>Sản phẩm liên quan</h3>
        <hr class="mx-auto">
    </div>
    <div class="row mx-auto container-fluid">
        <div class="product text-center col-lg-3 col-md-4 col-sm-12">
            <img class="img-fluid mb-3" src="assets/imgs/featured1.png" />
            <div class="star">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
            </div>
            <h5 class="p-name">Sports Shoes</h5>
            <h4 class="p-price">$199.9</h4>
            <button class="buy-btn">Mua ngay</button>
        </div>
        <div class="product text-center col-lg-3 col-md-4 col-sm-12">
            <img class="img-fluid mb-3" src="assets/imgs/featured2.webp" />
            <div class="star">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
            </div>
            <h5 class="p-name">Coat</h5>
            <h4 class="p-price">$19.9</h4>
            <button class="buy-btn">Mua ngay</button>
        </div>
        <div class="product text-center col-lg-3 col-md-4 col-sm-12">
            <img class="img-fluid mb-3" src="assets/imgs/featured3.avif" />
            <div class="star">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
            </div>
            <h5 class="p-name">Bag</h5>
            <h4 class="p-price">$59.9</h4>
            <button class="buy-btn">Mua ngay</button>
        </div>
        <div class="product text-center col-lg-3 col-md-4 col-sm-12">
            <img class="img-fluid mb-3" src="assets/imgs/featured4.png" />
            <div class="star">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
            </div>
            <h5 class="p-name">Air Frier</h5>
            <h4 class="p-price">$299.9</h4>
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
</script>

<?php include('layouts/footer.php'); ?>