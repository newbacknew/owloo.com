<?php

    require_once (__DIR__.'/../config.php');
    

    function instagram_search_username($username){
        //$username = instagram_clean_username($username);
        $data = get_url_content("https://api.instagram.com/v1/users/search?q=$username&client_id=".INSTAGRAM_CLIENT_ID);
        return json_decode($data, true);
    }
    
	$query = 'SELECT id_profile, username FROM `instagram_profiles` ORDER BY 1;'; 
    $que = db_query($query, array());
    
    while($fila = mysql_fetch_assoc($que)){

        $data = instagram_search_username($fila['username']);

        if($data['meta']['code'] == 200){
            foreach ($data['data'] as $user) {
                if($user['username'] == $fila['username']){
                    
                    $query = 'UPDATE `instagram_profiles` SET instagram_id = $1 WHERE id_profile = $2;'; 
                    $result = db_query($query, array($user['id'], $fila['id_profile']), true);

                    break;
                    
                }
            }
        }
    

	}