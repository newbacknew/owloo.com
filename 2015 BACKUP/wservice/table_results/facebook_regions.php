<?php

    set_time_limit(0);
    
    error_log('   Facebook Region (i): '.date('d m Y H:i:s'));
    
    require_once(__DIR__.'/../config.php');
    
    function get_region_date_last_update($count){
        $sql = 'SELECT date 
                  FROM '.DB_FACEBOOK_PREFIX.'record_region_3_1
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
    
    function getCrecimiento($id_region, $last_update, $days){
        $sql = 'SELECT total_user, (total_user - (
                            SELECT total_user 
                            FROM '.DB_FACEBOOK_PREFIX.'record_region_3_1 
                            WHERE date = (
                                        SELECT date 
                                        FROM '.DB_FACEBOOK_PREFIX.'record_region_3_1 
                                        WHERE id_region = $3
                                              AND DATE_SUB(\'$1\',INTERVAL $2 DAY) <= date
                                        ORDER BY 1 ASC 
                                        LIMIT 1
                                    ) 
                                AND id_region = $3
                        )) cambio 
                FROM '.DB_FACEBOOK_PREFIX.'record_region_3_1
                WHERE id_region = $3 
                    AND date = \'$1\';
                ';
        
        $que = db_query($sql, array($last_update, $days, $id_region));
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
    
    function get_region_history($id_region, $last_update, $days){
        $sql = "SELECT total_user, date 
                FROM ".DB_FACEBOOK_PREFIX."record_region_3_1
                WHERE id_region = $1
                    AND DATE_SUB(STR_TO_DATE('$2', '%Y-%m-%d'),INTERVAL $3 DAY) <= date
                ORDER BY 2 ASC;
                ";
        $que = db_query($sql, array($id_region, $last_update, $days));
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
    
    $query = 'SELECT id_region, name, slug, id_country FROM '.DB_FACEBOOK_PREFIX.'region_3_1 WHERE active_fb_get_data = 1 AND active = 1;';
    $que_regions = db_query($query, array());
    
    $regions_last_update = get_region_date_last_update(mysql_num_rows($que_regions));
    
    while($region = mysql_fetch_assoc($que_regions)){
        
        $id_region = $region['id_region'];
        
        $name = $region['name'];
        
        $slug = $region['slug'];
        
        $country_code = $countries[$region['id_country']]['code'];
        
        $idiom = (!empty($countries[$region['id_country']]['idiom'])?$countries[$region['id_country']]['idiom']:'NULL');
        
        $grow_90 = getCrecimiento($id_region, $regions_last_update, 90);
        
        $query = 'SELECT total_user, total_female, total_male FROM '.DB_FACEBOOK_PREFIX.'record_region_3_1 WHERE id_region = $1 AND date = \'$2\';';
        $que_audience = db_query($query, array($region['id_region'], $regions_last_update));
        
        if($audience = mysql_fetch_assoc($que_audience)){
            $total_user = $audience['total_user'];
            $total_female = $audience['total_female'];
            $total_male = $audience['total_male'];
        }
        
        $chart_history = get_region_history($id_region, $regions_last_update, 30);
        $chart_history = json_encode($chart_history);
       
        $query = 'SELECT id_region FROM '.DB_RESULTS_PREFIX.'facebook_regions WHERE id_region = $10;';
        $que_region = db_query_table_results($query, array($id_region));
        if($row = mysql_fetch_assoc($que_region)){
            $query = "UPDATE ".DB_RESULTS_PREFIX."facebook_regions SET 
                                                name = '$10',
                                                slug = '$11',
                                                country_code = '$12',
                                                idiom = ".($idiom!="NULL"?"'$13'":"$13").",
                                                total_user = $14,
                                                total_female = $15,
                                                total_male = $16,
                                                grow_90 = $17,
                                                chart_history = '$18',
                                                updated_at = NOW()
                                                
                                                WHERE id_region = $19;";
            $values = array(
                                $name,
                                $slug,
                                $country_code,
                                $idiom,
                                $total_user,
                                $total_female,
                                $total_male,
                                $grow_90,
                                $chart_history,
                                $id_region
                          );
            $res = db_query_table_results($query, $values, 1);
        }
        else{
            $query = "INSERT INTO ".DB_RESULTS_PREFIX."facebook_regions VALUES($10, '$11', '$12', '$13', ".($idiom!="NULL"?"'$14'":"$14").", $15, $16, $17, $18, '$19', NOW());";
            $values = array(
                                $id_region,
                                $name,
                                $slug,
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
    
    error_log('   Facebook Region (f): '.date('d m Y H:i:s'));