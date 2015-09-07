<?php
    
    die();
    
    require_once('../config.php');
    
    $sql = "SELECT id_country, code FROM `facebook_country_3_1` WHERE `supports_city` = 1;";
    $que = db_query($sql, array());
    
    while($fila = mysql_fetch_assoc($que)){
        $datos = get_url_content('https://graph.facebook.com/search?q=b&type=adcity&country_list=["'.$fila['code'].'"]&limit=100');
        $datosarray = json_decode ($datos, true);
        
        foreach ($datosarray['data'] as $data) {
            $sql = "INSERT INTO facebook_city_3_1 VALUES(null, '$1', $2, '$3', $4,1, 1);";
            $res = db_query($sql, array($data['name'], $data['key'], $data['subtext'], $fila['id_country']), true);
        }
    }
