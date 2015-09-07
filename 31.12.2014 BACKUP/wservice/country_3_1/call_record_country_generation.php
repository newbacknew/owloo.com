<?php
    set_time_limit(0);
    
    if(!is_numeric($_SERVER['argv'][1]) || strlen($_SERVER['argv'][2]) != 2 || !is_numeric($_SERVER['argv'][3]) || !is_numeric($_SERVER['argv'][5]) || $_SERVER['argv'][7] != 'c45a5f3b2cfa74ac94bd5bbfb2c5d6a5'){
        die();
    }
    
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'logs/';
    
    $generations = json_decode(urldecode($_SERVER['argv'][4]), true);
    $total_generation = $_SERVER['argv'][5];
    
    foreach($generations as $fila){
        
        exec('php-cli '.$folder_path.'record_country_generation.php '.$_SERVER['argv'][1].' '.$_SERVER['argv'][2].' '.($_SERVER['argv'][3]*$total_generation).' '.$fila['id'].' '.$fila['key'].' '.$_SERVER['argv'][6].' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'record_country_generation.log 2>&1 &');
    
        usleep(rand(0, 100000));
        
    }
    die();
