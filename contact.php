<?php include('layouts/header.php') ?>
<!--Contact-->
<section id="contact" class="container">
    <div class="contact-info">
        <h3>Hãy liên hệ với chúng tôi. </h3>
        <p>Chúng tôi mở cửa để tiếp nhận mọi gợi ý, phản hồi hoặc chỉ đơn giản là để trò chuyện.</p>
        <div class="contact-address">
            <i class="fa-solid fa-location-dot"></i>
            <p>Địa chỉ: 255 Cầu Giấy, Dịch Vọng, Cầu Giấy, Hà Nội</p>
        </div>
        <div class="contact-phone">
            <i class="fa-solid fa-phone"></i>
            <p>SĐT: (0865) 860-262</p>
        </div>
        <div class="contact-email">
            <i class="fa-regular fa-paper-plane"></i>
            <p>Email: cartier@gmail.com</p>
        </div>
        <div class="contact-website">
            <i class="fa-solid fa-earth-americas"></i>
            <p>Website: <a href="index.php" style="color: white; text-decoration: none">Cartier.com</a></p>
        </div>
    </div>
    <form id="contact-form" action="send.php" method="post">
        <h3 style="margin-bottom: 30px">Liên hệ</h3>
        <div class="name-and-email">
            <div class="input-container">
                <label for="full-name">Họ và tên</label>
                <input type="text" id="full-name" name="full-name" placeholder="Họ và tên" required autocomplete="off">
            </div>
            <div class="input-container">
                <label for="email">Địa chỉ Email</label>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>
        </div>
        <div class="input-container">
            <label for="subject">Chủ đề</label>
            <input type="text" id="subject" name="subject" placeholder="Chủ đề" required autocomplete="off">
        </div>
        <div class="input-container">
            <label for="message">Tin nhắn</label>
            <textarea id="message" name="message" rows="4" placeholder="Tin nhắn" required autocomplete="off"></textarea>
        </div>
        <button type="submit" name="send">Gửi Mail</button>

    </form>
</section>
<?php
if (isset($_GET['send_status'])) {
    $sendStatus = $_GET['send_status'];
    echo '<div class="send-status">' . $sendStatus . '</div>';
}
?>

<?php include('layouts/footer.php') ?>