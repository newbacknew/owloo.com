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

class UserSystem extends Administration{
	public $loggedin = false;
	public $status = false;
	public $uid;
	public $avatar;
	public $queries = 0;
	public $curpage = 'index';
	public $username = '';
	public $permissions = null;
	public $triggers = array();
	
	function __construct(){
		parent::__construct();
		if($this->installed){
			$this->check_status();
			$this->CheckSubscription();
		}
		
		$currentFile = $_SERVER["PHP_SELF"];
		$parts = explode('/', $currentFile);
		if(in_array('forum', $parts)){
			$this->curpage = 'forum';
		}else{
			$this->curpage = addslashes(str_replace('.php', '', $parts[count($parts) - 1]));
		}
	}
	
	## Find the individual users salt ##
	protected function find_salt($username){
		## It could be done a bit differently, but this way is easier to read  as you easily follow every step ##
		$salt = strlen($username);
		$salt = floor($salt/2);
		$salt = strtoupper(substr($username, $salt));
		$salt = $salt.strrev(strtolower($username));
		$salt = md5($salt);
		
		return $salt;
	}
	
	## Check if username exists ##
	public function check_username($user){
		$data = array($user);
		$check = $this->sql->prepare('SELECT id FROM members WHERE username=?');
		$check->execute($data);
		$check->closeCursor();
		$this->queries++;
		
		if($check->rowCount() > 0){
			return false;
		}else{
			return true;
		}		
	}
	## Check if the e-mail is already in use ##
	public function check_email($email){
		$data = array($email);
		$check_m = $this->sql->prepare('SELECT id FROM members WHERE email=?');
		$check_m->execute($data);
		$check_m->closeCursor();
		$this->queries++;
		
		if($check_m->rowCount() > 0){
			return false;
		}else{
			return true;
		}	
	}
	
	## Login ##
	public function process_login($cookie_login = false, $social_signup = false){
		//check if the signup form was submitted.
		$dologin = false;
		$social = false;
		
		if(isset($_POST['login_form']) && $_POST['login_form'] == '' && !$cookie_login){
			sleep(1);
			if(empty($_POST['login_email']) || empty($_POST['login_password'])){
				$status['status'] = false;
				$status['message'] = $this->lang['cl_users_1'];
				echo json_encode($status);
				exit;
			}
			
			//before we continue, we need to make the variables a bit more secure as we will be using them shortly.
			$email = $this->sanitize($_POST['login_email'], 'email');
			//prepare the sql query
			$pro_login = $this->sql->prepare('SELECT 
													m.id,
													m.username,
													m.triggers,
													m.password,
													m.avatar,
													m.account_key,
													m.membergroup,
													m.other_membergroups,
													m.invites,
													m.activation_key,
													mg.permissions
												FROM 
													members as m
												INNER JOIN
													member_groups as mg
													ON
														mg.id = m.membergroup
												WHERE 
													m.username=? 
												OR 
													m.email=?
												LIMIT 
													1');
			$pro_login->execute(array($email, $email));
			$this->queries++;
			
			if($pro_login->rowCount() < 1){
				$status['status'] = false;
				$status['message'] = $this->lang['cl_users_2'];
				echo json_encode($status);
				exit;
			}
			
			while($row = $pro_login->fetchAll(PDO::FETCH_ASSOC)){
				$row = $row[0];
				$acc_id = $row['id'];
				$acc_password = $row['password'];
				$acc_username = $row['username'];
				$acc_triggers = $row['triggers'];
				$acc_avatar = $row['avatar'];
				$acc_key = $row['account_key'];
				$acc_invites = $row['invites'];
				$acc_membrgroup = $row['membergroup'];
				$acc_othergroups = $row['other_membergroups'];
				$acc_permissions = $row['permissions'];
				$acc_activated = $row['activation_key'];
			}
			$pro_login->closeCursor();
			
			$salt = $this->find_salt($acc_username);
			$password = hash_hmac('sha256', $salt.hash_hmac('sha256', $this->config['hashing_salt'].$_POST['login_password'], $this->config['hashing_key']), $this->config['hashing_key']);
			
			if($acc_password !== $password){
				$status['status'] = false;
				$status['message'] = $this->lang['cl_users_3'];
				echo json_encode($status);
				exit;
			}
			
			if(!empty($_POST['login_staylogged']) && $_POST['login_staylogged'] == 'on'){
				$this->setAuthToken($acc_username, $acc_password.$account_key);
			}
			
			$dologin = true;
			
		}else if($cookie_login){
			if(empty($_COOKIE[sha1($_SERVER['REMOTE_ADDR'])])){
				return false;
			}
			$encoded_data = $_COOKIE[sha1($_SERVER['REMOTE_ADDR'])];
			$encoded_data = substr($encoded_data, 0, -64);
			$encoded_data = strrev($encoded_data);
			$encoded_data = base64_decode($encoded_data);
			$data = unserialize($encoded_data);
			
			$username = $this->sanitize(!empty($data['user']) ? $data['user'] : '', 'string');
			$auth_token = !empty($data['auth_token']) ? $data['auth_token'] : '';
			$security_token = !empty($data['secc_token']) ? substr($data['secc_token'], 0, 64) : '';
			$security_token_refined = preg_replace("/[^0-9]/","",!empty($data['secc_token']) ? substr($data['secc_token'], 64, 134) : '');
			
			if(empty($username) || empty($auth_token) || empty($security_token)){
				return false;
			}
			
			//prepare the sql query
			$cook_login = $this->sql->prepare('SELECT 
													m.id,
													m.username,
													m.triggers,
													m.password,
													m.avatar,
													m.account_key,
													m.membergroup,
													m.other_membergroups,
													m.activation_key,
													m.invites,
													mg.permissions
												FROM 
													members as m
												INNER JOIN
													member_groups as mg
													ON
														mg.id = membergroup
												WHERE 
													m.username=? 
												LIMIT 
													1');
			$cook_login->execute(array($username));
			$this->queries++;
			
			if($cook_login->rowCount() < 1){
				return false;
			}
			
			while($row = $cook_login->fetchAll(PDO::FETCH_ASSOC)){
				$row = $row[0];
				$acc_id = $row['id'];
				$acc_password = $row['password'];
				$acc_username = $row['username'];
				$acc_triggers = $row['triggers'];
				$acc_avatar = $row['avatar'];
				$acc_key = $row['account_key'];
				$acc_invites = $row['invites'];
				$acc_membrgroup = $row['membergroup'];
				$acc_othergroups = $row['other_membergroups'];
				$acc_permissions = $row['permissions'];
				$acc_activated = $row['activation_key'];
			}
			$cook_login->closeCursor();
			
			$acc_password = hash_hmac('sha256', $this->config['hashing_salt'].$acc_password.$acc_key.$security_token_refined, $this->config['hashing_key']);
			$security_code = hash_hmac('sha256', $this->config['hashing_salt'].$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].$_SERVER['HTTP_ACCEPT_LANGUAGE'], $this->config['hashing_key']);
			
			if($auth_token != $acc_password || $security_code != $security_token){
				return false;
			}
			
			$dologin = true;
		}else if(!empty($_GET['provider']) || $social_signup){
			// change the following paths if necessary 
			$config = SYSTEM_PATH.'/hybridauth/config.php';
			require_once( SYSTEM_PATH.'/hybridauth/Hybrid/Auth.php' );

			// the selected provider
			$provider_name = $this->sanitize((!empty($_GET['provider']) ? $_GET['provider'] : $social_signup), 'string');

			try{
				// initialize Hybrid_Auth with a given file
				$hybridauth = new Hybrid_Auth( $config );
				
				$adapter = $hybridauth->authenticate( $provider_name );
				
				$user_profile = $adapter->getUserProfile();
				
				//prepare the sql query
				$social_login = $this->sql->prepare('SELECT 
														m.id,
														m.username,
														m.triggers,
														m.password,
														m.avatar,
														m.account_key,
														m.membergroup,
														m.other_membergroups,
														m.activation_key,
														m.invites,
														mg.permissions
													FROM 
														social_connect as sc
													INNER JOIN
														members as m
														ON
															m.id = sc.member_id
													INNER JOIN
														member_groups as mg
														ON
															mg.id = membergroup
													WHERE 
														sc.provider_name = ?
													AND
														sc.provider_uid = ?');
				$social_login->execute(array($provider_name, $user_profile->identifier));
				$this->queries++;
				
				if($social_login->rowCount() > 0){				
					while($row = $social_login->fetchAll(PDO::FETCH_ASSOC)){
						$row = $row[0];
						$acc_id = $row['id'];
						$acc_password = $row['password'];
						$acc_username = $row['username'];
						$acc_triggers = $row['triggers'];
						$acc_avatar = $row['avatar'];
						$acc_key = $row['account_key'];
						$acc_invites = $row['invites'];
						$acc_membrgroup = $row['membergroup'];
						$acc_othergroups = $row['other_membergroups'];
						$acc_permissions = $row['permissions'];
						$acc_activated = $row['activation_key'];
					}
					
					$dologin = true;
					$social = true;
                    
			   }
                else {
                    header('Location: login.php?error=notassociated');
                    exit;
                }
			}
			catch( Exception $e ){
			   $dologin = false;
			}
		}
		
		## If the login was accepted by either cookie or post, then we initiate the session and log them in ##
		if(!empty($dologin) && $dologin){
			if(!empty($acc_activated)){
				$status['status'] = false;
				$status['message'] = ($this->config['admin_activation'] ? $this->lang['cl_users_44'] : $this->lang['cl_users_4']);
				if($cookie_login){
					return false;
				}else{
					if($social){
						header('Location: login.php?error=notactive');
					}else{
						echo json_encode($status);
					}
				}
				exit;
			}
			
			$session_key = hash_hmac('sha256', $acc_id.$this->config['hashing_salt'].session_id().uniqid(), $this->config['hashing_key']);
			$do_login = $this->sql->prepare('UPDATE
													members
												SET 
													session = "'.$session_key.'",
													lastactivity = "'.time().'"
												WHERE
													id = ?;
											'.($this->dbsessions ? '
											UPDATE
												sessions
											SET
												session_key = "'.$session_key.'"
											WHERE
												session_id = ?' : ''));
			
			if($this->dbsessions){
				$data = array($acc_id, session_id());
			}else{
				$data = array($acc_id);
			}
			
			$do_login->execute($data);
			$do_login->closeCursor();
			$this->queries++;
			
			if($do_login->rowCount() < 1){				
				$status['status'] = false;
				$status['message'] = $this->lang['cl_users_5'];
				if($cookie_login || $social){
					return false;
				}else{
					echo json_encode($status);
				}
				exit;
			}else{
				$_SESSION['uid'] = $acc_id;
				$_SESSION['udata'] = array('username'=>$acc_username, 'avatar'=>$acc_avatar, 'membergroup'=>$acc_membrgroup, 'othergroups'=>$acc_othergroups);
				
				if(!$this->dbsessions){
					$_SESSION['session'] = $this->session_encryption($session_key.$_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']);
					$_SESSION['session_expire'] = time();
				}
				
				// Set the triggers.
				if(!empty($acc_triggers)){
					foreach(unserialize($acc_triggers) as $tgr => $val){
						$this->setTrigger($tgr, $val);
					}
				}
				
				$this->permissions = $this->generatePermissions(unserialize($acc_permissions), $acc_othergroups);
				$this->uid = $acc_id;
				$this->invites = $acc_invites;
				$this->avatar = $acc_avatar;
				$this->loggedin = true;
				$this->username = $acc_username;
				
				$status['status'] = true;
				if($cookie_login){
					return true;
				}else{
					if(!empty($_GET['provider'])){
					    if(isset($_SESSION['login_url_origen'])){
                            $url = $_SESSION['login_url_origen'];
                            unset($_SESSION['login_url_origen']);
                            header('Location: '.$url);
                            exit;
                        }
						header("Location: index.php");
					}else{
						echo json_encode($status);
					}
				}
				exit;
			}
		}
	}
	
	protected function generatePermissions($primary, $other_ids = ''){
		$permissions_list = array($primary);
		
		if(!empty($other_ids)){
			$query = '';
			$data = array();
			foreach(explode(',', $other_ids) as $gid){
				$query .= (empty($query) ? '?' : ',?');
				$data[] = $gid;
			}
			
			$stmt = $this->sql->prepare('SELECT 
												permissions
											FROM
												member_groups
											WHERE
												id IN ('.$query.')');
			$stmt->execute($data);
			
			if($stmt->rowCount() > 0){
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$permissions_list[] = unserialize($row['permissions']);
				}
			}
			$stmt->closeCursor();
		}
		
		foreach($permissions_list as $list){
			foreach($list as $key => $val){
				switch($key){
					case 'pm_read':
					case 'pm_write':
					case 'friends_view':
					case 'friends_reqest':
					case 'account_edit':
					case 'account_delete':
					case 'admin':
					case 'invite_send':
					case 'invite_revoke':
						if(!$primary[$key] && $val == true){
							$primary[$key] = true;
						}
						break;
					
					case 'pm_limit':
					case 'viewlevel':
						if($primary[$key] < $val){
							$primary[$key] = $val;
						}
						break;
						
				}
			}
		}
		
		return $primary;
	}
	
	## set the remember me "authtoken" cookie ##
	private function setAuthToken($username, $hashed_password){
		//generate a random string which will be part of the security.
		$random_string = str_shuffle('abcdefghijklmnopqrstuvxyz1234567890ABCDEFGHIJKLMNOPQRSTUVXYZ0987654321');
		$random_string_refined = preg_replace("/[^0-9]/","",$random_string); //then we extract the numbers only, part of security
		
		//the we build the authorisation and security code, from the information we have.
		$auth_code = hash_hmac('sha256', $this->config['hashing_salt'].$hashed_password.$random_string_refined, $this->config['hashing_key']);
		$security_code = hash_hmac('sha256', $this->config['hashing_salt'].$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].$_SERVER['HTTP_ACCEPT_LANGUAGE'], $this->config['hashing_key']).$random_string;
		$data = array('user'=>$username, 
					  'auth_token'=>$auth_code,
					  'secc_token'=>$security_code);
		
		$encoded_data = serialize($data);
		$encoded_data = base64_encode($encoded_data);
		$encoded_data = strrev($encoded_data);
		$encoded_data = $encoded_data.hash_hmac('sha256', $encoded_data, $this->config['hashing_key']);
		
		//we encode all the data, and add it to the cookie, which we create.
		$cookie_name = sha1($_SERVER['REMOTE_ADDR']);
		setcookie($cookie_name, $encoded_data, time()+604800, null, null, null, true); //1 week
		
		return true;
	}
	
	/*** Envío de mensaje privado de bienvenida ***/
	public function sendpm_welcome($data){
		if(is_array($data)){
			$from = $this->sanitize(strip_tags($data['from']), 'integer');
			$to = $this->sanitize(strip_tags($data['to']), 'integer');
			$subject = $this->sanitize(strip_tags($data['subject']), 'string');
			$message = $this->sanitize(strip_tags($data['message']), 'string');
			
			$success = $this->sql->prepare('INSERT INTO
													private_messages
													(to_user, from_user, subject, message, date)
												VALUES
													(?,?,?,?,?)
											');
			
			$data = array($to, $from, $subject, $message, time());
			$success->execute($data);
			$success->closeCursor();
			
			return true;
		}
		return false;
	}
	
	function owloo_ema_register_http_post($url, $params){
	  $postData = '';
	   foreach($params as $k => $v)
	   {
		  $postData .= $k . '='.$v.'&';
	   }
	    @rtrim($postData, '&');
		$ch = curl_init(); 
		@curl_setopt($ch,CURLOPT_URL,$url);
		@curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		@curl_setopt($ch,CURLOPT_HEADER, false);
		@curl_setopt($ch, CURLOPT_POST, count($postData));
		@curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);   
		$output=curl_exec($ch);
		@curl_close($ch); 
	}
	
	## Register ##
	public function process_register(){
		//check if the signup form was submitted.
		if(defined('INSTALLER') || (isset($_POST['signup_form']) && $_POST['signup_form'] == '') || !empty($_GET['provider'])){	
			
			$social = false;
			if(!empty($_GET['provider'])){
				$social = true;
			}
			
			//check if they agreed with the terms
			if($this->config['signup_disabled']){
				$status['status'] = false;
				$status['message'] = $this->lang['cl_users_8'];
				echo json_encode($status);
				exit;
			}
			
			if(!$social){
				if($this->config['reCAPTCHA_enabled']){
					$status['reload'] = true;
				}else{
					$status['reload'] = false;
				}
				
				//Check if the reCaptcha was approved
				if(!defined('INSTALLER') && !$this->captcha_success){
					$status['status'] = false;
					$status['message'] = $this->lang['cl_users_6'];
					echo json_encode($status);
					exit;
				}
				//check if any of the required fields are empty
				if(empty($_POST['signup_email']) || empty($_POST['signup_username']) || empty($_POST['signup_password']) || empty($_POST['signup_password2'])){
					$status['status'] = false;
					$status['message'] = $this->lang['cl_users_7'];
					echo json_encode($status);
					exit;
				}
				//check if they agreed with the terms
				if(!defined('INSTALLER') && $this->config['termsrequired'] && $_POST['signup_iagree'] != 'on'){
					$status['status'] = false;
					$status['message'] = $this->lang['cl_users_8'];
					echo json_encode($status);
					exit;
				}
				//check if the e-mail is an actual e-mail
				if(!$this->validateEmail($_POST['signup_email'])){
					$status['status'] = false;
					$status['message'] = $this->lang['cl_users_9'];
					echo json_encode($status);
					exit;
				}
				//check if an account is already registered with that e-mail
				if(!$this->check_email($this->sanitize($_POST['signup_email'], 'email'))){
					$status['status'] = false;
					$status['message'] = $this->lang['cl_users_10'];
					echo json_encode($status);
					exit;
				}
				//check if the two passwords match
				if(!defined('INSTALLER') && strlen($_POST['signup_username']) < 3){
					$status['status'] = false;
					$status['message'] = $this->lang['cl_users_11'];
					echo json_encode($status);
					exit;
				}
				//check if the username contains special characters.
				if(!preg_match('/^[a-zA-Z0-9_-]{3,}$/', $this->sanitize($_POST['signup_username'], 'string'))){
					$status['status'] = false;
					$status['message'] = $this->lang['cl_users_12'].' 3 '.$this->lang['cl_users_13'];
					echo json_encode($status);
					exit;
				}
				//check if an account is already registered with that username
				if(!$this->check_username($this->sanitize($_POST['signup_username'], 'string'))){
					$status['status'] = false;
					$status['message'] = $this->lang['cl_users_14'];
					echo json_encode($status);
					exit;
				}
				//check if the two passwords match
				if($_POST['signup_password'] !== $_POST['signup_password2']){
					$status['status'] = false;
					$status['message'] = $this->lang['cl_users_15'];
					echo json_encode($status);
					exit;
				}
				//Make sure the username is not precent in the password
				if(strpos($_POST['signup_password'], $_POST['signup_username']) !== false){
					$status['status'] = false;
					$status['message'] = $this->lang['cl_users_16'];
					echo json_encode($status);
					exit;
				}
				
				// Check if the invitation code is a valid format
				if(!defined('INSTALLER') && !$this->check_invite((!empty($_POST['signup_invitecode']) ? $_POST['signup_invitecode'] : ''))){
					$status['status'] = false;
					$status['message'] = $this->lang['cl_invites_0'];
					echo json_encode($status);
					exit;
				}
				
				// check for required custom profile fields.
				if(!defined('INSTALLER') && !empty($_POST['p_field'])){
					$pf_query = '';
					$pf_data = array($this->sanitize($this->uid, 'integer'));
					$this->fetchAllFields();
					
					foreach($this->p_fields as $id => $field){
						//check if the any required fields are not missing/left empty
						if(empty($_POST['p_field'][$id]) && $field['p_signup'] == 1){
							$status['status'] = false;
							$status['message'] = $this->lang['cl_users_17'];
							echo json_encode($status);
							exit;
						}
					}
				}
				
				//before we continue, we need to make the variables a bit more secure as we will be using them shortly.
				$email = $this->sanitize($_POST['signup_email'], 'email');
				$username = $this->sanitize(strip_tags(addslashes($_POST['signup_username'])), 'string');
				$salt = $this->find_salt($username);
				$password = hash_hmac('sha256', $salt.hash_hmac('sha256', $this->config['hashing_salt'].$_POST['signup_password'], $this->config['hashing_key']), $this->config['hashing_key']);
			}
			
			if(!defined('INSTALLER')){
				// get the default user group
				$stmt = $this->sql->query('SELECT id FROM member_groups WHERE default_group = 1 LIMIT 1');
				$group = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$group = $group[0]['id'];
			}else{
				$group = 3;
			}
			
			// fetch the users's details from their social network.
			if($social){
				$config = SYSTEM_PATH.'/hybridauth/config.php';
				require_once( SYSTEM_PATH.'/hybridauth/Hybrid/Auth.php' );

				// the selected provider
				$provider_name = $this->sanitize($_GET['provider'], 'string');

				try{
					// initialize Hybrid_Auth with a given file
					$hybridauth = new Hybrid_Auth( $config );

					// try to authenticate with the selected provider
					$adapter = $hybridauth->authenticate( $provider_name );
					$user_profile = $adapter->getUserProfile();
                    
                    $social_link_data = array('provider'=>$provider_name, 'uid'=> $user_profile->identifier);
					
					//before we continue, we need to make the variables a bit more secure as we will be using them shortly.
					$email = $this->sanitize($user_profile->email, 'email');
					
					/*if(!$this->validateEmail($email)){
						header('Location: signup.php?error=noemail');
						exit;
					}*/
					
					if(!$this->validateEmail($email)){
                        $triggers = serialize(array('newusername' => true, 'newpassword' => true, 'newemail' => true));
                    }else{
                        //check if an account is already registered with that e-mail
                        if(!$this->check_email($email)){
                            header('Location: signup.php?error=emailinuse');
                            exit;
                        }
                        
                        //check if an account is already registered with that e-mail
                        if(!$this->socialAuthIsLinked($user_profile->identifier)){
                            header('Location: signup.php?error=sninuse');
                            exit;
                        }
                        $triggers = serialize(array('newusername' => true, 'newpassword' => true));
                    }
					
					
					
					$username = uniqid($provider_name.'_');
					$salt = $this->find_salt($username);
					$password = hash_hmac('sha256', $salt.hash_hmac('sha256', $this->config['hashing_salt'].substr(str_shuffle('abcdefghijklmnopqrstuvxyz1234567890ABCDEFGHIJKLMNOPQRSTUVXYZ0987654321'),0,22), $this->config['hashing_key']), $this->config['hashing_key']);
				}
				catch( Exception $e ){
					return false;
				}
			}
			
			if(empty($triggers)){
				$triggers = '';
			}
			
			$activation_key = ((!$social || $this->config['admin_activation']) && !defined('INSTALLER') && ($this->config['emailactivation'] || $this->config['admin_activation']) ? substr(str_shuffle('abcdefghijklmnopqrstuvxyz1234567890ABCDEFGHIJKLMNOPQRSTUVXYZ0987654321'),0,22) : '');
			$admin = (defined('INSTALLER') ? 1 : 0);
			
            if(!empty($user_profile->photoURL)){
                //prepare the sql query
            $data = array($username, $triggers, $password, $user_profile->photoURL, $email, time(), $group, substr(str_shuffle('abcdefghijklmnopqrstuvxyz1234567890ABCDEFGHIJKLMNOPQRSTUVXYZ0987654321'),0,8), $activation_key, $admin);
            $pro_reg = $this->sql->prepare('INSERT INTO members(username,triggers, password, avatar, email,date_registered, membergroup, account_key, activation_key, firstadmin) VALUES(?,?,?,?,?,?,?,?,?,?)');
            }
            else {
                //prepare the sql query
                $data = array($username, $triggers, $password, $email, time(), $group, substr(str_shuffle('abcdefghijklmnopqrstuvxyz1234567890ABCDEFGHIJKLMNOPQRSTUVXYZ0987654321'),0,8), $activation_key, $admin);
                $pro_reg = $this->sql->prepare('INSERT INTO members(username,triggers, password,email,date_registered, membergroup, account_key, activation_key, firstadmin) VALUES(?,?,?,?,?,?,?,?,?)');
            }
			$pro_reg->execute($data);
			$pro_reg->closeCursor();
			$this->queries++;
			
			if($pro_reg->rowCount() < 1){
				if(!$social){
					$status['status'] = false;
					$status['message'] = $this->lang['cl_users_18'];
					echo json_encode($status);
					exit;
				}else{
					header('Location: signup.php?error=regerr');
					exit;
				}
			}else{
				$new_id = $this->sql->lastInsertId();
				
				if(!$social){
					//Accept the invite or delete the account if the invite is invalid.
					if(!defined('INSTALLER') && !$this->accept_invite($new_id, (!empty($_POST['signup_invitecode']) ? $_POST['signup_invitecode'] : ''))){
						$status['status'] = false;
						$status['message'] = $this->lang['cl_invites_1'];
						echo json_encode($status);
						exit;
					}
					
					// Add custom profile fields to the newly created account
					if(!empty($_POST['p_field'])){
						$pf_query = '';
						$pf_data = array();
						$this->fetchAllFields();
						
						$_aux_user_name = '';
						$_aux_user_name_first = true;
						
						foreach($this->p_fields as $id => $field){
							$pf_query .= (!empty($pf_query) ? ',' : '').'(?,?,?)';
							$pf_data[] .= $new_id;
							$pf_data[] .= $this->sanitize($id, 'integer');
							$pf_data[] .= $this->sanitize(strip_tags(addslashes(!empty($_POST['p_field'][$id]) ? $_POST['p_field'][$id] : '')), 'string');
							if($_aux_user_name_first){
								$_aux_user_name = $_POST['p_field'][$id];
								$_aux_user_name_first = false;
							}
						}
						
						/*** Register into EMA ***/
						if(!empty($email) && !empty($_aux_user_name)){
							$params = array(
							   "EMAIL" => $email,
							   "AGE" => "0",
							   "FNAME" => $_aux_user_name,
							   "LNAME" => "",
							   'ADD_OWLOO_LIST' => 'ow#gfdg89-Hf56'
							);
							 
							$this->owloo_ema_register_http_post("http://latamclick.net/ema/lists/ga908fyyyj5f8/subscribe",$params);
						}
						/*** END - Register into EMA ***/
						
						if(!empty($pf_query)){
							$stmt = $this->sql->prepare('INSERT INTO profiles VALUES '.$pf_query);
							$stmt->execute($pf_data);
							$stmt->closeCursor();
							$this->queries++;
						}
					}
					//end of custom profile fields
				}else{
				    //Guardamos el nombre del usuario
				    $pf_data = array($new_id, 1, $user_profile->displayName);
                    $stmt = $this->sql->prepare('INSERT INTO profiles(u_id, f_id, f_val) VALUES(?,?,?)');
                    $stmt->execute($pf_data);
                    $stmt->closeCursor();
					
					/*** Register into EMA ***/
					if(!empty($email) && !empty($user_profile->displayName)){
						$params = array(
						   "EMAIL" => $email,
						   "AGE" => "0",
						   "FNAME" => $user_profile->displayName,
						   "LNAME" => "",
                           'ADD_OWLOO_LIST' => 'ow#gfdg89-Hf56'
						);
						 
						$this->owloo_ema_register_http_post("http://latamclick.net/ema/lists/ga908fyyyj5f8/subscribe",$params);
					}
					/*** FIN - Register into EMA ***/
                    
					$social_login = $this->sql->prepare('INSERT INTO
																social_connect
															VALUES
																(?,?,?,?)');
					$social_login->execute(array($new_id, $provider_name, $user_profile->identifier, $new_id.$provider_name));
					$social_login->closeCursor();
					$this->queries++;
				}
				
				if(!$social && !defined('INSTALLER') && $this->config['emailactivation']){
					$variables = array('website_name' => $this->config['website_name'],
									   'username' => $username,
									   'email' => $email,
									   'password' => $this->sanitize($_POST['signup_password'], 'string'),
									   'activationcode' => $activation_key,
									   'visitor_ip' => $_SERVER['REMOTE_ADDR'],
									   'site_url' => $this->base_url);

					$this->send_activation_mail($variables);
					
					$status['message'] = $this->lang['cl_users_19'];
				
				}else if(!$social && !$this->config['emailactivation'] && $this->config['admin_activation']){
					$status['message'] = $this->lang['cl_users_45'];
					
				}else if(!$social && !$this->config['emailactivation'] && !$this->config['admin_activation']){
					$status['message'] = $this->lang['cl_users_19a'];
					
				}else if(!$social){
					return array('status'=> true);
					exit;
				}
				
				if(!$social){
					$status['status'] = true;
					
					if(!$this->config['emailactivation']){
						$status['status'] = 'activado';
						/************* Envío de confirmación de registro **************/
							$variables = array('website_name' => $this->config['website_name'],
										   'username' => $username,
										   'email' => $email,
										   'password' => $this->sanitize($_POST['signup_password'], 'string'),
										   'activationcode' => $activation_key,
										   'visitor_ip' => $_SERVER['REMOTE_ADDR'],
										   'site_url' => $this->base_url);
	
						$this->send_activated_mail($variables);
						/**************************************************************/
						
						/************* Envío de un mensaje privado de bienvenida *************/
							$asunto = 'Gracias por registrarte a owloo';
							$mensaje = "Hola! Gracias por registrarte a owloo, la plataforma en español más completa para el analisis de Facebook. Para recibir ayuda puedes enviarnos un mensaje las 24 horas. Próximamente te estaremos notificando muchas novedades que serán de tu agrado.\n\nSaludos,\nowloo team";
	
							$send = array('from'=>1, 'to'=>$new_id, 'subject'=>urldecode($asunto), 'message'=>$mensaje);
							$estado = $this->sendpm_welcome($send);
						/*********************************************************************/
						$status['message'] = $username; 
					}
					echo json_encode($status);
				}else{
					if(!$this->config['admin_activation']){
						$this->process_login(null, true);
					}else{
						header('Location: signup.php?error=adminact');
						exit;
					}
				}
				exit;
			}
		}
	}
	
	public function validateEmail($email){
		return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", strtolower(trim(addslashes($email))));
	}
	
	public function activateAccount(){
		if(!empty($_GET['activate']) && strlen($_GET['activate']) == 22){
			sleep(1);
			$activate = $this->sql->prepare('UPDATE members SET activation_key = "" WHERE activation_key = ?');
			$activate->execute(array($this->sanitize($_GET['activate'], 'string')));
			
			if($activate->rowCount() > 0){
				echo '<div class="alert alert-success">
						<strong>'.$this->lang['cl_users_20'].'</strong> '.$this->lang['cl_users_21'].'
					</div>';
			}else{
				echo '<div class="alert alert-error">
						<strong>'.$this->lang['cl_users_22'].'</strong> '.$this->lang['cl_users_23'].'
					</div>';
			}
		}
		else if(!empty($_GET['active'])){
			sleep(1);
			echo '<div class="alert alert-success">
					'.$this->lang['cl_users_21'].'
				</div>';
		}
	}
	
	## Update Account Settings ##
	public function process_settings(){
		//check if the signup form was submitted.
		if(isset($_POST['upd_form']) && $_POST['upd_form'] == ''){
			if(!$this->loggedin){
				exit;
			}
			if($this->permissions['account_edit']){
				//prepare the sql query
				$pro_set = $this->sql->prepare('SELECT username, email, password FROM members WHERE id = ?');
				$pro_set->execute(array($this->uid));
				$this->queries++;
				
				while($row = $pro_set->fetchAll(PDO::FETCH_ASSOC)){
					$row = $row[0];
					$acc_username = $row['username'];
					$acc_email = $row['email'];
					$acc_password = $row['password'];
				}
				$pro_set->closeCursor();
				
				//Check the users password is correct (saftynet)
				if(empty($_POST['cur_password']) || hash_hmac('sha256', $this->find_salt($acc_username).hash_hmac('sha256', $this->config['hashing_salt'].$_POST['cur_password'], $this->config['hashing_key']), $this->config['hashing_key']) != $acc_password){
					$status['status'] = false;
					$status['message'] = $this->lang['cl_users_24'];
					return $status;
					exit;
				}
				//check if the e-mail is an actual e-mail
				if(!$this->validateEmail($_POST['upd_email'])){
					$status['status'] = false;
					$status['message'] = $this->lang['cl_users_25'];
					return $status;
					exit;
				}
				//check if an account is already registered with that e-mail
				if(($_POST['upd_email'] != $acc_email) && !$this->check_email($this->sanitize($_POST['upd_email'], 'email'))){
					$status['status'] = false;
					$status['message'] = $this->lang['cl_users_26'];
					return $status;
					exit;
				}
				//check if the two passwords match
				if(!empty($_POST['upd_password']) && $_POST['upd_password'] !== $_POST['upd_password2']){
					$status['status'] = false;
					$status['message'] = $this->lang['cl_users_27'];
					return $status;
					exit;
				}
				//Make sure the username is not precent in the password
				if(!empty($_POST['upd_password']) && strpos($_POST['upd_password'], $acc_username) !== false){
					$status['status'] = false;
					$status['message'] = $this->lang['cl_users_28'];
					return $status;
					exit;
				}
				
				if(!empty($_FILES['avartar']['tmp_name'])){
					$this->process_uploadavatar();
				}
				
				//before we continue, we need to make the variables a bit more secure as we will be using them shortly.
				$email = $this->sanitize($_POST['upd_email'], 'email');
				
				## Do they went to change password? ##
				if(!empty($_POST['upd_password'])){
					$password = hash_hmac('sha256', $this->find_salt($acc_username).hash_hmac('sha256', $this->config['hashing_salt'].$_POST['upd_password'], $this->config['hashing_key']), $this->config['hashing_key']);
				}else{
					$password = $acc_password;
				}
				
				## Or reset their account key? ##
				if(isset($_POST['reset_acc_key']) && $_POST['reset_acc_key'] == 'on'){
					$reset_key = substr(str_shuffle('abcdefghijklmnopqrstuvxyz1234567890ABCDEFGHIJKLMNOPQRSTUVXYZ0987654321'),0,8);
				}else{
					$reset_key = '';
				}
				
				//prepare the sql query
				$pro_set2 = $this->sql->prepare('UPDATE 
														members 
													SET 
														password=?, 
														email=?,
														membergroup=?,
														other_membergroups=?
														'.(!empty($fileupload_name) ? ',avatar=?' : '').'
														'.(!empty($reset_key) ? ',account_key=?' : '').'
													WHERE 
														id=?');
				$data = array($password, $email);
				
				// user groups
				$data[] = $this->sanitize($_POST['primary_group'], 'integer');
				
				$groups = explode(',', $_SESSION['udata']['othergroups']);
				$groups[] = $_SESSION['udata']['membergroup'];
				
				$grps = '';
				foreach($groups as $grp){
					if($grp != $_POST['primary_group']){
						$grps .= (empty($grps) ? '' : ',').$grp;
					}
				}
				$data[] = $grps;
				
				if(!empty($fileupload_name)){
					$data[] .= $fileupload_name;
				}
				if(!empty($reset_key)){
					$data[] .= $reset_key;
				}
				$data[] .= $this->uid;
				
				$pro_set2->execute($data);
				$pro_set2->closeCursor();
				$this->queries++;
				
				$status['status'] = true;
				$status['message'] = $this->lang['cl_users_29'];
				return $status;
				exit;
				
			}else{
				$status['status'] = false;
				$status['message'] = $this->lang['cl_users_30'];
				return $status;
				exit;
			}
		}
	}
	
	## Update Account Settings ##
	public function admin_process_settings(){
		//check if the signup form was submitted.
		if(isset($_POST['upd_form']) && $_POST['upd_form'] == '' && !empty($_POST['userid'])){
		
			if(!$this->loggedin || empty($this->permissions['admin']) || !$this->permissions['admin']){
				header("Location: ".$this->base_url);
				exit;
			}
			
			$_POST['userid'] = $this->sanitize($_POST['userid'],'integer');
			
			//prepare the sql query
			$pro_set = $this->sql->prepare('SELECT username, email, password, (SELECT m2.password FROM members as m2 WHERE id=?) as adminpw FROM members WHERE id = ?');
			$pro_set->execute(array($this->uid, $_POST['userid']));
			$this->queries++;
			
			while($row = $pro_set->fetchAll(PDO::FETCH_ASSOC)){
				$row = $row[0];
				$acc_username = $row['username'];
				$acc_email = $row['email'];
				$acc_password = $row['password'];
				$admin_password = $row['adminpw'];
			}
			$pro_set->closeCursor();
			
			//Check the users password is correct
			if(empty($_POST['cur_password']) || hash_hmac('sha256', $this->find_salt($_SESSION['udata']['username']).hash_hmac('sha256', $this->config['hashing_salt'].$_POST['cur_password'], $this->config['hashing_key']), $this->config['hashing_key']) != $admin_password){
				$status['status'] = false;
				$status['message'] = $this->lang['cl_users_24'];
				return $status;
				exit;
			}
			//check if the e-mail is an actual e-mail
			if(!$this->validateEmail($_POST['upd_email'])){
				$status['status'] = false;
				$status['message'] = $this->lang['cl_users_25'];
				return $status;
				exit;
			}
			//check if an account is already registered with that e-mail
			if(($_POST['upd_email'] != $acc_email) && !$this->check_email($this->sanitize($_POST['upd_email'], 'email'))){
				$status['status'] = false;
				$status['message'] = $this->lang['cl_users_26'];
				return $status;
				exit;
			}
			//check if the two passwords match
			if(!empty($_POST['upd_password']) && $_POST['upd_password'] !== $_POST['upd_password2']){
				$status['status'] = false;
				$status['message'] = $this->lang['cl_users_27'];
				return $status;
				exit;
			}
			//Make sure the username is not precent in the password
			if(!empty($_POST['upd_password']) && strpos($_POST['upd_password'], $acc_username) !== false){
				$status['status'] = false;
				$status['message'] = $this->lang['cl_users_28'];
				return $status;
				exit;
			}
			
			if(!empty($_FILES['avartar']['tmp_name'])){
				$this->process_uploadavatar(null, true);
			}
			
			//before we continue, we need to make the variables a bit more secure as we will be using them shortly.
			$email = $this->sanitize($_POST['upd_email'], 'email');
			
			## Do they went to change password? ##
			if(!empty($_POST['upd_password'])){
				$password = hash_hmac('sha256', $this->find_salt($acc_username).hash_hmac('sha256', $this->config['hashing_salt'].$_POST['upd_password'], $this->config['hashing_key']), $this->config['hashing_key']);
			}else{
				$password = $acc_password;
			}
			
			## Or reset their account key? ##
			if(isset($_POST['reset_acc_key']) && $_POST['reset_acc_key'] == 'on'){
				$reset_key = substr(str_shuffle('abcdefghijklmnopqrstuvxyz1234567890ABCDEFGHIJKLMNOPQRSTUVXYZ0987654321'),0,8);
			}else{
				$reset_key = '';
			}
			
			//prepare the sql query
			$pro_set2 = $this->sql->prepare('UPDATE 
													members 
												SET 
													password=?, 
													email=?,
													membergroup=?,
													other_membergroups=?
													'.(!empty($fileupload_name) ? ',avatar=?' : '').'
													'.(!empty($reset_key) ? ',account_key=?' : '').'
												WHERE 
													id=?');
			$data = array($password, $email);
			
			// user groups
			$data[] = $this->sanitize($_POST['primary_group'], 'integer');
			
			if(!empty($_POST['additional_groups'])){
				$data[] = $this->sanitize(implode(',', $_POST['additional_groups']), 'mixedint');
			}else{
				$data[] = '';
			}
			
			if(!empty($fileupload_name)){
				$data[] .= $fileupload_name;
			}
			if(!empty($reset_key)){
				$data[] .= $reset_key;
			}
			$data[] .= $_POST['userid'];
			
			$pro_set2->execute($data);
			$pro_set2->closeCursor();
			$this->queries++;
			
			$status['status'] = true;
			$status['message'] = $this->lang['cl_users_29'];
			return $status;
			exit;
		}
	}
	
	## This will check if the session if bound to the currently logged in user ##
	public function check_status(){
		if(!empty($_SESSION['uid']) && !empty($_SESSION['udata'])){
			$usrData = $this->check_session($_SESSION['uid']);
			
			if(empty($usrData)){
				$this->loggedin = false;
				return false;
			}
			
			// Update their last activity if set in the config
			if($this->config['last_activity']){
				$stmt = $this->sql->prepare('UPDATE members SET lastactivity = ? WHERE id = ?');
				$stmt->execute(array(time(),$_SESSION['uid']));
				$stmt->closeCursor();
				$this->queries++;
			}
			
			// Set the triggers.
			if(!empty($usrData['triggers'])){
				foreach(unserialize($usrData['triggers']) as $tgr => $val){
					$this->setTrigger($tgr, $val);
				}
			}
			
			$_SESSION['udata']['avatar'] = $usrData['avatar'];
			$_SESSION['udata']['membergroup'] = $usrData['membergroup'];
			$_SESSION['udata']['othergroups'] = $usrData['other_membergroups'];
			$this->invites = $usrData['invites'];
			$this->permissions = $this->generatePermissions(unserialize($usrData['permissions']), $usrData['other_membergroups']);
			$this->uid = intval($_SESSION['uid']);
			$this->avatar = $usrData['avatar'];
			$this->loggedin = true;
			$this->username = $usrData['username'];
			return true;
		}else{
			## Check if the user is logged in with Cookie/remember me instead ##
			if(!empty($_COOKIE[sha1($_SERVER['REMOTE_ADDR'])])){
				if($this->process_login(true)){
					return true;
				}else{
					setcookie(sha1($_SERVER['REMOTE_ADDR']), '', time()-604800); //delete cookie
					$this->loggedin = false;
					return false;
				}				
			}else{
				$this->loggedin = false;
				return false;
			}
		}
	}
	
	protected function send_activation_mail(array $variables=array()){
		require_once(SYSTEM_PATH.'/templates/emails/tmpl_account_activation.php');
								   
		$subject = $this->render_email($variables, $email['title']);
		$body = $this->render_email($variables, $email['body']);
		$this->send_mail($variables['email'], $subject, $body);
		return true;
	}
	
	protected function send_activated_mail(array $variables=array()){
		require_once(SYSTEM_PATH.'/templates/emails/tmpl_account_activated.php');
								   
		$subject = $this->render_email($variables, $email['title']);
		$body = $this->render_email($variables, $email['body']);
		$this->send_mail($variables['email'], $subject, $body);
		return true;
	}
	
	public function process_sendResetPassword(){
		if(isset($_POST['resetpw_form']) && $_POST['resetpw_form'] == ''){
			sleep(1);
			//Check if the reCaptcha was approved
			if(!$this->captcha_success){
				$status['status'] = false;
				$status['msgtype'] = 'error';
				$status['message'] = $this->lang['cl_users_31'];
				return $status;
				exit;
			}
			
			$send_rspw = $this->sql->prepare('SELECT username, email, reset_timer FROM members WHERE username = ? LIMIT 1');
			
			$send_rspw->execute(array($this->sanitize($_POST['reset_email'], 'string')));
			$this->queries++;
			
			if($send_rspw->rowCount() < 1){
				$status['status'] = false;
				$status['msgtype'] = 'error';
				$status['message'] = $this->lang['cl_users_32'];
				return $status;
				exit;
			}
			
			while($row = $send_rspw->fetchAll(PDO::FETCH_ASSOC)){
				$row = $row[0];
				$acc_username = $row['username'];
				$acc_email = $row['email'];
				$reset_timer = $row['reset_timer'];
			}
			$send_rspw->closeCursor();
			
			if((time() - $reset_timer) < 7200){
				$status['status'] = true;
				$status['msgtype'] = 'error';
				$status['message'] = $this->lang['cl_users_33'];
				return $status;
				exit;
			}
			
			$resetcode = hash_hmac('sha1', $acc_username.uniqid(), $this->config['hashing_key']);
			
			$send_rspw2 = $this->sql->prepare('UPDATE members SET reset_key=?, reset_timer=? WHERE username=?');
			$send_rspw2->execute(array($resetcode,time(),$acc_username));
			$send_rspw2->closeCursor();
			$this->queries++;
			
			require_once(SYSTEM_PATH.'/templates/emails/tmpl_confirm_pw_reset.php');
			$variables = array('website_name' => $this->config['website_name'],
							   'site_url' => $this->base_url,
							   'username' => $acc_username,
							   'email' => $acc_email,
							   'resetcode' => $resetcode,
							   'visitor_ip' => $_SERVER['REMOTE_ADDR']
							   );
			
			$subject = $this->render_email($variables, $email['title']);
			$body = $this->render_email($variables, $email['body']);
					
			$this->send_mail($acc_email, $subject, $body);
			
			$status['status'] = true;
			$status['msgtype'] = 'success';
			$status['message'] = $this->lang['cl_users_34'];
			return $status;
			exit;
		}
	}
	
	public function process_resetNewPassword(){
		if(!empty($_GET['reset']) && strlen($_GET['reset']) == 40){
			sleep(1);
			$key = $this->sanitize($_GET['reset'], 'string');
			
			$reset = $this->sql->prepare('SELECT username, email, reset_timer FROM members WHERE reset_key = ? LIMIT 1');
			$reset->execute(array($_GET['reset']));
			$this->queries++;
			
			if($reset->rowCount() > 0){
				$user =	$reset->fetchAll(PDO::FETCH_ASSOC);
				$user = $user[0];
				
				if((time() - $user['reset_timer']) > 7200){
					return '<div class="alert alert-warning">
								<strong>'.$this->lang['cl_users_35'].'</strong> '.$this->lang['cl_users_36'].'
							</div>';
					exit;
				}
				
				$password_readable = substr(str_shuffle('abcdefghijklmnopqrstuvxyz1234567890ABCDEFGHIJKLMNOPQRSTUVXYZ0987654321'),0,12);
				$password_hashed = hash_hmac('sha256', $this->find_salt($user['username']).hash_hmac('sha256', $this->config['hashing_salt'].$password_readable, $this->config['hashing_key']), $this->config['hashing_key']);
				
				$newpw = $this->sql->prepare('UPDATE members SET password=?, reset_key="", reset_timer="" WHERE reset_key=? LIMIT 1');
				$newpw->execute(array($password_hashed,$key));
				$this->queries++;
				
				require_once(SYSTEM_PATH.'/templates/emails/tmpl_new_password.php');
				$variables = array('website_name' => $this->config['website_name'],
								   'site_url' => $this->base_url,
								   'username' => $user['username'],
								   'email' => $user['email'],
								   'newpassword' => $password_readable,
								   'visitor_ip' => $_SERVER['REMOTE_ADDR']
								   );
				
				$subject = $this->render_email($variables, $email['title']);
				$body = $this->render_email($variables, $email['body']);
						
				$this->send_mail($user['email'], $subject, $body);
				
				return '<div class="alert alert-success">
							<strong>'.$this->lang['cl_users_37'].'</strong> '.$this->lang['cl_users_38'].'
						</div>';
			}else{
				return '<div class="alert alert-error">
							<strong>'.$this->lang['cl_users_39'].'</strong> '.$this->lang['cl_users_40'].'
						</div>';
			}
			
			return '';
		}
	}
	
	## logout ##
	public function process_logout(){
		if(!empty($_POST['logout'])){
			//sleep(1);
			if(!$this->csrfCheck('logout')){
				$status['status'] = false;
			}else{
				$this->destroy();
				setcookie(sha1($_SERVER['REMOTE_ADDR']), '', time()-604800, null, null, null, true); //1 week
				$status['status'] = true;
			}
			echo json_encode($status);
			exit();
		}
	}
	
	## Get config value ##
	public function config($value){
		//The reason for this, is because we do not want anyone to retrive sensitive information like databse information outside the system.
		switch($value){
			case 'sql_user':
			case 'sql_pass':
			case 'sql_db':
			case 'hashing_salt':
			case 'hashing_key':
			case 'smtp_host':
			case 'smtp_user':
			case 'smtp_pass':
			case 'smtp_pass':
			case 'reCAPTCHA_privatekey':
			case 'pp_api_user':
			case 'pp_api_pass':
			case 'pp_api_sig':
				return '';
				break;
				
			default:
				return $this->config[$value];
				break;
		}
	}
	
	public function get_uid($info, $type){
		$info = ($type == 'username' ? $this->sanitize($info, 'string') : $this->sanitize($info, 'email'));
		$stmt = $this->sql->prepare('SELECT id FROM members WHERE username = ? OR email = ?');
		$stmt->execute(array($info,$info));
		$this->queries++;
		
		if($stmt->rowCount() > 0){
			while($row = $stmt->fetchAll(PDO::FETCH_ASSOC)){
				$id = $row[0]['id'];
			}
			$stmt->closeCursor();
			return $id;
		}else{
			return 0;
		}
	}
	
	//Account Check
	public function process_checkaccount(){
		if($this->installed){
			$stmt = $this->sql->prepare('SELECT
											(SELECT 
													COUNT(f.friend_id) 
												FROM
													friends as f 
												INNER JOIN 
													members as m 
													ON 
														m.id = f.friend_id 
												LEFT JOIN 
													sessions as s 
													ON 
														s.session_key = m.session
												WHERE
													f.user_id = 7
												AND
													f.status = "accepted"
												AND
													s.session_expire > 1340661456
											) as friends_count,
											
											(SELECT 
													COUNT(pm_id) 
												FROM 
													private_messages 
												WHERE 
													to_user = u.id 
												AND 
													status = "unread"
											) as pm_count,
											
											(SELECT 
													COUNT(fl.f_id) 
												FROM 
													friends as fl
												WHERE 
													fl.friend_id = u.id 
												AND 
													fl.status = "pending"
											) as f_requests
											
										FROM
											members as u
										WHERE
											u.id = ?
									');
									
			$stmt->execute(array($this->uid));
			$this->queries++;
			if($stmt->rowCount() > 0){
				while($row = $stmt->fetchAll(PDO::FETCH_ASSOC)){
					$reply['online_friends'] = $row[0]['friends_count'];
					$reply['friend_requests'] = $row[0]['f_requests'];
					$reply['unread_pms'] = $row[0]['pm_count'];
				}
				
				$reply['status'] = true;
				
				$stmt->closeCursor();
			}else{
				$reply['status'] = false;
			}
			return $reply;
		}
		
		return $reply['status'] = false;
	}
	
	## 	  Mailing System 	 ##
	protected function send_mail($to, $subject, $body){
		if(strtolower($this->config['mailer_type']) == 'smtp' && !empty($this->config['smtp_host']) && !empty($this->config['smtp_port']) && !empty($this->config['smtp_user']) && !empty($this->config['smtp_pass'])){
			
            require_once('phpmailer/class.phpmailer.php');
            
            //SMTP Settings
            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->SMTPAuth   = true; 
            $mail->SMTPSecure = "tls"; 
            $mail->Host       = $this->config['smtp_host'];
            $mail->Username   = $this->config['smtp_user'];
            $mail->Password   = $this->config['smtp_pass'];//
            
            $mail->SetFrom($this->config['php_from'], 'Owloo'); //from (verified email address)
            $mail->Subject = $subject; //subject
            
            //message
            $mail->MsgHTML($body);
            //
            
            //recipient
            $mail->AddAddress($to); 
            
            
            $_result = $mail->Send();
            
			if(!$_result){
				$this->errorlog->logError(null, print_r($mail->ErrorInfo, true));
				return false;
			}else{
				return true;
			}
		}else{
			$headers = 'MIME-Version: 1.0'."\r\n".
					  'Content-type: text/html; charset=utf8'."\r\n".
					  'From: Owloo <'.$this->config['php_from'].">\r\n";

			if(!mail($to, '=?UTF-8?B?'.base64_encode($subject).'?=', $body, $headers)){
				return false;
			}else{
				return true;
			}
		}
	}
	
	protected function render_email(array $values = array(), $text){
		//run through the body and replace any keys with the data from the array
		foreach($values as $key => $txt){
			$text = preg_replace('#\{{'.$key.'}}#s', $txt, $text);
		}
		
		return $text;
	}
	
	public function socialAuthLink(){
		if(!empty($_GET['link'])){
			// change the following paths if necessary 
			$config = SYSTEM_PATH.'/hybridauth/config.php';
			require_once( SYSTEM_PATH.'/hybridauth/Hybrid/Auth.php' );

			// the selected provider
			$provider_name = $this->sanitize($_GET['link'], 'string');

			try{
				// initialize Hybrid_Auth with a given file
				$hybridauth = new Hybrid_Auth( $config );

				// try to authenticate with the selected provider
				$adapter = $hybridauth->authenticate( $provider_name );
				$user_profile = $adapter->getUserProfile();
                
                if(!empty($user_profile->photoURL)){
                    //Guardamos la foto del usuario
                    $pf_data = array($user_profile->photoURL, $this->uid);
                    $stmt = $this->sql->prepare('UPDATE members SET avatar = ? WHERE id = ?');
                    $stmt->execute($pf_data);
                    $stmt->closeCursor();
                }
                
				//prepare the sql query
				$social_login = $this->sql->prepare('INSERT INTO
																social_connect
															VALUES
																(?,?,?,?)
													ON DUPLICATE KEY UPDATE
																provider_uid = ?');
				
				$social_login->execute(array($this->uid, $provider_name, $user_profile->identifier, $this->uid.$provider_name, $user_profile->identifier));
				$this->queries++;
				
				return true;
			}
			catch( Exception $e ){
				return false;
			}
		}
	}
	
	protected function socialAuthIsLinked($id){
		$stmt = $this->sql->prepare('SELECT member_id FROM social_connect WHERE provider_uid = ?');
		$stmt->execute(array($id));
		$stmt->closeCursor();
		
		if($stmt->rowCount() > 0){
			return false;
		}else{
			return true;
		}
	}
	
	public function socialAuthUnLink(){
		if(!empty($_GET['unlink'])){
			// change the following paths if necessary 
			$config = SYSTEM_PATH.'/hybridauth/config.php';
			require_once( SYSTEM_PATH.'/hybridauth/Hybrid/Auth.php' );

			// the selected provider
			$provider_name = $this->sanitize($_GET['unlink'], 'string');

			try{
				// initialize Hybrid_Auth with a given file
				$hybridauth = new Hybrid_Auth( $config );
				
				//prepare the sql query
				$social_login = $this->sql->prepare('DELETE FROM
																social_connect
															WHERE
																provider_identify = ?');
				$social_login->execute(array($this->uid.$provider_name));
				$this->queries++;
				
				if($hybridauth->isConnectedWith($provider_name)){
					unset($_SESSION['HA::CONFIG']);
					unset($_SESSION['HA::STORE']);
				}
				
				return true;
			}
			catch( Exception $e ){
				return false;
			}
		}
	}
	
	public function showProviders($text = ''){
		$config = include(SYSTEM_PATH.'/hybridauth/config.php');
		if($config['active'] == 0){
			return array('providers'=>0);
		}
		
		$config_url = SYSTEM_PATH.'/hybridauth/config.php';		
		require_once( SYSTEM_PATH.'/hybridauth/Hybrid/Auth.php' );
		
		$connectedto = array();
		$curConnected = array();
		
		if($this->loggedin){
			$stmt = $this->sql->prepare('SELECT provider_name FROM social_connect WHERE member_id = ?');
			$stmt->execute(array($this->uid));
			
			foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
				$connectedto[] = $row['provider_name'];
			}
			$stmt->closeCursor();

			// initialize Hybrid_Auth with a given file
			$hybridauth = new Hybrid_Auth( $config_url );
			$curConnected = $hybridauth->getConnectedProviders();
		}
		
		// Make the list
		if($config['active'] == 0){
			return '';
		}
		
		$output = '';
		$c = 0;
		foreach($config['providers'] as $name => $conf){
			if($name != 'OpenID' && $conf['enabled']){
				if($this->loggedin){
					$output .= '<div class="span2">
									<img src="images/social/'.strtolower($name).'.png" alt="">'.(!in_array($name, $connectedto) ? 
										'<a href="?link='.$name.'" class="owloo_btn owloo_btn_blue">Conectar</a>' : 
										'<a href="?unlink='.$name.'" class="btn btn-small btn-danger">Desconectar</a>').'
								</div>';
				}else{
					$output .= '<a href="?provider='.$name.'" class="owloo_login_icons zocial '.strtolower($name).'">'.$name.'</a>';
                    //$output .= '<a href="?provider='.$name.'" class="owloo_login_icons zocial '.strtolower($name).'"><img src="'.URL_IMAGES.'social-icons/'.strtolower($name).'-icon.png" alt="'.strtolower($name).'" /></a>';
				}
				$c++;
			}
		}
		
		if($this->loggedin){
			return $output;
		}else{
			return array('providers'=>$c, 'links'=>$output);
		}
	}
	
	public function socialSignupError(){
		if(!empty($_GET['error'])){
			switch($_GET['error']){
				case 'emailinuse':
					return '<div class="alert alert-error">'.$this->lang['cl_users_10'].'</div>';
					break;
				case 'regerr':
					return '<div class="alert alert-error">'.$this->lang['cl_users_18'].'</div>';
					break;
				case 'noemail':
					return '<div class="alert alert-error">'.$this->lang['cl_users_42'].'</div>';
					break;
				case 'sninuse':
					return '<div class="alert alert-error">'.$this->lang['cl_users_43'].'</div>';
					break;
				case 'adminact':
					return '<div class="alert alert-success">'.$this->lang['cl_users_46'].'</div>';
					break;
				case 'notactive':
					return '<div class="alert alert-warning">'.$this->lang['cl_users_44'].'</div>';
					break;
                case 'notassociated':
                    return '<div class="alert alert-warning">'.$this->lang['cl_users_200'].'</div>';
                    break;
			}
		}
	}
}