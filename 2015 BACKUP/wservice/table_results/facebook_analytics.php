<?php
    
    set_time_limit(0);
    
    error_log('  Facebook Analytics (i): '.date('d m Y H:i:s'));
    
    exec('php-cli '.__DIR__.'/facebook_pages.php');
    
    exec('php-cli '.__DIR__.'/facebook_pages_local_fans.php');
    
    error_log('  Facebook Analytics (f): '.date('d m Y H:i:s'));
    
    die();