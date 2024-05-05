<?php

session_start();

if (!empty($_SESSION['cart'])) {

    //let user in

    //send user to homepage
} else {
    header('location: shop.php');
}
?>

<?php
session_start();
include('server/connection.php');

// Kiểm tra nếu user_name và user_email đã được lưu trong session
if (isset($_SESSION['user_name']) && isset($_SESSION['user_email'])) {
    $checkout_name = $_SESSION['user_name'];
    $checkout_email = $_SESSION['user_email'];
} else {
    $checkout_name = '';
    $checkout_email = '';
}
?>

<?php include('layouts/header.php'); ?>
<!--Checkout-->
<section class="my-5 py-5">
    <div class="container mt-3 pt-5">
        <h2 class="font-weight-bold text-center">Thanh Toán</h2>
        <hr class="mx-auto">
    </div>
    <div class="mx-auto container">
        <form id="checkout-form" method="POST" action="server/place_order.php">
            <p class="text-center" style="color: red;">
                <?php if (isset($_GET['message'])) {
                    echo $_GET['message'];
                } ?>
                <?php if (isset($_GET['message'])) { ?>

                <a href="login.php" class="btn btn_primary">Đăng nhập</a>

                <?php } ?>
            </p>
            <div class="form-group checkout-small-element">
                <label>Họ và tên</label>
                <input type="text" class="form-control" id="checkout-name" name="name" placeholder="Họ và tên"
                    value="<?php echo $checkout_name; ?>" required />
            </div>
            <div class="form-group checkout-small-element">
                <label>Email</label>
                <input type="email" class="form-control" id="checkout-email" name="email" placeholder="Email"
                    value="<?php echo $checkout_email; ?>" required />
                <!--Hidden-->
                <input type="hidden" name="subject" value="Thank You for Your Recent Purchase!" />
                <!-- <input type="hidden" name="message" value="Thank you,<?php echo $checkout_name ?>, for shopping at our store " /> -->
                <textarea name="message" style="display: none;">
                    Kính gửi <?php echo $checkout_name ?>,<br>
                    Chúng tôi xin gửi lời chân thành nhất tới bạn về việc đã chọn mua sản phẩm của chúng tôi<br>
                    Chúng tôi rất vui mừng khi biết rằng bạn đã tin tưởng và ủng hộ sản phẩm của chúng tôi.<br><br>

                    Sự ủng hộ của bạn không chỉ là một động lực lớn cho chúng tôi phát triển mà còn là một minh <br>
                    chứng cho chất lượng và dịch vụ mà chúng tôi cung cấp. Chúng tôi cam kết luôn cố gắng hết mình <br>
                    để mang lại trải nghiệm mua sắm tốt nhất cho quý khách hàng.<br><br>

                    Nếu bạn có bất kỳ câu hỏi hoặc yêu cầu nào, xin đừng ngần ngại liên hệ với chúng tôi. Chúng tôi <br>
                    luôn sẵn lòng hỗ trợ bạn mọi lúc.<br><br>

                    Một lần nữa, chúng tôi xin chân thành cảm ơn bạn và mong rằng bạn sẽ tiếp tục ủng hộ chúng tôi<br>
                    trong tương lai.<br><br>

                    Trân trọng,<br>
                    Cartier<br>
                    255 Cầu Giấy, Dịch Vọng, Cầu Giấy, Hà Nội<br>
                    cartier@gmail.com<br>
                </textarea>

            </div>
            <div class="form-group checkout-small-element">
                <label>Số điện thoại</label>
                <input type="tel" class="form-control" id="checkout-phone" name="phone" placeholder="Số điện thoại"
                    required />
            </div>
            <div class="form-group checkout-small-element">
                <label for="checkout-city">Tỉnh/Thành Phố</label>
                <input type="text" class="form-control" id="checkout-city" name="city" placeholder="Tỉnh/Thành Phố"
                    required autocomplete="off" />
                <ul id="province-list"></ul>
            </div>
            <div class="form-group checkout-small-element">
                <label>Quận/Huyện</label>
                <input type="text" class="form-control" id="checkout-district" name="district" placeholder="Quận/Huyện"
                    required autocomplete="off" />
                <ul id="district-list"></ul>
            </div>
            <div class="form-group checkout-small-element">
                <label>Phường/Xã</label>
                <input type="text" class="form-control" id="checkout-ward" name="ward" placeholder="Phường/Xã" required
                    autocomplete="off" />
                <ul id="ward-list"></ul>
            </div>
            <div class="form-group checkout-large-element">
                <label>Địa chỉ</label>
                <input type="text" class="form-control" id="checkout-address" name="address" placeholder="Địa chỉ"
                    required />
            </div>
            <div class="form-group checkout-btn-container">
                <p>Tổng số tiền: $ <?php echo number_format($_SESSION['total'], 2, '.', ',') ?></p>
                <input type="submit" class="btn" id="checkout-btn" name="place_order" value="Đặt Hàng" />
            </div>
        </form>
    </div>
</section>
<script>
var selectedProvinceId = null;

var selectedDistrictId = null;

$(document).ready(function() {
    $('#checkout-city').focus(function() {
        // Gửi yêu cầu Ajax để lấy danh sách tỉnh/thành phố
        $.ajax({
            url: 'https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/province',
            method: 'GET',
            headers: {
                'Token': 'af98b191-ffaf-11ee-b1d4-92b443b7a897',
                'ShopId': 191981
            },
            success: function(response) {
                // Hiển thị toàn bộ danh sách tỉnh/thành phố
                $('#province-list').empty();
                response.data.forEach(function(city) {
                    $('#province-list').append('<li data-province-id="' + city
                        .ProvinceID + '">' + city.ProvinceName + '</li>');
                });
                $('#province-list').show();
            },
            error: function(xhr, status, error) {
                console.error('Error fetching city list:', error);
            }
        });
    });

    $('#checkout-city').on('input', function() {
        var searchText = $(this).val().trim().toLowerCase();
        if (searchText === '') {
            $('#province-list').hide();
            return;
        }

        $.ajax({
            url: 'https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/province',
            method: 'GET',
            headers: {
                'Token': 'af98b191-ffaf-11ee-b1d4-92b443b7a897',
                'ShopId': 191981
            },
            success: function(response) {
                var filteredCities = response.data.filter(function(city) {
                    return city.ProvinceName.toLowerCase().includes(searchText);
                });

                $('#province-list').empty();
                filteredCities.forEach(function(city) {
                    $('#province-list').append('<li data-province-id="' + city
                        .ProvinceID + '">' + city.ProvinceName + '</li>');
                });
                $('#province-list').show();
            },
            error: function(xhr, status, error) {
                console.error('Error fetching city list:', error);
            }
        });
    });
    $('#province-list').on('mousedown', 'li', function() {
        // Lấy provinceId từ dữ liệu của phần tử li
        selectedProvinceId = $(this).data('province-id');
        $('#checkout-city').val($(this).text());
        $('#province-list').hide();
    });

    $(document).click(function(event) {
        if (!$(event.target).closest('#province-list').length && !$(event.target).closest(
                '#checkout-city').length) {
            $('#province-list').hide();
        }
    });
});

//Hiển thị danh sách quận huyện
$(document).ready(function() {
    $('#checkout-district').focus(function() {
        if (selectedProvinceId === null) {
            // Không có tỉnh/thành phố được chọn, ẩn danh sách quận/huyện
            $('#district-list').hide();
            return;
        }

        var token = 'af98b191-ffaf-11ee-b1d4-92b443b7a897';
        var shopID = 191981;
        $.ajax({
            url: 'https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/district?province_id=' +
                selectedProvinceId,
            method: 'GET',
            headers: {
                'Token': token,
                'ShopId': shopID
            },
            success: function(response) {
                $('#district-list').empty();
                response.data.forEach(function(district) {
                    $('#district-list').append('<li data-district-id="' + district
                        .DistrictID + '">' + district.DistrictName + '</li>');
                });
                $('#district-list').show();
            },
            error: function(xhr, status, error) {
                console.log('Error fetching district list:', error);
            }
        });
    });

    /////////////////////////////////
    $('#checkout-district').on('input', function() {
        var searchText = $(this).val().trim().toLowerCase();
        if (searchText === '') {
            $('#district-list').hide();
            return;
        }

        if (selectedProvinceId === null) {
            // Không có tỉnh/thành phố được chọn, ẩn danh sách quận/huyện
            $('#district-list').hide();
            return;
        }

        var token = 'af98b191-ffaf-11ee-b1d4-92b443b7a897';
        var shopID = 191981;
        $.ajax({
            url: 'https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/district?province_id=' +
                selectedProvinceId,
            method: 'GET',
            headers: {
                'Token': token,
                'ShopId': shopID
            },
            success: function(response) {
                var filteredDistricts = response.data.filter(function(district) {
                    return district.DistrictName.toLowerCase().includes(searchText);
                });

                $('#district-list').empty();
                filteredDistricts.forEach(function(district) {
                    $('#district-list').append('<li data-district-id="' + district
                        .DistrictID + '">' + district.DistrictName + '</li>');
                });
                $('#district-list').show();
            },
            error: function(xhr, status, error) {
                console.log('Error fetching district list:', error);
            }
        });
    });
    /////////////////////////////////

    $('#district-list').on('mousedown', 'li', function() {
        selectedDistrictId = $(this).data('district-id');
        $('#checkout-district').val($(this).text());
        $('#district-list').hide();
    });

    $(document).click(function(event) {
        if (!$(event.target).closest('#district-list').length && !$(event.target).closest(
                '#checkout-district').length) {
            $('#district-list').hide();
        }
    });

    $('#checkout-district').blur(function() {
        $('#district-list').hide();
    });
});

//Hiển thị danh sách phường xã
$(document).ready(function() {
    $('#checkout-ward').focus(function() {
        if (selectedProvinceId === null || selectedDistrictId === null) {
            // Không có tỉnh/thành phố hoặc quận/huyện được chọn, ẩn danh sách phường/xã
            $('#ward-list').hide();
            return;
        }

        var token = 'af98b191-ffaf-11ee-b1d4-92b443b7a897';
        var shopID = 191981;
        $.ajax({
            url: 'https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/ward?district_id=' +
                selectedDistrictId,
            method: 'GET',
            headers: {
                'Token': token,
                'ShopId': shopID
            },
            success: function(response) {
                $('#ward-list').empty();
                response.data.forEach(function(ward) {
                    $('#ward-list').append('<li>' + ward.WardName + '</li>');
                });
                $('#ward-list').show();
            },
            error: function(xhr, status, error) {
                console.log('Error fetching ward list:', error);
            }
        });
    });

    /////////////////////////////////////
    $('#checkout-ward').on('input', function() {
        var searchText = $(this).val().trim().toLowerCase();
        if (searchText === '') {
            $('#ward-list').hide();
            return;
        }

        if (selectedProvinceId === null || selectedDistrictId === null) {
            // Không có tỉnh/thành phố hoặc quận/huyện được chọn, ẩn danh sách phường/xã
            $('#ward-list').hide();
            return;
        }

        var token = 'af98b191-ffaf-11ee-b1d4-92b443b7a897';
        var shopID = 191981;
        $.ajax({
            url: 'https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/ward?district_id=' +
                selectedDistrictId,
            method: 'GET',
            headers: {
                'Token': token,
                'ShopId': shopID
            },
            success: function(response) {
                var filteredWards = response.data.filter(function(ward) {
                    return ward.WardName.toLowerCase().includes(searchText);
                });

                $('#ward-list').empty();
                filteredWards.forEach(function(ward) {
                    $('#ward-list').append('<li>' + ward.WardName + '</li>');
                });
                $('#ward-list').show();
            },
            error: function(xhr, status, error) {
                console.log('Error fetching ward list:', error);
            }
        });
    });
    ////////////////////////////////////

    // Xử lý khi chọn phường/xã từ danh sách
    $('#ward-list').on('mousedown', 'li', function() {
        $('#checkout-ward').val($(this).text());
        $('#ward-list').hide();
    });

    // Ẩn danh sách phường/xã khi click bên ngoài
    $(document).click(function(event) {
        if (!$(event.target).closest('#ward-list').length && !$(event.target).closest('#checkout-ward')
            .length) {
            $('#ward-list').hide();
        }
    });

    // Ẩn danh sách phường/xã khi blur khỏi trường nhập
    $('#checkout-ward').blur(function() {
        $('#ward-list').hide();
    });
});
</script>
<?php include('layouts/footer.php'); ?>