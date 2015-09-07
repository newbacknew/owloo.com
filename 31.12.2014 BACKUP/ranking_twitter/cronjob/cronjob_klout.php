<?php
    set_time_limit(0);
    include("../config/config_cronjob.php");
    
    $klout_api_key = 'gy4u8x32rvmeugcqpdun634b';
    
    function get_url_content($url){
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_URL, $url);
          $data = curl_exec($ch);
          curl_close($ch);
          return $data;
    }

    //ALL acounts
    $qry = "";
    $qry .= " SELECT owloo_user_id, owloo_screen_name FROM owloo_user_master WHERE owloo_user_id NOT IN (SELECT owloo_user_id FROM `owloo_klout_tw_data_record` WHERE date = DATE(NOW()))";
    $qry .= " Order By 1;";
    $qrydata = mysql_query($qry);
    
    $count = 1;
    
    while ($fetch_cntr = mysql_fetch_array($qrydata)) {
    
        $qry = "";
        $qry .= " SELECT owloo_user_id, klout_id FROM owloo_klout_tw_data";
        $qry .= " WHERE owloo_user_id = ".mysql_real_escape_string($fetch_cntr['owloo_user_id']).";";
        $qryuser = mysql_query($qry);
        
        if(!$fetch_user = mysql_fetch_array($qryuser)) {
            
            $data = get_url_content('http://api.klout.com/v2/identity.json/twitter?screenName='.$fetch_cntr['owloo_screen_name'].'&key='.$klout_api_key);
    
            $data = json_decode($data, true);
            
            if(isset($data['id'])){
                
                $klout_id = $data['id'];
                
                $qry = "";
                $qry = " INSERT INTO owloo_klout_tw_data VALUES (";
                $qry = $qry . " " . $fetch_cntr['owloo_user_id'] . ",";
                $qry = $qry . " " . mysql_real_escape_string($data['id']).")";
                mysql_query($qry);
                
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
            $qry = " INSERT INTO owloo_klout_tw_data_record VALUES (";
            $qry = $qry . " " . $fetch_cntr['owloo_user_id'] . ",";
            $qry = $qry . " " . mysql_real_escape_string($klout_data['score']) . ",";
            $qry = $qry . " " . mysql_real_escape_string($klout_data['scoreDelta']['dayChange']) . ",";
            $qry = $qry . " " . mysql_real_escape_string($klout_data['scoreDelta']['weekChange']) . ",";
            $qry = $qry . " " . mysql_real_escape_string($klout_data['scoreDelta']['monthChange']) . ",";
            $qry = $qry . " '" . mysql_real_escape_string($klout_data['bucket']) . "',";
            $qry = $qry . " '" . Date("Y-m-d") . "')";
            mysql_query($qry);
        }
        
        if($count % 8 == 0){
            sleep(2);
        }
        
        $count++;
        
    }