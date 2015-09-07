<?php
    set_time_limit(0);
    
    if(!is_numeric($_SERVER['argv'][1]) && $_SERVER['argv'][2] != 'c45a5f3b2cfa74ac94bd5bbfb2c5d6a5'){
        die();
    }
    
    require_once('../config.php');
    
    $folder_path = '/home/owloo/public_html/wservice/country_3_1/';
    $log_path = '/home/owloo/public_html/wservice/country_3_1/logs/';
        
    //Audiencia por interes
    $query = "SELECT id_comportamiento, key_comportamiento FROM `facebook_comportamiento_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;"; 
    $que = mysql_query($query) or die(mysql_error());
    while($fila = mysql_fetch_assoc($que)){
        exec('php-cli '.$folder_path.'record_country_comportamiento.php '.$_SERVER['argv'][1].' '.$fila['id_comportamiento'].' '.$fila['key_comportamiento'].' c45a5f3b2cfa74ac94bd5bbfb2c5d6a5 > '.$log_path.'record_country_comportamiento.log 2>&1 &');   
    }
   
    