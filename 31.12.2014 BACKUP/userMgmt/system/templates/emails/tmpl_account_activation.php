<?php
$email['title'] = "Confirma tu registro en owloo";
$email['body'] = '<!-- 

    Template Name: owloo
    Author: owloo
    Author URI: http://www.latamclick.com
    Version: 1.0

-->
<head>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width" />
<title>{subject}</title>
</head>
<body>
<table style="border-bottom: 1px dashed #ededed;" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
<tbody>
<tr>
<td>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
<tbody>
<tr>
<td style="padding: 0 20px;" height="40">
<p style="font-size: 11px; line-height: 16px; font-family: OpenSans,Arial,sans-serif; color: #999; font-style: italic;">Este es un correo electr&oacute;nico autom&aacute;tico, no lo respondas por favor.</p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
<tbody>
<tr>
<td style="padding-top: 20px; padding-right: 20px; padding-left: 20px;">
<table align="center" border="0" cellpadding="0" cellspacing="0" width="560">
<tbody>
<tr>
<td align="center" width="559">
 <a target="_blank" style="line-height: 0;" href="http://www.owloo.com/"> <img alt="Owloo" src="https://www.owloo.com/static/images/icowloo72x72.png" border="0" vspace="20" height="72" width="72" /></a>
</td>
<td align="left" width="1"></td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td style="padding: 20px 20; font: normal 14px OpenSans,Arial,sans-serif; line-height: 24px; color: #333;">
<br />
<p>Hola {{username}}.</p>
<p>Antes de poder ingresar a tu cuenta, necesitamos que confirme esta dirección de correo electrónico.</p>
<p><strong>Por favor ingresa en el siguiente enlace para poder activar la cuenta:</strong></p>
<p><a href="{{site_url}}/login.php?activate={{activationcode}}">{{site_url}}/login.php?activate={{activationcode}}</a></p>
<p>Si recibiste este correo electrónico por equivocación, por favor de eliminarlo. El enlace de confirmación tiene una validez de 7 días.</p>
 

</td>
</tr>
</tbody>
</table>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
<tbody>
<tr>
<td style="padding-top: 30px; padding-right: 20px; padding-bottom: 20px; padding-left: 20px;">
<p style="font-size: 18px; line-height: 24px; font-family: OpenSans,Arial,sans-serif; color: #666; margin: 0;">Atentamente,<br /> 
El equipo de Owloo</p></td>
</tr>
</tbody>
</table>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
<tbody>
<tr>
<td style="padding-top: 20px; padding-right: 20px; padding-bottom: 20px; padding-left: 20px; border-top: 1px solid #ededed;"><p style="font-size: 11px; line-height: 19px; font-family: OpenSans,Arial,sans-serif; color: #b5b5b5; margin-bottom: 15px;"><b>Protecci&oacute;n de datos</b> <br /> 
Owloo y logotipo de Owloo son marcas registradas de Latamclick S.A. Todas las dem&aacute;s empresas o productos mencionados son marcas registradas de las respectivas empresas y organizaciones a la que se encuentran asociadas. Recibes este mensaje porque eres Usuario, Partner o Cliente de Owloo.</p>
<p style="font-size: 11px; line-height: 19px; font-family: OpenSans,Arial,sans-serif; color: #b5b5b5; margin-bottom: 15px;">Owloo cuida sus datos personales, no compartimos sus datos con otras empresas. Para conocer m&aacute;s sobre c&oacute;mo tratamos sus datos personales, puede acceder en el <a target="_blank" href="http://www.owloo.com/privacy" style="text-decoration: none; color: #007CC3;">Centro de Privacidad</a>.<br /> <br /> &copy;2014 Owloo</p>
</td>
</tr>
</tbody>
</table></body></html>';
$email['variables'] = 'website_name,username,email,password,activationcode,visitor_ip,site_url';
