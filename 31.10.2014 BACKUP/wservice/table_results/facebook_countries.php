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
        $sql = 'SELECT total_user, total_female, total_male 
                  FROM facebook_record_country_3_1
                  WHERE id_country = $1 AND date = \'$2\';
               ';
        $res = db_query($sql, array($id_country, $date));
        if($fila = mysql_fetch_assoc($res)){
            return array('total_user' => $fila['total_user'], 'total_female' => $fila['total_female'], 'total_male' => $fila['total_male']);
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
    
    function get_audience_history($id_country, $last_update, $days){
        $sql = "SELECT total_user, date 
                FROM facebook_record_country_3_1
                WHERE id_country = $1 
                    AND DATE_SUB(STR_TO_DATE('$2', '%Y-%m-%d'),INTERVAL $3 DAY) <= date
                ORDER BY 2 ASC;
                ";
        $que = db_query($sql, array($id_country, $last_update, $days));
        $seriesData = ""; //Estadística vertical. Cantidad de usuarios que posee en Facebook
        $seriesDataMin = 0; //Número mínimo de usuarios
        $seriesDataMax = 0; //Número máximo de usuarios
        $xAxisCategories = ""; //Estadística horizontal. Fechas de los datos
        $ban = 1; //Bandera 
        $cont = 1;
        $_num_rango = 15;
        $_num_discard = mysql_num_rows($que) - ($_num_rango * floor(mysql_num_rows($que)/$_num_rango));
        while($fila = mysql_fetch_assoc($que)){
            if($_num_discard-- > 0) continue;
            if($cont % $_num_rango == 0){
                //Formatear fecha
                $auxformat = explode("-", $fila['date']);
                $dia = $auxformat[2];
                $mes = getMes($auxformat[1], 'short');
                if($ban == 1){
                    $seriesData .=      $fila['total_user'];
                    $xAxisCategories .= "'".$dia." ".$mes."'";
                    $seriesDataMin =    $fila['total_user'];
                    $seriesDataMax =    $fila['total_user'];
                    $ban = 0;
                }
                else{
                    $seriesData .= ','.$fila['total_user'];
                    $xAxisCategories .= ",'".$dia." ".$mes."'";
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
                    'series_data' => $seriesData,
                    'series_data_min' => $seriesDataMin,
                    'series_data_max' => $seriesDataMax,
                    'x_axis' => $xAxisCategories
            );
    }

    function getCrecimiento($id_country, $last_update, $days){
        $sql = "SELECT total_user, (total_user - (
                            SELECT total_user 
                            FROM facebook_record_country_3_1 
                            WHERE date = (
                                        SELECT date 
                                        FROM facebook_record_country_3_1 
                                        WHERE id_country = $3
                                              AND DATE_SUB('$1',INTERVAL $2 DAY) <= date
                                        ORDER BY 1 ASC 
                                        LIMIT 1
                                    ) 
                                AND id_country = $3
                        )) cambio 
                FROM facebook_record_country_3_1
                WHERE id_country = $3 
                    AND date = '$1';
                ";
        
        $que = db_query($sql, array($last_update, $days, $id_country));
        if($fila = mysql_fetch_assoc($que)){
            return $fila['cambio'];
        }
        return NULL;
    }
    
    //Países
    $countries = array();
    $query = 'SELECT * FROM facebook_country_3_1 ORDER BY 1;'; 
    $que = db_query($query, array());
    
    $country_date_last_update = get_country_date_last_update(mysql_num_rows($que));
    
    while($country= mysql_fetch_assoc($que)){
        $id_country = $country['id_country'];
        $code = $country['code'];
        $name = $country['nombre'];
        $name_en = $country['name'];
        $abbreviation = (!empty($country['abbreviation'])?$country['abbreviation']:'NULL');
        $idiom = ($country['habla_hispana']==1?'es':'NULL');
        $id_continent = $country['id_continent'];
        $supports_region = $country['supports_region'];
        $supports_city = $country['supports_city'];
        $audience = get_country_audience($id_country, $country_date_last_update);
        $audience_history = json_encode(get_audience_history($id_country, $country_date_last_update, 180));
        $audience_grow_1 = getCrecimiento($id_country, $country_date_last_update, 1);
        $audience_grow_7 = getCrecimiento($id_country, $country_date_last_update, 7);
        $audience_grow_30 = getCrecimiento($id_country, $country_date_last_update, 30);
        $audience_grow_60 = getCrecimiento($id_country, $country_date_last_update, 60);
        $audience_grow_90 = getCrecimiento($id_country, $country_date_last_update, 90);
        $audience_grow_180 = getCrecimiento($id_country, $country_date_last_update, 180);
        $general_ranking = 'NULL';
        
        $query = 'SELECT id_country FROM facebook_countries WHERE id_country = $10;';
        $que_country = db_query_table_results($query, array($id_country));
        if($row = mysql_fetch_assoc($que_country)){
            $query = "UPDATE facebook_countries SET 
                                                code = '$10',
                                                name = '$11',
                                                name_en = '$12',
                                                abbreviation = ".($abbreviation!="NULL"?"'$13'":"$13").",
                                                idiom = ".($idiom!="NULL"?"'$14'":"$14").",
                                                id_continent = $15,
                                                supports_region = $16,
                                                supports_city = $17,
                                                total_user = $18,
                                                total_female = $19,
                                                total_male = $20,
                                                audience_history = '$21',
                                                audience_grow_1 = $22,
                                                audience_grow_7 = $23,
                                                audience_grow_30 = $24,
                                                audience_grow_60 = $25,
                                                audience_grow_90 = $26,
                                                audience_grow_180 = $27,
                                                updated_at = NOW()
                                                
                                                WHERE id_country = $28;";
            $values = array(
                                $code,
                                $name,
                                $name_en,
                                $abbreviation,
                                $idiom,
                                $id_continent,
                                $supports_region,
                                $supports_city,
                                $audience['total_user'],
                                $audience['total_female'],
                                $audience['total_male'],
                                $audience_history,
                                $audience_grow_1,
                                $audience_grow_7,
                                $audience_grow_30,
                                $audience_grow_60,
                                $audience_grow_90,
                                $audience_grow_180,
                                $id_country
                          );
            $res = db_query_table_results($query, $values, 1);
        }
        else{
            $query = "INSERT INTO facebook_countries VALUES($10, '$11', '$12', '$13', ".($abbreviation!="NULL"?"'$14'":"$14").", ".($idiom!="NULL"?"'$15'":"$15").", $16, $17, $18, $19, $20, $21, '$22', $23, $24, $25, $26, $27, $28, $29, NOW());";
            $values = array(
                                $id_country,
                                $code,
                                $name,
                                $name_en,
                                $abbreviation,
                                $idiom,
                                $id_continent,
                                $supports_region,
                                $supports_city,
                                $audience['total_user'],
                                $audience['total_female'],
                                $audience['total_male'],
                                $audience_history,
                                $audience_grow_1,
                                $audience_grow_7,
                                $audience_grow_30,
                                $audience_grow_60,
                                $audience_grow_90,
                                $audience_grow_180,
                                $general_ranking
                           );
            $res = db_query_table_results($query, $values, 1);
        }
    }

    //General ranking
    $query = 'SELECT id_country FROM facebook_countries ORDER BY total_user DESC, audience_grow_30 DESC;';
    $que_countries = db_query_table_results($query, array());
    $count = 1;
    while($country = mysql_fetch_assoc($que_countries)){
        $query = "UPDATE facebook_countries SET general_ranking = $10 WHERE id_country = $11;";
        $values = array($count, $country['id_country']);
        $res = db_query_table_results($query, $values, 1);
        $count++;
    }