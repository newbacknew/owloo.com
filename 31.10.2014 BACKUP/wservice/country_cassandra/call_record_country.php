<?php
    
    set_time_limit(0);

    require_once('../config.php');
    
    $folder_path = __DIR__.'/';
    $log_path = $folder_path.'logs/';
    
    $countries = cassandra_query('SELECT id_country, code FROM facebook_country WHERE active_fb_get_data = 1;');
    
    $total_country = count($countries);
    
    /*foreach ($countries as $country) {
        
        //Audiencia total (8 seg)
        exec('php-cli '.$folder_path.'record_country.php '.$country['id_country'].' '.$country['code'].' '.$total_country.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'record_country.log 2>&1 &');
        
    } sleep(30);
    
    $count = 1;

    foreach ($countries as $country) {
        
        //Audiencia por edad (1:02 min)
        exec('php-cli '.$folder_path.'call_record_country_age.php '.$country['id_country'].' '.$country['code'].' '.$total_country.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_country_age.log 2>&1 &');
        
        if ($count++ % 125 == 0)
            sleep(40);
        
    } sleep(40);*/
    
    $count = 1;
    
    foreach ($countries as $country) {
        
        //Audiencia por idioma (<3 min)
        exec('php-cli '.$folder_path.'call_record_country_language.php '.$country['id_country'].' '.$country['code'].' '.$total_country.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_country_language.log 2>&1 &');
        
        if ($count++ % 20 == 0)
            sleep(40);
        
    }
    
    /*foreach ($countries as $country) {
        
        //Audiencia por situación sentimental (<3 min)
        exec('php-cli '.$folder_path.'call_record_country_relationship.php '.$country['id_country'].' '.$country['code'].' '.$total_country.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_country_relationship.log 2>&1 &');
        
        usleep(rand(0, 500000));
        
    }
    
    foreach ($countries as $country) {
        
        //Audiencia por generación (<3 min)
        exec('php-cli '.$folder_path.'call_record_country_generation.php '.$country['id_country'].' '.$country['code'].' '.$total_country.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_country_generation.log 2>&1 &');
        
        usleep(rand(0, 500000));
        
    }
    
    foreach ($countries as $country) {
        
        //Audiencia por intereses
        exec('php-cli '.$folder_path.'call_record_country_interest.php '.$country['id_country'].' '.$country['code'].' '.$total_country.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_country_interest.log 2>&1 &');
        
        usleep(rand(0, 1000000));
        
    }
    
    foreach ($countries as $country) {
        
        //Audiencia por comportamientos
        exec('php-cli '.$folder_path.'call_record_country_comportamiento.php '.$country['id_country'].' '.$country['code'].' '.$total_country.' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'call_record_country_comportamiento.log 2>&1 &');
        
        usleep(100000);
        
    }*/