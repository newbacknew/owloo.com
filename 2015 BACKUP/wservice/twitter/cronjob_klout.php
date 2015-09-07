<?php
    set_time_limit(0);
    
    error_log('   Twitter Klout (i): '.date('d m Y H:i:s'));
    
    require_once('../config.php');
    
    $klout_api_key = 'gy4u8x32rvmeugcqpdun634b';

    //ALL acounts
    $qry = "";
    $qry .= " SELECT owloo_user_id, owloo_screen_name, owloo_user_twitter_id FROM twitter_user_master WHERE owloo_user_status = 1 AND owloo_user_twitter_id NOT IN (SELECT owloo_user_id FROM `twitter_klout_tw_data_record` WHERE date = DATE(NOW()))";
    $qry .= " Order By 1;";
    $qrydata = db_query_tw($qry, array());
    
    $count = 1;
    
    while ($fetch_cntr = mysql_fetch_array($qrydata)) {
    
        $qry = "";
        $qry .= " SELECT owloo_user_id, klout_id FROM twitter_klout_tw_data";
        $qry .= " WHERE owloo_user_id = $1;";
        $qryuser = db_query_tw($qry, array($fetch_cntr['owloo_user_id']));
        
        if(!$fetch_user = mysql_fetch_array($qryuser)) {
            
            $data = get_url_content('http://api.klout.com/v2/identity.json/twitter?screenName='.$fetch_cntr['owloo_screen_name'].'&key='.$klout_api_key);
            //$data = get_url_content('http://api.klout.com/v2/identity.json/tw/'.$fetch_cntr['owloo_user_twitter_id'].'?key='.$klout_api_key);
    
            $data = json_decode($data, true);
            
            if(isset($data['id'])){
                
                $klout_id = $data['id'];
                
                $qry = "";
                $qry = " INSERT INTO twitter_klout_tw_data VALUES (";
                $qry = $qry . " $1,";
                $qry = $qry . " $2)";
                db_query_tw($qry, array($fetch_cntr['owloo_user_id'], $data['id']), true);
                
            }
            else {
                continue;
            }
            
        }else {
            $klout_id = $fetch_user['klout_id'];
        }
        
        $klout_data = get_url_content('http://api.klout.com/v2/user.json/'.$klout_id.'/score?key='.$klout_api_key);
        $klout_data = json_decode($klout_data, true);
        
        if(isset($klout_data['score'])){
            $qry = "";
            $qry = " INSERT INTO twitter_klout_tw_data_record VALUES (";
            $qry = $qry . " $1,";
            $qry = $qry . " $2,";
            $qry = $qry . " $3,";
            $qry = $qry . " $4,";
            $qry = $qry . " $5,";
            $qry = $qry . " '$6',";
            $qry = $qry . " '$7')";
            db_query_tw($qry, array($fetch_cntr['owloo_user_id'], $klout_data['score'], $klout_data['scoreDelta']['dayChange'], $klout_data['scoreDelta']['weekChange'], $klout_data['scoreDelta']['monthChange'], $klout_data['bucket'], Date("Y-m-d")), true);
        }
        
        if($count % 8 == 0){
            sleep(2);
        }
        
        $count++;
        
    }

    error_log('   Twitter Klout (f): '.date('d m Y H:i:s'));