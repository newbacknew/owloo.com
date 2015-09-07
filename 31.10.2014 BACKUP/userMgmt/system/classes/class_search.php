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

class Search extends Invites{
	public function process_search($data){
		if(!$this->loggedin){
			exit;
		}
		
		if(isset($_SESSION['last_search']) && time()-$_SESSION['last_search'] < $this->config['search_intval']){
			$result['status'] = false;
			$result['message'] = $this->lang['cl_search_1'].' '.($this->config['search_intval']-(time()-$_SESSION['last_search'])).' '.$this->lang['cl_search_2'];
			return $result;
		}else{
			$result['status'] = false;
			$result['message'] = $this->lang['cl_search_3'].' "'.$data['query'].'" '.$this->lang['cl_search_4'];
			$result['query'] = $data['query'];
		}
		
		if(empty($data['query'])){
			$result['message'] = $this->lang['cl_search_5'];
			return $result;
		}
		
		## Check if we should search the memebers table ##
		if(!empty($data['srch_members']) && $data['srch_members']){
			$stmt = $this->sql->prepare('SELECT
												u.id,
												u.username,
												u.avatar,
												g.name as usergroup,
												u.date_registered
											FROM
												members as u
											INNER JOIN
												member_groups as g
												ON
													g.id = u.membergroup
											WHERE
											(	
												u.username LIKE ?
											OR
												u.email LIKE ?
											)
											AND
												u.activation_key = ""
											LIMIT 30');
												
			$stmt->execute(array('%'.$data['query'].'%','%'.$data['query'].'%'));
			$this->queries++;
			if($stmt->rowCount() > 0){
				$result['status'] = true;
				$result['result']['members'] = array();
				while($members = $stmt->fetch(PDO::FETCH_ASSOC)){
					$result['result']['members'][] .= '<tr>
														<td><img src="'.$this->base_url.'/'.(!empty($members['avatar']) ? 'uploads/avatars/s/'.$members['avatar'] : 'images/default_small.png').'" title="'.$members['username'].'" class="srch_avatar" alt="" /></td>
														<td><a href="profile.php?user='.$members['username'].'">'.$members['username'].'</a></td>
														<td>'.date($this->config['dateformat_short'], $members['date_registered']).'</td>
														<td>'.$members['usergroup'].'</td>
													</tr>';
				}
			}
		}
		
		if($result['status']){
			$result['message'] = $this->lang['cl_search_6'];
		}
		
		$_SESSION['last_search'] = time();
		
		return $result;
	}
	
	public function process_autosearch($type, $admin = false, $prefix = false){
		if(!$this->loggedin){
			exit;
		}
		$result = array();
		switch($type){
			case 'users':
				if(!empty($_REQUEST['user'])){					
					if(strlen($_REQUEST['user']) >= 3){
						$_REQUEST['user'] = $this->sanitize($_REQUEST['user'], 'string');
						$stmt = $this->sql->prepare('SELECT
															id,
															username
														FROM
															members
														WHERE
															username LIKE ?
														AND
															activation_key = ""
														LIMIT 10');
															
						$stmt->execute(array('%'.$_REQUEST['user'].'%'));
						$this->queries++;
						$c = 0;
						if($admin){
							$result['users'] = array();
						}
						if($stmt->rowCount() > 0){
							while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
								if($admin){
									$result['users'][$c] = array('name'=>($prefix ? '[usr] ' : '').$row['username'], 'id'=>$row['id']);
									$c++;
								}else{
									$result[] = ($prefix ? '[usr] ' : '').$row['username'];
								}
							}
						}
					}
				}
				break;
			
			case 'membergroups':
				if(!empty($_REQUEST['group'])){					
					$_REQUEST['group'] = $this->sanitize($_REQUEST['group'], 'string');
					$result['groups'] = array();
					$stmt = $this->sql->prepare('SELECT
														id,
														name
													FROM
														member_groups
													WHERE
														name LIKE ?
													OR
														id LIKE ?
													LIMIT 10');
					
					$stmt->execute(array('%'.$_REQUEST['group'].'%','%'.$_REQUEST['group'].'%'));
					$this->queries++;
					if($stmt->rowCount() > 0){
						$result['status'] = true;
						$c = 0;
						while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							$result['groups'][$c] = array('name'=>($prefix ? '[grp] ' : '').$row['name'], 'id'=>$row['id']);
							$c++;
						}
					}
				}
				break;
			
			case 'all':
				if(!empty($_REQUEST['data'])){					
					if(strlen($_REQUEST['data']) >= 1){
						$result['results'] = array();
						$_REQUEST['user'] = $_REQUEST['group'] = $_REQUEST['data'];
						
						$users = $this->process_autosearch('users', true, true);
						$groups = $this->process_autosearch('membergroups', false, true);
						
						$result['results'] = array_merge($result['results'], $users['users']);
						$result['results'] = array_merge($result['results'], $groups['groups']);
					}
				}
				break;
		}
		return $result;
	}
	
	public function generatePagination($total, $currentPage, $hash = null, $limit = 10){
		$currentPage = intval($this->sanitize($currentPage, 'integer'));
		$currentPage = ($currentPage < 1 || !is_int($currentPage) ? 1 : $currentPage);
		$hash = (!empty($hash) ? '&tab='.$hash : '');
		$c = 1;
		$low_filler = false;
		$mid_fillers = 0;
		$high_filler = false;
		$output = '';
		$tab = str_replace($this->config['base_url'].'/', '', $_SERVER["SCRIPT_NAME"]);
		$tab = substr($tab, strpos($tab, '#'));
		
		$forum = '';
		if(!empty($_GET['topic'])){
			$forum = '&topic='.$this->sanitize($_GET['topic'], 'integer');
		}
		
		if($total > $limit){
			for($i = 1; $i <= $total; $i+=$limit){
				if($c == 1){
					$output .= '<ul>'.(!empty($currentPage) && $currentPage > 1 ? '<li><a href="?page='.($currentPage-1).$hash.$forum.'">'.$this->lang['cl_search_7'].'</a></li>' : '');
				}
				
				if($c < 3 || $currentPage <= 6 && $c < 10){
					if($c == $currentPage){
						$output .= '<li class="active"><a href="#">'.$c.'</a></li>';
					}else{
						$output .= '<li><a href="?page='.$c.$hash.$forum.'">'.$c.'</a></li>';
					}
				}else if($c+3 < $currentPage && !$low_filler){
					$output .= '<li><a href="#">...</a></li>';
					$low_filler = true;
				
				}else if($low_filler && $mid_fillers < 5 && (($c < $currentPage && $c >= $currentPage-2) || ($c > $currentPage && $c <= $currentPage+2) || $c == $currentPage)){
					if($c == $currentPage){
						$output .= '<li class="active"><a href="#">'.$c.'</a></li>';
					}else{
						$output .= '<li><a href="?page='.$c.$hash.$forum.'">'.$c.'</a></li>';
					}
					$mid_fillers++;
				}else if(($mid_fillers == 5 || $currentPage <= 6) && $total > $currentPage+3 && !$high_filler){
					$output .= '<li><a href="#">...</a></li>';
					$high_filler = true;
					
				}else if($high_filler && $c >= ($total/$limit)-1){
					$output .= '<li><a href="?page='.$c.$hash.$forum.'">'.$c.'</a></li>';
				}
				
				$c++;
			}
			
			$output .= ($currentPage > 0 && $currentPage < $c-1  ? '<li><a href="?page='.($currentPage+1).$hash.$forum.'">'.$this->lang['cl_search_8'].'</a></li>' : '').'</ul>';
		}
		
		return $output;
	}
}