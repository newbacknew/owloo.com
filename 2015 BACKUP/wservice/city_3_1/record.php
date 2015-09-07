<?php
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'logs/';
    
    exec('php-cli '.$folder_path.'call_country_city.php c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_country_city.log 2>&1 &');
    
    die();