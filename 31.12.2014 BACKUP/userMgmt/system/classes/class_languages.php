<?php
/*****************************************************************************************
 * Solid PHP User Management System														 *
 * Copyright 2012 Mark Eliasen (MrEliasen)												 *
 *																						 *
 * CodeCanyon Link: http://codecanyon.net/item/solid-php-user-management-system-/1254295 *
 * Author Website: http://zolidweb.com													 *
 * Version: 1.4.0 																		 *
 *****************************************************************************************/
 
if(!defined('IN_SYSTEM')){
	exit;
}

class Languages extends UserUploads {
	public $default_lang = 'eng';
	public $cur_lang = null;
	public $lang = null;
	
	function __construct(){
		parent::__construct();
		$this->default_lang = $this->config['default_lang'];
		
		if(!empty($_COOKIE['lang'])){
			$this->cur_lang = $this->sanitize($_COOKIE['lang'], 'string');
		}
		
		$this->load_language();
	}
	
	protected function load_language(){
		$this->change_language();
		
		if(file_exists(SYSTEM_PATH.'/lang/lang_'.$this->cur_lang.'.php')){
			$load_lang = $this->cur_lang;
		}else{
			if(file_exists(SYSTEM_PATH.'/lang/lang_'.$this->default_lang.'.php')){
				$load_lang = $this->default_lang;
			}else{
				exit($this->lang['cl_lang_1'].' <b>'.$this->default_lang.'</b>. '.$this->lang['cl_lang_2']);
			}
		}
		
		require_once(SYSTEM_PATH.'/lang/lang_'.$load_lang.'.php');
		if(!empty($lang)){
			$this->lang = $lang;
		}
	}
	
	public function change_language($lang = null){
		if(!empty($_POST['change_lang']) || !empty($lang)){
			$_POST['change_lang'] = $this->sanitize($_POST['change_lang'], 'string');
			
			if(!file_exists(SYSTEM_PATH.'/lang/lang_'.$_POST['change_lang'].'.php')){
				return false;
			}
			
			setcookie("lang",$_POST['change_lang'],time()+60*60*24*30);
			$this->cur_lang = $_POST['change_lang'];
		}
	}
	
	public function show_change_lang($standalone = false){
		$output = '';
		
		if($standalone){
			$output .='<form class="form-inline" method="post" action="#">';
		}
		$output .= '<select name="change_lang">';
			
			foreach(scandir(SYSTEM_PATH.'/lang') as $lng){
				if($lng !=  '.' && $lng !=  '..'){
					include(SYSTEM_PATH.'/lang/'.$lng);
					$lng = str_replace(array('lang_', '.php'), '', $lng);
					$output .= '<option '.($this->cur_lang == $lng || empty($this->cur_lang)  ? 'selected="selected"' : '').' value="'.$lng.'">'.$lang['title'].'</option>';
				}
			}
			
		$output .= '</select>';
		
		if($standalone){
			$output .='&nbsp;<button type="submit" class="btn">Change Language</button>';
			$output .='</form>';
		}
		
		return $output;
	}
}