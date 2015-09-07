<?php
    
    set_time_limit(300);
    
    if(!is_numeric($_SERVER['argv'][1]) || !is_numeric($_SERVER['argv'][2]) || $_SERVER['argv'][5] != 'c45a5f3b2cfa74ac94bd5bbfb2c5d6a5'){
        die();
    }

    require_once(__DIR__.'/../config.php');
    
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'logs/';
    
    //Ciudades
    $query = 'SELECT id_city, key_city FROM `facebook_city_3_1` WHERE id_country = '.$_SERVER['argv'][1].' AND active_fb_get_data = 1 ORDER BY 1;'; 
    $que = db_query($query, array());
    
    $total_city = $_SERVER['argv'][2];
    
    $data = $_SERVER['argv'][4];
    if($_SERVER['argv'][3]=='call_record_city_interest'){
        
        $query = 'SELECT id_interest id, key_interest \'key\' FROM `facebook_interest_3_1` WHERE id_interest IN (SELECT id_interest FROM facebook_interest_city_3_1 WHERE id_country = $1) AND active_fb_get_data = 1 ORDER BY 1;';
        $result = db_query($query, array($_SERVER['argv'][1]));
        
        $interests = array();
        while($fila = mysql_fetch_assoc($result)){
            $interests[] = $fila;
        }
        $interests = json_encode($interests);
        $data = urlencode($interests);
    }
    
    while($fila = mysql_fetch_assoc($que)){
        exec('php-cli '.$folder_path.$_SERVER['argv'][3].'.php '.$fila['id_city'].' '.$fila['key_city'].' '.$total_city.' '.$data.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.$_SERVER['argv'][3].'.log 2>&1 &');
        usleep(rand(0, 100000));
    }
    
    die();