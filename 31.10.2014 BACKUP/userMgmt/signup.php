<?php
require_once('system/initiater.php');	
set_current_page('account');

if($site->loggedin)
	header('Location: profile.php');

$site->process_register();

//$site->template_show_header();

$socialconnect = $site->showProviders('Sign up with');
$social_links = '';
if($socialconnect['providers'] > 0 && !$site->config('invite_only')){
	$social_links .= $socialconnect['links'];
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
    
    <title>Crea una cuenta gratuita en owloo</title>
        
    <link rel="stylesheet" type="text/css" href="<?=URL_ROOT?>userMgmt/assets/bootstrap/bootstrap.css?v=1.3.3"/>
    <link rel="stylesheet" type="text/css" href="<?=URL_ROOT?>userMgmt/assets/bootstrap/bootstrap-responsive.css?v=1.3.3"/>
    <?php require_once('../'.FOLDER_INCLUDE.'include_in_header.php'); ?>
    <link rel="stylesheet" type="text/css" href="<?=URL_CSS?>owloo_login.css?v=1.1" />
</head>

<body>
    <div id="alertMessage"></div>
    <?php require_once('../'.FOLDER_INCLUDE.'header.php'); ?>
    <div class="owloo_product_title">
        <div class="owloo_main">
            Crea una cuenta gratuita en owloo
        </div>
    </div>
    <div class="owloo_main owloo_main_content">
        <div class="modal hide fade in" id="termsconditions">
        	<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal">&times;</button>
        		<h3>Información y datos personales</h3>
        	</div>
        	<div class="modal-body">
        		<?php echo nl2br($site->config('termsconditions')); ?>
        	</div>
        	<div class="modal-footer">
        		<a href="#" class="btn" data-dismiss="modal">Close</a>
        	</div>
        </div>
        
        <div class="owloo_user_sigup_form owloo_user_login_form">
        	<?php
                if($site->config('signup_disabled')){
                    echo '<p>'.$site->lang['cl_users_41'].'</p>';
                }else{
            ?>
            <form id="formSignup" action="" onsubmit="return false">
                <div class="owloo_session owloo_session_more">
                    <div>Crea una cuenta en Owloo</div>
                    <div class="owloo_user_register_data">
                    	<div>Datos personales</div>
            			<?php echo $site->profileSignupFields(); ?>
                        
                        <div class="input-prepend">
                        	<?php if($isExplorerLegacy) { ?><div class="not_placeholder">correo electrónico</div><?php } ?>
                            <input name="signup_email" type="text" placeholder="correo electrónico" />
                        </div>
                    </div>
                    <div class="owloo_user_register_data">
                    	<div>Datos de la cuenta</div>
                        <div class="input-prepend">
                        	<?php if($isExplorerLegacy) { ?><div class="not_placeholder">nombre de usuario</div><?php } ?>
                            <input name="signup_username" type="text" placeholder="nombre de usuario" />
                        </div>
                        <div class="input-prepend">
                        	<?php if($isExplorerLegacy) { ?><div class="not_placeholder">contraseña</div><?php } ?>
                            <input name="signup_password" type="password" placeholder="contraseña" />
                        </div>
                        <div class="input-prepend">
                        	<?php if($isExplorerLegacy) { ?><div class="not_placeholder">confirmar contraseña</div><?php } ?>
                            <input name="signup_password2" type="password" placeholder="confirmar contraseña" />
                        </div>
                    </div>
                    <div class="owloo_session_inv_code">
                        <button id="btn_signup" class="owloo_btn owloo_btn_blue">
                            Crear cuenta
                        </button>
                    </div>
                    
                    <input type="hidden" name="signup_form" value="" />
                </div>
                <div class="owloo_session owloo_session_less">
                    <?php
                    if(!$site->config('signup_disabled')){
                        if(!empty($social_links)){
                            echo '<div>O regístrate con tus redes sociales</div>
                                    '.$site->socialSignupError().'
                                      '.$social_links.'
                                  ';
                        }
                    }
                    ?>
                </div>
        		
        		<?php echo $site->showTerms(); ?>
                
                
                <?php echo $site->showRecaptcha(); ?>			
                
                <?php echo $site->inviteSignupField(); ?>
                
            </form>
            <?php 
                }
            ?>
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