<?php

    set_time_limit(0);
    
    error_log('   Facebook Country Age (i): '.date('d m Y H:i:s'));
    
    require_once(__DIR__.'/../config.php');
    
    function get_country_ages_date_last_update($count){
        $sql = 'SELECT date 
                  FROM '.DB_FACEBOOK_PREFIX.'record_country_age_3_1
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
    
    function getCrecimiento($id_country, $id_age, $last_update, $days){
        $sql = 'SELECT total_user, (total_user - (
                            SELECT total_user 
                            FROM '.DB_FACEBOOK_PREFIX.'record_country_age_3_1 
                            WHERE date = (
                                        SELECT date 
                                        FROM '.DB_FACEBOOK_PREFIX.'record_country_age_3_1 
                                        WHERE id_country = $3 AND id_age = $4
                                              AND DATE_SUB(\'$1\',INTERVAL $2 DAY) <= date
                                        ORDER BY 1 ASC 
                                        LIMIT 1
                                    ) 
                                AND id_country = $3 AND id_age = $4
                        )) cambio 
                FROM '.DB_FACEBOOK_PREFIX.'record_country_age_3_1
                WHERE id_country = $3 AND id_age = $4
                    AND date = \'$1\';
                ';
        
        $que = db_query($sql, array($last_update, $days, $id_country, $id_age));
        if($fila = mysql_fetch_assoc($que)){
            if(is_numeric($fila['cambio'])){
                return $fila['cambio'];
            }
        }
        return 'NULL';
    }
        
    //Países
    $countries = array();
    $query = 'SELECT id_country, code, nombre, name FROM '.DB_FACEBOOK_PREFIX.'country_3_1 ORDER BY 1;';
    $que = db_query($query, array());
    
    while($fila = mysql_fetch_assoc($que)){
        $countries[] = array('id_country' => $fila['id_country'], 'code' => $fila['code'], 'name' => $fila['nombre'], 'name_en' => $fila['name']);
    }
    
    $ages = array();
    $query = 'SELECT id_age, min, max FROM '.DB_FACEBOOK_PREFIX.'age_3_1 WHERE active_fb_get_data = 1 AND active = 1;';
    $que_ages = db_query($query, array());
    
    while($fila = mysql_fetch_assoc($que_ages)){
        $ages[$fila['id_age']] = array('name' => ($fila['min']!=65?$fila['min'].' - '.$fila['max']:'65+'));
    }
    
    $country_ages_last_update = get_country_ages_date_last_update(count($countries) * count($ages));
    
    foreach($countries as $country){
        
        $country_code = $country['code'];
        
        $query = 'SELECT id_age, total_user, total_female, total_male FROM '.DB_FACEBOOK_PREFIX.'record_country_age_3_1 WHERE id_country = $1 AND date = \'$2\';';
        $que_audience = db_query($query, array($country['id_country'], $country_ages_last_update));
        
        while($audience = mysql_fetch_assoc($que_audience)){
            
            $id_age = $audience['id_age'];
            
            $name = $ages[$id_age]['name'];
            
            $total_user = $audience['total_user'];
            
            $total_female = $audience['total_female'];
            
            $total_male = $audience['total_male'];
            
            $grow_30 = getCrecimiento($country['id_country'], $id_age, $country_ages_last_update, 30);
            
            $query = 'SELECT id_age, country_code FROM '.DB_RESULTS_PREFIX.'facebook_countries_ages WHERE country_code = \'$10\' AND id_age = $11;';
            $que_age = db_query_table_results($query, array($country_code, $id_age));
            if($row = mysql_fetch_assoc($que_age)){
                $query = "UPDATE ".DB_RESULTS_PREFIX."facebook_countries_ages SET 
                                                    name = '$10',
                                                    total_user = $11,
                                                    total_female = $12,
                                                    total_male = $13,
                                                    grow_30 = $14,
                                                    updated_at = NOW()
                                                    
                                                    WHERE country_code = '$15' AND id_age = $16;";
                $values = array(
                                    $name,
                                    $total_user,
                                    $total_female,
                                    $total_male,
                                    $grow_30,
                                    $country_code,
                                    $id_age
                              );
                $res = db_query_table_results($query, $values, 1);
            }
            else{
                $query = "INSERT INTO ".DB_RESULTS_PREFIX."facebook_countries_ages VALUES(NULL, $10, '$11', '$12', $13, $14, $15, $16, NOW());";
                $values = array(
                                    $id_age,
                                    $name,
                                    $country_code,
                                    $total_user,
                                    $total_female,
                                    $total_male,
                                    $grow_30
                               );
                $res = db_query_table_results($query, $values, 1);
            }
            
        }
    }

    error_log('   Facebook Country Age (f): '.date('d m Y H:i:s'));