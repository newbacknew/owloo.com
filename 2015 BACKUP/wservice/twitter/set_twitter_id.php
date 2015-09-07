<?php

    require_once (__DIR__.'/../config.php');
    
	$query = 'SELECT owloo_user_id, owloo_screen_name FROM `twitter_user_master` WHERE owloo_user_twitter_id = \'0\' ORDER BY 1;';
    $que = db_query_tw($query, array());
    
    while($fila = mysql_fetch_assoc($que)){
        
        /***** 1 *****/
        /*$data = get_url_content('http://mytwitterid.com/api/?screen_name='.$fila['owloo_screen_name']);
        $data = json_decode($data, true);
        
        if(isset($data[0]['id_str'])){
            
            if(strtolower($data[0]['screen_name']) == strtolower($fila['owloo_screen_name'])){
                $query = 'UPDATE `twitter_user_master` SET owloo_user_twitter_id = \'$1\' WHERE owloo_user_id = $2;'; 
                $result = db_query_tw($query, array($data[0]['id_str'], $fila['owloo_user_id']), true);
            }
            
        }*/
        
        /***** 2 *****/
        
        $data = get_url_content('http://www.idfromuser.com/getID.php?service=twitter&username='.$fila['owloo_screen_name']);
        
        if(!empty($data)){
            
            $query = 'UPDATE `twitter_user_master` SET owloo_user_twitter_id = \'$1\' WHERE owloo_user_id = $2;'; 
            $result = db_query_tw($query, array($data, $fila['owloo_user_id']), true);
                
        }else{
            echo 'Die: '.$fila['owloo_screen_name']; die();
        }
	}