<?php require_once('../config.php');
require_once('set_access_token_functions.php');

function getAccessToken_function(){
    
    $access_token = array();
    
    $accounts = cassandra_query('SELECT email, pass, accountid, pageid, pagename FROM facebook_access_token_account;');
    
    foreach($accounts as $fila){
        
        $accounts_tokens = cassandra_query("SELECT access_token, accountid, date_out FROM facebook_access_token WHERE accountid = '".$fila['accountid']."';");
        
        foreach($accounts_tokens as $fila2){
            
            if($fila2['date_out'] - date('U') > 0){
                $access_token[] = array('access_token' => $fila2['access_token'], 'accountId' => $fila2['accountid'], 'pageId' => $fila['pageid'], 'pageName' => $fila['pagename']);
            }
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