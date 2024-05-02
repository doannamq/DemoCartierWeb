<?php

session_start();

if (isset($_POST['order_pay_btn'])) {
    $order_status = $_POST['order_status'];
    $order_total_price = $_POST['order_total_price'];
}

?>

<?php include('layouts/header.php'); ?>

<!--Payment-->
<section class="my-5 py-5">
    <div class="container mt-3 pt-5">
        <h2 class="font-weight-bold text-center">Thanh toán</h2>
        <hr class="mx-auto">
    </div>
    <div class="mx-auto container text-center"
        style="display: flex; flex-direction:column; align-items: center; margin-bottom: 120px">


        <?php if (isset($_POST['order_status']) && $_POST['order_status'] == 'not paid') { ?>
        <?php $amount = strval($_POST['order_total_price']); ?>
        <?php $order_id = $_POST['order_id']; ?>
        <p>Tổng thanh toán: $ <?php echo number_format($_POST['order_total_price'], 2, '.', ','); ?></p>
        <!-- <input class="btn btn-primary" type="submit" value="Pay Now" /> -->
        <div id="paypal-button-container"></div>


        <?php } else if (isset($_SESSION['total']) && $_SESSION['total'] != 0) { ?>
        <?php $amount = strval($_SESSION['total']); ?>
        <?php $order_id = $_SESSION['order_id']; ?>
        <p>Tổng thanh toán: $ <?php echo $_SESSION['total']; ?></p>
        <!-- <input class="btn btn-primary" type="submit" value="Pay Now" /> -->
        <div id="paypal-button-container"></div>


        <?php } else { ?>
        <p>Bạn không có đơn hàng nào</p>
        <?php } ?>


    </div>
</section>



<p id="result-message"></p>
<!-- Replace the "test" client-id value with your client-id -->
<script
    src="https://www.paypal.com/sdk/js?client-id=AXzPDb757lS8RNLH68uipdo6iQNBVGHymNkNEx5DSRNBhP8RVg2vCGZz5eQ9ZGLJWrsqWCVIygbXt-F0&currency=USD">
</script>
<script>
paypal.Buttons({
    createOrder: function(data, actions) {
        return actions.order.create({
            purchase_units: [{
                amount: {
                    value: '<?php echo $amount; ?>'
                }
            }]
        });
    },

    onApprove: function(data, actions) {
        return actions.order.capture().then(function(orderData) {
            console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
            var transaction = orderData.purchase_units[0].payments.captures[0];
            alert('Transaction ' + transaction.status + ': ' + transaction.id +
                '\n\nSee console for all available details');
            window.location.href = 'server/complete_payment.php?transaction_id=' + ' &order_id= ' +
                <?php echo $order_id ?>;
        });
    }
}).render('#paypal-button-container');
</script>


<?php include('layouts/footer.php'); ?>