<?php
    
    set_time_limit(0);
    
    require_once(__DIR__.'/../config.php');
    
    function get_data_for_sql_in($table, $num = null){
        if($table == 'country'){
            $query = 'SELECT id_country FROM `facebook_record_country_3_1` WHERE date = DATE(NOW());';
        }
        else {
            $query = 'SELECT id_country FROM `facebook_record_country_'.$table.'_3_1` WHERE date = DATE(NOW()) GROUP BY id_country HAVING COUNT(*) = '.$num.';';
        }
        $result = db_query($query, array());
        $data = array();
        $data[] = 0;
        while($fila = mysql_fetch_assoc($result)){
            $data[] = $fila['id_country'];
        }
        return implode(',', $data) ;
    }
    
    function get_data_id_secundary_for_sql_in($id_country, $column){
        $query = 'SELECT id_$1 id FROM `facebook_record_country_$1_3_1` WHERE id_country = $2 AND date = DATE(NOW())';
        $result = db_query($query, array($column, $id_country));
        $data = array();
        $data[] = 0;
        while($fila = mysql_fetch_assoc($result)){
            $data[] = $fila['id'];
        }
        return implode(',', $data) ;
    }
    
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'logs/';
    
    //Países
    $countries = array();
    $query = 'SELECT id_country, code FROM `facebook_country_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;'; 
    $que = db_query($query, array());
    
    $total_country = mysql_num_rows($que);
    
    /****** AUDIENCE *****/
    if($_SERVER['argv'][1] == 'country'){
    
        $query = 'SELECT id_country id, code FROM `facebook_country_3_1` WHERE id_country NOT IN('.get_data_for_sql_in('country').') AND active_fb_get_data = 1 ORDER BY 1;';
        $que = db_query($query, array());
        
        error_log('record_country (i - aux): '.date('d m Y H:i:s'));
        while($country = mysql_fetch_assoc($que)){
            exec('php-cli '.$folder_path.'record_country.php '.$country['id'].' '.$country['code'].' '.$total_country.' 1 c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'record_country.log 2>&1 &');
            usleep(rand(0, 100000));
        } die();
        error_log('record_country (f - aux): '.date('d m Y H:i:s'));
    
    }
    
    /* 
     * record_country (i): 01 10 2014 08:46:26
     * record_country (f): 01 10 2014 08:46:39
     * 
     * 
    */
    /****** END - AUDIENCE *****/
    
    /****** AGE *****/
    if($_SERVER['argv'][1] == 'country_age'){
        
        $query = 'SELECT count(*) cantidad FROM `facebook_age_3_1` WHERE active_fb_get_data = 1;';
        $result = db_query($query, array());
        if($fila = mysql_fetch_assoc($result)){
            $total_age = $fila['cantidad'];
        }
    
        $query = 'SELECT id_country id, code FROM `facebook_country_3_1` WHERE id_country NOT IN('.get_data_for_sql_in('age', $total_age).') AND active_fb_get_data = 1 ORDER BY 1;';
        $que = db_query($query, array());
        
        $count = 1;
        error_log('record_country_age (i - aux): '.date('d m Y H:i:s'));
        while($country = mysql_fetch_assoc($que)){
        
            $query = 'SELECT id_age id, min, max FROM `facebook_age_3_1` WHERE active_fb_get_data = 1 AND id_age NOT IN('.get_data_id_secundary_for_sql_in($country['id'], 'age').') ORDER BY 1;';
            $result = db_query($query, array());
            $ages = array();
            while($fila = mysql_fetch_assoc($result)){
                $ages[] = $fila;
            }
            $ages = json_encode($ages);
        
            // 01:00 min
            exec('php-cli '.$folder_path.'call_record_country_age.php '.$country['id'].' '.$country['code'].' '.$total_country.' '.urlencode($ages).' '.$total_age.' 1 c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_country_age.log 2>&1 &');
            usleep(rand(0, 100000));
            if ($count++ % 100 == 0)
                sleep(20);
        }
        error_log('record_country_age (f - aux): '.date('d m Y H:i:s'));
    
    }
    
     /*
     * 
     * record_country_age (i): 01 10 2014 08:49:23
     * record_country_age (f): 01 10 2014 08:50:11
     * 
     * 
    */
    /****** END - AGE *****/
    
    /****** LANGUAGE *****/
    if($_SERVER['argv'][1] == 'country_language'){
    
        $query = 'SELECT count(*) cantidad FROM `facebook_language_3_1` WHERE active_fb_get_data = 1;';
        $result = db_query($query, array());
        if($fila = mysql_fetch_assoc($result)){
            $total_language = $fila['cantidad'];
        }
        
        $query = 'SELECT id_country id, code FROM `facebook_country_3_1` WHERE id_country NOT IN('.get_data_for_sql_in('language', $total_language).') AND active_fb_get_data = 1 ORDER BY 1;';
        $que = db_query($query, array());
        
        $count = 1;
        error_log('record_country_language (i - aux): '.date('d m Y H:i:s'));
        while($country = mysql_fetch_assoc($que)){
            
            $query = "SELECT id_language id, key_language 'key' FROM `facebook_language_3_1` WHERE active_fb_get_data = 1 AND id_language NOT IN(".get_data_id_secundary_for_sql_in($country['id'], 'language').") ORDER BY 1;";
            $result = db_query($query, array());
            $languages = array();
            while($fila = mysql_fetch_assoc($result)){
                $languages[] = $fila;
            }
            $languages = json_encode($languages);
            
            // 06:00 min
            exec('php-cli '.$folder_path.'call_record_country_language.php '.$country['id'].' '.$country['code'].' '.$total_country.' '.urlencode($languages).' '.$total_language.' 1 c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_country_language.log 2>&1 &');
            usleep(rand(0, 100000));
            if ($count++ % 15 == 0)
                sleep(20);
        }
        error_log('record_country_language (f - aux): '.date('d m Y H:i:s'));
    
    }
    
    /*
     * 
     * record_country_language (i): 01 10 2014 08:53:02
     * record_country_language (f): 01 10 2014 08:57:18
     * 
     *
    */
    /****** END - LANGUAGE *****/
    
    /****** RELATIONSHIP *****/
    if($_SERVER['argv'][1] == 'country_relationship'){
        
        $query = 'SELECT count(*) cantidad FROM `facebook_relationship_3_1` WHERE active_fb_get_data = 1;';
        $result = db_query($query, array());
        if($fila = mysql_fetch_assoc($result)){
            $total_relationship = $fila['cantidad'];
        }
                
        $query = 'SELECT id_country id, code FROM `facebook_country_3_1` WHERE id_country NOT IN('.get_data_for_sql_in('relationship', $total_relationship).') AND active_fb_get_data = 1 ORDER BY 1;';
        $que = db_query($query, array());
        
        $count = 1;
        error_log('record_country_relationship (i - aux): '.date('d m Y H:i:s'));
        while($country = mysql_fetch_assoc($que)){
            
            $query = "SELECT id_relationship id, key_relationship 'key' FROM `facebook_relationship_3_1` WHERE active_fb_get_data = 1 AND id_relationship NOT IN(".get_data_id_secundary_for_sql_in($country['id'], 'relationship').") ORDER BY 1;"; 
            $result = db_query($query, array());
            $relationships = array();
            while($fila = mysql_fetch_assoc($result)){
                $relationships[] = $fila;
            }
            $relationships = json_encode($relationships);
            
            // 03:00 min
            exec('php-cli '.$folder_path.'call_record_country_relationship.php '.$country['id'].' '.$country['code'].' '.$total_country.' '.urlencode($relationships).' '.$total_relationship.' 1 c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_country_relationship.log 2>&1 &');
            usleep(rand(0, 100000));
            if ($count++ % 70 == 0)
                sleep(20);
        }
        error_log('record_country_relationship (f - aux): '.date('d m Y H:i:s'));
    
    }
    
    /*
     * 
     * record_country_relationship (i): 01 10 2014 09:02:28
     * record_country_relationship (f): 01 10 2014 09:04:19
     * 
     *
    */
    /****** END - RELATIONSHIP *****/
    
    /****** GENERATION *****/
    if($_SERVER['argv'][1] == 'country_generation'){
        
        $query = 'SELECT count(*) cantidad FROM `facebook_generation_3_1` WHERE active_fb_get_data = 1;';
        $result = db_query($query, array());
        if($fila = mysql_fetch_assoc($result)){
            $total_generation = $fila['cantidad'];
        }
        
        $query = 'SELECT id_country id, code FROM `facebook_country_3_1` WHERE id_country NOT IN('.get_data_for_sql_in('generation', $total_generation).') AND active_fb_get_data = 1 ORDER BY 1;';
        $que = db_query($query, array());
        
        error_log('record_country_generation (i - aux): '.date('d m Y H:i:s'));
        while($country = mysql_fetch_assoc($que)){
            
            $query = "SELECT id_generation id, key_generation 'key' FROM `facebook_generation_3_1` WHERE active_fb_get_data = 1 AND id_generation NOT IN(".get_data_id_secundary_for_sql_in($country['id'], 'generation').") ORDER BY 1;"; 
            $result = db_query($query, array());
            $generations = array();
            while($fila = mysql_fetch_assoc($result)){
                $generations[] = $fila;
            }
            $generations = json_encode($generations);
            
            //Audiencia por generación (00:20 min)
            exec('php-cli '.$folder_path.'call_record_country_generation.php '.$country['id'].' '.$country['code'].' '.$total_country.' '.urlencode($generations).' '.$total_generation.' 1 c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_country_generation.log 2>&1 &');
            usleep(rand(0, 100000));
        }
        error_log('record_country_generation (f - aux): '.date('d m Y H:i:s'));
    
    }
    
    /*
     * 
     * record_country_generation (i): 01 10 2014 09:06:59
     * record_country_generation (f): 01 10 2014 09:07:12
     * 
     *
    */
    /****** END - GENERATION *****/
    
    /****** INTEREST *****/
    if($_SERVER['argv'][1] == 'country_interest'){
        
        $query = 'SELECT count(*) cantidad FROM `facebook_interest_3_1` WHERE active_fb_get_data = 1;';
        $result = db_query($query, array());
        if($fila = mysql_fetch_assoc($result)){
            $total_interest = $fila['cantidad'];
        }
        
        $query = 'SELECT id_country id, code FROM `facebook_country_3_1` WHERE id_country NOT IN('.get_data_for_sql_in('interest', $total_interest).') AND active_fb_get_data = 1 ORDER BY 1;';
        $que = db_query($query, array());
        
        $count = 1;
        error_log('record_country_interest (i - aux): '.date('d m Y H:i:s'));
        while($country = mysql_fetch_assoc($que)){
            
            $query = "SELECT id_interest id, key_interest 'key' FROM `facebook_interest_3_1` WHERE active_fb_get_data = 1 AND id_interest NOT IN(".get_data_id_secundary_for_sql_in($country['id'], 'interest').") ORDER BY 1;";
            $result = db_query($query, array());
            $interests = array();
            while($fila = mysql_fetch_assoc($result)){
                $interests[] = $fila;
            }
            $interests = json_encode($interests);
            
            //Audiencia por intereses (30:00 min)
            exec('php-cli '.$folder_path.'call_record_country_interest.php '.$country['id'].' '.$country['code'].' '.$total_country.' '.urlencode($interests).' '.$total_interest.' 1 c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_country_interest.log 2>&1 &');
            usleep(rand(0, 100000));
            if ($count++ % 3 == 0)
                sleep(20);
        }
        error_log('record_country_interest (f - aux): '.date('d m Y H:i:s'));
    
    }
    
     /* 
      * 
      * record_country_interest (i): 01 10 2014 09:09:01
      * record_country_interest (f): 01 10 2014 09:33:45
      * 
      * 
      * 
      * sleep(30);
    
    error_log(date('d m Y H:i:s'));
    */
    /****** END - INTEREST *****/
    
    /****** COMPORTAMIENTO *****/
    if($_SERVER['argv'][1] == 'country_comportamiento'){
        
        $query = 'SELECT count(*) cantidad FROM `facebook_comportamiento_3_1` WHERE active_fb_get_data = 1;';
        $result = db_query($query, array());
        if($fila = mysql_fetch_assoc($result)){
            $total_comportamiento = $fila['cantidad'];
        }
        
        $query = 'SELECT id_country id, code FROM `facebook_country_3_1` WHERE id_country NOT IN('.get_data_for_sql_in('comportamiento', $total_comportamiento).') AND active_fb_get_data = 1 ORDER BY 1;';
        $que = db_query($query, array());
        
        $count = 1;
        error_log('record_country_comportamiento (i - aux): '.date('d m Y H:i:s'));
        while($country = mysql_fetch_assoc($que)){
            
            
            $query = "SELECT id_comportamiento id, key_comportamiento 'key' FROM `facebook_comportamiento_3_1` WHERE active_fb_get_data = 1 AND id_comportamiento NOT IN(".get_data_id_secundary_for_sql_in($country['id'], 'comportamiento').") ORDER BY 1;";
            $result = db_query($query, array());
            $comportamientos = array();
            while($fila = mysql_fetch_assoc($result)){
                $comportamientos[] = $fila;
            }
            $comportamientos = json_encode($comportamientos);
            
            //Audiencia por comportamientos (12:00 min)
            exec('php-cli '.$folder_path.'call_record_country_comportamiento.php '.$country['id'].' '.$country['code'].' '.$total_country.' '.urlencode($comportamientos).' '.$total_comportamiento.' 1 c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_country_comportamiento.log 2>&1 &');
            usleep(rand(0, 100000));
            if ($count++ % 7 == 0)
                sleep(20);
        }
        error_log('record_country_comportamiento (f - aux): '.date('d m Y H:i:s'));
    
    }

    /* 
     * 
     * record_country_comportamiento (i): 01 10 2014 09:39:06
     * record_country_comportamiento (f): 01 10 2014 09:51:19
     * 
     *
    */
   /****** END - COMPORTAMIENTO *****/ 
   
   die();
