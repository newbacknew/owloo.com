<?php
    
    set_time_limit(0);
    
    error_log('   Facebook Country Interest (i): '.date('d m Y H:i:s'));
    
    require_once(__DIR__.'/../config.php');
    
    function get_country_interests_date_last_update($count){
        $sql = 'SELECT date 
                  FROM '.DB_FACEBOOK_PREFIX.'record_country_interest_3_1
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
    
    function getCrecimiento($id_country, $id_interest, $last_update, $days){
        $sql = 'SELECT total_user, (total_user - (
                            SELECT total_user 
                            FROM '.DB_FACEBOOK_PREFIX.'record_country_interest_3_1 
                            WHERE date = (
                                        SELECT date 
                                        FROM '.DB_FACEBOOK_PREFIX.'record_country_interest_3_1 
                                        WHERE id_country = $3 AND id_interest = $4
                                              AND DATE_SUB(\'$1\',INTERVAL $2 DAY) <= date
                                        ORDER BY 1 ASC 
                                        LIMIT 1
                                    ) 
                                AND id_country = $3 AND id_interest = $4
                        )) cambio 
                FROM '.DB_FACEBOOK_PREFIX.'record_country_interest_3_1
                WHERE id_country = $3 AND id_interest = $4
                    AND date = \'$1\';
                ';
        
        $que = db_query($sql, array($last_update, $days, $id_country, $id_interest));
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
    
    function get_an_column_history($id_interest, $id_country, $last_update, $days){
        $sql = "SELECT total_user, date 
                FROM ".DB_FACEBOOK_PREFIX."record_country_interest_3_1
                WHERE id_country = $1
                    AND id_interest = $2
                    AND DATE_SUB(STR_TO_DATE('$3', '%Y-%m-%d'),INTERVAL $4 DAY) <= date
                ORDER BY 2 ASC;
                ";
        $que = db_query($sql, array($id_country, $id_interest, $last_update, $days));
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
    $query = 'SELECT id_country, code, nombre, name FROM '.DB_FACEBOOK_PREFIX.'country_3_1 ORDER BY 1;';
    $que = db_query($query, array());
    
    while($fila = mysql_fetch_assoc($que)){
        $countries[] = array('id_country' => $fila['id_country'], 'code' => $fila['code'], 'name' => $fila['nombre'], 'name_en' => $fila['name']);
    }
    
    $interests = array();
    $query = 'SELECT id_interest, nombre, nivel, nivel_superior FROM '.DB_FACEBOOK_PREFIX.'interest_3_1 WHERE active_fb_get_data = 1 AND active = 1;';
    $que_interests = db_query($query, array());
    
    while($fila = mysql_fetch_assoc($que_interests)){
        $interests[$fila['id_interest']] = array('name' => $fila['nombre'], 'nivel' => $fila['nivel'], 'nivel_superior' => $fila['nivel_superior']);
    }
    
    $country_interests_last_update = get_country_interests_date_last_update(count($countries) * count($interests));
    
    foreach($countries as $country){
        
        $country_code = $country['code'];
        
        $query = 'SELECT id_interest, total_user, total_female, total_male FROM '.DB_FACEBOOK_PREFIX.'record_country_interest_3_1 WHERE id_country = $1 AND date = \'$2\';';
        $que_audience = db_query($query, array($country['id_country'], $country_interests_last_update));
        
        while($audience = mysql_fetch_assoc($que_audience)){
            
            $id_interest = $audience['id_interest'];
            
            $name = $interests[$id_interest]['name'];
            
            $nivel = $interests[$id_interest]['nivel'];
            
            $nivel_superior = (!empty($interests[$id_interest]['nivel_superior'])?$interests[$id_interest]['nivel_superior']:0);
            
            $total_user = $audience['total_user'];
            
            $total_female = $audience['total_female'];
            
            $total_male = $audience['total_male'];
            
            $grow_1 = getCrecimiento($country['id_country'], $id_interest, $country_interests_last_update, 1);
            $grow_3 = getCrecimiento($country['id_country'], $id_interest, $country_interests_last_update, 3);
            $grow_7 = getCrecimiento($country['id_country'], $id_interest, $country_interests_last_update, 7);
            $grow_15 = getCrecimiento($country['id_country'], $id_interest, $country_interests_last_update, 15);
            $grow_30 = getCrecimiento($country['id_country'], $id_interest, $country_interests_last_update, 30);
            
            $chart_history = '';
            if($nivel == 1){
                $chart_history = get_an_column_history($id_interest, $country['id_country'], $country_interests_last_update, 30);
                $chart_history = json_encode($chart_history);
            }
            
            $query = 'SELECT id_interest, country_code FROM '.DB_RESULTS_PREFIX.'facebook_countries_interests WHERE country_code = \'$10\' AND id_interest = $11;';
            $que_interest = db_query_table_results($query, array($country_code, $id_interest));
            if($row = mysql_fetch_assoc($que_interest)){
                $query = "UPDATE ".DB_RESULTS_PREFIX."facebook_countries_interests SET 
                                                    name = '$10',
                                                    nivel = $11,
                                                    nivel_superior = $12,
                                                    total_user = $13,
                                                    total_female = $14,
                                                    total_male = $15,
                                                    grow_1 = $16,
                                                    grow_3 = $17,
                                                    grow_7 = $18,
                                                    grow_15 = $19,
                                                    grow_30 = $20,
                                                    chart_history = '$21',
                                                    updated_at = NOW()
                                                    
                                                    WHERE country_code = '$22' AND id_interest = $23;";
                $values = array(
                                    $name,
                                    $nivel,
                                    $nivel_superior,
                                    $total_user,
                                    $total_female,
                                    $total_male,
                                    $grow_1,
                                    $grow_3,
                                    $grow_7,
                                    $grow_15,
                                    $grow_30,
                                    $chart_history,
                                    $country_code,
                                    $id_interest
                              );
                $res = db_query_table_results($query, $values, 1);
            }
            else{
                $query = "INSERT INTO ".DB_RESULTS_PREFIX."facebook_countries_interests VALUES(NULL, $10, '$11', $12, $13, '$14', $15, $16, $17, $18, $19, $20, $21, $22, '$23', NOW());";
                $values = array(
                                    $id_interest,
                                    $name,
                                    $nivel,
                                    $nivel_superior,
                                    $country_code,
                                    $total_user,
                                    $total_female,
                                    $total_male,
                                    $grow_1,
                                    $grow_3,
                                    $grow_7,
                                    $grow_15,
                                    $grow_30,
                                    $chart_history
                               );
                $res = db_query_table_results($query, $values, 1);
            }
            
        }
    }
    
    error_log('   Facebook Country Interest (f): '.date('d m Y H:i:s'));