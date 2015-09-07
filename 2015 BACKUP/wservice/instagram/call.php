<?php
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'logs/';
    
    if(!isset($_GET['type'])){
        die();
    }
    
    if($_GET['type'] == 'profile'){
        exec('php-cli '.$folder_path.'cron.php > '.$log_path.'cron.log 2>&1 &');
    }
    
    die();