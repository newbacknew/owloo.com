<?php
    /*require_once('../config.php');
    
    $conexion_owloo = mysql_connect('localhost', MYSQL_DB_USER, MYSQL_DB_PASS) or die(mysql_error());
    mysql_select_db(MYSQL_DB_NAME, $conexion_owloo) or die(mysql_error());
    mysql_query('SET NAMES \'utf8\'');*/
    
    $alphas = range('a', 'z');
   
    
    /*$datos = file_get_contents('https://graph.facebook.com/search?q=&type=adregion&limit=1000');
    $datosarray = json_decode ($datos, true);
    
    foreach ($datosarray['data'] as $data) {
        $sql = "INSERT INTO facebook_region_3_1 VALUES(null, ".$data['key'].", '".mysql_real_escape_string($data['name'])."', (SELECT id_country FROM facebook_country_3_1 WHERE code LIKE '".$data['country_code']."'), 1, 1);";
        $res = mysql_query($sql) or die(mysql_error());
    }*/
    
    foreach ($alphas as $value) {
        $datos = file_get_contents("https://graph.facebook.com/search?q=".$value."&type=adgeolocation&location_types=['region']&limit=5000");
        $datosarray = json_decode ($datos, true);
        
        foreach ($datosarray['data'] as $data) {
            
            $query = 'SELECT id_region FROM facebook_region_3_1 WHERE region_key = '.$data['key'].';';
            $que = mysql_query($query);
            
            if(!$row = mysql_fetch_assoc($que)){
                $sql = "INSERT INTO facebook_region_3_1 VALUES(null, ".$data['key'].", '".mysql_real_escape_string($data['name'])."', (SELECT id_country FROM facebook_country_3_1 WHERE code LIKE '".$data['country_code']."'), 1, 1);";
                $res = mysql_query($sql) or die(mysql_error());
            }
        }
    }
    
    echo 'FIN';