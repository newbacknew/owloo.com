<?php

    set_time_limit(0);
    
    error_log('   Facebook Region Comportamiento (i): '.date('d m Y H:i:s'));
    
    require_once(__DIR__.'/../config.php');
    
    function get_region_comportamientos_date_last_update($count){
        $sql = 'SELECT date 
                  FROM '.DB_FACEBOOK_PREFIX.'record_region_comportamiento_3_1
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
        
    //Regiones
    $count_region = 0;
    $query = 'SELECT count(*) cantidad FROM '.DB_FACEBOOK_PREFIX.'region_3_1 WHERE active_fb_get_data = 1 AND active = 1;';
    $que = db_query($query, array());
    if($fila = mysql_fetch_assoc($que)){
        $count_region = $fila['cantidad'];
    }
    
    $comportamientos = array();
    $query = 'SELECT id_comportamiento, nombre FROM '.DB_FACEBOOK_PREFIX.'comportamiento_3_1 WHERE active_fb_get_data = 1 AND active = 1;';
    $que_comportamientos = db_query($query, array());
    
    while($fila = mysql_fetch_assoc($que_comportamientos)){
        $comportamientos[$fila['id_comportamiento']] = array('name' => $fila['nombre']);
    }
    
    $region_comportamientos_last_update = get_region_comportamientos_date_last_update($count_region * 5);
    
    $query = 'SELECT id_region, id_comportamiento, total_user FROM '.DB_FACEBOOK_PREFIX.'record_region_comportamiento_3_1 WHERE date = \'$1\';';
    $que = db_query($query, array($region_comportamientos_last_update));
    while($region = mysql_fetch_assoc($que)){
        
        $id_comportamiento = $region['id_comportamiento'];
        
        $name = $comportamientos[$region['id_comportamiento']]['name'];
        
        $id_region = $region['id_region'];
        
        $total_user = $region['total_user'];
        
        $query = 'SELECT id_comportamiento, id_region FROM '.DB_RESULTS_PREFIX.'facebook_regions_comportamientos WHERE id_region = $10 AND id_comportamiento = $11;';
        $que_comportamiento = db_query_table_results($query, array($id_region, $id_comportamiento));
        if($row = mysql_fetch_assoc($que_comportamiento)){
            $query = "UPDATE ".DB_RESULTS_PREFIX."facebook_regions_comportamientos SET 
                                                name = '$10',
                                                total_user = $11,
                                                updated_at = NOW()
                                                
                                                WHERE id_region = $12 AND id_comportamiento = $13;";
            $values = array(
                                $name,
                                $total_user,
                                $id_region,
                                $id_comportamiento
                          );
            $res = db_query_table_results($query, $values, 1);
        }
        else{
            $query = "INSERT INTO ".DB_RESULTS_PREFIX."facebook_regions_comportamientos VALUES(NULL, $10, '$11', $12, $13, NOW());";
            $values = array(
                                $id_comportamiento,
                                $name,
                                $id_region,
                                $total_user
                           );
            $res = db_query_table_results($query, $values, 1);
        }
    }
    
    error_log('   Facebook Region Comportamiento (f): '.date('d m Y H:i:s'));
