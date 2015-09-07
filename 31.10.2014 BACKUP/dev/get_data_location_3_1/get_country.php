<?php
    require_once('../config.php');
    
    //UPDATE `facebook_country_3_1` c SET id_continent = (SELECT id_continent FROM country WHERE code = c.code) WHERE code in (SELECT code FROM country)

    /*$datos = file_get_contents('https://graph.facebook.com/search?q=&type=adcountry&limit=1000');
        
    $datosarray = json_decode ($datos, true);
    
    //print_r($datosarray);
    
    foreach ($datosarray['data'] as $data) {
        $sql = "INSERT INTO facebook_country_3_1 VALUES(null, '".$data['country_code']."', '', '".mysql_real_escape_string($data['name'])."', '', 0, 0, ".($data['supports_region']=='true'?1:0).", ".($data['supports_city']=='true'?1:0).", 1, 1);";
        $res = mysql_query($sql) or die(mysql_error());
    }*/
    
    /*$datos = file_get_contents('https://graph.facebook.com/search?q=&type=adcountry&limit=1000&locale=es_LA');
        
    $datosarray = json_decode ($datos, true);
    
    foreach ($datosarray['data'] as $data) {
        $sql = "UPDATE facebook_country_3_1 SET nombre = '".mysql_real_escape_string($data['name'])."' WHERE code like '".$data['country_code']."';";
        $res = mysql_query($sql) or die(mysql_error());
    }*/
    
    echo 'FIN';
