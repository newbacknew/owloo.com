<?php
/*****************************************************************************************
 * Solid PHP User Management System														 *
 * Copyright 2012 Mark Eliasen (MrEliasen)												 *
 *																						 *
 * CodeCanyon Link: http://codecanyon.net/item/solid-php-user-management-system-/1254295 *
 * Author Website: http://zolidweb.com													 *
 * Version: 1.3.1 																		 *
 *****************************************************************************************/
 
if(!defined('IN_SYSTEM')){
	exit;
}

class Template extends Languages{
	
	function template_show_header(){
		if($this->loggedin){
			echo $this->template_header_loggedin();
		}else{
			echo $this->template_header_guest();
		}
	}
	
	function template_show_footer(){
		if($this->loggedin){
			echo $this->template_footer_loggedin();
		}else{
			echo $this->template_footer_guest();
		}
	}
	
	function template_show_avatar_loggedin(){
		echo $this->template_show_avatar_loggedin_content();
	}
	
	function template_show_avatar_loggedin_content(){
		echo '
		<div class="navbar">
			<div class="navbar-inner">
				<div class="nav-collapse">
					<ul class="nav pull-right">
						<li class="dropdown">
							<a href="#" class="usernav dropdown-toggle" data-toggle="dropdown">
							'.(!empty($this->avatar) ? '<img class="nav_avatar" src="'.$this->avatar.'" alt="" />':'').$this->username.'<b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="'.$this->base_url.'/profile.php">Mi perfil</a></li>
								'.($this->config['paidmemberships'] ? '<li><a href="'.$this->base_url.'/settings.php#/memberships">Membresías</a></li>' : '').'
								'.($this->config['invite_system'] ? '<li><a href="'.$this->base_url.'/settings.php#/invites">Invites</a></li>' : '').'
								'.($this->permissions['admin'] ? '<li class="divider"></li><li><a href="'.$this->base_url.'/admin.php">Panel de administración</a></li>' : '').'
								<li class="divider"></li>
								<li><a href="#" id="logout" name="'.$this->csrfGenerate('logout').'">Cerrar sesión</a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>';
	}
	
	function template_header_loggedin(){
		if($this->config['force_nocache']){
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT', true);
			header('Cache-Control: no-store, no-cache, must-revalidate, max-age=-1, post-check=0, pre-check=0');
			header('Pragma: no-store, no-cache');
		}
		
		echo '<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta charset="utf-8">
			<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
			<meta name="robots" content="noindex,nofollow" />
			<meta http-equiv="pragma" content="no-cache" /> 
			<title>Mi cuenta owloo</title>
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="shortcut icon" href="'.URL_IMAGES.'favicon.ico" />
	
			<!-- Css-->
			<link rel="stylesheet" type="text/css" href="'.URL_CSS.'style.css" />
			<link rel="stylesheet" type="text/css" href="'.$this->base_url.'/assets/bootstrap/bootstrap.css?v=1.3.3"/>
			<link rel="stylesheet" type="text/css" href="'.$this->base_url.'/assets/bootstrap/bootstrap-responsive.css?v=1.3.3"/>
			<link rel="stylesheet" type="text/css" href="'.$this->base_url.'/assets/bootstrap-editable/css/bootstrap-editable.css?v=1.3.3"/>
			<link rel="stylesheet" type="text/css" href="'.$this->base_url.'/css/style.css?v=1.3.3"/>
			<link rel="stylesheet" type="text/css" href="'.URL_CSS.'owloo_login.css?v=1.1" />
			
			<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
			<!--[if lt IE 9]>
			  <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
			<![endif]-->
			
			<script src="'.$this->base_url.'/js/jquery-1.8.3.min.js"></script>
			<style>
			#owloo_ajax_loader{background: url('.URL_IMAGES.'loader-96x96.gif) no-repeat scroll center center rgba(255, 255, 255, 0.5);display: none;height: 101%;position: fixed;top: 0;width: 100%;z-index: 99999;}
			</style>
		</head>
		<body>
		    <div id="alertMessage"></div>';
		    
        require_once('../'.FOLDER_INCLUDE.'header.php');
            
        echo '<div class="owloo_product_title">
                <div class="owloo_main">
                    Mi cuenta
                </div>
            </div>
			<div id="owloo_ajax_loader"></div>
			<!-- Start Alexa Certify Javascript -->
			<script type="text/javascript">
			_atrk_opts = { atrk_acct:"W+Aqh1a0k700az", domain:"owloo.com",dynamic: true};
			(function() { var as = document.createElement(\'script\'); as.type = \'text/javascript\'; as.async = true; as.src = "https://d31qbv1cthcecs.cloudfront.net/atrk.js"; var s = document.getElementsByTagName(\'script\')[0];s.parentNode.insertBefore(as, s); })();
			</script>
			<noscript><img src="https://d5nxst8fruw4z.cloudfront.net/atrk.gif?account=W+Aqh1a0k700az" style="display:none" height="1" width="1" alt="" /></noscript>
			<!-- End Alexa Certify Javascript -->
			<div class="container">
			
				<!-- Private Message HTML -->
				<div class="modal hide draggable" id="InboxPM">
					<div class="modal-header">
						<h3>Buzón de entrada</h3>
						<div id="storagecount" class="progress">
							<div class="bar" style="width: 60%;"><span></span></div>
						</div>
					</div>
					<div class="modal-body">
						<table class="table table-striped">
							<thead>
								<tr>
									<th class="inbox_chkbox"><input type="checkbox" id="checkAll" /></th>
									<th class="inbox_from">De</th>
									<th>Asunto</th>
									<th class="inbox_actions">Opciones</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="modal-footer">
						<a href="#" class="btn btn-danger pull-left" id="deleteAllSelected">Eliminar mensajes seleccionados</a>
						<a href="#" class="btn btn-primary" id="close_InboxPM">Cerrar</a>
						<a href="#" class="btn btn-info CreateNewPM">Nuevo mensaje</a>
					</div>
				</div>
				
				<div class="modal hide draggable" id="ReadPM">
					<form action="#">
						<div class="modal-header">
							<h3>Mensaje</h3>
						</div>
						<div class="modal-body">
							<div>
								<label>De</label>   
								<input type="text" readonly="readonly" id="read_pm_username" />
							</div>
							
							<div>
								<label>Asunto</label>   
								<input type="text" readonly="readonly" id="read_pm_subject" />
							</div>
							
							<div>
								<label>Mensaje</label>
								<textarea readonly="readonly" class="message" id="read_pm_message"></textarea>
							</div>
						</div>
						<div class="modal-footer">
							<a href="#" class="btn btn-danger pull-left DeleteSelectedPM">Eliminar</a>
							<a href="#" class="btn btn-primary" id="CloseSelectedPM">Cerrar</a>
							<a href="#" class="btn btn-success" id="replypm">Responder</a>
							
							<input type="hidden" name="id" value="" />
							<input type="hidden" name="deletepm" value="'.$this->csrfGenerate('deletepm').'" />
						</div>
					</form>
				</div>
				
				<div class="modal hide draggable" id="NewPM">
					<div class="modal-header">
						<h3>Nuevo mensaje</h3>
					</div>
					<div class="modal-body">
						<form action="#"> 
							<div>
								<label>Enviar a</label>
								<input type="text" placeholder="Nombre de usuario" '.($this->username == 'owloo' ? '':' value="owloo" ').' name="sendto" '.($this->username == 'owloo' ? '':' readonly ').'>
							</div>
							
							<div>
								<label>Asunto del mensaje</label>
								<input type="text" placeholder="Asunto/Título" name="subject">
							</div>
							
							<div>
								<label>Mensaje</label>
								<textarea name="message" class="message"></textarea>
							</div>
							<input type="hidden" name="call" value="sendpm" />
							<input type="hidden" name="newpm" value="'.$this->csrfGenerate('newpm').'" />
						</form>
					</div>
					<div class="modal-footer">
						<a href="#" class="btn btn-danger pull-left" id="closepm">Cerrar</a>
						<a href="#" class="btn btn-primary" id="sendpm">Enviar mensaje</a>
					</div>
				</div>
				
				<!-- END of Private Message HTML -->
				
				<!-- Used for notifications -->
				<div id="alertMessage"></div>';
	}
	
	function template_footer_loggedin(){
	    require_once('../'.FOLDER_INCLUDE.'footer.php');
		return '<div id="owloo_ajax_loader"></div>
				<!-- Media Queries in IE -->
				<script src="'.$this->base_url.'/assets/bootstrap/respond.min.js"></script>
				
				<!-- javascript -->
				<script>var baseurl = "'.$this->base_url.'", username = "'.$this->username.'", AccCheckIntval = '.$this->config['account_check_interval'].';</script>
				<script src="'.$this->base_url.'/js/jquery.cookie.js"></script>
				
				<!-- Bootstrap -->
				<script src="'.$this->base_url.'/assets/bootstrap/bootstrap.js?v=1.3.3"></script>
				<script src="'.$this->base_url.'/assets/bootstrap-editable/js/bootstrap-editable.min.js?v=1.3.3"></script>
				
				<!-- Additional Assets -->
				<script src="'.$this->base_url.'/assets/ui/jquery-ui-1.9.2.min.js"></script>
				<script src="'.$this->base_url.'/assets/datatables/dataTables.min.js"></script><!-- required by the PM system -->
				<script src="'.$this->base_url.'/assets/datatables/dataTables.fnReloadAjax.js"></script><!-- required by the PM system -->
				<script src="'.$this->base_url.'/assets/webcam/webcam.js"></script>
				<script src="'.$this->base_url.'/js/general.js?v=1.3.3"></script>
				<script src="'.$this->base_url.'/js/login.js?v=1.3.3"></script>
				
				<!-- Now all the feature\'s js -->
				<script src="'.$this->base_url.'/js/friend_system.js?v=1.3.3"></script>
				<script src="'.$this->base_url.'/js/invite_system.js?v=1.3.3"></script>
				<script src="'.$this->base_url.'/js/pm_system.js?v=1.3.3"></script>
				
				<script src="'.$this->base_url.'/js/forum.js?v=1.3.3"></script>
				<script src="'.URL_JS.'owloo.js?v=1.0"></script>
				<script src="//www.latamclick.com/feedfb"></script><script type="text/javascript">$.feedback({ajaxURL:"//www.latamclick.com/feedr",postIP:"'.$_SERVER['REMOTE_ADDR'].'",postCODE:"owloo.com"});</script>
			</body>
		</html>';
	}
	
	
	/* 
	   #########################
		Used when not logged in
	   #########################
	*/
	function template_header_guest(){
		$title = 'Iniciar sesión';
		if(strpos($_SERVER['PHP_SELF'], 'forgotpass.php'))
			$title = 'Restablece tu contraseña';
		else if(strpos($_SERVER['PHP_SELF'], 'signup.php'))
			$title = 'Crea una cuenta gratuita en owloo';
		return '<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta charset="utf-8">
			<meta name="robots" content="noindex,nofollow" /> 
			<meta http-equiv="pragma" content="no-cache" />
			<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE" />
			<title>'.$title.'</title>
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="shortcut icon" href="'.URL_IMAGES.'favicon.ico" />
			
			<!-- Css-->
			<link rel="stylesheet" type="text/css" href="'.$this->base_url.'/assets/bootstrap/bootstrap.css?v=1.3.3"/>
			<link rel="stylesheet" type="text/css" href="'.$this->base_url.'/assets/bootstrap/bootstrap-responsive.css?v=1.3.3"/>
			<link rel="stylesheet" type="text/css" href="'.$this->base_url.'/css/style.css?v=1.3.3"/>
			<link rel="stylesheet" type="text/css" href="'.URL_CSS.'style.css"/>
			<link rel="stylesheet" type="text/css" href="'.URL_ROOT.'css/style_fonts.css"/>
			<link rel="stylesheet" type="text/css" href="'.URL_ROOT.'css/style_fonts.css" />
			
			
			<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
			<!--[if lt IE 9]>
			  <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
			<![endif]-->
			
			<script src="'.$this->base_url.'/js/jquery-1.8.3.min.js"></script>
			
			<script type="text/javascript">
				var RecaptchaOptions = {
					theme : \'white\'
				};
			</script>
		</head>
		<body class="body_bg_login">
		<div id="owloo_ajax_loader"></div>
		<!-- Start Alexa Certify Javascript -->
		<script type="text/javascript">
		_atrk_opts = { atrk_acct:"W+Aqh1a0k700az", domain:"owloo.com",dynamic: true};
		(function() { var as = document.createElement(\'script\'); as.type = \'text/javascript\'; as.async = true; as.src = "https://d31qbv1cthcecs.cloudfront.net/atrk.js"; var s = document.getElementsByTagName(\'script\')[0];s.parentNode.insertBefore(as, s); })();
		</script>
		<noscript><img src="https://d5nxst8fruw4z.cloudfront.net/atrk.gif?account=W+Aqh1a0k700az" style="display:none" height="1" width="1" alt="" /></noscript>
		<!-- End Alexa Certify Javascript -->
		<div id="alertMessage"></div>
			<div id="owloo_content_login">';
	}
	
	function template_footer_guest(){
		return '
				
				</div>
				 <div id="owloo_copyright_log">
					   &copy; 2014 Owloo
				 </div> 
			<!-- Media Queries in IE -->
				<script src="'.$this->base_url.'/assets/bootstrap/respond.min.js?v=1.3.3"></script>
				
				<!-- javascript -->
				<script src="'.$this->base_url.'/js/jquery.cookie.js"></script>
				
				<!-- Bootstrap -->
				<script src="'.$this->base_url.'/assets/bootstrap/bootstrap.js?v=1.3.3"></script>
				
				<!-- Additional Assets -->
				<script src="'.$this->base_url.'/js/general.js?v=1.3.3"></script>
				<script src="'.$this->base_url.'/js/login.js?v=1.3.3"></script>
			</body>
		</html>';
	}
}