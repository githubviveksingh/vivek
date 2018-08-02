<?php
require_once('class.phpmailer.php');

$mail             = new PHPMailer(); // defaults to using php "mail()"


$mailBody = 'This is the test mail with embedded image.<br>Below is the attached image.<br><br><a href="#"><img alt="Embedded Image" src="cid:Banner-Image"></a><br><br>Test embedded image successful.<br><a href="#"><img alt="Embedded Image" src="cid:Banner-Image"></a>';
$mail->AddEmbeddedImage("36343_banner.jpg", "Banner-Image", "36343_banner.jpg");
// $mail->AddReplyTo("name@yourdomain.com","First Last");

$mail->SetFrom('pranavkashtvar88@gmail.com', 'Pranav Kashtvar');

$address = "pranav@qpi.in";
$mail->AddAddress($address, "Pranav Kashtvar");

$mail->Subject    = "Test email with attached image";

$mail->MsgHTML($mailBody);
if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message sent!";
}
?>
