<?php
/*****************************************************************************************
 * Solid PHP User Management System														 *
 * Copyright 2012 Mark Eliasen (MrEliasen)												 *
 *																						 *
 * CodeCanyon: http://codecanyon.net/item/solid-php-user-management-system-/1254295		 *
 * Author Website: http://zolidweb.com													 *
 * Version: 1.3.1 																		 *
 *****************************************************************************************/
 
if(!defined('IN_SYSTEM')){
	exit;
}
class Administration extends Caching{
	private $structure = '';
	private $subs = array();

	public function modify_membergroup(){
		if(!$this->loggedin){
			exit;
		}
		
		//check if the user is admin
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		//check if a group id has been received.
		if(empty($_POST['gid'])){
			return false;
		}
		
		//check if a user id has been received.
		if(empty($_POST['uid'])){
			return false;
		}
		
		//check if the usergroup was found in the database
		$stmt = $this->sql->prepare('SELECT name FROM member_groups WHERE id = ?');
		$stmt->execute(array($_POST['gid']));
		$stmt->closeCursor();
		
		if($stmt->rowCount() < 1){
			return false;
		}
		
		//Sanitize the values, we only want integers.
		$_POST['uid'] = $this->sanitize($_POST['uid'], 'integer');
		$_POST['gid'] = $this->sanitize($_POST['gid'], 'integer');
		
		//set the user's new member group
		$stmt = $this->sql->prepare('UPDATE members SET membergroup = ? WHERE id = ?');
		$stmt->execute(array($_POST['gid'], $_POST['uid']));
		$stmt->closeCursor();
		
		return true;
	}
	
	public function delete_usergroup(){
		if(!$this->loggedin){
			exit;
		}
		//check if the user is admin
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		//check if a group id has been received.
		if(empty($_POST['id'])){
			return false;
		}
		
		//Sanitize the values, we only want integers.
		$_POST['id'] = $this->sanitize($_POST['id'], 'integer');
		
		
		foreach($this->sql->query("SELECT id FROM member_groups WHERE default_group = '1' LIMIT 1") as $row){
			$default = $row['id'];
		}
		
		if($_POST['id'] == $default){
			return false;
		}
		
		$stmt = $this->sql->prepare('UPDATE members SET membergroup = ? WHERE membergroup = ?');
		$stmt->execute(array($default, $_POST['id']));
		$stmt->closeCursor();
		$stmt = null;
		
		$stmt = $this->sql->prepare('DELETE FROM member_groups WHERE id = ?');
		$stmt->execute(array($_POST['id']));
		$stmt->closeCursor();
		$stmt = null;
		
		return true;
	}
	
	public function update_usergroup(){
		if(!$this->loggedin){
			exit;
		}
		//check if the user is admin
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		//check if a group id has been received.
		if(empty($_POST['groupid'])){
			return false;
		}
		
		//Sanitize the values, we only want integers.
		$_POST['groupname'] = $this->sanitize($_POST['groupname'], 'string');
		$_POST['groupid'] = $this->sanitize($_POST['groupid'], 'integer');
		$_POST['groupcolour'] = $this->sanitize( str_replace('#','',$_POST['groupcolour']), 'string');
		$_POST['accdefault'] = (!empty($_POST['accdefault']) && $_POST['accdefault'] == 'on' ? 1 : 0);
		
		$permissions = array('pm_read' => (!empty($_POST['perm_readpm']) && $_POST['perm_readpm'] == 'on' ? true : false),
							 'pm_write' => (!empty($_POST['perm_sendpm']) && $_POST['perm_sendpm'] == 'on' ? true : false),
							 'pm_limit' => (!empty($_POST['perm_limitpm']) ? $this->sanitize($_POST['perm_limitpm'], 'integer') : 10),
							 'friends_view' => (!empty($_POST['perm_viewfriend']) && $_POST['perm_viewfriend'] == 'on' ? true : false),
							 'friends_reqest' => (!empty($_POST['perm_sendfriend']) && $_POST['perm_sendfriend'] == 'on' ? true : false),
							 'account_edit' => (!empty($_POST['perm_accedit']) && $_POST['perm_accedit'] == 'on' ? true : false),
							 'account_delete' => (!empty($_POST['perm_accdel']) && $_POST['perm_accdel'] == 'on' ? true : false),
							 'admin' => (!empty($_POST['perm_accadmin']) && $_POST['perm_accadmin'] == 'on' ? true : false),
							 'viewlevel' => (!empty($_POST['perm_viewlevel']) && $_POST['perm_viewlevel'] > 0 ? $this->sanitize($_POST['perm_viewlevel'], 'integer') : 1),
							 'invite_send' => (!empty($_POST['perm_invsend']) && $_POST['perm_invsend'] == 'on' ? true : false),
							 'invite_revoke' => (!empty($_POST['perm_invrevoke']) && $_POST['perm_invrevoke'] == 'on' ? true : false)
		);
		
		//set the user's new member group
		$stmt = $this->sql->prepare(($_POST['accdefault'] ? 'UPDATE member_groups SET default_group = 0; ' : '').'UPDATE member_groups SET name = ?, permissions = ?, colour = ?, default_group = ? WHERE id = ?');
		$stmt->execute(array($_POST['groupname'], serialize($permissions), $_POST['groupcolour'], $_POST['accdefault'], $_POST['groupid']));
		$stmt->closeCursor();
		
		return array('status' => true, 'message' => 'Changes Saved!');
	}
	
	public function process_deleteuser(){
		if(!$this->loggedin){
			exit;
		}
		//check if the user is admin
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			$status['message'] = $this->lang['cl_admin_1'];
			$status['status'] = false;
			return $status;
		}
		
		//check if a user id has been received.
		if(empty($_POST['uid'])){
			$status['message'] = $this->lang['cl_admin_2'];
			$status['status'] = false;
			return $status;
		}
		
		//check if a user id has been received.
		if($_POST['uid'] == $this->uid){
			$status['message'] = $this->lang['cl_admin_3'];
			$status['status'] = false;
			return $status;
		}
		
		//Sanitize the values, we only want integers.
		$_POST['uid'] = $this->sanitize($_POST['uid'], 'integer');
		
		$stmt = $this->sql->prepare('SELECT avatar FROM members WHERE id = ?');
		$stmt->execute(array($_POST['uid']));
		$userdata = $stmt->fetchAll();
		$userdata = $userdata[0];
		
		if(!empty($userdata['avatar'])){
			@unlink($this->config['avatar_dir'].'/b/'.$userdata['avatar']);
			@unlink($this->config['avatar_dir'].'/s/'.$userdata['avatar']);
		}
		
		//set the user's new member group
		$stmt = $this->sql->prepare('DELETE FROM members WHERE id = ?');
		$stmt->execute(array($_POST['uid']));
		$stmt->closeCursor();
		
		$status['status'] = true;
		return $status;
	}
	
	## Get the list of members groups as an array ##
	public function get_membergroups(){
		//check if the user is admin
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		$stmt = $this->sql->query("SELECT id,name FROM member_groups");
		$data = $stmt->fetchAll();
		$stmt->closeCursor();
		
		return $data;
	}
	
	public function saveSettings(){
		if(!defined('INSTALLER') && !$this->loggedin){
			exit;
		}
		//check if the user is admin
		if(!defined('INSTALLER') && (empty($this->permissions['admin']) || !$this->permissions['admin'])){
			return false;
		}
		
		if(defined('INSTALLER')){
			$key1 = $this->randomGenerator();
			$key2 = $this->randomGenerator();
		}
		
		if(!isset($_POST['settings_sessiontime'])){
			$_POST['settings_sessiontime'] = 0;
		}
		
		if($_POST['settings_sessiontime'] >= 1 && $_POST['settings_sessiontime'] < 60){
			$_POST['settings_sessiontime'] = 60;
		}
		
$output = '<?php
/*****************************************************************************************
 * Solid PHP User Management System :: Version '.$this->config['version'].'								 *
 * Copyright 2012 Mark Eliasen (MrEliasen)												 *
 *																						 *
 * CodeCanyon Link: http://codecanyon.net/item/solid-php-user-management-system-/1254295 *
 * Author Website: http://zolidweb.com													 *
 *****************************************************************************************/
 
$config = array();
$config[\'sql_host\'] = \''.(defined('INSTALLER') ? $_POST['sqlhost'] : $this->config['sql_host']).'\';
$config[\'sql_port\'] = \''.(defined('INSTALLER') ? $_POST['sqlport'] : $this->config['sql_port']).'\';
$config[\'sql_user\'] = \''.(defined('INSTALLER') ? $_POST['sqlusername'] : $this->config['sql_user']).'\';
$config[\'sql_pass\'] = \''.(defined('INSTALLER') ? $_POST['sqlpassword'] : $this->config['sql_pass']).'\';
$config[\'sql_db\'] = \''.(defined('INSTALLER') ? $_POST['sqldb'] : $this->config['sql_db']).'\';
$config[\'sql_charset\'] = \''.$this->config['sql_charset'].'\';
## Hash Keys - Do not share these keys with anyone!! ##
$config[\'hashing_salt\'] = \''.(defined('INSTALLER') ? $key1 : $this->config['hashing_salt']).'\';
$config[\'hashing_key\'] = \''.(defined('INSTALLER') ? $key2 : $this->config['hashing_key']).'\';
## General configuration ##
$config[\'version\'] = \''.(defined('UPDATER') && !empty($_POST['set_version']) ? $_POST['set_version'] : $this->config['version']).'\';
$config[\'website_name\'] = \''.(!empty($_POST['settings_website_name']) ? $_POST['settings_website_name'] : $this->config['website_name']).'\';
$config[\'base_url\'] = \''.(!empty($_POST['settings_baseurl']) ? $_POST['settings_baseurl'] : $this->config['base_url']).'\';
$config[\'session_timeout\'] = '.$_POST['settings_sessiontime'].';
$config[\'cache_time\'] = '.(!empty($_POST['settings_cachetime']) ? $_POST['settings_cachetime'] : $this->config['cache_time']).';
$config[\'cache_enabled\'] = '.(!empty($_POST['settings_cacheenabled']) && $_POST['settings_cacheenabled'] == 'on' ? 'true' : 'false').';
$config[\'cache_dir\'] = BASE_PATH.\''.(!empty($_POST['settings_cachedir']) ? $_POST['settings_cachedir'] : '/system/cache').'\';
$config[\'permission_writeable\'] = \''.(!empty($_POST['settings_writepermissions']) ? $_POST['settings_writepermissions'] : $this->config['permission_writeable']).'\';
$config[\'permission_readable\'] = \''.(!empty($_POST['settings_readpermissions']) ? $_POST['settings_readpermissions'] : $this->config['permission_readable']).'\';
$config[\'avatar_dir\'] = \''.(!empty($_POST['settings_uploaddir']) ? $_POST['settings_uploaddir'] : $this->config['avatar_dir']).'\';
$config[\'pm_interval\'] = '.(!empty($_POST['settings_pmspam']) ? $_POST['settings_pmspam'] : $this->config['pm_interval']).';
$config[\'last_activity\'] = '.(!empty($_POST['settings_trackactivity']) && $_POST['settings_trackactivity'] == 'on' ? 'true' : 'false').';
$config[\'dateformat_long\'] = \''.(!empty($_POST['settings_timeformatlong']) ? $_POST['settings_timeformatlong'] : $this->config['dateformat_long']).'\';
$config[\'dateformat_short\'] = \''.(!empty($_POST['settings_timeformatshort']) ? $_POST['settings_timeformatshort'] : $this->config['dateformat_short']).'\';
$config[\'dbsessions\'] = '.(!empty($_POST['dbsession']) && $_POST['dbsession'] == 'enabled' ? 'true' : 'false').';
$config[\'search_intval\'] = '.(!empty($_POST['settings_timebetweensearch']) && $_POST['settings_timebetweensearch'] >= '0' ? $_POST['settings_timebetweensearch'] : '20').';
$config[\'force_nocache\'] =  '.(!empty($_POST['force_nocache']) ? 'true' : 'false').';
## Account Check Interval ##
$config[\'account_check_interval\'] = '.(empty($_POST['settings_accountcheckintval']) ? 30 : $_POST['settings_accountcheckintval']).';
## Sign Up Options ##
$config[\'emailactivation\'] = '.(!empty($_POST['settings_accountsignup']) && $_POST['settings_accountsignup'] == 'emailactivation' ? 'true' : 'false').';
$config[\'signup_disabled\'] = '.(!empty($_POST['settings_accountsignup']) && $_POST['settings_accountsignup'] == 'disablesignup' ? 'true' : 'false').';
$config[\'admin_activation\'] =  '.(!empty($_POST['settings_accountsignup']) && $_POST['settings_accountsignup'] == 'admin_activation' ? 'true' : 'false').';
## IPN Settings ##
$config[\'crondebug\'] = '.(!empty($_POST['settings_crondebug']) ? 'true' : 'false').';
$config[\'crontype\'] = '.(empty($_POST['settings_crontype']) ? 0 : 1).';
$config[\'crontoken\'] = \''.(empty($_POST['settings_crontoken']) ? '' : $_POST['settings_crontoken']).'\';
$config[\'cronintval\'] = '.(empty($_POST['settings_cronintval']) ? 300 : $_POST['settings_cronintval']).';
$config[\'paidmemberships\'] = '.(!empty($_POST['settings_paidmemberships']) ? 'true' : 'false').';
$config[\'ipn_debug\'] = '.(!empty($_POST['settings_ipn_debug']) ? 'true' : 'false').';
$config[\'ipn_email\'] = \''.(empty($_POST['settings_ipn_email']) ? '' : $_POST['settings_ipn_email']).'\';
$config[\'ipn_currency\'] = \''.(empty($_POST['settings_ipn_currency']) ? '' : $_POST['settings_ipn_currency']).'\';
$config[\'ipn_timeout\'] = '.(empty($_POST['settings_ipn_timeout']) ? 30 : $_POST['settings_ipn_timeout']).';
$config[\'ipn_sandbox\'] = '.(!empty($_POST['settings_ipn_usecurl']) ? 'true' : 'false').';
$config[\'ipn_usessl\'] = '.(!empty($_POST['settings_ipn_usessl']) ? 'true' : 'false').';
$config[\'ipn_followloc\'] = '.(!empty($_POST['settings_ipn_followloc']) ? 'true' : 'false').';
$config[\'ipn_forcessl3\'] = '.(!empty($_POST['settings_ipn_forcessl3']) ? 'true' : 'false').';
$config[\'ipn_usecurl\'] =  '.(!empty($_POST['settings_ipn_usecurl']) ? 'true' : 'false').';
## PayPal API Information ##
$config[\'pp_api_user\'] = \''.(empty($_POST['settings_pp_api_user']) ? '' : $_POST['settings_pp_api_user']).'\';
$config[\'pp_api_pass\'] = \''.(empty($_POST['settings_pp_api_pass']) ? '' : $_POST['settings_pp_api_pass']).'\';
$config[\'pp_api_sig\'] =  \''.(empty($_POST['settings_pp_api_sig']) ? '' : $_POST['settings_pp_api_sig']).'\';
## Language Information ##
/* Make sure the name matches the files in the lang directory. 
   eg. if the file is called "lang_eng.php" you should put "eng" in the value below (without ""). */
$config[\'default_lang\'] = \''.(!empty($_POST['defaultlang']) ? $_POST['defaultlang'] : $this->config['default_lang']).'\';
## Mail Configuration ##
$config[\'mailer_type\'] = \''.(!empty($_POST['mailtype']) ? $_POST['mailtype'] : $this->config['mailer_type']).'\';
## SMTP Configuration ##
$config[\'smtp_host\'] = \''.(!empty($_POST['settings_smtphost']) ? $_POST['settings_smtphost'] : $this->config['smtp_host']).'\';
$config[\'smtp_port\'] = '.(!empty($_POST['settings_smtport']) ? $_POST['settings_smtport'] : $this->config['smtp_port']).';
$config[\'smtp_user\'] = \''.(!empty($_POST['settings_smtpuser']) ? $_POST['settings_smtpuser'] : $this->config['smtp_user']).'\';
$config[\'smtp_pass\'] = \''.(!empty($_POST['settings_smtppass']) ? $_POST['settings_smtppass'] : $this->config['smtp_pass']).'\';
## PHP Configuration ##
$config[\'php_from\'] = \''.(!empty($_POST['settings_phpfrom']) ? $_POST['settings_phpfrom'] : $this->config['php_from']).'\';
## reCaptcha Keys ##
$config[\'reCAPTCHA_enabled\'] = '.(!empty($_POST['settings_enablerecaptcha']) && $_POST['settings_enablerecaptcha'] == 'on' ? 'true' : 'false').'; //whether to enable or disabled the recaptcha system
$config[\'reCAPTCHA_publickey\'] = \''.(!empty($_POST['settings_recaptchapublic']) ? $_POST['settings_recaptchapublic'] : '').'\';
$config[\'reCAPTCHA_privatekey\'] = \''.(!empty($_POST['settings_recaptchaprivate']) ? $_POST['settings_recaptchaprivate'] : '').'\';
## Invitation System ##
$config[\'invite_system\'] = '.(!empty($_POST['settings_enableinvites']) && $_POST['settings_enableinvites'] == 'on' ? 'true' : 'false').';
$config[\'invite_only\'] = '.(!empty($_POST['settings_inviteonly']) && $_POST['settings_inviteonly'] == 'on' ? 'true' : 'false').';
## Friend System ##
$config[\'friend_system\'] = '.(!empty($_POST['settings_enablefriendsystem']) && $_POST['settings_enablefriendsystem'] == 'on' ? 'true' : 'false').';
## Forum System ##
$config[\'forum_enabled\'] = '.(!empty($_POST['settings_forumenabled']) && $_POST['settings_forumenabled'] == 'on' ? 'true' : 'false').';
## PM System ##
$config[\'pm_system\'] = '.(!empty($_POST['settings_enablepmsystem']) && $_POST['settings_enablepmsystem'] == 'on' ? 'true' : 'false').';
$config[\'pm_system_spam\'] = '.(!empty($_POST['settings_pmspam']) ? $_POST['settings_pmspam'] : $this->config['pm_system_spam']).';
## Legal information ##
$config[\'termsrequired\'] = '.(!empty($_POST['settings_legalsignuprequired']) && $_POST['settings_legalsignuprequired'] == 'on' ? 'true' : 'false').';
$config[\'termsconditions\'] =
"'.(!empty($_POST['settings_legaltext']) ? $_POST['settings_legaltext'] : $this->config['termsconditions']).'";
## Time settings ##
$config[\'timezone\'] = \''.(!empty($_POST['timezone']) ? $_POST['timezone'] : 'Europe/London').'\';
date_default_timezone_set(\''.(!empty($_POST['timezone']) ? $_POST['timezone'] : 'Europe/London').'\');';
		
		try{
			@$theFile = fopen(BASE_PATH.'/system/config.php', 'w');
			$success = @fwrite($theFile, $output);
			@fclose($theFile);
		}
		catch(exception $e){
			return false;
		}
		
		if(!$success){
			return false;
		}
		
		//Check if we need to re-cache the membership buttons.		
		if((isset($_POST['settings_pp_api_user']) && $_POST['settings_pp_api_user'] != $this->config['pp_api_user']) ||
		   (isset($_POST['settings_pp_api_pass']) && $_POST['settings_pp_api_pass'] != $this->config['pp_api_pass']) ||
		   (isset($_POST['settings_pp_api_sig']) && $_POST['settings_pp_api_sig'] != $this->config['pp_api_sig'])){
		   
			$this->generateButtons(false);
		}
		
		return true;
	}
	
public function updateVersion($newVersion){
		if(!defined('INSTALLER') && !$this->loggedin){
			exit;
		}
		
		include(SYSTEM_PATH.'/config.php');
		$this->load_config($config);
		unset($config);
		
$output = '<?php
/*****************************************************************************************
 * Solid PHP User Management System :: Version '.$newVersion.'							 *
 * Copyright 2012 Mark Eliasen (MrEliasen)												 *
 *																						 *
 * CodeCanyon Link: http://codecanyon.net/item/solid-php-user-management-system-/1254295 *
 * Author Website: http://zolidweb.com													 *
 *****************************************************************************************/
 
$config = array();
$config[\'version\'] = \''.$newVersion.'\';'."\n";

		foreach($this->config as $conf => $value){
			if($conf != 'version'){
				$output .= '$config[\''.$conf.'\'] = ';
							
				if(is_bool($value)){
					$output .= ($value ? 'true;' : 'false;');
				}else{
					if(is_int($value)){
						$output .= $value.';';
					}else{
						$output .= '\''.$value.'\';';
					}
				}
				
				$output .= "\n";
			}
		}
		$output .= 'date_default_timezone_set(\''.$this->config['timezone'].'\');';
		
		$theFile = fopen(BASE_PATH.'/system/config.php', 'w');
		fwrite($theFile, $output);
		fclose($theFile);
		
		return true;
	}
	
	public function process_adduser(){
		if(!$this->loggedin){
			exit;
		}
		//check if the user is admin
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		//check if any of the required fields are empty
		if(empty($_POST['username']) || empty($_POST['email']) || empty($_POST['usergroup'])){
			$status['message'] = $this->lang['cl_admin_4'];
			$status['title'] = $this->lang['cl_admin_5'];
			$status['msgtype'] = 'error';
			$status['status'] = false;
			return $status;
			exit;
		}
		//check if the e-mail is an actual e-mail
		if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", strtolower(trim($_POST['email']))) ){
			$status['message'] = $this->lang['cl_admin_6'];
			$status['title'] = $this->lang['cl_admin_7'];
			$status['msgtype'] = 'error';
			$status['status'] = false;
			return $status;
			exit;
		}
		//check if an account is already registered with that e-mail
		if(!$this->check_email($this->sanitize($_POST['email'], 'email'))){
			$status['message'] = $this->lang['cl_admin_8'];
			$status['title'] = $this->lang['cl_admin_9'];
			$status['msgtype'] = 'error';
			$status['status'] = false;
			return $status;
			exit;
		}
		//check if the two passwords match
		if(strlen($_POST['username']) < 3){
			$status['message'] = $this->lang['cl_admin_10'];
			$status['title'] = $this->lang['cl_admin_11'];
			$status['msgtype'] = 'error';
			$status['status'] = false;
			return $status;
			exit;
		}
		//check if an account is already registered with that username
		if(!$this->check_username($this->sanitize($_POST['username'], 'string'))){
			$status['message'] = $this->lang['cl_admin_12'];
			$status['title'] = $this->lang['cl_admin_12'];
			$status['msgtype'] = 'error';
			$status['status'] = false;
			return $status;
			exit;
		}
		//Make sure the username is not present in the password
		if(strpos($_POST['password'], $_POST['username']) !== false){
			$status['message'] = $this->lang['cl_admin_14'];
			$status['title'] = $this->lang['cl_admin_15'];
			$status['msgtype'] = 'error';
			$status['status'] = false;
			return $status;
			exit;
		}
		//Check if the system needs to generate a random password
		if(empty($_POST['password'])){
			$_POST['password'] = substr(str_shuffle('abcdefghijklmnopqrstuvxyz1234567890ABCDEFGHIJKLMNOPQRSTUVXYZ0987654321'),0,12);
		}
		
		//before we continue, we need to make the variables a bit more secure as we will be using them shortly.
		$_POST['usergroup'] = explode('##', $_POST['usergroup']);
		
		$useremail 	= $this->sanitize($_POST['email'], 'email');
		$username 	= $this->sanitize(strip_tags(addslashes($_POST['username'])), 'string');
		$salt 		= $this->find_salt($username);
		$membergroup = $this->sanitize($_POST['usergroup'][0], 'integer');
		$password 	 = hash_hmac('sha256', $salt.hash_hmac('sha256', $this->config['hashing_salt'].$_POST['password'], $this->config['hashing_key']), $this->config['hashing_key']);
		
		//prepare the sql query
		$pro_add = $this->sql->prepare('INSERT INTO members(username,password,email,date_registered, membergroup, account_key) VALUES(?,?,?,?,?,?)');
		$pro_add->execute(array($username, $password, $useremail, time(), $membergroup, substr(str_shuffle('abcdefghijklmnopqrstuvxyz1234567890ABCDEFGHIJKLMNOPQRSTUVXYZ0987654321'),0,8)));
		$pro_add->closeCursor();
		$this->queries++;
		
		if($pro_add->rowCount() < 1){
			$status['message'] = $this->lang['cl_admin_16'];
			$status['title'] = $this->lang['cl_admin_17'];
			$status['msgtype'] = 'error';
			$status['status'] = false;
			return $status;
			exit;
		}else{
			$status['title'] = $this->lang['cl_admin_18'];
			$status['msgtype'] = 'success';
			$status['message'] = $this->lang['cl_admin_19'];
			
			$id = $this->sql->lastInsertId();
			$gname = $this->sanitize($_POST['usergroup'][1], 'string');
			
			$status['html'] = '<tr>
								<td>'.$id.'</td>
								<td>
									<div class="btn-group">
										<a class="btn btn-small btn-primary" href="#"><i class="icon-user icon-white"></i> '.$username.'</a>
										<a class="btn btn-small btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
										<ul class="dropdown-menu">
											<li><a href="admin_accedit.php?uid='.$id.'" class="ctrl_edituser"><i class="icon-pencil"></i> Edit</a></li>
											<li class="divider"></li>
											<li><a href="'.$id.'" class="ctrl_deltuser"><i class="icon-trash"></i> Delete</a></li>
										</ul>
									</div>
								</td>
								<td>'.$useremail.'</td>
								<td>
									<div class="input-prepend">
										<input class="ctrl_membergroups span2" type="email" value="'.$gname.'" name="'.$id.'" />
										<span></span>
									</div>
								</td>
								<td> -- </td>
							</tr>';
			
			$status['status'] = true;
			if(!empty($_POST['sendemail']) && $_POST['sendemail'] == 'on'){
				
				require_once(SYSTEM_PATH.'/templates/emails/tmpl_admin_new_account.php');
				$variables = array('website_name' => $this->config['website_name'],
								   'site_url' => $this->base_url,
								   'username' => $username,
								   'email' => $useremail,
								   'password' => $_POST['password']
								   );
				
				$subject = $this->render_email($variables, $email['title']);
				$body = $this->render_email($variables, $email['body']);
						
				$this->send_mail($email, $subject, $body);
				
				$status['message'] = $this->lang['cl_admin_20'];
			}
			return $status;
		}
	}
	
	public function process_addgroup(){
		if(!$this->loggedin){
			exit;
		}
		//check if the user is admin
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		//check if any of the required fields are empty
		if(empty($_POST['groupname'])){
			$status['message'] = $this->lang['cl_admin_21'];
			$status['title'] = $this->lang['cl_admin_22'];
			$status['msgtype'] = 'error';
			$status['status'] = false;
			return $status;
			exit;
		}
		
		//Sanitize the values, we only want integers.
		$_POST['groupname'] = $this->sanitize(strip_tags(addslashes($_POST['groupname'])), 'string');
		$_POST['groupcolour'] = $this->sanitize(str_replace('#','',$_POST['groupcolour']), 'string');
		
		$permissions = array('pm_read' => (!empty($_POST['perm_readpm']) && $_POST['perm_readpm'] == 'on' ? true : false),
							 'pm_write' => (!empty($_POST['perm_sendpm']) && $_POST['perm_sendpm'] == 'on' ? true : false),
							 'pm_limit' => (!empty($_POST['perm_limitpm']) ? $this->sanitize($_POST['perm_limitpm'], 'integer') : 10),
							 'friends_view' => (!empty($_POST['perm_viewfriend']) && $_POST['perm_viewfriend'] == 'on' ? true : false),
							 'friends_reqest' => (!empty($_POST['perm_sendfriend']) && $_POST['perm_sendfriend'] == 'on' ? true : false),
							 'account_edit' => (!empty($_POST['perm_accedit']) && $_POST['perm_accedit'] == 'on' ? true : false),
							 'account_delete' => (!empty($_POST['perm_accdel']) && $_POST['perm_accdel'] == 'on' ? true : false),
							 'admin' => (!empty($_POST['perm_accadmin']) && $_POST['perm_accadmin'] == 'on' ? true : false),
							 'viewlevel' => (!empty($_POST['perm_viewlevel']) && $_POST['perm_viewlevel'] > 0 ? $this->sanitize($_POST['perm_viewlevel'], 'integer') : 1),
							 'invite_send' => (!empty($_POST['perm_invsend']) && $_POST['perm_invsend'] == 'on' ? true : false),
							 'invite_revoke' => (!empty($_POST['perm_invrevoke']) && $_POST['perm_invrevoke'] == 'on' ? true : false)
		);
		
		
		//check if the usergroup was found in the database
		$stmt = $this->sql->prepare('INSERT INTO member_groups (name, colour, permissions) VALUES (?,?,?)');
		$stmt->execute(array($_POST['groupname'], $_POST['groupcolour'], serialize($permissions)));
		$stmt->closeCursor();
		
		if($stmt->rowCount() < 1){
			$error = $stmt->errorInfo();
			$status['message'] = $this->lang['cl_admin_23'].' '.$error[2];
			$status['title'] = $this->lang['cl_admin_24'];
			$status['msgtype'] = 'error';
			$status['status'] = false;
			return $status;
			exit;
		}else{
			$status['title'] = $this->lang['cl_admin_25'];
			$status['msgtype'] = 'success';
			$status['message'] = $this->lang['cl_admin_26'];
			
			
			$id = $this->sql->lastInsertId();
			$status['name'] = $_POST['groupname'];
			$status['id'] = $id;
			
			$status['html'] = '<tr id="group_'.$id.'">
									<td>'.$id.'</td>
									<td><span class="label" style="background-color: #'.$_POST['groupcolour'].'">'.$_POST['groupname'].'</span></td>
									<td>There are 0 members in this group.</td>
									<td>
										<button class="delete_group btn btn-small btn-danger" data-groupid="'.$id.'">Delete Group</button> 
										<button class="edit_group btn btn-small btn-success" data-groupid="'.$id.'">Edit Group</button>
									</td>
								</tr>';
			
			$status['status'] = true;
			
			return $status;
		}
	}
	
	public function generate_userlist($results_per_page = 20){
		//check if the user is admin
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		$stmt = $this->sql->prepare('SELECT id FROM members');
		$stmt->execute(array());
		$this->queries++;
		$output = array('users' => '', 'pagination' => '');
		$total_users = $stmt->rowCount();
		
		if($total_users > 0){
			$stmt->closeCursor();
			$stmt = null;
			
			/* For security */
			$results_per_page = intval($this->sanitize($results_per_page, 'integer'));
			$_POST['page'] = (!empty($_REQUEST['page']) && !empty($_REQUEST['tab']) &&  $_REQUEST['tab'] == 'users'  ? intval($this->sanitize($_REQUEST['page'], 'integer')) : 1);
			
			$offset = (empty($_POST['page']) || $_POST['page'] < 0 ? 0 : ($_POST['page']*$results_per_page)-$results_per_page);
			
			$order_by = (!empty($_GET['sort']) && ($_GET['sort'] == 'username' || $_GET['sort'] == 'id' || $_GET['sort'] == 'membergroup') ? $this->sanitize($_GET['sort'], 'string') : 'id');
			$order_list = (!empty($_GET['list']) && ($_GET['list'] == 'asc' || $_GET['list'] == 'desc') ? $this->sanitize($_GET['list'], 'string') : 'desc');
			
			//get all the users
			$stmt = $this->sql->prepare('SELECT
												u.id,
												u.username,
												u.email,
												u.lastactivity,
												u.membergroup,
												u.activation_key,
												g.name,
												s.session_expire
											FROM
												members as u
											INNER JOIN
												member_groups as g
												ON g.id = u.membergroup
											LEFT JOIN
												sessions as s
												ON s.session_key = u.session
											GROUP BY
												u.id
											ORDER BY
												'.($this->config['admin_activation'] ? 'u.activation_key DESC, ' : '').'
												u.'.$order_by.' '.$order_list.'
											LIMIT '.$results_per_page.'
											OFFSET '.$offset);
											
			$stmt->execute(array());
			
			if($stmt->rowCount() > 0){
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					if(empty($row['activation_key'])){
						$onlineActivity = (!empty($row['session_expire']) && $row['session_expire'] > time()-$this->config('session_timeout') ? 'Online Now!' : ($row['lastactivity'] > 0 ? date($this->config('dateformat_short'), $row['lastactivity']) : 'Never'));
					}else{
						$onlineActivity = '<button class="btn btn-warning btn-small admActivateAcc" data-uid="'.$row['id'].'">Activate Account</button>';
					}
					
					$output['users'] .='<tr>
											<td>'.$row['id'].'</td>
											<td>
												<div class="btn-group">
													<a class="btn btn-small btn-primary" href="#"><i class="icon-user icon-white"></i> '.$row['username'].'</a>
													<a class="btn btn-small btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
													<ul class="dropdown-menu">
														<li><a href="admin_accedit.php?uid='.$row['id'].'" class="ctrl_edituser"><i class="icon-pencil"></i> '.$this->lang['cl_admin_49'].'</a></li>
														<li class="divider"></li>
														<li><a href="'.$row['id'].'" class="ctrl_deltuser"><i class="icon-trash"></i> '.$this->lang['cl_admin_50'].'</a></li>
													</ul>
												</div>
											</td>
											<td>'.$row['email'].'</td>
											<td>
												<div class="input-prepend">
													<input class="ctrl_membergroups span2" type="text" value="'.$row['name'].'" name="'.$row['id'].'" />
													<span></span>
												</div>
											</td>
											<td>'.$onlineActivity.'</td>
										</tr>';
				}
				
				$output['pagination'] = $this->generatePagination($total_users, $_POST['page'], 'users', $results_per_page);
				
			}else{
				$output['users'] .='<tr>
										<td colspan="5">'.$this->lang['cl_admin_51'].'</td>
									</tr>';
			}
		}
		
		return $output;
	}
	
	public function get_group_settings(){
		//check if the user is admin
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		// check if the id is set
		if(empty($_POST['id'])){
			return array('status' => false);
		}
		
		
		$stmt = $this->sql->prepare('SELECT
											m.*,
											(SELECT count(u.id) FROM members as u WHERE u.membergroup = m.id) as count
										FROM
											member_groups as m
										WHERE
											id = ?
										');
		$stmt->execute(array($this->sanitize($_POST['id'], 'integer')));
		
		$output = array('status' => false, 'html' =>'');
		
		if($stmt->rowCount() > 0){
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$output['status'] = true;
				$permissions = unserialize($row['permissions']);
				
				$output['html'] .='<div class="control-group">
										<label class="control-label" for="groupname">Group Name</label>
										<div class="controls">
											<input type="text" name="groupname" id="groupname" class="input-medium" value="'.$row['name'].'">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="groupname">Group Colour</label>
										<div class="controls">
											<input type="text" name="groupcolour"  value="#'.$row['colour'].'" class="groupcolour input-small">
											<div class="colorpicker editcolorpicker"></div>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="groupname">Default Group</label>
										<div class="controls">
											<input type="checkbox" '.($row['default_group'] ? 'checked="checked"' : '').' name="accdefault" value="on" />
										</div>
									</div>
									
									<legend>PM Permissions</legend>
									<div class="control-group">
										<label class="control-label" for="">
											Read PMs 
											<a href="#" data-rel="tooltip" data-title="Is the group allowed to read PMs?"><i class="icon-question-sign"></i></a>
										</label>
										<div class="controls">
											<input type="checkbox" name="perm_readpm" '.($permissions['pm_read'] ? 'checked="checked"' : '').' value="on">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="">
											Send PMs 
											<a href="#" data-rel="tooltip" data-title="Is the group allowed to send PMs?"><i class="icon-question-sign"></i></a>
										</label>
										<div class="controls">
											<input type="checkbox" name="perm_sendpm" '.($permissions['pm_write'] ? 'checked="checked"' : '').' value="on">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="">Inbox Limit</label>
										<div class="controls">
											<input type="number" min="10" class="input-mini" name="perm_limitpm" value="'.$permissions['pm_limit'].'">
										</div>
									</div>
									
									<legend>Friend Permissions</legend>
									<div class="control-group">
										<label class="control-label" for="">
											View List
											<a href="#" data-rel="tooltip" data-title="Is the group allowed to view their friendlist and requests?"><i class="icon-question-sign"></i></a>
										</label>
										<div class="controls">
											<input type="checkbox" name="perm_viewfriend" '.($permissions['friends_view'] ? 'checked="checked"' : '').' value="on">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="">
											Send Requests 
											<a href="#" data-rel="tooltip" data-title="Is the group allowed to send friend requests?"><i class="icon-question-sign"></i></a>
										</label>
										<div class="controls">
											<input type="checkbox" name="perm_sendfriend" '.($permissions['friends_reqest'] ? 'checked="checked"' : '').' value="on">
										</div>
									</div>
									
									<legend>Account Permissions</legend>
									<div class="control-group">
										<label class="control-label" for="">
											Edit Settings
											<a href="#" data-rel="tooltip" data-title="Is the group allowed to edit their account details?"><i class="icon-question-sign"></i></a>
										</label>
										<div class="controls">
											<input type="checkbox" name="perm_accedit" '.($permissions['account_edit'] ? 'checked="checked"' : '').' value="on">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="">
											Delete Account
											<a href="#" data-rel="tooltip" data-title="Is the group allowed to delete their account?"><i class="icon-question-sign"></i></a>
										</label>
										<div class="controls">
											<input type="checkbox" name="perm_accdel" '.($permissions['account_delete'] ? 'checked="checked"' : '').' value="on">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="">
											View Level
											<a href="#" data-rel="tooltip" data-title="This is used when protecting/restricting pages or a minimum required contect to view level. The higher value, the higher view level."><i class="icon-question-sign"></i></a>
										</label>
										<div class="controls">
											<input type="number" min="1" class="input-mini" name="perm_viewlevel" value="'.$permissions['viewlevel'].'" />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="">
											Admin Group
											<a href="#" data-rel="tooltip" data-title="Give this group admin permissions?"><i class="icon-question-sign"></i></a>
										</label>
										<div class="controls">
											<input type="checkbox" name="perm_accadmin" '.($permissions['admin'] ? 'checked="checked"' : '').' value="on">
										</div>
									</div>
									
									<legend>Invite Permissions</legend>
									<div class="control-group">
										<label class="control-label" for="">
											Send Invites
											<a href="#" data-rel="tooltip" data-title="Is the group allowed to send invitations?"><i class="icon-question-sign"></i></a>
										</label>
										<div class="controls">
											<input type="checkbox" name="perm_invsend" '.($permissions['invite_send'] ? 'checked="checked"' : '').' value="on">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="">
											Revoke Invites
											<a href="#" data-rel="tooltip" data-title="Is the group allowed to delete/revoke un-accepted invitations?"><i class="icon-question-sign"></i></a>
										</label>
										<div class="controls">
											<input type="checkbox" name="perm_invrevoke" '.($permissions['invite_revoke'] ? 'checked="checked"' : '').' value="on">
										</div>
									</div>
									
									<input type="hidden" name="groupid" value="'.$row['id'].'">';
			}
		}
		
		return $output;
	}
	
	public function generate_grouplist($results_per_page = 10){
		//check if the user is admin
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		$stmt = $this->sql->prepare('SELECT id FROM member_groups');
		$stmt->execute(array());
		$this->queries++;
		$output = array('groups' => '', 'pagination' => '');
		$total_groups = $stmt->rowCount();
		
		if($total_groups > 0){
			$stmt->closeCursor();
			$stmt = null;
			
			/* For security */
			$results_per_page = intval($this->sanitize($results_per_page, 'integer'));
			$_POST['page'] = (!empty($_REQUEST['page']) && !empty($_REQUEST['tab']) &&  $_REQUEST['tab'] == 'usergroups' ? intval($this->sanitize($_REQUEST['page'], 'integer')) : 1);
			
			$offset = (empty($_POST['page']) || $_POST['page'] < 0 ? 0 : ($_POST['page']*$results_per_page)-$results_per_page);
			//get all the users
			$stmt = $this->sql->prepare('SELECT
												m.*,
												(SELECT count(u.id) FROM members as u WHERE u.membergroup = m.id) as count
											FROM
												member_groups as m
											ORDER BY
												m.name ASC
											LIMIT '.$results_per_page.'
											OFFSET '.$offset);
			$stmt->execute(array());
			
			if($stmt->rowCount() > 0){
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$permissions = unserialize($row['permissions']);
					
					$output['groups'] .='<tr id="group_'.$row['id'].'">
											<td>'.$row['id'].'</td>
											<td><span class="label" style="background-color: #'.$row['colour'].'">'.$row['name'].'</span></td>
											<td>'.$this->lang['cl_admin_54'].' '.$row['count'].' '.$this->lang['cl_admin_55'].'</td>
											<td>
												<button class="delete_group btn btn-small btn-danger" data-groupid="'.$row['id'].'">'.$this->lang['cl_admin_52'].'</button> 
												<button class="edit_group btn btn-small btn-success" data-groupid="'.$row['id'].'">'.$this->lang['cl_admin_53'].'</button>
											</td>
										</tr>';
				}
				
				$output['pagination'] = $this->generatePagination($total_groups, $_POST['page'], 'usergroups');
			}else{
				$output['groups'] .='<tr>
										<td colspan="5">'.$this->lang['cl_admin_74'].'</td>
									</tr>';
			}
		}
		
		return $output;
	}
	
	public function loadConfigSettings($section = 'general'){
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		$output = '';
		
$timezones = array('Africa/Abidjan',
					'Africa/Accra',
					'Africa/Addis_Ababa',
					'Africa/Algiers',
					'Africa/Asmara',
					'Africa/Asmera',
					'Africa/Bamako',
					'Africa/Bangui',
					'Africa/Banjul',
					'Africa/Bissau',
					'Africa/Blantyre',
					'Africa/Brazzaville',
					'Africa/Bujumbura',
					'Africa/Cairo',
					'Africa/Casablanca',
					'Africa/Ceuta',
					'Africa/Conakry',
					'Africa/Dakar',
					'Africa/Dar_es_Salaam',
					'Africa/Djibouti',
					'Africa/Douala',
					'Africa/El_Aaiun',
					'Africa/Freetown',
					'Africa/Gaborone',
					'Africa/Harare',
					'Africa/Johannesburg',
					'Africa/Juba',
					'Africa/Kampala',
					'Africa/Khartoum',
					'Africa/Kigali',
					'Africa/Kinshasa',
					'Africa/Lagos',
					'Africa/Libreville',
					'Africa/Lome',
					'Africa/Luanda',
					'Africa/Lubumbashi',
					'Africa/Lusaka',
					'Africa/Malabo',
					'Africa/Maputo',
					'Africa/Maseru',
					'Africa/Mbabane',
					'Africa/Mogadishu',
					'Africa/Monrovia',
					'Africa/Nairobi',
					'Africa/Ndjamena',
					'Africa/Niamey',
					'Africa/Nouakchott',
					'Africa/Ouagadougou',
					'Africa/Porto-Novo',
					'Africa/Sao_Tome',
					'Africa/Timbuktu',
					'Africa/Tripoli',
					'Africa/Tunis',
					'Africa/Windhoek',
					'America/Adak',
					'America/Anchorage',
					'America/Anguilla',
					'America/Antigua',
					'America/Araguaina',
					'America/Argentina/Buenos_Aires',
					'America/Argentina/Catamarca',
					'America/Argentina/ComodRivadavia',
					'America/Argentina/Cordoba',
					'America/Argentina/Jujuy',
					'America/Argentina/La_Rioja',
					'America/Argentina/Mendoza',
					'America/Argentina/Rio_Gallegos',
					'America/Argentina/Salta',
					'America/Argentina/San_Juan',
					'America/Argentina/San_Luis',
					'America/Argentina/Tucuman',
					'America/Argentina/Ushuaia',
					'America/Aruba',
					'America/Asuncion',
					'America/Atikokan',
					'America/Atka',
					'America/Bahia',
					'America/Bahia_Banderas',
					'America/Barbados',
					'America/Belem',
					'America/Belize',
					'America/Blanc-Sablon',
					'America/Boa_Vista',
					'America/Bogota',
					'America/Boise',
					'America/Buenos_Aires',
					'America/Cambridge_Bay',
					'America/Campo_Grande',
					'America/Cancun',
					'America/Caracas',
					'America/Catamarca',
					'America/Cayenne',
					'America/Cayman',
					'America/Chicago',
					'America/Chihuahua',
					'America/Coral_Harbour',
					'America/Cordoba',
					'America/Costa_Rica',
					'America/Creston',
					'America/Cuiaba',
					'America/Curacao',
					'America/Danmarkshavn',
					'America/Dawson',
					'America/Dawson_Creek',
					'America/Denver',
					'America/Detroit',
					'America/Dominica',
					'America/Edmonton',
					'America/Eirunepe',
					'America/El_Salvador',
					'America/Ensenada',
					'America/Fort_Wayne',
					'America/Fortaleza',
					'America/Glace_Bay',
					'America/Godthab',
					'America/Goose_Bay',
					'America/Grand_Turk',
					'America/Grenada',
					'America/Guadeloupe',
					'America/Guatemala',
					'America/Guayaquil',
					'America/Guyana',
					'America/Halifax',
					'America/Havana',
					'America/Hermosillo',
					'America/Indiana/Indianapolis',
					'America/Indiana/Knox',
					'America/Indiana/Marengo',
					'America/Indiana/Petersburg',
					'America/Indiana/Tell_City',
					'America/Indiana/Vevay',
					'America/Indiana/Vincennes',
					'America/Indiana/Winamac',
					'America/Indianapolis',
					'America/Inuvik',
					'America/Iqaluit',
					'America/Jamaica',
					'America/Jujuy',
					'America/Juneau',
					'America/Kentucky/Louisville',
					'America/Kentucky/Monticello',
					'America/Knox_IN',
					'America/Kralendijk',
					'America/La_Paz',
					'America/Lima',
					'America/Los_Angeles',
					'America/Louisville',
					'America/Lower_Princes',
					'America/Maceio',
					'America/Managua',
					'America/Manaus',
					'America/Marigot',
					'America/Martinique',
					'America/Matamoros',
					'America/Mazatlan',
					'America/Mendoza',
					'America/Menominee',
					'America/Merida',
					'America/Metlakatla',
					'America/Mexico_City',
					'America/Miquelon',
					'America/Moncton',
					'America/Monterrey',
					'America/Montevideo',
					'America/Montreal',
					'America/Montserrat',
					'America/Nassau',
					'America/New_York',
					'America/Nipigon',
					'America/Nome',
					'America/Noronha',
					'America/North_Dakota/Beulah',
					'America/North_Dakota/Center',
					'America/North_Dakota/New_Salem',
					'America/Ojinaga',
					'America/Panama',
					'America/Pangnirtung',
					'America/Paramaribo',
					'America/Phoenix',
					'America/Port-au-Prince',
					'America/Port_of_Spain',
					'America/Porto_Acre',
					'America/Porto_Velho',
					'America/Puerto_Rico',
					'America/Rainy_River',
					'America/Rankin_Inlet',
					'America/Recife',
					'America/Regina',
					'America/Resolute',
					'America/Rio_Branco',
					'America/Rosario',
					'America/Santa_Isabel',
					'America/Santarem',
					'America/Santiago',
					'America/Santo_Domingo',
					'America/Sao_Paulo',
					'America/Scoresbysund',
					'America/Shiprock',
					'America/Sitka',
					'America/St_Barthelemy',
					'America/St_Johns',
					'America/St_Kitts',
					'America/St_Lucia',
					'America/St_Thomas',
					'America/St_Vincent',
					'America/Swift_Current',
					'America/Tegucigalpa',
					'America/Thule',
					'America/Thunder_Bay',
					'America/Tijuana',
					'America/Toronto',
					'America/Tortola',
					'America/Vancouver',
					'America/Virgin',
					'America/Whitehorse',
					'America/Winnipeg',
					'America/Yakutat',
					'America/Yellowknife',
					'Antarctica/Casey',
					'Antarctica/Davis',
					'Antarctica/DumontDUrville',
					'Antarctica/Macquarie',
					'Antarctica/Mawson',
					'Antarctica/McMurdo',
					'Antarctica/Palmer',
					'Antarctica/Rothera',
					'Antarctica/South_Pole',
					'Antarctica/Syowa',
					'Antarctica/Vostok',
					'Arctic/Longyearbyen',
					'Asia/Aden',
					'Asia/Almaty',
					'Asia/Amman',
					'Asia/Anadyr',
					'Asia/Aqtau',
					'Asia/Aqtobe',
					'Asia/Ashgabat',
					'Asia/Ashkhabad',
					'Asia/Baghdad',
					'Asia/Bahrain',
					'Asia/Baku',
					'Asia/Bangkok',
					'Asia/Beirut',
					'Asia/Bishkek',
					'Asia/Brunei',
					'Asia/Calcutta',
					'Asia/Choibalsan',
					'Asia/Chongqing',
					'Asia/Chungking',
					'Asia/Colombo',
					'Asia/Dacca',
					'Asia/Damascus',
					'Asia/Dhaka',
					'Asia/Dili',
					'Asia/Dubai',
					'Asia/Dushanbe',
					'Asia/Gaza',
					'Asia/Harbin',
					'Asia/Hebron',
					'Asia/Ho_Chi_Minh',
					'Asia/Hong_Kong',
					'Asia/Hovd',
					'Asia/Irkutsk',
					'Asia/Istanbul',
					'Asia/Jakarta',
					'Asia/Jayapura',
					'Asia/Jerusalem',
					'Asia/Kabul',
					'Asia/Kamchatka',
					'Asia/Karachi',
					'Asia/Kashgar',
					'Asia/Kathmandu',
					'Asia/Katmandu',
					'Asia/Kolkata',
					'Asia/Krasnoyarsk',
					'Asia/Kuala_Lumpur',
					'Asia/Kuching',
					'Asia/Kuwait',
					'Asia/Macao',
					'Asia/Macau',
					'Asia/Magadan',
					'Asia/Makassar',
					'Asia/Manila',
					'Asia/Muscat',
					'Asia/Nicosia',
					'Asia/Novokuznetsk',
					'Asia/Novosibirsk',
					'Asia/Omsk',
					'Asia/Oral',
					'Asia/Phnom_Penh',
					'Asia/Pontianak',
					'Asia/Pyongyang',
					'Asia/Qatar',
					'Asia/Qyzylorda',
					'Asia/Rangoon',
					'Asia/Riyadh',
					'Asia/Saigon',
					'Asia/Sakhalin',
					'Asia/Samarkand',
					'Asia/Seoul',
					'Asia/Shanghai',
					'Asia/Singapore',
					'Asia/Taipei',
					'Asia/Tashkent',
					'Asia/Tbilisi',
					'Asia/Tehran',
					'Asia/Tel_Aviv',
					'Asia/Thimbu',
					'Asia/Thimphu',
					'Asia/Tokyo',
					'Asia/Ujung_Pandang',
					'Asia/Ulaanbaatar',
					'Asia/Ulan_Bator',
					'Asia/Urumqi',
					'Asia/Vientiane',
					'Asia/Vladivostok',
					'Asia/Yakutsk',
					'Asia/Yekaterinburg',
					'Asia/Yerevan',
					'Atlantic/Azores',
					'Atlantic/Bermuda',
					'Atlantic/Canary',
					'Atlantic/Cape_Verde',
					'Atlantic/Faeroe',
					'Atlantic/Faroe',
					'Atlantic/Jan_Mayen',
					'Atlantic/Madeira',
					'Atlantic/Reykjavik',
					'Atlantic/South_Georgia',
					'Atlantic/St_Helena',
					'Atlantic/Stanley',
					'Australia/ACT',
					'Australia/Adelaide',
					'Australia/Brisbane',
					'Australia/Broken_Hill',
					'Australia/Canberra',
					'Australia/Currie',
					'Australia/Darwin',
					'Australia/Eucla',
					'Australia/Hobart',
					'Australia/LHI',
					'Australia/Lindeman',
					'Australia/Lord_Howe',
					'Australia/Melbourne',
					'Australia/North',
					'Australia/NSW',
					'Australia/Perth',
					'Australia/Queensland',
					'Australia/South',
					'Australia/Sydney',
					'Australia/Tasmania',
					'Australia/Victoria',
					'Australia/West',
					'Australia/Yancowinna',
					'Europe/Amsterdam',
					'Europe/Andorra',
					'Europe/Athens',
					'Europe/Belfast',
					'Europe/Belgrade',
					'Europe/Berlin',
					'Europe/Bratislava',
					'Europe/Brussels',
					'Europe/Bucharest',
					'Europe/Budapest',
					'Europe/Chisinau',
					'Europe/Copenhagen',
					'Europe/Dublin',
					'Europe/Gibraltar',
					'Europe/Guernsey',
					'Europe/Helsinki',
					'Europe/Isle_of_Man',
					'Europe/Istanbul',
					'Europe/Jersey',
					'Europe/Kaliningrad',
					'Europe/Kiev',
					'Europe/Lisbon',
					'Europe/Ljubljana',
					'Europe/London',
					'Europe/Luxembourg',
					'Europe/Madrid',
					'Europe/Malta',
					'Europe/Mariehamn',
					'Europe/Minsk',
					'Europe/Monaco',
					'Europe/Moscow',
					'Europe/Nicosia',
					'Europe/Oslo',
					'Europe/Paris',
					'Europe/Podgorica',
					'Europe/Prague',
					'Europe/Riga',
					'Europe/Rome',
					'Europe/Samara',
					'Europe/San_Marino',
					'Europe/Sarajevo',
					'Europe/Simferopol',
					'Europe/Skopje',
					'Europe/Sofia',
					'Europe/Stockholm',
					'Europe/Tallinn',
					'Europe/Tirane',
					'Europe/Tiraspol',
					'Europe/Uzhgorod',
					'Europe/Vaduz',
					'Europe/Vatican',
					'Europe/Vienna',
					'Europe/Vilnius',
					'Europe/Volgograd',
					'Europe/Warsaw',
					'Europe/Zagreb',
					'Europe/Zaporozhye',
					'Europe/Zurich',
					'Indian/Antananarivo',
					'Indian/Chagos',
					'Indian/Christmas',
					'Indian/Cocos',
					'Indian/Comoro',
					'Indian/Kerguelen',
					'Indian/Mahe',
					'Indian/Maldives',
					'Indian/Mauritius',
					'Indian/Mayotte',
					'Indian/Reunion',
					'Pacific/Apia',
					'Pacific/Auckland',
					'Pacific/Chatham',
					'Pacific/Chuuk',
					'Pacific/Easter',
					'Pacific/Efate',
					'Pacific/Enderbury',
					'Pacific/Fakaofo',
					'Pacific/Fiji',
					'Pacific/Funafuti',
					'Pacific/Galapagos',
					'Pacific/Gambier',
					'Pacific/Guadalcanal',
					'Pacific/Guam',
					'Pacific/Honolulu',
					'Pacific/Johnston',
					'Pacific/Kiritimati',
					'Pacific/Kosrae',
					'Pacific/Kwajalein',
					'Pacific/Majuro',
					'Pacific/Marquesas',
					'Pacific/Midway',
					'Pacific/Nauru',
					'Pacific/Niue',
					'Pacific/Norfolk',
					'Pacific/Noumea',
					'Pacific/Pago_Pago',
					'Pacific/Palau',
					'Pacific/Pitcairn',
					'Pacific/Pohnpei',
					'Pacific/Ponape',
					'Pacific/Port_Moresby',
					'Pacific/Rarotonga',
					'Pacific/Saipan',
					'Pacific/Samoa',
					'Pacific/Tahiti',
					'Pacific/Tarawa',
					'Pacific/Tongatapu',
					'Pacific/Truk',
					'Pacific/Wake',
					'Pacific/Wallis',
					'Pacific/Yap',
					'Brazil/Acre',
					'Brazil/DeNoronha',
					'Brazil/East',
					'Brazil/West',
					'Canada/Atlantic',
					'Canada/Central',
					'Canada/East-Saskatchewan',
					'Canada/Eastern',
					'Canada/Mountain',
					'Canada/Newfoundland',
					'Canada/Pacific',
					'Canada/Saskatchewan',
					'Canada/Yukon',
					'CET',
					'Chile/Continental',
					'Chile/EasterIsland',
					'CST6CDT',
					'Cuba',
					'EET',
					'Egypt',
					'Eire',
					'EST',
					'EST5EDT',
					'Etc/GMT',
					'Etc/GMT+0',
					'Etc/GMT+1',
					'Etc/GMT+10',
					'Etc/GMT+11',
					'Etc/GMT+12',
					'Etc/GMT+2',
					'Etc/GMT+3',
					'Etc/GMT+4',
					'Etc/GMT+5',
					'Etc/GMT+6',
					'Etc/GMT+7',
					'Etc/GMT+8',
					'Etc/GMT+9',
					'Etc/GMT-0',
					'Etc/GMT-1',
					'Etc/GMT-10',
					'Etc/GMT-11',
					'Etc/GMT-12',
					'Etc/GMT-13',
					'Etc/GMT-14',
					'Etc/GMT-2',
					'Etc/GMT-3',
					'Etc/GMT-4',
					'Etc/GMT-5',
					'Etc/GMT-6',
					'Etc/GMT-7',
					'Etc/GMT-8',
					'Etc/GMT-9',
					'Etc/GMT0',
					'Etc/Greenwich',
					'Etc/UCT',
					'Etc/Universal',
					'Etc/UTC',
					'Etc/Zulu',
					'Factory',
					'GB',
					'GB-Eire',
					'GMT',
					'GMT+0',
					'GMT-0',
					'GMT0',
					'Greenwich',
					'Hongkong',
					'HST',
					'Iceland',
					'Iran',
					'Israel',
					'Jamaica',
					'Japan',
					'Kwajalein',
					'Libya',
					'MET',
					'Mexico/BajaNorte',
					'Mexico/BajaSur',
					'Mexico/General',
					'MST',
					'MST7MDT',
					'Navajo',
					'NZ',
					'NZ-CHAT',
					'Poland',
					'Portugal',
					'PRC',
					'PST8PDT',
					'ROC',
					'ROK',
					'Singapore',
					'Turkey',
					'UCT',
					'Universal',
					'US/Alaska',
					'US/Aleutian',
					'US/Arizona',
					'US/Central',
					'US/East-Indiana',
					'US/Eastern',
					'US/Hawaii',
					'US/Indiana-Starke',
					'US/Michigan',
					'US/Mountain',
					'US/Pacific',
					'US/Pacific-New',
					'US/Samoa',
					'UTC',
					'W-SU',
					'WET',
					'Zulu');
		$zones = '';
		foreach($timezones as $zone){
			$zones .= '<option '.($zone == $this->config['timezone'] ? 'selected="selected"' : '').'value="'.$zone.'">'.$zone.'</option>';
		}
		
		switch(strtolower($section)){
			case 'general':
				$output = '<legend>'.$this->lang['cl_admin_75'].' <button class="btn btn-success btn_savesettings"><i class="icon-arrow-right icon-white"></i> '.$this->lang['cl_admin_75_a'].'</button></legend>
							<div class="control-group">
								<label class="control-label" for="settings_website_name">
									'.$this->lang['cl_admin_75a'].'
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_75b'].'"><i class="icon-question-sign"></i></a>
								</label>
								<div class="controls">
									<input type="text" class="input-xlarge" id="settings_website_name" name="settings_website_name" value="'.$this->config['website_name'].'">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="settings_baseurl">
									'.$this->lang['cl_admin_76'].'
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_77'].'"><i class="icon-question-sign"></i></a>
								</label>
								<div class="controls">
									<input type="text" class="input-xlarge" id="settings_baseurl" name="settings_baseurl" value="'.$this->config['base_url'].'">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="settings_uploaddir">
									'.$this->lang['cl_admin_78'].'
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_79'].'"><i class="icon-question-sign"></i></a>
								</label>
								<div class="controls">
									<input type="text" class="input-xlarge" id="settings_uploaddir" name="settings_uploaddir" value="'.$this->config['avatar_dir'].'">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="settings_writepermissions">
									'.$this->lang['cl_admin_80'].'
								</label>
								<div class="controls">
									<input type="text" class="input-mini" id="settings_writepermissions" name="settings_writepermissions" value="'.$this->config['permission_writeable'].'">
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_81'].'"><i class="icon-question-sign"></i></a>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="settings_readpermissions">
									'.$this->lang['cl_admin_82'].'
								</label>
								<div class="controls">
									<input type="text" class="input-mini" id="settings_readpermissions" name="settings_readpermissions" value="'.$this->config['permission_readable'].'">
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_83'].'"><i class="icon-question-sign"></i></a>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="settings_sessiontime">
									'.$this->lang['cl_admin_84'].'
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_85'].'"><i class="icon-question-sign"></i></a>
								</label>
								<div class="controls">
									<input type="number" min="30" class="input-mini" id="settings_sessiontime" name="settings_sessiontime" value="'.$this->config['session_timeout'].'" placeholder="900">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="settings_sessiontime">
									'.$this->lang['cl_admin_86'].'
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_87'].'"><i class="icon-question-sign"></i></a>
								</label>
								<div class="controls">
									<select name="dbsession">
										<option value="enabled" '.($this->config['dbsessions'] ? 'selected="selected"' : '').'>'.$this->lang['cl_admin_88'].'</option>
										<option '.(!$this->config['dbsessions'] ? 'selected="selected"' : '').'>'.$this->lang['cl_admin_89'].'</option>
									</select>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="settings_sessiontime">
									'.$this->lang['cl_admin_89_1'].'
								</label>
								<div class="controls">
									<select name="timezone">
										'.$zones.'
									</select>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="settings_sessiontime">
									'.$this->lang['cl_admin_208'].'
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_209'].'"><i class="icon-question-sign"></i></a>
								</label>
								<div class="controls">
									<select name="force_nocache">
										<option value="enabled" '.($this->config['force_nocache'] ? 'selected="selected"' : '').'>'.$this->lang['cl_admin_210'].'</option>
										<option value="" '.(!$this->config['force_nocache'] ? 'selected="selected"' : '').'>'.$this->lang['cl_admin_211'].'</option>
									</select>
								</div>
							</div>
							
							<legend>'.$this->lang['cl_admin_90'].' <button class="btn btn-warning adm_clearCache">'.$this->lang['cl_admin_91'].' <span></span></button></legend><br>
							<div class="control-group">
								<label class="control-label" for="settings_cachedir">
									'.$this->lang['cl_admin_92'].'
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_93'].'"><i class="icon-question-sign"></i></a>
								</label>
								<div class="controls">
									<input type="text" class="input-xlarge" id="settings_cachedir" name="settings_cachedir" value="'.str_replace(BASE_PATH,'',$this->config['cache_dir']).'">
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="settings_cachetime">
									'.$this->lang['cl_admin_94'].'
								</label>
								<div class="controls">
									<input type="number" min="0" class="input-mini" id="settings_cachetime" name="settings_cachetime" value="'.$this->config['cache_time'].'" placeholder="300">
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_95'].'"><i class="icon-question-sign"></i></a>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="settings_cacheenabled">
									'.$this->lang['cl_admin_96'].'
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_97'].'"><i class="icon-question-sign"></i></a>
								</label>
								<div class="controls">
									<input type="checkbox" id="settings_cacheenabled" name="settings_cacheenabled" '.($this->config['cache_enabled'] ? 'checked="checked"' : '').' />
								</div>
							</div>
							<p class="help-block"><b>'.$this->lang['cl_admin_98'].'</b> '.$this->lang['cl_admin_99'].'</p>
							
							<br />
							
							<legend>'.$this->lang['cl_admin_100'].'</legend><br>
							<div class="control-group">
								<label class="control-label" for="settings_uploaddir">
									'.$this->lang['cl_admin_101'].'
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_102'].'"><i class="icon-question-sign"></i></a>
								</label>
								<div class="controls">
									<select name="mailtype">
										<option '.($this->config['mailer_type'] == 'php' ? 'selected="selected"' : '').' value="php">PHP</option>
										<option '.($this->config['mailer_type'] == 'smtp' ? 'selected="selected"' : '').' value="smtp">SMTP</option>
									</select>
								</div>
							</div>
							
							<div class="control-group" id="smtp_settings" style="display:none;">
								<label class="control-label" for="settings_smtphost">
									'.$this->lang['cl_admin_105'].' 
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_106'].'"><i class="icon-question-sign"></i></a>
								</label>
								<div class="controls">
									<input type="text" class="input-large" id="settings_smtphost" name="settings_smtphost" value="'.$this->config['smtp_host'].'">
								</div>
								
								<label class="control-label" for="settings_smtport">
									'.$this->lang['cl_admin_107'].'
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_108'].'"><i class="icon-question-sign"></i></a>
								</label>
								<div class="controls">
									<input type="text" class="input-mini" id="settings_smtport" name="settings_smtport" value="'.$this->config['smtp_port'].'">
								</div>
								
								<label class="control-label" for="settings_smtpuser">
									'.$this->lang['cl_admin_109'].' 
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_110'].'"><i class="icon-question-sign"></i></a>
								</label>
								<div class="controls">
									<input type="text" class="input-large" id="settings_smtpuser" name="settings_smtpuser" value="'.$this->config['smtp_user'].'">
								</div>
								
								<label class="control-label" for="settings_smtppass">
									'.$this->lang['cl_admin_111'].'
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_112'].'"><i class="icon-question-sign"></i></a>
								</label>
								<div class="controls">
									<input type="password" class="input-large" id="settings_smtppass" name="settings_smtppass" value="'.$this->config['smtp_pass'].'">
								</div>
							</div>
							<div class="control-group" id="php_settings" style="display:none;">
								<label class="control-label" for="settings_phpfrom">
									From E-mail
									<a href="#" data-rel="tooltip" data-title="The email address mails should appear to be from."><i class="icon-question-sign"></i></a>
								</label>
								<div class="controls">
									<input type="text" class="input-large" id="settings_phpfrom" name="settings_phpfrom" value="'.$this->config['php_from'].'">
								</div>
							</div>';
				break;
				
			case 'security':
			$output = '<legend>'.$this->lang['cl_admin_113'].' <button class="btn btn-success btn_savesettings"><i class="icon-arrow-right icon-white"></i> '.$this->lang['cl_admin_75_a'].'</button></legend>
						<div class="control-group">
							<label class="control-label" for="settings_enablerecaptcha">
								'.$this->lang['cl_admin_114'].'
							</label>
							<div class="controls">
								<input type="checkbox" id="settings_enablerecaptcha" name="settings_enablerecaptcha" '.($this->config['reCAPTCHA_enabled'] ? 'checked="checked"' : '').' />
								<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_115'].'"><i class="icon-question-sign"></i></a>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="settings_recaptchapublic">
								'.$this->lang['cl_admin_116'].'
							</label>
							<div class="controls">
								<input type="text" class="input-xlarge" id="settings_recaptchapublic" name="settings_recaptchapublic" value="'.$this->config['reCAPTCHA_publickey'].'">
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="settings_recaptchaprivate">
								'.$this->lang['cl_admin_117'].'
							</label>
							<div class="controls">
								<input type="text" class="input-xlarge" id="settings_recaptchaprivate" name="settings_recaptchaprivate" value="'.$this->config['reCAPTCHA_privatekey'].'">
							</div>
						</div>
						<p class="help-block"><b>'.$this->lang['cl_admin_118'].'</b> '.$this->lang['cl_admin_119'].'</p>
						
						<br />
						
						<legend>'.$this->lang['cl_admin_120'].'</legend>
						<div class="control-group">
							<label class="control-label" for="settings_timebetweensearch">
								'.$this->lang['cl_admin_121'].'
								<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_122'].'"><i class="icon-question-sign"></i></a>
							</label>
							<div class="controls">
								<input type="number" min="0" class="input-mini" id="settings_timebetweensearch" name="settings_timebetweensearch" value="'.$this->config['search_intval'].'" placeholder="10">
							</div>
						</div>';
				break;
				
			case 'accounts':
				$output = '<legend>'.$this->lang['cl_admin_123'].' <button class="btn btn-success btn_savesettings"><i class="icon-arrow-right icon-white"></i> '.$this->lang['cl_admin_75_a'].'</button></legend>
							<div class="control-group">
								<label class="control-label" for="settings_enablefriendsystem">
									'.$this->lang['cl_admin_124'].'
								</label>
								<div class="controls">
									<input type="checkbox" id="settings_enablefriendsystem" name="settings_enablefriendsystem" '.($this->config['friend_system'] ? 'checked="checked"' : '').' />
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_125'].'"><i class="icon-question-sign"></i></a>
								</div>
							</div>
							
							<legend>'.$this->lang['cl_admin_126'].'</legend>
							<div class="control-group">
								<label class="control-label" for="settings_enablepmsystem">
									'.$this->lang['cl_admin_127'].'
								</label>
								<div class="controls">
									<input type="checkbox" id="settings_enablepmsystem" name="settings_enablepmsystem" '.($this->config['pm_system'] ? 'checked="checked"' : '').'>
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_128'].'"><i class="icon-question-sign"></i></a>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="settings_pmspam">
									'.$this->lang['cl_admin_129'].'
								</label>
								<div class="controls">
									<input type="number" min="0" class="input-mini" id="settings_pmspam" name="settings_pmspam" value="'.$this->config['pm_system_spam'].'" placeholder="30"><a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_130'].'"><i class="icon-question-sign"></i></a>
								</div>
							</div>
							
							<legend>'.$this->lang['cl_admin_131'].'</legend>
							<div class="control-group">
								<label class="control-label" for="settings_trackactivity">
									'.$this->lang['cl_admin_132'].'
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_133'].'"><i class="icon-question-sign"></i></a>
								</label>
								<div class="controls">
									<input type="checkbox" id="settings_trackactivity" name="settings_trackactivity" '.($this->config['last_activity'] ? 'checked="checked"' : '').'>
								</div>
							</div>
							
							<legend>'.$this->lang['cl_admin_133a'].'</legend>
							<div class="control-group">
								<label class="control-label" for="settings_emailactivation">
									'.$this->lang['cl_admin_133b'].'
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_133c'].'"><i class="icon-question-sign"></i></a>
								</label>
								<div class="controls">
									<input type="radio" id="settings_emailactivation" name="settings_accountsignup" '.($this->config['emailactivation'] ? 'checked="checked"' : '').' value="emailactivation">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="settings_disablesignup">
									'.$this->lang['cl_admin_133d'].'
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_133e'].'"><i class="icon-question-sign"></i></a>
								</label>
								<div class="controls">
									<input type="radio" id="settings_disablesignup" name="settings_accountsignup" '.($this->config['signup_disabled'] ? 'checked="checked"' : '').' value="disablesignup">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="settings_disablesignup">
									'.$this->lang['cl_admin_276'].'
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_277'].'"><i class="icon-question-sign"></i></a>
								</label>
								<div class="controls">
									<input type="radio" id="admin_activation" name="settings_accountsignup" '.($this->config['admin_activation'] ? 'checked="checked"' : '').' value="admin_activation">
								</div>
							</div>
							<legend>'.$this->lang['cl_admin_273'].'</legend>
							<div class="control-group">
								<label class="control-label" for="settings_accountcheckintval">
									'.$this->lang['cl_admin_274'].'
								</label>
								<div class="controls">
									<input type="number" min="10" class="input-mini" name="settings_accountcheckintval" value="'.($this->config['account_check_interval']/1000).'">
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_275'].'"><i class="icon-question-sign"></i></a>
								</div>
							</div>';
				break;
				
			case 'invitereferral':
				$output = '<legend>'.$this->lang['cl_admin_200'].' <button class="btn btn-success btn_savesettings"><i class="icon-arrow-right icon-white"></i> '.$this->lang['cl_admin_205'].'</button></legend>
							<div class="control-group">
								<label class="control-label" for="settings_enableinvites">
									'.$this->lang['cl_admin_201'].'
								</label>
								<div class="controls">
									<input type="checkbox" id="settings_enableinvites" name="settings_enableinvites" '.($this->config['invite_system'] ? 'checked="checked"' : '').'>
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_202'].'"><i class="icon-question-sign"></i></a>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="settings_inviteonly">
									'.$this->lang['cl_admin_203'].'
								</label>
								<div class="controls">
									<input type="checkbox" id="settings_inviteonly" name="settings_inviteonly" '.($this->config['invite_only'] ? 'checked="checked"' : '').'>
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_204'].'"><i class="icon-question-sign"></i></a>
								</div>
							</div>';
				break;
				
			case 'legal':
				$output = '<legend>'.$this->lang['cl_admin_134'].' <button class="btn btn-success btn_savesettings"><i class="icon-arrow-right icon-white"></i> '.$this->lang['cl_admin_75_a'].'</button></legend>
							<div class="control-group">
								<label class="control-label" for="settings_legalsignuprequired">
									'.$this->lang['cl_admin_135'].'
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_136'].'"><i class="icon-question-sign"></i></a>
								</label>
								<div class="controls">
									<input type="checkbox" id="settings_legalsignuprequired" name="settings_legalsignuprequired" '.($this->config['termsrequired'] ? 'checked="checked"' : '').' />
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="settings_legaltext">
									'.$this->lang['cl_admin_137'].'
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_138'].'"><i class="icon-question-sign"></i></a>
								</label>
								<div class="controls">
									<textarea class="input-xxlarge" rows="12" name="settings_legaltext">'.$this->config['termsconditions'].'</textarea>
								</div>
							</div>';
				break;
				
			case 'forum':
				$output = '<legend>Forum Settings <button class="btn btn-success btn_savesettings"><i class="icon-arrow-right icon-white"></i> '.$this->lang['cl_admin_75_a'].'</button></legend>
							<div class="control-group">
								<label class="control-label" for="settings_forumenabled">
									Enable Forum
									<a href="#" data-rel="tooltip" data-title="If the forum is disabled, users with admin permissions will still be able to access it."><i class="icon-question-sign"></i></a>
								</label>
								<div class="controls">
									<input type="checkbox" id="settings_forumenabled" name="settings_forumenabled" '.($this->config['forum_enabled'] ? 'checked="checked"' : '').' />
								</div>
							</div>';
				break;
				
			case 'langtime':
				$output = '<legend>'.$this->lang['cl_admin_139'].' <button class="btn btn-success btn_savesettings"><i class="icon-arrow-right icon-white"></i> '.$this->lang['cl_admin_75_a'].'</button></legend>
							<div class="control-group">
								<label class="control-label" for="settings_legalsignuprequired">
									'.$this->lang['cl_admin_140'].'
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_141'].'"><i class="icon-question-sign"></i></a>
								</label>
								<div class="controls">
									<select name="defaultlang" class="span1">';
									
									foreach(scandir(BASE_PATH.'/system/lang') as $lang){
										if($lang !=  '.' && $lang !=  '..'){
											$lang = str_replace(array('lang_', '.php'), '', $lang);
											$output .= '<option '.($this->config['mailer_type'] == $lang ? 'selected="selected"' : '').' value="'.$lang.'">'.ucfirst($lang).'</option>';
										}
									}
										
						$output .= '</select>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="settings_timeformatlong">
									'.$this->lang['cl_admin_142'].'
								</label>
								<div class="controls">
									<input type="text" class="input-small" id="settings_timeformatlong" name="settings_timeformatlong" value="'.$this->config['dateformat_long'].'">
									<p class="help-block">'.$this->lang['cl_admin_143'].'</p>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="settings_timeformatshort">
									'.$this->lang['cl_admin_144'].'
								</label>
								<div class="controls">
									<input type="text" class="input-small" id="settings_timeformatshort" name="settings_timeformatshort" value="'.$this->config['dateformat_short'].'">
									<p class="help-block">'.$this->lang['cl_admin_145'].'</p>
								</div>
							</div>';
				break;
		}
		
		return $output;
	}
	
	public function emailTemplateList(){
		//check if the user is admin
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		$output = '<select name="emailtemplatelist">
						<option value="">'.$this->lang['cl_admin_148'].'</option>';
					
					foreach(scandir(SYSTEM_PATH.'/templates/emails') as $tempalte){
						if($tempalte !=  '.' && $tempalte !=  '..'){
							$tempalte = str_replace(array('tmpl_', '.php'), '', $tempalte);
							$output .= '<option value="'.$tempalte.'">'.ucfirst(str_replace('_',' ',$tempalte)).'</option>';
						}
					}
						
		$output .= '</select>';
		
		return $output;
	}
	
	public function loadEmailTemplate(){
		if(!$this->loggedin){
			exit;
		}
		//check if the user is admin
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		if(empty($_POST['tmpl'])){
			$status['message'] = $this->lang['cl_admin_149'];
			$status['status'] = false;
			return $status;
		}
		
		$_POST['tmpl'] = $this->sanitize($_POST['tmpl'], 'string');
		
		if(!file_exists(SYSTEM_PATH.'/templates/emails/tmpl_'.$_POST['tmpl'].'.php')){
			$status['message'] = $this->lang['cl_admin_150'];
			$status['status'] = false;
			return $status;
		}
		
		require(SYSTEM_PATH.'/templates/emails/tmpl_'.$_POST['tmpl'].'.php');
		$status['status'] = true;
		$status['html'] = '<legend>'.$email['title'].'</legend>
							<div class="control-group">
								<label class="control-label" for="tmpl_title">
									'.$this->lang['cl_admin_151'].'
								</label>
								<div class="controls">
									<input type="text" class="input-xlarge" id="tmpl_title" name="tmpl_title" value="'.$email['title'].'" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="tmpl_title">
									'.$this->lang['cl_admin_152'].'
									<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_153'].'"><i class="icon-question-sign"></i></a>
								</label>
								<div class="controls">
									<textarea name="tmpl_body" class="input-xxlarge" rows="12">'.$email['body'].'</textarea>
								</div>
							</div>';
							
					$variables = $email['variables'];
					
					if(!empty($email['variables'])){
						$email['variables'] = explode(',',$email['variables']);
						
						$status['html'] .= '<div class="control-group">
											<label class="control-label" for="tmpl_title">
												'.$this->lang['cl_admin_154'].'
											</label>
											<div class="controls">
												<div class="help-inline">';
												
							if(in_array('site_url', $email['variables'])){
								$status['html'] .= '<p>
														'.$this->lang['cl_admin_155'].' 
														<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_156'].'"><i class="icon-question-sign"></i></a>: 
														<code>{{site_url}}</code>
													</p>';
							}
							
							if(in_array('website_name', $email['variables'])){
								$status['html'] .= '<p>
														'.$this->lang['cl_admin_156a'].'
														<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_156b'].'"><i class="icon-question-sign"></i></a>:
														<code>{{website_name}}</code>
													</p>';
							}
							
							if(in_array('username', $email['variables'])){
								$status['html'] .= '<p>
														'.$this->lang['cl_admin_157'].' 
														<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_158'].'"><i class="icon-question-sign"></i></a>:
														<code>{{username}}</code>
													</p>';
							}
							
							if(in_array('email', $email['variables'])){
								$status['html'] .= '<p>
														'.$this->lang['cl_admin_159'].'
														<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_160'].'"><i class="icon-question-sign"></i></a>:
														<code>{{email}}</code>
													</p>';
							}
							
							if(in_array('password', $email['variables'])){
								$status['html'] .= '<p>
														'.$this->lang['cl_admin_161'].'
														<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_162'].'"><i class="icon-question-sign"></i></a>:
														<code>{{password}}</code>
													</p>';
							}
							
							if(in_array('activationcode', $email['variables'])){
								$status['html'] .= '<p>
														'.$this->lang['cl_admin_163'].'
														<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_164'].'"><i class="icon-question-sign"></i></a>:
														<code>{{activationcode}}</code>
													</p>';
							}
							
							if(in_array('resetcode', $email['variables'])){
								$status['html'] .= '<p>
														'.$this->lang['cl_admin_165'].'
														<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_166'].'"><i class="icon-question-sign"></i></a>:
														<code>{{resetcode}}</code>
													</p>';
							}
							
							if(in_array('invitecode', $email['variables'])){
								$status['html'] .= '<p>
														'.$this->lang['cl_admin_206'].'
														<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_207'].'"><i class="icon-question-sign"></i></a>:
														<code>{{invitecode}}</code>
													</p>';
							}
							
							if(in_array('newpassword', $email['variables'])){
								$status['html'] .= '<p>
														'.$this->lang['cl_admin_167'].'
														<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_168'].'"><i class="icon-question-sign"></i></a>:
														<code>{{newpassword}}</code>
													</p>';
							}
							
							if(in_array('visitor_ip', $email['variables'])){
								$status['html'] .= '<p>
														'.$this->lang['cl_admin_169'].'
														<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_170'].'"><i class="icon-question-sign"></i></a>:
														<code>{{visitor_ip}}</code>
													</p>';
							}
													
							$status['html'] .= '</div>
											</div>
										</div>';
					}
					
		$status['html'] .= '<hr />
							<button class="btn btn-success saveEmailTemplate">'.$this->lang['cl_admin_171'].'</button>
							<input type="hidden" name="tmpl_variables" value="'.$variables.'" />
							<input type="hidden" name="tmpl" value="'.$_POST['tmpl'].'" />';
		
		return $status;
	}
	
	public function saveEmailTemplate(){
		if(!$this->loggedin){
			exit;
		}
		//check if the user is admin
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		if(empty($_POST['tmpl'])){
			$status['message'] = $this->lang['cl_admin_172'];
			$status['status'] = false;
			return $status;
		}
		
		$_POST['tmpl'] = $this->sanitize($_POST['tmpl'], 'string');
		$_POST['tmpl_title'] = $_POST['tmpl_title'];
		$_POST['tmpl_body'] = $_POST['tmpl_body'];
		$_POST['tmpl_variables'] = $_POST['tmpl_variables'];
		
$output = '<?php
$email[\'title\'] = "'.str_replace('\'',"\'", $_POST['tmpl_title']).'";
$email[\'body\'] = "'.str_replace('\'',"\'", $_POST['tmpl_body']).'";
$email[\'variables\'] = \''.$_POST['tmpl_variables'].'\';
';
		
		$theFile = fopen(SYSTEM_PATH.'/templates/emails/tmpl_'.$_POST['tmpl'].'.php', 'w');
		fwrite($theFile, $output);
		fclose($theFile);
		
		$status['message'] = $this->lang['cl_admin_173'];
		$status['status'] = true;
		
		return $status;
	}
	
	/************ Envío de email ******************/
	## 	  Mailing System 	 ##
	protected function send_mail($to, $subject, $body){
		$headers = 'MIME-Version: 1.0'."\r\n".
				  'Content-type: text/html; charset=utf8'."\r\n".
				  'From: owloo<noreply@latamclick.com>'."\r\n";

		if(!mail($to, '=?UTF-8?B?'.base64_encode($subject).'?=', $body, $headers)){
			return false;
		}else{
			return true;
		}
	}
	
	protected function render_email(array $values = array(), $text){
		//run through the body and replace any keys with the data from the array
		foreach($values as $key => $txt){
			$text = preg_replace('#\{{'.$key.'}}#s', $txt, $text);
		}
		
		return $text;
	}
	
	protected function dav_send_mail(array $variables=array()){
		require_once(SYSTEM_PATH.'/templates/emails/tmpl_dav_enviar_email.php');
		$subject = $variables['subject'];
		$body = $this->render_email($variables, $email['body']);
		
		$error_envio = '';
		$correos = $pieces = explode(",", $variables['to']);
		foreach($correos as $correo){
			if(!$this->send_mail($correo, $subject, $body)){
				$error_envio .= $correo.' - ';
			}
		}
		
		return $error_envio;
	}
	
	protected function dav_send_mail_all(array $variables=array()){
		require_once(SYSTEM_PATH.'/templates/emails/tmpl_dav_enviar_email.php');
		$subject = $variables['subject'];
		$body = $this->render_email($variables, $email['body']);
		
		$error_envio = '';
		
		$stmt = $this->sql->prepare('SELECT
											email
										FROM
											members
										WHERE 
											membergroup = 1;
										'); //Usuarios básicos
											
		$stmt->execute(array());
		
		if($stmt->rowCount() > 0){
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				if(!$this->send_mail($row['email'], $subject, $body)){
					$error_envio .= $row['email'].' - ';
				}
			}
			
		}	
		
		return $error_envio;
	}
		
	/************ Envío de emails *****************/
	public function enviarMensajeAll(){
		if(!$this->loggedin){
			exit;
		}
		//check if the user is admin
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		$_POST['dav_to'] = str_replace(' ', '', $_POST['dav_to']);  
		
		if(empty($_POST['dav_to']) && empty($_POST['dav_enviarAll'])){
			$status['message'] = 'Ingresa el destinatario del correo.';
			$status['status'] = false;
			return $status;
		}
		if(empty($_POST['dav_enviarAll'])){
			$correos = $pieces = explode(",", $_POST['dav_to']);
			foreach($correos as $correo){
				if(!filter_var($correo, FILTER_VALIDATE_EMAIL)){
					$status['message'] = 'Ingresa una dirección de correo válida.';
					$status['status'] = false;
					return $status;		
				}
			}
		}
		if(empty($_POST['dav_subject'])){
			$status['message'] = 'Ingresa el asunto del correo.';
			$status['status'] = false;
			return $status;
		}
		if(empty($_POST['dav_message'])){
			$status['message'] = 'Ingresa el mensaje del correo';
			$status['status'] = false;
			return $status;
		}
		
		if(empty($_POST['dav_enviarAll'])){ //Envío de correo a usuarios específicos
			$variables = array(	'to' => $_POST['dav_to'],
								'subject' => str_replace("\'", "'", $_POST['dav_subject']),
								'message_title' => str_replace("\'", "'", $_POST['dav_message_title']),
								'message' => str_replace("\'", "'", $_POST['dav_message']));
			$estado = $this->dav_send_mail($variables);
			if(!empty($$estado)){
				$status['message'] = 'Error en el envío del correo a: '.$estado;
				$status['status'] = false;
				return $status;
			}
		}else{
			$variables = array(	'subject' => str_replace("\'", "'", $_POST['dav_subject']),
								'message_title' => str_replace("\'", "'", $_POST['dav_message_title']),
								'message' => str_replace("\'", "'", $_POST['dav_message']));
			$estado = $this->dav_send_mail_all($variables);
			if(!empty($$estado)){
				$status['message'] = 'Error en el envío del correo a: '.$estado;
				$status['status'] = false;
				return $status;
			}
		}
		
		
		$status['message'] = 'Mensaje enviado correctamente.';
		$status['status'] = true;
		return $status;
	}
	
	/*
		Language Editor
	*/
	
	public function languageList(){
		//check if the user is admin
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		$output = '<select name="languagelist">
						<option value="">'.$this->lang['cl_admin_174'].'</option>';
					
					foreach(scandir(SYSTEM_PATH.'/lang') as $lang){
						if($lang !=  '.' && $lang !=  '..'){
							$lang = str_replace(array('tmpl_', '.php'), '', $lang);
							$output .= '<option value="'.$lang.'">'.ucfirst(str_replace('lang_','',$lang)).'</option>';
						}
					}
						
		$output .= '</select>';
		
		return $output;
	}
	
	public function loadEditLanguage(){
		if(!$this->loggedin){
			exit;
		}
		//check if the user is admin
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		if(empty($_POST['lang'])){
			$status['message'] = $this->lang['cl_admin_175'];
			$status['status'] = false;
			return $status;
		}
		
		$_POST['lang'] = $this->sanitize($_POST['lang'], 'string');
		
		if(!file_exists(SYSTEM_PATH.'/lang/'.$_POST['lang'].'.php')){
			$status['message'] = $this->lang['cl_admin_176'];
			$status['status'] = false;
			return $status;
		}
		
		require(SYSTEM_PATH.'/lang/'.$_POST['lang'].'.php');
		$status['status'] = true;
		$status['html'] = '<legend>'.$lang['title'].'</legend>
							<div class="control-group">
								<label class="control-label" for="lang_title">
									'.$this->lang['cl_admin_177'].'
								</label>
								<div class="controls">
									<input type="text" class="input-xlarge" id="lang_title" name="lang_title" value="'.$lang['title'].'" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">
									'.$this->lang['cl_admin_178'].'
								</label>
								<div class="controls">';
									foreach($lang as $key => $string){
										if($key != 'title'){
											$status['html'] .= '<textarea class="input-xxlarge" name="lang['.$key.']">'.$string.'</textarea><br />';
										}
									}
									
			$status['html'] .= '</div>
							</div>';
					
		$status['html'] .= '<hr />
							<button class="btn btn-success saveLanguage">'.$this->lang['cl_admin_179'].'</button>
							<input type="hidden" name="editlang" value="'.$_POST['lang'].'" />';
		
		return $status;
	}
	
	public function saveLanguage(){
		if(!$this->loggedin){
			exit;
		}
		//check if the user is admin
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		if(empty($_POST['editlang'])){
			$status['message'] = $this->lang['cl_admin_180'];
			$status['status'] = false;
			return $status;
		}
		
		$_POST['editlang'] = $this->sanitize($_POST['editlang'], 'string');
		$_POST['lang_title'] = str_replace('\'',"\'", $_POST['lang_title']);
		
$output = '<?php
$lang = array();
$lang[\'title\'] = \''.str_replace('\'',"\'", $_POST['lang_title']).'\';'."\n";
		foreach($_POST['lang'] as $key => $string){
			$output .= '$lang[\''.$key.'\'] = \''.str_replace('\'',"\'", $string).'\';'."\n";
		}
		
		$theFile = fopen(SYSTEM_PATH.'/lang/'.$_POST['editlang'].'.php', 'w');
		fwrite($theFile, $output);
		fclose($theFile);
		
		$status['message'] = $this->lang['cl_admin_181'];
		$status['status'] = true;
		
		return $status;
	}
	
	public function clearCache(){
		if(!$this->loggedin){
			exit;
		}
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		foreach(@scandir($this->config['cache_dir']) as $cached){
			if($cached !=  '.' && $cached !=  '..'){
				unlink($this->config['cache_dir'].'/'.$cached);
			}
		}
		
		return true;
	}
	
	public function loadProfileFields(){
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		$pfields = $this->sql->query('SELECT * FROM profile_fields');
		
		$output = '<legend>'.$this->lang['cl_admin_182'].' <button class="btn btn-info profile_field_add"><i class="icon-plus-sign icon-white"></i> '.$this->lang['cl_admin_183'].'</button></legend>
						<table class="table profile_fields">
							<thead>
								<tr>
									<th>
										'.$this->lang['cl_admin_184'].'
										<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_185'].'"><i class="icon-question-sign"></i></a>
									</th>
									<th>'.$this->lang['cl_admin_186'].'</th>
									<th>
										'.$this->lang['cl_admin_187'].' 
										<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_188'].'"><i class="icon-question-sign"></i></a>
									</th>
									<th>'.$this->lang['cl_admin_189'].'</th>
									<th>'.$this->lang['cl_admin_190'].'</th>
									<th>
										'.$this->lang['cl_admin_191'].'
										<a href="#" data-rel="tooltip" data-title="'.$this->lang['cl_admin_192'].'"><i class="icon-question-sign"></i></a>
									</th>
									<th></th>
								</tr>
							</thead>
							<tbody>';
		
		foreach($pfields as $field){
			$output .= '<tr id="field_row_'.$field['p_id'].'">
							<td><input type="text" name="profile_field_group['.$field['p_id'].']" placeholder="'.$this->lang['cl_admin_193'].'" class="input-medium" value="'.$field['p_group'].'"></td>
							<td>
								<select name="profile_field_type['.$field['p_id'].']" class="input-small">
									<option value="0" '.($field['p_type'] == 0 ? 'selected="selected"' : '').'>Textarea</option>
									<option value="1" '.($field['p_type'] == 1 ? 'selected="selected"' : '').'>Text Field</option>
									<option value="2" '.($field['p_type'] == 2 ? 'selected="selected"' : '').'>Checkbox</option>
									<option value="3" '.($field['p_type'] == 3 ? 'selected="selected"' : '').'>Radio</option>
									<option value="4" '.($field['p_type'] == 4 ? 'selected="selected"' : '').'>Select</option>
								</select>
							</td>
							<td>
								<input type="text" name="profile_field_options['.$field['p_id'].']" placeholder="'.$this->lang['cl_admin_194'].'" class="input-medium" value="'.$field['p_options'].'" />
							</td>
							<td>
								<select name="profile_field_signup['.$field['p_id'].']" class="input-small">
									<option value="0" '.($field['p_signup'] == 0 ? 'selected="selected"' : '').'>'.$this->lang['cl_admin_195'].'</option>
									<option value="1" '.($field['p_signup'] == 1 ? 'selected="selected"' : '').'>'.$this->lang['cl_admin_196'].'</option>
									<option value="2" '.($field['p_signup'] == 2 ? 'selected="selected"' : '').'>'.$this->lang['cl_admin_197'].'</option>
								</select>
							</td>
							<td><input type="text" name="profile_field_label['.$field['p_id'].']" placeholder="'.$this->lang['cl_admin_198'].'" class="input-medium" value="'.$field['p_label'].'"></td>
							<td><input type="checkbox" name="profile_field_profile['.$field['p_id'].']" '.($field['p_profile'] == 1 ? 'checked="checked"' : '').' value="1"></td>
							<td><button class="btn btn-warning profile_field_delete">'.$this->lang['cl_admin_199'].'</button></td>
						</tr>';
		}
		
		$output .='</tbody>
				</table>';
		
		return $output;
	}
	
	public function deleteProfileField(){
		if(!$this->loggedin){
			exit;
		}
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		$_POST['fid'] = $this->sanitize($_POST['fid'],'integer');
		$stmt= $this->sql->prepare('DELETE FROM profile_fields WHERE p_id = ?');
		$stmt->execute(array($_POST['fid']));
		
		return true;
	}
	
	public function saveProfileFields(){
		if(!$this->loggedin){
			exit;
		}
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		if(empty($_POST['profile_field_group'])){
			return false;
		}
		
		$data = array();
		$query = '';
		foreach($_POST['profile_field_group'] as $id => $group){
			if(!empty($_POST['profile_field_group'])){				
				$query .= (!empty($query) ? ',' : '').'(?,?,?,?,?,?,?)';
				$data[] = $this->sanitize($id, 'integer');
				$data[] = $this->sanitize($_POST['profile_field_type'][$id], 'integer');
				$data[] = $this->sanitize($_POST['profile_field_options'][$id], 'string');
				$data[] = $this->sanitize($_POST['profile_field_label'][$id], 'string');
				$data[] = $this->sanitize($group, 'string');
				$data[] = $this->sanitize($_POST['profile_field_profile'][$id], 'integer');
				$data[] = $this->sanitize($_POST['profile_field_signup'][$id], 'integer');
			}
		}
		
		$stmt = $this->sql->prepare('INSERT INTO 
												profile_fields 
										VALUES 
											'.$query.'
									ON DUPLICATE KEY UPDATE 
										p_type = VALUES(p_type),
										p_options = VALUES(p_options),
										p_label = VALUES(p_label),
										p_group = VALUES(p_group),
										p_profile = VALUES(p_profile),
										p_signup = VALUES(p_signup)');
		$stmt->execute($data);
		
		return true;
	}
	
	public function checkversion($getversion = false){
		if(!empty($this->config['version']) && in_array('curl', get_loaded_extensions())){
			$version = explode('.',$this->config['version']);
			
			$data = array(
				'product' => 'sphpums',
				'version_major' => $version[0],
				'version_minor' => $version[1],
				'version_revision' => $version[2],
				'version_patch' => $version[3]
			);
			
			if($getversion){
				return implode('',$version);
			}
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://www.zolidweb.com/versionchecker.php");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('REFERER: '.$_SERVER['SERVER_NAME'])); 
			// The additional header is used to avoid spamming of the version checker script from the same domain within a short period of time.
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			$output = curl_exec($ch);
			curl_close($ch);
			
			return json_decode($output);
		}
	}
	
	public function checkFirstAdmin(){
		if($this->installed){
			$stmt = $this->sql->query('SELECT id FROM members WHERE firstadmin = 1 LIMIT 1');
			
			if($stmt->rowCount() > 0){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	private function _generateForumStructure($main){
		//if there are no sub categories
		if( empty($this->subs[$main['c_id']]) ){
			$this->structure .= '<li class="dd-item dd3-item" data-id="'.$main['c_id'].'">
									<div class="dd-handle dd3-handle">Drag</div>
									<div class="dd3-content">
										<span>'.$main['c_name'].'</span>
										<button data-cid="'.$main['c_id'].'" class="forum_cat_delete pull-right btn btn-danger btn-mini">'.$this->lang['cl_admin_212'].'</button>
										<button data-cid="'.$main['c_id'].'" class="forum_cat_edit pull-right btn btn-success btn-mini">'.$this->lang['cl_admin_213'].'</button>
									</div>
								</li>';
		}else{
			//else continue
			$this->structure .= '<li class="dd-item dd3-item" data-id="'.$main['c_id'].'">
									<div class="dd-handle dd3-handle">Drag</div>
									<div class="dd3-content">
										<span>'.$main['c_name'].'</span>
										<button data-cid="'.$main['c_id'].'" class="forum_cat_delete pull-right btn btn-danger btn-mini">'.$this->lang['cl_admin_212'].'</button>
										<button data-cid="'.$main['c_id'].'" class="forum_cat_edit pull-right btn btn-success btn-mini">'.$this->lang['cl_admin_213'].'</button>
									</div>
									<ol class="dd-list">';
									
			foreach($this->subs[$main['c_id']] as $sub){
				$this->_generateForumStructure($sub);
			}
			
			$this->structure .= '</ol>
							</li>';
		}
	}
	
	public function loadForumManager(){
		$stmt = $this->sql->query('SELECT * FROM forum_categories ORDER BY c_sort ASC');
		$stmt->execute();
		
		$mains = array();
		$output = '<p><button id="addForumCateogry" class="btn btn-small btn-info">'.$this->lang['cl_admin_214'].'</button> <button id="saveForum" class="btn btn-small btn-success">'.$this->lang['cl_admin_215'].'</button></p>
		'.$this->lang['cl_admin_216'];
		
		$this->structure = '<div class="dd">
								<ol class="dd-list forum_list">';
								
		if($stmt->rowCount() > 0){
			
									
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				if($row['c_sub'] > 0){
					$this->subs[$row['c_sub']][] = $row;
				}else{
					$mains[] = $row;
				}
			}
			
			foreach($mains as $main){
				$this->_generateForumStructure($main);
			}
		}
		
		$this->structure .= '</ol>
						</div>';
		
		$stmt->closeCursor();
		
		return $output.$this->structure;
	}
	
	public function process_saveforum(){
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		$forums = json_decode(urldecode(str_replace('\"', '"', $_POST['data'])), true);
		
		$data = array();
		$query = '';
		
		if( empty($forums['forums']) || !is_array($forums['forums']) ){
			return array('status'=>false, 'msg'=>'No forum categories was received. If you are using IE7 or ealier you will not be able to save your forum structure.');
		}
		
		foreach($forums['forums'] as $cat){
			$data[] = $cat['id'];
			$data[] = $cat['sort'];
			$data[] = $cat['sub'];
			$data[] = $cat['name'];
			$query .= (empty($query) ? '' : ',').'(?,?,?,?)';
		}
		
		$stmt = $this->sql->prepare('INSERT INTO 
												forum_categories (c_id, c_sort, c_sub, c_name)
											VALUES 
												'.$query.'
									ON DUPLICATE KEY UPDATE 
												c_name = VALUES(c_name),
												c_sort = VALUES(c_sort),
												c_sub = VALUES(c_sub)');
		
		if($stmt->execute($data)){
			$status = true;
			$msg = $this->lang['cl_admin_217'];
		}else{
			$status = false;
			$msg = $this->lang['cl_admin_218'];
		}
		
		$stmt->closeCursor();
		
		return array('status'=>$status, 'msg'=>$msg);
	}
	
	public function process_deleteforum(){
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		$_POST['fid'] = $this->sanitize($_POST['fid'], 'integer');
		
		$stmt = $this->sql->prepare('DELETE FROM forum_posts WHERE p_catid = ?; DELETE FROM forum_categories WHERE c_id = ?');
		
		if($stmt->execute(array($_POST['fid'],$_POST['fid']))){
			$status = true;
			$msg = $this->lang['cl_admin_219'];
		}else{
			$status = false;
			$msg = $this->lang['cl_admin_220'];
		}
		
		$stmt->closeCursor();
		
		return array('status'=>$status, 'msg'=>$msg);
	}
	
	public function process_loadcatsettings(){
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		$_POST['fid'] = $this->sanitize($_POST['fid'], 'integer');

		// get all the member groups
		$stmt = $this->sql->prepare('SELECT id, name FROM member_groups');
		$stmt->execute();
		$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt->closeCursor();
		
		// get the category
		$stmt = $this->sql->prepare('SELECT * FROM forum_categories WHERE c_id = ?');
		
		$status = array();
		
		if($stmt->execute(array($_POST['fid']))){
			$cat = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$cat = $cat[0];
			
			$permissions = json_decode($cat['c_permissions'], true);
			
			$status['status'] = true;
			$status['data'] = '<fieldset>
									<div class="control-group">
										<label class="control-label" for="cat_name">
											'.$this->lang['cl_admin_221'].'
										</label>
										<div class="controls">
											<input type="text" class="input-xlarge" id="cat_name" name="cat_name" value="'.$cat['c_name'].'">
										</div>
									</div>
									
									<hr />
									
									<div class="control-group">
										<label class="control-label" for="">
											'.$this->lang['cl_admin_222'].'
										</label>
										<div class="controls">
											<select name="viewperm[]" multiple="multiple">';
											
											foreach($groups as $grp){
												$status['data'] .= '<option value="'.$grp['id'].'" '.(!empty($permissions['viewperm']) && in_array($grp['id'], $permissions['viewperm']) ? 'selected="selected"' : '').'>'.$grp['name'].'</option>';
											}
											
						$status['data'] .= '</select>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="">
											'.$this->lang['cl_admin_223'].'
										</label>
										<div class="controls">
											<select name="topicperm[]" multiple="multiple">';
											
											foreach($groups as $grp){
												$status['data'] .= '<option value="'.$grp['id'].'" '.(!empty($permissions['topicperm']) && in_array($grp['id'], $permissions['topicperm']) ? 'selected="selected"' : '').'>'.$grp['name'].'</option>';
											}
											
											
						$status['data'] .= '</select>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="">
											'.$this->lang['cl_admin_224'].'
										</label>
										<div class="controls">
											<select name="replyperm[]" multiple="multiple">';
											
											foreach($groups as $grp){
												$status['data'] .= '<option value="'.$grp['id'].'" '.(!empty($permissions['replyperm']) && in_array($grp['id'], $permissions['replyperm']) ? 'selected="selected"' : '').'>'.$grp['name'].'</option>';
											}
											
											
						$status['data'] .= '</select>
										</div>
									</div>
									<input type="hidden" name="cat_id" value="'.$cat['c_id'].'">
								</fieldset>';
		}else{
			$status['status'] = false;
			$msg = $this->lang['cl_admin_225'];
		}
		
		$stmt->closeCursor();
		
		return $status;
	}
	
	public function process_savecatsettings(){
		if(empty($_POST['cat_name'])){
			return array('status'=>false, 'msg'=>'');
		}
		
		$data = array();
		
		if(!empty($_POST['viewperm'])){
			foreach($_POST['viewperm'] as $vp){
				$data['viewperm'][] = $vp;
			}
		}
		if(!empty($_POST['topicperm'])){
			foreach($_POST['topicperm'] as $tp){
				$data['topicperm'][] = $tp;
			}
		}
		if(!empty($_POST['replyperm'])){
			foreach($_POST['replyperm'] as $rp){
				$data['replyperm'][] = $rp;
			}
		}
		
		$data = json_encode($data);
		
		$stmt = $this->sql->prepare('UPDATE forum_categories SET c_permissions = ?, c_name = ? WHERE c_id = ?');
		$stmt->execute(array($data, $this->sanitize($_POST['cat_name'], 'string'), $this->sanitize($_POST['cat_id'], 'integer')));
		$err = $stmt->errorInfo();
		$stmt->closeCursor();
		
		if($stmt->rowCount() > 0 || empty($err[2])){
			$status = array('status' => true, 'msg'=>$this->lang['cl_admin_226']);
		}else{
			$status = array('status' => false, 'msg'=>$this->lang['cl_admin_227'].(!empty($err[2]) ? $err[2] : ''));
		}
		
		return $status;
	}
	
	/* Social Connection */
	
	public function socialAuthAdm(){
		$config = include(SYSTEM_PATH.'/hybridauth/config.php');
		
		return '<legend>Social Connect <button class="btn btn-small btn-success btn_savesocialsettings"><i class="icon-arrow-right icon-white"></i> Save Settings</button></legend>
		
				<p>Allow users to sign up and login with their favorite social network.<br />If "Status" is set to Disabled for a given method below, then users will not be able to login with using this method on you website.</p>
				
				<form id="socialIntegration" class="form-horizontal" action="#">
					<div class="control-group">
						<label class="control-label" for"endpoint_url">Endpoint URL</label>
						<div class="controls">
							<code>'.$this->base_url.'/system/hybridauth/</code>
							<input type="hidden" name="GLOBAL_HYBRID_AUTH_URL_BASE" value="'.$this->base_url.'/system/hybridauth/">
						</div>
					</div>
					
					<hr />
					
					<h3>Twitter</h3>
					<div class="control-group">
						<label class="control-label">Status</label>
						<div class="controls">
							<select name="twitter_ADAPTER_STATUS">
								<option value="0">Disabled</option>
								<option value="1" '.($config['providers']['Twitter']['enabled'] ? 'selected="selected"' : '').'>Enabled</option>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="twitter_ID">Application Key</label>
						<div class="controls">
							<input class="input-xlarge" type="text" id="twitter_APPLICATION_KEY" name="twitter_APPLICATION_KEY" value="'.$config['providers']['Twitter']['keys']['key'].'">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="twitter_secret">Application Secret</label>
						<div class="controls">
							<input class="input-xlarge" type="text" id="twitter_APPLICATION_SECRET" name="twitter_APPLICATION_SECRET" value="'.$config['providers']['Twitter']['keys']['secret'].'">
						</div>
					</div>
					<div class="help-block">
						Callback URL: <code>'.$this->base_url.'/system/hybridauth/?hauth.done=Twitter</code>
						<ol>
							<li>Go to <a href="https://dev.twitter.com/apps">https://dev.twitter.com/apps</a> and create a new application.</li>
							<li>Fill out any required fields such as the application name and description.</li>
							<li>Put your website domain in the Application Website and Application Callback URL fields.</li>
							<li>Set the Default Access Type to Read, Write, & Direct Messages.</li>
							<li>Once you have registered, copy and past the created application credentials into the fields above.</li>
						</ol>
					</div>
					
					<hr />
					
					<h3>Facebook</h3>
					<div class="control-group">
						<label class="control-label">Status</label>
						<div class="controls">
							<select name="facebook_ADAPTER_STATUS">
								<option value="0">Disabled</option>
								<option value="1" '.($config['providers']['Facebook']['enabled'] ? 'selected="selected"' : '').'>Enabled</option>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="facebook_ID">Application ID</label>
						<div class="controls">
							<input class="input-xlarge" type="text" id="facebook_APPLICATION_APP_ID" name="facebook_APPLICATION_APP_ID" value="'.$config['providers']['Facebook']['keys']['id'].'">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="facebook_secret">Application Secret</label>
						<div class="controls">
							<input class="input-xlarge" type="text" id="facebook_APPLICATION_SECRET" name="facebook_APPLICATION_SECRET" value="'.$config['providers']['Facebook']['keys']['secret'].'">
						</div>
					</div>
					<div class="help-block">
						Callback URL: <code>'.$this->base_url.'/system/hybridauth/?hauth.done=Facebook</code>
						<ol>
							<li>Go to <a href="https://www.facebook.com/developers/">https://www.facebook.com/developers/</a> and create a new application.</li>
							<li>Fill out any required fields such as the application name and description.</li>
							<li>Put your website domain in the Site Url field.</li>
							<li>Once you have registered, copy and past the created application credentials into the fields above.</li>
						</ol>
					</div>
					
					<hr />
					
					<h3>Google</h3>
					<div class="control-group">
						<label class="control-label">Status</label>
						<div class="controls">
							<select name="google_ADAPTER_STATUS">
								<option value="0">Disabled</option>
								<option value="1" '.($config['providers']['Google']['enabled'] ? 'selected="selected"' : '').'>Enabled</option>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="google_ID">Application ID</label>
						<div class="controls">
							<input class="input-xlarge" type="text" id="google_APPLICATION_APP_ID" name="google_APPLICATION_APP_ID" value="'.$config['providers']['Google']['keys']['id'].'">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="google_secret">Application Secret</label>
						<div class="controls">
							<input class="input-xlarge" type="text" id="google_APPLICATION_SECRET" name="google_APPLICATION_SECRET" value="'.$config['providers']['Google']['keys']['secret'].'">
						</div>
					</div>
					<div class="help-block">
						Callback URL: <code>'.$this->base_url.'/system/hybridauth/?hauth.done=Google</code>
						<ol>
							<li>Go to <a href="https://code.google.com/apis/console/">https://code.google.com/apis/console/</a> and create a new application.</li>
							<li>Fill out any required fields such as the application name and description.</li>
							<li>On the <b>"Create Client ID"</b> popup switch to advanced settings by clicking on <b>(more options)</b>.</li>
							<li>Provide this URL as the Callback URL for your application</li>
							<li>Once you have registered, copy and past the created application credentials into the fields above.</li>
						</ol>
					</div>
					
					<hr />
					
					<h3>Yahoo!</h3>
					<div class="control-group">
						<label class="control-label">Status</label>
						<div class="controls">
							<select name="yahoo_ADAPTER_STATUS">
								<option value="0">Disabled</option>
								<option value="1" '.($config['providers']['Yahoo']['enabled'] ? 'selected="selected"' : '').'>Enabled</option>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="yahoo_ID">Application ID</label>
						<div class="controls">
							<input class="input-xlarge" type="text" id="yahoo_APPLICATION_KEY" name="yahoo_APPLICATION_KEY" value="'.$config['providers']['Yahoo']['keys']['id'].'">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="yahoo_secret">Application Secret</label>
						<div class="controls">
							<input class="input-xlarge" type="text" id="yahoo_APPLICATION_SECRET" name="yahoo_APPLICATION_SECRET" value="'.$config['providers']['Yahoo']['keys']['secret'].'">
						</div>
					</div>
					<div class="help-block">
						Callback URL: <code>'.$this->base_url.'/system/hybridauth/?hauth.done=Yahoo</code>
						<ol>
							<li>Go to <a href="https://developer.apps.yahoo.com/dashboard/createKey.html">https://developer.apps.yahoo.com/dashboard/createKey.html</a> and create a new application.</li>
							<li>Put your website domain in the Application URL and Application Domain fields.</li>
							<li>Set the Kind of Application to "Web-based".</li>
							<li>Once you have registered, copy and past the created application credentials into the fields above.</li>
						</ol>
					</div>
					
					<hr />
					
					<h3>LinkedIn</h3>
					<div class="control-group">
						<label class="control-label">Status</label>
						<div class="controls">
							<select name="linkedin_ADAPTER_STATUS">
								<option value="0">Disabled</option>
								<option value="1" '.($config['providers']['LinkedIn']['enabled'] ? 'selected="selected"' : '').'>Enabled</option>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="linkedin_ID">Application ID</label>
						<div class="controls">
							<input class="input-xlarge" type="text" id="linkedin_APPLICATION_KEY" name="linkedin_APPLICATION_KEY" value="'.$config['providers']['LinkedIn']['keys']['key'].'">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="linkedin_secret">Application Secret</label>
						<div class="controls">
							<input class="input-xlarge" type="text" id="linkedin_APPLICATION_SECRET" name="linkedin_APPLICATION_SECRET" value="'.$config['providers']['LinkedIn']['keys']['secret'].'">
						</div>
					</div>
					<div class="help-block">
						Callback URL: <code>'.$this->base_url.'/system/hybridauth/?hauth.done=LinkedIn</code>
						<ol>
							<li>Go to <a href="https://www.linkedin.com/secure/developer">https://www.linkedin.com/secure/developer</a> and create a new application.</li>
							<li>Fill out any required fields such as the application name and description.</li>
							<li>Put your website domain in the Integration URL field.</li>
							<li>Set the Application Type to Web Application.</li>
							<li>Once you have registered, copy and past the created application credentials into the fields above.</li>
						</ol>
					</div>
					
					<hr />
					
					<h3>Tumblr</h3>
					<div class="control-group">
						<label class="control-label">Status</label>
						<div class="controls">
							<select name="Tumblr_ADAPTER_STATUS">
								<option value="0">Disabled</option>
								<option value="1" '.($config['providers']['Tumblr']['enabled'] ? 'selected="selected"' : '').'>Enabled</option>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="Tumblr_APPLICATION_KEY">Application ID</label>
						<div class="controls">
							<input class="input-xlarge" type="text" id="Tumblr_APPLICATION_KEY" name="Tumblr_APPLICATION_KEY" value="'.$config['providers']['Tumblr']['keys']['key'].'">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="Tumblr_APPLICATION_SECRET">Application Secret</label>
						<div class="controls">
							<input class="input-xlarge" type="text" id="Tumblr_APPLICATION_SECRET" name="Tumblr_APPLICATION_SECRET" value="'.$config['providers']['Tumblr']['keys']['secret'].'">
						</div>
					</div>
					<div class="help-block">
						Callback URL: <code>'.$this->base_url.'/system/hybridauth/?hauth.done=Tumblr</code>
						<ol>
							<li>Go to <a href="https://www.tumblr.com/login">https://www.tumblr.com/login</a> and create a new application.</li>
							<li>Fill out any required fields such as the application name and description.</li>
							<li>Once you have registered, copy and past the created application credentials into the fields above.</li>
						</ol>
					</div>
					
				</form>';
	}
	
	public function saveSocialAuth(){
		$template = file_get_contents( SYSTEM_PATH.'/hybridauth/Hybrid/resources/config.php.tpl');
		$c = 0;
		foreach($_POST AS $k => $v){
			if($k != 'call'){
				$v = strip_tags($this->sanitize($v, 'string'));
				$z = '#'.$this->sanitize(strtoupper($k), 'string').'#';
				$template = str_replace( $z, $v, $template );
				
				if(preg_match('/STATUS/', $k)){
					if($v){
						$c++;
					}
				}
			}
		}
		
		$template = str_replace( '#ACTIVE_COUNT#', $c, $template );
		
		$save = file_put_contents( SYSTEM_PATH.'/hybridauth/config.php', $template );
		
		if($save){
			return array('status'=>true, 'msg'=>'Settings Saved!');
		}else{
			return array('status'=>false, 'msg'=>'Unable to save settings, please make sure the file is writeable.');
		}
	}
	
	public function manualAccountActivation(){
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		if(empty($_POST['uid'])){
			return false;
		}
		
		$stmt = $this->sql->prepare('UPDATE members SET activation_key = "" WHERE id = ?');
		$stmt->execute(array($this->sanitize($_POST['uid'], 'integer')));
		$stmt->closeCursor();
		
		if($stmt->rowCount() > 0){
			$stmt = $this->sql->prepare('SELECT email, username FROM members WHERE id = ?');
			$stmt->execute(array($this->sanitize($_POST['uid'], 'integer')));
			$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt->closeCursor();
			
			if(!empty($user['email'])){
				// Send a notification to the user that their account has been activated.
				require_once(SYSTEM_PATH.'/templates/emails/tmpl_account_activated.php');
				$variables = array('website_name' => $this->config['website_name'],
								   'site_url' => $this->base_url,
								   'username' => $user['username'],
								   'email' => $user['email']
								   );
								   
				$subject = $this->render_email($variables, $email['title']);
				$body = $this->render_email($variables, $email['body']);
				$this->send_mail($user['email'], $subject, $body);
			}
			
			return array('status'=>true, 'msg'=>'');
		}else{
			$err = $stmt->errorInfo();
			return array('status'=>false, 'msg'=>'There was an error while trying to activate the account: '.$err[2]);
		}
	}
}