<?php
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'logs/';
    
    if(!isset($_GET['type'])){
        die();
    }
    
    if($_GET['type'] == 'facebook_analytics'){
        exec('php-cli '.$folder_path.'facebook_analytics.php > '.$log_path.'facebook_analytics.log 2>&1 &');
    }
    if($_GET['type'] == 'facebook_local_fans'){
        exec('php-cli '.$folder_path.'facebook_pages_local_fans.php > '.$log_path.'facebook_pages_local_fans.log 2>&1 &');
    }
    elseif($_GET['type'] == 'instagram'){
        exec('php-cli '.$folder_path.'instagram_profiles.php > '.$log_path.'instagram_profiles.log 2>&1 &');
    }
    elseif($_GET['type'] == 'twitter'){
        exec('php-cli '.$folder_path.'twitter_profiles.php > '.$log_path.'twitter_profiles.log 2>&1 &');
    }
    elseif($_GET['type'] == 'twitter_async'){
        exec('php-cli '.$folder_path.'twitter_generate_async.php > '.$log_path.'twitter_generate_async.log 2>&1 &');
    }
    
    die();