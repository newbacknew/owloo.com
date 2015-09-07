<?php
    set_time_limit(0);
    
    if(!is_numeric($_SERVER['argv'][1]) || strlen($_SERVER['argv'][2]) != 2 || !is_numeric($_SERVER['argv'][3]) || $_SERVER['argv'][4] != 'c45a5f3b2cfa74ac94bd5bbfb2c5d6a5'){
        die();
    }
    
    require_once('../config.php');
    
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'logs/';
        
    //Audiencia por interes
    
    $generations = cassandra_query('SELECT id_interest, key_interest FROM facebook_interest WHERE active_fb_get_data = 1;');
    
    $total_interest = count($interests);
    
    foreach($interests as $fila){
        
        exec('php-cli '.$folder_path.'record_country_interest.php '.$_SERVER['argv'][1].' '.$_SERVER['argv'][2].' '.($_SERVER['argv'][2]*$total_interest).' '.$fila['id_interest'].' '.$fila['key_interest'].' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'record_country_interest.log 2>&1 &');   
        
    }
   
    