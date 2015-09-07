<?php
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'logs/';
    
    if(!isset($_GET['type'])){
        die();
    }
    
    if($_GET['type'] == 'profile'){
        exec('php-cli '.$folder_path.'cronjob.php > '.$log_path.'cronjob.log 2>&1 &');
    }elseif($_GET['type'] == 'tweet'){
        exec('php-cli '.$folder_path.'tweets/cronjob_tweet.php > '.$log_path.'cronjob_tweet.log 2>&1 &');
    }elseif($_GET['type'] == 'klout'){
        exec('php-cli '.$folder_path.'cronjob_klout.php > '.$log_path.'cronjob_klout.log 2>&1 &');
    }
    
    die();