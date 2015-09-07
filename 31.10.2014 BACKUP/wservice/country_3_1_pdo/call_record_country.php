<?php
    
    set_time_limit(0);

    require_once('../config_pdo.php');
    
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'logs/';
    
    //Países
    $countries = array();
    $query = 'SELECT id_country id, code FROM `facebook_country_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;'; 
    $result = db_query($query, array());
    
    $total_country = $result->rowCount();
    $countries = $result->fetchAll(PDO::FETCH_ASSOC);
    
    /****** AUDIENCE *****/
    /*error_log('record_country (i): '.date('d m Y H:i:s'));
    foreach ($countries as $country) {
        
        //Audiencia total (01:10 min)
        exec('php-cli '.$folder_path.'record_country.php '.$country['id'].' '.$country['code'].' '.$total_country.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'record_country.log 2>&1 &');
        
        usleep(rand(0, 100000));
     
    }
    error_log('record_country (f): '.date('d m Y H:i:s')); 
    
    /*sleep(30);
    /* 
     * record_country (i): 30 09 2014 09:35:19
     * record_country (f): 30 09 2014 09:35:33
     * send success      : 30 09 2014 09:35:35
     * 
    */
    /****** END - AUDIENCE *****/
    
    /****** AGE *****/
    
    /*$query = 'SELECT id_age id, min, max FROM `facebook_age_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;';
    $result = db_query($query, array());
    $total_age = $result->rowCount();
    $ages = $result->fetchAll(PDO::FETCH_ASSOC);
    $ages = json_encode($ages);
	
    $count = 1;
    error_log('record_country_age (i): '.date('d m Y H:i:s'));
    foreach ($countries as $country) {
        
        //Audiencia por edad (01:00 min)
        exec('php-cli '.$folder_path.'call_record_country_age.php '.$country['id'].' '.$country['code'].' '.$total_country.' '.urlencode($ages).' '.$total_age.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_country_age.log 2>&1 &');
        
        usleep(rand(0, 100000));
        
        if ($count++ % 125 == 0)
            sleep(30);
        
    }
    error_log('record_country_age (f): '.date('d m Y H:i:s'));
    
    /*sleep(30);
	
     /*
     * 
     * record_country_age (i): 30 09 2014 10:27:58
     * record_country_age (f): 30 09 2014 10:28:46
     * send success          : 30 09 2014 10:28:49
     * 
    */
	/****** END - AGE *****/
	
	/****** LANGUAGE *****/
    /*$query = "SELECT id_language id, key_language 'key' FROM `facebook_language_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;";
    $result = db_query($query, array());
    $total_language = $result->rowCount();
    $languages = $result->fetchAll(PDO::FETCH_ASSOC);
    $languages = json_encode($languages);
	
    $count = 1;
    error_log('record_country_language (i): '.date('d m Y H:i:s'));
    foreach ($countries as $country) {
        
        //Audiencia por idioma (06:00 min)
        exec('php-cli '.$folder_path.'call_record_country_language.php '.$country['id'].' '.$country['code'].' '.$total_country.' '.urlencode($languages).' '.$total_language.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_country_language.log 2>&1 &');
        
        usleep(rand(0, 100000));
      
        if ($count++ % 20 == 0)
            sleep(20);
        
    }
    error_log('record_country_language (f): '.date('d m Y H:i:s'));
    
    //sleep(30);
    
    /*
     * 
     * record_country_language (i): 30 09 2014 10:44:50
     * record_country_language (i): 30 09 2014 10:49:06
     * send success               : 30 09 2014 10:49:10
     *
    */
    /****** END - LANGUAGE *****/
	
	
	/****** RELATIONSHIP *****/
    /*$query = "SELECT id_relationship id, key_relationship 'key' FROM `facebook_relationship_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;"; 
    $result = db_query($query, array());
    $total_relationship = $result->rowCount();
    $relationships = $result->fetchAll(PDO::FETCH_ASSOC);
    $relationships = json_encode($relationships);
	
    $count = 1;
    error_log('record_country_relationship (i): '.date('d m Y H:i:s'));
    foreach ($countries as $country) {
        
        //Audiencia por situación sentimental (02:00 min)
        exec('php-cli '.$folder_path.'call_record_country_relationship.php '.$country['id'].' '.$country['code'].' '.$total_country.' '.urlencode($relationships).' '.$total_relationship.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_country_relationship.log 2>&1 &');
        
        usleep(rand(0, 100000));
        
        if ($count++ % 80 == 0)
            sleep(30);
        
    }
    error_log('record_country_relationship (f): '.date('d m Y H:i:s'));
    
    /*sleep(30);
    
    /*
     * 
     * record_country_relationship (i): 30 09 2014 11:26:05
     * record_country_relationship (f): 30 09 2014 11:27:57
     * send success                   : 30 09 2014 11:27:59
     *
    */
	/****** END - RELATIONSHIP *****/
	
	/****** GENERATION *****/
	/*$query = "SELECT id_generation id, key_generation 'key' FROM `facebook_generation_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;"; 
    $result = db_query($query, array());
    $total_generation = $result->rowCount();
    $generations = $result->fetchAll(PDO::FETCH_ASSOC);
    $generations = json_encode($generations);
	
	error_log('record_country_generation (i): '.date('d m Y H:i:s'));
    foreach ($countries as $country) {
        
        //Audiencia por generación (00:20 min)
        exec('php-cli '.$folder_path.'call_record_country_generation.php '.$country['id'].' '.$country['code'].' '.$total_country.' '.urlencode($generations).' '.$total_generation.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_country_generation.log 2>&1 &');
        
        usleep(rand(0, 100000));
        
    }
    error_log('record_country_generation (f): '.date('d m Y H:i:s'));
      
    /*
     * 
     * record_country_generation (i): 30 09 2014 11:38:40
     * record_country_generation (f): 30 09 2014 11:38:53
     * send success                 : 30 09 2014 11:38:55
     *
    */
	/****** END - GENERATION *****/
	
	/****** INTEREST *****/
    $query = "SELECT id_interest id, key_interest 'key' FROM `facebook_interest_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;";
    $result = db_query($query, array());
    $total_interest = $result->rowCount();
    $interests = $result->fetchAll(PDO::FETCH_ASSOC);
    $interests = json_encode($interests);
	
    $count = 1;
    error_log('record_country_interest (i): '.date('d m Y H:i:s'));
    foreach ($countries as $country) {
        
        //Audiencia por intereses (30:00 min)
        exec('php-cli '.$folder_path.'call_record_country_interest.php '.$country['id'].' '.$country['code'].' '.$total_country.' '.urlencode($interests).' '.$total_interest.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_country_interest.log 2>&1 &');
        
        usleep(rand(0, 100000));
        
        if ($count++ % 3 == 0)
            sleep(30);
        
    }
    error_log('record_country_interest (f): '.date('d m Y H:i:s'));
    
    
     /* 26 09 2014 09:36:57
     * 26 09 2014 10:01:44
     * 
     * 26 09 2014 12:17:30
     * 26 09 2014 12:42:13
     * 
     * sleep(30);
    
    error_log(date('d m Y H:i:s'));
    */
	/****** END - INTEREST *****/
	
	/****** COMPORTAMIENTO *****/
    /*$query = "SELECT id_comportamiento id, key_comportamiento 'key' FROM `facebook_comportamiento_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;";
    $result = db_query($query, array());
    $total_comportamiento = $result->rowCount();
    $comportamientos = $result->fetchAll(PDO::FETCH_ASSOC);
    $comportamientos = json_encode($comportamientos);
	
    $count = 1;
    error_log('record_country_comportamiento (i): '.date('d m Y H:i:s'));
    foreach ($countries as $country) {
        
        //Audiencia por comportamientos (12:00 min)
        exec('php-cli '.$folder_path.'call_record_country_comportamiento.php '.$country['id'].' '.$country['code'].' '.$total_country.' '.urlencode($comportamientos).' '.$total_comportamiento.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_country_comportamiento.log 2>&1 &');
        
        usleep(rand(0, 100000));
        
        if ($count++ % 10 == 0)
            sleep(30);
        
    }
    error_log('record_country_comportamiento (f): '.date('d m Y H:i:s'));
    
	/* 
     * 
     * 26 09 2014 10:52:11
     * 26 09 2014 11:04:23
     * 
     *
    */
   /****** END - COMPORTAMIENTO *****/ 