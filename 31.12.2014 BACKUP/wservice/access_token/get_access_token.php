<?php
require_once('set_access_token_functions.php');

function getAccessToken_function(){
	$conexion_token = mysql_connect("localhost", "owloo_admin", "fblatamx244") or die(mysql_error());
	mysql_select_db("owloo_fbAccess", $conexion_token) or die(mysql_error());
	
	$access_token = array();
	
	$query = "SELECT id, email, pass, accountId, pageId, pageName FROM facebook_account ORDER BY 1 ASC;"; 
	$que = mysql_query($query, $conexion_token) or die(mysql_error());
	while($fila = mysql_fetch_assoc($que)){
		$query = "SELECT access_token, accountId, (date_out - UNIX_TIMESTAMP( )) expire FROM access_token WHERE accountId = ".$fila['accountId']." AND (date_out - UNIX_TIMESTAMP( )) > 3600 ORDER BY 3 DESC;"; 
		
        $que2 = mysql_query($query, $conexion_token) or die(mysql_error());
		while($fila2 = mysql_fetch_assoc($que2)){
			$access_token[] = array('access_token' => $fila2['access_token'], 'accountId' => $fila2['accountId'], 'pageId' => $fila['pageId'], 'pageName' => $fila['pageName']);
		}
	}
mysql_close($conexion_token);
	if(count($access_token) > 0)
		return $access_token;
	return false;
}

function getAccessToken(){
	$access_token = getAccessToken_function();
	if(!$access_token){
		setAccessToken();
		$access_token = getAccessToken_function();
	}
	return $access_token;
}
	