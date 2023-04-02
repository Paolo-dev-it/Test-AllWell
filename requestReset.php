<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require './config.php';

if (isset($_POST["email"])) {

    $emailTo = $_POST["email"];

    // check if email exists in database
    $check_query = mysqli_query($conn, "SELECT * FROM utenti WHERE email='$emailTo'");
    if (mysqli_num_rows($check_query) == 0) {

        echo '<script type="text/javascript">';
        echo ' alert("Email non valida, torna indietro e riprova")';
        echo '</script>';
        echo '<p><a href="login.html">Torna al <strong>Login</strong> </a></p>';
        exit('');
    }

    $code = uniqid(true);

    $query = mysqli_query($conn, "INSERT INTO reset_password(token, email) VALUES ('$code', '$emailTo')");
    if (!$query) {
        exit("Error");
    }

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP(); // Send using SMTP
        $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'paolo.nicoletti99@gmail.com'; // SMTP username
        $mail->Password = 'WebDevPaul.99'; // SMTP password
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption
        $mail->Port = 587; // TCP port to connect to

        //Recipients
        $mail->setFrom('paolo.nicoletti99@gmail.com', 'Resetter');
        $mail->addAddress($emailTo); // Add a recipient
        $mail->addReplyTo('no-reply@libero.it', 'No Reply');

        //Content
        $url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/passwordReset.php?code=$code";
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Your password reset link';
        $mail->Body = "<h1>Click <a href='$url'>this link</a> for the reset</h1>This is the HTML message body <b>in bold!</b>";
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        echo 'Reset password link has been sent to your email';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AllWell</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css' integrity='sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8