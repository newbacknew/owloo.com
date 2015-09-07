<?php
    
    die();

    require_once('../config.php');
    
    $datos = file_get_contents('https://graph.facebook.com/search?access_token=CAAI4BG12pyIBAA16bEgZAmUtevxgP5JwySOgDZCGiTB79xMYIZCe6AjFxApsmMoEZAZAOPZCaNKacYxBNr3hMtjQZAsvZBh5UGokQ9O33vy2yNqM83ILuTdaGU3jPkZBbmtDHzqPTWe7A0ZAv0CaFyuU6PvRhKfT1LZASA27QQGQZBNVHJmsgOH67EClOhZANTToeuiEcSSYWEPji83wwjYaKRPkdb04UlAM82EsZD&endpoint=/search&limit=500&locale=es_LA&method=get&pretty=0&type=adlocale');
        
    $datosarray = json_decode ($datos, true);
    
    //print_r($datosarray);
    
    for($i = 0; $i < count($datosarray['data']); $i++) {
        $nombre = $datosarray['data'][$i]['name'];
        $name = NULL;
        $key = $datosarray['data'][$i]['key'];
        if(isset($datosarray['data'][$i+1])){
            if($datosarray['data'][$i+1]['key'] == $key){
                $nombre = $datosarray['data'][$i+1]['name'];
                $name = $datosarray['data'][$i]['name'];
                $i++;
            }
        }
        
        $sql = "INSERT INTO facebook_language_3_1 VALUES(null, $1, '$2', ".(!empty($name)?"'$3'":"NULL").", 1, 1);";
        $res = db_query($sql, array($key, $nombre, $name), true);
        
    }
    
    echo 'FIN';
