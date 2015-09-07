<?php
	require_once('system/initiater.php');
	set_current_page('account');
    
	if($site->loggedin){
		header("Location: index.php");
	}
    
	$site->process_login();
	//$site->template_show_header();
	
	if(strpos($_SERVER['HTTP_REFERER'], substr(DOMAIN, 0, -1))){
	    $_SESSION['login_url_origen'] = $_SERVER['HTTP_REFERER'];
	}
	
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="robots" content="noindex,nofollow" /> 
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Iniciar sesión - owloo</title>
        
    <?php require_once('../'.FOLDER_INCLUDE.'include_in_header.php'); ?>
    <link rel="stylesheet" type="text/css" href="<?=URL_CSS?>owloo_login.css?v=1.1" />
</head>

<body>
    <div id="alertMessage"></div>
    <?php require_once('../'.FOLDER_INCLUDE.'header.php'); ?>
    <div class="owloo_product_title">
        <div class="owloo_main">
            Iniciar sesión en Owloo
        </div>
    </div>
    <div class="owloo_main owloo_main_content">
        <div class="owloo_user_login_form">
            <?php 
                // Add these 2 calls here, as they will return a notification - so it will look better keeping them inside the html
                // it will not make a difference in the end functionality-wise :).
                echo $site->process_resetNewPassword();
                echo $site->activateAccount(); 
            ?>
            
            <form id="form_login" action="" onsubmit="return false">
                <div class="owloo_session owloo_session_left">
                    <div>Inicia sesión con tus redes sociales</div>
                    <div>
                        <?php
                            $socialconnect = $site->showProviders();
                            if($socialconnect['providers'] > 0){
                                echo $site->socialSignupError();
                                echo $socialconnect['links'];
                            }
                        ?>
                    </div>
                    <div class="owloo_login_explain owloo_session_right">
                        Conécta tu cuenta de Owloo con Facebook o Twitter, ingresa a tu <b>Perfil</b>, en el menú <b>Configuración</b>, en la opción <b>Redes Sociales</b>.
                    </div>
                </div>
                <div class="owloo_session owloo_session_login">
                    <div>Inicia sesión con tu cuenta</div>
                    <div>
                        <div class="owloo_login_input_text_content">
                            <?php if($isExplorerLegacy) { ?><div class="not_placeholder" style="padding-left:0 !important;">email o nombre de usuario</div><?php } ?>
                            <input name="login_email" type="text" placeholder="email o nombre de usuario" value="<?=(!empty($_GET['active']))?$_GET['active']:''?>" />
                            <?php if($isExplorerLegacy) { ?><div class="not_placeholder" style="padding-left:0 !important;">contraseña</div><?php } ?>
                            <input name="login_password" type="password" placeholder="contraseña" />
                            <button type="submit" class="owloo_btn owloo_btn_blue" id="btn_login">iniciar sesión</button>
                            <div><a href="forgotpass.php" class="owloo_user_login_forgot">¿olvidaste tu contraseña?</a></div>
                        </div>
                        <input type="hidden" name="url_from" id="url_from" value="<?php if(strpos($_SERVER['HTTP_REFERER'], substr(DOMAIN, 0, -1))) echo $_SERVER['HTTP_REFERER']; ?>" />
                    </div>
                </div>
                <div class="owloo_session_forgot">
                    <span>¿Aún no estás registrado?</span><br /><br />
                    <a href="<?=URL_ROOT?>userMgmt/signup.php" class="owloo_btn owloo_btn_orange owloo_btn_big">Regístrate hoy - GRATIS</a>
                    <input type="hidden" name="login_form" value="" />
                </div>
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
	if(!empty($_GET['active'])){?>
	<script type="text/javascript">
		$('input[name="login_password"]').focus();
	</script>
<?php } ?>