<?php
    
    set_time_limit(0);

    require_once('../config.php');
    
    function get_data_for_sql_in($table, $num = null){
        if($table == 'region'){
            $query = 'SELECT id_region FROM `facebook_record_region_3_1` WHERE date = DATE(NOW());';
        }
        else {
            $query = 'SELECT id_region FROM `facebook_record_region_'.$table.'_3_1` WHERE date = DATE(NOW()) GROUP BY id_region HAVING COUNT(*) = '.$num.';';
        }
        $result = db_query($query, array());
        $data = array();
        $data[] = 0;
        while($fila = mysql_fetch_assoc($result)){
            $data[] = $fila['id_region'];
        }
        return implode(',', $data) ;
    }
    
    function get_data_id_secundary_for_sql_in($id_region, $column){
        $query = 'SELECT id_$1 id FROM `facebook_record_region_$1_3_1` WHERE id_region = $2 AND date = DATE(NOW())';
        $result = db_query($query, array($column, $id_region));
        $data = array();
        $data[] = 0;
        while($fila = mysql_fetch_assoc($result)){
            $data[] = $fila['id'];
        }
        return implode(',', $data) ;
    }
    
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'logs/';
    
    //Regiones
    $regions = array();
    $query = 'SELECT id_region, region_key, id_country FROM `facebook_region_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;'; 
    $que = db_query($query, array());
    
    $total_region = mysql_num_rows($que);
    
    /***** REGION *****/
    if($_SERVER['argv'][1] == 'region'){
        
        $query = 'SELECT id_region id, region_key code, id_country FROM `facebook_region_3_1` WHERE id_region NOT IN('.get_data_for_sql_in('region').') AND active_fb_get_data = 1 ORDER BY 1;'; 
        $que = db_query($query, array());
        
        error_log('record_region (i - aux): '.date('d m Y H:i:s'));
        while($region = mysql_fetch_assoc($que)) {
            //Audiencia total (00:30 min)
            exec('php-cli '.$folder_path.'record_region.php '.$region['id'].' '.$region['code'].' '.$total_region.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'record_region.log 2>&1 &');
            usleep(rand(0, 100000));
        }
        error_log('record_region (f - aux): '.date('d m Y H:i:s'));
        
    }
    /***** END - REGION *****/
    
    /***** AGE *****/
    if($_SERVER['argv'][1] == 'region_age'){
        
        $query = 'SELECT count(*) cantidad FROM `facebook_age_3_1` WHERE active_fb_get_data = 1;';
        $result = db_query($query, array());
        if($fila = mysql_fetch_assoc($result)){
            $total_age = $fila['cantidad'];
        }
        
        $query = 'SELECT id_region id, region_key code, id_country FROM `facebook_region_3_1` WHERE id_region NOT IN('.get_data_for_sql_in('age', $total_age).') AND active_fb_get_data = 1 ORDER BY 1;'; 
        $que = db_query($query, array());
        
        $count = 1;
        error_log('record_region_age (i): '.date('d m Y H:i:s'));
        while($region = mysql_fetch_assoc($que)) {
            
            $query = 'SELECT id_age id, min, max FROM `facebook_age_3_1` WHERE active_fb_get_data = 1 AND id_age NOT IN('.get_data_id_secundary_for_sql_in($region['id'], 'age').') ORDER BY 1;';
            $result = db_query($query, array());
            $ages = array();
            while($fila = mysql_fetch_assoc($result)){
                $ages[] = $fila;
            }
            $ages = json_encode($ages);
            
            exec('php-cli '.$folder_path.'call_record_region_age.php '.$region['id'].' '.$region['code'].' '.$total_region.' '.urlencode($ages).' '.$total_age.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_region_age.log 2>&1 &');
            usleep(rand(0, 100000));
            if ($count++ % 100 == 0)
                sleep(20);
        }
        error_log('record_region_age (f): '.date('d m Y H:i:s'));
    
    }
    /***** END - AGE *****/
    
    /***** RELATIONSHIP *****/
    if($_SERVER['argv'][1] == 'region_relationship'){
        
        $query = 'SELECT count(*) cantidad FROM `facebook_relationship_3_1` WHERE active_fb_get_data = 1;';
        $result = db_query($query, array());
        if($fila = mysql_fetch_assoc($result)){
            $total_relationship = $fila['cantidad'];
        }
        
        $query = 'SELECT id_region id, region_key code, id_country FROM `facebook_region_3_1` WHERE id_region NOT IN('.get_data_for_sql_in('relationship', $total_relationship).') AND active_fb_get_data = 1 ORDER BY 1;'; 
        $que = db_query($query, array());
        
        $count = 1;
        error_log('record_region_relationship (i): '.date('d m Y H:i:s'));
        while($region = mysql_fetch_assoc($que)) {
                
            $query = "SELECT id_relationship id, key_relationship 'key' FROM `facebook_relationship_3_1` WHERE active_fb_get_data = 1 AND id_relationship NOT IN(".get_data_id_secundary_for_sql_in($region['id'], 'relationship').") ORDER BY 1;"; 
            $result = db_query($query, array());
            $relationships = array();
            while($fila = mysql_fetch_assoc($result)){
                $relationships[] = $fila;
            }
            $relationships = json_encode($relationships);
            
            exec('php-cli '.$folder_path.'call_record_region_relationship.php '.$region['id'].' '.$region['code'].' '.$total_region.' '.urlencode($relationships).' '.$total_relationship.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_region_relationship.log 2>&1 &');
            usleep(rand(0, 100000));
            if ($count++ % 70 == 0)
                sleep(20);
        }
        error_log('record_region_relationship (f): '.date('d m Y H:i:s'));
    
    }
    /***** END - RELATIONSHIP *****/

    
    /***** INTEREST *****/
    if($_SERVER['argv'][1] == 'region_interest'){
        
        $total_interest = 5;
        
        $query = 'SELECT id_region id, region_key code, id_country FROM `facebook_region_3_1` WHERE id_region NOT IN('.get_data_for_sql_in('interest', $total_interest).') AND active_fb_get_data = 1 ORDER BY 1;'; 
        $que = db_query($query, array());
        
        $count = 1;
        error_log('record_region_interest (i): '.date('d m Y H:i:s'));
        while($region = mysql_fetch_assoc($que)) {
            
            $query = "SELECT id_interest id, key_interest 'key' FROM `facebook_interest_3_1` WHERE id_interest IN(SELECT id_interest FROM facebook_interest_city_3_1 WHERE id_country = $1) AND active_fb_get_data = 1 AND id_interest NOT IN(".get_data_id_secundary_for_sql_in($region['id'], 'interest').") ORDER BY 1;";
            $result = db_query($query, array($region['id_country']));
            $interests = array();
            while($fila = mysql_fetch_assoc($result)){
                $interests[] = $fila;
            }
            $interests = json_encode($interests);
            
            exec('php-cli '.$folder_path.'call_record_region_interest.php '.$region['id'].' '.$region['code'].' '.$region['id_country'].' '.$total_region.' '.urlencode($interests).' '.$total_interest.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_region_interest.log 2>&1 &');
            usleep(rand(0, 100000));
            if ($count++ % 100 == 0)
                sleep(20);
        }
        error_log('record_region_interest (f): '.date('d m Y H:i:s'));
    
    }
    /***** END - INTEREST *****/
    
    /***** COMPORTAMIENTO *****/
    if($_SERVER['argv'][1] == 'region_comportamiento'){
        
        $query = 'SELECT count(*) cantidad FROM `facebook_mobile_os_city_3_1`;';
        $result = db_query($query, array());
        if($fila = mysql_fetch_assoc($result)){
            $total_comportamiento = $fila['cantidad'];
        }
        
        $query = 'SELECT id_region id, region_key code, id_country FROM `facebook_region_3_1` WHERE id_region NOT IN('.get_data_for_sql_in('comportamiento', $total_comportamiento).') AND active_fb_get_data = 1 ORDER BY 1;'; 
        $que = db_query($query, array());
        
        $count = 1;
        error_log('record_region_comportamiento (i): '.date('d m Y H:i:s'));
        while($region = mysql_fetch_assoc($que)) {
            
            $query = "SELECT id_comportamiento id, key_comportamiento 'key' FROM `facebook_comportamiento_3_1` WHERE id_comportamiento IN(SELECT id_comportamiento FROM facebook_mobile_os_city_3_1) AND active_fb_get_data = 1 AND id_comportamiento NOT IN(".get_data_id_secundary_for_sql_in($region['id'], 'comportamiento').") ORDER BY 1;";
            $result = db_query($query, array());
            $comportamientos = array();
            while($fila = mysql_fetch_assoc($result)){
                $comportamientos[] = $fila;
            }
            $comportamientos = json_encode($comportamientos);
        
            exec('php-cli '.$folder_path.'call_record_region_comportamiento.php '.$region['id'].' '.$region['code'].' '.$total_region.' '.urlencode($comportamientos).' '.$total_comportamiento.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_region_comportamiento.log 2>&1 &');       
            usleep(rand(0, 100000));
            if ($count++ % 100 == 0)
                sleep(20);
        }
        error_log('record_region_comportamiento (f): '.date('d m Y H:i:s'));
    
    }
    /***** END - COMPORTAMIENTO *****/ 