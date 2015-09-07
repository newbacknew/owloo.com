<?php
/*****************************************************************************************
 * Solid PHP User Management System														 *
 * Copyright 2012 Mark Eliasen (MrEliasen)												 *
 *																						 *
 * CodeCanyon: http://codecanyon.net/item/solid-php-user-management-system-/1254295		 *
 * Author Website: http://zolidweb.com													 *
 * Version: 1.4.0																		 *
 *****************************************************************************************/
 
if(!defined('IN_SYSTEM')){
	exit;
}

class ErrorHandler{
	public function logError($number = 0, $string, $file = 'Undefined', $line = 0, $context = array()){
		$error_log = '['.date('H:i:s - d/m/Y', time()).']'."\n".'File: '.str_replace(BASE_PATH, '', $file).''."\n".'Line: '.$line.''."\n".'Error: '.$string."\n ------------------------------------------------------------ \n";
		
		try{
			$theFile = @fopen(SYSTEM_PATH.'/errors.log', 'a');
			$error = @fwrite($theFile, $error_log);
			@fclose($theFile);
		}
		catch(PDOException $pe){
			error_log($error_log);
		}
	}
	
	public function showErrLog(){
		$log = 'No system errors.';
		$rows = 2;
		
		if(@file_exists(SYSTEM_PATH.'/errors.log')){
			$log = @file_get_contents(SYSTEM_PATH.'/errors.log');
			$rows = ceil(@filesize(SYSTEM_PATH.'/errors.log')/30);
			
			if($rows > 30){
				$rows = 30;
			}
		}
		
		return '<textarea rows="'.$rows.'" readonly="readonly" class="span8">'.$log.'</textarea>';
	}
	
	public function clearLog($admin = false){
		if(!$admin){
			return false;
		}
		
		if(@file_exists(SYSTEM_PATH.'/errors.log')){
			@unlink(SYSTEM_PATH.'/errors.log');
		}
		
		return true;
	}
}