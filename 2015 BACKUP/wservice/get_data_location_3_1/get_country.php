<?php

    die();
    
    require_once('../config.php');
    
    //UPDATE `facebook_country_3_1` c SET id_continent = (SELECT id_continent FROM country WHERE code = c.code) WHERE code in (SELECT code FROM country)

    $datos = get_url_content('https://graph.facebook.com/search?q=&type=adcountry&limit=1000');
        
    $datosarray = json_decode ($datos, true);
    
    //print_r($datosarray);
    
    foreach ($datosarray['data'] as $data) {
        $sql = "INSERT INTO facebook_country_3_1 VALUES(null, '$1', '', '$2', '', '', 0, 0, ".($data['supports_region']=='true'?1:0).", ".($data['supports_city']=='true'?1:0).", 1, 1);";
        $res = db_query($sql, array($data['country_code'], $data['name']), true);
    }
    
    $datos = get_url_content('https://graph.facebook.com/search?q=&type=adcountry&limit=1000&locale=es_LA');
        
    $datosarray = json_decode ($datos, true);
    
    foreach ($datosarray['data'] as $data) {
        $sql = "UPDATE facebook_country_3_1 SET nombre = '$1' WHERE code like '$2';";
        $res = db_query($sql, array($data['name'], $data['country_code']), true);
    }
    
    echo 'FIN';
