<?php
    require_once('../config.php');
    
    /*$sql = "SELECT id_country, code FROM `facebook_country_3_1` WHERE `supports_city` = 1;";
    $que = mysql_query($sql) or die(mysql_error());
    
    while($fila = mysql_fetch_assoc($que)){
        $datos = file_get_contents('https://graph.facebook.com/search?q=b&type=adcity&country_list=["'.$fila['code'].'"]&limit=100');
        $datosarray = json_decode ($datos, true);
        
        foreach ($datosarray['data'] as $data) {
            $sql = "INSERT INTO facebook_city_3_1 VALUES(null, '".mysql_real_escape_string($data['name'])."', ".$data['key'].", '".mysql_real_escape_string($data['subtext'])."', ".$fila['id_country'].",1, 1);";
            $res = mysql_query($sql) or die(mysql_error());
        }
    }*/
