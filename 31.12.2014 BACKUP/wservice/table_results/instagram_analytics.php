<?php
    
    set_time_limit(0);
    
    error_log('  Instagram Analytics (i): '.date('d m Y H:i:s'));
    
    exec('php-cli '.__DIR__.'/instagram_profiles.php');
    
    error_log('  Instagram Analytics (f): '.date('d m Y H:i:s'));
    
    die();