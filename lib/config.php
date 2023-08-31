<?php
// Db connection
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'gut');
define('DB_PASSWORD', '#');
define('DB_NAME', 'gut');
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Discord Websocket Message function
function discordws($discordwsmessage) {
    $json_data = json_encode([
        "content" => $discordwsmessage,
        "username" => "Datalok Shop API",
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
    $ch = curl_init("#");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_exec($ch);
    curl_close($ch);
}

// Sendmail function
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
    $mail->CharSet = 'UTF-8';
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->send();
}
?>