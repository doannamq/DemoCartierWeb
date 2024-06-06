<?php

session_start();

if (isset($_POST['add_to_cart'])) {

    //if user has already added a product to cart
    if (isset($_SESSION['cart'])) {

        $products_array_ids = array_column($_SESSION['cart'], "product_id");
        //if product has already been added cart or not
        if (!in_array($_POST['product_id'], $products_array_ids)) {

            $product_id = $_POST['product_id'];

            $product_array = array(
                'product_id' => $_POST['product_id'],
                'product_name' => $_POST['product_name'],
                'product_price' => $_POST['product_price'],
                'product_image' => $_POST['product_image'],
                'product_quantity' => $_POST['product_quantity'],
                'product_size' => $_POST['product_size'],
                'product_category' => $_POST['product_category']
            );

            $_SESSION['cart'][$product_id] = $product_array;

            //product has already been added
        } else {
            echo '<script>alert("Sản phẩm đã có trong giỏ hàng");</script>';
        }

        //if this is the first product
    } else {

        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_image = $_POST['product_image'];
        $product_quantity = $_POST['product_quantity'];
        $product_size = $_POST['product_size'];
        $product_category = $_POST['product_category'];

        $product_array = array(
            'product_id' => $product_id,
            'product_name' => $product_name,
            'product_price' => $product_price,
            'product_image' => $product_image,
            'product_quantity' => $product_quantity,
            'product_size' => $product_size,
            'product_category' => $product_category
        );

        $_SESSION['cart'][$product_id] = $product_array;
    }

    //update calculate total
    calculateTotalCart();

    //remove product from cart
} else if (isset($_POST['remove_product'])) {
    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);

    //calculate total
    calculateTotalCart();
} else if (isset($_POST['edit_quantity'])) {

    //we get id and quantity from the form
    $product_id = $_POST['product_id'];
    $product_quantity = $_POST['product_quantity'];

    //
    if ($product_quantity < 1) {
        echo "<script>
                alert('Số lượng sản phẩm phải lớn hơn 0');
            </script>";
    } else {

        //

        //get the product array from the session
        $product_array =  $_SESSION['cart'][$product_id];

        //update product quantity
        $product_array['product_quantity'] = $product_quantity;

        //return array back its place
        $_SESSION['cart'][$product_id] = $product_array;

        //calculate total
        calculateTotalCart();
    }
} else {
    //header('location: index.php');
}

function calculateTotalCart()
{

    $total_price = 0;
    $total_quantity = 0;

    foreach ($_SESSION['cart'] as $key => $value) {
        $product = $_SESSION['cart'][$key];

        // $price = $product['product_price'];
        $price = floatval(str_replace(',', '', $product['product_price']));
        $quantity = $product['product_quantity'];

        $total_price = $total_price + ($price * $quantity);
        $total_quantity = $total_quantity + $quantity;
    }

    $_SESSION['total'] = $total_price;
    $_SESSION['quantity'] = $total_quantity;
}


?>


<?php include('layouts/header.php'); ?>

<!--Cart-->
<section class="cart container my-5 py-5">
    <div class="container mt-5">
        <h2 class="font-weight-bolde">Giỏ hàng</h2>
        <hr>
    </div>

    <table class="mt-5 pt-5">
        <tr>
            <th>Sản phẩm</th>
            <th>Số lượng</th>
            <th>Tổng giá</th>
        </tr>

        <?php if (isset($_SESSION['cart'])) { ?>

            <?php foreach ($_SESSION['cart'] as $key => $value) { ?>

                <tr>
                    <td>
                        <div class="product-info">
                            <img src="assets/imgs/<?php echo $value['product_image']; ?>" />
                            <div>
                                <div style="display: flex;">
                                    <p><?php echo $value['product_name']; ?></p>
                                    <?php if ($value['product_category'] == 'ring') { ?>
                                        <p>(Size: <?php echo $value['product_size'] ?>)</p>
                                    <?php } ?>
                                </div>
                                <small><span>$</span> <?php echo $value['product_price']; ?></small>
                                <br>
                                <form method="POST" action="cart.php">
                                    <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>" />
                                    <input type="submit" name="remove_product" class="remove-btn" value="Xóa" />
                                </form>
                            </div>
                        </div>
                    </td>

                    <td>

                        <form method="POST" action="cart.php" onsubmit="return validateQuantity()">
                            <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>" />
                            <input type="number" name="product_quantity" id="product_quantity" value="<?php echo $value['product_quantity']; ?>" />
                            <input type="submit" class="edit-btn" value="Sửa" name="edit_quantity" />
                        </form>
                    </td>

                    <td>
                        <span>$</span>
                        <span class="product-price"><?php echo number_format($value['product_quantity'] * floatval(str_replace(',', '', $value['product_price'])), 2, '.', ','); ?></span>
                    </td>
                </tr>

            <?php } ?>

        <?php } ?>

    </table>

    <div class="cart-total">
        <table>
            <tr>
                <td>Tổng</td>
                <?php if (isset($_SESSION['cart'])) { ?>
                    <td>$ <?php echo number_format($_SESSION['total'], 2, '.', ','); ?></td>
                <?php } ?>
            </tr>
        </table>
    </div>

    <div class="checkout-container">
        <form method="POST" action="checkout.php">
            <input type="submit" class="btn checkout-btn" value="Thanh toán" name="checkout" />
        </form>
    </div>
</section>

<?php include('layouts/footer.php'); ?>