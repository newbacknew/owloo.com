<?php

//include phpmailer
require_once('class.phpmailer.php');

//SMTP Settings
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth   = true; 
$mail->SMTPSecure = "tls"; 
$mail->Host       = "email-smtp.us-east-1.amazonaws.com";
$mail->Username   = "AKIAIGCQUYRFI6CKSBWQ";
$mail->Password   = "AkvBI3vhI6yLRRf7YzL6GfyOL+3+Ief6sP92EsHh/WQJ";
//

$mail->SetFrom('noreply@owloo.com', 'Owloo'); //from (verified email address)
$mail->Subject = "Otro intento!"; //subject

//message
$body = "This is a test message.";
$body = eregi_replace("[\]",'',$body);
$mail->MsgHTML($body);
//

//recipient
$mail->AddAddress("dmolinas@hotmail.es", "Test Recipient"); 

//Success
if ($mail->Send()) { 
    echo "Message sent!"; die; 
}

//Error
if(!$mail->Send()) { 
    echo "Mailer Error: " . $mail->ErrorInfo; 
} 

?>
