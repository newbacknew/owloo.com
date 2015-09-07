<?php
    set_time_limit(0);
    
    if(!is_numeric($_SERVER['argv'][1]) || strlen($_SERVER['argv'][2]) != 2 || !is_numeric($_SERVER['argv'][3]) || $_SERVER['argv'][4] != 'c45a5f3b2cfa74ac94bd5bbfb2c5d6a5'){
        die();
    }
    
    require_once('../config.php');
    
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'logs/';
    
    //Audiencia por edad
    
    $ages = cassandra_query('SELECT id_age, min, max FROM facebook_age WHERE active_fb_get_data = 1;');
    
    $total_age = count($ages);
    
    foreach($ages as $fila){
        
        exec('php-cli '.$folder_path.'record_country_age.php '.$_SERVER['argv'][1].' '.$_SERVER['argv'][2].' '.($_SERVER['argv'][3]*$total_age).' '.$fila['id_age'].' '.$fila['min'].' '.$fila['max'].' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'record_country_age.log 2>&1 &');
        
    }