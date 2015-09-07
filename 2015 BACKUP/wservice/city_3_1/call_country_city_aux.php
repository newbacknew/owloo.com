<?php

    set_time_limit(0);
    
    require_once(__DIR__.'/../config.php');
    
    function get_sql_in($sql){
        $result = db_query($sql, array());
        $data = array();
        $data[] = 0;
        while($fila = mysql_fetch_assoc($result)){
            $data[] = $fila['id_city'];
        }
        return implode(',', $data) ;
    }
    
    function get_data_for_sql_in($table, $num = null){
        if($table == 'city'){
            $query = 'SELECT DISTINCT id_country FROM facebook_city_3_1 WHERE id_city NOT IN('.get_sql_in("SELECT id_city FROM `facebook_record_city_3_1` WHERE date = DATE(NOW())").');';
        }
        else {
            $query = 'SELECT DISTINCT id_country FROM facebook_city_3_1 WHERE id_city NOT IN('.get_sql_in('SELECT id_city FROM `facebook_record_city_'.$table.'_3_1` WHERE date = DATE(NOW()) GROUP BY id_city HAVING COUNT(*) = '.$num).');';
        }
        $result = db_query($query, array());
        $data = array();
        $data[] = 0;
        while($fila = mysql_fetch_assoc($result)){
            $data[] = $fila['id_country'];
        }
        return implode(',', $data) ;
    }
    
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'logs/';
    
    //Ciudades
    $query = 'SELECT id_city, key_city, id_country FROM `facebook_city_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;'; 
    $que = db_query($query, array());
    
    $total_city = mysql_num_rows($que);
    
    /****** CITY *****/
    if($_SERVER['argv'][1] == 'city'){
        
        $countries = array();
        $query = 'SELECT id_country id FROM facebook_country_3_1 WHERE supports_city = 1 AND id_country IN ('.get_data_for_sql_in('city').') AND active_fb_get_data = 1 ORDER BY 1;'; 
        $que = db_query($query, array());
        
        $count = 1;
        error_log('record_city (i - aux): '.date('d m Y H:i:s'));
        while($country = mysql_fetch_assoc($que)) {
            // 02:00 min
            exec('php-cli '.$folder_path.'call_record_city_aux.php '.$country['id'].' '.$total_city.' record_city 0 c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_city_aux.log 2>&1 &');
            usleep(rand(0, 100000));
            if ($count++ % 8 == 0)
                sleep(20);
        }
        error_log('record_city (f - aux): '.date('d m Y H:i:s'));
    
    }
    /****** END - CITY *****/
    
    /****** AGE *****/
    if($_SERVER['argv'][1] == 'city_age'){
        
        $query = 'SELECT count(*) cantidad FROM `facebook_age_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;';
        $result = db_query($query, array());
        $total_age = null;
        if($fila = mysql_fetch_assoc($result)){
            $total_age = $fila['cantidad'];
        }
        
        $countries = array();
        $query = 'SELECT id_country id FROM facebook_country_3_1 WHERE supports_city = 1 AND id_country IN ('.get_data_for_sql_in('age', $total_age).') AND active_fb_get_data = 1 ORDER BY 1;'; 
        $que = db_query($query, array());

        $count = 1;
        error_log('record_city_age (i - aux): '.date('d m Y H:i:s'));
        while($country = mysql_fetch_assoc($que)) {
            // 20:00 min
            exec('php-cli '.$folder_path.'call_record_city_aux.php '.$country['id'].' '.$total_city.' call_record_city_age '.$total_age.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_city_aux.log 2>&1 &');
            usleep(rand(0, 100000));
            if ($count++ % 1 == 0) 
                sleep(20);
        }
        error_log('record_city_age (f - aux): '.date('d m Y H:i:s'));
    
    }
    /****** END - AGE *****/
    
    /****** INTEREST *****/
    if($_SERVER['argv'][1] == 'city_interest'){
        
        $total_interest = 5;
        
        $countries = array();
        $query = 'SELECT id_country id FROM facebook_country_3_1 WHERE supports_city = 1 AND id_country IN ('.get_data_for_sql_in('interest', $total_interest).') AND active_fb_get_data = 1 ORDER BY 1;'; 
        $que = db_query($query, array());
        
        $count = 1;
        error_log('record_city_interest (i - aux): '.date('d m Y H:i:s'));
        while($country = mysql_fetch_assoc($que)) {
            // (12:00 min)
            exec('php-cli '.$folder_path.'call_record_city_aux.php '.$country['id'].' '.$total_city.' call_record_city_interest '.$total_interest.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_city_aux.log 2>&1 &');
            usleep(rand(0, 100000));
            if ($count++ % 1 == 0)
                sleep(20);
        }
        error_log('record_city_interest (f - aux): '.date('d m Y H:i:s'));
    
    }
    /****** END - INTEREST *****/
    
    /****** COMPORTAMIENTO *****/
    if($_SERVER['argv'][1] == 'city_comportamiento'){
        
        $query = "SELECT count(*) cantidad FROM facebook_mobile_os_city_3_1;";
        $result = db_query($query, array());
        $total_comportamiento = null;
        if($fila = mysql_fetch_assoc($result)){
            $total_comportamiento = $fila['cantidad'];
        }
        
        $countries = array();
        $query = 'SELECT id_country id FROM facebook_country_3_1 WHERE supports_city = 1 AND id_country IN ('.get_data_for_sql_in('comportamiento', $total_comportamiento).') AND active_fb_get_data = 1 ORDER BY 1;'; 
        $que = db_query($query, array());
        
        $count = 1;
        error_log('record_city_comportamiento (i - aux): '.date('d m Y H:i:s'));
        while($country = mysql_fetch_assoc($que)) {
            
            // (12:00 min)
            exec('php-cli '.$folder_path.'call_record_city_aux.php '.$country['id'].' '.$total_city.' call_record_city_comportamiento '.$total_comportamiento.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_city_aux.log 2>&1 &');
            
            usleep(rand(0, 100000));
            
            if ($count++ % 1 == 0)
                sleep(20);
         
        }
        error_log('record_city_comportamiento (f - aux): '.date('d m Y H:i:s'));
    
    }
    /****** END - COMPORTAMIENTO *****/
   
   /****** RELATIONSHIP *****/
   if($_SERVER['argv'][1] == 'city_relationship'){
       
        $query = "SELECT count(*) cantidad FROM `facebook_relationship_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;"; 
        $result = db_query($query, array());
        $total_relationship = null;
        if($fila = mysql_fetch_assoc($result)){
            $total_relationship = $fila['cantidad'];
        }
        
        $countries = array();
        $query = 'SELECT id_country id FROM facebook_country_3_1 WHERE supports_city = 1 AND id_country IN ('.get_data_for_sql_in('relationship', $total_relationship).') AND active_fb_get_data = 1 ORDER BY 1;'; 
        $que = db_query($query, array());
        
        $count = 1;
        error_log('record_city_relationship (i - aux): '.date('d m Y H:i:s'));
        while($country = mysql_fetch_assoc($que)) {
            
            // (20:00 min)
            exec('php-cli '.$folder_path.'call_record_city_aux.php '.$country['id'].' '.$total_city.' call_record_city_relationship '.$total_relationship.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_city_aux.log 2>&1 &');
            
            usleep(rand(0, 100000));
            if ($count++ % 1 == 0)
                sleep(40);
        }
        error_log('record_city_relationship (f - aux): '.date('d m Y H:i:s'));
    
    }
    /****** END - RELATIONSHIP *****/ 
    
    die();
