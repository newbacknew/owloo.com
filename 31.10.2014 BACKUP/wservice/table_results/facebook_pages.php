<?php
    set_time_limit(0);
    
    require_once(__DIR__.'/../config.php');
    
    function get_country_date_last_update($count){
        $sql = 'SELECT date 
                  FROM facebook_record_country_3_1
                  GROUP BY date 
                  HAVING count(date) = $1
                  ORDER BY 1 DESC 
                  LIMIT 1;
               ';
        $res = db_query($sql, array($count));
        if($fila = mysql_fetch_assoc($res)){
            return $fila['date'];
        }
        return NULL;
    }
    
    function get_country_audience($id_country, $date){
        $sql = 'SELECT total_user 
                  FROM facebook_record_country_3_1
                  WHERE id_country = $1 AND date = \'$2\';
               ';
        $res = db_query($sql, array($id_country, $date));
        if($fila = mysql_fetch_assoc($res)){
            return $fila['total_user'];
        }
        return NULL;
    }
    
    function get_fb_page_likes_last_update($id_page){
        $query = 'SELECT date FROM facebook_pages_likes_talking_about WHERE id_page = $1 ORDER BY date DESC LIMIT 1;';
        $que = db_query($query, array($id_page));
        if($row = mysql_fetch_assoc($que)){
           return $row['date'];
        }
        return NULL;
    }
    
    function get_fb_page_likes_nun_dates($id_page){
        $query = 'SELECT count(*) count FROM facebook_pages_likes_talking_about WHERE id_page = $1;';
        $que = db_query($query, array($id_page));
        if($row = mysql_fetch_assoc($que)){
           return $row['count'];
        }
        return 0;
    }
    
    function get_fb_page_sub_category($id_page){
        $query = 'SELECT sc1.id_sub_category id_sub_category, sub_category FROM facebook_pages_sub_categories sc1 JOIN facebook_page_sub_category sc2 ON sc1.id_sub_category = sc2.id_sub_category WHERE id_page = $1;';
        $que = db_query($query, array($id_page));
        if($row = mysql_fetch_assoc($que)){
           return array('id' => $row['id_sub_category'], 'name' => $row['sub_category']);
        }
        return array('id' => "NULL", 'name' => "NULL");
    }
    
    function get_fb_page_category($id_sub_category){
        if(is_numeric($id_sub_category)){
            $query = 'SELECT csc.id_category id_category, category FROM facebook_pages_categories_sub_categories csc JOIN facebook_page_category c ON csc.id_category = c.id_category WHERE id_sub_category = $1;';
            $que = db_query($query, array($id_sub_category));
            if($row = mysql_fetch_assoc($que)){
               return array('id' => $row['id_category'], 'name' => $row['category']);
            }
        }
        return array('id' => "NULL", 'name' => "NULL");
    }
    
    function get_fb_page_local_fans_general_last_date(){
        $sql = 'SELECT date, count(*) FROM facebook_page_local_fans_country WHERE date > DATE_SUB(DATE(NOW()), INTERVAL 6 DAY) GROUP BY 1 ORDER BY 2 DESC, 1 DESC';
        
        $res = db_query($sql, array($id_page));
        if($fila = mysql_fetch_assoc($res)){
            return $fila['date'];
        }
        return NULL;
    }
    
    function getCrecimientoFacebookFansPage($id_page, $last_update, $days){
                
        $query = "SELECT likes, (likes - (
                            SELECT likes 
                            FROM facebook_pages_likes_talking_about 
                            WHERE id_page = $1
                                AND date = (
                                        SELECT date 
                                        FROM facebook_pages_likes_talking_about 
                                        WHERE DATE_SUB('$2',INTERVAL $3 DAY) <= date
                                            AND id_page = $1
                                        ORDER BY 1 ASC 
                                        LIMIT 1
                                    )
                             LIMIT 1
                        )) cambio_likes, talking_about , (talking_about  - (
                            SELECT talking_about  
                            FROM facebook_pages_likes_talking_about 
                            WHERE id_page = $1
                                AND date = (
                                        SELECT date 
                                        FROM facebook_pages_likes_talking_about 
                                        WHERE DATE_SUB('$2',INTERVAL $3 DAY) <= date
                                            AND id_page = $1
                                        ORDER BY 1 ASC 
                                        LIMIT 1
                                    )
                             LIMIT 1
                        )) cambio_talking_about
                FROM facebook_pages_likes_talking_about
                WHERE id_page = $1 
                    AND date = '$2';
                ";
        
        $que = db_query($query, array($id_page, $last_update, $days));
        
        if($fila = mysql_fetch_assoc($que)){
            return array('likes' => $fila['cambio_likes'], 'talking_about' => $fila['cambio_talking_about']);
        }
        return array('likes' => "NULL", 'talking_about' => "NULL");;
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
    
    function getHistory30Array($id_page, $fb_page_likes_last_update){
        $sql =  'SELECT likes, talking_about, date 
                FROM facebook_pages_likes_talking_about 
                WHERE id_page = $1
                    AND DATE_SUB(STR_TO_DATE(\'$2\', \'%Y-%m-%d\'),INTERVAL 30 DAY) <= date
                ORDER BY date ASC;
                ';
        $que = db_query($sql, array($id_page, $fb_page_likes_last_update));
        
        $seriesData = ""; //Estadística vertical. Cantidad de usuarios que posee en Facebook
        $seriesData_talking_about = ""; //Estadística vertical. Cantidad de usuarios que posee en Facebook
        $seriesDataMin = 0; //Número mínimo de usuarios
        $seriesDataMax = 0; //Número máximo de usuarios
        $seriesDataMin_talking_about = 0; //Número mínimo de usuarios
        $seriesDataMax_talking_about = 0; //Número máximo de usuarios
        $xAxisCategories = ""; //Estadística horizontal. Fechas de los datos
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
                if($ban == 1){
                    $seriesData .=      $fila['likes'];
                    $seriesData_talking_about .= $fila['talking_about'];
                    $xAxisCategories .= "'".$dia." ".$mes."'";
                    $seriesDataMin =    $fila['likes'];
                    $seriesDataMax =    $fila['likes'];
                    $seriesDataMin_talking_about = $fila['talking_about'];
                    $seriesDataMax_talking_about = $fila['talking_about'];
                    $ban = 0;
                }
                else{
                    $seriesData .= ','.$fila['likes'];
                    $seriesData_talking_about .= ','.$fila['talking_about'];
                    $xAxisCategories .= ",'".$dia." ".$mes."'";
                    if($fila['likes'] < $seriesDataMin)
                        $seriesDataMin = $fila['likes'];
                    else
                    if($fila['likes'] > $seriesDataMax)
                        $seriesDataMax = $fila['likes'];
                    
                    if($fila['talking_about'] < $seriesDataMin_talking_about)
                        $seriesDataMin_talking_about = $fila['talking_about'];
                    else
                    if($fila['talking_about'] > $seriesDataMax_talking_about)
                        $seriesDataMax_talking_about = $fila['talking_about'];
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
                'series_data' => $seriesData,
                'series_data_min' => $seriesDataMin,
                'series_data_max' => $seriesDataMax,
                'x_axis' => $xAxisCategories,
                'step' => $step_1
        );
        
        $talking_about = array(
                'series_data' => $seriesData_talking_about,
                'series_data_min' => $seriesDataMin_talking_about,
                'series_data_max' => $seriesDataMax_talking_about,
                'x_axis' => $xAxisCategories,
                'step' => $step_1
        );
        
        return array('likes' => $likes, 'talking_about' => $talking_about);
        
    }
    
    //Países
    $countries = array();
    $query = 'SELECT id_country, code, nombre, name FROM facebook_country_3_1 ORDER BY 1;'; 
    $que = db_query($query, array());
    
    $country_date_last_update = get_country_date_last_update(mysql_num_rows($que));
    
    while($fila = mysql_fetch_assoc($que)){
        $countries[$fila['id_country']] = array('id' => $fila['id_country'], 'code' => $fila['code'], 'name' => $fila['nombre'], 'name_en' => $fila['name'], 'audience' => get_country_audience($fila['id_country'], $country_date_last_update));
    }
    
    $id_country_uses = array();

    $query = 'SELECT * FROM facebook_page WHERE active = 1 ORDER BY id_page;';
    $que_pages = db_query($query, array());
    while($pages = mysql_fetch_assoc($que_pages)){
       
        $id_page = $pages['id_page'];
        $parent = (!empty($pages['parent'])?$pages['parent']:"0");
        $fb_id = $pages['fb_id'];
        $username = $pages['username'];
        $name = $pages['name'];
        $about = $pages['about'];
        $description = (!empty($pages['description'])?$pages['description']:"NULL");
        $link = $pages['link'];
        $picture = $pages['picture'];
        $cover = $pages['cover'];
        $is_verified = $pages['is_verified'];
        
        $fb_page_likes_last_update = get_fb_page_likes_last_update($id_page);
        $fb_page_likes_nun_dates = get_fb_page_likes_nun_dates($id_page);
        $history = getHistory30Array($id_page, $fb_page_likes_last_update);
        
        $likes = $pages['likes'];
        $likes_history_30 = json_encode($history['likes']);
        $likes_grow_1 = "NULL";
        $likes_grow_7 = "NULL";
        $likes_grow_15 = "NULL";
        $likes_grow_30 = "NULL";
        $likes_grow_60 = "NULL";
        
        $talking_about = $pages['talking_about'];
        $talking_about_history_30 = json_encode($history['talking_about']);
        $talking_about_grow_1 = "NULL";
        $talking_about_grow_7 = "NULL";
        $talking_about_grow_15 = "NULL";
        $talking_about_grow_30 = "NULL";
        $talking_about_grow_60 = "NULL";
        
        
        if($fb_page_likes_nun_dates > 1){
            $aux = getCrecimientoFacebookFansPage($id_page, $fb_page_likes_last_update, 1);
            $likes_grow_1 = $aux['likes'];
            $talking_about_grow_1 = $aux['talking_about'];
        }
        if($fb_page_likes_nun_dates > 7){
            $aux = getCrecimientoFacebookFansPage($id_page, $fb_page_likes_last_update, 7);
            $likes_grow_7 = $aux['likes'];
            $talking_about_grow_7 = $aux['talking_about'];
        }
        if($fb_page_likes_nun_dates > 15){
            $likes_grow_15 = getCrecimientoFacebookFansPage($id_page, $fb_page_likes_last_update, 15);
            $aux = getCrecimientoFacebookFansPage($id_page, $fb_page_likes_last_update, 15);
            $likes_grow_15 = $aux['likes'];
            $talking_about_grow_15 = $aux['talking_about'];
        }
        if($fb_page_likes_nun_dates > 30){
            $aux = getCrecimientoFacebookFansPage($id_page, $fb_page_likes_last_update, 30);
            $likes_grow_30 = $aux['likes'];
            $talking_about_grow_30 = $aux['talking_about'];
        }
        if($fb_page_likes_nun_dates > 60){
            $aux = getCrecimientoFacebookFansPage($id_page, $fb_page_likes_last_update, 60);
            $likes_grow_60 = $aux['likes'];
            $talking_about_grow_60 = $aux['talking_about'];
        }
        
        
        $country_code = "NULL";
        $country_name = "NULL";
        $country_name_en = "NULL";
        $country_ranking = "NULL";
        if(!empty($pages['location'])){
            $country_code = $countries[$pages['location']]['code'];
            $country_name = $countries[$pages['location']]['name'];
            $country_name_en = $countries[$pages['location']]['name_en'];
            if(!in_array($pages['location'], $id_country_uses))
                $id_country_uses[] = $pages['location'];
        }
        
        $first_country_code = "NULL";
        $first_country_name = "NULL";
        $first_country_name_en = "NULL";
        $first_local_fans_country_audience = "NULL";
        if(!empty($pages['first_local_fans_country'])){
            $first_country_code = $countries[$pages['first_local_fans_country']]['code'];
            $first_country_name = $countries[$pages['first_local_fans_country']]['name'];
            $first_country_name_en = $countries[$pages['first_local_fans_country']]['name_en'];
            $first_local_fans_country_audience = $countries[$pages['first_local_fans_country']]['audience'];
            
            if(!in_array($pages['first_local_fans_country'], $id_country_uses))
                $id_country_uses[] = $pages['first_local_fans_country'];
            
            
        }
        
        $idiom = "NULL";
        if($pages['hispanic'] == 1){
            $idiom = "es";
        }
        
        //Category
        $sub_category = get_fb_page_sub_category($id_page);
        $sub_category_id = $sub_category['id'];
        $sub_category_name = $sub_category['name'];
        //Subcategory
        $category = get_fb_page_category($sub_category_id);
        $category_id = $category['id'];
        $category_name = $category['name'];
        
        $general_ranking = 'NULL';
        $first_local_fans_country_ranking = 'NULL';
        
        $in_owloo_from = $pages['date_add'];
        
        $query = 'SELECT id_page FROM facebook_pages WHERE id_page = $10;';
        $que_page = db_query_table_results($query, array($id_page));
        if($row = mysql_fetch_assoc($que_page)){
            $query = "UPDATE facebook_pages SET  
                                                parent = $10,
                                                fb_id = $11, 
                                                username = '$12', 
                                                name = '$13', 
                                                about = ".($about!="NULL"?"'$14'":"$14").",
                                                description = ".($description!="NULL"?"'$15'":"$15").",
                                                link = '$16',
                                                picture = '$17',
                                                cover = ".($cover!="NULL"?"'$18'":"$18").",
                                                is_verified = $19, 
                                                likes = $20, 
                                                likes_history_30 = '$21',
                                                likes_grow_1 = $22, 
                                                likes_grow_7 = $23, 
                                                likes_grow_15 = $24, 
                                                likes_grow_30 = $25, 
                                                likes_grow_60 = $26, 
                                                talking_about = $27, 
                                                talking_about_history_30 = '$28',
                                                talking_about_grow_1 = $29, 
                                                talking_about_grow_7 = $30, 
                                                talking_about_grow_15 = $31, 
                                                talking_about_grow_30 = $32, 
                                                talking_about_grow_60 = $33,
                                                country_code = ".($country_code!="NULL"?"'$34'":"$34").", 
                                                country_name = ".($country_name!="NULL"?"'$35'":"$35").", 
                                                country_name_en = ".($country_name_en!="NULL"?"'$36'":"$36").", 
                                                first_country_code = ".($first_country_code!="NULL"?"'$37'":"$37").", 
                                                first_country_name = ".($first_country_name!="NULL"?"'$38'":"$38").",
                                                first_country_name_en = ".($first_country_name_en!="NULL"?"'$39'":"$39").", 
                                                idiom = ".($idiom!="NULL"?"'$40'":"$40").",
                                                category_id = $41,
                                                category_name = ".($category_name!="NULL"?"'$42'":"$42").",
                                                sub_category_id = $43,
                                                sub_category_name = ".($sub_category_name!="NULL"?"'$44'":"$44").",
                                                first_local_fans_country_audience = $45,
                                                in_owloo_from = '$46',
                                                updated_at = NOW()
                                                
                                                WHERE id_page = $47;";
            $values = array(
                                $parent,
                                $fb_id, 
                                $username,
                                $name,
                                $about,
                                $description,
                                $link,
                                $picture,
                                $cover,
                                $is_verified, 
                                $likes,
                                $likes_history_30,  
                                $likes_grow_1, 
                                $likes_grow_7, 
                                $likes_grow_15, 
                                $likes_grow_30, 
                                $likes_grow_60, 
                                $talking_about,
                                $talking_about_history_30,
                                $talking_about_grow_1, 
                                $talking_about_grow_7, 
                                $talking_about_grow_15, 
                                $talking_about_grow_30, 
                                $talking_about_grow_60, 
                                $country_code, 
                                $country_name, 
                                $country_name_en, 
                                $first_country_code, 
                                $first_country_name, 
                                $first_country_name_en, 
                                $idiom,
                                $category_id,
                                $category_name,
                                $sub_category_id,
                                $sub_category_name,
                                $first_local_fans_country_audience,
                                $in_owloo_from,
                                $id_page
                          );
            $res = db_query_table_results($query, $values, 1);
        }
        else{
            $query = "INSERT INTO facebook_pages VALUES($10, $11, $12, '$13', '$14', ".($about!="NULL"?"'$15'":"$15").", ".($description!="NULL"?"'$16'":"$16").", '$17', '$18', ".($cover!="NULL"?"'$19'":"$19").", $20, $21, '$22', $23, $24, $25, $26, $27, $28, '$29', $30, $31, $32, $33, $34, ".($country_code!="NULL"?"'$35'":"$35").", ".($country_name!="NULL"?"'$36'":"$36").", ".($country_name_en!="NULL"?"'$37'":"$37").", $38, ".($first_country_code!="NULL"?"'$39'":"$39").", ".($first_country_name!="NULL"?"'$40'":"$40").", ".($first_country_name_en!="NULL"?"'$41'":"$41").", ".($idiom!="NULL"?"'$42'":"$42").", $43, ".($category_name!="NULL"?"'$44'":"$44").", $45, ".($sub_category_name!="NULL"?"'$46'":"$46").", $47, $48, $49, '$50', NOW());";
            $values = array(
                                $id_page, 
                                $parent,
                                $fb_id, 
                                $username,
                                $name, 
                                $about,
                                $description,
                                $link,
                                $picture,
                                $cover,
                                $is_verified, 
                                $likes,
                                $likes_history_30,  
                                $likes_grow_1, 
                                $likes_grow_7, 
                                $likes_grow_15, 
                                $likes_grow_30, 
                                $likes_grow_60, 
                                $talking_about,
                                $talking_about_history_30,
                                $talking_about_grow_1, 
                                $talking_about_grow_7, 
                                $talking_about_grow_15, 
                                $talking_about_grow_30, 
                                $talking_about_grow_60, 
                                $country_code, 
                                $country_name,
                                $country_name_en,
                                $country_ranking,
                                $first_country_code, 
                                $first_country_name, 
                                $first_country_name_en, 
                                $idiom,
                                $category_id,
                                $category_name,
                                $sub_category_id,
                                $sub_category_name,
                                $general_ranking,
                                $first_local_fans_country_ranking,
                                $first_local_fans_country_audience,
                                $in_owloo_from
                           );
            $res = db_query_table_results($query, $values, 1);
        }

    }
    
    //General ranking
    $query = 'SELECT id_page FROM facebook_pages WHERE parent = 0 ORDER BY likes DESC, talking_about DESC;';
    $que_pages = db_query_table_results($query, array());
    $count = 1;
    while($pages = mysql_fetch_assoc($que_pages)){
        $query = "UPDATE facebook_pages SET general_ranking = $10 WHERE id_page = $11;";
        $values = array($count, $pages['id_page']);
        $res = db_query_table_results($query, $values, 1);
        $count++;
    }
    
    //Local fans country
    foreach ($countries as $country) {
        if(in_array($country['id'], $id_country_uses)){
            $sql = "SELECT id_page, likes 
                       FROM facebook_pages_local_fans
                       WHERE country_code = '$10' 
                       ORDER BY likes DESC
                     ;";
            $que_pages = db_query_table_results($sql, array($country['code']));
            $count = 1;
            while($pages = mysql_fetch_assoc($que_pages)){
                $query = "UPDATE facebook_pages SET first_local_fans_country_ranking = $10 WHERE id_page = $11 AND first_country_code = '$12';";
                $values = array($count, $pages['id_page'], $country['code']);
                $res = db_query_table_results($query, $values, 1);
                
                $query = "UPDATE facebook_pages SET country_ranking = $10 WHERE id_page = $11 AND country_code = '$12';";
                $values = array($count, $pages['id_page'], $country['code']);
                $res = db_query_table_results($query, $values, 1);
            
                
                $count++;
            }
        }
    }