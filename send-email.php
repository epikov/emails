<?php
/**
 * Created by Alex Epikov.
 * Date: 07.03.2017
 * Time: 23:14
 */

require_once __DIR__ . '/vendor/autoload.php';

$mail = new PHPMailer();

$mail->setFrom('from@example.com', 'Mailer');
//$mail->addAddress('alexforjob.alex@yandex.ru');     // Add a recipient
$mail->addAddress('mazerattimc@gmail.com');     // Add a recipient
//$mail->addReplyTo('info@example.com', 'Information');
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$emailText = 'Test message
<br/>
<b>Some message is bold</b>
<br/>
<i>Some message is i</i>
<br/>
<h3>Some message is h3</h3>

<img src="http://my-development.esy.es/statistics.php?counter_id=' . time() . '">
';
$mail->Subject = 'Here is the subject';
$mail->Body    = $emailText;
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
