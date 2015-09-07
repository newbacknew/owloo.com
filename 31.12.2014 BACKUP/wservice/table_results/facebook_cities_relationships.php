<?php

    set_time_limit(0);
    
    error_log('   Facebook City Relationship (i): '.date('d m Y H:i:s'));
    
    require_once(__DIR__.'/../config.php');
    
    function get_city_relationships_date_last_update($count){
        $sql = 'SELECT date 
                  FROM '.DB_FACEBOOK_PREFIX.'record_city_relationship_3_1
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
        
    //Ciudades
    $count_city = 0;
    $query = 'SELECT count(*) cantidad FROM '.DB_FACEBOOK_PREFIX.'city_3_1 WHERE active_fb_get_data = 1 AND active = 1;';
    $que = db_query($query, array());
    if($fila = mysql_fetch_assoc($que)){
        $count_city = $fila['cantidad'];
    }
    
    $relationships = array();
    $query = 'SELECT id_relationship, nombre FROM '.DB_FACEBOOK_PREFIX.'relationship_3_1 WHERE active_fb_get_data = 1 AND active = 1;';
    $que_relationships = db_query($query, array());
    
    while($fila = mysql_fetch_assoc($que_relationships)){
        $relationships[$fila['id_relationship']] = array('name' => $fila['nombre']);
    }
    
    $city_relationships_last_update = get_city_relationships_date_last_update($count_city * count($relationships));
    
    $query = 'SELECT id_city, id_relationship, total_user FROM '.DB_FACEBOOK_PREFIX.'record_city_relationship_3_1 WHERE date = \'$1\';';
    $que = db_query($query, array($city_relationships_last_update));
    while($city = mysql_fetch_assoc($que)){
        
        $id_relationship = $city['id_relationship'];
        
        $name = $relationships[$city['id_relationship']]['name'];
        
        $id_city = $city['id_city'];
        
        $total_user = $city['total_user'];
        
        $query = 'SELECT id_relationship, id_city FROM '.DB_RESULTS_PREFIX.'facebook_cities_relationships WHERE id_city = $10 AND id_relationship = $11;';
        $que_relationship = db_query_table_results($query, array($id_city, $id_relationship));
        if($row = mysql_fetch_assoc($que_relationship)){
            $query = "UPDATE ".DB_RESULTS_PREFIX."facebook_cities_relationships SET 
                                                name = '$10',
                                                total_user = $11,
                                                updated_at = NOW()
                                                
                                                WHERE id_city = $12 AND id_relationship = $13;";
            $values = array(
                                $name,
                                $total_user,
                                $id_city,
                                $id_relationship
                          );
            $res = db_query_table_results($query, $values, 1);
        }
        else{
            $query = "INSERT INTO ".DB_RESULTS_PREFIX."facebook_cities_relationships VALUES(NULL, $10, '$11', $12, $13, NOW());";
            $values = array(
                                $id_relationship,
                                $name,
                                $id_city,
                                $total_user
                           );
            $res = db_query_table_results($query, $values, 1);
        }
    }
    
    error_log('   Facebook City Relationship (f): '.date('d m Y H:i:s'));