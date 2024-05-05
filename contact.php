<?php include('layouts/header.php') ?>
<!--Contact-->
<section id="contact" class="container">
    <div class="contact-info">
        <h3>Let's get in touch</h3>
        <p>We're open for any suggestion or just to have a chat</p>
        <div class="contact-address">
            <i class="fa-solid fa-location-dot"></i>
            <p>Address: 255 Cầu Giấy, Dịch Vọng, Cầu Giấy, Hà Nội</p>
        </div>
        <div class="contact-phone">
            <i class="fa-solid fa-phone"></i>
            <p>Phone: (0865) 860-262</p>
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
        <h3 style="margin-bottom: 30px">Get in touch</h3>
        <div class="name-and-email">
            <div class="input-container">
                <label for="full-name">Full Name</label>
                <input type="text" id="full-name" name="full-name" placeholder="Name" required autocomplete="off">
            </div>
            <div class="input-container">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>
        </div>
        <div class="input-container">
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" placeholder="Subject" required autocomplete="off">
        </div>
        <div class="input-container">
            <label for="message">Message</label>
            <textarea id="message" name="message" rows="4" placeholder="Message" required autocomplete="off"></textarea>
        </div>
        <button type="submit" name="send">Send Message</button>

    </form>
</section>
<?php
if (isset($_GET['send_status'])) {
    $sendStatus = $_GET['send_status'];
    echo '<div class="send-status">' . $sendStatus . '</div>';
}
?>

<?php include('layouts/footer.php') ?>