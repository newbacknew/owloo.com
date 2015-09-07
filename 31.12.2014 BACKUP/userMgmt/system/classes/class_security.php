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

class Security extends IPN{
	public $status;
	protected $recaptcha_get_html;
	protected $captcha_resp = null;
	protected $captcha_error = null;
	protected $captcha_success = false;
	public $errorlog = null;
	
	function __construct($err){
		$this->errorlog = $err;
		
		parent::__construct();
		$this->showRecaptcha(null,null,true);
	}
	
	
	## ## ## ## ## ## ##
	## CSRF Protection
	#
    protected function csrfCheck($key = 'general'){
		## Check if a token is even set ##
        if(empty($_SESSION['csrf'][$key])){
			return false;
		}
		## Check if the token was set with the form ##
        if(empty($_REQUEST[$key])){
			return false;
		}
        ## Check if the submitted token matches the session token ##
        if($_REQUEST[$key] != $_SESSION['csrf'][$key]){
			return false;
		}
		
        return true;
    }
	
    public function csrfGenerate($key = 'general'){		
		$token = sha1(time().$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].$this->randomGenerator(17));
        $_SESSION['csrf'][$key] = $token;

        return $token;
    }
	
	
	## ## ## ## ## ## ##
	## Spam Protection
	#
	public function showRecaptcha($key = null, $error = null, $check = false){
		if($this->config['reCAPTCHA_enabled']){
			if($key == null){
				$key = $this->config['reCAPTCHA_publickey'];
			}
			if($error == null){
				$error = $this->captcha_error;
			}
			
			## Initiate the reCaptcha ##
			require_once(SYSTEM_PATH.'/recaptcha/recaptchalib.php');
			
			if(!$check){
				return '<div id="recaptcha">'.recaptcha_get_html($key, $error).'</div>';
			}
			
			if(isset($_POST['recaptcha_response_field'])){
				$captcha_resp = recaptcha_check_answer ($this->config['reCAPTCHA_privatekey'],$_SERVER['REMOTE_ADDR'],$_POST['recaptcha_challenge_field'],$_POST['recaptcha_response_field']);
				if(!$captcha_resp->is_valid){
					//$captcha_error = $captcha_resp->error; //Just in case of debugging
					$status = false;
				}else{
					$status = true;
				}
				$this->captcha_success = $status;
			}
		}else{
			$this->captcha_success = true;
		}
	}
	
	
	## ## ## ## ## ## ##
	## XXS Protection
	#
	function sanitize($data, $type){
		## Use the HTML Purifier, as it help remove malicious scripts and code. ##
		##       HTML Purifier 4.4.0 - Standards Compliant HTML Filtering       ##
		require_once(SYSTEM_PATH.'/purifier/HTMLPurifier.standalone.php');
		//require_once(SYSTEM_PATH.'/purifier/standalone/HTMLPurifier/Filter/MyIframe.php');
		$purifier = new HTMLPurifier();
		$config = HTMLPurifier_Config::createDefault();
		$config->set('Core.Encoding', 'UTF-8');
		//$config->set('Filter.Custom', array(new HTMLPurifier_Filter_MyIframe()));
		//$config->set('HTML.Allowed', 'p,a[href|rel|target],img[class|src|height|width]');

		switch($type){
			case 'string':
				$data = $purifier->purify($data);
				break;
				
			case 'purestring':
				$data = $purifier->purify(strip_tags($data));
				break;
				
			case 'integer':
				$data = intval(preg_replace("/[^0-9]+/", '', $purifier->purify(addslashes($data))));
				break;
			
			case 'float':
				$data = floatval(preg_replace("/[^0-9\.]+/", '', $purifier->purify(addslashes($data))));
				break;
				
			case 'mixedint':
				$data = preg_replace("/[^0-9\,\.]+/", '', $purifier->purify($data));
				break;
				
			case 'email':
				$data = strtolower(filter_var($purifier->purify($data), FILTER_SANITIZE_EMAIL));
				break;
				
			case 'phone':
				$data = filter_var($purifier->purify($data), FILTER_SANITIZE_NUMBER_INT);
				break;
		}
		
		return $data;
	}
	
	
	## ## ## ## ## ## ## ##
	## General Protection
	#
	public function restricted_page($redirect = null, $admincheck = false, $viewlevel = null, $membergroups = null){
		if(!$this->loggedin && !$admincheck){
			if($redirect != null){
				$this->destroy();
				header("Location: ".$redirect);
				exit();
			}else{
				if(empty($membergroups) && empty($viewlevel)){
					echo json_encode(array('status'=>false));
					exit();
				}
				return false;
			}
		}else{			
			//If the requires Admin access
			if($admincheck && !$this->permissions['admin']){
				if(!empty($redirect)){
					header("Location: ".$redirect);
				}
				return false;
			}
			
			//If the page requires a certain view level
			if(!empty($viewlevel) && (empty($this->permissions['viewlever']) ||  $this->permissions['viewlever'] < $viewlevel) && !$this->permissions['admin']){
				if(!empty($redirect)){
					header("Location: ".$redirect);
				}
				return false;
			}
			
			//If the page requires a certain view level
			if(!empty($membergroups) && !$this->permissions['admin']){
				$membergroups = explode(',',$membergroups);
				
				if(!in_array($_SESSION['udata']['membergroup'], $membergroups)){
					$allowed = false;
					foreach(explode(',',$_SESSION['udata']['othergroups']) as $othergroup){
						if(in_array($othergroup, $membergroups)){
							$allowed = true;
							break;
						}
					}
					if(!$allowed){
						if(!empty($redirect)){
							header("Location: ".$redirect);
						}
						return false;
					}
				}
			}
			
			return true;
		}
	}
	
	public function randomGenerator($length = 64){
		if($length < 1){
			$length = 1;
		}
		
		$vowels = 'OUYaeiouyAEI';
		$consonants = '^*@£bcdfghjklB{]}CDFGHJK[LMmnpq$+|,.-_rstvxzNPQRSTVXZ!#%&/()=?';
		
		$key = '';
		$alt = mt_rand() % 2;
		for ($i = 0; $i < $length; $i++) {
			if ($alt == 1) {
				$key .= $consonants[(mt_rand() % strlen($consonants))];
				$alt = 0;
			} else {
				$key .= $vowels[(mt_rand() % strlen($vowels))];
				$alt = 1;
			}
		}
		
		return $key;
	}
	
	public function debugdata(){
		return '[Queries: '.$this->queries.']';
	}
}