<?php

    require_once (__DIR__.'/../config.php');
    
    function enviar_informe($total, $before, $after){
        //Enviar aviso por email
        $para = 'mmolinas@latamclick.com';
        $titulo = 'Owloo - Instagram';
        $mensaje = 'Total: '.$total.' - Antes: '.$before.' - Despues: '.$after;
        $cabeceras = 'From: dev@owloo.com' . "\r\n";
        mail($para, $titulo, $mensaje, $cabeceras);
    }
    
    function get_data_for_sql_in($sql, $column){
        $result = db_query($sql, array());
        $data = array();
        $data[] = 0;
        while($fila = mysql_fetch_assoc($result)){
            $data[] = $fila[$column];
        }
        return implode(',', $data) ;
    }

    define('INSTAGRAM_CLIENT_ID', '04e770ce699e44eb80bcf26cf929aa5a');
    define('INSTAGRAM_COUNT_DATA', 100);
    
    function instagram_user_data($user_id){
        $data = get_url_content("https://api.instagram.com/v1/users/$user_id/?client_id=".INSTAGRAM_CLIENT_ID);
        return json_decode($data, true);
    }
    
    $query = 'SELECT id_profile, instagram_id FROM `instagram_profiles`'; 
    $que = db_query($query, array());
    $total_profile = mysql_num_rows($que);
	
	$query = 'SELECT id_profile, instagram_id FROM `instagram_profiles` WHERE id_profile NOT IN('.get_data_for_sql_in('SELECT id_profile FROM `instagram_record` WHERE `date` = DATE(NOW())', 'id_profile').') ORDER BY 1'; 
    $que = db_query($query, array());
    $total_profile_before = mysql_num_rows($que);
    
    while($fila = mysql_fetch_assoc($que)){
		$user_data = instagram_user_data($fila['instagram_id']);
		if($user_data['meta']['code'] == 200){
			$query = 'INSERT INTO instagram_record VALUES($1, $2, $3, $4, NOW());';
			$row = db_query($query, array($fila['id_profile'], $user_data['data']['counts']['media'], $user_data['data']['counts']['followed_by'], $user_data['data']['counts']['follows']), 1);
		}
	}

	$query = 'SELECT id_profile, instagram_id FROM `instagram_profiles` WHERE id_profile NOT IN('.get_data_for_sql_in('SELECT id_profile FROM `instagram_record` WHERE `date` = DATE(NOW())', 'id_profile').') ORDER BY 1'; 
    $que = db_query($query, array());
    $total_profile_after = mysql_num_rows($que);
    
    enviar_informe($total_profile, $total_profile_before, $total_profile_after);
