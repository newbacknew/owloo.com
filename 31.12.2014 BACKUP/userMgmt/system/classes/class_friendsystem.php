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

class FriendSystem extends Profiles{	
	public function process_sendrequest($fid){
		if(!$this->loggedin){
			exit;
		}
		if(!$this->config['friend_system']) {
			return false;
		}
		
		if($this->permissions['friends_reqest']){
			$fid = $this->sanitize($fid, 'integer');
			$uid = $this->sanitize($this->uid, 'integer');
			
			$friend = $this->sql->prepare('INSERT IGNORE INTO
															friends
															(f_id, user_id, friend_id, status, date)
														VALUES
															(?,?,?,?,?)');
			
			$data = array($uid.$fid, $uid, $fid, 'pending', time());
			$friend->execute($data);
			$friend->closeCursor();
			$this->queries++; //add 1 more to the number of queries executed.
			
			if($friend->rowCount() > 0){
				$reply['status'] = true;
				$reply['message'] = $this->lang['cl_friend_1'];
			}else{
				$reply['status'] = false;
				$reply['message'] = $this->lang['cl_friend_2'];
			}
		}else{
			$reply['status'] = false;
			$reply['message'] = $this->lang['cl_friend_3'];
		}
		
		return $reply;
	}
	
	public function process_acceptrequest($fid){
		if(!$this->loggedin){
			exit;
		}
		if(!$this->config['friend_system']) {
			return false;
		}
		
		$fid = $this->sanitize($fid, 'integer');
		$uid = $this->sanitize($this->uid, 'integer');
		
		$friend1 = $this->sql->prepare('INSERT IGNORE INTO
														friends
														(f_id, user_id, friend_id, status, date)
													VALUES
														(?,?,?,?,?)');
		
		$data = array($uid.$fid, $uid, $fid, 'accepted', time());
		$friend1->execute($data);
		$friend1->closeCursor();
		$this->queries++; //add 1 more to the number of queries executed.
		
		$friend2 = $this->sql->prepare('UPDATE 
											friends
										SET
											status = "accepted"
										WHERE
											f_id = ?');
		
		$data = array($fid.$uid);
		$friend2->execute($data);
		$friend2->closeCursor();
		$this->queries++; //add 1 more to the number of queries executed.
		
		if($friend2->rowCount() > 0){
			$reply['status'] = true;
			$reply['message'] = $this->lang['cl_friend_4'];
		}else{
			$reply['status'] = false;
			$reply['message'] = $this->lang['cl_friend_5'];
		}
		
		return $reply;
	}
	
	public function process_removefriend($fid){	
		if(!$this->loggedin){
			exit;
		}
		if(!$this->config['friend_system']) {
			return false;
		}	
		
		$fid = $this->sanitize($fid, 'integer');
		$uid = $this->sanitize($this->uid, 'integer');
		
		$friend = $this->sql->prepare('DELETE FROM 
												friends
											WHERE
												f_id = ? OR f_id = ?');
		
		$data = array($fid.$uid, $uid.$fid);
		$friend->execute($data);
		$friend->closeCursor();
		$this->queries++; //add 1 more to the number of queries executed.
		
		if($friend->rowCount() > 0){
			$reply['status'] = true;
		}else{
			$reply['status'] = false;
			$reply['message'] = $this->lang['cl_friend_6'];
		}
		
		return $reply;
	}
	
	public function get_friendlist($asArray = false){
		if(!$this->config['friend_system']) {
			if($asArray){
				return array();
			}else{
				return false;
			}
		}
		
		$friend_array = array();
		
		if($this->permissions['friends_view']){
			$get_friends = $this->sql->prepare('SELECT
													u.username,
													u2.username as req_from,
													u2.id as req_id,
													u.id,
													f.status,
													f.date
												FROM
													friends as f
												INNER JOIN
													members as u
													ON u.id = f.friend_id
												LEFT JOIN
													members as u2
													ON u2.id = f.user_id
													AND
													f.user_id <> ?
												WHERE
													f.user_id = ?
												OR
													f.friend_id = ?
													AND
													f.status = "pending"');
			$my_id = $this->sanitize($this->uid, 'integer');
			$get_friends->execute(array($my_id,$my_id,$my_id));
			$this->queries++;
			
			if($get_friends->rowCount() > 0){
				while($friend = $get_friends->fetch(PDO::FETCH_ASSOC)){
					$status_dav = $friend['status']=='accepted'?'aceptado':'pendiente';
					if($asArray){
						$friend_array[] = $friend;
					}else{
						if($friend['status'] == 'pending' && $friend['username'] == $this->username){
							echo '<tr id="flr_'.$friend['req_id'].'">
									<td class="friend_name"><a href="profile.php?user='.$friend['req_from'].'">'.$friend['req_from'].'</a></td>
									<td class="'.($friend['status'] == 'accepted' ? 'friend_accepted' : 'friend_pending').'">
										<a href="#" title="'.$this->lang['cl_friend_7'].' '.date('jS M Y', $friend['date']).'">'.$status_dav.'</a>
									</td>
									<td>
										<a title="'.$this->lang['cl_friend_8'].'" href="profile.php?user='.$friend['req_from'].'" class="pop_box" onclick="">
											<img src="images/icons/glyphicons_003_user.png" alt="">
										</a>
										<a title="'.$this->lang['cl_friend_9'].'" href="#" class="btn_remove_friend" name="'.$friend['req_id'].'" name="Band ring">
											<img src="images/icons/glyphicons_207_remove_2.png" alt="">
										</a>
										<a title="'.$this->lang['cl_friend_10'].'" href="#" class="btn_add_friend approve_friend" name="'.$friend['req_id'].'" name="Band ring">
											<img src="images/icons/glyphicons_206_ok_2.png" alt="">
										</a>
									</td>
								</tr>';
						}else{
							echo '<tr id="flr_'.$friend['id'].'">
									<td class="friend_name"><a href="profile.php?user='.$friend['username'].'">'.$friend['username'].'</a></td>
									<td class="'.($friend['status'] == 'accepted' ? 'friend_accepted' : 'friend_pending').'">
										<a href="#" title="'.$this->lang['cl_friend_11'].' '.date('jS M Y', $friend['date']).'">'.$status_dav.'</a>
									</td>
									<td>
										<a title="'.$this->lang['cl_friend_12'].'" href="profile.php?user='.$friend['username'].'" class="pop_box" onclick="">
											<img src="images/icons/glyphicons_003_user.png" alt="">
										</a>
										<a title="'.$this->lang['cl_friend_13'].'" href="#" class="btn_remove_friend" name="'.$friend['id'].'" name="Band ring">
											<img src="images/icons/glyphicons_207_remove_2.png" alt="">
										</a>
									</td>
								</tr>';
						}
					}
				}
			}else{
				if(!$asArray){
					echo '<tr">
							<td colspan="3" style="text-align:center;">'.$this->lang['cl_friend_14'].'</td>
						</tr>';
				}
			}
			$get_friends->closeCursor();
			
		}else{
			if(!$asArray){
				echo '<tr">
						<td colspan="3" style="text-align:center;">'.$this->lang['cl_friend_15'].'</td>
					</tr>';
			}
		}
		
		if($asArray){
			return $friend_array;
		}
	}
}