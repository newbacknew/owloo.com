<?php

    set_time_limit(0);
    
    if(!is_numeric($_SERVER['argv'][1]) || $_SERVER['argv'][2] != 'c45a5f3b2cfa74ac94bd5bbfb2c5d6a5'){
        die();
    }
    
    //error_log('   Twitter Profile (i): '.date('d m Y H:i:s'));
    
    require_once(__DIR__.'/../config.php');
    
    include(__DIR__.'/mentions_picture/simple_html_dom.php');
    
    function get_current_picture_profile($username){
        $picture = str_get_html(get_url_content('https://twitter.com/'.$username))->find('img.ProfileAvatar-image');
        if(isset($picture[0]->attr['src'])){
            return $picture[0]->attr['src'];
        }else{
            return 'http://a0.twimg.com/sticky/default_profile_images/default_profile_5_200x200.png';
        }
    }    
    
    function getMes($mes, $format){ //Formateo de meses
        switch((int)$mes){
            case 1: if($format == 'short') return 'Ene'; else if($format == 'large') return 'Enero';
            case 2: if($format == 'short') return 'Feb'; else if($format == 'large') return 'Febrero';
            case 3: if($format == 'short') return 'Mar'; else if($format == 'large') return 'Marzo';
            case 4: if($format == 'short') return 'Abr'; else if($format == 'large') return 'Abril';
            case 5: if($format == 'short') return 'May'; else if($format == 'large') return 'Mayo';
            case 6: if($format == 'short') return 'Jun'; else if($format == 'large') return 'Junio';
            case 7: if($format == 'short') return 'Jul'; else if($format == 'large') return 'Julio';
            case 8: if($format == 'short') return 'Ago'; else if($format == 'large') return 'Agosto';
            case 9: if($format == 'short') return 'Set'; else if($format == 'large') return 'Setiembre';
            case 10: if($format == 'short') return 'Oct'; else if($format == 'large') return 'Octubre';
            case 11: if($format == 'short') return 'Nov'; else if($format == 'large') return 'Noviembre';
            case 12: if($format == 'short') return 'Dic'; else if($format == 'large') return 'Diciembre';
        }
    }

    function owlooFormatPorcent($number, $total = NULL, $decimal = 2, $sep_decimal = ',', $sep_miles = '.') {
        if ($total === 0 || $number === 0)
            return 0;
        
        if (!empty($total))
            $aux_result = ($number * 100 / $total);
        else
            $aux_result = $number;
        
        $aux_decimal = $decimal;
        
        $aux_result = ($aux_result < 0? $aux_result * -1 : $aux_result);
        if(is_int($aux_result)){
            $aux_decimal = 0;
        }
        elseif ($aux_result < 0.001) {
            $aux_decimal = 4;
        }
        elseif ($aux_result < 0.01) {
            $aux_decimal = 3;
        }
        
        if (!empty($total))
            return number_format(round(($number * 100 / $total), $aux_decimal), $aux_decimal, $sep_decimal, $sep_miles);
        else
            return number_format(round(($number), $aux_decimal), $aux_decimal, $sep_decimal, $sep_miles);
    }

    function get_tw_profile_followers_nun_dates($id_profile){
        $query = 'SELECT count(*) count FROM '.DB_TWITTER_PREFIX.'daily_track WHERE owloo_user_twitter_id = $1;';
        $que = db_query_tw($query, array($id_profile));
        if($row = mysql_fetch_assoc($que)){
           return $row['count'];
        }
        return 0;
    }

    function get_twitter_date_for_days($id_profile, $days){
        $last_update = '';
        $query = 'SELECT MAX(owloo_updated_on) max_date FROM '.DB_TWITTER_PREFIX.'daily_track WHERE owloo_user_twitter_id = $1;';
        $que = db_query_tw($query, array($id_profile));
        if($fila = mysql_fetch_assoc($que)){
            $last_update = $fila['max_date'];
        }

        $query =   'SELECT owloo_updated_on date
                    FROM '.DB_TWITTER_PREFIX.'daily_track 
                    WHERE DATE_SUB(STR_TO_DATE(\'$2\', \'%Y-%m-%d\'),INTERVAL $3 DAY) <= owloo_updated_on 
                    AND owloo_user_twitter_id = $1
                    GROUP BY owloo_updated_on 
                    ORDER BY 1 ASC 
                    LIMIT 1;
                    ';
        $que = db_query_tw($query, array($id_profile, $last_update, $days));
        
        if($fila = mysql_fetch_assoc($que)){
            return $fila['date'];
        }
        return NULL;
    }
    
    function get_data_in_a_specific_date($id_profile, $date){
        $qry = "";
        $qry = $qry . " SELECT owloo_followers_count, owloo_following_count, owloo_tweetcount, owloo_user_twitter_id, owloo_updated_on";
        $qry = $qry . " FROM ".DB_TWITTER_PREFIX."daily_track";
        $qry = $qry . " WHERE owloo_user_twitter_id = $1 AND owloo_updated_on = '$2';";
        
        $que = db_query_tw($qry, array($id_profile, $date));
        
        if($fila = mysql_fetch_assoc($que)){
            return array('followers' => $fila['owloo_followers_count'], 'following' => $fila['owloo_following_count'], 'tweetcount' => $fila['owloo_tweetcount'], 'date' => $fila['owloo_updated_on']);
        }
        
        return NULL;
    }
    
    function getHistory30Array($id_profile){
        
        $qry = "";
        $qry = $qry . " SELECT DISTINCT owloo_followers_count, owloo_following_count, owloo_user_twitter_id, owloo_updated_on";
        $qry = $qry . " FROM ".DB_TWITTER_PREFIX."daily_track";
        $qry = $qry . " WHERE owloo_user_twitter_id = $1 AND owloo_updated_on >= '".get_twitter_date_for_days($id_profile, 30)."'";
        $qry = $qry . " ORDER BY owloo_updated_on ASC , owloo_followers_count DESC";
        $qry = $qry . " LIMIT 0 , 35";
        
        $que = db_query_tw($qry, array($id_profile));
        
        $_followers_valor_anterior = -1;
        $_followers_suma_crecimiento = 0;
        $_following_valor_anterior = -1;
        $_following_suma_crecimiento = 0;
        $_count_suma_crecimiento = 0;
        $accumulate_down = 0;
        
        $seriesData = array(); //Estadística vertical. Cantidad de seguidores
        $seriesDataMin = NULL; //Número mínimo de seguidores
        $seriesDataMax = NULL; //Número máximo de seguidores
        $xAxisCategories = array(); //Estadística horizontal. Fechas de los datos
        
        $daily_followers_grow_seriesData = array(); //Estadística vertical. Cantidad de seguidores
        $daily_followers_grow_seriesDataMin = NULL; //Número mínimo de seguidores
        $daily_followers_grow_seriesDataMax = NULL; //Número máximo de seguidores
        $daily_followers_grow_xAxisCategories = array(); //Estadística horizontal. Fechas de los datos
        
        $daily_following_grow_seriesData = array(); //Estadística vertical. Cantidad de seguidores
        $daily_following_grow_seriesDataMin = NULL; //Número mínimo de seguidores
        $daily_following_grow_seriesDataMax = NULL; //Número máximo de seguidores
        $daily_following_grow_xAxisCategories = array(); //Estadística horizontal. Fechas de los datos
        
        $ban = 1; //Bandera
        $cont_num_data = mysql_num_rows($que);
        while($fila = mysql_fetch_assoc($que)){
            //Formatear fecha
            $auxformat = explode("-", $fila['owloo_updated_on']);
            $dia = $auxformat[2];
            $mes = getMes($auxformat[1], 'short');
            $year = substr($auxformat[0],2,2);
            
            $seriesData[] = $fila['owloo_followers_count'];
            $xAxisCategories[] = $dia.' '.$mes.' '.$year;
            
            if($ban == 1){
                $seriesDataMin =    $fila['owloo_followers_count'];
                $seriesDataMax =    $fila['owloo_followers_count'];
                $data_in_a_specific_date = get_data_in_a_specific_date($id_profile, get_twitter_date_for_days($id_profile, 31));
                if(!empty($data_in_a_specific_date) && $data_in_a_specific_date['date'] != $fila['owloo_updated_on']){
                    $_followers_valor_anterior = $data_in_a_specific_date['followers'];
                    $_following_valor_anterior = $data_in_a_specific_date['following'];
                }
                $ban = 0;
            }
            else{
                if($fila['owloo_followers_count'] < $seriesDataMin)
                    $seriesDataMin = $fila['owloo_followers_count'];
                else
                if($fila['owloo_followers_count'] > $seriesDataMax)
                    $seriesDataMax = $fila['owloo_followers_count'];
            }
            
            if($_followers_valor_anterior == -1){
                $_followers_valor_anterior = $fila['owloo_followers_count'];
                $_following_valor_anterior = $fila['owloo_following_count'];
            }else{
                
                if(($fila['owloo_followers_count'] - $_followers_valor_anterior) < 0){
                    $accumulate_down += $_followers_valor_anterior - $fila['owloo_followers_count'];
                }
                
                $followers_daily_grow = ($fila['owloo_followers_count'] - $_followers_valor_anterior);
                $following_daily_grow = ($fila['owloo_following_count'] - $_following_valor_anterior);
                
                $daily_followers_grow_seriesData[]      = $followers_daily_grow;
                $daily_followers_grow_xAxisCategories[] = $dia." ".$mes;
                $daily_following_grow_seriesData[]      = $following_daily_grow;
                $daily_following_grow_xAxisCategories[] = $dia." ".$mes;
                
                if(count($daily_followers_grow_seriesData) == 1){ //Primera vez
                    $daily_followers_grow_seriesDataMin =    $followers_daily_grow;
                    $daily_followers_grow_seriesDataMax =    $followers_daily_grow;
                    
                    $daily_following_grow_seriesDataMin =    $following_daily_grow;
                    $daily_following_grow_seriesDataMax =    $following_daily_grow;
                }else {
                    if($followers_daily_grow < $daily_followers_grow_seriesDataMin)
                        $daily_followers_grow_seriesDataMin = $followers_daily_grow;
                    else
                    if($followers_daily_grow > $daily_followers_grow_seriesDataMax)
                        $daily_followers_grow_seriesDataMax = $followers_daily_grow;
                    
                    if($following_daily_grow < $daily_following_grow_seriesDataMin)
                        $daily_following_grow_seriesDataMin = $following_daily_grow;
                    else
                    if($following_daily_grow > $daily_following_grow_seriesDataMax)
                        $daily_following_grow_seriesDataMax = $following_daily_grow;
                }
                
                $_followers_suma_crecimiento += $followers_daily_grow;
                $_following_suma_crecimiento += $following_daily_grow;
                $_count_suma_crecimiento++;
                $_followers_valor_anterior = $fila['owloo_followers_count'];
                $_following_valor_anterior = $fila['owloo_following_count'];
            }
            
        }
        $average_growth = array('followers' => NULL, 'following' => NULL);
        if($_count_suma_crecimiento > 0){
            $average_growth = array('followers' => owlooFormatPorcent($_followers_suma_crecimiento/$_count_suma_crecimiento, NULL, 2), 'following' => owlooFormatPorcent($_following_suma_crecimiento/$_count_suma_crecimiento, NULL, 2));
        }

        return array(
                        'followers' => array(
                                        'series_data' => implode(',', $seriesData),
                                        'series_data_min' => $seriesDataMin,
                                        'series_data_max' => $seriesDataMax,
                                        'x_axis' => implode(',', $xAxisCategories),
                                        'accumulate_down' => $accumulate_down
                               ),
                        'daily_followers_grow' => array(
                                        'series_data' => implode(',', $daily_followers_grow_seriesData),
                                        'x_axis' => implode(',', $daily_followers_grow_xAxisCategories),
                                        'min_change_of_followers_on' => $daily_followers_grow_seriesDataMin,
                                        'max_change_of_followers_on' => $daily_followers_grow_seriesDataMax,
                                        'avg_change_per_day' => $average_growth['followers']
                               ),
                        'daily_following_grow' => array(
                                        'series_data' => implode(',', $daily_following_grow_seriesData),
                                        'x_axis' => implode(',', $daily_following_grow_xAxisCategories),
                                        'min_change_of_following_on' => $daily_following_grow_seriesDataMin,
                                        'max_change_of_following_on' => $daily_following_grow_seriesDataMax,
                                        'avg_change_per_day' => $average_growth['following']
                               )
               );
        
    }

    function get_tweets_made_by_day($id_profile){
        $qry = "SELECT DISTINCT FROM_UNIXTIME(tweet_created_at, '%Y-%m-%d') AS owloo_tweet_date, count(*) tweets
                FROM ".DB_TWITTER_PREFIX."tweet_data
                WHERE owloo_user_id = $1 AND type_tweet = 1 AND FROM_UNIXTIME(tweet_created_at, '%Y-%m-%d') >= '".get_twitter_date_for_days($id_profile, 30)."'
                GROUP BY 1
                ORDER BY tweet_created_at ASC
                LIMIT 0 , 35";
        
        $que = db_query_tw($qry, array($id_profile));
        
        $seriesData = array(); //Estadística vertical. Cantidad de seguidores
        $seriesDataMin = 0; //Número mínimo de seguidores
        $seriesDataMax = 0; //Número máximo de seguidores
        $xAxisCategories = array(); //Estadística horizontal. Fechas de los datos
        $ban = 1; //Bandera
        
        $count_week = 0;
        
        while($fila = mysql_fetch_assoc($que)){
            
            $auxformat = explode("-", $fila['owloo_tweet_date']);
            $dia = $auxformat[2];
            $mes = getMes($auxformat[1], 'short');
            $year = substr($auxformat[0],2,2);
            
            $seriesData[] = $fila['tweets'];
            $xAxisCategories[] = $dia.' '.$mes.' '.$year;
            
            if($ban == 1){
                $seriesDataMin =    $fila['tweets'];
                $seriesDataMax =    $fila['tweets'];
                $ban = 0;
            }
            else{
                if($fila['tweets'] < $seriesDataMin)
                    $seriesDataMin = $fila['tweets'];
                else
                if($fila['tweets'] > $seriesDataMax)
                    $seriesDataMax = $fila['tweets'];
            }
            
            $count_week++;
            
        }

        return array(
                'series_data' => implode(',', $seriesData),
                'series_data_min' => $seriesDataMin,
                'series_data_max' => $seriesDataMax,
                'x_axis' => implode(',', $xAxisCategories)
        );
        
    }

    function get_tweets_made_by_day_of_the_week($id_profile){
        $qry = 'SELECT WEEKDAY( FROM_UNIXTIME(tweet_created_at, \'%Y-%m-%d\') ) day_of_week , count(*) tweets
                FROM `'.DB_TWITTER_PREFIX.'tweet_data`
                WHERE `owloo_user_id` = $1 AND type_tweet = 1
                GROUP BY 1';
        
        $que = db_query_tw($qry, array($id_profile));
        
        $days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        
        $seriesData = array(); //Estadística vertical. Cantidad de seguidores
        $seriesDataMin = 0; //Número mínimo de seguidores
        $seriesDataMax = 0; //Número máximo de seguidores
        $xAxisCategories = array(); //Estadística horizontal. Fechas de los datos
        $ban = 1; //Bandera
        
        $count_week = 0;
        
        while($fila = mysql_fetch_assoc($que)){
            
            while($count_week != $fila['day_of_week']){
                $seriesData[] = 0;
                $xAxisCategories[] = $days[$count_week];
                $seriesDataMin = 0;
                $count_week++;
            }
            
            $seriesData[] = $fila['tweets'];
            $xAxisCategories[] = $days[$fila['day_of_week']];
            
            if($ban == 1){
                if($count_week == 0)
                    $seriesDataMin =    $fila['tweets'];
                $seriesDataMax =    $fila['tweets'];
                $ban = 0;
            }
            else{
                if($fila['tweets'] < $seriesDataMin)
                    $seriesDataMin = $fila['tweets'];
                else
                if($fila['tweets'] > $seriesDataMax)
                    $seriesDataMax = $fila['tweets'];
            }
            
            $count_week++;
            
        }

        return array(
                'series_data' => implode(',', $seriesData),
                'series_data_min' => $seriesDataMin,
                'series_data_max' => $seriesDataMax,
                'x_axis' => implode(',', $xAxisCategories)
        );
        
    }

    function getKloutHistory30Array($id_profile){
        
        $current_klout_data = NULL;
        
        $qry = $qry . " SELECT * ";
        $qry = $qry . " FROM ".DB_TWITTER_PREFIX."klout_tw_data_record";
        $qry = $qry . " WHERE owloo_user_id = $1";
        $qry = $qry . " ORDER BY date ASC";
        $qry = $qry . " LIMIT 1";
        $que = db_query_tw($qry, array($id_profile));
        
        if($fila = mysql_fetch_assoc($que)){
            $current_klout_data['score'] =  owlooFormatPorcent($fila['score'], NULL, 3, '.', '');
            $current_klout_data['day_change'] =  owlooFormatPorcent($fila['day_change']);
            $current_klout_data['week_change'] =  owlooFormatPorcent($fila['week_change']);
            $current_klout_data['month_change'] =  owlooFormatPorcent($fila['month_change']);
            $current_klout_data['bucket'] =  $fila['bucket'];
        }
        else{
            return array();
        }
        
        $qry = "";
        $qry = $qry . " SELECT score, date";
        $qry = $qry . " FROM ".DB_TWITTER_PREFIX."klout_tw_data_record";
        $qry = $qry . " WHERE owloo_user_id = $1";
        $qry = $qry . " ORDER BY date ASC";
        $qry = $qry . " LIMIT 0 , 30";
        
        $que = db_query_tw($qry, array($id_profile));
        
        $seriesData = array(); //Estadística vertical. Cantidad de seguidores
        $seriesDataMin = 0; //Número mínimo de seguidores
        $seriesDataMax = 0; //Número máximo de seguidores
        $xAxisCategories = array(); //Estadística horizontal. Fechas de los datos
        $ban = 1; //Bandera
        $cont_num_data = mysql_num_rows($que);
        while($fila = mysql_fetch_assoc($que)){
            //Formatear fecha
            $auxformat = explode("-", $fila['date']);
            $dia = $auxformat[2];
            $mes = getMes($auxformat[1], 'short');
            $year = $semister_year = substr($auxformat[0],2,2);
            
            $score = owlooFormatPorcent($fila['score']);
            
            $seriesData[] = $score;
            $xAxisCategories[] = $dia.' '.$mes.' '.$year;
            
            if($ban == 1){
                $seriesDataMin =    $score;
                $seriesDataMax =    $score;
                $ban = 0;
            }
            else{
                if($score < $seriesDataMin)
                    $seriesDataMin = $score;
                else
                if($score > $seriesDataMax)
                    $seriesDataMax = $score;
            }
            
        }

        return array(
                'series_data' => implode(',', $seriesData),
                'series_data_min' => $seriesDataMin,
                'series_data_max' => $seriesDataMax,
                'x_axis' => implode(',', $xAxisCategories),
                'current_klout_data' => $current_klout_data
        );
        
    }

    function getCrecimientoTwitterFollowers($id_profile, $current_follower_count, $days){
        $qry = "";
        $qry = " SELECT owloo_followers_count FROM ".DB_TWITTER_PREFIX."daily_track WHERE owloo_user_twitter_id = $1 AND owloo_updated_on = STR_TO_DATE('".get_twitter_date_for_days($id_profile, $days)."','%Y-%m-%d') ORDER BY owloo_updated_on ASC LIMIT 1;";
        
        $que = db_query_tw($qry, array($id_profile));
        if($fila = mysql_fetch_assoc($que)){
            return $current_follower_count - $fila['owloo_followers_count'];
        }
        return NULL;
    }
    
    function getMentionsArray($id_profile, $count = 10){
        $mentions = array();
        $qry = ' SELECT screen_name AS name, count(*) AS count
                    FROM '.DB_TWITTER_PREFIX.'mentions
                    WHERE owloo_user_id = $1 AND type_tweet = 1
                    GROUP BY screen_name
                    ORDER BY count DESC
                    LIMIT 0 , $2;';
        
        $que = db_query_tw($qry, array($id_profile, $count));
        while($fila = mysql_fetch_assoc($que)){
            $fila['picture'] = get_current_picture_profile($fila['name']);
            $mentions[] = $fila;
        }
        return $mentions;
    }
    
    function getHashtagsArray($id_profile, $count = 10){
        $hastags = array();
        $qry = ' SELECT hashtags AS name, count(*) AS count
                    FROM '.DB_TWITTER_PREFIX.'hashtag
                    WHERE owloo_user_id = $1 AND type_tweet = 1
                    GROUP BY hashtags
                    ORDER BY count DESC
                    LIMIT 0 , $2;';
        
        $que = db_query_tw($qry, array($id_profile, $count));
        while($fila = mysql_fetch_assoc($que)){
            $hastags[] = $fila;
        }
        return $hastags;
    }
    
    $query = 'SELECT * FROM '.DB_TWITTER_PREFIX.'user_master WHERE owloo_user_id = $1 AND owloo_user_status = 1;';
    $que_profiles = db_query_tw($query, array($_SERVER['argv'][1]));
    while($profiles = mysql_fetch_assoc($que_profiles)){
        
        $id_profile = $profiles['owloo_user_id'];
        $screen_name = $profiles['owloo_screen_name'];
        $name = $profiles['owloo_user_name'];
        $description = $profiles['owloo_user_description'];
        $picture = $profiles['owloo_user_photo'];
        $cover = $profiles['owloo_user_cover'];
        $is_verified = $profiles['owloo_user_verified_account'];
        $location = $profiles['owloo_user_location'];
        $idiom = $profiles['owloo_user_language'];
        $in_twitter_from = date("Y-m-d", strtotime($profiles['owloo_user_created_on']));
        $followers_count = $profiles['owloo_followers_count'];
        $following_count = $profiles['owloo_following_count'];
        $tweet_count = $profiles['owloo_tweetcount'];
        
        $tweets_made_by_day = get_tweets_made_by_day($id_profile);
        
        $tweets_made_by_day_of_the_week = get_tweets_made_by_day_of_the_week($id_profile);
        
        $history = getHistory30Array($id_profile);
        
        $history['tweets_made_by_day'] = $tweets_made_by_day;
        
        $history['tweets_made_by_day_of_the_week'] = $tweets_made_by_day_of_the_week;
        
        $average_growth = ($history['followers']['average_growth']===NULL?'NULL':$history['followers']['average_growth']);
        
        $accumulate_down_30 = $history['followers']['accumulate_down'];
        
        unset($history['followers']['accumulate_down']);
        
        $charts = json_encode($history);
        
        $followers_grow_1 = "NULL";
        $followers_grow_7 = "NULL";
        $followers_grow_15 = "NULL";
        $followers_grow_30 = "NULL";
        
        $tw_profile_followers_nun_dates = get_tw_profile_followers_nun_dates($id_profile);
        
        if($tw_profile_followers_nun_dates > 1){
            $followers_grow_1 = getCrecimientoTwitterFollowers($id_profile, $followers_count, 1);
        }
        if($tw_profile_followers_nun_dates > 7){
            $followers_grow_7 = getCrecimientoTwitterFollowers($id_profile, $followers_count, 7);
        }
        if($tw_profile_followers_nun_dates > 15){
            $followers_grow_15 = getCrecimientoTwitterFollowers($id_profile, $followers_count, 15);
        }
        if($tw_profile_followers_nun_dates > 30){
            $followers_grow_30 = getCrecimientoTwitterFollowers($id_profile, $followers_count, 30);
        }
        
        $most_mentions = getMentionsArray($id_profile, 6);
        $most_mentions = json_encode($most_mentions);
        
        $most_hashtags = getHashtagsArray($id_profile, 7);
        $most_hashtags = json_encode($most_hashtags);
        
        $klout = getKloutHistory30Array($id_profile);
        
        $klout = json_encode($klout);
        
        $general_ranking = 'NULL';
        
        $in_owloo_from = $profiles['owloo_created_on'];
        
        $query = 'SELECT id FROM '.DB_RESULTS_PREFIX.'twitter_profiles WHERE id = $10;';
        $que_profile = db_query_table_results($query, array($id_profile));
        if($row = mysql_fetch_assoc($que_profile)){
            $query = "UPDATE ".DB_RESULTS_PREFIX."twitter_profiles SET  
                                                screen_name = '$10', 
                                                name = '$11', 
                                                description = '$12',
                                                picture = '$13',
                                                cover = '$14',
                                                is_verified = $15, 
                                                location = '$16',
                                                idiom = '$17',
                                                in_twitter_from = '$18',
                                                followers_count = $19,
                                                following_count = $20,
                                                tweet_count = $21,
                                                accumulate_down_30 = $22,
                                                charts = '$23',
                                                average_growth = $24,
                                                followers_grow_1 = $25,
                                                followers_grow_7 = $26,
                                                followers_grow_15 = $27,
                                                followers_grow_30 = $28,
                                                most_mentions = '$29',
                                                most_hashtags = '$30',
                                                klout = '$31',
                                                in_owloo_from = '$32',
                                                updated_at = NOW()
                                                
                                                WHERE id = $33;";
            $values = array(
                                $screen_name, 
                                $name, 
                                $description,
                                $picture,
                                $cover,
                                $is_verified, 
                                $location,
                                $idiom,
                                $in_twitter_from,
                                $followers_count,
                                $following_count,
                                $tweet_count,
                                $accumulate_down_30,
                                $charts,
                                $average_growth,
                                $followers_grow_1,
                                $followers_grow_7,
                                $followers_grow_15,
                                $followers_grow_30,
                                $most_mentions,
                                $most_hashtags,
                                $klout,
                                $in_owloo_from,
                                $id_profile
                          );
            $res = db_query_table_results($query, $values, 1);
        }
        else{
            $query = "INSERT INTO ".DB_RESULTS_PREFIX."twitter_profiles VALUES($10, '$11', '$12', '$13', '$14', '$15', $16, '$17', '$18', '$19', $20, $21, $22, $23, '$24', $25, $26, $27, $28, $29, '$30', '$31', '$32', $33, '$34', NOW());";
            $values = array(
                                $id_profile, 
                                $screen_name, 
                                $name, 
                                $description,
                                $picture,
                                $cover,
                                $is_verified, 
                                $location,
                                $idiom,
                                $in_twitter_from,
                                $followers_count,
                                $following_count,
                                $tweet_count,
                                $accumulate_down_30,
                                $charts,
                                $average_growth,
                                $followers_grow_1,
                                $followers_grow_7,
                                $followers_grow_15,
                                $followers_grow_30,
                                $most_mentions,
                                $most_hashtags,
                                $klout,
                                $general_ranking,
                                $in_owloo_from
                           );
            $res = db_query_table_results($query, $values, 1);
        }

    }

    //error_log('   Twitter Profile (f): '.date('d m Y H:i:s'));