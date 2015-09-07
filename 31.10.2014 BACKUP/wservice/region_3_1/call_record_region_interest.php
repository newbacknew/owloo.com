<?php
    set_time_limit(300);
    
    if(!is_numeric($_SERVER['argv'][1]) || !is_numeric($_SERVER['argv'][2]) || !is_numeric($_SERVER['argv'][3]) || !is_numeric($_SERVER['argv'][4]) || !is_numeric($_SERVER['argv'][6]) || $_SERVER['argv'][7] != 'c45a5f3b2cfa74ac94bd5bbfb2c5d6a5'){
        die();
    }
    
    require_once('../config.php');
    
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'logs/';
        
    $interests = json_decode(urldecode($_SERVER['argv'][5]), true);
    $total_interest = $_SERVER['argv'][6];
    
    foreach($interests as $fila){
        
        exec('php-cli '.$folder_path.'record_region_interest.php '.$_SERVER['argv'][1].' '.$_SERVER['argv'][2].' '.($_SERVER['argv'][4]*$total_interest).' '.$fila['id'].' '.$fila['key'].' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'record_region_interest.log 2>&1 &');   
        
        usleep(rand(0, 100000));
        
    }
   
    die();