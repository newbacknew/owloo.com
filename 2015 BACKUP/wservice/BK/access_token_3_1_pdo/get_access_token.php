<?php
require_once('set_access_token_functions.php');

function getAccessToken_function(){
	
	$access_token = array();
	
	$query = 'SELECT id, email, pass, accountId, pageId, pageName FROM facebook_access_token_account_3_1 ORDER BY 1 ASC;';
    $result = db_query($query, array());
    
	while($fila = $result->fetch(PDO::FETCH_ASSOC)){
        $query = 'SELECT access_token, accountId, (date_out - UNIX_TIMESTAMP( )) expire FROM facebook_access_token_3_1 WHERE accountId = $1 AND (date_out - UNIX_TIMESTAMP( )) > 3600 ORDER BY 3 DESC;';
        $result2 = db_query($query, array($fila['accountId']));
		while($fila2 = $result2->fetch(PDO::FETCH_ASSOC)){
			$access_token[] = array('access_token' => $fila2['access_token'], 'accountId' => $fila2['accountId'], 'pageId' => $fila['pageId'], 'pageName' => $fila['pageName']);
		}
	}
    
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
	