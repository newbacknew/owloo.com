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
	
	function template_header_loggedin(){
		if($this->config['force_nocache']){
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT', true);
			header('Cache-Control: no-store, no-cache, must-revalidate, max-age=-1, post-check=0, pre-check=0');
			header('Pragma: no-store, no-cache');
		}
		
		return '<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta charset="utf-8">
			<title>Solid PHP User Management System</title>
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="" />
			<meta name="keywords" content="" />
			<meta name="author" content="Zolidweb.com">
	
			<!-- Css-->
			<link rel="stylesheet" type="text/css" href="'.$this->base_url.'/assets/bootstrap/bootstrap.css?v=1.3.3"/>
			<link rel="stylesheet" type="text/css" href="'.$this->base_url.'/assets/bootstrap/bootstrap-responsive.css?v=1.3.3"/>
			<link rel="stylesheet" type="text/css" href="'.$this->base_url.'/assets/bootstrap-editable/css/bootstrap-editable.css?v=1.3.3"/>
			<link rel="stylesheet" type="text/css" href="'.$this->base_url.'/css/style.css?v=1.3.3"/>
			
			<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
			<!--[if lt IE 9]>
			  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
			<![endif]-->
			
			<script src="'.$this->base_url.'/js/jquery-1.8.3.min.js"></script>
		</head>
		<body>
			<div class="navbar navbar-fixed-top">
				<div class="navbar-inner">
					<div class="container">
						<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</a>
						<a class="brand" href="#">Solid UMS</a>
						<div class="nav-collapse">
							<ul class="nav">
								<li '.($this->curpage == 'index' ? 'class="active"' : '').'><a href="'.$this->base_url.'/index.php">Home</a></li>
								<li '.($this->curpage == 'forum' ? 'class="active"' : '').'><a href="'.$this->base_url.'/forum.php">Forum</a></li>
								<li class="divider-vertical"></li>
							</ul>
							
							<ul class="nav pull-right">
							
								'.($this->config['friend_system'] ? '
								<li '.($this->curpage == 'friends' ? 'class="active"' : '').'><a href="'.$this->base_url.'/friends.php"><span title="Online Friends" class="badge badge-info badge-friends">0</span> Friends</a></li>' : '').'
								
								'.($this->config['pm_system'] ? '
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span title="New Messages" class="badge badge-info badge-pm">0</span> New Message<b class="caret"></b></a>
									<ul class="dropdown-menu">
										<li><a href="#" class="showpmlist">View Inbox</a></li>
										<li class="divider"></li>
										<li><a href="#" class="CreateNewPM">New Message</a></li>
									</ul>
								</li>
								' : '').'
								
								<li class="divider-vertical"></li>
								<li class="dropdown">
									<a href="#" class="usernav dropdown-toggle" data-toggle="dropdown">
									<img class="nav_avatar" src="'.(!empty($this->avatar) ? $this->base_url.'/uploads/avatars/s/'.$this->avatar : $this->base_url.'/images/default_small.png').'" alt="" />
									
									'.$this->username.'<b class="caret"></b></a>
									<ul class="dropdown-menu">
										<li><a href="'.$this->base_url.'/profile.php">Profile</a></li>
										<li><a href="'.$this->base_url.'/settings.php">Settings</a></li>
										'.($this->config['paidmemberships'] ? '<li><a href="'.$this->base_url.'/settings.php#/memberships">Memberships</a></li>' : '').'
										'.($this->config['invite_system'] ? '<li><a href="'.$this->base_url.'/settings.php#/invites">Invites</a></li>' : '').'
										'.($this->permissions['admin'] ? '<li class="divider"></li><li><a href="'.$this->base_url.'/admin.php">Admin Panel</a></li>' : '').'
										<li class="divider"></li>
										<li><a href="#" id="logout" name="'.$this->csrfGenerate('logout').'">Logout</a></li>
									</ul>
								</li>
							</ul>
							
							
							
							<form class="navbar-search pull-right" action="#" onsubmit="return false;">
								<input id="top_search" type="text" class="search-query span2" placeholder="Search for user.."  autocomplete="off">
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="container">
			
				<!-- Private Message HTML -->
				<div class="modal hide draggable" id="InboxPM">
					<div class="modal-header">
						<h3>PM Inbox</h3>
						<div id="storagecount" class="progress">
							<div class="bar" style="width: 60%;"><span></span></div>
						</div>
					</div>
					<div class="modal-body">
						<table class="table table-striped">
							<thead>
								<tr>
									<th class="inbox_chkbox"><input type="checkbox" id="checkAll" /></th>
									<th class="inbox_from">From</th>
									<th>Subject</th>
									<th class="inbox_actions">Actions</th>
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
						<a href="#" class="btn btn-danger pull-left" id="deleteAllSelected">Delete Selected Messages</a>
						<a href="#" class="btn btn-primary" id="close_InboxPM">Close Inbox</a>
						<a href="#" class="btn btn-info CreateNewPM">New PM</a>
					</div>
				</div>
				
				<div class="modal hide draggable" id="ReadPM">
					<form action="#">
						<div class="modal-header">
							<h3>Read PM</h3>
						</div>
						<div class="modal-body">
							<div>
								<label>From</label>   
								<input type="text" readonly="readonly" id="read_pm_username" />
							</div>
							
							<div>
								<label>Subject</label>   
								<input type="text" readonly="readonly" id="read_pm_subject" />
							</div>
							
							<div>
								<label>Message</label>
								<textarea readonly="readonly" class="message" id="read_pm_message"></textarea>
							</div>
						</div>
						<div class="modal-footer">
							<a href="#" class="btn btn-danger pull-left DeleteSelectedPM">Delete PM</a>
							<a href="#" class="btn btn-primary" id="CloseSelectedPM">Close</a>
							<a href="#" class="btn btn-success" id="replypm">Send Reply</a>
							
							<input type="hidden" name="id" value="" />
							<input type="hidden" name="deletepm" value="'.$this->csrfGenerate('deletepm').'" />
						</div>
					</form>
				</div>
				
				<div class="modal hide draggable" id="NewPM">
					<div class="modal-header">
						<h3>New PM</h3>
					</div>
					<div class="modal-body">
						<form action="#"> 
							<div>
								<label>Send To</label>
								<input type="text" placeholder="Username" name="sendto">
							</div>
							
							<div>
								<label>Message Subject</label>
								<input type="text" placeholder="Subject/Title" name="subject">
							</div>
							
							<div>
								<label>Message</label>
								<textarea name="message" class="message"></textarea>
							</div>
							<input type="hidden" name="call" value="sendpm" />
							<input type="hidden" name="newpm" value="'.$this->csrfGenerate('newpm').'" />
						</form>
					</div>
					<div class="modal-footer">
						<a href="#" class="btn btn-danger pull-left" id="closepm">Close</a>
						<a href="#" class="btn btn-primary" id="sendpm">Send PM</a>
					</div>
				</div>
				
				<!-- END of Private Message HTML -->
				
				<!-- Used for notifications -->
				<div id="alertMessage"></div>';
	}
	
	function template_footer_loggedin(){
		return '</div>
				<div id="footer">
					<div>
						Copyright 2012 &copy; by Zolid Web Solutions '.$this->debugdata().'
						<div class="pull-right">'.$this->show_change_lang(true).'</div>
					</div>
				</div>
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
				
				<!-- Now all the feature\'s js -->
				<script src="'.$this->base_url.'/js/friend_system.js?v=1.3.3"></script>
				<script src="'.$this->base_url.'/js/invite_system.js?v=1.3.3"></script>
				<script src="'.$this->base_url.'/js/pm_system.js?v=1.3.3"></script>
				
				<script src="'.$this->base_url.'/js/forum.js?v=1.3.3"></script>
			</body>
		</html>';
	}
	
	
	/* 
	   #########################
		Used when not logged in
	   #########################
	*/
	function template_header_guest(){
		return '<!DOCTYPE html>
		<html lang="en">
		  <head>
			<meta charset="utf-8">
			<title>Solid PHP User Management System</title>
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="">
			<meta name="author" content="Zolidweb.com">
			
			<!-- Css-->
			<link rel="stylesheet" type="text/css" href="'.$this->base_url.'/assets/bootstrap/bootstrap.css?v=1.3.3"/>
			<link rel="stylesheet" type="text/css" href="'.$this->base_url.'/assets/bootstrap/bootstrap-responsive.css?v=1.3.3"/>
			<link rel="stylesheet" type="text/css" href="'.$this->base_url.'/css/style.css?v=1.3.3"/>
			
			<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
			<!--[if lt IE 9]>
			  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
			<![endif]-->
			
			<script src="'.$this->base_url.'/js/jquery-1.8.3.min.js"></script>
			
			<script type="text/javascript">
				var RecaptchaOptions = {
					theme : \'white\'
				};
			</script>
		</head>
		<body>
		<div id="alertMessage"></div>
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a class="brand" href="#">Solid UMS</a>
					<div class="nav-collapse">
						<ul class="nav">
							<li '.($this->curpage == 'login' 	  ? 'class="active"' : '').'><a href="'.$this->base_url.'/login.php">Login</a></li>
							<li '.($this->curpage == 'forgotpass' ? 'class="active"' : '').'><a href="'.$this->base_url.'/forgotpass.php">Forgot Password</a></li>
							<li '.($this->curpage == 'signup'     ? 'class="active"' : '').'><a href="'.$this->base_url.'/signup.php">Signup</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="container">';
	}
	
	function template_footer_guest(){
		return '</div>
				<div id="footer">
					<div>Copyright 2012 &copy; by Zolid Web Solutions '.$this->debugdata().'</div>
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