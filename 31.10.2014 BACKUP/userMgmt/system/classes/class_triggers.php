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

class Triggers extends Template{
	
	protected function setTrigger($name, $value = true){		
		if(!isset($_SESSION['triggers'])){
			$_SESSION['triggers'] = array();
		}
		
		switch($name){
			case 'newusername':
				$_SESSION['triggers']['newusername'] = $value;
				break;
			case 'newpassword':
				$_SESSION['triggers']['newpassword'] = $value;
				break;
			case 'newemail':
				$_SESSION['triggers']['newemail'] = $value;
				break;
		}
	}
	
	public function checkTriggers(){
		if(empty($_SESSION['triggers'])){
			return false;
		}else{
			if(($this->curpage == 'login' || $this->curpage == 'forgotpass' || $this->curpage == 'signup' || $this->curpage == 'profile') && $this->curpage != 'required' && (empty($_POST['call']) || $_POST['call'] != 'checkaccount')){
				header('Location: required.php');
				exit;
			}
		}
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
	
	public function process_triggers(){
		if(!$this->loggedin){
			header('Location: login.php');
			exit;
		}
		
		$update = '';
		$data = array();
		
		if(!empty($_POST['triggerevents'])){
			if(!$this->csrfCheck('triggerevents')){
				$this->setNotification('required', 'Security Token was invalid.');
				return false;
			}
			
			if(!empty($_SESSION['triggers']['newusername']) && $_SESSION['triggers']['newusername']){
				$_POST['new_username'] = strip_tags(addslashes($this->sanitize($_POST['new_username'], 'string')));
				$update .= 'username = ?';
				$data[] = $_POST['new_username'];
				
				// The the old password and username
				$udata = $this->sql->prepare('SELECT username, password FROM members WHERE id = ?');
				$udata->execute(array($this->uid));
				$this->queries++;
				
				while($row = $udata->fetchAll(PDO::FETCH_ASSOC)){
					$row = $row[0];
				
					$cur_username = $row['username'];
					$cur_password = $row['password'];
				}
				$udata->closeCursor();
				
				//check if the username contains special characters.
				if(empty($_POST['new_username'])){
					$this->setNotification('required', $this->lang['cl_users_17']);
					return false;
				}
				//check if the username is the same as before.
				if($_POST['new_username'] == $cur_username){
					$this->setNotification('required', 'You cannot use the same username.');
					return false;
				}
				//check if the username contains special characters.
				if(!preg_match('/^[a-zA-Z0-9_-]{3,}$/', $_POST['new_username'])){
					$this->setNotification('required', $this->lang['cl_users_12'].' 3 '.$this->lang['cl_users_13']);
					return false;
				}
				//check if an account is already registered with that username
				if(!$this->check_username($_POST['new_username'])){
					$this->setNotification('required', $this->lang['cl_users_14']);
					return false;
				}
				
				// check if the password is correct.
				if(empty($_SESSION['triggers']['newpassword'])){
					if(empty($_POST['new_curpass']) || hash_hmac('sha256', $this->find_salt($cur_username).hash_hmac('sha256', $this->config['hashing_salt'].$_POST['new_curpass'], $this->config['hashing_key']), $this->config['hashing_key']) != $cur_password){
						$this->setNotification('required', $this->lang['cl_users_24']);
						return false;
					}else{
						$newpassword = $_POST['new_curpass'];
					}
				}else{
					$newpassword = $_POST['new_password'];
				}
				
				$salt = $this->find_salt((empty($_POST['new_username']) ? $_SESSION['udata']['username'] : $_POST['new_username']));
				$password = hash_hmac('sha256', $salt.hash_hmac('sha256', $this->config['hashing_salt'].$newpassword, $this->config['hashing_key']), $this->config['hashing_key']);
			
				$update .= (!empty($update) ? ',' : '').'password = ?';
				$data[] = $password;
			}
			
			if(!empty($_SESSION['triggers']['newpassword']) && $_SESSION['triggers']['newpassword']){
				//check if the username contains special characters.
				if(empty($_POST['new_password'])){
					$this->setNotification('required', $this->lang['cl_users_17']);
					return false;
				}
				//check if the two passwords match
				if($_POST['new_password'] !== $_POST['new_password2']){
					$this->setNotification('required', $this->lang['cl_users_15']);
					return false;
				}
				//Make sure the username is not precent in the password
				if(strpos($_POST['new_password'], (!empty($_POST['new_username']) ? $_POST['new_username'] : $_SESSION['udata']['username'])) !== false){
					$this->setNotification('required', $this->lang['cl_users_16']);
					return false;
				}
				
				$salt = $this->find_salt((empty($_POST['new_username']) ? $_SESSION['udata']['username'] : $_POST['new_username']));
				$password = hash_hmac('sha256', $salt.hash_hmac('sha256', $this->config['hashing_salt'].$_POST['new_password'], $this->config['hashing_key']), $this->config['hashing_key']);
			
				$update .= (!empty($update) ? ',' : '').'password = ?';
				$data[] = $password;
			}
			
			if(!empty($_SESSION['triggers']['newemail']) && $_SESSION['triggers']['newemail']){
				$_POST['new_email'] = $this->sanitize($_POST['new_email'], 'email');
				
				//check if the username contains special characters.
				if(empty($_POST['new_email'])){
					$this->setNotification('required', $this->lang['cl_users_17']);
					return false;
				}
				//check if the e-mail is an actual e-mail
				if(!$this->validateEmail($_POST['new_email'])){
					$this->setNotification('required', $this->lang['cl_users_9']);
					return false;
				}
				//check if an account is already registered with that e-mail
				if(!$this->check_email($_POST['new_email'])){
					$this->setNotification('required', $this->lang['cl_users_10']);
					return false;
				}
				
				$update .= (!empty($update) ? ',' : '').'email = ?';
				$data[] = $_POST['new_email'];
			}
			
			$data[] = $this->uid;
			
			$stmt = $this->sql->prepare('UPDATE members SET '.$update.', triggers = "" WHERE id = ?');
			$stmt->execute($data);
			$err = $stmt->errorInfo();
			
			unset($_SESSION['triggers']);
            
            /*** Register into EMA ***/
            $_aux_user_name = '';
            $udata = $this->sql->prepare('SELECT f_val FROM profiles WHERE u_id = ? AND f_id = 1');
            $udata->execute(array($this->uid));
            
            while($row = $udata->fetchAll(PDO::FETCH_ASSOC)){
                $_aux_user_name = $row[0]['f_val'];
            }
            $udata->closeCursor();
            
            if(!empty($_POST['new_email']) && !empty($_aux_user_name)){
                $params = array(
                   "EMAIL" => $_POST['new_email'],
                   "AGE" => "0",
                   "FNAME" => $_aux_user_name,
                   "LNAME" => "",
                   'ADD_OWLOO_LIST' => 'ow#gfdg89-Hf56'
                );
                 
                $this->owloo_ema_register_http_post("http://latamclick.net/ema/lists/ga908fyyyj5f8/subscribe",$params);
            }
            /*** FIN - Register into EMA ***/
			
			if(empty($err[2])){
				header('Location: index.php');
				exit;
			}
		}
	}
	
	public function showTriggerEventsForm(){
		$output = '<form class="form-horizontal owloo_signup_require" method="post" action="#">';
		
		if(!empty($_SESSION['triggers']['newusername'])){
			$output .= '<div class="control-group">
							<label class="control-label" for="new_username">Nombre de usuario</label>
							<div class="controls">
								<input type="text" id="new_username" name="new_username" placeholder="username">
							</div>
						</div>';
			
			if(empty($_SESSION['triggers']['newpassword'])){
				$output .= '<div class="control-group">
								<label class="control-label" for="new_curpass">Contraseña actual</label>
								<div class="controls">
									<input type="password" id="new_curpass" name="new_curpass" placeholder="contraseña actual">
								</div>
							</div>';
			}
		}

        if(!empty($_SESSION['triggers']['newemail'])){
            $output .= '<div class="control-group">
                            <label class="control-label" for="new_email">Correo electrónico</label>
                            <div class="controls">
                                <input type="text" id="new_email" name="new_email" placeholder="correo">
                            </div>
                        </div>';
        }
		
		if(!empty($_SESSION['triggers']['newpassword'])){
			$output .= '<div class="control-group">
							<label class="control-label" for="new_password">Contraseña</label>
							<div class="controls">
								<input type="password" id="new_password" name="new_password" placeholder="contraseña">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="new_password2">Confirmar contraseña</label>
							<div class="controls">
								<input type="password" id="new_password2" name="new_password2" placeholder="confirmar contraseña">
							</div>
						</div>';
		}
		
		$output .= '
			<div class="form-actions">
				<button type="submit" class="owloo_btn owloo_btn_blue">Guardar</button>
			</div>
			<input type="hidden" name="triggerevents" value="'.$this->csrfGenerate('triggerevents').'" />
		</form>';
		
		return $output;
	}
	
	/*
		ADMIN PANEL
	*/
	
	public function triggerActions(){
		$output = '<legend>Account Triggers</legend>
						<p>Account triggers are events which will run when the user log in or while the user is logged in. For example if the "change password" 
						trigger is applied to a user, the user will be promted to change their password when the user log in or while the user is already logged in. 
						The trigger is then removed once completed. When applying/removing triggers to all members or a member group, the admin adding or removing the triggers will not be affected.</p>
						<br />
						<form id="apply_trigger" class="form-inline" onsubmit="return false;">
							Add the 
							&nbsp;
							<select name="trigger" class="input-large">
								<option value="1">Change Password</option>
								<option value="2">Change Username</option>
								<option value="3">Change Email</option>
								<option value="4">All</option>
							</select>
							&nbsp;
							trigger(s) to
							&nbsp;
							<select name="range" class="input-medium">
								<option value="1">A User or Group</option>
								<option value="2">Everyone</option>
							</select>
							&nbsp;
							<input type="text" class="input-medium" name="to" data-search="members" placeholder="Username" autocomplete="off">
							&nbsp;
							<button type="submit" class="btn btn-success">Add Now</button>
						</form>
						
						<form id="remove_trigger" class="form-inline">
							Remove the 
							&nbsp;
							<select name="trigger" class="input-large">
								<option value="1">Change Password</option>
								<option value="2">Change Username</option>
								<option value="3">Change Email</option>
								<option value="4">All</option>
							</select>
							&nbsp;
							trigger(s) from
							&nbsp;
							<select name="range" class="input-medium">
								<option value="1">A User or Group</option>
								<option value="2">Everyone</option>
							</select>
							&nbsp;
							<input type="text" class="input-medium" name="to" data-search="members" placeholder="Username" autocomplete="off">
							&nbsp;
							<button type="submit" class="btn btn-success">Remove Now</button>
						</form>';
		return $output;
	}
	
	public function applyTriggers(){
		//check if the user is admin
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		if(empty($_POST['trigger']) || empty($_POST['range'])){
			return array('status'=>false, 'message'=>'You need to select both a trigger and who to apply the trigger to.');
		}
		
		if(!in_array(intval($_POST['trigger']), array(1,2,3,4))){
			return array('status'=>false, 'message'=>'You need to select both a trigger and who to apply the trigger to.');
		}
		
		if($_POST['range'] == 1 && empty($_POST['to'])){
			return array('status'=>false, 'message'=>'You need to select both a trigger and who to apply the trigger to.');
		}
		
		switch(intval($_POST['trigger'])){
			case 1:
				$triggers = array('newpassword' => true);
				break;
				
			case 2:
				$triggers = array('newusername' => true);
				break;
				
			case 3:
				$triggers = array('newemail' => true);
				break;
				
			case 4:
				$triggers = array(
							'newusername' => true,
							'newpassword' => true,
							'newemail' => true
						);
				break;
		}
		
		// Keep track of how many the trigger(s) where applied to.
		$c = 0;
		// Find out who to apply the trigger(s) to.
		if($_POST['range'] == 1){
			if(strpos($_POST['to'], '[grp]') !== false){
			/*
				APPLY TO GROUP
			*/
				$stmt = $this->sql->prepare('SELECT id, triggers FROM members WHERE membergroup = (SELECT id FROM member_groups WHERE name = ?)');
				$stmt->execute(array($this->sanitize(str_replace('[grp] ', '',$_POST['to']), 'string')));
				$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$stmt->closeCursor();
													
				if($stmt->rowCount() > 0){
					$stmt = $this->sql->prepare('UPDATE  
														members 
													SET  
														triggers = ? 
													WHERE 
														id = ?');
					
					foreach($user as $usr){
						$usr['triggers'] = unserialize($usr['triggers']);
						
						if(!empty($usr['triggers'])){
							$usr_triggers = array_merge($triggers, $usr['triggers']);
						}else{
							$usr_triggers = $triggers;
						}
						
						$stmt->execute(array(serialize($usr_triggers), $usr['id']));
						$c++;
					}
					
					$stmt->closeCursor();
				}
			}else{				
				/*
					APPLY TO USER
				*/
				$stmt = $this->sql->prepare('SELECT triggers FROM members WHERE username = ?');
				$stmt->execute(array($this->sanitize(str_replace('[usr] ', '', $_POST['to']), 'string')));
				$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$stmt->closeCursor();
				
				if($stmt->rowCount() > 0 && !empty($user[0]['triggers'])){
					$user = unserialize($user[0]['triggers']);
					
					if(!empty($user)){
						$triggers = array_merge($triggers, $user);
					}
				}
				
				$stmt = $this->sql->prepare('UPDATE  
													members 
												SET  
													triggers = ? 
												WHERE 
													username = ?');
				$stmt->execute(array(serialize($triggers), str_replace('[usr] ', '', $_POST['to'])));
				$stmt->closeCursor();
				$c++;
			}
		}else{
			/*
				APPLY TO ALL
			*/
			$stmt = $this->sql->prepare('SELECT id, triggers FROM members WHERE id != ? ');
			$stmt->execute(array($this->uid));
			$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt->closeCursor();
			
			if($stmt->rowCount() > 0){
				$stmt = $this->sql->prepare('UPDATE  
													members 
												SET  
													triggers = ? 
												WHERE 
													id = ?');
													
				foreach($user as $usr){
					$usr['triggers'] = unserialize($usr['triggers']);
					
					if(!empty($usr['triggers'])){
						$usr_triggers = array_merge($triggers, $usr['triggers']);
					}else{
						$usr_triggers = $triggers;
					}
					
					$stmt->execute(array(serialize($usr_triggers), $usr['id']));
					$c++;
				}
				
				$stmt->closeCursor();
			}
		}
		
		return array('status'=>true, 'message'=>'Trigger(s) applied to '.$c.' users.');
	}
	
	public function removeTriggers(){
		//check if the user is admin
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		if(empty($_POST['trigger']) || empty($_POST['range'])){
			return array('status'=>false, 'message'=>'You need to select both a trigger and who to remove the trigger from.');
		}
		
		if(!in_array(intval($_POST['trigger']), array(1,2,3,4))){
			return array('status'=>false, 'message'=>'You need to select both a trigger and who to remove the trigger from.');
		}
		
		if($_POST['range'] == 1 && empty($_POST['to'])){
			return array('status'=>false, 'message'=>'You need to select both a trigger and who to remove the trigger from.');
		}
		
		switch(intval($_POST['trigger'])){
			case 1:
				$triggers = array('newpassword');
				break;
				
			case 2:
				$triggers = array('newusername');
				break;
				
			case 3:
				$triggers = array('newemail');
				break;
				
			case 4:
				$triggers = array(
								'newusername',
								'newpassword',
								'newemail'
							);
				break;
		}
		
		// Keep track of how many the trigger(s) where applied to.
		$c = 0;
		// Find out who to apply the trigger(s) to.
		if($_POST['range'] == 1){
			if(strpos('[grp]', $_POST['range']) !== false){
			/*
				REMOVE FROM GROUP
			*/
				$stmt = $this->sql->prepare('SELECT id, triggers FROM members WHERE membergroup = ?');
				$stmt->execute(array($this->sanitize(str_replace('[grp] ', '',$_POST['to']), 'string')));
				$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$stmt->closeCursor();
				
				$stmt = $this->sql->prepare('UPDATE  
													members 
												SET  
													triggers = ? 
												WHERE 
													id = ?');
													
				foreach($user as $usr){
					$usr_triggers = '';
					$c++;		
					
					if(!empty($usr['triggers'])){
						$usr['triggers'] = unserialize($usr['triggers']);
						
						foreach($triggers as $tgr){
							if(isset($usr['triggers'][$tgr])){
								unset($usr['triggers'][$tgr]);
							}
						}
						
						if(!empty($usr['triggers'])){
							$usr_triggers = serialize($usr['triggers']);
						}
					}else{
						// no need to update the users triggers if the user do not have any triggers to begin with.
						continue;
					}
					
					$stmt->execute(array($usr_triggers, $usr['id']));
				}
				
				$stmt->closeCursor();
			}else{				
				/*
					REMOVE FROM USER
				*/
				$stmt = $this->sql->prepare('SELECT triggers FROM members WHERE username = ?');
				$stmt->execute(array($this->sanitize(str_replace('[usr] ', '', $_POST['to']), 'string')));
				$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$stmt->closeCursor();
				
				$usr_triggers = '';
				
				if($stmt->rowCount() > 0 && !empty($user[0]['triggers'])){
					$user['triggers'] = unserialize($user[0]['triggers']);
					
					if(!empty($user['triggers'])){
						foreach($triggers as $tgr){
							if(isset($user['triggers'][$tgr])){
								unset($user['triggers'][$tgr]);
							}
						}
						if(!empty($user['triggers'])){
							$usr_triggers = serialize($user['triggers']);
						}
					}
				}
				
				$stmt = $this->sql->prepare('UPDATE  
													members 
												SET  
													triggers = ? 
												WHERE 
													username = ?');
				$stmt->execute(array($usr_triggers, str_replace('[usr] ', '', $_POST['to'])));
				$stmt->closeCursor();
				$c++;
			}
		}else{
			/*
				REMOVE FROM ALL
			*/
			$stmt = $this->sql->prepare('SELECT id, triggers FROM members WHERE id != ? ');
			$stmt->execute(array($this->uid));
			$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt->closeCursor();
			
			if($stmt->rowCount() > 0){
				$stmt = $this->sql->prepare('UPDATE  
													members 
												SET  
													triggers = ? 
												WHERE 
													id = ?');
													
				foreach($user as $usr){
					$usr_triggers = '';
					$c++;
					
					if(!empty($usr['triggers'])){
						$usr['triggers'] = unserialize($usr['triggers']);
						
						foreach($triggers as $tgr){
							if(isset($usr['triggers'][$tgr])){
								unset($usr['triggers'][$tgr]);
							}
						}
						
						if(!empty($usr['triggers'])){
							$usr_triggers = serialize($usr['triggers']);
						}
					}else{
						// no need to update the users triggers if the user do not have any triggers to begin with.
						continue;
					}
					
					$stmt->execute(array($usr_triggers, $usr['id']));
				}
				
				$stmt->closeCursor();
			}
		}
		
		return array('status'=>true, 'message'=>'Trigger(s) removed from '.$c.' users.');
	}
}