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
 
class Caching extends Sessions{
	private $cache_directory;
	protected $generated_unix;
	
	public function __construct(){
		parent::__construct();
		if($this->config['cache_dir'][0] != '/'){
			$this->config['cache_dir'] = '/'.$this->config['cache_dir'];
		}
		$this->cache_directory = $this->config['cache_dir'];
	}
	
	private function cache_exists($file_name){
		$cache_dir = $this->cache_directory;
		if(!file_exists($cache_dir)){
			!@mkdir($cache_dir, $this->config['permission_writeable']);
			!@chmod($cache_dir, $this->config['permission_writeable']);
		}
		
		return $cache_dir;
	}
	
	public function cache($to_file, $output, $refresh = true){
		if(!$this->config['cache_enabled'] && $refresh){
			return false;
		}
		
		$dir = $this->cache_exists($to_file);
		
		$cache_ready_output = '<?php';
		$cache_ready_output .= "\n";
		$cache_ready_output .= '$generated_time = '.time().';';
		$cache_ready_output .= "\n";
		$cache_ready_output .= '$output = \''.addslashes($output).'\';';
		
		$to_file = $to_file.'.php';
		$theFile = fopen($dir.'/'.$to_file, 'w');
		$error = @fwrite($theFile, $cache_ready_output);
		fclose($theFile);
		if(!$error){
			return false;
		}
		return true;
	}
	
	public function cache_show($from_file, $time = null, $refresh = true){
		if(!$this->config['cache_enabled'] && $refresh){
			return '';
		}
		
		if($time == null){
			$time = $this->config['cache_time'];
		}
		$dir = $this->cache_directory;
		$from_file = $from_file.'.php';
		
		
		if(file_exists($dir.'/'.$from_file)){
			if(filesize($dir.'/'.$from_file) > 0){
				require_once($dir.'/'.$from_file);
				$this->generated_unix = $generated_time;
				if(!$refresh || $generated_time > time()-$time){
					return stripslashes($output);
				}
			}
		}
		
		return '';
	}
}