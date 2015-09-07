<?php
    set_time_limit(300);
    
    if(!is_numeric($_SERVER['argv'][1]) || !is_numeric($_SERVER['argv'][2]) || !is_numeric($_SERVER['argv'][3]) || $_SERVER['argv'][5] != 'c45a5f3b2cfa74ac94bd5bbfb2c5d6a5'){
        die();
    }
    
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'logs/';
    
    $ages = json_decode(urldecode($_SERVER['argv'][4]), true);
    $total_age = count($ages);
    
    foreach($ages as $fila){
        exec('php-cli '.$folder_path.'record_city_age.php '.$_SERVER['argv'][1].' '.$_SERVER['argv'][2].' '.($_SERVER['argv'][3]*$total_age).' '.$fila['id'].' '.$fila['min'].' '.$fila['max'].' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'record_city_age.log 2>&1 &');
        usleep(rand(0, 100000));
    }
    
    die();
