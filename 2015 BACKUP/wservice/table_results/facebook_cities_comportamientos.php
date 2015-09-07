<?php

    set_time_limit(0);
    
    error_log('   Facebook City Comportamiento (i): '.date('d m Y H:i:s'));
    
    require_once(__DIR__.'/../config.php');
    
    function get_city_comportamientos_date_last_update($count){
        $sql = 'SELECT date 
                  FROM '.DB_FACEBOOK_PREFIX.'record_city_comportamiento_3_1
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
    
    $comportamientos = array();
    $query = 'SELECT id_comportamiento, nombre FROM '.DB_FACEBOOK_PREFIX.'comportamiento_3_1 WHERE active_fb_get_data = 1 AND active = 1;';
    $que_comportamientos = db_query($query, array());
    
    while($fila = mysql_fetch_assoc($que_comportamientos)){
        $comportamientos[$fila['id_comportamiento']] = array('name' => $fila['nombre']);
    }
    
    $city_comportamientos_last_update = get_city_comportamientos_date_last_update($count_city * 5);
    
    $query = 'SELECT id_city, id_comportamiento, total_user FROM '.DB_FACEBOOK_PREFIX.'record_city_comportamiento_3_1 WHERE date = \'$1\';';
    $que = db_query($query, array($city_comportamientos_last_update));
    while($city = mysql_fetch_assoc($que)){
        
        $id_comportamiento = $city['id_comportamiento'];
        
        $name = $comportamientos[$city['id_comportamiento']]['name'];
        
        $id_city = $city['id_city'];
        
        $total_user = $city['total_user'];
        
        $query = 'SELECT id_comportamiento, id_city FROM '.DB_RESULTS_PREFIX.'facebook_cities_comportamientos WHERE id_city = $10 AND id_comportamiento = $11;';
        $que_comportamiento = db_query_table_results($query, array($id_city, $id_comportamiento));
        if($row = mysql_fetch_assoc($que_comportamiento)){
            $query = "UPDATE ".DB_RESULTS_PREFIX."facebook_cities_comportamientos SET 
                                                name = '$10',
                                                total_user = $11,
                                                updated_at = NOW()
                                                
                                                WHERE id_city = $12 AND id_comportamiento = $13;";
            $values = array(
                                $name,
                                $total_user,
                                $id_city,
                                $id_comportamiento
                          );
            $res = db_query_table_results($query, $values, 1);
        }
        else{
            $query = "INSERT INTO ".DB_RESULTS_PREFIX."facebook_cities_comportamientos VALUES(NULL, $10, '$11', $12, $13, NOW());";
            $values = array(
                                $id_comportamiento,
                                $name,
                                $id_city,
                                $total_user
                           );
            $res = db_query_table_results($query, $values, 1);
        }
    }
    
    error_log('   Facebook City Comportamiento (f): '.date('d m Y H:i:s'));