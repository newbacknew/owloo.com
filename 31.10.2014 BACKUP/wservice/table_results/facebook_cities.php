<?php
    require_once(__DIR__.'/../config.php');
    
    function get_city_date_last_update($count){
        $sql = 'SELECT date 
                  FROM facebook_record_city_3_1
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
        
    //Países
    $countries = array();
    $query = 'SELECT id_country, code, nombre, name FROM facebook_country_3_1 ORDER BY 1;';
    $que = db_query($query, array());
    
    while($fila = mysql_fetch_assoc($que)){
        $countries[$fila['id_country']] = array('code' => $fila['code'], 'name' => $fila['nombre'], 'name_en' => $fila['name']);
    }
    
    $query = "SELECT id_city, name, id_country FROM facebook_city_3_1 WHERE active_fb_get_data = 1 AND active = 1;";
    $que_cities = db_query($query, array());
    
    $cities_last_update = get_city_date_last_update(mysql_num_rows($que_cities));
    
    while($city = mysql_fetch_assoc($que_cities)){
        
        $id_city = $city['id_city'];
        
        $name = $city['name'];
        
        $country_code = $countries[$city['id_country']]['code'];
        
        $query = "SELECT total_user, total_female, total_male FROM facebook_record_city_3_1 WHERE id_city = $1 AND date = '$2';";
        $que_audience = db_query($query, array($city['id_city'], $cities_last_update));
        
        if($audience = mysql_fetch_assoc($que_audience)){
            $total_user = $audience['total_user'];
            $total_female = $audience['total_female'];
            $total_male = $audience['total_male'];
        }
       
        $query = 'SELECT id_city FROM facebook_cities WHERE id_city = $10;';
        $que_city = db_query_table_results($query, array($id_city));
        if($row = mysql_fetch_assoc($que_city)){
            $query = "UPDATE facebook_cities SET 
                                                name = '$10',
                                                country_code = '$11',
                                                total_user = $12,
                                                total_female = $13,
                                                total_male = $14,
                                                updated_at = NOW()
                                                
                                                WHERE id_city = $15;";
            $values = array(
                                $name,
                                $country_code,
                                $total_user,
                                $total_female,
                                $total_male,
                                $id_city
                          );
            $res = db_query_table_results($query, $values, 1);
        }
        else{
            $query = "INSERT INTO facebook_cities VALUES($10, '$11', '$12', $13, $14, $15, NOW());";
            $values = array(
                                $id_city,
                                $name,
                                $country_code,
                                $total_user,
                                $total_female,
                                $total_male
                           );
            $res = db_query_table_results($query, $values, 1);
        }
    }