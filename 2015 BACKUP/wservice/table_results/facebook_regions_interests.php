<?php

    set_time_limit(0);
    
    error_log('   Facebook Region Interest (i): '.date('d m Y H:i:s'));
    
    require_once(__DIR__.'/../config.php');
    
    function get_region_interests_date_last_update($count){
        $sql = 'SELECT date 
                  FROM '.DB_FACEBOOK_PREFIX.'record_region_interest_3_1
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
    
    function getCrecimiento($id_region, $id_interest, $last_update, $days){
        $sql = 'SELECT total_user, (total_user - (
                            SELECT total_user 
                            FROM '.DB_FACEBOOK_PREFIX.'record_region_interest_3_1 
                            WHERE date = (
                                        SELECT date 
                                        FROM '.DB_FACEBOOK_PREFIX.'record_region_interest_3_1 
                                        WHERE id_region = $3 AND id_interest = $4
                                              AND DATE_SUB(\'$1\',INTERVAL $2 DAY) <= date
                                        ORDER BY 1 ASC 
                                        LIMIT 1
                                    ) 
                                AND id_region = $3 AND id_interest = $4
                        )) cambio 
                FROM '.DB_FACEBOOK_PREFIX.'record_region_interest_3_1
                WHERE id_region = $3 AND id_interest = $4
                    AND date = \'$1\';
                ';
        
        $que = db_query($sql, array($last_update, $days, $id_region, $id_interest));
        if($fila = mysql_fetch_assoc($que)){
            return $fila['cambio'];
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
    
    $interests = array();
    $query = 'SELECT id_interest, nombre FROM '.DB_FACEBOOK_PREFIX.'interest_3_1 WHERE active_fb_get_data = 1 AND active = 1;';
    $que_interests = db_query($query, array());
    
    while($fila = mysql_fetch_assoc($que_interests)){
        $interests[$fila['id_interest']] = array('name' => $fila['nombre']);
    }
    
    $region_interests_last_update = get_region_interests_date_last_update($count_region * 5);
    
    $query = 'SELECT id_region, id_interest, total_user FROM '.DB_FACEBOOK_PREFIX.'record_region_interest_3_1 WHERE date = \'$1\';';
    $que = db_query($query, array($region_interests_last_update));
    while($region = mysql_fetch_assoc($que)){
        
        $id_interest = $region['id_interest'];
        
        $name = $interests[$region['id_interest']]['name'];
        
        $id_region = $region['id_region'];
        
        $total_user = $region['total_user'];
        
        $grow_30 = getCrecimiento($id_region, $id_interest, $region_interests_last_update, 30);
        
        $query = 'SELECT id_interest, id_region FROM '.DB_RESULTS_PREFIX.'facebook_regions_interests WHERE id_region = $10 AND id_interest = $11;';
        $que_interest = db_query_table_results($query, array($id_region, $id_interest));
        if($row = mysql_fetch_assoc($que_interest)){
            $query = "UPDATE ".DB_RESULTS_PREFIX."facebook_regions_interests SET 
                                                name = '$10',
                                                total_user = $11,
                                                grow_30 = $12,
                                                updated_at = NOW()
                                                
                                                WHERE id_region = $13 AND id_interest = $14;";
            $values = array(
                                $name,
                                $total_user,
                                $grow_30,
                                $id_region,
                                $id_interest
                          );
            $res = db_query_table_results($query, $values, 1);
        }
        else{
            $query = "INSERT INTO ".DB_RESULTS_PREFIX."facebook_regions_interests VALUES(NULL, $10, '$11', $12, $13, $14, NOW());";
            $values = array(
                                $id_interest,
                                $name,
                                $id_region,
                                $total_user,
                                $grow_30
                           );
            $res = db_query_table_results($query, $values, 1);
        }
    }
    
    error_log('   Facebook Region Interest (f): '.date('d m Y H:i:s'));