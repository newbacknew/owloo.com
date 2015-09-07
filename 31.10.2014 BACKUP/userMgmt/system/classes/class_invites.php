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

class Invites extends PrivateMsg{
	public $invites = 0;
	
	public function send_invite(){
		if(!$this->config['invite_system']){
			return false;
		}
		
		## Check if the user is allowed to send invites. ##
		if(!$this->permissions['invite_send']){
			return array('status'=>false, 'message'=>$this->lang['cl_invites_2']);
		}
		
		## Check if the user got enough invites ##
		if($this->invites < 1){
			return array('status'=>false, 'message'=>$this->lang['cl_invites_3']);
		}
		
		## Check if the user has type an email to send the invite to ##
		$_POST['sendto'] = $this->sanitize($_POST['sendto'], 'email');
		if(empty($_POST['sendto'])){
			return array('status'=>false, 'message'=>$this->lang['cl_invites_4']);
		}
		
		## Check if the email is valid ##
		if(!$this->validateEmail($_POST['sendto'])){
			return array('status'=>false, 'message'=>$this->lang['cl_invites_5']);
		}
		
		$time = time();
		$invite_code = '';
		$c = 0;
		foreach(str_split(md5($_SESSION['udata']['username'].$time)) as $char){
			if(in_array($c, array(6,13,19,26))){
				$invite_code .= '-';
			}
			$invite_code .= $char;
			$c++;
		}
		$invite_code = strtoupper($invite_code);
		
		$stmt = $this->sql->prepare('INSERT INTO invites (i_by, i_to, i_code, i_status, i_date) VALUES ( ?, ?, ?, ?, ?)');
		$stmt->execute(array($this->sanitize($this->uid, 'integer'), $_POST['sendto'], str_replace('-','', $invite_code), '0', $time));
		$stmt->closeCursor();
		
		if($stmt->rowCount() > 0){
			$stmt = $this->sql->prepare('UPDATE members SET invites=invites-1 WHERE id = ?');
			$stmt->execute(array($this->uid));
			$stmt->closeCursor();
			
			$this->invites--;
			
			require_once(SYSTEM_PATH.'/templates/emails/tmpl_send_invite.php');
			$variables = array('website_name' => $this->config['website_name'],
							   'site_url' => $this->base_url,
							   'username' => $this->sanitize($_SESSION['udata']['username'], 'string'),
							   'invitecode' => $invite_code
							   );
			
			$subject = $this->render_email($variables, $email['title']);
			$body = $this->render_email($variables, $email['body']);
			
			$this->send_mail($_POST['sendto'], $subject, $body);
			
			return array('status'=>true, 
						 'message'=>$this->lang['cl_invites_6'], 
						 'html'=>'<tr class="warning"> <td>'.$_POST['sendto'].'</td> <td>'.date($this->config['dateformat_long'], $time).'</td> <td>Pending</td> <td><button class="btn btn-small btn-warning revokeinvite" data-inviteid="'.$this->sql->lastInsertId().'">Revoke Invite</button></td> </tr>');
		}else{
			return array('status'=>false, 'message'=>$this->lang['cl_invites_7']);
		}
	}
	
	public function revoke_invite(){		
		## Check if the user is allowed to revoke invites. ##
		if(!$this->permissions['invite_revoke']){
			return array('status'=>false, 'message'=>$this->lang['cl_invites_8']);
		}
		
		## Check if the user has selected an invite ##
		if(empty($_POST['invite'])){
			return array('status'=>false, 'message'=>$this->lang['cl_invites_9']);
		}
		
		$stmt = $this->sql->prepare('DELETE FROM invites WHERE i_id = ? AND i_by = ? AND i_status = 0');
		$stmt->execute(array($this->sanitize($_POST['invite'], 'integer'), $this->uid));
		$stmt->closeCursor();
		
		if($stmt->rowCount() > 0){
			$stmt = $this->sql->prepare('UPDATE members SET invites=invites+1 WHERE id = ?');
			$stmt->execute(array($this->uid));
			$stmt->closeCursor();
		
			$this->invites++;
			return array('status'=>true);
		}else{
			return array('status'=>false, 'message'=>$this->lang['cl_invites_11']);
		}
	}
	
	public function check_invite($code){
		$code = str_replace('-', '', addslashes($code));
		
		if(!$this->config['invite_system'] || !$this->config['invite_only']){
			return true;
		}
		
		if(empty($code)){
			return false;
		}
		
		if(strlen($code) != 32){
			return false;
		}
		
		return true;
	}
	
	public function showInvites(){
		if(!$this->config['invite_system']){
			return false;
		}
		
		$stmt = $this->sql->prepare('SELECT * FROM invites WHERE i_by = ? ORDER BY i_date DESC');
		$stmt->execute(array($this->sanitize($this->uid,'integer')));
		
		$data = '';
		
		if($stmt->rowCount() > 0){
			while($invite = $stmt->fetch(PDO::FETCH_ASSOC)){
				$data .= '<tr class="'.($invite['i_status'] == 0 ? 'warning' : 'success').'">
							<td>'.$invite['i_to'].'</td>
							<td>'.date('d-m-Y @ H:i:s', $invite['i_date']).'</td>
							<td>'.($invite['i_status'] == 0 ? 'Pending' : 'Accepted').'</td>
							<td>'.($invite['i_status'] == 1 ? '' : '<button class="btn btn-small btn-warning revokeinvite" data-inviteid="'.$invite['i_id'].'">Revoke Invite</button>').'</td>
						</tr>';
			}
		}
		
		return $data;
	}
	
	public function accept_invite($uid, $code){
		if(!$this->config['invite_system']){
			return true;
		}
		
		if(!$this->config['invite_only']){
			return true;
		}
		
		$code = str_replace('-', '', addslashes($code));
		
		$stmt = $this->sql->prepare('UPDATE invites SET i_acceptedby = ?, i_status = 1 WHERE i_code = ? AND i_status = 0');
		$stmt->execute(array($this->sanitize($uid ,'integer'), $code));
		$stmt->closeCursor();
		
		if($stmt->rowCount() > 0){
			return true;
		}else{
			$stmt = $this->sql->prepare('DELETE FROM members WHERE id = ?');
			$stmt->execute(array($this->sanitize($uid ,'integer')));
			$stmt->closeCursor();
			
			return false;
		}
	}
	
	public function inviteSignupField(){
		if($this->config['invite_system'] && $this->config['invite_only']){
			return '<div class="owloo_session_inv_code">
						<div>introduce el código de invitación <img src="https://www.latamclick.com/owloo/static/images/owloo_qmark.png" alt="?" title="Código de invitación..."></div>
						<div class="input-prepend">
							<input name="signup_invitecode" type="text" placeholder="código de invitación" />
						</div>
					</div>';
		}
	}
	
	public function show_latestinvites(){
		//check if the user is admin
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		$stmt = $this->sql->prepare('SELECT 
											*,
											username
										FROM 
											invites 
										INNER JOIN
											members
											ON id = i_by
										ORDER BY 
											i_date DESC 
										LIMIT 20');
		$stmt->execute(array($this->sanitize($this->uid,'integer')));
		
		$data = '';
		
		if($stmt->rowCount() > 0){
			while($invite = $stmt->fetch(PDO::FETCH_ASSOC)){
				$data .= '<tr class="'.($invite['i_status'] == 0 ? 'warning' : 'success').'">
							<td>'.$invite['username'].'</td>
							<td>'.$invite['i_to'].'</td>
							<td>'.($invite['i_status'] == 0 ? 'Pending' : 'Accepted').'</td>
							<td>'.date('d-m-Y @ H:i:s', $invite['i_date']).'</td>
						</tr>';
			}
		}
		$stmt->closeCursor();
		
		return $data;
	}
	
	public function set_user_invites(){
		//check if the user is admin
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		if(empty($_POST['action']) || (empty($_POST['invites']) || $_POST['invites'] < 1) || empty($_POST['username'])){
			return false;
		}
		
		$_POST['invites'] = $this->sanitize($_POST['invites'], 'integer');
		$_POST['username'] = $this->sanitize($_POST['username'], 'string');
		$_POST['action'] = ($_POST['action'] == 'add' ? '+' : '-');
		
		$stmt = $this->sql->prepare('UPDATE 
											members
										SET
											invites=
											CASE
												WHEN invites'.$_POST['action'].$_POST['invites'].' < 1 THEN 0
												WHEN invites'.$_POST['action'].$_POST['invites'].' > 10000 THEN 10000
												ELSE invites'.$_POST['action'].$_POST['invites'].'
											END
										WHERE 
											username = ?');
		$stmt->execute(array($_POST['username']));
		$stmt->closeCursor();
		
		return true;
	}
	
	public function set_group_invites(){
		//check if the user is admin
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		if(empty($_POST['action']) || (empty($_POST['invites']) || $_POST['invites'] < 1) || empty($_POST['group'])){
			return false;
		}
		
		$_POST['invites'] = $this->sanitize($_POST['invites'], 'integer');
		$_POST['group'] = $this->sanitize($_POST['group'], 'string');
		$_POST['action'] = ($_POST['action'] == 'add' ? '+' : '-');
		
		$stmt = $this->sql->prepare('SELECT id FROM member_groups WHERE name = ?');
		$stmt->execute(array($_POST['group']));
		
		$group = @$stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt->closeCursor();
		
		if($stmt->rowCount() > 0){
			$stmt = $this->sql->prepare('UPDATE 
												members
											SET
												invites=
												CASE
													WHEN invites'.$_POST['action'].$_POST['invites'].' < 1 THEN 0
													WHEN invites'.$_POST['action'].$_POST['invites'].' > 10000 THEN 10000
													ELSE invites'.$_POST['action'].$_POST['invites'].'
												END
											WHERE 
												membergroup = ?');
			$stmt->execute(array($group[0]['id']));
			$stmt->closeCursor();
			
			return true;
		}else{
			return false;
		}
	}
}