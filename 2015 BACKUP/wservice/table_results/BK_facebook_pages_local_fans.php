<?php
    
    set_time_limit(0);
    
    error_log('   Facebook Page Local Fans (i): '.date('d m Y H:i:s'));
    
    require_once(__DIR__.'/../config.php');
    
    function get_fb_page_local_fans_general_last_date(){
        $sql = 'SELECT date, count(*) FROM '.DB_FACEBOOK_PREFIX.'page_local_fans_country WHERE date > DATE_SUB(DATE(NOW()), INTERVAL 6 DAY) GROUP BY 1 ORDER BY 2 DESC, 1 DESC';
        
        $res = db_query($sql, array($id_page));
        if($fila = mysql_fetch_assoc($res)){
            return $fila['date'];
        }
        return NULL;
    }
    
    function get_local_fans_last_date($page_id, $country_id){
        $sql = 'SELECT date FROM '.DB_FACEBOOK_PREFIX.'page_local_fans_country
                WHERE id_page = $1
                    AND id_country = $2
                ORDER BY 1 DESC
                LIMIT 1;';
        
        $res = db_query($sql, array($page_id, $country_id));
        if($fila = mysql_fetch_assoc($res)){
            return $fila['date'];
        }
        return NULL;
    }
    
    function get_likes_last_register($page_id, $country_id){
        $sql = 'SELECT likes FROM '.DB_FACEBOOK_PREFIX.'page_local_fans_country
                WHERE id_page = $1
                    AND id_country = $2
                ORDER BY 1 DESC
                LIMIT 1;';
        
        $res = db_query($sql, array($page_id, $country_id));
        if($fila = mysql_fetch_assoc($res)){
            return $fila['likes'];
        }
        return NULL;
    }
    
    function get_fb_page_likes_nun_dates($page_id, $country_id){
        $query = 'SELECT count(*) count FROM '.DB_FACEBOOK_PREFIX.'page_local_fans_country
                  WHERE id_page = $1
                    AND id_country = $2;';
        $que = db_query($query, array($page_id, $country_id));
        if($row = mysql_fetch_assoc($que)){
           return $row['count'];
        }
        return 0;
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
    
    function getCrecimientoFacebookFansPage($page_id, $country_id, $last_update, $days){
                
        $query = 'SELECT likes, (likes - (
                            SELECT likes 
                            FROM '.DB_FACEBOOK_PREFIX.'page_local_fans_country 
                            WHERE id_page = $1
                                AND id_country = $2
                                AND date = (
                                        SELECT date 
                                        FROM '.DB_FACEBOOK_PREFIX.'page_local_fans_country 
                                        WHERE DATE_SUB(\'$3\',INTERVAL $4 DAY) <= date
                                            AND id_page = $1
                                            AND id_country = $2
                                        ORDER BY 1 ASC 
                                        LIMIT 1
                                    )
                             LIMIT 1
                        )) cambio_likes
                FROM '.DB_FACEBOOK_PREFIX.'page_local_fans_country
                WHERE id_page = $1 
                    AND id_country = $2
                    AND date = \'$3\';
                ';
        
        $que = db_query($query, array($page_id, $country_id, $last_update, $days));
        
        if($fila = mysql_fetch_assoc($que)){
            return $fila['cambio_likes'];
        }
        return NULL;
    }
    
    function getHistory30Array($page_id, $country_id, $fb_page_likes_last_update, $days = 30){
        $sql =  'SELECT likes, date 
                FROM '.DB_FACEBOOK_PREFIX.'page_local_fans_country 
                WHERE id_page = $1
                    AND id_country = $2
                    AND DATE_SUB(STR_TO_DATE(\'$3\', \'%Y-%m-%d\'),INTERVAL $4 DAY) <= date
                ORDER BY date ASC;
                ';
        $que = db_query($sql, array($page_id, $country_id, $fb_page_likes_last_update, $days));
        
        $seriesData = array(); //Estadística vertical. Cantidad de usuarios que posee en Facebook
        $seriesDataMin = NULL; //Número mínimo de usuarios
        $seriesDataMax = NULL; //Número máximo de usuarios
        $xAxisCategories = array(); //Estadística horizontal. Fechas de los datos
        
        $ban = 1; //Bandera 
        $cont = 1;
        $_num_rango = 1;
        $_num_discard = mysql_num_rows($que) - ($_num_rango * floor(mysql_num_rows($que)/$_num_rango));
        while($fila = mysql_fetch_assoc($que)){
            if($_num_discard-- > 0) continue;
            if($cont % $_num_rango == 0){
                //Formatear fecha
                $auxformat = explode("-", $fila['date']);
                $dia = $auxformat[2];
                $mes = getMes($auxformat[1], 'short');
                $year = substr($auxformat[0],2,2);
                
                $seriesData[]               = $fila['likes'];
                $xAxisCategories[]          = $dia.' '.$mes.' '.$year;
                
                if($ban == 1){
                    $seriesDataMin =    $fila['likes'];
                    $seriesDataMax =    $fila['likes'];
                    
                    $ban = 0;
                }
                else{
                    if($fila['likes'] < $seriesDataMin)
                        $seriesDataMin = $fila['likes'];
                    else
                    if($fila['likes'] > $seriesDataMax)
                        $seriesDataMax = $fila['likes'];
                    
                }
            }
            $cont++;
        }
        
        $step_1 = 1;
        if($cont-1 > 11)
            $step_1 = 2;
        if($cont-1 > 21)
            $step_1 = 3;
        
        $likes = array(
                'series_data' => implode(',', $seriesData),
                'series_data_min' => $seriesDataMin,
                'series_data_max' => $seriesDataMax,
                'x_axis' => implode(',', $xAxisCategories),
                'step' => $step_1
        );
        
        return $likes;
        
    }
        
    //Países
    $countries = array();
    $query = 'SELECT id_country, code, nombre, slug FROM '.DB_FACEBOOK_PREFIX.'country_3_1 ORDER BY 1;';
    $que = db_query($query, array());
    
    while($fila = mysql_fetch_assoc($que)){
        $countries[$fila['id_country']] = array('code' => $fila['code'], 'name' => $fila['nombre'], 'slug' => $fila['slug']);
    }
    
    $local_fans_last_date = get_fb_page_local_fans_general_last_date();
    
    $query = "DELETE FROM ".DB_RESULTS_PREFIX."facebook_pages_local_fans;";
    $values = array();
    $res = db_query_table_results($query, $values, 1);
    
    $query = 'SELECT * FROM '.DB_FACEBOOK_PREFIX.'page_local_fans_country WHERE date = \'$1\';';
    $que_pages = db_query($query, array($local_fans_last_date));
    
    $last_id_page = NULL;
    $page_info = array();
    
    while($pages = mysql_fetch_assoc($que_pages)){
        
        if($last_id_page != $pages['id_page']){
            $page_info = array();
            $query = "SELECT id_page, parent, fb_id, name, username, is_verified, likes, likes_grow_7, talking_about, category_id, sub_category_id FROM ".DB_RESULTS_PREFIX."facebook_pages WHERE id_page = $10;";
            $que_page = db_query_table_results($query, array($pages['id_page']));
            
            if($page_info = mysql_fetch_assoc($que_page)){
                $last_id_page = $pages['id_page'];
            }
            else {
                continue;
            }
        }
       
        $id_page = $pages['id_page'];
                
        $country_code = $countries[$pages['id_country']]['code'];
        $country_name = $countries[$pages['id_country']]['name'];
        $country_slug = $countries[$pages['id_country']]['slug'];
        
        $likes = $pages['likes'];
        //$likes = get_likes_last_register($id_page, $pages['id_country']);
        
        /*$current_local_fans_last_update = get_local_fans_last_date($id_page, $pages['id_country']);
        $fb_page_likes_nun_dates = get_fb_page_likes_nun_dates($id_page, $pages['id_country']);
        $likes_history = array();
        $likes_history['chart'] = getHistory30Array($id_page, $pages['id_country'], $current_local_fans_last_update, 30);
        
        $array_days = array(1, 7, 15, 30);
        foreach ($array_days as $day) {
            $likes_history['local_fans_grow']['grow_'.$day] = NULL;
            if($fb_page_likes_nun_dates > $day){
                $likes_history['local_fans_grow']['grow_'.$day] = getCrecimientoFacebookFansPage($id_page, $pages['id_country'], $current_local_fans_last_update, $day);
            }
        }
        
        $likes_history = json_encode($likes_history);*/
        
        $query = "INSERT INTO ".DB_RESULTS_PREFIX."facebook_pages_local_fans VALUES(NULL, $10, $11, $12, '$13', '$14', $15, $16, $17, $18, $19, $20, '$21', '$22', '$23', $24, NOW());";
        $values = array(
                            $id_page,
                            $page_info['parent'],
                            $page_info['fb_id'],
                            $page_info['username'],
                            $page_info['name'],
                            $page_info['is_verified'],
                            $page_info['likes'],
                            (!empty($page_info['likes_grow_7'])?$page_info['likes_grow_7']:'NULL'),
                            $page_info['talking_about'],
                            (!empty($page_info['category_id'])?$page_info['category_id']:'NULL'),
                            (!empty($page_info['sub_category_id'])?$page_info['sub_category_id']:'NULL'),
                            $country_code,
                            $country_name,
                            $country_slug,
                            $likes/*,
                            $likes_history*/
                       );
        $res = db_query_table_results($query, $values, 1);
        
    }

    //Update ranking local fans country
    
    $countries = array();
    $query = 'SELECT id_country, code
                FROM '.DB_FACEBOOK_PREFIX.'country_3_1
                WHERE code in(SELECT DISTINCT country_code FROM owloo_results.'.DB_RESULTS_PREFIX.'facebook_pages WHERE country_code is not null)
                    OR code in(SELECT DISTINCT first_country_code FROM owloo_results.'.DB_RESULTS_PREFIX.'facebook_pages WHERE first_country_code is not null)
                ORDER BY 1;'; 
    $que = db_query($query, array());
    
    while($country = mysql_fetch_assoc($que)){
        $sql = "SELECT id_page, likes_local_fans 
                   FROM ".DB_RESULTS_PREFIX."facebook_pages_local_fans
                   WHERE country_code = '$10' 
                   ORDER BY likes_local_fans DESC
                 ;";
        $que_pages = db_query_table_results($sql, array($country['code']));
        $count = 1;
        while($pages = mysql_fetch_assoc($que_pages)){
            $query = "UPDATE ".DB_RESULTS_PREFIX."facebook_pages SET first_local_fans_country_ranking = $10 WHERE id_page = $11 AND first_country_code = '$12';";
            $values = array($count, $pages['id_page'], $country['code']);
            $res = db_query_table_results($query, $values, 1);
            
            $query = "UPDATE ".DB_RESULTS_PREFIX."facebook_pages SET country_ranking = $10 WHERE id_page = $11 AND country_code = '$12';";
            $values = array($count, $pages['id_page'], $country['code']);
            $res = db_query_table_results($query, $values, 1);
            
            $count++;
        }
    }
    
    error_log('   Facebook Page Local Fans (f): '.date('d m Y H:i:s'));