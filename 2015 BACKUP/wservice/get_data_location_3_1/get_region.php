<?php

    die();
    
    set_time_limit(0);

    require_once('../config.php');
    
    /*$datos = get_url_content('https://graph.facebook.com/search?q=&type=adregion&limit=1000');
    $datosarray = json_decode ($datos, true);
    
    foreach ($datosarray['data'] as $data) {
        $sql = "INSERT INTO facebook_region_3_1 VALUES(null, $1, '$2', (SELECT id_country FROM facebook_country_3_1 WHERE code LIKE '$3'), 1, 1);";
        $res = db_query($sql, array($data['key'], $data['name'], $data['country_code']), true);
    }*/
    
    $alphas = range('a', 'z');
    foreach ($alphas as $alpha1) {
        foreach ($alphas as $alpha2) {
            $datos = get_url_content("https://graph.facebook.com/search?q=".$alpha1.$alpha2."&type=adgeolocation&location_types=['region']&limit=5000&access_token=CAAI4BG12pyIBAKLB7z6AMfZBW8eqza1FSj1YHtHFBW1zZBVMa8ljKciclZAVpghaehZCZC6SrYHnM0ZCDpYXyUGRLJZCpE3UfD2jRBGxjNKBdjFFhvANmRCzkID02iayPJzSMuEeB0G4SeY5taGrNC22AgAWeKXcJY5NnK4nkUcoLkt0c6GZCCybuGcUSO2777BkUbuaYRHPYb97CKlfrb9p1KoOzNuLG9oZD");
            $datosarray = json_decode ($datos, true);
            foreach ($datosarray['data'] as $data) {
                
                $query = 'SELECT id_region FROM facebook_region_3_1 WHERE region_key = $1;';
                $que = db_query($query, array($data['key']));
                
                if(!$row = mysql_fetch_assoc($que)){
                    $sql = "INSERT INTO facebook_region_3_1 VALUES(null, $1, '$2', (SELECT id_country FROM facebook_country_3_1 WHERE code LIKE '$3'), 1, 1);";
                    $res = db_query($sql, array($data['key'], $data['name'], $data['country_code']), true);
                }
            }
        }
    }
    
    echo 'FIN';