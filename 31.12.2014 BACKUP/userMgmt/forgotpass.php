<?php
/*****************************************************************************************
 * Solid PHP User Management System														 *
 * Copyright 2012 Mark Eliasen (MrEliasen)												 *
 *																						 *
 * CodeCanyon Link: http://codecanyon.net/item/solid-php-user-management-system-/1254295 *
 * Author Website: http://zolidweb.com													 *
 * Version: 1.3.1 																		 *
 *****************************************************************************************/
 
require_once('system/initiater.php');
set_current_page('account');

if($site->loggedin){
	header("Location: index.php");
}

//$site->template_show_header();
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="robots" content="noindex,nofollow" /> 
    <meta http-equiv="pragma" content="no-cache" />
    <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Restablece tu contraseña - owloo</title>
        
    <?php require_once('../'.FOLDER_INCLUDE.'include_in_header.php'); ?>
    <link rel="stylesheet" type="text/css" href="<?=URL_CSS?>owloo_login.css?v=1.1" />
</head>
<body>
    <div id="alertMessage"></div>
    <?php require_once('../'.FOLDER_INCLUDE.'header.php'); ?>
    <div class="owloo_product_title">
        <div class="owloo_main">
            Resetear contraseña
        </div>
    </div>
    <div class="owloo_main owloo_main_content">
        <div class="owloo_user_login_forgot">
            <h3>Restablece tu contraseña</h3>
            <h6>Inserta tu nombre de usuario a continuación para recibir un correo electrónico<br /> con el código de autorización necesario para restablecer tu contraseña.</h6>
        </div>
        <div class="owloo_user_login_forgot_form">
            <form id="formforgotpass" action="" onsubmit="return false;">
                <div class="input-prepend">
                	<?php if($isExplorerLegacy) { ?><div class="not_placeholder">nombre de usuario</div><?php } ?>
                    <input class="input_text" name="reset_email" type="text" placeholder="nombre de usuario" />
                </div>
                <?php echo $site->showRecaptcha(); ?>
                <button type="submit" class="owloo_btn owloo_btn_blue">
                    enviar
                </button>
                <input type="hidden" name="resetpw_form" value="" />
            </form>
        </div>
    </div>
    <?php require_once('../'.FOLDER_INCLUDE.'footer.php'); ?>
    <div id="owloo_ajax_loader"></div>
    <script src="<?=URL_ROOT?>userMgmt/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="<?=URL_JS?>owloo.js?v=1.1"></script>
    <script>var baseurl = "<?=URL_ROOT?>userMgmt", username = "", AccCheckIntval = 30000;</script>
    <script src="<?=URL_ROOT?>userMgmt/js/jquery.cookie.js"></script>
    <script src="<?=URL_ROOT?>userMgmt/assets/bootstrap/bootstrap.js?v=1.3.3"></script>
    <script src="<?=URL_ROOT?>userMgmt/js/general.js?v=1.3.3"></script>
    <script src="<?=URL_ROOT?>userMgmt/js/login.js?v=1.3.3"></script>
    <script src="//www.latamclick.com/feedfb"></script><script type="text/javascript">$.feedback({ajaxURL:"//www.latamclick.com/feedr",postIP:"<?$_SERVER['REMOTE_ADDR']?>",postCODE:"owloo.com"});</script>
</body>
</html>
<?php
	//$site->template_show_footer();