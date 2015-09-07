<?php
    
    set_time_limit(0);

    require_once('../config.php');
    
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'logs/';
    
    //Regiones
    $regions = array();
    $query = 'SELECT id_region, region_key, id_country FROM `facebook_region_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;'; 
    $que = db_query($query, array());
    
    while($fila = mysql_fetch_assoc($que)){
        $regions[] = array('id' => $fila['id_region'], 'code' => $fila['region_key'], 'id_country' => $fila['id_country']);
    }
    
    $total_region = count($regions);
    
    /***** REGION *****/
    
    error_log('record_region (i): '.date('d m Y H:i:s'));
    foreach ($regions as $region) {
        //Audiencia total (00:30 min)
        exec('php-cli '.$folder_path.'record_region.php '.$region['id'].' '.$region['code'].' '.$total_region.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'record_region.log 2>&1 &');
        usleep(rand(0, 100000));
    }
    error_log('record_region (f): '.date('d m Y H:i:s'));
    sleep(120);
    
    /***** END - REGION *****/
    
    /***** AGE *****/
    $query = 'SELECT id_age id, min, max FROM `facebook_age_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;';
    $result = db_query($query, array());
    $ages = array();
    while($fila = mysql_fetch_assoc($result)){
        $ages[] = $fila;
    }
    $total_age = count($ages);
    $ages = json_encode($ages);
    
    $count = 1;
    error_log('record_region_age (i): '.date('d m Y H:i:s'));
    foreach ($regions as $region) {
        //Audiencia por edad (00:00 min)
        exec('php-cli '.$folder_path.'call_record_region_age.php '.$region['id'].' '.$region['code'].' '.$total_region.' '.urlencode($ages).' '.$total_age.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_region_age.log 2>&1 &');
        usleep(rand(0, 100000));
        if ($count++ % 100 == 0)
            sleep(20);
    }
    error_log('record_region_age (f): '.date('d m Y H:i:s'));
    sleep(120);
    
    /***** END - AGE *****/
    
    /***** RELATIONSHIP *****/
    $query = "SELECT id_relationship id, key_relationship 'key' FROM `facebook_relationship_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;"; 
    $result = db_query($query, array());
    $relationships = array();
    while($fila = mysql_fetch_assoc($result)){
        $relationships[] = $fila;
    }
    $total_relationship = count($relationships);
    $relationships = json_encode($relationships);
    
    $count = 1;
    error_log('record_region_relationship (i): '.date('d m Y H:i:s'));
    foreach ($regions as $region) {
        //Audiencia por relaciones (00:00 min)
        exec('php-cli '.$folder_path.'call_record_region_relationship.php '.$region['id'].' '.$region['code'].' '.$total_region.' '.urlencode($relationships).' '.$total_relationship.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_region_relationship.log 2>&1 &');
        usleep(rand(0, 100000));
        if ($count++ % 70 == 0)
            sleep(20);
    }
    error_log('record_region_relationship (f): '.date('d m Y H:i:s'));
    sleep(120);
    
    /***** END - RELATIONSHIP *****/

    
    /***** INTEREST *****/
    $count = 1;
    error_log('record_region_interest (i): '.date('d m Y H:i:s'));
    foreach ($regions as $region) {
        
        $query = "SELECT id_interest id, key_interest 'key' FROM `facebook_interest_3_1` WHERE id_interest IN(SELECT id_interest FROM facebook_interest_city_3_1 WHERE id_country = $1) AND active_fb_get_data = 1 ORDER BY 1;";
        $result = db_query($query, array($region['id_country']));
        $interests = array();
        while($fila = mysql_fetch_assoc($result)){
            $interests[] = $fila;
        }
        $total_interest = count($interests);
        $interests = json_encode($interests);
        
        exec('php-cli '.$folder_path.'call_record_region_interest.php '.$region['id'].' '.$region['code'].' '.$region['id_country'].' '.$total_region.' '.urlencode($interests).' '.$total_interest.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_region_interest.log 2>&1 &');
        usleep(rand(0, 100000));
        if ($count++ % 100 == 0)
            sleep(20);
    }
    error_log('record_region_interest (f): '.date('d m Y H:i:s'));
    sleep(120);
    
    /***** END - INTEREST *****/
    
    /***** COMPORTAMIENTO *****/
    $query = "SELECT id_comportamiento id, key_comportamiento 'key' FROM `facebook_comportamiento_3_1` WHERE id_comportamiento IN(SELECT id_comportamiento FROM facebook_mobile_os_city_3_1) AND active_fb_get_data = 1 ORDER BY 1;"; 
    $result = db_query($query, array());
    $comportamientos = array();
    while($fila = mysql_fetch_assoc($result)){
        $comportamientos[] = $fila;
    }
    $total_comportamiento = count($comportamientos);
    $comportamientos = json_encode($comportamientos);
    
    $count = 1;
    error_log('record_region_comportamiento (i): '.date('d m Y H:i:s'));
    foreach ($regions as $region) {
        //Audiencia por comportamientos (00:00 min)
        exec('php-cli '.$folder_path.'call_record_region_comportamiento.php '.$region['id'].' '.$region['code'].' '.$total_region.' '.urlencode($comportamientos).' '.$total_comportamiento.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_region_comportamiento.log 2>&1 &');
        usleep(rand(0, 100000));
        if ($count++ % 100 == 0)
            sleep(20);
    }
    error_log('record_region_comportamiento (f): '.date('d m Y H:i:s'));
    
    /***** END - COMPORTAMIENTO *****/ 
    
    /*//Regiones
    $regions = array();
    $query = 'SELECT id_region, region_key, id_country FROM `facebook_region_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;'; 
    $que = db_query($query, array());
    
    while($fila = mysql_fetch_assoc($que)){
        $regions[] = array('id' => $fila['id_region'], 'code' => $fila['region_key'], 'id_country' => $fila['id_country']);
    }
    
    $total_region = count($regions);
    
    error_log('record_region (i): '.date('d m Y H:i:s'));
    foreach ($regions as $region) {
        //Audiencia total (00:30 min)
        exec('php-cli '.$folder_path.'record_region.php '.$region['id'].' '.$region['code'].' '.$total_region.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'record_region.log 2>&1 &');
        usleep(rand(0, 100000));
    }
    error_log('record_region (f): '.date('d m Y H:i:s'));
    sleep(120);
    
    $count = 1;
    error_log('record_region_age (i): '.date('d m Y H:i:s'));
    foreach ($regions as $region) {
        //Audiencia por edad (00:00 min)
        exec('php-cli '.$folder_path.'call_record_region_age.php '.$region['id'].' '.$region['code'].' '.$total_region.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_region_age.log 2>&1 &');
        usleep(rand(0, 100000));
        if ($count++ % 100 == 0)
            sleep(20);
    }
    error_log('record_region_age (f): '.date('d m Y H:i:s'));
    sleep(120);
    
    $count = 1;
    error_log('record_region_relationship (i): '.date('d m Y H:i:s'));
    foreach ($regions as $region) {
        //Audiencia por relaciones (00:00 min)
        exec('php-cli '.$folder_path.'call_record_region_relationship.php '.$region['id'].' '.$region['code'].' '.$total_region.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_region_relationship.log 2>&1 &');
        usleep(rand(0, 100000));
        if ($count++ % 70 == 0)
            sleep(20);
    }
    error_log('record_region_relationship (f): '.date('d m Y H:i:s'));
    sleep(120);
    
    $count = 1;
    error_log('record_region_interest (i): '.date('d m Y H:i:s'));
    foreach ($regions as $region) {
        //Audiencia por intereses (00:00 min)
        exec('php-cli '.$folder_path.'call_record_region_interest.php '.$region['id'].' '.$region['code'].' '.$region['id_country'].' '.$total_region.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_region_interest.log 2>&1 &');
        usleep(rand(0, 100000));
        if ($count++ % 100 == 0)
            sleep(20);
    }
    error_log('record_region_interest (f): '.date('d m Y H:i:s'));
    sleep(120);
    
    $count = 1;
    error_log('record_region_comportamiento (i): '.date('d m Y H:i:s'));
    foreach ($regions as $region) {
        //Audiencia por comportamientos (00:00 min)
        exec('php-cli '.$folder_path.'call_record_region_comportamiento.php '.$region['id'].' '.$region['code'].' '.$total_region.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_region_comportamiento.log 2>&1 &');
        usleep(rand(0, 100000));
        if ($count++ % 100 == 0)
            sleep(20);
    }
    error_log('record_region_comportamiento (f): '.date('d m Y H:i:s'));*/
   