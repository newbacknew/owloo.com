<?php
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'logs/';
    
    exec('php-cli '.$folder_path.'call_record_country.php > '.$log_path.'call_record_country.log 2>&1 &');
    die();
