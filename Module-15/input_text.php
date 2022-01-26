<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
require_once 'autoload.php';

if (isset($_POST['author']) && isset($_POST['text'])) {
    $telegraphText = new TelegraphText($_POST['author'], 1);
    $telegraphTextSave = new FileStorage();
    $telegraphTextSave->create($telegraphText);
}

if (isset($_POST['email'])) {
    $mail = new PHPMailer();
    try {
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = 'smtp.mail.ru';
        $mail->SMTPAuth = true;
        $mail->Username = 'ssmitth@bk.ru';
        $mail->Password = 'HD7Sy8PX.~et)zR';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;
        $mail->setFrom('ssmitth@bk.ru', 'ssmitth');
        $mail->isHTML(true);
        $mail->Subject = 'Telegraph Text';
        $mail->Body = $telegraphText;
        $mail->send();
    } catch (Exception $exception) {
        echo $exception->getMessage(), PHP_EOL;
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    } finally {
        echo 'hello';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<form action="input_text.php" method="post">
    <label>Автор: <input type="text" name="author"></label>
    <label>Текст: <textarea name="text">Место для Ваших мыслей</textarea></label>
    <label>Email: <input type="text" name="email"></label>
    <input type="submit">
</form>

</body>
</html>