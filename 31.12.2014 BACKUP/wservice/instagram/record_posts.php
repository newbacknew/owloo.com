<?php
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'logs/';
    
    exec('php-cli '.$folder_path.'add_instagram_posts.php > '.$log_path.'add_instagram_posts.log 2>&1 &');
    
    die();