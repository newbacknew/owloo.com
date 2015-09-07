<?php
    set_time_limit(0);
    
    if(!is_numeric($_SERVER['argv'][1]) || strlen($_SERVER['argv'][2]) != 2 || !is_numeric($_SERVER['argv'][3]) || $_SERVER['argv'][4] != 'c45a5f3b2cfa74ac94bd5bbfb2c5d6a5'){
        die();
    }
    
    require_once('../config.php');
    
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'logs/';
    
    //Audiencia por idioma
    
    $languages = cassandra_query('SELECT id_language, key_language FROM facebook_language WHERE active_fb_get_data = 1;');
    
    $total_language = count($languages);
    
    foreach($languages as $fila){
        
        exec('php-cli '.$folder_path.'record_country_language.php '.$_SERVER['argv'][1].' '.$_SERVER['argv'][2].' '.($_SERVER['argv'][3]*$total_language).' '.$fila['id_language'].' '.$fila['key_language'].' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'record_country_language.log 2>&1 &');
        
    }