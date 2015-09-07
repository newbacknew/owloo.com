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

class PrivateMsg extends FriendSystem{
	
	public function processtime($unix, $showlabel = false, $countdown = false){
		$min = 60;
		$hour = 3600;
		$day = 86400;
		
		$diff = time() - $unix;
		$diff2 = $diff;
		
		if($countdown){
			$diff = $unix - time();
			$diff2 = time();
		}

		$days = floor($diff / $day);
		$days = floor($diff / $day);
		$diff = $diff-($day * $days);
		$hours = floor($diff / $hour);
		$diff = $diff-($hour * $hours);
		$minutes = floor($diff / $min);
		$diff = $diff-($min * $minutes);
		$seconds = $diff;
		
		if($minutes == 1){
			$m = ' '.$this->lang['cl_private_1']; } else { $m = ' '.$this->lang['cl_private_2']; 
		}
		
		if($hours == 1){
			$h = ' '.$this->lang['cl_private_3'];
		}else{
			$h = ' '.$this->lang['cl_private_4'];
		}
		
		if($days == 1){
			$d = ' '.$this->lang['cl_private_5'];
		}else{
			$d = ' '.$this->lang['cl_private_6'];
		}

		if($diff2 < 60) {
			$timest = $diff.' '.$this->lang['cl_private_7'];
		}else{
			if($minutes >= 1){
				$timest = (!$countdown ? $this->lang['cl_private_8'] : '').' '.$minutes.$m;
			}
			if($hours >= 1){
				$timest = (!$countdown ? $this->lang['cl_private_9'] : '').' '.$hours.$h;
			}
			if($days >= 1){
				$timest = (!$countdown ? $this->lang['cl_private_10'] : '').' '.$days.$d;
			}
			if(!isset($timest)){
				$timest = '';
			}
		}

		if($timest == ''){
			$timest = (!$countdown ? $this->lang['cl_private_11'] : $this->lang['cl_private_22']);
		}
		
		if($showlabel){
			if($countdown){
				$now = $unix-time();
			}else{
				$now = time()-$unix;
			}
			switch(true){
				case($now <= 10800): //3 hours
					$labeltype = 'label-success';
					break;
				case($now > 10800 && $now <= 86400): //24 hours
					$labeltype = 'label-info';
					break;
				case($now > 86400 && $now <= 259200): //3 days
					$labeltype = 'label-warning';
					break;
				case($now > 259200 && $now <= 604800): //7 days
					$labeltype = 'label-important';
					break;
				case($now > 604800): //7 days and more
				default:
					$labeltype = '';
					break;
			}
			
			$timest = '<span class="label '.$labeltype.'">'.$timest.'</span>';
		}
		
		return $timest;
	}
	
	public function process_sendpm($data){
		if(!$this->loggedin){
			exit;
		}
		if(!$this->config['pm_system']) {
			return false;
		}
		
		if(!$this->csrfCheck('newpm')){
			return false;
		}
		
		if(is_array($data)){
			if($this->permissions['pm_write']){
				// Check if the user is trying to send a pm within the time limit
				$check_interval = (empty($_SESSION['last_pm']) ? '' : time()-$_SESSION['last_pm']);
				if(!empty($check_interval) && $check_interval <= $this->config['pm_interval']){
					$reply['status'] = false;
					$reply['message'] = $this->lang['cl_private_12'].' '.($this->config['pm_interval']-$check_interval).' '.$this->lang['cl_private_13'];
					return $reply;
				}
				
				// Check if the user the PM is for actually exists.
				$to = $this->get_uid($this->sanitize($data['to_user'], 'string'), 'username');
				if(empty($to)){
					$reply['status'] = false;
					$reply['message'] = $this->lang['cl_private_14'];
					return  $reply;
				}
				
				$from = $this->sanitize($this->uid, 'integer');
				$subject = $this->sanitize(strip_tags($data['subject']), 'string');
				$message = $this->sanitize(strip_tags($data['message']), 'string');
				
				if(empty($data['subject']) || empty($data['message'])){
					$reply['status'] = false;
					$reply['message'] = $this->lang['cl_private_14a'];
					return  $reply;
				}
				
				$success = $this->sql->prepare('INSERT INTO
														private_messages
														(to_user, from_user, subject, message, date)
													VALUES
														(?,?,?,?,?)
												');
				
				$data = array($to, $from, $subject, $message, time());
				$success->execute($data);
				$success->closeCursor();
				$this->queries++; //add 1 more to the number of queries executed.
				
				if($success->rowCount() > 0){
					$reply['status'] = true;
					$reply['message'] = $this->lang['cl_private_15'];
				}else{
					$reply['status'] = false;
					$reply['message'] = $this->lang['cl_private_16'];
				}
				
				$_SESSION['last_pm'] = time();
			}else{
				$reply['status'] = false;
				$reply['message'] = $this->lang['cl_private_17'];
			}
			
			return $reply;
		}
	}
	
	public function retrive_pmlist($asArray = false){
		if(!$this->loggedin){
			exit;
		}
		if(!$this->config['pm_system']) {
			if($asArray){
				return array();
			}else{
				return '';
			}
		}
		
		if($this->permissions['pm_read']){
			$retrive = $this->sql->prepare('SELECT 
												p.pm_id,
												p.subject,
												p.date,
												p.status,
												u.username as sender,
												u.id
											FROM
												private_messages as p
											INNER JOIN
												members as u
												ON id = from_user
											WHERE
												to_user = ?
											ORDER BY
												p.pm_id DESC
											LIMIT '.$this->sanitize($this->permissions['pm_limit'], 'integer'));
			
			$data = array($this->uid);
			$retrive->execute($data);
			$this->queries++; //add 1 more to the number of queries executed.
			
			$messages['messages'] = array();
			
			$pm_count = 0;
			$pm_list = array();
			if($retrive->rowCount() > 0){
				while($row = $retrive->fetch(PDO::FETCH_ASSOC)){
					$pm_count++;
					
					if($asArray){
						$pm_list[] = $row;
					}else{
						$lastid = $row['pm_id'];
						$date = date($this->config['dateformat_long'], $row['date']);
						$time = $this->processtime($row['date']);
						
						$message = array();
						$message['check'] = '<input type="checkbox" name="checkbox[]" id="pm_check_'.$row['pm_id'].'"/>';
						
						$message['sender'] = '<strong><a href="profile.php?username='.$row['sender'].'">'.$row['sender'].'</a></strong>';
						
						$message['date'] = '<div class="status '.($row['status'] == 'unread' ? 'unread' : 'read').'">
												<strong>'.$row['subject'].'</strong><br />
												<span>'.$this->lang['cl_private_18'].' '.$time.'</span>
											</div>';
															
						$message['controls'] = '<a href="#" name="'.$row['pm_id'].'" class="btn btn-success pop_box ReadSelectedPM">'.$this->lang['cl_private_19'].'</a>
												<a href="#" name="'.$row['pm_id'].'" class="btn btn-danger Delete DeleteSelectedPM" name="Band ring">'.$this->lang['cl_private_20'].'</a>';
															
						array_push($messages['messages'], $message);
					}
				}
			}
			$retrive->closeCursor();
			
			if($asArray){
				$messages = $pm_list;
			}else{
				$messages['pm_max'] = $this->permissions['pm_limit'];
				$messages['pm_space_left'] = $this->permissions['pm_limit']-$pm_count;
				
				if($pm_count >= $this->permissions['pm_limit']){
					$del_pms = $this->sql->prepare('DELETE FROM
																private_messages
															WHERE
																pm_id < ?
															AND
																to_user = ?
													');		
					$data = array($pm_count, $this->uid);
					$del_pms->execute($data);
					$del_pms->closeCursor();
					$this->queries++;
				}
			}
		}else{
			if($asArray){
				$messages = array();
			}else{
				$messages['messages'] = '';
				$messages['message'] = $this->lang['cl_private_21'];
			}
		}
		
		return $messages;
	}
	
	public function retrive_pmlist_dav($asArray = false){
		if(!$this->loggedin){
			exit;
		}
		if(!$this->config['pm_system']) {
			if($asArray){
				return array();
			}else{
				return '';
			}
		}
		
		if($this->permissions['pm_read']){
			$retrive = $this->sql->prepare('SELECT 
												p.pm_id,
												p.subject,
												p.date,
												p.status,
												u.username as sender,
												u.id
											FROM
												private_messages as p
											INNER JOIN
												members as u
												ON id = from_user
											WHERE
												to_user = ?
											ORDER BY
												p.pm_id DESC
											LIMIT '.$this->sanitize($this->permissions['pm_limit'], 'integer'));
			
			$data = array($this->uid);
			$retrive->execute($data);
			$this->queries++; //add 1 more to the number of queries executed.
			
			$messages['messages'] = array();
			
			$pm_count = 0;
			$pm_list = array();
			if($retrive->rowCount() > 0){
				while($row = $retrive->fetch(PDO::FETCH_ASSOC)){
					$pm_count++;
					
					if($asArray){
						$pm_list[] = $row;
					}else{
						$lastid = $row['pm_id'];
						$date = date($this->config['dateformat_long'], $row['date']);
						$time = $this->processtime($row['date']);
						
						$message = array();
						
						$message['sender'] = '<strong><a href="profile.php?username='.$row['sender'].'">'.$row['sender'].'</a></strong>';
						
						$message['subject'] = '<strong>'.$row['subject'].'</strong>';
															
						$message['controls'] = '<a href="#" name="'.$row['pm_id'].'" class="btn btn-success pop_box ReadSelectedPM">'.$this->lang['cl_private_19'].'</a>';
															
						array_push($messages['messages'], $message);
					}
				}
			}
			$retrive->closeCursor();
			
			if($asArray){
				$messages = $pm_list;
			}else{
				$messages['pm_max'] = $this->permissions['pm_limit'];
				$messages['pm_space_left'] = $this->permissions['pm_limit']-$pm_count;
				
				if($pm_count >= $this->permissions['pm_limit']){
					$del_pms = $this->sql->prepare('DELETE FROM
																private_messages
															WHERE
																pm_id < ?
															AND
																to_user = ?
													');		
					$data = array($pm_count, $this->uid);
					$del_pms->execute($data);
					$del_pms->closeCursor();
					$this->queries++;
				}
			}
		}else{
			if($asArray){
				$messages = array();
			}else{
				$messages['messages'] = '';
				$messages['message'] = $this->lang['cl_private_21'];
			}
		}
		
		return $messages;
	}
	
	public function process_readpm($pm_id, $origen = ''){
		if(!$this->loggedin){
			exit;
		}
		if(!$this->config['pm_system']) {
			return false;
		}
		
		if($this->permissions['pm_read']){
			$pm_id = $this->sanitize($pm_id, 'integer');
			$retrive = $this->sql->prepare('SELECT
												message,
												subject,
												status,
												username as sender
											FROM
												private_messages
											INNER JOIN
												members
												ON id = from_user
											WHERE
												pm_id = ?
											AND
												to_user = ?
											LIMIT 1');
												
			$data = array($pm_id, $this->uid);
			$retrive->execute($data);
			$this->queries++; //add 1 more to the number of queries executed.
			
			$message['status'] = false;
			if($retrive->rowCount() > 0){
				while($row = $retrive->fetchAll(PDO::FETCH_ASSOC)){
					$message['status']	= true;
					$message['message'] = $row[0]['message'];
					$message['subject'] = $row[0]['subject'];
					$message['sender'] 	= $row[0]['sender'];
					$message['pmid'] 	= $pm_id;
					$message['unread']	= ($row[0]['status'] == 'unread' ? true : false);
					$message['origen'] = $origen;
				}
			}
			$retrive->closeCursor();
			
			if(!empty($message['unread']) && $message['unread']){
				$markpm = $this->sql->prepare('UPDATE private_messages SET status="read" WHERE pm_id = ?');
				$markpm->execute(array($pm_id));
				$this->queries++;
				$markpm->closeCursor();
			}
		}
		
		return $message;
	}
	
	public function process_deletepm($msg_id){
		if(!$this->loggedin){
			exit;
		}
		if(!$this->config['pm_system']){
			return false;
		}
		
		if(!$this->csrfCheck('deletepm')){
			return false;
		}
		
		$id = $this->sanitize($msg_id, 'integer');
		
		$success = $this->sql->prepare('DELETE FROM
												private_messages
											WHERE
												pm_id = ?
											AND
												to_user = ?');
		
		$data = array($id, $this->sanitize($this->uid, 'integer'));
		$success->execute($data);
		$success->closeCursor();
		$this->queries++; //add 1 more to the number of queries executed.
		
		if($success->rowCount() > 0){
			$reply['status'] = true;
		}else{
			$reply['status'] = false;
		}
		$reply['pm_max'] = $this->permissions['pm_limit'];
		
		return $reply;
	}
	
	public function process_massdeletepm($msg_id){
		if(!$this->loggedin){
			exit;
		}
		if(!$this->config['pm_system']) {
			return false;
		}
		
		$msg_id = explode(',', $msg_id);
		if(count($msg_id) > 0){
			$sql_code = 'AND pm_id IN (';
			$id_array = array($this->uid);
			foreach($msg_id as $id){
				if($sql_code == 'AND pm_id IN ('){
					$sql_code .= '?';
				}else{
					$sql_code .= ',?';
				}
				$id_array[] .= $this->sanitize($id, 'integer');
			}
			$sql_code .= ')';
			
			$success = $this->sql->prepare('DELETE FROM
													private_messages
												WHERE
													to_user = ?
											'.$sql_code);

			$success->execute($id_array);
			$success->closeCursor();
			$this->queries++; //add 1 more to the number of queries executed.
			
			if($success->rowCount() > 0){
				$reply['status'] = true;
			}else{
				$reply['status'] = false;
			}
		}
		$reply['pm_max'] = $this->permissions['pm_limit'];
		
		return $reply;
	}
}