<?php

    set_time_limit(0);

    if(!is_numeric($_SERVER['argv'][1])){
       die();
    }

    $num = $_SERVER['argv'][1];

    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'/';
    
    for ($i = 0; $i < 100; $i++) {
        
        exec('php-cli '.$folder_path.'call_cassandra_2.php '.($i+$num).' > '.$log_path.'call_cassandra_1.log 2>&1 &');
        
    }
