<?php
    set_time_limit(300);
    
    if(!is_numeric($_SERVER['argv'][1]) || !is_numeric($_SERVER['argv'][2]) || !is_numeric($_SERVER['argv'][3]) || $_SERVER['argv'][5] != 'c45a5f3b2cfa74ac94bd5bbfb2c5d6a5'){
        die();
    }
    
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'logs/';
    
    $comportamientos = json_decode(urldecode($_SERVER['argv'][4]), true);
    $total_comportamiento = count($comportamientos);
    
    foreach($comportamientos as $fila){
        exec('php-cli '.$folder_path.'record_city_comportamiento.php '.$_SERVER['argv'][1].' '.$_SERVER['argv'][2].' '.($_SERVER['argv'][3]*$total_comportamiento).' '.$fila['id'].' '.$fila['key'].' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'record_city_comportamiento.log 2>&1 &');   
        usleep(rand(0, 100000));
    }
    
    die();
