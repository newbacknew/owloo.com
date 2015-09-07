<?php
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'logs/';
    
    exec('php-cli '.$folder_path.'call_record_region.php > '.$log_path.'call_record_region.log 2>&1 &');