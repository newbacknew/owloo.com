<?php

//include phpmailer
require_once('class.phpmailer.php');

//SMTP Settings
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth   = true; 
$mail->SMTPSecure = "tls"; 
$mail->Host       = "email-smtp.us-east-1.amazonaws.com";
$mail->Username   = "AKIAJURUOQ6Q5XOELJLQ";
$mail->Password   = "At3g5SKtOQVoqh/M+hg+sp3IjimIv8h5JF3tG8eFg8mq";

//

$mail->SetFrom('noreply@owloo.com', 'Owloo'); //from (verified email address)
$mail->Subject = "Email Subject"; //subject

//message
$body = "This is a test message.";
$body = eregi_replace("[\]",'',$body);
$mail->MsgHTML($body);
//

//recipient
$mail->AddAddress("dmolinas@hotmail.es"); 

//Success
if ($mail->Send()) { 
	echo "Message sent!"; die; 
}

//Error
if(!$mail->Send()) { 
	echo "Mailer Error: " . $mail->ErrorInfo; 
} 

?>
