<?php
    require_once(__DIR__.'/../config.php');
    
    function get_region_date_last_update($count){
        $sql = 'SELECT date 
                  FROM facebook_record_region_3_1
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
    
    $query = "SELECT id_region, name, id_country FROM facebook_region_3_1 WHERE active_fb_get_data = 1 AND active = 1;";
    $que_regions = db_query($query, array());
    
    $regions_last_update = get_region_date_last_update(mysql_num_rows($que_regions));
    
    while($region = mysql_fetch_assoc($que_regions)){
        
        $id_region = $region['id_region'];
        
        $name = $region['name'];
        
        $country_code = $countries[$region['id_country']]['code'];
        
        $query = "SELECT total_user, total_female, total_male FROM facebook_record_region_3_1 WHERE id_region = $1 AND date = '$2';";
        $que_audience = db_query($query, array($region['id_region'], $regions_last_update));
        
        if($audience = mysql_fetch_assoc($que_audience)){
            $total_user = $audience['total_user'];
            $total_female = $audience['total_female'];
            $total_male = $audience['total_male'];
        }
       
        $query = 'SELECT id_region FROM facebook_regions WHERE id_region = $10;';
        $que_region = db_query_table_results($query, array($id_region));
        if($row = mysql_fetch_assoc($que_region)){
            $query = "UPDATE facebook_regions SET 
                                                name = '$10',
                                                country_code = '$11',
                                                total_user = $12,
                                                total_female = $13,
                                                total_male = $14,
                                                updated_at = NOW()
                                                
                                                WHERE id_region = $15;";
            $values = array(
                                $name,
                                $country_code,
                                $total_user,
                                $total_female,
                                $total_male,
                                $id_region
                          );
            $res = db_query_table_results($query, $values, 1);
        }
        else{
            $query = "INSERT INTO facebook_regions VALUES($10, '$11', '$12', $13, $14, $15, NOW());";
            $values = array(
                                $id_region,
                                $name,
                                $country_code,
                                $total_user,
                                $total_female,
                                $total_male
                           );
            $res = db_query_table_results($query, $values, 1);
        }
        
    }