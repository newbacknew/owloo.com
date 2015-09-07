<?php
    
    set_time_limit(0);

    /*require_once('../../config.php');
    
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'/';
            
    for ($i = 0; $i < 10; $i++) {
        
        exec('php-cli '.$folder_path.'call_cassandra_1.php '.($i*100).' > '.$log_path.'call_cassandra.log 2>&1 &');
        
    }*/
    
    $mh = curl_multi_init();
    $handles = array();
    
    for ($i = 0; $i < 20; $i++) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://www.owloo.com/wservice/country_cassandra/test/curl/call_cassandra_1.php");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,array('num' => ($i*100)));
        curl_multi_add_handle($mh, $ch);
        $handles[] = $ch;
    }
    
    $running=null;
    
    do{
        curl_multi_exec($mh, $running);
        
    }
    while ($running > 0);
    
    curl_multi_close($mh);