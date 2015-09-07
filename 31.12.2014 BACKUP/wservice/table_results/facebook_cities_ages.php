<?php
    
    set_time_limit(0);
    
    error_log('   Facebook City Age (i): '.date('d m Y H:i:s'));
    
    require_once(__DIR__.'/../config.php');
    
    function get_city_ages_date_last_update($count){
        $sql = 'SELECT date 
                  FROM '.DB_FACEBOOK_PREFIX.'record_city_age_3_1
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
    
    $ages = array();
    $query = "SELECT id_age, min, max FROM ".DB_FACEBOOK_PREFIX."age_3_1 WHERE active_fb_get_data = 1 AND active = 1;";
    $que_ages = db_query($query, array());
    
    while($fila = mysql_fetch_assoc($que_ages)){
        $ages[$fila['id_age']] = array('name' => ($fila['min']!=65?$fila['min'].' - '.$fila['max']:'65+'));
    }
    
    $city_ages_last_update = get_city_ages_date_last_update($count_city * count($ages));
    
    $query = 'SELECT id_city, id_age, total_user FROM '.DB_FACEBOOK_PREFIX.'record_city_age_3_1 WHERE date = \'$1\';';
    $que = db_query($query, array($city_ages_last_update));
    while($city = mysql_fetch_assoc($que)){
        
        $id_age = $city['id_age'];
        
        $name = $ages[$city['id_age']]['name'];
        
        $id_city = $city['id_city'];
        
        $total_user = $city['total_user'];
        
        $query = 'SELECT id_age, id_city FROM '.DB_RESULTS_PREFIX.'facebook_cities_ages WHERE id_city = $10 AND id_age = $11;';
        $que_age = db_query_table_results($query, array($id_city, $id_age));
        if($row = mysql_fetch_assoc($que_age)){
            $query = "UPDATE ".DB_RESULTS_PREFIX."facebook_cities_ages SET 
                                                name = '$10',
                                                total_user = $11,
                                                updated_at = NOW()
                                                
                                                WHERE id_city = $12 AND id_age = $13;";
            $values = array(
                                $name,
                                $total_user,
                                $id_city,
                                $id_age
                          );
            $res = db_query_table_results($query, $values, 1);
        }
        else{
            $query = "INSERT INTO ".DB_RESULTS_PREFIX."facebook_cities_ages VALUES(NULL, $10, '$11', $12, $13, NOW());";
            $values = array(
                                $id_age,
                                $name,
                                $id_city,
                                $total_user
                           );
            $res = db_query_table_results($query, $values, 1);
        }
    }
    
    error_log('   Facebook City Age (f): '.date('d m Y H:i:s'));