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

class Sessions{
	public $sql;
	protected $config;
	public $base_url;
	protected $dbsessions = false;
	public $installed = false;
   
	public function __construct() {
		## Get the config information. ##
		require(SYSTEM_PATH.'/config.php');
		
		if(!empty($config['sql_host']) && !empty($config['sql_user']) && !empty($config['sql_pass']) && !empty($config['sql_db']) && !empty($config['sql_charset']) && !empty($config['sql_port'])){
			## Connect to the databse ##
			$this->installed = true;
			
			try{	
				$sql = new PDO("mysql:host=".$config['sql_host'].";port=".$config['sql_port'].";dbname=".$config['sql_db'].";charset=".$config['sql_charset'], $config['sql_user'], $config['sql_pass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
			}
			catch(PDOException $pe){
				$this->installed = false;
			}
			
			if($this->installed){
				$this->sql = $sql;
			}
		}
		
		$this->load_config($config);
		
		if(!defined('IPN') && $this->dbsessions && $this->installed){
			session_set_save_handler(array( &$this, "open" ), 
									 array( &$this, "close" ),
									 array( &$this, "read" ),
									 array( &$this, "write"),
									 array( &$this, "destroy"),
									 array( &$this, "gc" ));
		}
		
		if(session_id() == ''){
			session_start();
		}
		$this->regeneratSession();
	}
	
	protected function regeneratSession($force = false){
		if(!empty($_SESSION['secure_session']) && !$force){
			if($_SESSION['secure_session'] == hash_hmac('sha256', session_id().$this->config['hashing_salt'], $this->config['hashing_key'])){
				return true;
			}
		}
		
		$old_session = session_id();
		session_regenerate_id();
		$_SESSION['secure_session'] = hash_hmac('sha256', session_id().$this->config['hashing_salt'], $this->config['hashing_key']);
		$this->destroy($old_session, true);
	}
	
	protected function load_config($config){
		## Check if the salt key is set, else use an MD5 of the Domain ##
		if(empty($config['hashing_salt'])){
			$config['hashing_salt'] = md5($_SERVER['HTTP_HOST']);
		}
		## Check if the hashing key is set, else use the MD5 of the domain and directory path ##
		if(empty($config['hashing_key'])){
			$config['hashing_key'] = md5($_SERVER['HTTP_HOST'].dirname(__FILE__));
		}
		## Check if the domain is set, else try to find it ourselfs ##
		if(empty($config['base_url'])){
			$config['base_url'] = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['REQUEST_URI']);
		}
		## Check if the session timeout is above 10 (sec) and is a number ##
		if(!isset($config['session_timeout']) || !is_int($config['session_timeout']) || ($config['session_timeout'] > 0 && $config['session_timeout'] < 10)){
			$config['session_timeout'] = 0;
		}
		
		## Check if the session timeout is above 10 (sec) and is a number ##
		if(!isset($config['session_timeout']) || !is_int($config['session_timeout']) || ($config['session_timeout'] > 0 && $config['session_timeout'] < 60)){
			$config['session_timeout'] = 60;
		}
		$config['account_check_interval'] *= 1000; // we need it in 1000s as it is for the javascript setInterval()
		
		## Check if the session timeout is above 10 (sec) and is a number ##
		if(!empty($config['dbsessions'])){
			$this->dbsessions = ($config['dbsessions'] == true ? true : false);
		}
		
		$this->config = $config;
		$this->base_url = $config['base_url'];
		
		return $this->dbsessions;
	}
	
	public function open() {
		return true;
	}
	 
	public function close() {
		return true;
	}
	
	public function read($id) {
		$s_data = '';
		$read = $this->sql->prepare('SELECT 
										`session_data` 
									FROM 
										`sessions` 
									WHERE 
										`session_id` = ?');
		$q_data = array($id);
		$read->execute($q_data);
		$this->queries++;
		
		if($read->rowCount() > 0){
			while($row = $read->fetchAll(PDO::FETCH_ASSOC)){
				$row = $row[0];
				$s_data = $row['session_data'];
			}
		}
		$read->closeCursor();
		
		return $s_data;
	}
	
	public function write($id, $data) {	
		$write = $this->sql->prepare('INSERT INTO 
												`sessions` 
												(`session_id`, `session_data`, `session_expire`, `session_agent`, `session_ip`, `session_host`) 
											VALUES
												(?,?,?,?,?,?)
											ON DUPLICATE KEY UPDATE
												`session_data` = ?
												'.(defined('AJAX_CALL') ? '' : ',`session_expire` = ?')
									);
											
		$q_data = array($id, $data, time(),
						$this->session_encryption($_SERVER['HTTP_USER_AGENT']), 
						$this->session_encryption($_SERVER['REMOTE_ADDR']),
						$this->session_encryption(gethostbyaddr($_SERVER['REMOTE_ADDR'])),
						$data);
		if(!defined('AJAX_CALL')){
			$q_data[] .= time();
		}
		
		$write->execute($q_data);
		$write->closeCursor();
		$err = $write->errorInfo();
		if(!empty($err[2])){
			var_dump($write->errorInfo());
		}
		
		$this->queries++;
		
		return true;
	}
	
	public function destroy($id='', $regen = false) {
		if($this->dbsessions){
			if(!empty($id)){
				$destroy = $this->sql->prepare('DELETE FROM sessions WHERE session_id = ?');
				$destroy->execute(array($this->sanitize($id, 'string')));
				$destroy->closeCursor();
				$this->queries++;
			}else{
				@session_destroy();
			}
		}else{
			if(isset($_SESSION) && !$regen){
				@session_destroy();
			}
		}
		return true;
	}
	
	public function gc(){
		$lifetime = $this->config['session_timeout'];
		
		if( $lifetime > 0){
			$gc_qry = $this->sql->prepare('DELETE FROM `sessions` WHERE session_expire < ?');
			$q_data = array(time()-$lifetime);
			$gc_qry->execute($q_data);
			$gc_qry->closeCursor();
			$this->queries++;
		}
		
		return true;
	}
	
	protected function session_encryption($string){		
		$string = hash_hmac('sha256', $this->config['hashing_salt'].$string, $this->config['hashing_key']);
		
		return $string;
	}
	
	public function check_session($user){
		if($this->dbsessions){
			$check_ses = $this->sql->prepare('SELECT
												sessions.session_key,
												sessions.session_expire,
												members.id,
												members.triggers,
												members.username,
												members.avatar,
												members.membergroup,
												members.other_membergroups,
												members.invites,
												member_groups.permissions
											FROM
												sessions
											INNER JOIN
												members
												ON
													members.session = sessions.session_key
											INNER JOIN
												member_groups
												ON
													member_groups.id = members.membergroup
											WHERE
												sessions.session_id = ?
											AND
												sessions.session_agent = ?
											AND
												sessions.session_ip = ?
											AND
												sessions.session_host = ?');
			//add "gethostbyaddr()" check in 1.3.1
			
			$data = array(session_id(),
						  $this->session_encryption($_SERVER['HTTP_USER_AGENT']), 
						  $this->session_encryption($_SERVER['REMOTE_ADDR']),
						  $this->session_encryption(gethostbyaddr($_SERVER['REMOTE_ADDR'])),
						 );
			
			$check_ses->execute($data);
			$this->queries++;
			
			if($check_ses->rowCount() != 1){
				$check_ses->closeCursor();
				return '';
			}
			
			$row = $check_ses->fetchAll(PDO::FETCH_ASSOC);
			$row = $row[0];
			
			$check_ses->closeCursor();
			
			if($this->config['session_timeout'] > 0 && ($row['session_expire'] < time()-$this->config['session_timeout'])){
				$this->destroy(session_id());
				return '';
			}
			
			if($row['id'] !== $user){
				return '';
			}
			
			return $row;
		}else{
			$check_ses = $this->sql->prepare('SELECT
												members.id,
												members.username,
												members.avatar,
												members.session,
												members.triggers,
												members.membergroup,
												members.other_membergroups,
												members.invites,
												member_groups.permissions
											FROM
												members
											INNER JOIN
												member_groups
												ON
													member_groups.id = members.membergroup
											WHERE
												members.id = ?');
			
			$check_ses->execute( array($user));
			$this->queries++;
			
			if($check_ses->rowCount() != 1){
				$check_ses->closeCursor();
				return '';
			}
			
			$row = $check_ses->fetchAll(PDO::FETCH_ASSOC);
			$row = $row[0];
			$check_ses->closeCursor();
			
			
			if($_SESSION['session'] !== $this->session_encryption($row['session'].$_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'])){
				return '';
			}
			
			if($this->config['session_timeout'] > 0 && ($_SESSION['session_expire'] < time()-$this->config['session_timeout'])){
				$this->destroy();
				return '';
			}
			
			if($_SESSION['uid'] !== $user){
				return '';
			}
			
			return $row;
		}
	}
}