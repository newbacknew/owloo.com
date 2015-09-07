<?php
    require_once('../config.php');
    
    //https://graph.facebook.com/search?access_token=CAACZBzNhafycBAMfoXresPjHwQ2405oXmZBAS1y4dT1SZCQZAiFn6OknvEKOv9G4BoqzQpEx8gKHrXdlfeDO99FYDa7oHFC97EaYNBaQIhhZC15QvZBT26MIp70BSgcfzPIfTZC7rqu3H5kdO5uI2XvDsz8wH12jOnpgzZBCufh550xeZCl8BZBNksrSMmsxD8fkXcgZAM6lwMSR77gdUjgQxSL7vjABTBgqTQZD&class=demographics&endpoint=%2Fsearch&locale=es_LA&method=get&pretty=0&type=adTargetingCategory
    
    $datos = '[{"id":6002714401172,"name":"Baby boomers","description":"Personas nacidas durante el baby boom"},{"id":6016645577583,"name":"Generation X","description":"People who were born between 1961 and 1981"},{"id":6016645612183,"name":"Millennials","description":"People who were born between 1982 and 2004"}]';
    
    $datos_array = json_decode ($datos, true);
            
    //print_r($datos_array);
    
    foreach ($datos_array as $data) {
        /*
         $sql = "INSERT INTO facebook_generation_3_1 VALUES(null, ".$data['id'].", '".mysql_real_escape_string($data['name'])."', '".mysql_real_escape_string($data['description'])."', 1, 1);";
        $res = mysql_query($sql) or die(mysql_error());
          */
    }
    