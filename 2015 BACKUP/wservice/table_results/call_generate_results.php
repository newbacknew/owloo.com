<?php
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'logs/';
    
    exec('php-cli '.$folder_path.'generate_results.php > '.$log_path.'generate_results.log 2>&1 &');
    
    die();