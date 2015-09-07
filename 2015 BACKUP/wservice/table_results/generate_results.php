<?php
    
    set_time_limit(0);
    
    error_log('Results (i): '.date('d m Y H:i:s'));
    
    exec('php-cli '.__DIR__.'/facebook_research.php');
    
    exec('php-cli '.__DIR__.'/facebook_analytics.php');
    
    exec('php-cli '.__DIR__.'/twitter_analytics.php');
    
    exec('php-cli '.__DIR__.'/instagram_analytics.php');
    
    error_log('Results (f): '.date('d m Y H:i:s'));
    
    die();