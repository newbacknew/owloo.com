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

class Profiles extends Forum{
	public $p_fields = array();
	public $p_msg = '';
	
	protected function fetchAllFields(){
		if(empty($this->p_fields)){
			$fields = array();
			foreach($this->sql->query('SELECT * FROM profile_fields ORDER BY p_group') as $field){
				$fields[$field['p_id']] = $field;
			}
			$this->p_fields = $fields;
		}
	}
	
	protected function generateProfileFields($pop = false, $placeholder = false, $admin_edit = false){
		
		global $isExplorerLegacy;
		
		$this->fetchAllFields();
		
		if(!empty($this->p_fields)){
			$groups = array();
			
			if($pop){
				if(!empty($_REQUEST['user'])){
					$uid = $this->sanitize($_REQUEST['user'],'purestring');
					$query = 'SELECT p.* FROM members as u LEFT JOIN profiles as p ON p.u_id = u.id WHERE u.username = ?';
				}else{
					$uid = $this->sanitize($this->uid,'integer');
					$query = 'SELECT * FROM profiles WHERE u_id = ?';
				}
				
				if($admin_edit){
					if(!empty($_REQUEST['uid'])){
						$uid = $this->sanitize($_GET['uid'],'integer');
					}else{
						$pop = false;
					}
				}
				$stmt = $this->sql->prepare($query);
				$stmt->execute(array($uid));
				
				$fields = array();
				while($pfield = $stmt->fetch(PDO::FETCH_ASSOC)){
					$fields[$pfield['f_id']] = $pfield['f_val'];
				}
				$user_fields = $fields;
				
			}
			
			foreach($this->p_fields as $p_id => $field){			
				switch($field['p_type']){
					case 0:
						$p_field = '<textarea class="input-large" name="p_field['.$p_id.']" rows="6">'.(!empty($user_fields) && !empty($user_fields[$p_id]) ? $user_fields[$p_id] : '').'</textarea>';
						$groups[$field['p_group']][] = array('id'=>$p_id,
															'label'=>$field['p_label'], 
															'field'=>$p_field,
															'icon'=>'icon-align-justify',
															'value'=>(!empty($user_fields) && !empty($user_fields[$p_id]) ? $user_fields[$p_id] : ''),
															'profile'=>$field['p_profile'], 
															'signup' => $field['p_signup']
															);
						break;
						
					case 1:
						$p_field = ($isExplorerLegacy)?'<div class="not_placeholder">'.strtolower($field['p_label']).'</div>':'';
						$p_field .= '<input '.($placeholder ? 'placeholder="'.strtolower($field['p_label']).'"':'').' type="text" name="p_field['.$p_id.']" value="'.(!empty($user_fields) && !empty($user_fields[$p_id]) ? $user_fields[$p_id] : '').'" />';
						$groups[$field['p_group']][] = array('id'=>$p_id,
															'label'=>$field['p_label'], 
															'field'=>$p_field, 
															'icon'=>'icon-italic',
															'type'=>$field['p_type'],
															'value'=>(!empty($user_fields) && !empty($user_fields[$p_id]) ? $user_fields[$p_id] : ''),
															'profile'=>$field['p_profile'], 
															'signup' => $field['p_signup']
															);
						break;
						
					case 2:
						$p_field = '<label class="checkbox inline"><input type="checkbox" name="p_field['.$p_id.']" '.(!empty($user_fields) && !empty($user_fields[$p_id]) ? 'checked="checked"' : '').'/>'.$field['p_label'].'</label>';
						$groups[$field['p_group']][] = array('id'=>$p_id,
															'label'=>$field['p_label'], 
															'field'=>$p_field, 
															'type'=>$field['p_type'],
															'value'=>(!empty($user_fields) && !empty($user_fields[$p_id]) ? $user_fields[$p_id] : ''),
															'profile'=>$field['p_profile'], 
															'signup' => $field['p_signup']
															);
						break;
						
					case 3:
						$p_field = '';
						foreach(explode(',', $field['p_options']) as $option){
							$p_field .= '<label class="radio inline"><input type="radio" name="p_field['.$p_id.']" value="'.$option.'" '.(!empty($user_fields) && !empty($user_fields[$p_id]) && $user_fields[$p_id] == $option ? 'checked="checked"' : '').' /> '.$option.'</label>';
						}
						
						$groups[$field['p_group']][] = array('id'=>$p_id,
															'label'=>$field['p_label'], 
															'field'=>$p_field,
															'type'=>$field['p_type'],
															'value'=>(!empty($user_fields) && !empty($user_fields[$p_id]) ? $user_fields[$p_id] : ''),
															'options'=>$field['p_options'],
															'profile'=>$field['p_profile'], 
															'signup' => $field['p_signup']
															);
						break;
						
					case 4:
						$p_field = ($isExplorerLegacy)?'<div class="not_placeholder">'.strtolower($field['p_label']).'</div>':'';
						$p_field .= '<select name="p_field['.$p_id.']">';
						$p_field .= '<option value="">seleccionar '.strtolower($field['p_label']).'</option>';
						foreach(explode(',', $field['p_options']) as $option){
							$p_field .= '<option '.((trim($option) == "Venezuela") ? "' style='margin-bottom:15px'" : "").' value="'.$option.'" '.(!empty($user_fields) && !empty($user_fields[$p_id]) && $user_fields[$p_id] == $option ? 'selected="selected"' : '').'>'.$option.'</option>';
						}
						$p_field .= '</select>';
						
						$groups[$field['p_group']][] = array('id'=>$p_id,
															'label'=>$field['p_label'], 
															'field'=>$p_field,
															'icon'=>'icon-arrow-down',
															'type'=>$field['p_type'],
															'value'=>(!empty($user_fields) && !empty($user_fields[$p_id]) ? $user_fields[$p_id] : ''),
															'options'=>$field['p_options'],
															'profile'=>$field['p_profile'], 
															'signup' => $field['p_signup']
															);
						break;
				}
			}
			
			return $groups;
		}
	}
	
	public function showEditProfileFields($admin_edit = false){
		$groups = $this->generateProfileFields(true, null, $admin_edit);
		$output = '';
		if(!empty($groups)){
			$output = '<form id="edit_profile_fields" class="form-horizontal" method="post" action="#">';
			foreach($groups as $group => $fields){
				//$output .='<legend>'.$group.'</legend>';
				
				foreach($fields as $field){
					$output .='<div class="control-group">
									<label class="control-label">'.$field['label'].($field['signup'] == 1 ? '*' : '').'</label>
									<div class="controls">'.$field['field'].'</div>
								</div>';
				}
			}
			$output .= '
			<div class="form-actions" style="padding-left: 0;text-align: center;">
				<button class="owloo_btn owloo_btn_blue" id="submit_profile_changes">'.$this->lang['cl_profiles_1'].'</button>
			</div>
			<input type="hidden" name="upd_profile" value="" />
			'.($admin_edit ? '<input type="hidden" name="userid" value="'.(!empty($_GET['uid']) ? $this->sanitize($_GET['uid'], 'integer') : '').'" />' : '').'
			</form>';
		}
		return $output;
	}
	
	## Format the ending of a string ##
	public function get_profile($usr = null, $how = 'username'){
		## Check if a username or id has been supplied when calling the function.
		## If so, we simply sanitize this value, based on the $how value.		
		if(!empty($usr)){
			if($how == 'username'){
				$usr = $this->sanitize($usr, 'string');
				$qury = 'u.username=?';
			}else{
				$usr = $this->sanitize($usr, 'integer');
				$qury = 'u.id=?';
			}
		}else{
			## If no value is submitted via the $_GET variable, we just retrive the profile data of the user who is viewing the page ##
			if($how == 'username'){
				$usr = (!empty($_GET['user']) ? $this->sanitize($_GET['user'], 'string') : $this->sanitize($this->username, 'string'));
				$qury = 'u.username=?';
			}else{
				$usr = (!empty($_GET['user']) ? $this->sanitize($_GET['user'], 'integer') : $this->sanitize($this->uid, 'integer'));
				$qury = 'u.id=?';
			}
		}
		
		$conditions = array($this->uid, $usr);
		
		$get_details = $this->sql->prepare('SELECT
												u.id,
												mg.name as usergroup,
												u.avatar, 
												u.username,
												u.email,
												u.membergroup as primary_grp,
												u.other_membergroups as other_grps,
												f.status,
												(SELECT f_val FROM profiles WHERE u_id = u.id AND f_id = 1) nombre,
												(SELECT f_val FROM profiles WHERE u_id = u.id AND f_id = 2) pais
											FROM 
												members as u
											INNER JOIN
												member_groups mg
												ON
													mg.id = u.membergroup
											LEFT JOIN
												friends as f
												ON
													f.friend_id = u.id
												AND
													f.user_id = ?
											WHERE 
												'.$qury);
		
		$get_details->execute($conditions);
		$this->queries++;
		
		$profile_data = array('user_found' 	=> false,
							  'user_id' 	=> '',
							  'user_email' 	=> '',
							  'user_username' 	=> $this->lang['cl_profiles_2'],
							  'user_usergroup' 	=> '',
							  'user_avatar' => $this->base_url.'/images/default_big.png',
							  'user_friend' => '',
							  'nombre' => '',
							  'pais' => ''
							);
							
		if($get_details->rowCount() > 0){
			$profile_data['user_found'] = true;
			while($row = $get_details->fetchAll(PDO::FETCH_ASSOC)){
				$profile_data = array('user_found' 			=> true,
									  'user_id' 			=> $row[0]['id'],
									  'user_email' 			=> $row[0]['email'],
									  'user_username' 		=> $row[0]['username'],
									  'user_usergroup' 		=> $row[0]['usergroup'],
									  'user_primarygroup'  	=> $row[0]['primary_grp'],
									  'user_othergroups' 	=> (!empty($row[0]['other_grps']) ? explode(',', $row[0]['other_grps']) : array()),
									  'user_avatar'        => ($row[0]['avatar'] != null ? $row[0]['avatar'] : ''),
									  'user_friend' 		=> ($row[0]['status'] == null ? false : $row[0]['status']),
                                      'nombre' => $row[0]['nombre'],
                                      'pais' => $row[0]['pais']
									);
			}
		}
		$get_details->closeCursor();
		
		return $profile_data;
	}

    public function get_user_avatar($email){
                  
        $conditions = array($email);
        
        $get_details = $this->sql->prepare('SELECT
                                                u.avatar
                                            FROM 
                                                members as u
                                            WHERE 
                                                u.email=?');
        
        $get_details->execute($conditions);
        $this->queries++;
        
        $profile_data = array(
                              'user_avatar' => ''
                            );
                            
        if($get_details->rowCount() > 0){
            $profile_data['user_found'] = true;
            while($row = $get_details->fetchAll(PDO::FETCH_ASSOC)){
                
                $profile_data = array('user_avatar'        => ($row[0]['avatar'] != null ? $row[0]['avatar'] : '')
                                    );
            }
        }
        $get_details->closeCursor();
        
        return $profile_data;
    }
	
	## Format the ending of a string ##
	public function format_ending($str){
		## Eg. if the username is "Jonas" it will return "Jonas'" ##
		## If the username is "Mark" it will return "Mark's" ##
		if(strtolower(substr($str, -1, 1)) != 's'){
			$str .= '\'s';
		}else{
			$str .= '\'';
		}
		
		return $str;
	}
	
	public function process_profile(){
		if(isset($_POST['upd_profile']) && $_POST['upd_profile'] == '' && empty($_POST['userid'])){
			if(!$this->loggedin){
				exit;
			}
			$query = '';
			$data = array($this->sanitize($this->uid, 'integer'));
			$this->fetchAllFields();
			
			foreach($this->p_fields as $id => $field){
				//check if the any required fields are not missing/left empty
				if(empty($_POST['p_field'][$id]) && $field['p_signup'] == 1){
					return array('status'=> false, 'message'=>$this->lang['cl_profiles_3']);
				}
				$query .= (!empty($query) ? ',' : '').'(?,?,?)';
				$data[] .= $this->sanitize($this->uid, 'integer');
				$data[] .= $this->sanitize($id, 'integer');
				$data[] .= $this->sanitize(strip_tags($_POST['p_field'][$id]), 'string');
			}
			
			if(!empty($query)){
				$stmt = $this->sql->prepare('DELETE FROM profiles WHERE u_id = ?; INSERT INTO profiles VALUES '.$query);
				$stmt->execute($data);
				$stmt->closeCursor();
				
				return array('status'=> true, 'message'=>$this->lang['cl_profiles_4']);
			}
		}
	}
	
	public function admin_process_profile(){
		if(isset($_POST['upd_profile']) && $_POST['upd_profile'] == '' && !empty($_POST['userid'])){
			if(!$this->loggedin || empty($this->permissions['admin']) || !$this->permissions['admin']){
				header("Location: ".$this->base_url);
				exit;
			}
			
			$_POST['userid'] = $this->sanitize($_POST['userid'],'integer');
			
			$query = '';
			$data = array($this->sanitize($_POST['userid'], 'integer'));
			$this->fetchAllFields();
			
			foreach($this->p_fields as $id => $field){
				//check if the any required fields are not missing/left empty
				if(empty($_POST['p_field'][$id]) && $field['p_signup'] == 1){
					return array('status'=> false, 'message'=>$this->lang['cl_profiles_5']);
				}
				$query .= (!empty($query) ? ',' : '').'(?,?,?)';
				$data[] .= $this->sanitize($_POST['userid'], 'integer');
				$data[] .= $this->sanitize($id, 'integer');
				$data[] .= $this->sanitize(strip_tags($_POST['p_field'][$id]), 'string');
			}
			
			if(!empty($query)){
				$stmt = $this->sql->prepare('DELETE FROM profiles WHERE u_id = ?; INSERT INTO profiles VALUES '.$query);
				$stmt->execute($data);
				$stmt->closeCursor();
				
				return array('status'=> true, 'message'=>$this->lang['cl_profiles_6']);
			}
		}
	}
	
	public function setNotification($where, $body, $title = 'Error', $type = 'error'){
		$_SESSION['noti'][$where] = '<div class="alert alert-'.$type.'"><button type="button" class="close" data-dismiss="alert">&#215;</button><strong>'.$title.'</strong> '.$body.'</div>';
	}
	
	public function showNotification($where){
		if(!empty($_SESSION['noti'][$where])){
			$msg = $_SESSION['noti'][$where];
			unset($_SESSION['noti'][$where]);
			return $msg;
		}
	}
	
	public function generateProfile(){
		$groups = $this->generateProfileFields(true);
		$output = '';
		
		if(!empty($groups)){
			foreach($groups as $group => $fields){
				$title = '';
				foreach($fields as $field){
					if($field['profile']){
						if($group !== $title){
							$output .='<legend>'.$group.'</legend>';
							$title = $group;
						}
						
						if(!empty($field['value'])){
							$output .='<div><label><b>'.$field['label'].'</b></label><span>'.$field['value'].'</span></div><hr>';
						}
					}
				}
			}
		}
		
		return $output;
	}
	
	public function profileSignupFields(){
		$groups = $this->generateProfileFields(null, true);
		$output = '';
		
		if(!empty($groups)){
			foreach($groups as $group => $fields){
				$title = '';
				foreach($fields as $field){
					if($field['signup'] < 2){
						if($group !== $title){
							$output .='<legend>'.$group.'</legend>';
							$title = $group;
						}
						
						$output .='<div class="input-prepend">
										'.(!empty($field['icon']) ? '' : '').'
										'.$field['field'].'
									</div>';
					}
				}
			}
		}
		$output .= '';
		return $output;
	}
	
	public function showTerms(){
		if($this->config['termsrequired']){
			return '<hr />
					<label class="checkbox">
						<input type="checkbox" name="signup_iagree" />
						<span class="owloo_terms_text">Al registrarte acepta nuestros <a data-toggle="modal" href="#termsconditions">'.$this->lang['cl_profiles_7'].'</a></span>
					</label>';
		}
	}
}