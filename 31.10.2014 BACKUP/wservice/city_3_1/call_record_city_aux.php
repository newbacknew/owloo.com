<?php
    
    set_time_limit(300);
    
    if(!is_numeric($_SERVER['argv'][1]) || !is_numeric($_SERVER['argv'][2]) || !is_numeric($_SERVER['argv'][4]) || $_SERVER['argv'][5] != 'c45a5f3b2cfa74ac94bd5bbfb2c5d6a5'){
        die();
    }
    
    function get_sql_in($sql){
        $result = db_query($sql, array());
        $data = array();
        $data[] = 0;
        while($fila = mysql_fetch_assoc($result)){
            $data[] = $fila['id_city'];
        }
        return implode(',', $data) ;
    }
    
    function get_data_for_sql_in($id_country, $table, $num = null){
        if($table == 'city'){
            $query = 'SELECT id_city FROM facebook_city_3_1 WHERE id_country = '.$id_country.' AND id_city NOT IN('.get_sql_in("SELECT id_city FROM `facebook_record_city_3_1` WHERE date = DATE(NOW())").');';
        }
        else {
            $query = 'SELECT id_city FROM facebook_city_3_1 WHERE id_country = '.$id_country.' AND id_city NOT IN('.get_sql_in('SELECT id_city FROM `facebook_record_'.$table.'_3_1` WHERE date = DATE(NOW()) GROUP BY id_city HAVING COUNT(*) = '.$num).');';
        }
        $result = db_query($query, array());
        $data = array();
        $data[] = 0;
        while($fila = mysql_fetch_assoc($result)){
            $data[] = $fila['id_city'];
        }
        return implode(',', $data) ;
    }
    
    function get_data_id_secundary_for_sql_in($id_city, $column){
        $query = 'SELECT id_$1 id FROM `facebook_record_city_$1_3_1` WHERE id_city = $2 AND date = DATE(NOW())';
        $result = db_query($query, array($column, $id_city));
        $data = array();
        $data[] = 0;
        while($fila = mysql_fetch_assoc($result)){
            $data[] = $fila['id'];
        }
        return implode(',', $data) ;
    }

    require_once(__DIR__.'/../config.php');
    
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'logs/';
    
    //Ciudades
    $table = str_replace('call_', '', str_replace('record_', '', $_SERVER['argv'][3]));
    $query = 'SELECT id_city, key_city FROM `facebook_city_3_1` WHERE id_city IN('.get_data_for_sql_in($_SERVER['argv'][1], $table, $_SERVER['argv'][4]).') AND active_fb_get_data = 1 ORDER BY 1;'; 
    $que = db_query($query, array());
    
    $total_city = $_SERVER['argv'][2];
    
    while($fila = mysql_fetch_assoc($que)){
        
        $data = array();
        switch($table){
            case 'city_age':
                $query = 'SELECT id_age id, min, max FROM `facebook_age_3_1` WHERE active_fb_get_data = 1 AND id_age NOT IN('.get_data_id_secundary_for_sql_in($fila['id_city'], 'age').') ORDER BY 1;';
                break;
            case 'city_interest':
                $query = "SELECT id_interest id, key_interest 'key' FROM `facebook_interest_3_1` WHERE id_interest IN(SELECT id_interest FROM facebook_interest_city_3_1 WHERE id_country = ".$_SERVER['argv'][1].") AND id_interest NOT IN(".get_data_id_secundary_for_sql_in($fila['id_city'], 'interest').") AND active_fb_get_data = 1 ORDER BY 1;";
                break;
            case 'city_comportamiento':
                $query = "SELECT id_comportamiento id, key_comportamiento 'key' FROM `facebook_comportamiento_3_1` WHERE id_comportamiento IN(SELECT id_comportamiento FROM facebook_mobile_os_city_3_1) AND active_fb_get_data = 1 AND id_comportamiento NOT IN(".get_data_id_secundary_for_sql_in($fila['id_city'], 'comportamiento').") ORDER BY 1;";
                break;
            case 'city_relationship':
                $query = "SELECT id_relationship id, key_relationship 'key' FROM `facebook_relationship_3_1` WHERE active_fb_get_data = 1 AND id_relationship NOT IN(".get_data_id_secundary_for_sql_in($fila['id_city'], 'relationship').") ORDER BY 1;"; 
                break;
        }
        
        if($table != 'city'){
            $result = db_query($query, array());
            while($fila2 = mysql_fetch_assoc($result)){
                $data[] = $fila2;
            }
        }
        $data = json_encode($data);
        
        exec('php-cli '.$folder_path.$_SERVER['argv'][3].'.php '.$fila['id_city'].' '.$fila['key_city'].' '.$total_city.' '.urlencode($data).' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.$_SERVER['argv'][3].'.log 2>&1 &');
        usleep(rand(0, 100000));
    }
    
    die();