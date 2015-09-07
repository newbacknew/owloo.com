<?php
    
    set_time_limit(0);
    
    error_log('  Twitter Analytics (i): '.date('d m Y H:i:s'));
    
    exec('php-cli '.__DIR__.'/twitter_profiles.php');
    
    error_log('  Twitter Analytics (f): '.date('d m Y H:i:s'));
    
    die();