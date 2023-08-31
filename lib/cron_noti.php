<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'gut');
define('DB_PASSWORD', '#');
define('DB_NAME', 'gut');
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '/PHPMailer/src/Exception.php';
require '/PHPMailer/src/PHPMailer.php';
require '/PHPMailer/src/SMTP.php';

function sendmail($to, $subject, $message) {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
	$mail->Host       = 'smtp.sendgrid.net';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'apikey';
    $mail->Password   = '#';
    $mail->Port       = 465;
    $mail->SMTPSecure = 'ssl';
    $mail->setFrom('shop@datalok.de', 'Datalok Shop');
    $mail->addAddress($to);
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = $subject;
    $mail->Body    = $message;
	$mail->send();
}

$time=time();

$result1=$link->query("SELECT username,randomcoins FROM shop WHERE nextcoins<=$time AND noti_sent=0");
if ($result1->num_rows>0) {while ($row=$result1->fetch_assoc()) {
    $username=$row["username"];
    $nextcoins=$row["randomcoins"];
    $emailbody='<!DOCTYPE html>
	<html><head><meta charset="UTF-8"><title>DATALOK E-Mail</title>
	<style>
	body {font-family: Inter, Arial, sans-serif; background-color: #F8F9FA; text-align: center;}
	.container {width: auto; max-width: 600px; margin: 20px auto; background-color: #FFFFFF; border: 1px solid lightgrey; border-radius: 12px; padding: 20px; padding-bottom: 28px;}
	h1 {color: #333333;}
	p {color: #666666; font-size: 16px; margin-bottom:30px;}
	a {background-color:#65C9CB; color:#FFFFFF; text-decoration:none; padding: 10px 3rem; border-radius: 12px;}
	img {max-width: 100%; height: auto;}
	</style></head>
	<body><div class="container">
		<img src="https://files.datalok.de/Logos/logo.webp" alt="DATALOK" width="128">
		<h1>Hohle dir jetzt <b style="color:#65C9CB;">'.$nextcoins.'</b> Göld!</h1>
		<p>Du kannst dir jetzt dein Göld im Datalok Shop abhohlen!</p>
		<a href="https://datalok.de/shop/">Zum Shop</a>
	</div><span style="color: lightgrey;">Diese E-Mail wurde Automatisch versendet. Bitte nicht antworten!</span></body>
	</html>';
    $result2=$link->query("SELECT email FROM Accounts WHERE username='$username' AND notifications=1 AND email!=''");
    if ($result2->num_rows>0) {while ($row=$result2->fetch_assoc()) {
        $email=$row["email"];
        sendmail($email, 'Dein Göld im Shop ist abholbereit!', $emailbody);
        $result3 = "UPDATE shop SET noti_sent=1 WHERE username='$username'";
        if ($link->query($result3)===TRUE) {}
    }}
}}


$link->close();
?>