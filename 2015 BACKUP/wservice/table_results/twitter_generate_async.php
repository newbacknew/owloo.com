<?php

    set_time_limit(0);
    
    error_log('   Twitter Profile Generate Async (i): '.date('d m Y H:i:s'));
    
    require_once(__DIR__.'/../config.php');
    
    $query = 'SELECT owloo_user_id 
                FROM '.DB_TWITTER_PREFIX.'user_master 
                WHERE owloo_user_status = 1 
                ORDER BY owloo_user_id;';
    $que_profiles = db_query_tw($query, array());
    $count = 1;
    while($profiles = mysql_fetch_assoc($que_profiles)){
        
        exec('php-cli '.__DIR__.'/twitter_profiles_async.php '.$profiles['owloo_user_id'].' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.__DIR__.'/logs/twitter_profiles_async.log 2>&1 &');
        if($count++ % 250 == 0){
            sleep(60);
        }
        
    }
    
    sleep(120);
    
    //General ranking
    $query = 'SELECT id FROM '.DB_RESULTS_PREFIX.'twitter_profiles ORDER BY followers_count DESC, followers_grow_30 DESC;';
    $que_pages = db_query_table_results($query, array());
    $count = 1;
    while($pages = mysql_fetch_assoc($que_pages)){
        $query = "UPDATE ".DB_RESULTS_PREFIX."twitter_profiles SET general_ranking = $10 WHERE id = $11;";
        $values = array($count, $pages['id']);
        $res = db_query_table_results($query, $values, 1);
        $count++;
    }

    error_log('   Twitter Profile Generate Async (f): '.date('d m Y H:i:s'));