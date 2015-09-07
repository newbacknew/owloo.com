<?php
    
    set_time_limit(0);

    require_once('../../config.php');
    
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'/';
            
    for ($i = 0; $i < 10; $i++) {
        
        exec('php-cli '.$folder_path.'call_cassandra_1.php '.($i*100).' > '.$log_path.'call_cassandra.log 2>&1 &');
        
        if (($i+1) % 20 == 0)
            sleep(20);
        
        
    }