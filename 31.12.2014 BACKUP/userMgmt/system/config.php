<?php
/*****************************************************************************************
 * Solid PHP User Management System :: Version 1.4.0.4								 *
 * Copyright 2012 Mark Eliasen (MrEliasen)												 *
 *																						 *
 * CodeCanyon Link: http://codecanyon.net/item/solid-php-user-management-system-/1254295 *
 * Author Website: http://zolidweb.com													 *
 *****************************************************************************************/
 
$config = array();
$config['sql_host'] = 'localhost';
$config['sql_port'] = '3306';
$config['sql_user'] = 'owloo_admin';
$config['sql_pass'] = 'fblatamx244';
$config['sql_db'] = 'owloo_userManagement';
$config['sql_charset'] = 'utf8';
## Hash Keys - Do not share these keys with anyone!! ##
$config['hashing_salt'] = 'A[a-AXeCUtE+U.AFyqe)yte}I#aRIvA]AqySERiSUDaHizABYdYpe|IKiTePa+AR';
$config['hashing_key'] = 'eQykUQuDe=uKOhU)OXACA]ANy,Ezy-I/Y@o)IRi!yDEbE?aBEjI#o*iXiGAXEFAG';
## General configuration ##
$config['version'] = '1.4.0.4';
$config['website_name'] = 'Owloo';
$config['base_url'] = 'http://www.owloo.com/userMgmt';
$config['session_timeout'] = 0;
$config['cache_time'] = 600;
$config['cache_enabled'] = false;
$config['cache_dir'] = BASE_PATH.'/system/cache';
$config['permission_writeable'] = '0755';
$config['permission_readable'] = '0644';
$config['avatar_dir'] = '/home/owloo/public_html/userMgmt/uploads/avatars';
$config['pm_interval'] = 20;
$config['last_activity'] = false;
$config['dateformat_long'] = 'd-m-Y - H:i:s';
$config['dateformat_short'] = 'd-m-Y';
$config['dbsessions'] = false;
$config['search_intval'] = 20;
$config['force_nocache'] =  true;
## Account Check Interval ##
$config['account_check_interval'] = 30;
## Sign Up Options ##
$config['emailactivation'] = false;
$config['signup_disabled'] = false;
$config['admin_activation'] =  false;
## IPN Settings ##
$config['crondebug'] = false;
$config['crontype'] = 0;
$config['crontoken'] = '';
$config['cronintval'] = 300;
$config['paidmemberships'] = false;
$config['ipn_debug'] = false;
$config['ipn_email'] = '';
$config['ipn_currency'] = 'AUD';
$config['ipn_timeout'] = 30;
$config['ipn_sandbox'] = false;
$config['ipn_usessl'] = false;
$config['ipn_followloc'] = false;
$config['ipn_forcessl3'] = false;
$config['ipn_usecurl'] =  false;
## PayPal API Information ##
$config['pp_api_user'] = '';
$config['pp_api_pass'] = '';
$config['pp_api_sig'] =  '';
## Language Information ##
/* Make sure the name matches the files in the lang directory. 
   eg. if the file is called "lang_eng.php" you should put "eng" in the value below (without ""). */
$config['default_lang'] = 'eng';
## Mail Configuration ##
$config['mailer_type'] = 'smtp';
## SMTP Configuration ##
$config['smtp_host'] = 'email-smtp.us-east-1.amazonaws.com';
$config['smtp_port'] = 465;
$config['smtp_user'] = 'AKIAJURUOQ6Q5XOELJLQ';
$config['smtp_pass'] = 'At3g5SKtOQVoqh/M+hg+sp3IjimIv8h5JF3tG8eFg8mq';
## PHP Configuration ##
$config['php_from'] = 'noreply@owloo.com';
## reCaptcha Keys ##
$config['reCAPTCHA_enabled'] = false; //whether to enable or disabled the recaptcha system
$config['reCAPTCHA_publickey'] = '';
$config['reCAPTCHA_privatekey'] = '';
## Invitation System ##
$config['invite_system'] = false;
$config['invite_only'] = false;
## Friend System ##
$config['friend_system'] = false;
## Forum System ##
$config['forum_enabled'] = false;
## PM System ##
$config['pm_system'] = false;
$config['pm_system_spam'] = 20;
## Legal information ##
$config['termsrequired'] = false;
$config['termsconditions'] =
"<div class=\"owloo_terms_conditions\"><p>A través del envío de tu información personal, al enviar, acepta y entiende que eres el único responsable de mantener la confidencialidad de las contraseñas y datos personales asociados a cualquier producto y servicio que brindamos y que cualquier información de registro que facilite a Owloo será precisa, correcta y actualizada.</p>
<p>Es posible que Owloo use la conexión de Facebook (Facebook Connect) para la autorización, recaudar información y el acceso a un determinado producto y servicio.</p>
<p>Owloo toma las precauciones adecuadas para proteger toda la información de nuestros usuarios que a diario acceden a nuestros productos y servicios. La información de tu cuenta se encuentra en un servidor seguro. Owloo no vende ni comparte tu información personal con ninguna otra empresa.</p>
<p>Cuando ingresa información sensible cómo tus datos personales, codificamos la información usando la tecnología Secure Socket Layer (SSL). Hay que subrayar que Owloo cuenta con una política de seguridad interna con respecto a la confidencialidad de los datos de nuestros clientes, limitando el acceso únicamente a aquellos empleados que creemos que necesitan conocer dicha información con el único propósito de llevar a cabo su trabajo.</p>
<p>Si tienes el conocimiento de cualquier uso no autorizado de tu contraseña o de tu información personal, por favor de notificar inmediatamente a Owloo al correo electrónico privacy@latamclick.com.<p>
<h5>Para conocer el Uso de los productos y servicios, Cookies y Responsabilidad por favor ingresa en <span class='owloo_modal_a' onclick='window.open(\"http://www.latamclick.com/privacy\")'>Latamclick</span>.</h5></div>";
## Time settings ##
$config['timezone'] = 'America/Asuncion';
date_default_timezone_set('America/Asuncion');