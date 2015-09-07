<?php
    
    set_time_limit(0);

    require_once(__DIR__.'/../config.php');
    
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'logs/';
    
    //Países
    $countries = array();
    $query = 'SELECT id_country, code FROM `facebook_country_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;'; 
    $que = db_query($query, array());
    
    while($fila = mysql_fetch_assoc($que)){
        $countries[] = array('id' => $fila['id_country'], 'code' => $fila['code']);
    }
    $total_country = count($countries);
    
    /****** AUDIENCE *****/
    error_log('record_country (i): '.date('d m Y H:i:s'));
    foreach ($countries as $country) {
        // 00:20 min
        exec('php-cli '.$folder_path.'record_country.php '.$country['id'].' '.$country['code'].' '.$total_country.' 0 c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'record_country.log 2>&1 &');
        usleep(rand(0, 100000));
    }
    error_log('record_country (f): '.date('d m Y H:i:s')); 
    sleep(60);
    
    /* 
     * record_country (i): 01 10 2014 08:46:26
     * record_country (f): 01 10 2014 08:46:39
     * 
     * 
    */
    /****** END - AUDIENCE *****/
    
    /****** AGE *****/
    $query = 'SELECT id_age id, min, max FROM `facebook_age_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;';
    $result = db_query($query, array());
    $ages = array();
    while($fila = mysql_fetch_assoc($result)){
        $ages[] = $fila;
    }
    $total_age = count($ages);
    $ages = json_encode($ages);
    
    $count = 1;
    error_log('record_country_age (i): '.date('d m Y H:i:s'));
    foreach ($countries as $country) {
        // 01:00 min
        exec('php-cli '.$folder_path.'call_record_country_age.php '.$country['id'].' '.$country['code'].' '.$total_country.' '.urlencode($ages).' '.$total_age.' 0 c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_country_age.log 2>&1 &');
        usleep(rand(0, 100000));
        if ($count++ % 100 == 0)
            sleep(20);
    }
    error_log('record_country_age (f): '.date('d m Y H:i:s'));
    sleep(60);
    
     /*
     * 
     * record_country_age (i): 01 10 2014 08:49:23
     * record_country_age (f): 01 10 2014 08:50:11
     * 
     * 
    */
    /****** END - AGE *****/
    
    /****** LANGUAGE *****/
    $query = "SELECT id_language id, key_language 'key' FROM `facebook_language_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;";
    $result = db_query($query, array());
    $languages = array();
    while($fila = mysql_fetch_assoc($result)){
        $languages[] = $fila;
    }
    $total_language = count($languages);
    $languages = json_encode($languages);
    
    $count = 1;
    error_log('record_country_language (i): '.date('d m Y H:i:s'));
    foreach ($countries as $country) {
        // 06:00 min
        exec('php-cli '.$folder_path.'call_record_country_language.php '.$country['id'].' '.$country['code'].' '.$total_country.' '.urlencode($languages).' '.$total_language.' 0 c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_country_language.log 2>&1 &');
        usleep(rand(0, 100000));
        if ($count++ % 15 == 0)
            sleep(20);
    }
    error_log('record_country_language (f): '.date('d m Y H:i:s'));
    sleep(180);
    
    /*
     * 
     * record_country_language (i): 09 10 2014 15:07:49
     * record_country_language (f): 09 10 2014 15:13:21
     * 
     *
    */
    /****** END - LANGUAGE *****/
    
    /****** RELATIONSHIP *****/
    $query = "SELECT id_relationship id, key_relationship 'key' FROM `facebook_relationship_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;"; 
    $result = db_query($query, array());
    $relationships = array();
    while($fila = mysql_fetch_assoc($result)){
        $relationships[] = $fila;
    }
    $total_relationship = count($relationships);
    $relationships = json_encode($relationships);
    
    $count = 1;
    error_log('record_country_relationship (i): '.date('d m Y H:i:s'));
    foreach ($countries as $country) {
        // 03:00 min
        exec('php-cli '.$folder_path.'call_record_country_relationship.php '.$country['id'].' '.$country['code'].' '.$total_country.' '.urlencode($relationships).' '.$total_relationship.' 0 c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_country_relationship.log 2>&1 &');
        usleep(rand(0, 100000));
        if ($count++ % 70 == 0)
            sleep(20);
    }
    error_log('record_country_relationship (f): '.date('d m Y H:i:s'));
    sleep(120);
    
    /*
     * 
     * record_country_relationship (i): 01 10 2014 09:02:28
     * record_country_relationship (f): 01 10 2014 09:04:19
     * 
     *
    */
    /****** END - RELATIONSHIP *****/
    
    /****** GENERATION *****/
    $query = "SELECT id_generation id, key_generation 'key' FROM `facebook_generation_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;"; 
    $result = db_query($query, array());
    $generations = array();
    while($fila = mysql_fetch_assoc($result)){
        $generations[] = $fila;
    }
    $total_generation = count($generations);
    $generations = json_encode($generations);
    
    error_log('record_country_generation (i): '.date('d m Y H:i:s'));
    foreach ($countries as $country) {
        
        //Audiencia por generación (00:20 min)
        exec('php-cli '.$folder_path.'call_record_country_generation.php '.$country['id'].' '.$country['code'].' '.$total_country.' '.urlencode($generations).' '.$total_generation.' 0 c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_country_generation.log 2>&1 &');
        
        usleep(rand(0, 100000));
        
    }
    error_log('record_country_generation (f): '.date('d m Y H:i:s'));
    sleep(60);
    
    /*
     * 
     * record_country_generation (i): 01 10 2014 09:06:59
     * record_country_generation (f): 01 10 2014 09:07:12
     * 
     *
    */
    /****** END - GENERATION *****/
    
    /****** INTEREST *****/
    $query = "SELECT id_interest id, key_interest 'key' FROM `facebook_interest_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;";
    $result = db_query($query, array());
    $interests = array();
    while($fila = mysql_fetch_assoc($result)){
        $interests[] = $fila;
    }
    $total_interest = count($interests);
    $interests = json_encode($interests);
    
    $count = 1;
    error_log('record_country_interest (i): '.date('d m Y H:i:s'));
    foreach ($countries as $country) {
        
        //Audiencia por intereses (30:00 min)
        exec('php-cli '.$folder_path.'call_record_country_interest.php '.$country['id'].' '.$country['code'].' '.$total_country.' '.urlencode($interests).' '.$total_interest.' 0 c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_country_interest.log 2>&1 &');
        
        usleep(rand(0, 100000));
        
        if ($count++ % 3 == 0)
            sleep(20);
        
    }
    error_log('record_country_interest (f): '.date('d m Y H:i:s'));
    sleep(600);
    
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
    $query = "SELECT id_comportamiento id, key_comportamiento 'key' FROM `facebook_comportamiento_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;";
    $result = db_query($query, array());
    $comportamientos = array();
    while($fila = mysql_fetch_assoc($result)){
        $comportamientos[] = $fila;
    }
    $total_comportamiento = count($comportamientos);
    $comportamientos = json_encode($comportamientos);
    
    $count = 1;
    error_log('record_country_comportamiento (i): '.date('d m Y H:i:s'));
    foreach ($countries as $country) {
        
        //Audiencia por comportamientos (12:00 min)
        exec('php-cli '.$folder_path.'call_record_country_comportamiento.php '.$country['id'].' '.$country['code'].' '.$total_country.' '.urlencode($comportamientos).' '.$total_comportamiento.' 0 c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_country_comportamiento.log 2>&1 &');
        
        usleep(rand(0, 100000));
        
        if ($count++ % 5 == 0)
            sleep(20);
        
    }
    error_log('record_country_comportamiento (f): '.date('d m Y H:i:s'));
    
    /* 
     * 
     * record_country_comportamiento (i): 01 10 2014 09:39:06
     * record_country_comportamiento (f): 01 10 2014 09:51:19
     * 
     *
    */
   /****** END - COMPORTAMIENTO *****/ 
   
   die();
