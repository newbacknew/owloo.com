<?php
    set_time_limit(0);
    
    $generate_new_profile = false;
    $generate_new_profile_id = NULL;
    if(isset($_GET['generate_new_profile']) AND isset($_GET['generate_new_profile_id']) AND is_numeric($_GET['generate_new_profile_id'])){
        $generate_new_profile = true;
        $generate_new_profile_id = $_GET['generate_new_profile_id'];
    }
    
    if(!$generate_new_profile){
        error_log('   Instagram Profile (i): '.date('d m Y H:i:s'));
    }
    
    require_once(__DIR__.'/../config.php');
    
    include(__DIR__.'/mentions_picture/simple_html_dom.php');
    
    function get_current_picture_profile($username){
        
        global $generate_new_profile;
        
        if(!$generate_new_profile){
            $html =  get_url_content('http://instagram.com/'.$username);
        
            $doc = new DOMDocument();
            $doc->loadHTML($html);
            
            foreach( $doc->getElementsByTagName('meta') as $meta ) {
               if($meta->getAttribute('property') == 'og:image'){
                   if($meta->getAttribute('content') != ''){
                       return  $meta->getAttribute('content');
                   }
               }
            }
        }
        
        return 'http://a0.twimg.com/sticky/default_profile_images/default_profile_5_200x200.png';
        
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

    function owloo_number_format($value, $separador = '.', $decimal_separator = ',', $decimal_count = 0) {
        return str_replace(' ', '&nbsp;', number_format($value, $decimal_count, $decimal_separator, $separador));
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

    function get_instagram_current_record_data($id_profile){
        $last_update = get_instagram_last_update($id_profile);
        $query = 'SELECT followed_by, follows, media FROM '.DB_INSTAGRAM_PREFIX.'record WHERE id_profile = $1 AND date = \'$2\';';
        $que = db_query($query, array($id_profile, $last_update));
        if($row = mysql_fetch_assoc($que)){
           return $row;
        }
        return NULL;
    }

    function get_instagram_profile_followers_num_dates($id_profile){
        $query = 'SELECT count(*) count FROM '.DB_INSTAGRAM_PREFIX.'record WHERE id_profile = $1;';
        $que = db_query($query, array($id_profile));
        if($row = mysql_fetch_assoc($que)){
           return $row['count'];
        }
        return 0;
    }
    
    function get_instagram_last_update($id_profile){
        $query = 'SELECT MAX(date) max_date FROM '.DB_INSTAGRAM_PREFIX.'record WHERE id_profile = $1;';
        $que = db_query($query, array($id_profile));
        if($fila = mysql_fetch_assoc($que)){
            return $fila['max_date'];
        }
        return NULL;
    }
    
    function get_instagram_date_for_days($id_profile, $days){
        $last_update = get_instagram_last_update($id_profile);

        $query =   'SELECT date
                    FROM '.DB_INSTAGRAM_PREFIX.'record 
                    WHERE DATE_SUB(STR_TO_DATE(\'$2\', \'%Y-%m-%d\'),INTERVAL $3 DAY) <= date 
                    AND id_profile = $1
                    GROUP BY date 
                    ORDER BY 1 ASC 
                    LIMIT 1;
                    ';
        $que = db_query($query, array($id_profile, $last_update, $days));
        
        if($fila = mysql_fetch_assoc($que)){
            return $fila['date'];
        }
        return NULL;
    }
    
    function getHistory30Array($id_profile){
        
        $qry = "";
        $qry = $qry . " SELECT media, followed_by, follows, date";
        $qry = $qry . " FROM ".DB_INSTAGRAM_PREFIX."record";
        $qry = $qry . " WHERE id_profile = $1 AND date >= '".get_instagram_date_for_days($id_profile, 30)."'";
        $qry = $qry . " ORDER BY date ASC , followed_by DESC";
        $qry = $qry . " LIMIT 0 , 35";
        
        $que = db_query($qry, array($id_profile));
        
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
            $auxformat = explode("-", $fila['date']);
            $dia = $auxformat[2];
            $mes = getMes($auxformat[1], 'short');
            $year = substr($auxformat[0],2,2);
            
            $seriesData[] = $fila['followed_by'];
            $xAxisCategories[] = $dia.' '.$mes.' '.$year;
            
            if($ban == 1){
                $seriesDataMin =    $fila['followed_by'];
                $seriesDataMax =    $fila['followed_by'];
                
                $_followers_valor_anterior = $fila['followed_by'];
                $_following_valor_anterior = $fila['follows'];
                
                $ban = 0;
            }
            else{
                if($fila['followed_by'] < $seriesDataMin)
                    $seriesDataMin = $fila['followed_by'];
                else
                if($fila['followed_by'] > $seriesDataMax)
                    $seriesDataMax = $fila['followed_by'];
                
                
                if(($fila['followed_by'] - $_followers_valor_anterior) < 0){
                    $accumulate_down += $_followers_valor_anterior - $fila['followed_by'];
                }
                
                $followers_daily_grow = ($fila['followed_by'] - $_followers_valor_anterior);
                $following_daily_grow = ($fila['follows'] - $_following_valor_anterior);
                
                $daily_followers_grow_seriesData[]      = $followers_daily_grow;
                $daily_followers_grow_xAxisCategories[] = $dia.' '.$mes.' '.$year;
                $daily_following_grow_seriesData[]      = $following_daily_grow;
                $daily_following_grow_xAxisCategories[] = $dia.' '.$mes.' '.$year;
                
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
                $_followers_valor_anterior = $fila['followed_by'];
                $_following_valor_anterior = $fila['follows'];
                
            }
        }
        
        $average_growth = array('followers' => NULL, 'following' => NULL);
        if($_count_suma_crecimiento > 0){
            $average_growth = array('followers' => owlooFormatPorcent($_followers_suma_crecimiento/$_count_suma_crecimiento), 'following' => owlooFormatPorcent($_following_suma_crecimiento/$_count_suma_crecimiento));
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

    function getCrecimientoInstagramFollowers($id_profile, $current_follower_count, $days){
        $qry = "";
        $qry = " SELECT followed_by FROM ".DB_INSTAGRAM_PREFIX."record WHERE id_profile = $1 AND date = STR_TO_DATE('".get_instagram_date_for_days($id_profile, $days)."','%Y-%m-%d') ORDER BY date ASC LIMIT 1;";
        
        $que = db_query($qry, array($id_profile));
        if($fila = mysql_fetch_assoc($que)){
            return $current_follower_count - $fila['followed_by'];
        }
        return NULL;
    }
    
    function getMentionsArray($id_profile, $count = 10){
        $mentions = array();
        $qry = ' SELECT mention name, count(*) AS count
                    FROM '.DB_INSTAGRAM_PREFIX.'media_mentions
                    WHERE id_profile = $1
                    GROUP BY mention
                    ORDER BY count DESC
                    LIMIT 0 , $2;';
        
        $que = db_query($qry, array($id_profile, $count));
        while($fila = mysql_fetch_assoc($que)){
            $fila['picture'] = get_current_picture_profile($fila['name']);
            $mentions[] = $fila;
        }
        return $mentions;
    }
    
    function getHashtagsArray($id_profile, $count = 10){
        $hastags = array();
        $qry = ' SELECT tag name, count(*) AS count
                    FROM '.DB_INSTAGRAM_PREFIX.'media_tags
                    WHERE id_profile = $1
                    GROUP BY tag
                    ORDER BY count DESC
                    LIMIT 0 , $2;';
        
        $que = db_query($qry, array($id_profile, $count));
        while($fila = mysql_fetch_assoc($que)){
            $hastags[] = $fila;
        }
        return $hastags;
    }
    
    function get_instagram_medio_comments($id_media, $count){
        $comments = array();
        $qry = ' SELECT comment, username, full_name, profile_picture, created_time
                    FROM '.DB_INSTAGRAM_PREFIX.'media_comments
                    WHERE id_media = $1
                    ORDER BY created_time DESC
                    LIMIT 0 , $2;';
        
        $que = db_query($qry, array($id_media, $count));
        while($fila = mysql_fetch_assoc($que)){
            $comments[] = $fila;
        }
        return $comments;
    }
    
    function get_instagram_last_post($id_profile, $count, $followed_by_count, $data_type = 'last_post'){
        $media = array();
        
        $where = '';
        $order_by = '';
        /*if($data_type == 'last_post'){*/
            $order_by = 'created_time DESC';
        /*}elseif($data_type == 'engagement_rate'){
            $where = ' AND created_time >= '.strtotime(date('Y-m-d').' -1 months');
            $order_by = 'engagement DESC';
        }*/
        
        $qry = ' SELECT id_media, caption_text, type, images_standard_resolution, videos_standard_resolution, filter, comments_count, likes_count, (comments_count + likes_count) engagement, location_latitude, location_longitude, location_name, link, FROM_UNIXTIME(created_time) created_time 
                    FROM '.DB_INSTAGRAM_PREFIX.'media
                    WHERE id_profile = $1 AND active = 1 '.$where.'
                    ORDER BY '.$order_by.'
                    LIMIT 0 , $2;';
        
        $que = db_query($qry, array($id_profile, $count));
        
        $index = 0; 
        while($fila = mysql_fetch_assoc($que)){
            
            $media[$index]['type'] = $fila['type'];
            $media[$index]['images_standard_resolution'] = $fila['images_standard_resolution'];
            $media[$index]['videos_standard_resolution'] = $fila['videos_standard_resolution'];
            if($data_type == 'engagement_rate'){
                $media[$index]['caption_text'] = $fila['caption_text'];
                $media[$index]['filter'] = $fila['filter'];
                $media[$index]['comments_count'] = owloo_number_format($fila['comments_count']);
                $media[$index]['likes_count'] = owloo_number_format($fila['likes_count']);
                $media[$index]['engagement_rate'] = ($followed_by_count > 0?owlooFormatPorcent((($fila['likes_count'] + $fila['comments_count']) / $followed_by_count) * 100):0);
                
                $media[$index]['location'] = array();
                if(!empty($fila['location_latitude'])){
                    $media[$index]['location'] = array(
                                                        'latitude' => $fila['location_latitude'],
                                                        'longitude' => $fila['location_longitude'],
                                                        'name' => $fila['location_name']
                                                 );
                }
                
                $media[$index]['link'] = $fila['link'];
                $media[$index]['created_time'] = $fila['created_time'];
                //$media[$index]['comments'] = get_instagram_medio_comments($fila['id_media'], 5);
            }
            
            $index++;
            
        }
        return $media;
    }

    function get_av_engagement_post($id_profile, $followed_by_count){
        
        $qry = ' SELECT id_media, comments_count, likes_count 
                    FROM '.DB_INSTAGRAM_PREFIX.'media 
                    WHERE id_profile = $1 AND active = 1 AND created_time >= '.strtotime(date('Y-m-d').' -1 months');
        
        $que = db_query($qry, array($id_profile));
        
        $post_count = 0;
        
        $engagement_count = 0;
        
        while($fila = mysql_fetch_assoc($que)){
            
            $engagement_count += ($followed_by_count > 0 ? ((($fila['comments_count'] + $fila['likes_count']) / $followed_by_count) * 100):0);
            
            $post_count++;
            
        }
        return ($post_count > 0 ? ($engagement_count / $post_count) : 0);
    }
    
    //Categorías
    $categories = array();
    $query = 'SELECT id_category, category FROM '.DB_INSTAGRAM_PREFIX.'category ORDER BY 1;'; 
    $que = db_query($query, array());
    while($fila = mysql_fetch_assoc($que)){
        $categories[$fila['id_category']] = array('id' => $fila['id_category'], 'name' => $fila['category']);
    }
    
    if(!$generate_new_profile){
        $query = 'SELECT * FROM '.DB_INSTAGRAM_PREFIX.'profiles WHERE active = 1 ORDER BY id_profile;';
        $que_profiles = db_query($query, array());
    }else {
        $query = 'SELECT * FROM '.DB_INSTAGRAM_PREFIX.'profiles WHERE id_profile = $1 AND active = 1;';
        $que_profiles = db_query($query, array($generate_new_profile_id));
    }
    
    while($profiles = mysql_fetch_assoc($que_profiles)){
        
        $id_profile = $profiles['id_profile'];
        $username = $profiles['username'];
        $name = (!empty($profiles['full_name'])?$profiles['full_name']:ucfirst($profiles['username']));
        $bio = $profiles['bio'];
        $website = $profiles['website'];
        $picture = $profiles['profile_picture'];
        $category = (!empty($profiles['id_category'])?$categories[$profiles['id_category']]['name']:'NULL');
        $in_owloo_from = $profiles['date_add'];
        
        $follow = get_instagram_current_record_data($id_profile);
        
        $followed_by_count = $follow['followed_by'];
        $follows_count = $follow['follows'];
        $media_count = $follow['media'];
        
        $history = getHistory30Array($id_profile);
        $accumulate_down_30 = $history['followers']['accumulate_down'];
        unset($history['followers']['accumulate_down']);
        
        $charts = json_encode($history);
        
        $followed_by_grow_1 = "NULL";
        $followed_by_grow_7 = "NULL";
        $followed_by_grow_15 = "NULL";
        $followed_by_grow_30 = "NULL";
        
        $tw_profile_followed_by_nun_dates = get_instagram_profile_followers_num_dates($id_profile);
        
        if($tw_profile_followed_by_nun_dates > 1){
            $followed_by_grow_1 = getCrecimientoInstagramFollowers($id_profile, $followed_by_count, 1);
        }
        if($tw_profile_followed_by_nun_dates > 7){
            $followed_by_grow_7 = getCrecimientoInstagramFollowers($id_profile, $followed_by_count, 7);
        }
        if($tw_profile_followed_by_nun_dates > 15){
            $followed_by_grow_15 = getCrecimientoInstagramFollowers($id_profile, $followed_by_count, 15);
        }
        if($tw_profile_followed_by_nun_dates > 30){
            $followed_by_grow_30 = getCrecimientoInstagramFollowers($id_profile, $followed_by_count, 30);
        }
        
        $most_mentions = getMentionsArray($id_profile, 6);
        $most_mentions = json_encode($most_mentions);
        
        $most_hashtags = getHashtagsArray($id_profile, 7);
        $most_hashtags = json_encode($most_hashtags);
        
        /*** Get last post ***/
        $last_post = get_instagram_last_post($id_profile, 3, $followed_by_count, 'last_post');
        $last_post = json_encode($last_post);
        
        /*** Get post by engagement ***/
        $post_by_engagement_rate = get_instagram_last_post($id_profile, 3, $followed_by_count, 'engagement_rate');
        $post_by_engagement_rate = json_encode($post_by_engagement_rate);
        
        $av_engagement = get_av_engagement_post($id_profile, $followed_by_count);
        
        $general_ranking = 'NULL';
        
        $query = 'SELECT id FROM '.DB_RESULTS_PREFIX.'instagram_profiles WHERE id = $10;';
        $que_profile = db_query_table_results($query, array($id_profile));
        if($row = mysql_fetch_assoc($que_profile)){
            $query = "UPDATE ".DB_RESULTS_PREFIX."instagram_profiles SET  
                                                username = '$10', 
                                                name = '$11', 
                                                bio = '$12',
                                                website = '$13', 
                                                picture = '$14',
                                                category = ".($category!="NULL"?"'$15'":"$15").",
                                                in_owloo_from = '$16',
                                                followed_by_count = $17,
                                                follows_count = $18,
                                                media_count = $19,
                                                charts = '$20',
                                                accumulate_down_30 = $21,
                                                followed_by_grow_1 = $22,
                                                followed_by_grow_7 = $23,
                                                followed_by_grow_15 = $24,
                                                followed_by_grow_30 = $25,
                                                most_mentions = '$26',
                                                most_hashtags = '$27',
                                                last_post = '$28',
                                                post_by_engagement_rate = '$29',
                                                av_engagement = $30,
                                                updated_at = NOW()
                                                
                                                WHERE id = $31;";
            $values = array(
                                $username, 
                                $name, 
                                $bio,
                                $website,
                                $picture,
                                $category,
                                $in_owloo_from, 
                                $followed_by_count,
                                $follows_count,
                                $media_count,
                                $charts,
                                $accumulate_down_30,
                                $followed_by_grow_1,
                                $followed_by_grow_7,
                                $followed_by_grow_15,
                                $followed_by_grow_30,
                                $most_mentions,
                                $most_hashtags,
                                $last_post,
                                $post_by_engagement_rate,
                                $av_engagement,
                                $id_profile
                          );
            $res = db_query_table_results($query, $values, 1);
        }
        else{
            $query = "INSERT INTO ".DB_RESULTS_PREFIX."instagram_profiles VALUES($10, '$11', '$12', '$13', '$14', '$15', ".($category!="NULL"?"'$16'":"$16").", '$17', $18, $19, $20, '$21', $22, $23, $24, $25, $26, '$27', '$28', '$29', '$30', $31, $32, NOW());";
            $values = array(
                                $id_profile, 
                                $username, 
                                $name, 
                                $bio,
                                $website,
                                $picture,
                                $category,
                                $in_owloo_from, 
                                $followed_by_count,
                                $follows_count,
                                $media_count,
                                $charts,
                                $accumulate_down_30,
                                $followed_by_grow_1,
                                $followed_by_grow_7,
                                $followed_by_grow_15,
                                $followed_by_grow_30,
                                $most_mentions,
                                $most_hashtags,
                                $last_post,
                                $post_by_engagement_rate,
                                $av_engagement,
                                $general_ranking
                           );
            $res = db_query_table_results($query, $values, 1);
        }

    }

    if(!$generate_new_profile){
        //General ranking
        $query = 'SELECT id FROM '.DB_RESULTS_PREFIX.'instagram_profiles ORDER BY followed_by_count DESC, followed_by_grow_30 DESC;';
        $que_pages = db_query_table_results($query, array());
        $count = 1;
        while($pages = mysql_fetch_assoc($que_pages)){
            $query = "UPDATE ".DB_RESULTS_PREFIX."instagram_profiles SET general_ranking = $10 WHERE id = $11;";
            $values = array($count, $pages['id']);
            $res = db_query_table_results($query, $values, 1);
            $count++;
        }
    }else{
        //General ranking
        $query = 'SELECT COUNT( * ) +1 AS rank
                  FROM '.DB_RESULTS_PREFIX.'instagram_profiles
                  WHERE (followed_by_count, followed_by_grow_30) >
                      (
                          SELECT followed_by_count, followed_by_grow_30
                          FROM '.DB_RESULTS_PREFIX.'instagram_profiles
                          WHERE id = $10
                          LIMIT 1
                      );';
        $que_pages = db_query_table_results($query, array($generate_new_profile_id));
        while($page = mysql_fetch_assoc($que_pages)){
            $query = "UPDATE ".DB_RESULTS_PREFIX."instagram_profiles SET general_ranking = $10 WHERE id = $11;";
            $values = array($page['rank'], $generate_new_profile_id);
            $res = db_query_table_results($query, $values, 1);
        }
        
        //return the page data
        $query = 'SELECT * FROM '.DB_RESULTS_PREFIX.'instagram_profiles WHERE id = $10;';
        $que_page = db_query_table_results($query, array($generate_new_profile_id));
        if($page = mysql_fetch_assoc($que_page)){
            header('Content-Type: application/json');
            
            $page['charts'] = json_decode($page['charts'], true);
            $page['most_mentions'] = json_decode($page['most_mentions'], true);
            $page['most_hashtags'] = json_decode($page['most_hashtags'], true);
            $page['last_post'] = json_decode($page['last_post'], true);
            $page['post_by_engagement_rate'] = json_decode($page['post_by_engagement_rate'], true);
            
            echo json_encode(array('profile_data' => $page));
            die();
        }else{
            header('Content-Type: application/json');
            echo json_encode(array('message_code' => 3, 'description' => 'Sorry, an error has occurred'));
            die();
        }
    }
    
    if(!$generate_new_profile){
        error_log('   Instagram Profile (f): '.date('d m Y H:i:s'));
    }
