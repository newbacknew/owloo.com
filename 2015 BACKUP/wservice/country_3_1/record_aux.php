<?php
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'logs/';
    
    error_log('Type: '.$_GET['type']);
    
    if(!isset($_GET['type'])){
        die();
    }
    
    exec('php-cli '.$folder_path.'call_record_country_aux.php '.$_GET['type'].' > '.$log_path.'call_record_country_aux.log 2>&1 &');
    
    die();
