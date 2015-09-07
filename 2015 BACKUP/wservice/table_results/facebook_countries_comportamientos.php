<?php

    set_time_limit(0);
    
    error_log('   Facebook Country Comportamiento (i): '.date('d m Y H:i:s'));

    require_once(__DIR__.'/../config.php');
    
    function get_country_comportamientos_date_last_update($count){
        $sql = 'SELECT date 
                  FROM '.DB_FACEBOOK_PREFIX.'record_country_comportamiento_3_1
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
    
    function getCrecimiento($id_country, $id_comportamiento, $last_update, $days){
        $sql = 'SELECT total_user, (total_user - (
                            SELECT total_user 
                            FROM '.DB_FACEBOOK_PREFIX.'record_country_comportamiento_3_1 
                            WHERE date = (
                                        SELECT date 
                                        FROM '.DB_FACEBOOK_PREFIX.'record_country_comportamiento_3_1 
                                        WHERE id_country = $3 AND id_comportamiento = $4
                                              AND DATE_SUB(\'$1\',INTERVAL $2 DAY) <= date
                                        ORDER BY 1 ASC 
                                        LIMIT 1
                                    ) 
                                AND id_country = $3 AND id_comportamiento = $4
                        )) cambio 
                FROM '.DB_FACEBOOK_PREFIX.'record_country_comportamiento_3_1
                WHERE id_country = $3 AND id_comportamiento = $4
                    AND date = \'$1\';
                ';
        
        $que = db_query($sql, array($last_update, $days, $id_country, $id_comportamiento));
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
    
    function get_an_column_history($id_comportamiento, $id_country, $last_update, $days){
        $sql = "SELECT total_user, date 
                FROM ".DB_FACEBOOK_PREFIX."record_country_comportamiento_3_1
                WHERE id_country = $1
                    AND id_comportamiento = $2
                    AND DATE_SUB(STR_TO_DATE('$3', '%Y-%m-%d'),INTERVAL $4 DAY) <= date
                ORDER BY 2 ASC;
                ";
        $que = db_query($sql, array($id_country, $id_comportamiento, $last_update, $days));
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

    function mobile_device_has_more_device($id_mobile_device){
        $not_has_more_device = array(62, 77, 90, 154, 156, 157, 188, 189);
        return !in_array($id_mobile_device, $not_has_more_device);
    }
        
    //Países
    $countries = array();
    $query = 'SELECT id_country, code, nombre, name FROM '.DB_FACEBOOK_PREFIX.'country_3_1 ORDER BY 1;';
    $que = db_query($query, array());
    
    while($fila = mysql_fetch_assoc($que)){
        $countries[] = array('id_country' => $fila['id_country'], 'code' => $fila['code'], 'name' => $fila['nombre'], 'name_en' => $fila['name']);
    }
    
    $comportamientos = $comportamientos_auxiliares = array();
    $query = 'SELECT id_comportamiento, key_comportamiento, nombre, nivel, nivel_superior, mobile_device, mobile_os FROM '.DB_FACEBOOK_PREFIX.'comportamiento_3_1 WHERE active = 1;';
    $que_comportamientos = db_query($query, array());
    
    while($fila = mysql_fetch_assoc($que_comportamientos)){
        if(!empty($fila['key_comportamiento']))
            $comportamientos[$fila['id_comportamiento']] = array('name' => $fila['nombre'], 'nivel' => $fila['nivel'], 'nivel_superior' => $fila['nivel_superior'], 'mobile_device' => $fila['mobile_device'], 'mobile_os' => $fila['mobile_os']);
        else
            $comportamientos_auxiliares[] = array('id_comportamiento' => $fila['id_comportamiento'], 'name' => $fila['nombre'], 'nivel' => $fila['nivel'], 'nivel_superior' => $fila['nivel_superior'], 'mobile_device' => $fila['mobile_device'], 'mobile_os' => $fila['mobile_os']);
    }
    
    $country_comportamientos_last_update = get_country_comportamientos_date_last_update(count($countries) * count($comportamientos));
    
    foreach($countries as $country){
        
        $country_code = $country['code'];
        
        $query = 'SELECT id_comportamiento, total_user, total_female, total_male FROM '.DB_FACEBOOK_PREFIX.'record_country_comportamiento_3_1 WHERE id_country = $1 AND date = \'$2\';';
        $que_audience = db_query($query, array($country['id_country'], $country_comportamientos_last_update));
        
        while($audience = mysql_fetch_assoc($que_audience)){
            
            $id_comportamiento = $audience['id_comportamiento'];
            
            $name = $comportamientos[$id_comportamiento]['name'];
            
            $nivel = $comportamientos[$id_comportamiento]['nivel'];
            
            $nivel_superior = (!empty($comportamientos[$id_comportamiento]['nivel_superior'])?$comportamientos[$id_comportamiento]['nivel_superior']:0);
            
            $mobile_device = $comportamientos[$id_comportamiento]['mobile_device'];
            
            $mobile_os = $comportamientos[$id_comportamiento]['mobile_os'];
            
            $total_user = $audience['total_user'];
            
            $total_female = $audience['total_female'];
            
            $total_male = $audience['total_male'];
            
            $grow_1 = getCrecimiento($country['id_country'], $id_comportamiento, $country_comportamientos_last_update, 1);
            $grow_3 = getCrecimiento($country['id_country'], $id_comportamiento, $country_comportamientos_last_update, 3);
            $grow_7 = getCrecimiento($country['id_country'], $id_comportamiento, $country_comportamientos_last_update, 7);
            $grow_15 = getCrecimiento($country['id_country'], $id_comportamiento, $country_comportamientos_last_update, 15);
            $grow_30 = getCrecimiento($country['id_country'], $id_comportamiento, $country_comportamientos_last_update, 30);
            
            $chart_history = '';
            if($id_comportamiento == 92 || $id_comportamiento == 93 || $id_comportamiento == 94 || ($comportamientos[$id_comportamiento]['nivel'] == 3 && mobile_device_has_more_device($id_comportamiento))){
                $chart_history = get_an_column_history($id_comportamiento, $country['id_country'], $country_comportamientos_last_update, 30);
                $chart_history = json_encode($chart_history);
            }
            
            $query = 'SELECT id_comportamiento, country_code FROM '.DB_RESULTS_PREFIX.'facebook_countries_comportamientos WHERE country_code = \'$10\' AND id_comportamiento = $11;';
            $que_comportamiento = db_query_table_results($query, array($country_code, $id_comportamiento));
            if($row = mysql_fetch_assoc($que_comportamiento)){
                $query = "UPDATE ".DB_RESULTS_PREFIX."facebook_countries_comportamientos SET 
                                                    name = '$10',
                                                    nivel = $11,
                                                    nivel_superior = $12,
                                                    mobile_device = $13,
                                                    mobile_os = '$14',
                                                    total_user = $15,
                                                    total_female = $16,
                                                    total_male = $17,
                                                    grow_1 = $18,
                                                    grow_3 = $19,
                                                    grow_7 = $20,
                                                    grow_15 = $21,
                                                    grow_30 = $22,
                                                    chart_history = '$23',
                                                    updated_at = NOW()
                                                    
                                                    WHERE country_code = '$24' AND id_comportamiento = $25;";
                $values = array(
                                    $name,
                                    $nivel,
                                    $nivel_superior,
                                    $mobile_device,
                                    $mobile_os,
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
                                    $id_comportamiento
                              );
                $res = db_query_table_results($query, $values, 1);
            }
            else{
                $query = "INSERT INTO ".DB_RESULTS_PREFIX."facebook_countries_comportamientos VALUES(NULL, $10, '$11', $12, $13, $14, '$15', '$16', $17, $18, $19, $20, $21, $22, $23, $24, '$25', NOW());";
                $values = array(
                                    $id_comportamiento,
                                    $name,
                                    $nivel,
                                    $nivel_superior,
                                    $mobile_device,
                                    $mobile_os,
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
    
    //Insertamos los comportamientos que no tengan valores
    foreach($countries as $country){
        
        $country_code = $country['code'];
        
        foreach($comportamientos_auxiliares as $comportamiento){
            
            $id_comportamiento = $comportamiento['id_comportamiento'];
            
            $name = $comportamiento['name'];
            
            $nivel = $comportamiento['nivel'];
            
            $nivel_superior = (!empty($comportamiento['nivel_superior'])?$comportamiento['nivel_superior']:0);
            
            $mobile_device = $comportamiento['mobile_device'];
            
            $mobile_os = $comportamiento['mobile_os'];
            
            $total_user = 'NULL';
            
            $total_female = 'NULL';
            
            $total_male = 'NULL';
            
            $grow_1 = 'NULL';
            $grow_3 = 'NULL';
            $grow_7 = 'NULL';
            $grow_15 = 'NULL';
            $grow_30 = 'NULL';
            
            $chart_history = '';
            
            $query = 'SELECT id_comportamiento, country_code FROM '.DB_RESULTS_PREFIX.'facebook_countries_comportamientos WHERE country_code = \'$10\' AND id_comportamiento = $11;';
            $que_comportamiento = db_query_table_results($query, array($country_code, $id_comportamiento));
            if($row = mysql_fetch_assoc($que_comportamiento)){
                $query = "UPDATE ".DB_RESULTS_PREFIX."facebook_countries_comportamientos SET 
                                                    name = '$10',
                                                    nivel = $11,
                                                    nivel_superior = $12,
                                                    mobile_device = $13,
                                                    mobile_os = '$14'
                                                    total_user = $15,
                                                    total_female = $16,
                                                    total_male = $17,
                                                    grow_1 = $18,
                                                    grow_3 = $19,
                                                    grow_7 = $20,
                                                    grow_15 = $21,
                                                    grow_30 = $22,
                                                    chart_history = '$23',
                                                    updated_at = NOW()
                                                    
                                                    WHERE country_code = '$24' AND id_comportamiento = $25;";
                $values = array(
                                    $name,
                                    $nivel,
                                    $nivel_superior,
                                    $mobile_device,
                                    $mobile_os,
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
                                    $id_comportamiento
                              );
                $res = db_query_table_results($query, $values, 1);
            }
            else{
                $query = "INSERT INTO ".DB_RESULTS_PREFIX."facebook_countries_comportamientos VALUES(NULL, $10, '$11', $12, $13, $14, '$15', '$16', $17, $18, $19, $20, $21, $22, $23, $24, '$25', NOW());";
                $values = array(
                                    $id_comportamiento,
                                    $name,
                                    $nivel,
                                    $nivel_superior,
                                    $mobile_device,
                                    $mobile_os,
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
    
    //Suma de los items de los dispositivos de Google, Amazon y Asus
    
    foreach (array(39, 141, 147) as $id_comport) {
        foreach($countries as $country){
            $country_code = $country['code'];
            $query = 'SELECT SUM(total_user) total_user, SUM(total_female) total_female, SUM(total_male) total_male, SUM(grow_1) grow_1, SUM(grow_3) grow_3, SUM(grow_7) grow_7, SUM(grow_15) grow_15, SUM(grow_30) grow_30 FROM `'.DB_RESULTS_PREFIX.'facebook_countries_comportamientos` WHERE `nivel_superior` = $10 AND `country_code` LIKE \'$11\'';
            $que_result = db_query_table_results($query, array($id_comport, $country_code));
            if($row = mysql_fetch_assoc($que_result)){
                $query = "UPDATE ".DB_RESULTS_PREFIX."facebook_countries_comportamientos SET 
                                                    total_user = $10,
                                                    total_female = $11,
                                                    total_male = $12,
                                                    grow_1 = $13,
                                                    grow_3 = $14,
                                                    grow_7 = $15,
                                                    grow_15 = $16,
                                                    grow_30 = $17
                                                    
                                                    WHERE country_code = '$18' AND id_comportamiento = $19;";
                $values = array(
                                    $row['total_user'],
                                    $row['total_female'],
                                    $row['total_male'],
                                    $row['grow_1'],
                                    $row['grow_3'],
                                    $row['grow_7'],
                                    $row['grow_15'],
                                    $row['grow_30'],
                                    $country_code,
                                    $id_comport
                              );
                $res = db_query_table_results($query, $values, 1);
            }
        }
    }
    
    //Asignar el valor de "Todos los dispositivos IOS" a "Apple"
    $id_ios = 94;
    $id_apple = 25;
    foreach($countries as $country){
        $country_code = $country['code'];
        $query = 'SELECT * FROM '.DB_RESULTS_PREFIX.'facebook_countries_comportamientos WHERE id_comportamiento = $10 AND `country_code` LIKE \'$11\'';
        $que_result = db_query_table_results($query, array($id_ios, $country_code));
        if($row = mysql_fetch_assoc($que_result)){
            
            $query = "UPDATE ".DB_RESULTS_PREFIX."facebook_countries_comportamientos SET 
                                                total_user = $10,
                                                total_female = $11,
                                                total_male = $12,
                                                grow_1 = $13,
                                                grow_3 = $14,
                                                grow_7 = $15,
                                                grow_15 = $16,
                                                grow_30 = $17
                                                
                                                WHERE id_comportamiento = $18 AND country_code = '$19';";
            $values = array(
                                $row['total_user'],
                                $row['total_female'],
                                $row['total_male'],
                                $row['grow_1'],
                                $row['grow_3'],
                                $row['grow_7'],
                                $row['grow_15'],
                                $row['grow_30'],
                                $id_apple,
                                $country_code
                          );
            $res = db_query_table_results($query, $values, 1);
        }
    }
    
    error_log('   Facebook Country Comportamiento (f): '.date('d m Y H:i:s'));