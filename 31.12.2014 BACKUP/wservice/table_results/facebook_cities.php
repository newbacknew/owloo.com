<?php
    
    set_time_limit(0);
    
    error_log('   Facebook City (i): '.date('d m Y H:i:s'));
    
    require_once(__DIR__.'/../config.php');
    
    function get_city_date_last_update($count){
        $sql = 'SELECT date 
                  FROM '.DB_FACEBOOK_PREFIX.'record_city_3_1
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
    
    function getCrecimiento($id_city, $last_update, $days){
        $sql = "SELECT total_user, (total_user - (
                            SELECT total_user 
                            FROM ".DB_FACEBOOK_PREFIX."record_city_3_1 
                            WHERE date = (
                                        SELECT date 
                                        FROM ".DB_FACEBOOK_PREFIX."record_city_3_1 
                                        WHERE id_city = $3
                                              AND DATE_SUB('$1',INTERVAL $2 DAY) <= date
                                        ORDER BY 1 ASC 
                                        LIMIT 1
                                    ) 
                                AND id_city = $3
                        )) cambio 
                FROM ".DB_FACEBOOK_PREFIX."record_city_3_1
                WHERE id_city = $3 
                    AND date = '$1';
                ";
        
        $que = db_query($sql, array($last_update, $days, $id_city));
        if($fila = mysql_fetch_assoc($que)){
            return $fila['cambio'];
        }
        return NULL;
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
    
    function get_city_history($id_city, $last_update, $days){
        $sql = "SELECT total_user, date 
                FROM ".DB_FACEBOOK_PREFIX."record_city_3_1
                WHERE id_city = $1
                    AND DATE_SUB(STR_TO_DATE('$2', '%Y-%m-%d'),INTERVAL $3 DAY) <= date
                ORDER BY 2 ASC;
                ";
        $que = db_query($sql, array($id_city, $last_update, $days));
        $seriesData = array(); //Estadística vertical. Cantidad de usuarios que posee en Facebook
        $seriesDataMin = 0; //Número mínimo de usuarios
        $seriesDataMax = 0; //Número máximo de usuarios
        $xAxisCategories = array(); //Estadística horizontal. Fechas de los datos
        $ban = 1; //Bandera 
        $cont = 1;
        $_num_rango = 3;
        $_num_discard = mysql_num_rows($que) - ($_num_rango * floor(mysql_num_rows($que)/$_num_rango));
        while($fila = mysql_fetch_assoc($que)){
            
            if($_num_discard-- > 0) continue;
            if($cont % $_num_rango == 0){
                //Formatear fecha
                $auxformat = explode("-", $fila['date']);
                $dia = $auxformat[2];
                $mes = getMes($auxformat[1], 'short');
                $year = $semister_year = substr($auxformat[0],2,2);
                
                $seriesData[] = $fila['total_user'];
                $xAxisCategories[] = "'".$dia." ".$mes."'";
                
                if($ban == 1){
                    $seriesDataMin =    $fila['total_user'];
                    $seriesDataMax =    $fila['total_user'];
                    $ban = 0;
                }
                else{
                    if($fila['total_user'] < $seriesDataMin)
                        $seriesDataMin = $fila['total_user'];
                    else
                    if($fila['total_user'] > $seriesDataMax)
                        $seriesDataMax = $fila['total_user'];
                }
            }
            $cont++;
        }
        return array(
                    'series_data' => implode(',', $seriesData),
                    'series_data_min' => $seriesDataMin,
                    'series_data_max' => $seriesDataMax,
                    'x_axis' => implode(',', $xAxisCategories)
            );
    }
        
    //Países
    $countries = array();
    $query = 'SELECT id_country, code, nombre, name, idiom FROM '.DB_FACEBOOK_PREFIX.'country_3_1 ORDER BY 1;';
    $que = db_query($query, array());
    
    while($fila = mysql_fetch_assoc($que)){
        $countries[$fila['id_country']] = array('code' => $fila['code'], 'name' => $fila['nombre'], 'name_en' => $fila['name'], 'idiom' => $fila['idiom']);
    }
    
    $query = "SELECT id_city, name, id_country FROM ".DB_FACEBOOK_PREFIX."city_3_1 WHERE active_fb_get_data = 1 AND active = 1;";
    $que_cities = db_query($query, array());
    
    $cities_last_update = get_city_date_last_update(mysql_num_rows($que_cities));
    
    while($city = mysql_fetch_assoc($que_cities)){
        
        $id_city = $city['id_city'];
        
        $name = $city['name'];
        
        $country_code = $countries[$city['id_country']]['code'];
        
        $idiom = (!empty($countries[$city['id_country']]['idiom'])?$countries[$city['id_country']]['idiom']:'NULL');
        
        $country_code = $countries[$city['id_country']]['code'];
        
        $grow_90 = getCrecimiento($id_city, $cities_last_update, 90);
        
        $query = "SELECT total_user, total_female, total_male FROM ".DB_FACEBOOK_PREFIX."record_city_3_1 WHERE id_city = $1 AND date = '$2';";
        $que_audience = db_query($query, array($city['id_city'], $cities_last_update));
        
        if($audience = mysql_fetch_assoc($que_audience)){
            $total_user = $audience['total_user'];
            $total_female = $audience['total_female'];
            $total_male = $audience['total_male'];
        }
        
        $chart_history = get_city_history($id_city, $cities_last_update, 30);
        $chart_history = json_encode($chart_history);
       
        $query = 'SELECT id_city FROM '.DB_RESULTS_PREFIX.'facebook_cities WHERE id_city = $10;';
        $que_city = db_query_table_results($query, array($id_city));
        if($row = mysql_fetch_assoc($que_city)){
            $query = "UPDATE ".DB_RESULTS_PREFIX."facebook_cities SET 
                                                name = '$10',
                                                country_code = '$11',
                                                idiom = ".($idiom!="NULL"?"'$12'":"$12").",
                                                total_user = $13,
                                                total_female = $14,
                                                total_male = $15,
                                                grow_90 = $16,
                                                chart_history = '$17',
                                                updated_at = NOW()
                                                
                                                WHERE id_city = $18;";
            $values = array(
                                $name,
                                $country_code,
                                $idiom,
                                $total_user,
                                $total_female,
                                $total_male,
                                $grow_90,
                                $chart_history,
                                $id_city
                          );
            $res = db_query_table_results($query, $values, 1);
        }
        else{
            $query = "INSERT INTO ".DB_RESULTS_PREFIX."facebook_cities VALUES($10, '$11', '$12', ".($idiom!="NULL"?"'$13'":"$13").", $14, $15, $16, $17, '$18', NOW());";
            $values = array(
                                $id_city,
                                $name,
                                $country_code,
                                $idiom,
                                $total_user,
                                $total_female,
                                $total_male,
                                $grow_90,
                                $chart_history
                           );
            $res = db_query_table_results($query, $values, 1);
        }
    }
    
    error_log('   Facebook City (f): '.date('d m Y H:i:s'));