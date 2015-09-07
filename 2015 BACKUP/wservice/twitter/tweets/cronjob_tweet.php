<?php
    
    set_time_limit(0);
    
    error_log('   Twitter tweets (i): '.date('d m Y H:i:s'));
    
    require_once(__DIR__.'/../../config_db.php');
    require_once(__DIR__.'/twitterfunctions_profile.php');
    
    $conexion_owloo = mysql_connect('localhost', DB_USER, DB_PASS) or die(mysql_error());
    mysql_select_db(DB_NAME_TW, $conexion_owloo) or die(mysql_error());
    mysql_query('SET NAMES \'utf8\'');
    
    
    function update_tweets($update_id, $screen_name, $twitter_id, $tweetcount){
    	global $code_app, $cont_access_token; shuffle($code_app);
    	
    	$uid = $update_id;
    	$is_new_insert = 0; // 1 for new 0 for update
        
		$since_id = "";
		$since_tweet = "";
		$qry = "";
		$qry = "Select owloo_id_str, owloo_tweet_count From twitter_last_id_str";
		$qry = $qry . " Where owloo_user_id = '" . mysql_real_escape_string($uid) . "'";
		
		$dataarr = mysql_query($qry) or die(mysql_error());
		$fetch_cntr = mysql_fetch_assoc($dataarr);
		if($fetch_cntr){
			$since_id = $fetch_cntr['owloo_id_str'];
			$since_tweet = $fetch_cntr['owloo_tweet_count'];
		}
		if(!$since_id){
			$is_new_insert = 1;
		}
    	
    	include('get_tweets.php');
        
    }
    
    //ALL acounts
    $qry = "";
    $qry .= " SELECT owloo_user_id, owloo_user_twitter_id, owloo_screen_name, owloo_tweetcount FROM twitter_user_master"; 
    $qry .= " WHERE owloo_user_status = 1 ";
    $qry .= " Order By 1";
    //$qry .= " LIMIT 1";
    
    $qrydata = mysql_query($qry) or die(mysql_error());
    while ($fetch_cntr = mysql_fetch_assoc($qrydata)) {
        
        update_tweets($fetch_cntr['owloo_user_id'], $fetch_cntr['owloo_screen_name'], $fetch_cntr['owloo_user_twitter_id'], $fetch_cntr['owloo_tweetcount']);
        	
    }
   
    error_log('   Twitter tweets (f): '.date('d m Y H:i:s'));
    