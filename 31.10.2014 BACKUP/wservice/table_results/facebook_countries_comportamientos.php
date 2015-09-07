<?php
    require_once(__DIR__.'/../config.php');
    
    function get_country_comportamientos_date_last_update($count){
        $sql = 'SELECT date 
                  FROM facebook_record_country_comportamiento_3_1
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
        $sql = "SELECT total_user, (total_user - (
                            SELECT total_user 
                            FROM facebook_record_country_comportamiento_3_1 
                            WHERE date = (
                                        SELECT date 
                                        FROM facebook_record_country_comportamiento_3_1 
                                        WHERE id_country = $3 AND id_comportamiento = $4
                                              AND DATE_SUB('$1',INTERVAL $2 DAY) <= date
                                        ORDER BY 1 ASC 
                                        LIMIT 1
                                    ) 
                                AND id_country = $3 AND id_comportamiento = $4
                        )) cambio 
                FROM facebook_record_country_comportamiento_3_1
                WHERE id_country = $3 AND id_comportamiento = $4
                    AND date = '$1';
                ";
        
        $que = db_query($sql, array($last_update, $days, $id_country, $id_comportamiento));
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
    
    $comportamientos = $comportamientos_auxiliares = array();
    $query = "SELECT id_comportamiento, key_comportamiento, nombre, nivel, nivel_superior FROM facebook_comportamiento_3_1 WHERE active = 1;";
    $que_comportamientos = db_query($query, array());
    
    while($fila = mysql_fetch_assoc($que_comportamientos)){
        if(!empty($fila['key_comportamiento']))
            $comportamientos[$fila['id_comportamiento']] = array('name' => $fila['nombre'], 'nivel' => $fila['nivel'], 'nivel_superior' => $fila['nivel_superior']);
        else
            $comportamientos_auxiliares[] = array('id_comportamiento' => $fila['id_comportamiento'], 'name' => $fila['nombre'], 'nivel' => $fila['nivel'], 'nivel_superior' => $fila['nivel_superior']);
    }
    
    $country_comportamientos_last_update = get_country_comportamientos_date_last_update(count($countries) * count($comportamientos));
    
    foreach($countries as $country){
        
        $country_code = $country['code'];
        
        $query = "SELECT id_comportamiento, total_user, total_female, total_male FROM facebook_record_country_comportamiento_3_1 WHERE id_country = $1 AND date = '$2';";
        $que_audience = db_query($query, array($country['id_country'], $country_comportamientos_last_update));
        
        while($audience = mysql_fetch_assoc($que_audience)){
            
            $id_comportamiento = $audience['id_comportamiento'];
            
            $name = $comportamientos[$id_comportamiento]['name'];
            
            $nivel = $comportamientos[$id_comportamiento]['nivel'];
            
            $nivel_superior = (!empty($comportamientos[$id_comportamiento]['nivel_superior'])?$comportamientos[$id_comportamiento]['nivel_superior']:0);
            
            $total_user = $audience['total_user'];
            
            $total_female = $audience['total_female'];
            
            $total_male = $audience['total_male'];
            
            $grow_30 = getCrecimiento($country['id_country'], $id_comportamiento, $country_comportamientos_last_update, 30);
            
            $query = 'SELECT id_comportamiento, country_code FROM facebook_countries_comportamientos WHERE country_code = \'$10\' AND id_comportamiento = $11;';
            $que_comportamiento = db_query_table_results($query, array($country_code, $id_comportamiento));
            if($row = mysql_fetch_assoc($que_comportamiento)){
                $query = "UPDATE facebook_countries_comportamientos SET 
                                                    name = '$10',
                                                    nivel = $11,
                                                    nivel_superior = $12,
                                                    total_user = $13,
                                                    total_female = $14,
                                                    total_male = $15,
                                                    grow_30 = $16,
                                                    updated_at = NOW()
                                                    
                                                    WHERE country_code = '$17' AND id_comportamiento = $18;";
                $values = array(
                                    $name,
                                    $nivel,
                                    $nivel_superior,
                                    $total_user,
                                    $total_female,
                                    $total_male,
                                    $grow_30,
                                    $country_code,
                                    $id_comportamiento
                              );
                $res = db_query_table_results($query, $values, 1);
            }
            else{
                $query = "INSERT INTO facebook_countries_comportamientos VALUES(NULL, $10, '$11', $12, $13, '$14', $15, $16, $17, $18, NOW());";
                $values = array(
                                    $id_comportamiento,
                                    $name,
                                    $nivel,
                                    $nivel_superior,
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

    foreach($countries as $country){
        
        $country_code = $country['code'];
        
        foreach($comportamientos_auxiliares as $comportamiento){
            
            $id_comportamiento = $comportamiento['id_comportamiento'];
            
            $name = $comportamiento['name'];
            
            $nivel = $comportamiento['nivel'];
            
            $nivel_superior = (!empty($comportamiento['nivel_superior'])?$comportamiento['nivel_superior']:0);
            
            $total_user = 'NULL';
            
            $total_female = 'NULL';
            
            $total_male = 'NULL';
            
            $grow_30 = 'NULL';
            
            $query = 'SELECT id_comportamiento, country_code FROM facebook_countries_comportamientos WHERE country_code = \'$10\' AND id_comportamiento = $11;';
            $que_comportamiento = db_query_table_results($query, array($country_code, $id_comportamiento));
            if($row = mysql_fetch_assoc($que_comportamiento)){
                $query = "UPDATE facebook_countries_comportamientos SET 
                                                    name = '$10',
                                                    nivel = $11,
                                                    nivel_superior = $12,
                                                    total_user = $13,
                                                    total_female = $14,
                                                    total_male = $15,
                                                    grow_30 = $16,
                                                    updated_at = NOW()
                                                    
                                                    WHERE country_code = '$17' AND id_comportamiento = $18;";
                $values = array(
                                    $name,
                                    $nivel,
                                    $nivel_superior,
                                    $total_user,
                                    $total_female,
                                    $total_male,
                                    $grow_30,
                                    $country_code,
                                    $id_comportamiento
                              );
                $res = db_query_table_results($query, $values, 1);
            }
            else{
                $query = "INSERT INTO facebook_countries_comportamientos VALUES(NULL, $10, '$11', $12, $13, '$14', $15, $16, $17, $18, NOW());";
                $values = array(
                                    $id_comportamiento,
                                    $name,
                                    $nivel,
                                    $nivel_superior,
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