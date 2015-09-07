<?php

    set_time_limit(0);
    
    if($_SERVER['argv'][1] != 'c45a5f3b2cfa74ac94bd5bbfb2c5d6a5'){
        die();
    }
    
    require_once(__DIR__.'/../config.php');
    
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'logs/';
    
    //Ciudades
    $query = 'SELECT id_city, key_city, id_country FROM `facebook_city_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;'; 
    $que = db_query($query, array());
    
    $total_city = mysql_num_rows($que);
    
    //Países
    $countries = array();
    $query = 'SELECT id_country FROM facebook_country_3_1 WHERE supports_city = 1 AND active_fb_get_data = 1 ORDER BY 1;'; 
    $que = db_query($query, array());
    
    while($fila = mysql_fetch_assoc($que)){
        $countries[] = array('id' => $fila['id_country']);
    }
 
    
    /****** CITY *****/
    $count = 1;
    error_log('record_city (i): '.date('d m Y H:i:s'));
    foreach ($countries as $country) {
        // 02:00 min
        exec('php-cli '.$folder_path.'call_record_city.php '.$country['id'].' '.$total_city.' record_city null c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_city.log 2>&1 &');
        usleep(rand(0, 100000));
        if ($count++ % 8 == 0)
            sleep(20);
    }
    error_log('record_city (f): '.date('d m Y H:i:s'));
    sleep(120);
    
    /* 
     * 
     * record_city (i): 01 10 2014 11:17:56
     * record_city (f): 01 10 2014 11:19:59
     * 
     * 
    */ 
    /****** END - CITY *****/
    
    /****** AGE *****/
    $query = 'SELECT id_age id, min, max FROM `facebook_age_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;';
    $result = db_query($query, array());
    $ages = array();
    while($fila = mysql_fetch_assoc($result)){
        $ages[] = $fila;
    }
    $ages = json_encode($ages);
	
    $count = 1;
    error_log('record_city_age (i): '.date('d m Y H:i:s'));
    foreach ($countries as $country) {
        // 20:00 min
        exec('php-cli '.$folder_path.'call_record_city.php '.$country['id'].' '.$total_city.' call_record_city_age '.urlencode($ages).' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_city.log 2>&1 &');
        usleep(rand(0, 100000));
        if ($count++ % 1 == 0) 
            sleep(20);
    }
    error_log('record_city_age (f): '.date('d m Y H:i:s'));
    sleep(420);
    
    /*
     * 
     * record_city_age (i): 01 10 2014 11:36:44
     * record_city_age (f): 01 10 2014 11:52:17
     * 
     * 
     * 
    */
    /****** END - AGE *****/
    
    /****** INTEREST *****/
	$count = 1;
    error_log('record_city_interest (i): '.date('d m Y H:i:s'));
    foreach ($countries as $country) {
        // (12:00 min)
        exec('php-cli '.$folder_path.'call_record_city.php '.$country['id'].' '.$total_city.' call_record_city_interest null c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_city.log 2>&1 &');
        usleep(rand(0, 100000));
        if ($count++ % 1 == 0)
            sleep(20);
    }
    error_log('record_city_interest (f): '.date('d m Y H:i:s'));
    sleep(300);
    
    /*
     * 
     * record_city_interest (i): 01 10 2014 12:23:48
     * record_city_interest (f): 01 10 2014 12:34:11
     * 
     * 
     * 
    */
    /****** END - INTEREST *****/
    
	/****** COMPORTAMIENTO *****/
    $query = "SELECT id_comportamiento id, key_comportamiento 'key' FROM `facebook_comportamiento_3_1` WHERE id_comportamiento IN (SELECT id_comportamiento FROM facebook_mobile_os_city_3_1) AND active_fb_get_data = 1 ORDER BY 1;";
    $result = db_query($query, array());
    $comportamientos = array();
    while($fila = mysql_fetch_assoc($result)){
        $comportamientos[] = $fila;
    }
    $comportamientos = json_encode($comportamientos);
    
    $count = 1;
    error_log('record_city_comportamiento (f): '.date('d m Y H:i:s'));
    foreach ($countries as $country) {
        
        // (12:00 min)
        exec('php-cli '.$folder_path.'call_record_city.php '.$country['id'].' '.$total_city.' call_record_city_comportamiento '.urlencode($comportamientos).' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_city.log 2>&1 &');
        
        usleep(rand(0, 100000));
        
        if ($count++ % 1 == 0)
            sleep(20);
     
    }
    error_log('record_city_comportamiento (f): '.date('d m Y H:i:s'));
    sleep(420);
    
    /*
     * 
     * record_city_comportamiento (f): 01 10 2014 12:44:11
     * record_city_comportamiento (f): 01 10 2014 12:54:35
     * 
     * 
     * 
    */
    /****** END - COMPORTAMIENTO *****/
   
   /****** RELATIONSHIP *****/
    $query = "SELECT id_relationship id, key_relationship 'key' FROM `facebook_relationship_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;"; 
    $result = db_query($query, array());
    $relationships = array();
    while($fila = mysql_fetch_assoc($result)){
        $relationships[] = $fila;
    }
    $relationships = json_encode($relationships);
    
    $count = 1;
    error_log('record_city_relationship (i): '.date('d m Y H:i:s'));
    foreach ($countries as $country) {
        
        // (20:00 min)
        exec('php-cli '.$folder_path.'call_record_city.php '.$country['id'].' '.$total_city.' call_record_city_relationship '.urlencode($relationships).' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_city.log 2>&1 &');
        usleep(rand(0, 100000));
        if ($count++ % 1 == 0)
            sleep(40);
    }
    error_log('record_city_relationship (f): '.date('d m Y H:i:s'));
    
    /*
     * 
     * record_city_relationship (i): 01 10 2014 12:02:13
     * record_city_relationship (f): 01 10 2014 12:23:16
     * 
     * 
     * 
    */
    /****** END - RELATIONSHIP *****/
   
   die(); 