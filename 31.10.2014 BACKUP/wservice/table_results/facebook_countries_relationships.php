<?php
    require_once(__DIR__.'/../config.php');
    
    function get_country_relationships_date_last_update($count){
        $sql = 'SELECT date 
                  FROM facebook_record_country_relationship_3_1
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
    
    function getCrecimiento($id_country, $id_relationship, $last_update, $column, $days){
        $sql = "SELECT total_$5, (total_$5 - (
                            SELECT total_$5 
                            FROM facebook_record_country_relationship_3_1 
                            WHERE date = (
                                        SELECT date 
                                        FROM facebook_record_country_relationship_3_1 
                                        WHERE id_country = $3 AND id_relationship = $4
                                              AND DATE_SUB('$1',INTERVAL $2 DAY) <= date
                                        ORDER BY 1 ASC 
                                        LIMIT 1
                                    ) 
                                AND id_country = $3 AND id_relationship = $4
                        )) cambio 
                FROM facebook_record_country_relationship_3_1
                WHERE id_country = $3 AND id_relationship = $4
                    AND date = '$1';
                ";
        
        $que = db_query($sql, array($last_update, $days, $id_country, $id_relationship, $column));
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
    
    $relationships = array();
    $query = "SELECT id_relationship, nombre FROM facebook_relationship_3_1 WHERE active_fb_get_data = 1 AND active = 1;";
    $que_relationships = db_query($query, array());
    
    while($fila = mysql_fetch_assoc($que_relationships)){
        $relationships[$fila['id_relationship']] = array('name' => $fila['nombre']);
    }
    
    $country_relationships_last_update = get_country_relationships_date_last_update(count($countries) * count($relationships));
    
    foreach($countries as $country){
        
        $country_code = $country['code'];
        
        $query = "SELECT id_relationship, total_user, total_female, total_male FROM facebook_record_country_relationship_3_1 WHERE id_country = $1 AND date = '$2';";
        $que_audience = db_query($query, array($country['id_country'], $country_relationships_last_update));
        
        while($audience = mysql_fetch_assoc($que_audience)){
            
            $id_relationship = $audience['id_relationship'];
            
            $name = $relationships[$id_relationship]['name'];
            
            $total_user = $audience['total_user'];
            
            $total_female = $audience['total_female'];
            
            $total_male = $audience['total_male'];
            
            $total_user_grow_30 = getCrecimiento($country['id_country'], $id_relationship, $country_relationships_last_update, 'user', 30);
            
            $total_female_grow_30 = getCrecimiento($country['id_country'], $id_relationship, $country_relationships_last_update, 'female', 30);
            
            $total_male_grow_30 = getCrecimiento($country['id_country'], $id_relationship, $country_relationships_last_update, 'male', 30);
            
            $query = 'SELECT id_relationship, country_code FROM facebook_countries_relationships WHERE country_code = \'$10\' AND id_relationship = $11;';
            $que_relationship = db_query_table_results($query, array($country_code, $id_relationship));
            if($row = mysql_fetch_assoc($que_relationship)){
                $query = "UPDATE facebook_countries_relationships SET 
                                                    name = '$10',
                                                    total_user = $11,
                                                    total_female = $12,
                                                    total_male = $13,
                                                    total_user_grow_30 = $14,
                                                    total_female_grow_30 = $15,
                                                    total_male_grow_30 = $16,
                                                    updated_at = NOW()
                                                    
                                                    WHERE country_code = '$17' AND id_relationship = $18;";
                $values = array(
                                    $name,
                                    $total_user,
                                    $total_female,
                                    $total_male,
                                    $total_user_grow_30,
                                    $total_female_grow_30,
                                    $total_male_grow_30,
                                    $country_code,
                                    $id_relationship
                              );
                $res = db_query_table_results($query, $values, 1);
            }
            else{
                $query = "INSERT INTO facebook_countries_relationships VALUES(NULL, $10, '$11', '$12', $13, $14, $15, $16, $17, $18, NOW());";
                $values = array(
                                    $id_relationship,
                                    $name,
                                    $country_code,
                                    $total_user,
                                    $total_female,
                                    $total_male,
                                    $total_user_grow_30,
                                    $total_female_grow_30,
                                    $total_male_grow_30
                               );
                $res = db_query_table_results($query, $values, 1);
            }
            
        }
    }