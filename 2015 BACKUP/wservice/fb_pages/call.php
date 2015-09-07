<?php
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'logs/';
    
    if(!isset($_GET['type'])){
        die();
    }
    
    if($_GET['type'] == 'page'){
        exec('php-cli '.$folder_path.'cron_fb_page.php > '.$log_path.'cron_fb_page.log 2>&1 &');
    }
    
    die();