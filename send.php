<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require('phpmailer/src/Exception.php');
require('phpmailer/src/PHPMailer.php');
require('phpmailer/src/SMTP.php');

if (isset($_POST['send'])) {

    $full_name = $_POST['full-name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $to = 'doannamq@gmail.com';

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'doannamq@gmail.com';
    $mail->Password = 'criwujlzsqpvfxmg';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    // $mail->setFrom($email, $full_name);
    $mail->setFrom($email, mb_encode_mimeheader($subject, 'UTF-8', 'B'));
    $mail->addAddress($to);

    $mail->isHTML(true);

    $mail->Subject = mb_encode_mimeheader($subject, 'UTF-8', 'B');
    $mail->Body = "Name: $full_name<br></br>Email: $email<br></br>Message:<br></br>$message";

    $mail->send();

    echo "<script>
            document.location.href = 'contact.php?send_status=Cảm ơn bạn đã liên hệ';
            </script>";
}
