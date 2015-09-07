<?php
    require_once(__DIR__.'/../config.php');
    
    function get_country_languages_date_last_update($count){
        $sql = 'SELECT date 
                  FROM facebook_record_country_language_3_1
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
    
    function getCrecimiento($id_country, $id_language, $last_update, $days){
        $sql = "SELECT total_user, (total_user - (
                            SELECT total_user 
                            FROM facebook_record_country_language_3_1 
                            WHERE date = (
                                        SELECT date 
                                        FROM facebook_record_country_language_3_1 
                                        WHERE id_country = $3 AND id_language = $4
                                              AND DATE_SUB('$1',INTERVAL $2 DAY) <= date
                                        ORDER BY 1 ASC 
                                        LIMIT 1
                                    ) 
                                AND id_country = $3 AND id_language = $4
                        )) cambio 
                FROM facebook_record_country_language_3_1
                WHERE id_country = $3 AND id_language = $4
                    AND date = '$1';
                ";
        
        $que = db_query($sql, array($last_update, $days, $id_country, $id_language));
        if($fila = mysql_fetch_assoc($que)){
            return $fila['cambio'];
        }
        return NULL;
    }
        
    //Países
    $countries = array();
    $query = 'SELECT id_country, code, nombre, name FROM facebook_country_3_1 ORDER BY 1;';
    $que = db_query($query, array());
    
    while($fila = mysql_fetch_assoc($que)){
        $countries[] = array('id_country' => $fila['id_country'], 'code' => $fila['code'], 'name' => $fila['nombre'], 'name_en' => $fila['name']);
    }
    
    $languages = array();
    $query = "SELECT id_language, nombre FROM facebook_language_3_1 WHERE active_fb_get_data = 1 AND active = 1;";
    $que_languages = db_query($query, array());
    
    while($fila = mysql_fetch_assoc($que_languages)){
        $languages[$fila['id_language']] = array('name' => $fila['nombre']);
    }
    
    $country_languages_last_update = get_country_languages_date_last_update(count($countries) * count($languages));
    
    foreach($countries as $country){
        
        $country_code = $country['code'];
        
        $query = "SELECT id_language, total_user FROM facebook_record_country_language_3_1 WHERE id_country = $1 AND date = '$2';";
        $que_audience = db_query($query, array($country['id_country'], $country_languages_last_update));
        
        while($audience = mysql_fetch_assoc($que_audience)){
            
            $id_language = $audience['id_language'];
            
            $name = $languages[$id_language]['name'];
            
            $total_user = $audience['total_user'];
            
            $grow_30 = getCrecimiento($country['id_country'], $id_language, $country_languages_last_update, 30);
            
            $query = 'SELECT id_language, country_code FROM facebook_countries_languages WHERE country_code = \'$10\' AND id_language = $11;';
            $que_language = db_query_table_results($query, array($country_code, $id_language));
            if($row = mysql_fetch_assoc($que_language)){
                $query = "UPDATE facebook_countries_languages SET 
                                                    name = '$10',
                                                    total_user = $11,
                                                    grow_30 = $12,
                                                    updated_at = NOW()
                                                    
                                                    WHERE country_code = '$13' AND id_language = $14;";
                $values = array(
                                    $name,
                                    $total_user,
                                    $grow_30,
                                    $country_code,
                                    $id_language
                              );
                $res = db_query_table_results($query, $values, 1);
            }
            else{
                $query = "INSERT INTO facebook_countries_languages VALUES(NULL, $10, '$11', '$12', $13, $14, NOW());";
                $values = array(
                                    $id_language,
                                    $name,
                                    $country_code,
                                    $total_user,
                                    $grow_30
                               );
                $res = db_query_table_results($query, $values, 1);
            }
            
        }
        
    }