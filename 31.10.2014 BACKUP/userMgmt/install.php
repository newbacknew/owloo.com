<?php
/*****************************************************************************************
 * Solid PHP User Management System														 *
 * Copyright 2012 Mark Eliasen (MrEliasen)												 *
 *																						 *
 * CodeCanyon Link: http://codecanyon.net/item/solid-php-user-management-system-/1254295 *
 * Author Website: http://zolidweb.com													 *
 * Version: 1.4.0 																		 *
 *****************************************************************************************/
define('INSTALLER', true);
require_once('system/initiater.php');

if(!empty($_POST['sqlhost'])){
	$status = true;
	$msg = '';
	
	try{	
		$sql = new PDO("mysql:host=".$_POST['sqlhost'].";port=".$_POST['sqlport'].";dbname=".$_POST['sqldb'].";charset=utf8", $_POST['sqlusername'], $_POST['sqlpassword']);
	}catch(PDOException $pe){
		$status = false;
		$msg = 'Unable to connect to database.';
	}
	
	if( !empty($_POST['settings_writepermissions']) && ( $_POST['settings_writepermissions'] == '0755' || $_POST['settings_writepermissions'] == '0777') )
	{
		chmod(dirname(__FILE__) . "/system/config.php", octdec( $_POST['settings_writepermissions'] ) );
		chmod(dirname(__FILE__) . "/system/hybridauth/config.php", octdec( $_POST['settings_writepermissions'] ) );
	}
	
	if( !$site->saveSettings() )
	{
		$status = false;
		$msg = 'Unable to write settings to the config file.';
	}
	
	if($status){
		$stmt = $sql->exec("
		CREATE TABLE IF NOT EXISTS `forum_categories` (
		  `c_id` smallint(5) NOT NULL AUTO_INCREMENT,
		  `c_sort` smallint(3) NOT NULL,
		  `c_sub` tinyint(1) NOT NULL,
		  `c_name` char(128) NOT NULL,
		  `c_permissions` text NOT NULL,
		  PRIMARY KEY (`c_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

		CREATE TABLE IF NOT EXISTS `forum_posts` (
		  `p_id` int(10) NOT NULL AUTO_INCREMENT,
		  `p_author` int(12) NOT NULL,
		  `p_postdate` int(11) NOT NULL,
		  `p_replydate` int(11) NOT NULL,
		  `p_istopic` tinyint(1) NOT NULL DEFAULT '0',
		  `p_topictitle` char(128) NOT NULL,
		  `p_catid` smallint(5) NOT NULL,
		  `p_topicid` int(10) NOT NULL,
		  `p_post` mediumtext NOT NULL,
		  `p_sticky` tinyint(1) NOT NULL DEFAULT '0',
		  `p_locked` tinyint(1) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`p_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='p_sticky' ;

		CREATE TABLE IF NOT EXISTS `friends` (
		  `f_id` int(24) NOT NULL,
		  `user_id` int(10) NOT NULL,
		  `friend_id` int(10) NOT NULL,
		  `status` enum('pending','accepted') NOT NULL,
		  `date` int(30) NOT NULL,
		  UNIQUE KEY `f_id` (`f_id`),
		  KEY `user_id` (`user_id`,`friend_id`),
		  KEY `friend_id` (`friend_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;

		CREATE TABLE IF NOT EXISTS `invites` (
		  `i_id` int(12) NOT NULL AUTO_INCREMENT,
		  `i_by` int(12) NOT NULL,
		  `i_to` char(100) NOT NULL,
		  `i_acceptedby` int(11) NOT NULL,
		  `i_code` char(32) NOT NULL,
		  `i_status` tinyint(1) NOT NULL,
		  `i_date` int(12) NOT NULL,
		  PRIMARY KEY (`i_id`),
		  UNIQUE KEY `i_to` (`i_to`),
		  KEY `i_by` (`i_by`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

		CREATE TABLE IF NOT EXISTS `members` (
		  `id` int(12) NOT NULL AUTO_INCREMENT,
		  `username` char(50) NOT NULL,
		  `triggers` text NOT NULL,
		  `password` char(64) NOT NULL,
		  `membergroup` tinyint(4) NOT NULL DEFAULT '1',
		  `other_membergroups` char(255) NOT NULL,
		  `avatar` char(100) NOT NULL,
		  `reset_key` char(40) NOT NULL,
		  `reset_timer` int(11) NOT NULL,
		  `email` char(100) NOT NULL,
		  `date_registered` int(12) NOT NULL,
		  `lastactivity` int(12) NOT NULL,
		  `activation_key` char(22) NOT NULL,
		  `session` char(64) NOT NULL,
		  `account_key` char(8) NOT NULL,
		  `firstadmin` tinyint(1) NOT NULL DEFAULT '0',
		  `invites` smallint(5) unsigned NOT NULL DEFAULT '0',
		  `subscription_id` int(10) NOT NULL,
		  `subscription_start` int(11) NOT NULL,
		  `f_posts` mediumint(7) NOT NULL,
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `username` (`username`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

		CREATE TABLE IF NOT EXISTS `membership_plans` (
		  `ms_id` int(10) NOT NULL AUTO_INCREMENT,
		  `ms_name` char(200) NOT NULL,
		  `ms_description` text NOT NULL,
		  `ms_price` decimal(10,2) NOT NULL,
		  `ms_duration` int(10) NOT NULL,
		  `ms_durationtype` tinyint(1) NOT NULL,
		  `ms_groupid` int(10) NOT NULL,
		  PRIMARY KEY (`ms_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

		CREATE TABLE IF NOT EXISTS `member_groups` (
		  `id` int(10) NOT NULL AUTO_INCREMENT,
		  `name` char(30) NOT NULL,
		  `colour` char(6) NOT NULL,
		  `default_group` tinyint(1) NOT NULL DEFAULT '0',
		  `permissions` text NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

		INSERT INTO `member_groups` (`id`, `name`, `colour`, `default_group`, `permissions`) VALUES
		(1, 'Member', '207bdf', 1, 'a:11:{s:7:\"pm_read\";b:1;s:8:\"pm_write\";b:1;s:8:\"pm_limit\";i:100;s:12:\"friends_view\";b:1;s:14:\"friends_reqest\";b:1;s:12:\"account_edit\";b:1;s:14:\"account_delete\";b:1;s:5:\"admin\";b:0;s:9:\"viewlevel\";i:1;s:11:\"invite_send\";b:1;s:13:\"invite_revoke\";b:1;}'),
		(2, 'VIP', 'e3e035', 0, 'a:11:{s:7:\"pm_read\";b:1;s:8:\"pm_write\";b:1;s:8:\"pm_limit\";i:300;s:12:\"friends_view\";b:1;s:14:\"friends_reqest\";b:1;s:12:\"account_edit\";b:1;s:14:\"account_delete\";b:1;s:5:\"admin\";b:0;s:9:\"viewlevel\";i:10;s:11:\"invite_send\";b:1;s:13:\"invite_revoke\";b:1;}'),
		(3, 'Admin', 'd2141d', 0, 'a:11:{s:7:\"pm_read\";b:1;s:8:\"pm_write\";b:1;s:8:\"pm_limit\";s:4:\"1000\";s:12:\"friends_view\";b:1;s:14:\"friends_reqest\";b:1;s:12:\"account_edit\";b:1;s:14:\"account_delete\";b:0;s:5:\"admin\";b:1;s:9:\"viewlevel\";s:3:\"999\";s:11:\"invite_send\";b:1;s:13:\"invite_revoke\";b:1;}');

		CREATE TABLE IF NOT EXISTS `member_payments` (
		  `id` int(10) NOT NULL AUTO_INCREMENT,
		  `user_id` int(12) NOT NULL,
		  `plan_id` int(10) NOT NULL,
		  `g_id` int(10) NOT NULL,
		  `plan_name` char(100) NOT NULL,
		  `payment_date` int(11) NOT NULL,
		  `expiry_date` int(11) NOT NULL,
		  `plan_length` int(11) NOT NULL,
		  `amount` decimal(10,2) NOT NULL,
		  `returndata` text NOT NULL,
		  `status` char(20) NOT NULL DEFAULT '0',
		  `trans_id` char(20) NOT NULL,
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `trans_id` (`trans_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

		CREATE TABLE IF NOT EXISTS `private_messages` (
		  `pm_id` int(10) NOT NULL AUTO_INCREMENT,
		  `to_user` int(12) NOT NULL,
		  `from_user` int(12) NOT NULL,
		  `subject` char(200) NOT NULL,
		  `message` text NOT NULL,
		  `date` int(30) NOT NULL,
		  `status` enum('unread','read') NOT NULL DEFAULT 'unread',
		  PRIMARY KEY (`pm_id`),
		  KEY `to_user` (`to_user`),
		  KEY `from_user` (`from_user`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

		CREATE TABLE IF NOT EXISTS `profiles` (
		  `u_id` int(10) NOT NULL,
		  `f_id` int(10) NOT NULL,
		  `f_val` text NOT NULL,
		  KEY `f_id` (`f_id`),
		  KEY `u_id` (`u_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;

		CREATE TABLE IF NOT EXISTS `profile_fields` (
		  `p_id` int(10) NOT NULL AUTO_INCREMENT,
		  `p_type` tinyint(1) NOT NULL,
		  `p_options` text NOT NULL,
		  `p_label` char(75) NOT NULL,
		  `p_group` char(50) NOT NULL,
		  `p_profile` tinyint(1) NOT NULL DEFAULT '0',
		  `p_signup` tinyint(1) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`p_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

		CREATE TABLE IF NOT EXISTS `sessions` (
		  `session_id` char(64) NOT NULL DEFAULT '',
		  `session_data` text NOT NULL,
		  `session_expire` int(20) NOT NULL DEFAULT '0',
		  `session_agent` char(64) NOT NULL,
		  `session_ip` char(64) NOT NULL,
		  `session_host` char(64) NOT NULL,
		  `session_key` char(64) NOT NULL,
		  PRIMARY KEY (`session_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;

		CREATE TABLE IF NOT EXISTS `social_connect` (
		  `member_id` int(12) NOT NULL,
		  `provider_name` char(20) NOT NULL,
		  `provider_uid` char(255) NOT NULL,
		  `provider_identify` char(35) NOT NULL,
		  UNIQUE KEY `provider_identify` (`provider_identify`),
		  KEY `member_id` (`member_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;

		ALTER TABLE `friends`
		  ADD CONSTRAINT `friends_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
		  ADD CONSTRAINT `friends_ibfk_2` FOREIGN KEY (`friend_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

		ALTER TABLE `invites`
		  ADD CONSTRAINT `invites_ibfk_2` FOREIGN KEY (`i_by`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

		ALTER TABLE `private_messages`
		  ADD CONSTRAINT `private_messages_ibfk_1` FOREIGN KEY (`to_user`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

		ALTER TABLE `profiles`
		  ADD CONSTRAINT `profiles_ibfk_1` FOREIGN KEY (`f_id`) REFERENCES `profile_fields` (`p_id`) ON DELETE CASCADE ON UPDATE CASCADE,
		  ADD CONSTRAINT `profiles_ibfk_2` FOREIGN KEY (`u_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

		ALTER TABLE `social_connect`
		  ADD CONSTRAINT `social_connect_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;");
	}
	 
	echo json_encode(array('status'=>$status, 'message'=>$msg));
	exit;
}

if(!empty($_POST['signup_username'])){
	$status = $site->process_register();
	
	if($status['status']){
		@unlink(dirname( __FILE__ ).'/install.php');
	}
	
	echo json_encode($status);
	exit;
}

$admin = $site->checkFirstAdmin();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Solig PHP User Management System</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="Zolidweb.com">

		<!-- Css-->
		<link rel="stylesheet" type="text/css" href="assets/bootstrap/bootstrap.css?v=1.3.3"/>
		<link rel="stylesheet" type="text/css" href="assets/bootstrap/bootstrap-responsive.css?v=1.3.3"/>
		<link rel="stylesheet" type="text/css" href="css/style.css?v=1.3.3"/>
		
		<style type="text/css">
			#createadmin,
			#finished{
				display:none;
			}
		</style>
		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	</head>
	<body>
		<div id="alertMessage"></div>
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="brand" href="#">Solid UMS - Installer</a>
				</div>
			</div>
		</div>
		
		<div class="container">
			<div class="row">
				<div class="span6">
					<form id="testconnection" class="form-horizontal">
						<legend>Permissios</legend>
						<div class="control-group">
							<label class="control-label" for="settings_writepermissions">Read/Write Permissions</label>
							<div class="controls">
								<input type="text" id="settings_writepermissions" name="settings_writepermissions" placeholder="0755" value="0755">
							</div>
							<p><small>This is by default (depending on your webhost) either 0755 or 0777. If the install fails with 0755, try again with 0777.</small></p>
						</div>
						
						<legend>Database Settings</legend>
						<div class="control-group">
							<label class="control-label" for="sqlhost">Host</label>
							<div class="controls">
								<input type="text" id="sqlhost" name="sqlhost" placeholder="Host" value="localhost">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="sqlport">Port</label>
							<div class="controls">
								<input type="text" id="sqlport" name="sqlport" placeholder="Port" value="3306">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="sqldb">Database Name</label>
							<div class="controls">
								<input type="text" id="sqldb" name="sqldb" placeholder="Database Name" value="">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="sqlusername">Username</label>
							<div class="controls">
								<input type="text" id="sqlusername" name="sqlusername" placeholder="Username">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="sqlpassword">Password</label>
							<div class="controls">
								<input type="password" id="sqlpassword" name="sqlpassword" placeholder="Password">
							</div>
						</div>
						<div class="control-group">
							<div class="controls">
								<button type="submit" class="btn" data-loading-text="Installing..">Install Database</button>
							</div>
						</div>
						<input type="hidden" name="settings_baseurl" value="<?php echo 'http://'.str_replace('/install.php','',$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]); ?>" />
						<input type="hidden" name="settings_uploaddir" value="<?php echo dirname(__FILE__)?>/uploads/avatars" />
					</form>
				</div>
				<div class="span6">
					<form id="createadmin" class="form-horizontal">
						<legend>Admin Account</legend>
						<div class="control-group">
							<label class="control-label" for="signup_username">Admin Username</label>
							<div class="controls">
								<input type="text" id="signup_username" name="signup_username" placeholder="Admin Username">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="signup_email">Admin Email</label>
							<div class="controls">
								<input type="text" id="signup_email" name="signup_email" placeholder="Admin Email">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="signup_password">Password</label>
							<div class="controls">
								<input type="password" id="signup_password" name="signup_password" placeholder="Password">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="signup_password2">Confirm Password</label>
							<div class="controls">
								<input type="password" id="signup_password2" name="signup_password2" placeholder="Confirm Password">
							</div>
						</div>
						<div class="control-group">
							<div class="controls">
								<button type="submit" class="btn">Create Account</button>
							</div>
						</div>
					</form>
					<div class="clearfix"></div>
					<div id="finished">
						<p>Installation complete!<br>
						Remember to make sure the install.php file has been deleted (automatic deletion is not supported on all servers). <a href="login.php">Click here</a> to login to your new website.</p>
						<p>Also, check out the <a href="examples/index.php">Examples</a> on how to develop on this system!</p>
					</div>
				</div>
			</div>
		</div>
		
		<div id="footer">
			<div>
				Copyright 2012 &copy; by Zolid Web Solutions
			</div>
		</div>
		<!-- Media Queries in IE -->
		<script src="assets/bootstrap/respond.min.js"></script>
				
		<!-- Bootstrap -->
		<script src="js/jquery-1.8.3.min.js"></script>
		<script src="js/general.js"></script>
		<script src="assets/bootstrap/bootstrap.js?v=1.3.3"></script>
		
		<!-- Additional Assets -->
		<script>
			$(document).ready(function(){
				<?php
					if($site->installed){
						echo '$("#testconnection button").html("Connected Successfully!").addClass("btn-success").attr("disabled", "true");
						$("#createadmin").fadeIn(500);';
					}
					
					if($admin){
						echo '$("#createadmin button").html("Admin Already Created").addClass("btn-success").attr("disabled", "true");';
					}
				?>
				
				$("#testconnection").submit(function(e){
					hideNotification();
					setLoading('Installing, this might take longer than a few seconds..',true);
					$.ajax({
						url: 'install.php',
						type: 'POST',
						data: $('#testconnection').serialize(),
						dataType: 'json',
						success: function(data) {
							removeLoading();
							if(data.status){
								$("#testconnection button").html('Installed Successfully!').addClass('btn-success').attr('disabled', 'true');
								$("#createadmin").fadeIn(500);
							}else{
								showNotification(data.message, 'Error', 'error');
							}
						}
					});
					
					return false;
					e.preventDefault();
				});
				
				$("#createadmin").submit(function(e){
					hideNotification();
					setLoading('Creating admin account..',true);
					$.ajax({
						url: 'install.php',
						type: 'POST',
						data: $('#createadmin').serialize(),
						dataType: 'json',
						success: function(data) {
							removeLoading();
							if(data.status){
								$("#createadmin button").html('Admin Created').addClass('btn-success').attr('disabled', 'true');
								showNotification('Your Admin account has been created. <b>Please make sure the install.php has been successfully removed by the system.</b>', 'Success', 'success');
								$('#finished').fadeIn(300);
							}else{
								showNotification(data.message, 'Error', 'error');
							}
						}
					});
					
					return false;
					e.preventDefault();
				});
			})
		</script>
	</body>
</html>